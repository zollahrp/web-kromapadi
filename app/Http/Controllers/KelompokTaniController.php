<?php

namespace App\Http\Controllers;

use App\Models\KelompokTani;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class KelompokTaniController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // Hanya super_admin atau master_wilayah yang boleh mengakses
        if (!in_array($user->role, ['super_admin', 'master_wilayah'])) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $query = KelompokTani::query();

        $wilayahs = null;
        // Jika master_wilayah, hanya ambil KTD di wilayahnya
        if ($user->role === 'master_wilayah') {
            if (!$user->wilayah_id) {
                abort(403, 'Anda belum ditetapkan ke wilayah manapun.');
            }
            $query->with(['wilayah', 'masterAdmin'])->where('wilayah_id', $user->wilayah_id);
        } else {
            // super_admin bisa melihat semua KTD dan perlu list wilayah untuk tambah KTD
            $query->with(['wilayah', 'masterAdmin']);
            $wilayahs = \App\Models\Wilayah::all();
            
            if ($request->filled('wilayah_id')) {
                $query->where('wilayah_id', $request->wilayah_id);
            }
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nama_ketua', 'like', "%{$search}%");
            });
        }

        $ktds = $query->latest()->get();

        return view('admin.ktd.index', compact('ktds', 'wilayahs'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        
        if (!in_array($user->role, ['super_admin', 'master_wilayah'])) {
            abort(403, 'Hanya Super Admin atau Master Wilayah yang dapat membuat akun Kelompok Tani Desa.');
        }

        $wilayah_id = $user->role === 'master_wilayah' ? $user->wilayah_id : $request->wilayah_id;

        if (!$wilayah_id) {
            return back()->withErrors(['error' => 'Harap tentukan atau miliki wilayah untuk Kelompok Tani.']);
        }

        $rules = [
            'name' => 'required|string|max:255',
            'nama_ketua' => 'required|string|max:255',
            'lokasi_long' => 'required|string|max:255',
            'lokasi_lat' => 'required|string|max:255',
            'alamat' => 'required|string',
        ];

        if ($user->role === 'super_admin') {
            $rules['wilayah_id'] = 'required|exists:wilayahs,id';
        }

        $request->validate($rules);

        // (KTD tidak lagi dibuatkan akun User)

        KelompokTani::create([
            'name' => $request->name,
            'wilayah_id' => $wilayah_id,
            'master_admin_id' => $user->id,
            'nama_ketua' => $request->nama_ketua,
            'lokasi_long' => $request->lokasi_long,
            'lokasi_lat' => $request->lokasi_lat,
            'alamat' => $request->alamat
        ]);

        return back()->with('success', 'Kelompok Tani Desa berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        
        if (!in_array($user->role, ['super_admin', 'master_wilayah'])) {
            abort(403, 'Hanya Super Admin atau Master Wilayah yang dapat mengubah KTD.');
        }

        if ($user->role === 'master_wilayah') {
            $ktd = KelompokTani::where('wilayah_id', $user->wilayah_id)->findOrFail($id);
        } else {
            $ktd = KelompokTani::findOrFail($id);
        }

        $rules = [
            'name' => 'required|string|max:255',
            'nama_ketua' => 'required|string|max:255',
            'lokasi_long' => 'required|string|max:255',
            'lokasi_lat' => 'required|string|max:255',
            'alamat' => 'required|string',
        ];

        if ($user->role === 'super_admin' && $request->has('wilayah_id')) {
            $rules['wilayah_id'] = 'required|exists:wilayahs,id';
        }

        $request->validate($rules);

        $updateData = [
            'name' => $request->name,
            'nama_ketua' => $request->nama_ketua,
            'lokasi_long' => $request->lokasi_long,
            'lokasi_lat' => $request->lokasi_lat,
            'alamat' => $request->alamat
        ];

        if ($user->role === 'super_admin' && $request->has('wilayah_id')) {
            $updateData['wilayah_id'] = $request->wilayah_id;
        }

        $ktd->update($updateData);

        return back()->with('success', 'Kelompok Tani Desa berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = auth()->user();
        
        if (!in_array($user->role, ['super_admin', 'master_wilayah'])) {
            abort(403, 'Hanya Super Admin atau Master Wilayah yang dapat menghapus KTD.');
        }

        if ($user->role === 'master_wilayah') {
            $ktd = KelompokTani::where('wilayah_id', $user->wilayah_id)->findOrFail($id);
        } else {
            $ktd = KelompokTani::findOrFail($id);
        }
        
        $ktd->delete();

        return back()->with('success', 'Kelompok Tani Desa berhasil dihapus!');
    }
}
