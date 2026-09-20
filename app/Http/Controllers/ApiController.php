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
            
            // Only Petani (or KTD for backward compatibility) users can use the mobile app
            if (!in_array($user->role, ['petani', 'ktd'])) {
                return response()->json(['error' => 'Akses ditolak. Aplikasi mobile hanya untuk Petani atau Kelompok Tani.'], 403);
            }

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

        if ($user->role === 'petani' && $user->kelompok_tani_id) {
            $ktd_id = $user->kelompok_tani_id;
        } else {
            // Backward compatibility for old 'ktd' role accounts
            $ktd = \App\Models\KelompokTani::where('name', $user->name)
                                            ->where('wilayah_id', $user->wilayah_id)
                                            ->first();
            
            if (!$ktd) {
                return response()->json(['data' => []]);
            }
            $ktd_id = $ktd->id;
        }

        $lahans = \App\Models\Lahan::where('kelompok_tani_id', $ktd_id)->get();

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
        $request->validate([
            'lahan_id' => 'required|exists:lahans,id',
            'penyakit' => 'required|string',
            'akurasi' => 'nullable|numeric',
            'tindakan' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $foto_path = null;
        if ($request->hasFile('foto')) {
            // Save to storage/app/public/scans
            $foto_path = $request->file('foto')->store('scans', 'public');
        }

        // Cek status aktif tandur
        $tandur = \App\Models\Tandur::where('lahan_id', $request->lahan_id)
                                    ->where('status_aktif', true)
                                    ->latest()
                                    ->first();
        
        if (!$tandur) {
            return response()->json(['error' => 'Data padi tidak ditemukan atau lahan sedang tidak dalam masa tanam aktif.'], 403);
        }

        // Hitung HST
        $hst = $tandur->tanggal_tanam->diffInDays(now());

        $scan = \App\Models\RiwayatScan::create([
            'user_id' => $request->user()->id,
            'lahan_id' => $request->lahan_id,
            'penyakit' => $request->penyakit,
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
        
        // Ambil riwayat milik KTD tersebut, diurutkan dari yang terbaru
        $history = \App\Models\RiwayatScan::with('lahan')
                                          ->where('user_id', $user->id)
                                          ->latest()
                                          ->get();

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
