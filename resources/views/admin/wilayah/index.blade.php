@extends('layouts.admin')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<style>

.ts-wrapper.skeleton-loading .ts-control {
    background-color: #e5e7eb !important;
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    pointer-events: none;
}
.ts-wrapper.skeleton-loading .ts-control input::placeholder {
    color: transparent !important;
}
.ts-wrapper.skeleton-loading .ts-control .item {
    opacity: 0;
}

/* Premium TomSelect UI */
.ts-control { 
    border-radius: 0.75rem !important; 
    padding: 0.625rem 1rem !important; 
    border: 1px solid #e5e7eb !important; 
    background-color: #f9fafb !important;
    transition: all 0.3s ease !important;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
}
.ts-control.focus { 
    border-color: #5C52E7 !important; 
    background-color: #ffffff !important;
    box-shadow: 0 0 0 4px rgba(92, 82, 231, 0.15) !important; 
}
.ts-dropdown {
    border-radius: 0.75rem !important;
    border: 1px solid #e5e7eb !important;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
    margin-top: 0.5rem !important;
    animation: dropdownSlide 0.2s cubic-bezier(0.16, 1, 0.3, 1) forwards !important;
    overflow: hidden !important;
}
.ts-dropdown .option {
    padding: 0.625rem 1rem !important;
    transition: all 0.2s ease !important;
}
.ts-dropdown .active {
    background-color: #f3f2ff !important;
    color: #5C52E7 !important;
    font-weight: 500 !important;
}
@keyframes dropdownSlide {
    from { opacity: 0; transform: translateY(-8px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Smooth Modal Animation */
@keyframes modalFadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
@keyframes modalScaleIn {
    from { opacity: 0; transform: translateY(20px) scale(0.95); }
    to { opacity: 1; transform: translateY(0) scale(1); }
}
.animate-backdrop {
    animation: modalFadeIn 0.3s ease-out forwards;
}
.animate-modal {
    animation: modalScaleIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
</style>

<div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Manajemen Wilayah</h1>
        <p class="text-gray-500 mt-1 text-sm">Kelola daftar wilayah yang tersedia dalam sistem.</p>
    </div>
    <div class="flex items-center gap-3">
        <!-- Button Trigger Modal Tambah -->
        <button onclick="document.getElementById('modal-tambah').classList.remove('hidden')" class="bg-[#5C52E7] hover:bg-[#4a42b9] text-white px-5 py-2.5 rounded-xl font-medium transition-colors shadow-sm flex items-center gap-2 text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Wilayah
        </button>
    </div>
</div>

@if(session('success'))
<div id="success-alert" class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-r-xl">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="ml-3">
            <p class="text-sm text-green-700">
                {{ session('success') }}
            </p>
        </div>
    </div>
</div>
@endif

<!-- Filter Section -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
    <form action="{{ route('wilayah.index') }}" method="GET" class="flex flex-col md:flex-row md:items-end gap-3" id="filterForm">
        <!-- Search Name -->
        <div class="flex-1 min-w-[250px] relative">
            <label class="block text-sm font-medium text-gray-700 mb-1 truncate">Cari Nama Wilayah</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama wilayah lalu Enter..." class="pl-10 mt-1 focus:ring-[#5C52E7] focus:border-[#5C52E7] block w-full shadow-sm sm:text-sm border-gray-300 rounded-xl px-4 py-2 border outline-none transition-colors">
            </div>
        </div>
        
        <!-- Filter Provinsi -->
        <div class="w-full md:w-48 flex-shrink-0">
            <label class="block text-sm font-medium text-gray-700 mb-1 truncate">Provinsi</label>
            <select name="provinsi" onchange="document.getElementById('filterForm').submit()" class="mt-1 focus:ring-[#5C52E7] focus:border-[#5C52E7] block w-full shadow-sm sm:text-sm border-gray-300 rounded-xl px-4 py-2 border outline-none transition-colors bg-white appearance-none cursor-pointer">
                <option value="">Semua Provinsi</option>
                @if(isset($provinsis))
                    @foreach($provinsis as $prov)
                        <option value="{{ $prov }}" {{ request('provinsi') == $prov ? 'selected' : '' }}>{{ $prov }}</option>
                    @endforeach
                @endif
            </select>
        </div>
        
        @if(request()->hasAny(['search', 'provinsi']))
        <div class="flex items-center gap-3 flex-shrink-0">
            <a href="{{ route('wilayah.index') }}" class="px-4 py-2 bg-red-50 text-red-600 rounded-xl text-sm font-medium hover:bg-red-100 transition-colors border border-red-100 mt-1 flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                Reset
            </a>
        </div>
        @endif
    </form>
</div>
<!-- Table Data Wilayah -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100">
                    <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">No</th>
                    <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Wilayah</th>
                    <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Lokasi Lengkap</th>
                    <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Dibuat Pada</th>
                    <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($wilayahs as $index => $wilayah)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="py-4 px-6 text-sm text-gray-500">{{ $index + 1 }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $wilayah->name }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-xs text-gray-500">
                            {{ $wilayah->kelurahan ?? '-' }}, {{ $wilayah->kecamatan ?? '-' }}<br>
                            {{ $wilayah->kota ?? '-' }}, {{ $wilayah->provinsi ?? '-' }}
                        </div>
                    </td>
                    <td class="py-4 px-6 text-sm text-gray-500">{{ $wilayah->created_at->format('d M Y') }}</td>
                    <td class="py-4 px-6 flex items-center justify-end gap-3">
                        <!-- Button Edit -->
                        <button onclick="openEditModal('{{ $wilayah->id }}', '{{ $wilayah->name }}', '{{ $wilayah->provinsi }}', '{{ $wilayah->kota }}', '{{ $wilayah->kecamatan }}', '{{ $wilayah->kelurahan }}')" class="text-blue-500 hover:text-blue-700 transition-colors p-2 hover:bg-blue-50 rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </button>
                        <!-- Button Delete -->
                        <form action="{{ route('wilayah.destroy', $wilayah->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus wilayah ini?');">
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
                    <td colspan="5" class="py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                            <p>Belum ada data wilayah.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Wilayah -->
<div id="modal-tambah" class="fixed inset-0 z-50 hidden animate-backdrop" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="document.getElementById('modal-tambah').classList.add('hidden')"></div>

        <!-- Center modal -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-visible shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative z-10" animate-modal" animate-modal">
            <form action="{{ route('wilayah.store') }}" method="POST">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-[#F0EFFF] sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-[#5C52E7]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Tambah Wilayah</h3>
                            <div class="mt-4 flex flex-col gap-4">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Wilayah (Spesifik)</label>
                                    <input type="text" name="name" id="name" required class="mt-1 focus:ring-[#5C52E7] focus:border-[#5C52E7] block w-full shadow-sm sm:text-sm border-gray-300 rounded-xl px-4 py-2 border outline-none transition-colors" placeholder="Contoh: AEWO Lemah Duhur">
                                </div>
                                <div>
    <label for="provinsi" class="block text-sm font-medium text-gray-700">Provinsi</label>
    <input type="hidden" name="provinsi" id="provinsi_name">
    <select id="provinsi" class="mt-1 block w-full" placeholder="Pilih Provinsi..."></select>
</div>
                                <div>
    <label for="kota" class="block text-sm font-medium text-gray-700">Kota/Kabupaten</label>
    <input type="hidden" name="kota" id="kota_name">
    <select id="kota" class="mt-1 block w-full" placeholder="Pilih Kota/Kabupaten..."></select>
</div>
                                <div>
    <label for="kecamatan" class="block text-sm font-medium text-gray-700">Kecamatan</label>
    <input type="hidden" name="kecamatan" id="kecamatan_name">
    <select id="kecamatan" class="mt-1 block w-full" placeholder="Pilih Kecamatan..."></select>
</div>
                                <div>
    <label for="kelurahan" class="block text-sm font-medium text-gray-700">Kelurahan/Desa</label>
    <input type="hidden" name="kelurahan" id="kelurahan_name">
    <select id="kelurahan" class="mt-1 block w-full" placeholder="Pilih Kelurahan/Desa..."></select>
</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100 rounded-b-2xl">
                    <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-[#5C52E7] text-base font-medium text-white hover:bg-[#4a42b9] focus:outline-none sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                        Simpan
                    </button>
                    <button type="button" onclick="document.getElementById('modal-tambah').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Wilayah -->
<div id="modal-edit" class="fixed inset-0 z-50 hidden animate-backdrop" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="document.getElementById('modal-edit').classList.add('hidden')"></div>

        <!-- Center modal -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-visible shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative z-10">
            <form id="form-edit" method="POST">
                @csrf
                @method('PUT')
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-50 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Edit Wilayah</h3>
                            <div class="mt-4 flex flex-col gap-4">
                                <div>
                                    <label for="edit_name" class="block text-sm font-medium text-gray-700">Nama Wilayah (Spesifik)</label>
                                    <input type="text" name="name" id="edit_name" required class="mt-1 focus:ring-[#5C52E7] focus:border-[#5C52E7] block w-full shadow-sm sm:text-sm border-gray-300 rounded-xl px-4 py-2 border outline-none transition-colors">
                                </div>
                                <div>
    <label for="edit_provinsi" class="block text-sm font-medium text-gray-700">Provinsi</label>
    <input type="hidden" name="provinsi" id="edit_provinsi_name">
    <select id="edit_provinsi" class="mt-1 block w-full" placeholder="Pilih Provinsi..."></select>
</div>
                                <div>
    <label for="edit_kota" class="block text-sm font-medium text-gray-700">Kota/Kabupaten</label>
    <input type="hidden" name="kota" id="edit_kota_name">
    <select id="edit_kota" class="mt-1 block w-full" placeholder="Pilih Kota/Kabupaten..."></select>
</div>
                                <div>
    <label for="edit_kecamatan" class="block text-sm font-medium text-gray-700">Kecamatan</label>
    <input type="hidden" name="kecamatan" id="edit_kecamatan_name">
    <select id="edit_kecamatan" class="mt-1 block w-full" placeholder="Pilih Kecamatan..."></select>
</div>
                                <div>
    <label for="edit_kelurahan" class="block text-sm font-medium text-gray-700">Kelurahan/Desa</label>
    <input type="hidden" name="kelurahan" id="edit_kelurahan_name">
    <select id="edit_kelurahan" class="mt-1 block w-full" placeholder="Pilih Kelurahan/Desa..."></select>
</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100 rounded-b-2xl">
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
    const apiUrl = 'https://www.emsifa.com/api-wilayah-indonesia/api';

    function setupCascadingDropdown(prefix) {
        let tsProv, tsKota, tsKec, tsKel;

        const elProv = document.getElementById(prefix + 'provinsi');
        const elKota = document.getElementById(prefix + 'kota');
        const elKec = document.getElementById(prefix + 'kecamatan');
        const elKel = document.getElementById(prefix + 'kelurahan');

        const hidProv = document.getElementById(prefix + 'provinsi_name');
        const hidKota = document.getElementById(prefix + 'kota_name');
        const hidKec = document.getElementById(prefix + 'kecamatan_name');
        const hidKel = document.getElementById(prefix + 'kelurahan_name');

        const tsConfig = {
            valueField: 'id',
            labelField: 'name',
            searchField: 'name',
        };

        tsProv = new TomSelect(elProv, {
            ...tsConfig,
            onChange: function(value) {
                if (!value) {
                    tsKota.disable(); tsKota.clearOptions(); tsKota.clear();
                    tsKec.disable(); tsKec.clearOptions(); tsKec.clear();
                    tsKel.disable(); tsKel.clearOptions(); tsKel.clear();
                    return;
                }
                hidProv.value = this.options[value].name;
                
                tsKota.clearOptions(); tsKota.clear(); tsKota.disable();
                tsKec.clearOptions(); tsKec.clear(); tsKec.disable();
                tsKel.clearOptions(); tsKel.clear(); tsKel.disable();
                
                tsKota.wrapper.classList.add('skeleton-loading');
                
                fetch(`${apiUrl}/regencies/${value}.json`)
                    .then(response => response.json())
                    .then(json => {
                        tsKota.wrapper.classList.remove('skeleton-loading');
                        tsKota.addOption(json);
                        tsKota.enable();
                    })
                    .catch(() => tsKota.wrapper.classList.remove('skeleton-loading'));
            }
        });

        tsKota = new TomSelect(elKota, {
            ...tsConfig,
            onChange: function(value) {
                if (!value) {
                    tsKec.disable(); tsKec.clearOptions(); tsKec.clear();
                    tsKel.disable(); tsKel.clearOptions(); tsKel.clear();
                    return;
                }
                hidKota.value = this.options[value].name;

                tsKec.clearOptions(); tsKec.clear(); tsKec.disable();
                tsKel.clearOptions(); tsKel.clear(); tsKel.disable();

                tsKec.wrapper.classList.add('skeleton-loading');

                fetch(`${apiUrl}/districts/${value}.json`)
                    .then(response => response.json())
                    .then(json => {
                        tsKec.wrapper.classList.remove('skeleton-loading');
                        tsKec.addOption(json);
                        tsKec.enable();
                    })
                    .catch(() => tsKec.wrapper.classList.remove('skeleton-loading'));
            }
        });

        tsKec = new TomSelect(elKec, {
            ...tsConfig,
            onChange: function(value) {
                if (!value) {
                    tsKel.disable(); tsKel.clearOptions(); tsKel.clear();
                    return;
                }
                hidKec.value = this.options[value].name;

                tsKel.clearOptions(); tsKel.clear(); tsKel.disable();
                
                tsKel.wrapper.classList.add('skeleton-loading');

                fetch(`${apiUrl}/villages/${value}.json`)
                    .then(response => response.json())
                    .then(json => {
                        tsKel.wrapper.classList.remove('skeleton-loading');
                        tsKel.addOption(json);
                        tsKel.enable();
                    })
                    .catch(() => tsKel.wrapper.classList.remove('skeleton-loading'));
            }
        });

        tsKel = new TomSelect(elKel, {
            ...tsConfig,
            onChange: function(value) {
                if (!value) return;
                hidKel.value = this.options[value].name;
            }
        });

        // Initialize state
        tsProv.disable();
        tsProv.control_input.placeholder = "Sedang mengambil data...";
        tsKota.disable();
        tsKec.disable();
        tsKel.disable();
        
        // Fetch provinces immediately
        fetch(`${apiUrl}/provinces.json`)
            .then(res => res.json())
            .then(json => {
                tsProv.addOption(json);
                tsProv.control_input.placeholder = "Pilih Provinsi...";
                tsProv.enable();
            })
            .catch(() => {
                tsProv.control_input.placeholder = "Gagal memuat";
            });

        return { tsProv, tsKota, tsKec, tsKel, hidProv, hidKota, hidKec, hidKel };
    }

    let tambahSelects, editSelects;

    document.addEventListener("DOMContentLoaded", function() {
        tambahSelects = setupCascadingDropdown('');
        editSelects = setupCascadingDropdown('edit_');
    });

    function openEditModal(id, nama, provinsi, kota, kecamatan, kelurahan) {
        document.getElementById('form-edit').action = '/wilayah/' + id;
        document.getElementById('edit_name').value = nama;
        
        editSelects.hidProv.value = provinsi || '';
        editSelects.hidKota.value = kota || '';
        editSelects.hidKec.value = kecamatan || '';
        editSelects.hidKel.value = kelurahan || '';

        // Add dummy options so TomSelect shows the current names if available
        if (provinsi) {
            editSelects.tsProv.addOption({id: provinsi, name: provinsi});
            editSelects.tsProv.setValue(provinsi, true);
        }
        if (kota) {
            editSelects.tsKota.enable();
            editSelects.tsKota.addOption({id: kota, name: kota});
            editSelects.tsKota.setValue(kota, true);
        }
        if (kecamatan) {
            editSelects.tsKec.enable();
            editSelects.tsKec.addOption({id: kecamatan, name: kecamatan});
            editSelects.tsKec.setValue(kecamatan, true);
        }
        if (kelurahan) {
            editSelects.tsKel.enable();
            editSelects.tsKel.addOption({id: kelurahan, name: kelurahan});
            editSelects.tsKel.setValue(kelurahan, true);
        }

        document.getElementById('modal-edit').classList.remove('hidden');
    }
</script>



@endsection
