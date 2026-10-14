<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wilayah;
use App\Models\KelompokTani;
use App\Models\Lahan;
use App\Models\RiwayatScan;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $wilayahCount = Wilayah::count();
        $kelompokTaniCount = KelompokTani::count();
        $lahanCount = Lahan::count();
        
        $totalScanBulanIni = RiwayatScan::whereMonth('created_at', Carbon::now()->month)
                                        ->whereYear('created_at', Carbon::now()->year)
                                        ->count();
                                        
        $totalRiwayatScan = RiwayatScan::count();

        // Riwayat Terbaru
        $riwayatTerbaru = RiwayatScan::with(['lahan.kelompokTani', 'user'])
                                     ->latest()
                                     ->take(5)
                                     ->get();

        // Statistik Scan 7 Hari Terakhir
        $scan7HariTerakhir = [];
        $labels7Hari = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels7Hari[] = $date->translatedFormat('d M');
            $scan7HariTerakhir[] = RiwayatScan::whereDate('created_at', $date->toDateString())->count();
        }

        // Distribusi Hasil Scan
        $sehatCount = RiwayatScan::where('penyakit', 'Normal')->orWhere('penyakit', 'Sehat')->count();
        $terdeteksiOptCount = RiwayatScan::whereNotIn('penyakit', ['Normal', 'Sehat', 'Tidak Terdeteksi', ''])->whereNotNull('penyakit')->count();
        $tidakTerdeteksiCount = RiwayatScan::where('penyakit', 'Tidak Terdeteksi')->orWhereNull('penyakit')->orWhere('penyakit', '')->count();
        // Since we don't have "Perlu Perhatian" clearly defined in data yet, maybe just group them
        $perluPerhatianCount = 0; // Or define some logic if there's a specific 'penyakit'

        // Prepare data for Donut Chart
        $distribusiScan = [
            'sehat' => $sehatCount,
            'perlu_perhatian' => $perluPerhatianCount,
            'terdeteksi_opt' => $terdeteksiOptCount,
            'tidak_terdeteksi' => $tidakTerdeteksiCount,
            'total' => $totalRiwayatScan > 0 ? $totalRiwayatScan : 1 // prevent div by zero
        ];

        // Aktivitas Mendatang (just dummy logic for now as we don't have event models, or we can use Tandur if there's date)
        $aktivitasMendatang = [];

        return view('admin.dashboard', compact(
            'wilayahCount',
            'kelompokTaniCount',
            'lahanCount',
            'totalScanBulanIni',
            'totalRiwayatScan',
            'riwayatTerbaru',
            'scan7HariTerakhir',
            'labels7Hari',
            'distribusiScan',
            'aktivitasMendatang'
        ));
    }
}
