<?php

namespace App\Http\Controllers;

use App\Models\Lahan;
use App\Models\KelompokTani;
use Illuminate\Http\Request;

class LahanController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        if (!in_array($user->role, ['super_admin', 'master_wilayah'])) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $query = Lahan::with(['kelompokTani', 'uploader']);

        if ($user->role === 'master_wilayah') {
            $query->where('uploaded_by', $user->id);
            $ktds = KelompokTani::where('wilayah_id', $user->wilayah_id)->get();
        } else {
            $ktds = KelompokTani::all();
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('kelompokTani', function($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        if ($request->filled('klasifikasi')) {
            $query->where('klasifikasi', $request->klasifikasi);
        }

        $lahans = $query->latest()->get();
        $klasifikasis = Lahan::select('klasifikasi')->whereNotNull('klasifikasi')->where('klasifikasi', '!=', '')->distinct()->pluck('klasifikasi');

        return view('admin.lahan.index', compact('lahans', 'ktds', 'klasifikasis'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        
        if ($user->role !== 'master_wilayah') {
            abort(403, 'Hanya Master Wilayah yang dapat menambahkan data Lahan.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'kelompok_tani_id' => 'required|exists:kelompok_tanis,id',
            'klasifikasi' => 'nullable|string|max:255',
        ]);

        // Verifikasi bahwa kelompok_tani_id yang dipilih benar-benar ada di wilayah master ini
        $ktd = KelompokTani::findOrFail($request->kelompok_tani_id);
        if ($ktd->wilayah_id !== $user->wilayah_id) {
            abort(403, 'Anda tidak dapat menambahkan lahan untuk KTD di luar wilayah Anda.');
        }

        Lahan::create([
            'name' => $request->name,
            'kelompok_tani_id' => $request->kelompok_tani_id,
            'klasifikasi' => $request->klasifikasi,
            'uploaded_by' => $user->id
        ]);

        return back()->with('success', 'Data Lahan berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        if ($user->role !== 'master_wilayah') {
            abort(403, 'Hanya Master Wilayah yang dapat mengubah Lahan.');
        }

        $lahan = Lahan::where('uploaded_by', $user->id)->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'kelompok_tani_id' => 'required|exists:kelompok_tanis,id',
            'klasifikasi' => 'nullable|string|max:255',
        ]);

        $ktd = KelompokTani::findOrFail($request->kelompok_tani_id);
        if ($ktd->wilayah_id !== $user->wilayah_id) {
            abort(403, 'Anda tidak dapat memindahkan lahan ke KTD di luar wilayah Anda.');
        }

        $lahan->update([
            'name' => $request->name,
            'kelompok_tani_id' => $request->kelompok_tani_id,
            'klasifikasi' => $request->klasifikasi,
        ]);

        return back()->with('success', 'Data Lahan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = auth()->user();
        if ($user->role !== 'master_wilayah') {
            abort(403, 'Hanya Master Wilayah yang dapat menghapus Lahan.');
        }

        $lahan = Lahan::where('uploaded_by', $user->id)->findOrFail($id);
        $lahan->delete();

        return back()->with('success', 'Data Lahan berhasil dihapus!');
    }
}
