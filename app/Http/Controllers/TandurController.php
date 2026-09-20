<?php

namespace App\Http\Controllers;

use App\Models\Tandur;
use App\Models\KelompokTani;
use App\Models\Lahan;
use Illuminate\Http\Request;

class TandurController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        if (!in_array($user->role, ['super_admin', 'master_wilayah'])) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $query = Tandur::with(['kelompokTani', 'lahan']);

        if ($user->role === 'master_wilayah') {
            $query->whereHas('kelompokTani', function($q) use ($user) {
                $q->where('wilayah_id', $user->wilayah_id);
            });
            $ktds = KelompokTani::where('wilayah_id', $user->wilayah_id)->get();
            $lahans = Lahan::whereIn('kelompok_tani_id', $ktds->pluck('id'))->get();
        } else {
            $ktds = KelompokTani::all();
            $lahans = Lahan::all();
        }
        
        // Filter Pencarian (Cari berdasarkan nama KTD atau Lahan)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('kelompokTani', function($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('lahan', function($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%");
                });
            });
        }
        
        // Filter by KTD
        if ($request->filled('ktd_id')) {
            $query->where('kelompok_tani_id', $request->ktd_id);
        }

        $tandurs = $query->latest()->get();

        return view('admin.tandur.index', compact('tandurs', 'ktds', 'lahans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kelompok_tani_id' => 'required|exists:kelompok_tanis,id',
            'lahan_id' => 'required|exists:lahans,id',
            'tanggal_tanam' => 'required|date',
            'status_aktif' => 'boolean',
        ]);

        Tandur::create([
            'kelompok_tani_id' => $request->kelompok_tani_id,
            'lahan_id' => $request->lahan_id,
            'tanggal_tanam' => $request->tanggal_tanam,
            'status_aktif' => $request->status_aktif ?? true,
        ]);

        return back()->with('success', 'Data Tandur berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kelompok_tani_id' => 'required|exists:kelompok_tanis,id',
            'lahan_id' => 'required|exists:lahans,id',
            'tanggal_tanam' => 'required|date',
            'status_aktif' => 'boolean',
        ]);

        $tandur = Tandur::findOrFail($id);
        $tandur->update([
            'kelompok_tani_id' => $request->kelompok_tani_id,
            'lahan_id' => $request->lahan_id,
            'tanggal_tanam' => $request->tanggal_tanam,
            'status_aktif' => $request->has('status_aktif') ? $request->status_aktif : false,
        ]);

        return back()->with('success', 'Data Tandur berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $tandur = Tandur::findOrFail($id);
        $tandur->delete();

        return back()->with('success', 'Data Tandur berhasil dihapus!');
    }
}
