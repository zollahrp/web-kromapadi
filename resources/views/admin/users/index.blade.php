@extends('layouts.admin')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<style>
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

.ts-control {
    border-radius: 0.75rem !important;
    padding: 0.5rem 1rem !important;
    border-color: #D1D5DB !important;
}
.ts-control.focus {
    border-color: #5C52E7 !important;
    box-shadow: 0 0 0 1px #5C52E7 !important;
}
</style>

<div class="mb-8 flex justify-between items-end">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Pengguna Sistem</h1>
        <p class="text-gray-500 mt-1 text-sm">Kelola akun Super Admin dan Master Wilayah.</p>
    </div>
    <div>
        <button onclick="document.getElementById('modal-add').classList.remove('hidden')" class="bg-[#5C52E7] hover:bg-[#4a42b9] text-white px-4 py-2 rounded-xl text-sm font-medium transition-colors shadow-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Pengguna
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

@if(session('error'))
<div id="error-alert" class="fixed top-6 right-6 z-[100] max-w-sm w-full bg-white rounded-2xl shadow-xl border border-red-100 p-4 flex items-start gap-3 transform transition-all duration-500 translate-y-0 opacity-100" style="animation: slideInRight 0.5s cubic-bezier(0.16, 1, 0.3, 1);">
    <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center flex-shrink-0">
        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
    </div>
    <div class="flex-1">
        <h3 class="text-sm font-bold text-gray-900">Gagal!</h3>
        <p class="text-sm text-gray-500 mt-0.5">{{ session('error') }}</p>
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

@if($errors->any())
<div class="mb-6 p-4 bg-red-50 border border-red-100 rounded-2xl flex items-start gap-3">
    <svg class="w-5 h-5 text-red-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
    <div>
        <h3 class="text-sm font-medium text-red-800">Terdapat Kesalahan</h3>
        <ul class="text-sm text-red-600 mt-1 list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif

<!-- Filter Section -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
    <form action="{{ route('users.index') }}" method="GET" class="flex flex-col md:flex-row md:items-end gap-3" id="filterForm">
        <!-- Search Name/Email -->
        <div class="flex-1 min-w-[250px] relative">
            <label class="block text-sm font-medium text-gray-700 mb-1 truncate">Cari Pengguna</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama lalu Enter..." class="pl-10 mt-1 focus:ring-[#5C52E7] focus:border-[#5C52E7] block w-full shadow-sm sm:text-sm border-gray-300 rounded-xl px-4 py-2 border outline-none transition-colors">
            </div>
        </div>
        
        <!-- Filter Role -->
        <div class="w-full md:w-36 flex-shrink-0">
            <label class="block text-sm font-medium text-gray-700 mb-1 truncate">Role</label>
            <select name="role" onchange="document.getElementById('filterForm').submit()" class="mt-1 focus:ring-[#5C52E7] focus:border-[#5C52E7] block w-full shadow-sm sm:text-sm border-gray-300 rounded-xl px-4 py-2 border outline-none transition-colors bg-white appearance-none cursor-pointer">
                <option value="">Semua Role</option>
                <option value="super_admin" {{ request('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                <option value="master_wilayah" {{ request('role') == 'master_wilayah' ? 'selected' : '' }}>Master Wilayah</option>
            </select>
        </div>
        
        <!-- Filter Provinsi -->
        <div class="w-full md:w-48 flex-shrink-0">
            <label class="block text-sm font-medium text-gray-700 mb-1 truncate">Provinsi (Master)</label>
            <select name="provinsi" onchange="document.getElementById('filterForm').submit()" class="mt-1 focus:ring-[#5C52E7] focus:border-[#5C52E7] block w-full shadow-sm sm:text-sm border-gray-300 rounded-xl px-4 py-2 border outline-none transition-colors bg-white appearance-none cursor-pointer">
                <option value="">Semua Provinsi</option>
                @if(isset($provinsis))
                    @foreach($provinsis as $prov)
                        <option value="{{ $prov }}" {{ request('provinsi') == $prov ? 'selected' : '' }}>{{ $prov }}</option>
                    @endforeach
                @endif
            </select>
        </div>
        
        @if(request()->hasAny(['search', 'role', 'provinsi']))
        <div class="flex items-center gap-3 flex-shrink-0">
            <a href="{{ route('users.index') }}" class="px-4 py-2 bg-red-50 text-red-600 rounded-xl text-sm font-medium hover:bg-red-100 transition-colors border border-red-100 mt-1 flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                Reset
            </a>
        </div>
        @endif
    </form>
</div>

<!-- Table -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100">
                    <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama & Email</th>
                    <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Role</th>
                    <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Wilayah</th>
                    <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($users as $user)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="py-4 px-6">
                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                        <div class="text-xs text-gray-500 mt-1">{{ $user->email }}</div>
                    </td>
                    <td class="py-4 px-6">
                        @if($user->role === 'super_admin')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                Super Admin
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Master Wilayah
                            </span>
                        @endif
                    </td>
                    <td class="py-4 px-6 text-sm text-gray-500">
                        {{ $user->wilayah ? $user->wilayah->name : '-' }}
                    </td>
                    <td class="py-4 px-6 flex items-center justify-end gap-3">
                        <button onclick="openEditModal({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}', '{{ $user->role }}', '{{ $user->wilayah_id }}')" class="text-gray-400 hover:text-[#5C52E7] transition-colors p-2 hover:bg-[#F0EFFF] rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        </button>
                        @if($user->id !== auth()->id())
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors p-2 hover:bg-red-50 rounded-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Pengguna -->
<div id="modal-add" class="fixed inset-0 z-50 hidden animate-backdrop" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="document.getElementById('modal-add').classList.add('hidden')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full animate-modal relative z-10">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <div class="border-b border-gray-100 pb-4 mb-4"><h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">Tambah Pengguna</h3></div>
                            <div class="mt-4 flex flex-col gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                                    <input type="text" name="name" required class="mt-1 focus:ring-[#5C52E7] focus:border-[#5C52E7] block w-full shadow-sm sm:text-sm border-gray-300 rounded-xl px-4 py-2 border outline-none transition-colors">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                    <input type="email" name="email" required class="mt-1 focus:ring-[#5C52E7] focus:border-[#5C52E7] block w-full shadow-sm sm:text-sm border-gray-300 rounded-xl px-4 py-2 border outline-none transition-colors">
                                </div>
                                <div class="bg-red-50 p-3 rounded-xl border border-red-100 flex items-start gap-2">
                                    <svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <p class="text-sm text-red-600 font-medium">Catatan: <span class="font-normal">Kata sandi default untuk akun ini adalah <strong>password</strong></span></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                                    <select name="role" id="add_role" required  class="mt-1 block w-full">
                                        <option value="master_wilayah">Master Wilayah</option>
                                        <option value="super_admin">Super Admin</option>
                                    </select>
                                </div>
                                <div id="add_wilayah_container">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Wilayah (Untuk Master Wilayah)</label>
                                    <select name="wilayah_id" id="add_wilayah_id" class="mt-1 block w-full">
                                        <option value="">Pilih Wilayah...</option>
                                        @foreach($wilayahs as $wilayah)
                                            <option value="{{ $wilayah->id }}">{{ $wilayah->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 flex justify-end gap-3 border-t border-gray-100">
                    <button type="button" onclick="document.getElementById('modal-add').classList.add('hidden')" class="w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:w-auto sm:text-sm transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-[#5C52E7] text-base font-medium text-white hover:bg-[#4a42b9] focus:outline-none sm:w-auto sm:text-sm transition-colors">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Pengguna -->
<div id="modal-edit" class="fixed inset-0 z-50 hidden animate-backdrop" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="document.getElementById('modal-edit').classList.add('hidden')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full animate-modal relative z-10">
            <form id="form-edit" method="POST">
                @csrf
                @method('PUT')
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <div class="border-b border-gray-100 pb-4 mb-4"><h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">Edit Pengguna</h3></div>
                            <div class="mt-4 flex flex-col gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                                    <input type="text" name="name" id="edit_name" required class="mt-1 focus:ring-[#5C52E7] focus:border-[#5C52E7] block w-full shadow-sm sm:text-sm border-gray-300 rounded-xl px-4 py-2 border outline-none transition-colors">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                    <input type="email" name="email" id="edit_email" required class="mt-1 focus:ring-[#5C52E7] focus:border-[#5C52E7] block w-full shadow-sm sm:text-sm border-gray-300 rounded-xl px-4 py-2 border outline-none transition-colors">
                                </div>
                                <div class="bg-red-50 p-3 rounded-xl border border-red-100 flex items-start gap-2">
                                    <svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <p class="text-sm text-red-600 font-medium">Catatan: <span class="font-normal">Kata sandi akun ini sudah diatur sebelumnya atau menggunakan default <strong>password</strong>.</span></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                                    <select name="role" id="edit_role" required  class="mt-1 block w-full">
                                        <option value="master_wilayah">Master Wilayah</option>
                                        <option value="super_admin">Super Admin</option>
                                    </select>
                                </div>
                                <div id="edit_wilayah_container">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Wilayah (Untuk Master Wilayah)</label>
                                    <select name="wilayah_id" id="edit_wilayah_id" class="mt-1 block w-full">
                                        <option value="">Pilih Wilayah...</option>
                                        @foreach($wilayahs as $wilayah)
                                            <option value="{{ $wilayah->id }}">{{ $wilayah->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 flex justify-end gap-3 border-t border-gray-100">
                    <button type="button" onclick="document.getElementById('modal-edit').classList.add('hidden')" class="w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:w-auto sm:text-sm transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-[#5C52E7] text-base font-medium text-white hover:bg-[#4a42b9] focus:outline-none sm:w-auto sm:text-sm transition-colors">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>

    let tsAddRole, tsEditRole, tsAddWilayah, tsEditWilayah;
    
    document.addEventListener("DOMContentLoaded", function() {
        const tsConfig = {
            create: false,
            sortField: {
                field: "text",
                direction: "asc"
            }
        };
        
        tsAddRole = new TomSelect("#add_role", {
            ...tsConfig,
            onChange: function(value) {
                toggleWilayah('add', value);
            }
        });
        
        tsEditRole = new TomSelect("#edit_role", {
            ...tsConfig,
            onChange: function(value) {
                toggleWilayah('edit', value);
            }
        });

        tsAddWilayah = new TomSelect("#add_wilayah_id", tsConfig);
        tsEditWilayah = new TomSelect("#edit_wilayah_id", tsConfig);
        
        // Initial state
        toggleWilayah('add', tsAddRole.getValue());
    });

    function toggleWilayah(type, roleValue) {
        let role = roleValue;
        if (!role) {
            // fallback if called directly
            const roleEl = document.getElementById(type + '_role');
            if (roleEl) role = roleEl.value;
        }
        const container = document.getElementById(type + '_wilayah_container');
        if (!container) return;
        
        if (role === 'master_wilayah') {
            container.style.display = 'block';
        } else {
            container.style.display = 'none';
        }
    }

    function openEditModal(id, name, email, role, wilayahId) {
        document.getElementById('form-edit').action = '/users/' + id;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_email').value = email;
        
        if(tsEditRole) tsEditRole.setValue(role);
        if(tsEditWilayah) tsEditWilayah.setValue(wilayahId || '');
        
        toggleWilayah('edit', role);
        
        document.getElementById('modal-edit').classList.remove('hidden');
    }

</script>
@endsection
