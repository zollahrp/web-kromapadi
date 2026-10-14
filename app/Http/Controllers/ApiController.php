<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // Petani, KTD, Master Wilayah (Penyuluh), dan Super Admin diizinkan login ke mobile
            if (!in_array($user->role, ['petani', 'ktd', 'master_wilayah', 'penyuluh', 'super_admin'])) {
                return response()->json(['error' => 'Akses ditolak. Peran pengguna tidak diizinkan di aplikasi mobile.'], 403);
            }

            $user->load(['wilayah', 'kelompokTani']);

            $token = $user->createToken('mobile-app-token')->plainTextToken;

            return response()->json([
                'token' => $token,
                'user' => $user,
            ]);
        }

        return response()->json(['error' => 'Kredensial tidak valid'], 401);
    }

    public function getLahan(Request $request)
    {
        $user = $request->user();

        if (in_array($user->role, ['master_wilayah', 'penyuluh'])) {
            $lahans = collect();
            if ($user->wilayah_id) {
                $ktd_ids = \App\Models\KelompokTani::where('wilayah_id', $user->wilayah_id)->pluck('id');
                $lahans = \App\Models\Lahan::whereIn('kelompok_tani_id', $ktd_ids)->get();
            }
            if ($lahans->isEmpty()) {
                // Fallback ke semua lahan agar Penyuluh selalu dapat memonitor lahan yang ada
                $lahans = \App\Models\Lahan::all();
            }
        } elseif ($user->role === 'super_admin') {
            $lahans = \App\Models\Lahan::all();
        } elseif ($user->role === 'petani' && $user->kelompok_tani_id) {
            $lahans = \App\Models\Lahan::where('kelompok_tani_id', $user->kelompok_tani_id)->get();
            if ($lahans->isEmpty()) {
                $lahans = \App\Models\Lahan::all();
            }
        } else {
            // Backward compatibility for old 'ktd' role accounts
            $ktd = \App\Models\KelompokTani::where('name', $user->name)
                                            ->where('wilayah_id', $user->wilayah_id)
                                            ->first();
            
            if (!$ktd) {
                $lahans = $user->wilayah_id
                    ? \App\Models\Lahan::whereIn('kelompok_tani_id', \App\Models\KelompokTani::where('wilayah_id', $user->wilayah_id)->pluck('id'))->get()
                    : collect();
            } else {
                $lahans = \App\Models\Lahan::where('kelompok_tani_id', $ktd->id)->get();
            }
            if ($lahans->isEmpty()) {
                $lahans = \App\Models\Lahan::all();
            }
        }

        // Attach HST and active status from Tandur
        $lahans = $lahans->map(function ($lahan) {
            $tandur = \App\Models\Tandur::where('lahan_id', $lahan->id)
                                        ->where('status_aktif', true)
                                        ->latest()
                                        ->first();
            
            $lahan->is_aktif = $tandur ? true : false;
            if ($tandur) {
                $lahan->tanggal_tanam = $tandur->tanggal_tanam->format('Y-m-d');
                $lahan->hst = $tandur->tanggal_tanam->diffInDays(now());
            } else {
                $lahan->tanggal_tanam = null;
                $lahan->hst = null;
            }
            return $lahan;
        });

        return response()->json(['data' => $lahans]);
    }

    public function scan(Request $request)
    {
        // Jika lahan_id kosong atau bernilai 0 / tidak ditemukan, fallback ke lahan pertama yang valid
        if (!$request->lahan_id || !\App\Models\Lahan::where('id', $request->lahan_id)->exists()) {
            $firstLahan = \App\Models\Lahan::first();
            if ($firstLahan) {
                $request->merge(['lahan_id' => $firstLahan->id]);
            }
        }

        $request->validate([
            'lahan_id' => 'required|exists:lahans,id',
            'penyakit' => 'required|string',
            'akurasi' => 'nullable|numeric',
            'tindakan' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $foto_path = null;
        if ($request->hasFile('foto')) {
            $foto_path = $request->file('foto')->store('scans', 'public');
        }

        // Cek status aktif tandur
        $tandur = \App\Models\Tandur::where('lahan_id', $request->lahan_id)
                                    ->where('status_aktif', true)
                                    ->latest()
                                    ->first();
        
        if (!$tandur) {
            $lahan = \App\Models\Lahan::find($request->lahan_id);
            if ($lahan) {
                $tandur = \App\Models\Tandur::create([
                    'kelompok_tani_id' => $lahan->kelompok_tani_id,
                    'lahan_id' => $lahan->id,
                    'tanggal_tanam' => now()->subDays(30),
                    'status_aktif' => true,
                ]);
            }
        }

        $scan = \App\Models\RiwayatScan::create([
            'user_id' => $request->user()->id,
            'lahan_id' => $request->lahan_id,
            'penyakit' => $request->penyakit,
            'bwd_value' => $request->bwd_value,
            'bwd_status' => $request->bwd_status,
            'akurasi' => $request->akurasi,
            'tindakan' => $request->tindakan,
            'foto_path' => $foto_path
        ]);

        return response()->json([
            'message' => 'Riwayat scan berhasil dicatat', 
            'data' => $scan
        ]);
    }

    public function history(Request $request)
    {
        $user = $request->user();
        
        $query = \App\Models\RiwayatScan::with('lahan');

        if (in_array($user->role, ['master_wilayah', 'penyuluh'])) {
            if ($user->wilayah_id) {
                $ktd_ids = \App\Models\KelompokTani::where('wilayah_id', $user->wilayah_id)->pluck('id');
                $lahan_ids = \App\Models\Lahan::whereIn('kelompok_tani_id', $ktd_ids)->pluck('id');
                $query->where(function ($q) use ($user, $lahan_ids) {
                    $q->where('user_id', $user->id)
                      ->orWhereIn('lahan_id', $lahan_ids);
                });
            } else {
                $query->where('user_id', $user->id);
            }
        } elseif ($user->role === 'super_admin') {
            // super_admin sees all
        } else {
            $query->where('user_id', $user->id);
        }

        $history = $query->latest()->get();

        return response()->json(['data' => $history]);
    }

    public function getWilayah(Request $request)
    {
        // Get all wilayah or just the user's wilayah
        $wilayahs = \App\Models\Wilayah::all();
        return response()->json(['data' => $wilayahs]);
    }

    public function getPetani(Request $request)
    {
        $user = $request->user();

        if (in_array($user->role, ['master_wilayah', 'penyuluh'])) {
            if ($user->wilayah_id) {
                $ktd_ids = \App\Models\KelompokTani::where('wilayah_id', $user->wilayah_id)->pluck('id');
                $petanis = \App\Models\User::where('role', 'petani')
                                           ->whereIn('kelompok_tani_id', $ktd_ids)
                                           ->get();
                return response()->json(['data' => $petanis]);
            }
        }
        
        $ktd_id = $user->kelompok_tani_id;
        if (!$ktd_id) {
            $ktd = \App\Models\KelompokTani::where('name', $user->name)
                                            ->where('wilayah_id', $user->wilayah_id)
                                            ->first();
            if ($ktd) $ktd_id = $ktd->id;
        }

        if (!$ktd_id) {
             return response()->json(['data' => []]);
        }

        // Return all petani in the same KTD
        $petanis = \App\Models\User::where('role', 'petani')
                                   ->where('kelompok_tani_id', $ktd_id)
                                   ->get();
                                   
        return response()->json(['data' => $petanis]);
    }
}