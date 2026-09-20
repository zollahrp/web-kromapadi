@extends('layouts.admin')

@section('content')
<div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Data Lahan</h1>
        <p class="text-gray-500 mt-1 text-sm">Kelola data lahan dan klasifikasinya di wilayah Anda.</p>
    </div>
    <div class="flex items-center gap-3">
        <!-- Button Trigger Modal Tambah -->
        <button onclick="document.getElementById('modal-tambah').classList.remove('hidden')" class="bg-[#5C52E7] hover:bg-[#4a42b9] text-white px-5 py-2.5 rounded-xl font-medium transition-colors shadow-sm flex items-center gap-2 text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Lahan
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
    <form action="{{ route('lahan.index') }}" method="GET" class="flex flex-col md:flex-row md:items-end gap-3" id="filterForm">
        <!-- Search Name -->
        <div class="flex-1 min-w-[250px] relative">
            <label class="block text-sm font-medium text-gray-700 mb-1 truncate">Cari Lahan / KTD</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama lahan atau KTD lalu Enter..." class="pl-10 mt-1 focus:ring-[#5C52E7] focus:border-[#5C52E7] block w-full shadow-sm sm:text-sm border-gray-300 rounded-xl px-4 py-2 border outline-none transition-colors">
            </div>
        </div>
        
        <!-- Filter Klasifikasi -->
        <div class="w-full md:w-48 flex-shrink-0">
            <label class="block text-sm font-medium text-gray-700 mb-1 truncate">Klasifikasi</label>
            <select name="klasifikasi" onchange="document.getElementById('filterForm').submit()" class="mt-1 focus:ring-[#5C52E7] focus:border-[#5C52E7] block w-full shadow-sm sm:text-sm border-gray-300 rounded-xl px-4 py-2 border outline-none transition-colors bg-white appearance-none cursor-pointer">
                <option value="">Semua Klasifikasi</option>
                @if(isset($klasifikasis))
                    @foreach($klasifikasis as $klas)
                        <option value="{{ $klas }}" {{ request('klasifikasi') == $klas ? 'selected' : '' }}>{{ $klas }}</option>
                    @endforeach
                @endif
            </select>
        </div>
        
        @if(request()->hasAny(['search', 'klasifikasi']))
        <div class="flex items-center gap-3 flex-shrink-0">
            <a href="{{ route('lahan.index') }}" class="px-4 py-2 bg-red-50 text-red-600 rounded-xl text-sm font-medium hover:bg-red-100 transition-colors border border-red-100 mt-1 flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                Reset
            </a>
        </div>
        @endif
    </form>
</div>
<!-- Table Data Lahan -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100">
                    <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">No</th>
                    <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Lahan</th>
                    <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Kelompok Tani</th>
                    <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Klasifikasi</th>
                    <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Dibuat Pada</th>
                    <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($lahans as $index => $lahan)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="py-4 px-6 text-sm text-gray-500">{{ $index + 1 }}</td>
                    <td class="py-4 px-6">
                        <div class="font-medium text-gray-900">{{ $lahan->name }}</div>
                    </td>
                    <td class="py-4 px-6 text-sm text-gray-500">
                        {{ $lahan->kelompokTani ? $lahan->kelompokTani->name : '-' }}
                    </td>
                    <td class="py-4 px-6 text-sm text-gray-500">
                        @if($lahan->klasifikasi)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ $lahan->klasifikasi }}
                            </span>
                        @else
                            -
                        @endif
                    </td>
                    <td class="py-4 px-6 text-sm text-gray-500">{{ $lahan->created_at->format('d M Y') }}</td>
                    <td class="py-4 px-6 flex items-center justify-end gap-3">
                        <!-- Button Edit -->
                        <button onclick="openEditModal('{{ $lahan->id }}', '{{ $lahan->name }}', '{{ $lahan->kelompok_tani_id }}', '{{ $lahan->klasifikasi }}')" class="text-blue-500 hover:text-blue-700 transition-colors p-2 hover:bg-blue-50 rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </button>
                        <!-- Button Delete -->
                        <form action="{{ route('lahan.destroy', $lahan->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus Lahan ini?');">
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
                            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            <p>Belum ada data Lahan di wilayah ini.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Lahan -->
<div id="modal-tambah" class="fixed inset-0 z-50 hidden animate-backdrop" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="document.getElementById('modal-tambah').classList.add('hidden')"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-visible shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full animate-modal relative z-10">
            <form action="{{ route('lahan.store') }}" method="POST">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Tambah Data Lahan</h3>
                            
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Lahan</label>
                                    <input type="text" name="name" id="name" required class="mt-1 focus:ring-[#5C52E7] focus:border-[#5C52E7] block w-full shadow-sm sm:text-sm border-gray-300 rounded-xl px-4 py-2 border outline-none transition-colors" placeholder="Contoh: Lahan Padi Sektor A">
                                </div>
                                <div>
                                    <label for="kelompok_tani_id" class="block text-sm font-medium text-gray-700">Pemilik Kelompok Tani</label>
                                    <select name="kelompok_tani_id" id="kelompok_tani_id" required class="mt-1 focus:ring-[#5C52E7] focus:border-[#5C52E7] block w-full shadow-sm sm:text-sm border-gray-300 rounded-xl px-4 py-2 border outline-none transition-colors">
                                        <option value="">-- Pilih Kelompok Tani --</option>
                                        @foreach($ktds as $ktd)
                                            <option value="{{ $ktd->id }}">{{ $ktd->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="klasifikasi" class="block text-sm font-medium text-gray-700">Klasifikasi Lahan</label>
                                    <input type="text" name="klasifikasi" id="klasifikasi" class="mt-1 focus:ring-[#5C52E7] focus:border-[#5C52E7] block w-full shadow-sm sm:text-sm border-gray-300 rounded-xl px-4 py-2 border outline-none transition-colors" placeholder="Contoh: Sawah Irigasi, Ladang (Opsional)">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100">
                    <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-[#5C52E7] text-base font-medium text-white hover:bg-[#4a42b9] focus:outline-none sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                        Simpan Lahan
                    </button>
                    <button type="button" onclick="document.getElementById('modal-tambah').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Lahan -->
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
                            <div class="border-b border-gray-100 pb-4 mb-4"><h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">Edit Lahan</h3></div>
                            
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label for="edit_name" class="block text-sm font-medium text-gray-700">Nama Lahan</label>
                                    <input type="text" name="name" id="edit_name" required class="mt-1 focus:ring-[#5C52E7] focus:border-[#5C52E7] block w-full shadow-sm sm:text-sm border-gray-300 rounded-xl px-4 py-2 border outline-none transition-colors">
                                </div>
                                <div>
                                    <label for="edit_kelompok_tani_id" class="block text-sm font-medium text-gray-700">Pemilik Kelompok Tani</label>
                                    <select name="kelompok_tani_id" id="edit_ktd" required class="mt-1 block w-full">
                                        <option value="">-- Pilih Kelompok Tani --</option>
                                        @foreach($ktds as $ktd)
                                            <option value="{{ $ktd->id }}">{{ $ktd->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="edit_klasifikasi" class="block text-sm font-medium text-gray-700">Klasifikasi Lahan</label>
                                    <input type="text" name="klasifikasi" id="edit_klasifikasi" class="mt-1 focus:ring-[#5C52E7] focus:border-[#5C52E7] block w-full shadow-sm sm:text-sm border-gray-300 rounded-xl px-4 py-2 border outline-none transition-colors">
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

<script>
    let tsEditKtd;
    document.addEventListener('DOMContentLoaded', function() {
        new TomSelect("#add_ktd", {
            create: false,
            sortField: { field: "text", direction: "asc" },
            placeholder: "Pilih Kelompok Tani..."
        });

        tsEditKtd = new TomSelect("#edit_ktd", {
            create: false,
            sortField: { field: "text", direction: "asc" },
            placeholder: "Pilih Kelompok Tani..."
        });
    });

    function openEditModal(id, nama, kelompok_tani_id, klasifikasi) {
        document.getElementById('form-edit').action = '/lahan/' + id;
        document.getElementById('edit_name').value = nama;
        if(tsEditKtd) {
            tsEditKtd.setValue(kelompok_tani_id);
        }
        document.getElementById('edit_klasifikasi').value = klasifikasi || '';
        document.getElementById('modal-edit').classList.remove('hidden');
    }
</script>
@endsection
