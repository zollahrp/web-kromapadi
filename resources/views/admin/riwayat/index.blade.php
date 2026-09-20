@extends('layouts.admin')

@section('content')
<style>
.animate-backdrop {
    animation: fadeIn 0.3s ease-out forwards;
}
.animate-modal {
    animation: scaleUp 0.3s ease-out forwards;
}
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
@keyframes scaleUp {
    from { opacity: 0; transform: scale(0.95) translateY(10px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
}
</style>

<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900">Riwayat Scan</h1>
    <p class="text-gray-500 mt-1 text-sm">Lihat hasil identifikasi penyakit tanaman padi dari Kelompok Tani Dewasa.</p>
</div>

<!-- Filter Section -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
    <form action="{{ route('riwayat.index') }}" method="GET" class="flex flex-col md:flex-row md:items-end gap-3" id="filterForm">
        <!-- Search -->
        <div class="flex-1 min-w-[250px] relative">
            <label class="block text-sm font-medium text-gray-700 mb-1 truncate">Cari Penyakit / KTD / Lahan</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" onchange="document.getElementById('filterForm').submit()" placeholder="Ketik kata kunci..." class="pl-10 mt-1 focus:ring-[#5C52E7] focus:border-[#5C52E7] block w-full shadow-sm sm:text-sm border-gray-300 rounded-xl px-4 py-2 border outline-none transition-colors">
            </div>
        </div>
        
        @if(request()->filled('search'))
        <div class="flex items-center gap-3 flex-shrink-0">
            <a href="{{ route('riwayat.index') }}" class="px-4 py-2 bg-red-50 text-red-600 rounded-xl text-sm font-medium hover:bg-red-100 transition-colors border border-red-100 mt-1 flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                Reset
            </a>
        </div>
        @endif
    </form>
</div>
<!-- Table Data Riwayat Scan -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100">
                    <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">No</th>
                    <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Foto</th>
                    <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Hasil / Penyakit</th>
                    <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">KTD & Lahan</th>
                    <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Waktu Scan</th>
                    <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($riwayats as $index => $riwayat)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="py-4 px-6 text-sm text-gray-500">{{ $index + 1 }}</td>
                    <td class="py-4 px-6">
                        @if($riwayat->foto_path)
                            <img src="{{ asset('storage/' . $riwayat->foto_path) }}" alt="Foto Scan" class="w-12 h-12 object-cover rounded-lg border border-gray-200">
                        @else
                            <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center border border-gray-200">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                    </td>
                    <td class="py-4 px-6">
                        <div class="font-medium text-gray-900">{{ $riwayat->penyakit }}</div>
                        @if($riwayat->akurasi)
                            <div class="text-xs text-green-600 font-medium mt-1">Akurasi: {{ $riwayat->akurasi }}%</div>
                        @endif
                    </td>
                    <td class="py-4 px-6">
                        <div class="text-sm font-medium text-gray-900">{{ $riwayat->user ? $riwayat->user->name : 'Unknown Petani' }}</div>
                        <div class="text-xs text-gray-500 mt-1">{{ $riwayat->lahan ? $riwayat->lahan->name : '-' }}</div>
                    </td>
                    <td class="py-4 px-6 text-sm text-gray-500">{{ $riwayat->created_at->format('d M Y, H:i') }}</td>
                    <td class="py-4 px-6 flex items-center justify-end gap-3">
                        <!-- Button Detail -->
                        <button onclick="openDetailModal('{{ $riwayat->penyakit }}', '{{ addslashes(str_replace(array("\r", "\n"), '', $riwayat->tindakan)) }}')" class="text-[#5C52E7] hover:text-[#4a42b9] transition-colors p-2 hover:bg-[#F0EFFF] rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            <p>Belum ada data riwayat scan.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Detail -->
<div id="modal-detail" class="fixed inset-0 z-50 hidden animate-backdrop" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="document.getElementById('modal-detail').classList.add('hidden')"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-visible shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full animate-modal relative z-10">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                        <div class="border-b border-gray-100 pb-4 mb-4"><h3 class="text-lg leading-6 font-bold text-gray-900" id="detail_title">Detail Penyakit</h3></div>
                        
                        <div class="mt-4">
                            <h4 class="text-sm font-semibold text-gray-700 mb-1">Rekomendasi Tindakan</h4>
                            <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 text-sm text-gray-700 whitespace-pre-wrap" id="detail_tindakan">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 flex justify-end border-t border-gray-100">
                <button type="button" onclick="document.getElementById('modal-detail').classList.add('hidden')" class="w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:w-auto sm:text-sm transition-colors">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function openDetailModal(penyakit, tindakan) {
        document.getElementById('detail_title').innerText = 'Penyakit: ' + penyakit;
        document.getElementById('detail_tindakan').innerText = tindakan ? tindakan : 'Tidak ada rekomendasi/tindakan yang tercatat.';
        document.getElementById('modal-detail').classList.remove('hidden');
    }
</script>
@endsection
