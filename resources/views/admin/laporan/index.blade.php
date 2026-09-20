@extends('layouts.admin')

@section('content')
<style>
    @media print {
        body {
            background-color: white !important;
        }
        /* Sembunyikan elemen yang tidak perlu saat diprint */
        nav, aside, .no-print, button, form {
            display: none !important;
        }
        /* Pastikan content utama mengambil lebar penuh */
        main {
            margin-left: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }
        .print-header {
            display: block !important;
            text-align: center;
            margin-bottom: 2rem;
            border-bottom: 2px solid #000;
            padding-bottom: 1rem;
        }
        .shadow-sm {
            box-shadow: none !important;
        }
        table {
            border-collapse: collapse !important;
            width: 100% !important;
        }
        th, td {
            border: 1px solid #ddd !important;
            padding: 8px !important;
        }
    }
    
    .print-header {
        display: none;
    }
</style>

<div class="print-header">
    <h1 style="font-size: 24px; font-weight: bold; margin: 0;">LAPORAN IDENTIFIKASI PENYAKIT PADI</h1>
    <p style="font-size: 16px; margin: 5px 0;">Sistem Informasi KromaPadi</p>
    <p style="font-size: 14px; margin: 0;">Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>
</div>

<div class="mb-8 flex justify-between items-end no-print">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Laporan Aktivitas Scan</h1>
        <p class="text-gray-500 mt-1 text-sm">Ringkasan hasil identifikasi penyakit tanaman padi.</p>
    </div>
    <div>
        <button onclick="window.print()" class="bg-[#5C52E7] hover:bg-[#4a42b9] text-white px-4 py-2 rounded-xl text-sm font-medium transition-colors shadow-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Cetak PDF
        </button>
    </div>
</div>

<!-- Form Filter -->
<div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 mb-8 no-print">
    <form action="{{ route('laporan.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
        <div class="flex-1 w-full">
            <label class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
            <input type="date" name="start_date" value="{{ $startDate }}" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#5C52E7] focus:border-transparent transition-all text-sm">
        </div>
        <div class="flex-1 w-full">
            <label class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
            <input type="date" name="end_date" value="{{ $endDate }}" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#5C52E7] focus:border-transparent transition-all text-sm">
        </div>
        <div class="flex-1 w-full">
            <label class="block text-sm font-medium text-gray-700 mb-1">Kelompok Tani (Opsional)</label>
            <select name="ktd_id" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#5C52E7] focus:border-transparent transition-all text-sm appearance-none bg-white">
                <option value="">Semua Kelompok Tani</option>
                @foreach($ktdList as $ktd)
                    <option value="{{ $ktd->id }}" {{ $ktdId == $ktd->id ? 'selected' : '' }}>{{ $ktd->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="w-full md:w-auto">
            <button type="submit" class="w-full md:w-auto bg-gray-900 hover:bg-gray-800 text-white px-6 py-2 rounded-xl text-sm font-medium transition-colors shadow-sm">
                Terapkan Filter
            </button>
        </div>
    </form>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center">
        <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center mr-4">
            <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">Total Aktivitas Scan</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ $totalScan }} <span class="text-sm font-normal text-gray-500">kali</span></h3>
        </div>
    </div>
    
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center">
        <div class="w-12 h-12 rounded-xl bg-red-50 flex items-center justify-center mr-4">
            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">Penyakit Terbanyak</p>
            <h3 class="text-xl font-bold text-gray-900 truncate max-w-[150px]" title="{{ $penyakitTerbanyak }}">{{ $penyakitTerbanyak }}</h3>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center">
        <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center mr-4">
            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">KTD Teraktif</p>
            <h3 class="text-xl font-bold text-gray-900 truncate max-w-[150px]" title="{{ $ktdTeraktif }}">{{ $ktdTeraktif }}</h3>
        </div>
    </div>
</div>

<!-- Table Data Riwayat Scan -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100">
                    <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Waktu Scan</th>
                    <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Hasil Penyakit</th>
                    <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">KTD</th>
                    <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Lahan</th>
                    <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right no-print">Akurasi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($riwayats as $riwayat)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="py-4 px-6 text-sm text-gray-900 font-medium">{{ $riwayat->created_at->format('d/m/Y H:i') }}</td>
                    <td class="py-4 px-6 text-sm text-gray-700">{{ $riwayat->penyakit }}</td>
                    <td class="py-4 px-6 text-sm text-gray-700">{{ $riwayat->user ? $riwayat->user->name : '-' }}</td>
                    <td class="py-4 px-6 text-sm text-gray-700">{{ $riwayat->lahan ? $riwayat->lahan->name : '-' }}</td>
                    <td class="py-4 px-6 text-sm text-gray-500 text-right no-print">
                        @if($riwayat->akurasi)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ $riwayat->akurasi }}%
                            </span>
                        @else
                            -
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            <p>Tidak ada data laporan pada periode atau filter ini.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
