@extends('layouts.admin')

@section('title', 'Dashboard - PadiNexa')

@section('content')
<!-- Header Area -->
<div class="flex justify-between items-end mb-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 mb-1 flex items-center gap-2">
            Selamat pagi, {{ explode(' ', auth()->user()->name)[0] }} <span class="text-2xl">👋</span>
        </h1>
        <p class="text-gray-500 text-sm">Berikut ringkasan aktivitas di wilayah {{ auth()->user()->wilayah ? auth()->user()->wilayah->name : 'Mulyaharja' }} hari ini.</p>
    </div>
    <button class="bg-[#5C52E7] hover:bg-[#4d44c9] text-white px-5 py-2.5 rounded-xl text-sm font-medium transition-colors shadow-sm flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Tambah Data
        <svg class="w-4 h-4 ml-1 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
    </button>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-4 mb-8">
    
    <!-- Card 1 -->
    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-[#F0EFFF] text-[#5C52E7] flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
        </div>
        <div>
            <p class="text-xs font-medium text-gray-500 mb-0.5">Wilayah Aktif</p>
            <h3 class="text-xl font-bold text-gray-900">{{ $wilayahCount }}</h3>
            <p class="text-[10px] text-gray-400 mt-0.5">{{ auth()->user()->wilayah ? auth()->user()->wilayah->name : 'Total wilayah' }}</p>
        </div>
    </div>

    <!-- Card 2 -->
    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] flex items-center gap-4 border-b-4 border-b-green-400">
        <div class="w-12 h-12 rounded-xl bg-[#E8F8F1] text-[#22C55E] flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        </div>
        <div>
            <p class="text-xs font-medium text-gray-500 mb-0.5">Kelompok Tani</p>
            <h3 class="text-xl font-bold text-gray-900">{{ $kelompokTaniCount }}</h3>
            <p class="text-[10px] text-gray-400 mt-0.5">Total terdaftar</p>
        </div>
    </div>

    <!-- Card 3 -->
    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] flex items-center gap-4 border-b-4 border-b-yellow-400">
        <div class="w-12 h-12 rounded-xl bg-[#FEF9E6] text-[#EAB308] flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
        </div>
        <div>
            <p class="text-xs font-medium text-gray-500 mb-0.5">Lahan Terdaftar</p>
            <h3 class="text-xl font-bold text-gray-900">{{ $lahanCount }}</h3>
            <p class="text-[10px] text-gray-400 mt-0.5">Total lahan</p>
        </div>
    </div>

    <!-- Card 4 -->
    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-[#FEE2E2] text-[#EF4444] flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
        </div>
        <div>
            <p class="text-xs font-medium text-gray-500 mb-0.5">Total Scan</p>
            <h3 class="text-xl font-bold text-gray-900">{{ $totalScanBulanIni }}</h3>
            <p class="text-[10px] text-gray-400 mt-0.5">Bulan ini</p>
        </div>
    </div>

    <!-- Card 5 -->
    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-[#E0F2FE] text-[#0EA5E9] flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div>
            <p class="text-xs font-medium text-gray-500 mb-0.5">Riwayat Scan</p>
            <h3 class="text-xl font-bold text-gray-900">{{ $totalRiwayatScan }}</h3>
            <p class="text-[10px] text-gray-400 mt-0.5">Total riwayat</p>
        </div>
    </div>
</div>

<!-- Main Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    
    <!-- Riwayat Scan Terbaru (Spans 2 columns on lg) -->
    <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] overflow-hidden flex flex-col">
        <div class="px-6 py-5 border-b border-gray-50 flex justify-between items-center">
            <h2 class="text-base font-bold text-gray-900">Riwayat Scan Terbaru</h2>
            <a href="#" class="text-sm font-medium text-[#5C52E7] hover:underline flex items-center gap-1">
                Lihat Semua
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>
        <div class="overflow-x-auto flex-1">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-xs text-gray-500 border-b border-gray-50">
                        <th class="px-6 py-4 font-semibold">Tanggal</th>
                        <th class="px-6 py-4 font-semibold">Kelompok Tani</th>
                        <th class="px-6 py-4 font-semibold">Lahan</th>
                        <th class="px-6 py-4 font-semibold">Komoditas</th>
                        <th class="px-6 py-4 font-semibold">Hasil Scan</th>
                        <th class="px-6 py-4 font-semibold">Oleh</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700 divide-y divide-gray-50">
                    @forelse($riwayatTerbaru as $riwayat)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 flex items-center gap-2 text-gray-500 whitespace-nowrap">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            {{ \Carbon\Carbon::parse($riwayat->created_at)->translatedFormat('d M Y H:i') }}
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $riwayat->lahan->kelompokTani->name ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $riwayat->lahan->name ?? '-' }}</td>
                        <td class="px-6 py-4">Padi</td>
                        <td class="px-6 py-4">
                            <div>
                            @if($riwayat->penyakit == 'Sehat' || $riwayat->penyakit == 'Normal')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Sehat
                            </span>
                            @elseif($riwayat->penyakit == 'Tidak Terdeteksi' || !$riwayat->penyakit)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                Tidak Terdeteksi
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                {{ $riwayat->penyakit }}
                            </span>
                            @endif
                            </div>
                            @if($riwayat->bwd_status)
                            <div class="text-[10px] text-gray-500 mt-1">BWD: {{ $riwayat->bwd_status }} ({{ $riwayat->bwd_value }})</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 flex items-center gap-2">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($riwayat->user->name ?? 'User') }}&background=F3F4F6" alt="{{ substr($riwayat->user->name ?? 'U', 0, 1) }}" class="w-6 h-6 rounded-full">
                            {{ explode(' ', $riwayat->user->name ?? 'User')[0] }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">Belum ada riwayat scan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Calendar & Progress (Spans 1 column on lg) -->
    <div class="lg:col-span-1 space-y-6 flex flex-col">
        
        <!-- Kalender -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-base font-bold text-gray-900">Kalender</h2>
                <div class="flex items-center gap-2 text-sm font-medium text-gray-700">
                    <span id="calendarMonthYear"></span>
                    <div class="flex gap-1 ml-2 text-gray-400">
                        <button id="prevMonthBtn" class="hover:text-gray-900">&lt;</button>
                        <button id="nextMonthBtn" class="hover:text-gray-900">&gt;</button>
                    </div>
                </div>
            </div>
            <!-- Calendar Grid -->
            <div class="grid grid-cols-7 text-center text-xs font-semibold text-gray-400 mb-2">
                <div>Sen</div><div>Sel</div><div>Rab</div><div>Kam</div><div>Jum</div><div>Sab</div><div>Min</div>
            </div>
            <div id="calendarDays" class="grid grid-cols-7 text-center text-sm gap-y-2 text-gray-700">
                <!-- Days will be generated by JS -->
            </div>
        </div>

        <!-- Progress Scan -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] p-6 flex-1 flex flex-col justify-center">
            <div class="flex justify-between items-end mb-3">
                <h2 class="text-base font-bold text-gray-900">Progress Scan Bulan Ini</h2>
                <span class="text-xl font-bold text-gray-900">
                    @php 
                        $target = 233; 
                        $pct = $target > 0 ? min(100, round(($totalScanBulanIni / $target) * 100)) : 0;
                    @endphp
                    {{ $pct }}%
                </span>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-3 mb-2">
                <div class="bg-[#5C52E7] h-3 rounded-full" style="width: {{ $pct }}%"></div>
            </div>
            <p class="text-sm text-gray-500">{{ $totalScanBulanIni }} dari {{ $target }} target scan</p>
        </div>

    </div>
</div>

<!-- Secondary Grid for Charts & Activities -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Charts (Spans 2 columns) -->
    <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <!-- Line Chart -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-base font-bold text-gray-900">Statistik Scan (7 Hari Terakhir)</h2>
                <select class="text-sm border border-gray-200 rounded-lg px-2 py-1 text-gray-600 bg-gray-50 focus:outline-none focus:ring-1 focus:ring-[#5C52E7]">
                    <option>7 Hari Terakhir</option>
                    <option>Bulan Ini</option>
                </select>
            </div>
            <div class="relative h-48 w-full">
                <canvas id="lineChart"></canvas>
            </div>
        </div>

        <!-- Donut Chart -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] p-6">
            <h2 class="text-base font-bold text-gray-900 mb-6">Distribusi Hasil Scan</h2>
            <div class="flex items-center gap-6">
                <div class="relative h-32 w-32 flex-shrink-0">
                    <canvas id="donutChart"></canvas>
                </div>
                <div class="flex-1 space-y-3">
                    <div class="flex justify-between items-center text-sm">
                        <div class="flex items-center gap-2 text-gray-600"><span class="w-2.5 h-2.5 rounded-full bg-[#22C55E]"></span> Sehat</div>
                        <div class="font-medium text-gray-900">{{ $distribusiScan['sehat'] }} <span class="text-gray-400 font-normal ml-1">({{ round(($distribusiScan['sehat']/$distribusiScan['total'])*100) }}%)</span></div>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <div class="flex items-center gap-2 text-gray-600"><span class="w-2.5 h-2.5 rounded-full bg-[#EAB308]"></span> Perlu Perhatian</div>
                        <div class="font-medium text-gray-900">{{ $distribusiScan['perlu_perhatian'] }} <span class="text-gray-400 font-normal ml-1">({{ round(($distribusiScan['perlu_perhatian']/$distribusiScan['total'])*100) }}%)</span></div>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <div class="flex items-center gap-2 text-gray-600"><span class="w-2.5 h-2.5 rounded-full bg-[#EF4444]"></span> Terdeteksi OPT</div>
                        <div class="font-medium text-gray-900">{{ $distribusiScan['terdeteksi_opt'] }} <span class="text-gray-400 font-normal ml-1">({{ round(($distribusiScan['terdeteksi_opt']/$distribusiScan['total'])*100) }}%)</span></div>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <div class="flex items-center gap-2 text-gray-600"><span class="w-2.5 h-2.5 rounded-full bg-gray-400"></span> Tidak Terdeteksi</div>
                        <div class="font-medium text-gray-900">{{ $distribusiScan['tidak_terdeteksi'] }} <span class="text-gray-400 font-normal ml-1">({{ round(($distribusiScan['tidak_terdeteksi']/$distribusiScan['total'])*100) }}%)</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Aktivitas Mendatang -->
    <div class="lg:col-span-1 bg-white rounded-2xl border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-base font-bold text-gray-900">Aktivitas Mendatang</h2>
            <a href="#" class="text-sm font-medium text-[#5C52E7] hover:underline flex items-center gap-1">
                Lihat Semua
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>
        
        <div class="space-y-5">
            <!-- Item 1 -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-[#E8F8F1] text-[#22C55E] flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900">Pelatihan Pengendalian OPT</h4>
                        <p class="text-xs text-gray-500 mt-0.5">29 Mei 2024 &bull; 09:00 WIB</p>
                    </div>
                </div>
                <span class="text-[10px] font-bold text-yellow-600 bg-yellow-50 px-2.5 py-1 rounded-md">Pelatihan</span>
            </div>

            <!-- Item 2 -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-[#E0F2FE] text-[#0EA5E9] flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900">Monitoring Lahan</h4>
                        <p class="text-xs text-gray-500 mt-0.5">30 Mei 2024 &bull; 08:00 WIB</p>
                    </div>
                </div>
                <span class="text-[10px] font-bold text-[#5C52E7] bg-[#F0EFFF] px-2.5 py-1 rounded-md">Monitoring</span>
            </div>

            <!-- Item 3 -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-[#FEE2E2] text-[#EF4444] flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900">Evaluasi Bulanan</h4>
                        <p class="text-xs text-gray-500 mt-0.5">31 Mei 2024 &bull; 10:00 WIB</p>
                    </div>
                </div>
                <span class="text-[10px] font-bold text-pink-600 bg-pink-50 px-2.5 py-1 rounded-md">Evaluasi</span>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Chart Defaults
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = '#9CA3AF';
    
    // --- Line Chart ---
    const ctxLine = document.getElementById('lineChart').getContext('2d');
    
    // Gradient for Line Chart
    let gradient = ctxLine.createLinearGradient(0, 0, 0, 200);
    gradient.addColorStop(0, 'rgba(92, 82, 231, 0.2)');
    gradient.addColorStop(1, 'rgba(92, 82, 231, 0)');

    new Chart(ctxLine, {
        type: 'line',
        data: {
            labels: {!! json_encode($labels7Hari) !!},
            datasets: [{
                label: 'Scan',
                data: {!! json_encode($scan7HariTerakhir) !!},
                borderColor: '#5C52E7',
                backgroundColor: gradient,
                borderWidth: 2,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#5C52E7',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                fill: true,
                tension: 0.4 // curve
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#fff',
                    titleColor: '#1F2937',
                    bodyColor: '#4B5563',
                    borderColor: '#E5E7EB',
                    borderWidth: 1,
                    padding: 10,
                    displayColors: false,
                    callbacks: {
                        label: function(context) { return context.parsed.y + ' Scan'; }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 40,
                    ticks: { stepSize: 10 },
                    border: { display: false },
                    grid: { color: '#F3F4F6' }
                },
                x: {
                    border: { display: false },
                    grid: { display: false }
                }
            }
        }
    });

    // --- Donut Chart ---
    const ctxDonut = document.getElementById('donutChart').getContext('2d');
    new Chart(ctxDonut, {
        type: 'doughnut',
        data: {
            labels: ['Sehat', 'Perlu Perhatian', 'Terdeteksi OPT', 'Tidak Terdeteksi'],
            datasets: [{
                data: [{{ $distribusiScan['sehat'] }}, {{ $distribusiScan['perlu_perhatian'] }}, {{ $distribusiScan['terdeteksi_opt'] }}, {{ $distribusiScan['tidak_terdeteksi'] }}],
                backgroundColor: ['#22C55E', '#EAB308', '#EF4444', '#9CA3AF'],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) { return ' ' + context.parsed + ' (' + Math.round((context.parsed / 156) * 100) + '%)'; }
                    }
                }
            }
        }
    });

    // --- Calendar ---
    const calendarMonthYear = document.getElementById('calendarMonthYear');
    const calendarDays = document.getElementById('calendarDays');
    const prevMonthBtn = document.getElementById('prevMonthBtn');
    const nextMonthBtn = document.getElementById('nextMonthBtn');

    let currentDate = new Date();

    const monthNames = [
        "Januari", "Februari", "Maret", "April", "Mei", "Juni",
        "Juli", "Agustus", "September", "Oktober", "November", "Desember"
    ];

    function renderCalendar() {
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();
        const today = new Date();

        calendarMonthYear.textContent = `${monthNames[month]} ${year}`;
        calendarDays.innerHTML = '';

        let firstDay = new Date(year, month, 1).getDay();
        firstDay = firstDay === 0 ? 6 : firstDay - 1;

        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const prevMonthDays = new Date(year, month, 0).getDate();

        for (let i = firstDay - 1; i >= 0; i--) {
            const dayDiv = document.createElement('div');
            dayDiv.className = 'text-gray-300 py-1';
            dayDiv.textContent = prevMonthDays - i;
            calendarDays.appendChild(dayDiv);
        }

        for (let i = 1; i <= daysInMonth; i++) {
            const dayDiv = document.createElement('div');
            
            if (year === today.getFullYear() && month === today.getMonth() && i === today.getDate()) {
                dayDiv.className = 'py-1 bg-[#5C52E7] text-white rounded-full mx-auto w-7 h-7 flex items-center justify-center';
            } else {
                dayDiv.className = 'py-1 cursor-pointer hover:font-bold hover:text-[#5C52E7]';
            }
            
            dayDiv.textContent = i;
            calendarDays.appendChild(dayDiv);
        }

        const totalCells = calendarDays.children.length;
        const remainingCells = (7 - (totalCells % 7)) % 7;
        
        for (let i = 1; i <= remainingCells; i++) {
            const dayDiv = document.createElement('div');
            dayDiv.className = 'text-gray-300 py-1';
            dayDiv.textContent = i;
            calendarDays.appendChild(dayDiv);
        }
    }

    prevMonthBtn.addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar();
    });

    nextMonthBtn.addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar();
    });

    renderCalendar();
});
</script>
@endpush
