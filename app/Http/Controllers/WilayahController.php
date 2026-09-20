<?php

namespace App\Http\Controllers;

use App\Models\Wilayah;
use Illuminate\Http\Request;

class WilayahController extends Controller
{
    public function __construct()
    {
        // Hanya super_admin yang bisa akses wilayah controller (secara keseluruhan jika diinginkan, tapi kita restrict metode modify)
    }

    public function index(Request $request)
    {
        if (auth()->user()->role !== 'super_admin') {
            abort(403, 'Hanya Super Admin yang dapat mengakses halaman ini.');
        }

        $query = Wilayah::query();
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }
        
        if ($request->filled('provinsi')) {
            $query->where('provinsi', $request->provinsi);
        }

        $wilayahs = $query->latest()->get();
        $provinsis = Wilayah::select('provinsi')->whereNotNull('provinsi')->where('provinsi', '!=', '')->distinct()->pluck('provinsi');

        return view('admin.wilayah.index', compact('wilayahs', 'provinsis'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'super_admin') {
            abort(403, 'Hanya Super Admin yang dapat menambahkan wilayah.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'provinsi' => 'nullable|string|max:255',
            'kota' => 'nullable|string|max:255',
            'kecamatan' => 'nullable|string|max:255',
            'kelurahan' => 'nullable|string|max:255',
        ]);

        Wilayah::create([
            'name' => $request->name,
            'provinsi' => $request->provinsi,
            'kota' => $request->kota,
            'kecamatan' => $request->kecamatan,
            'kelurahan' => $request->kelurahan,
        ]);

        return back()->with('success', 'Wilayah berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        if (auth()->user()->role !== 'super_admin') {
            abort(403, 'Hanya Super Admin yang dapat mengubah wilayah.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'provinsi' => 'nullable|string|max:255',
            'kota' => 'nullable|string|max:255',
            'kecamatan' => 'nullable|string|max:255',
            'kelurahan' => 'nullable|string|max:255',
        ]);

        $wilayah = Wilayah::findOrFail($id);
        $wilayah->update([
            'name' => $request->name,
            'provinsi' => $request->provinsi,
            'kota' => $request->kota,
            'kecamatan' => $request->kecamatan,
            'kelurahan' => $request->kelurahan,
        ]);

        return back()->with('success', 'Wilayah berhasil diperbarui!');
    }

    public function destroy($id)
    {
        if (auth()->user()->role !== 'super_admin') {
            abort(403, 'Hanya Super Admin yang dapat menghapus wilayah.');
        }

        $wilayah = Wilayah::findOrFail($id);
        $wilayah->delete();

        return back()->with('success', 'Wilayah berhasil dihapus!');
    }
}
