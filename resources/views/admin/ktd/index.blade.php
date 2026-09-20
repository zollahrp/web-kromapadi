@extends('layouts.admin')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<style>
.ts-control { 
    border-radius: 0.75rem !important; 
    padding: 0.5rem 1rem !important; 
    border-color: #D1D5DB !important; 
}
.ts-dropdown {
    border-radius: 0.75rem !important;
    overflow: hidden;
    border-color: #E5E7EB !important;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}
.ts-dropdown .active {
    background-color: #F0EFFF !important;
    color: #5C52E7 !important;
}
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

<div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Kelompok Tani Dewasa (KTD)</h1>
        @if(auth()->user()->role === 'master_wilayah' && auth()->user()->wilayah)
        <p class="text-gray-500 mt-1 text-sm">Kelola akun dan data Kelompok Tani Dewasa di Wilayah <strong class="text-gray-900">{{ auth()->user()->wilayah->name }}</strong>.</p>
        @else
        <p class="text-gray-500 mt-1 text-sm">Kelola akun dan data Kelompok Tani Dewasa di semua wilayah.</p>
        @endif
    </div>
    <div class="flex items-center gap-3">
        <!-- Button Trigger Modal Tambah -->
        <button onclick="document.getElementById('modal-tambah').classList.remove('hidden')" class="bg-[#5C52E7] hover:bg-[#4a42b9] text-white px-5 py-2.5 rounded-xl font-medium transition-colors shadow-sm flex items-center gap-2 text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah KTD
        </button>
    </div>
</div>

@if(session('success'))
<div id="success-alert" class="fixed top-6 right-6 z-[100] max-w-sm w-full bg-white rounded-2xl shadow-xl border border-green-100 p-4 flex items-start gap-3 transform transition-all duration-500 translate-y-0 opacity-100" style="animation: slideInRight 0.5s cubic-bezier(0.16, 1, 0.3, 1);">
    <div class="w-10 h-10 rounded-full bg-green-50 flex items-center justify-center flex-shrink-0">
        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
    </div>
    <div class="flex-1">
        <h3 class="text-sm font-bold text-gray-900">Berhasil!</h3>
        <p class="text-sm text-gray-500 mt-0.5">{{ session('success') }}</p>
    </div>
    <button onclick="closeAlert()" class="text-gray-400 hover:text-gray-600 transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
    </button>
</div>
<style>
@keyframes slideInRight {
    from { transform: translateX(100%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}
</style>
<script>
    function closeAlert() {
        const el = document.getElementById('success-alert');
        if (el) {
            el.style.transform = 'translateX(100%)';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 500);
        }
    }
    setTimeout(closeAlert, 5000);
</script>
@endif

@if($errors->any())
<div id="error-alert" class="fixed top-6 right-6 z-[100] max-w-sm w-full bg-white rounded-2xl shadow-xl border border-red-100 p-4 flex items-start gap-3 transform transition-all duration-500 translate-y-0 opacity-100" style="animation: slideInRight 0.5s cubic-bezier(0.16, 1, 0.3, 1);">
    <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center flex-shrink-0">
        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
    </div>
    <div class="flex-1">
        <h3 class="text-sm font-bold text-gray-900">Gagal!</h3>
        <ul class="text-sm text-gray-500 mt-0.5 list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    <button onclick="closeErrorAlert()" class="text-gray-400 hover:text-gray-600 transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
    </button>
</div>
<script>
    function closeErrorAlert() {
        const el = document.getElementById('error-alert');
        if (el) {
            el.style.transform = 'translateX(100%)';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 500);
        }
    }
    setTimeout(closeErrorAlert, 5000);
</script>
@endif

<!-- Filter Section -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
    <form action="{{ route('ktd.index') }}" method="GET" class="flex flex-col md:flex-row md:items-end gap-3" id="filterForm">
        <!-- Search Name -->
        <div class="flex-1 min-w-[250px] relative">
            <label class="block text-sm font-medium text-gray-700 mb-1 truncate">Cari KTD / Ketua</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama KTD atau ketua..." class="pl-10 mt-1 focus:ring-[#5C52E7] focus:border-[#5C52E7] block w-full shadow-sm sm:text-sm border-gray-300 rounded-xl px-4 py-2 border outline-none transition-colors">
            </div>
        </div>
        
        @if(auth()->user()->role === 'super_admin')
        <!-- Filter Wilayah (Super Admin) -->
        <div class="w-full md:w-64 flex-shrink-0">
            <label class="block text-sm font-medium text-gray-700 mb-1 truncate">Wilayah</label>
            <select name="wilayah_id" id="filter_wilayah_id" onchange="document.getElementById('filterForm').submit()" class="mt-1 block w-full">
                <option value="">Semua Wilayah</option>
                @if(isset($wilayahs))
                    @foreach($wilayahs as $wil)
                        <option value="{{ $wil->id }}" {{ request('wilayah_id') == $wil->id ? 'selected' : '' }}>{{ $wil->name }}</option>
                    @endforeach
                @endif
            </select>
        </div>
        @endif
        
        @if(request()->hasAny(['search', 'wilayah_id']))
        <div class="flex items-center gap-3 flex-shrink-0">
            <a href="{{ route('ktd.index') }}" class="px-4 py-2 bg-red-50 text-red-600 rounded-xl text-sm font-medium hover:bg-red-100 transition-colors border border-red-100 mt-1 flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                Reset
            </a>
        </div>
        @endif
    </form>
</div>
<!-- Table Data KTD -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100">
                    <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">No</th>
                    <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama KTD</th>
                    <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Ketua</th>
                    <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Wilayah</th>
                    <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Dibuat Oleh</th>
                    <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Dibuat Pada</th>
                    <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($ktds as $index => $ktd)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="py-4 px-6 text-sm text-gray-500">{{ $index + 1 }}</td>
                    <td class="py-4 px-6">
                        <div class="font-medium text-gray-900">{{ $ktd->name }}</div>
                    </td>
                    <td class="py-4 px-6 text-sm text-gray-500">
                        {{ $ktd->nama_ketua ?? '-' }}
                    </td>
                    <td class="py-4 px-6 text-sm text-gray-500">
                        {{ $ktd->wilayah ? $ktd->wilayah->name : '-' }}
                    </td>
                    <td class="py-4 px-6 text-sm text-gray-500">
                        {{ $ktd->masterAdmin ? $ktd->masterAdmin->name : '-' }}
                    </td>
                    <td class="py-4 px-6 text-sm text-gray-500">{{ $ktd->created_at->format('d M Y') }}</td>
                    <td class="py-4 px-6 flex items-center justify-end gap-3">
                        <!-- Button Kelola Petani -->
                        <a href="{{ route('ktd.petani.index', $ktd->id) }}" class="text-green-500 hover:text-green-700 transition-colors p-2 hover:bg-green-50 rounded-lg flex items-center gap-1" title="Kelola Petani">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </a>
                        <!-- Button Edit -->
                        <button 
                            data-id="{{ $ktd->id }}"
                            data-name="{{ $ktd->name }}"
                            data-ketua="{{ $ktd->nama_ketua }}"
                            data-long="{{ $ktd->lokasi_long }}"
                            data-lat="{{ $ktd->lokasi_lat }}"
                            data-alamat="{{ $ktd->alamat }}"
                            data-wilayah="{{ $ktd->wilayah_id }}"
                            onclick="openEditModal(this)" 
                            class="text-blue-500 hover:text-blue-700 transition-colors p-2 hover:bg-blue-50 rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </button>
                        <!-- Button Delete -->
                        <form action="{{ route('ktd.destroy', $ktd->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus Kelompok Tani ini beserta akunnya?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 transition-colors p-2 hover:bg-red-50 rounded-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            <p>Belum ada data Kelompok Tani Dewasa di wilayah ini.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah KTD -->
<div id="modal-tambah" class="fixed inset-0 z-50 hidden animate-backdrop" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="document.getElementById('modal-tambah').classList.add('hidden')"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-visible shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full animate-modal relative z-10">
            <form action="{{ route('ktd.store') }}" method="POST">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <div class="border-b border-gray-100 pb-4 mb-4"><h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">Tambah Kelompok Tani</h3></div>
                            
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Kelompok Tani</label>
                                    <input type="text" name="name" id="name" required class="mt-1 focus:ring-[#5C52E7] focus:border-[#5C52E7] block w-full shadow-sm sm:text-sm border-gray-300 rounded-xl px-4 py-2 border outline-none transition-colors" placeholder="Contoh: KTD Mulyaharja 01">
                                </div>
                                @if(auth()->user()->role === 'super_admin' && isset($wilayahs))
                                <div>
                                    <label for="wilayah_id" class="block text-sm font-medium text-gray-700">Wilayah</label>
                                    <select name="wilayah_id" id="add_wilayah_id" required class="mt-1 block w-full">
                                        <option value="">-- Pilih Wilayah --</option>
                                        @foreach($wilayahs as $wilayah)
                                            <option value="{{ $wilayah->id }}">{{ $wilayah->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif
                                <div>
                                    <label for="nama_ketua" class="block text-sm font-medium text-gray-700">Nama Ketua KTD</label>
                                    <input type="text" name="nama_ketua" id="nama_ketua" required class="mt-1 focus:ring-[#5C52E7] focus:border-[#5C52E7] block w-full shadow-sm sm:text-sm border-gray-300 rounded-xl px-4 py-2 border outline-none transition-colors" placeholder="Masukkan nama ketua">
                                </div>
                                <div>
                                    <div class="flex justify-between items-center mb-1">
                                        <label class="block text-sm font-medium text-gray-700">Koordinat Lokasi</label>
                                        <button type="button" onclick="getLocation('tambah', this)" class="text-xs flex items-center gap-1 text-[#5C52E7] hover:text-[#4a42b9] font-medium bg-[#F0EFFF] px-2 py-1 rounded-lg transition-colors focus:outline-none cursor-pointer">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                            Ambil Lokasi Saat Ini
                                        </button>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <input type="text" name="lokasi_lat" id="lokasi_lat" required class="focus:ring-[#5C52E7] focus:border-[#5C52E7] block w-full shadow-sm sm:text-sm border-gray-300 rounded-xl px-4 py-2 border outline-none transition-colors" placeholder="Latitude (Cth: -6.123)">
                                        </div>
                                        <div>
                                            <input type="text" name="lokasi_long" id="lokasi_long" required class="focus:ring-[#5C52E7] focus:border-[#5C52E7] block w-full shadow-sm sm:text-sm border-gray-300 rounded-xl px-4 py-2 border outline-none transition-colors" placeholder="Longitude (Cth: 106.12)">
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                                    <textarea name="alamat" id="alamat" rows="2" required class="mt-1 focus:ring-[#5C52E7] focus:border-[#5C52E7] block w-full shadow-sm sm:text-sm border-gray-300 rounded-xl px-4 py-2 border outline-none transition-colors" placeholder="Alamat lengkap KTD"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100">
                    <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-[#5C52E7] text-base font-medium text-white hover:bg-[#4a42b9] focus:outline-none sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                        Simpan KTD
                    </button>
                    <button type="button" onclick="document.getElementById('modal-tambah').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit KTD -->
<div id="modal-edit" class="fixed inset-0 z-50 hidden animate-backdrop" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="document.getElementById('modal-edit').classList.add('hidden')"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-visible shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full animate-modal relative z-10">
            <form id="form-edit" method="POST">
                @csrf
                @method('PUT')
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Edit Kelompok Tani</h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label for="edit_name" class="block text-sm font-medium text-gray-700">Nama Kelompok Tani</label>
                                    <input type="text" name="name" id="edit_name" required class="mt-1 focus:ring-[#5C52E7] focus:border-[#5C52E7] block w-full shadow-sm sm:text-sm border-gray-300 rounded-xl px-4 py-2 border outline-none transition-colors">
                                </div>
                                @if(auth()->user()->role === 'super_admin' && isset($wilayahs))
                                <div>
                                    <label for="edit_wilayah_id" class="block text-sm font-medium text-gray-700">Wilayah</label>
                                    <select name="wilayah_id" id="edit_wilayah_ts" required class="mt-1 block w-full">
                                        <option value="">-- Pilih Wilayah --</option>
                                        @foreach($wilayahs as $wilayah)
                                            <option value="{{ $wilayah->id }}">{{ $wilayah->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif
                                <div>
                                    <label for="edit_nama_ketua" class="block text-sm font-medium text-gray-700">Nama Ketua KTD</label>
                                    <input type="text" name="nama_ketua" id="edit_nama_ketua" required class="mt-1 focus:ring-[#5C52E7] focus:border-[#5C52E7] block w-full shadow-sm sm:text-sm border-gray-300 rounded-xl px-4 py-2 border outline-none transition-colors">
                                </div>
                                <div>
                                    <div class="flex justify-between items-center mb-1">
                                        <label class="block text-sm font-medium text-gray-700">Koordinat Lokasi</label>
                                        <button type="button" onclick="getLocation('edit', this)" class="text-xs flex items-center gap-1 text-[#5C52E7] hover:text-[#4a42b9] font-medium bg-[#F0EFFF] px-2 py-1 rounded-lg transition-colors focus:outline-none cursor-pointer">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                            Ambil Lokasi Saat Ini
                                        </button>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <input type="text" name="lokasi_lat" id="edit_lokasi_lat" required class="focus:ring-[#5C52E7] focus:border-[#5C52E7] block w-full shadow-sm sm:text-sm border-gray-300 rounded-xl px-4 py-2 border outline-none transition-colors" placeholder="Latitude">
                                        </div>
                                        <div>
                                            <input type="text" name="lokasi_long" id="edit_lokasi_long" required class="focus:ring-[#5C52E7] focus:border-[#5C52E7] block w-full shadow-sm sm:text-sm border-gray-300 rounded-xl px-4 py-2 border outline-none transition-colors" placeholder="Longitude">
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label for="edit_alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                                    <textarea name="alamat" id="edit_alamat" rows="2" required class="mt-1 focus:ring-[#5C52E7] focus:border-[#5C52E7] block w-full shadow-sm sm:text-sm border-gray-300 rounded-xl px-4 py-2 border outline-none transition-colors"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100">
                    <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-[#5C52E7] text-base font-medium text-white hover:bg-[#4a42b9] focus:outline-none sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                        Simpan Perubahan
                    </button>
                    <button type="button" onclick="document.getElementById('modal-edit').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function getLocation(type, btn) {
        if (navigator.geolocation) {
            let latId = type === 'tambah' ? 'lokasi_lat' : 'edit_lokasi_lat';
            let longId = type === 'tambah' ? 'lokasi_long' : 'edit_lokasi_long';
            
            let originalContent = btn.innerHTML;
            btn.innerHTML = `<svg class="animate-spin h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...`;
            btn.disabled = true;

            navigator.geolocation.getCurrentPosition(function(position) {
                document.getElementById(latId).value = position.coords.latitude;
                document.getElementById(longId).value = position.coords.longitude;
                btn.innerHTML = originalContent;
                btn.disabled = false;
            }, function(error) {
                alert('Gagal mengambil lokasi. Pastikan izin lokasi (location/GPS) diizinkan di browser Anda.');
                btn.innerHTML = originalContent;
                btn.disabled = false;
            });
        } else {
            alert("Fitur Geolocation tidak didukung oleh browser Anda.");
        }
    }

    function openEditModal(button) {
        var id = button.getAttribute('data-id');
        var nama = button.getAttribute('data-name');
        var ketua = button.getAttribute('data-ketua');
        var long = button.getAttribute('data-long');
        var lat = button.getAttribute('data-lat');
        var alamat = button.getAttribute('data-alamat');
        var wilayah = button.getAttribute('data-wilayah');

        // Set action form
        document.getElementById('form-edit').action = '/ktd/' + id;
        // Set input value
        document.getElementById('edit_name').value = nama;
        document.getElementById('edit_nama_ketua').value = ketua;
        document.getElementById('edit_lokasi_long').value = long;
        document.getElementById('edit_lokasi_lat').value = lat;
        document.getElementById('edit_alamat').value = alamat;
        
        if (document.getElementById('edit_wilayah_id') && wilayah) {
            document.getElementById('edit_wilayah_id').value = wilayah;
        }

        // Show modal
        document.getElementById('modal-edit').classList.remove('hidden');
    }
</script>

<script>
    let tsFilterWilayah, tsAddWilayah, tsEditWilayah;
    
    document.addEventListener('DOMContentLoaded', function() {
        if(document.getElementById('filter_wilayah_id')) {
            tsFilterWilayah = new TomSelect("#filter_wilayah_id", {
                create: false,
                sortField: { field: "text", direction: "asc" },
                placeholder: "Semua Wilayah"
            });
        }
        
        if(document.getElementById('add_wilayah_id')) {
            tsAddWilayah = new TomSelect("#add_wilayah_id", {
                create: false,
                sortField: { field: "text", direction: "asc" },
                placeholder: "Pilih Wilayah..."
            });
        }
        
        if(document.getElementById('edit_wilayah_ts')) {
            tsEditWilayah = new TomSelect("#edit_wilayah_ts", {
                create: false,
                sortField: { field: "text", direction: "asc" },
                placeholder: "Pilih Wilayah..."
            });
        }
    });

    // Make sure openEditModal updates TomSelect if it exists
    const oldOpenEditModal = openEditModal;
    openEditModal = function(btn) {
        const wilayah = btn.getAttribute('data-wilayah');
        oldOpenEditModal(btn);
        
        if(tsEditWilayah && wilayah) {
            tsEditWilayah.setValue(wilayah);
        }
    }
</script>

@endsection
