<?php

namespace App\Http\Controllers;

use App\Models\RiwayatScan;
use App\Models\KelompokTani;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        if (!in_array($user->role, ['super_admin', 'master_wilayah'])) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        // Ambil filter dari request
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->toDateString());
        $ktdId = $request->input('ktd_id');

        // Query base untuk RiwayatScan
        $query = RiwayatScan::with(['user', 'lahan.kelompokTani'])
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        // Filter berdasarkan peran Master Wilayah
        if ($user->role === 'master_wilayah') {
            $query->whereHas('user', function ($q) use ($user) {
                $q->where('wilayah_id', $user->wilayah_id);
            });
            
            // Ambil daftar KTD di wilayah ini untuk dropdown filter
            $ktdList = KelompokTani::where('wilayah_id', $user->wilayah_id)->get();
        } else {
            // Super admin melihat semua KTD
            $ktdList = KelompokTani::all();
        }

        // Filter tambahan jika admin memilih spesifik KTD
        if ($ktdId) {
            // Karena RiwayatScan terhubung ke KTD melalui user->name == kelompokTani->name,
            // atau lewat lahan->kelompok_tani_id. Kita filter dari lahan_id nya.
            $query->whereHas('lahan', function($q) use ($ktdId) {
                $q->where('kelompok_tani_id', $ktdId);
            });
        }

        $riwayats = $query->latest()->get();

        // Hitung Statistik
        $totalScan = $riwayats->count();
        
        // Cari Penyakit Terbanyak
        $penyakitTerbanyak = '-';
        $ktdTeraktif = '-';

        if ($totalScan > 0) {
            $penyakitTerbanyak = $riwayats->groupBy('penyakit')
                ->map->count()
                ->sortDesc()
                ->keys()
                ->first();

            $ktdTeraktifData = $riwayats->groupBy(function($item) {
                return $item->user ? $item->user->name : 'Unknown';
            })
                ->map->count()
                ->sortDesc()
                ->keys()
                ->first();
                
            $ktdTeraktif = $ktdTeraktifData;
        }

        return view('admin.laporan.index', compact(
            'riwayats', 
            'ktdList', 
            'startDate', 
            'endDate', 
            'ktdId',
            'totalScan',
            'penyakitTerbanyak',
            'ktdTeraktif'
        ));
    }
}
