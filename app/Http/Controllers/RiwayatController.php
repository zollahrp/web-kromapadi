<?php

namespace App\Http\Controllers;

use App\Models\RiwayatScan;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        if (!in_array($user->role, ['super_admin', 'master_wilayah'])) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $query = RiwayatScan::with(['user', 'lahan.kelompokTani']);

        if ($user->role === 'master_wilayah') {
            $query->whereHas('user', function ($q) use ($user) {
                $q->where('wilayah_id', $user->wilayah_id);
            });
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('penyakit', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('lahan', function($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $riwayats = $query->latest()->get();

        return view('admin.riwayat.index', compact('riwayats'));
    }
}
