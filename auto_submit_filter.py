import re

with open('resources/views/admin/users/index.blade.php', 'r') as f:
    content = f.read()

# Update the form UI for auto-submit
old_form_ui = """<!-- Filter Section -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
    <form action="{{ route('users.index') }}" method="GET" class="flex flex-col md:flex-row md:items-end gap-4">
        <!-- Search Name/Email -->
        <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 mb-1">Cari Pengguna</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama atau email..." class="pl-10 mt-1 focus:ring-[#5C52E7] focus:border-[#5C52E7] block w-full shadow-sm sm:text-sm border-gray-300 rounded-xl px-4 py-2 border outline-none transition-colors">
            </div>
        </div>
        
        <!-- Filter Role -->
        <div class="w-full md:w-48">
            <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
            <select name="role" class="mt-1 focus:ring-[#5C52E7] focus:border-[#5C52E7] block w-full shadow-sm sm:text-sm border-gray-300 rounded-xl px-4 py-2 border outline-none transition-colors bg-white appearance-none">
                <option value="">Semua Role</option>
                <option value="super_admin" {{ request('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                <option value="master_wilayah" {{ request('role') == 'master_wilayah' ? 'selected' : '' }}>Master Wilayah</option>
            </select>
        </div>
        
        <!-- Filter Provinsi -->
        <div class="w-full md:w-56">
            <label class="block text-sm font-medium text-gray-700 mb-1">Provinsi (Master Wilayah)</label>
            <select name="provinsi" class="mt-1 focus:ring-[#5C52E7] focus:border-[#5C52E7] block w-full shadow-sm sm:text-sm border-gray-300 rounded-xl px-4 py-2 border outline-none transition-colors bg-white appearance-none">
                <option value="">Semua Provinsi</option>
                @if(isset($provinsis))
                    @foreach($provinsis as $prov)
                        <option value="{{ $prov }}" {{ request('provinsi') == $prov ? 'selected' : '' }}>{{ $prov }}</option>
                    @endforeach
                @endif
            </select>
        </div>
        
        <div class="flex items-center gap-3">
            @if(request()->hasAny(['search', 'role', 'provinsi']))
            <a href="{{ route('users.index') }}" class="px-4 py-2 bg-gray-50 text-gray-700 rounded-xl text-sm font-medium hover:bg-gray-100 transition-colors border border-gray-200 mt-1">
                Reset
            </a>
            @endif
            <button type="submit" class="px-4 py-2 bg-[#5C52E7] text-white rounded-xl text-sm font-medium hover:bg-[#4a42b9] transition-colors shadow-sm flex items-center justify-center gap-2 mt-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                Filter
            </button>
        </div>
    </form>
</div>"""

new_form_ui = """<!-- Filter Section -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
    <form action="{{ route('users.index') }}" method="GET" class="flex flex-col md:flex-row md:items-end gap-4" id="filterForm">
        <!-- Search Name/Email -->
        <div class="flex-1 relative">
            <label class="block text-sm font-medium text-gray-700 mb-1">Cari Pengguna</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama lalu Enter..." class="pl-10 mt-1 focus:ring-[#5C52E7] focus:border-[#5C52E7] block w-full shadow-sm sm:text-sm border-gray-300 rounded-xl px-4 py-2 border outline-none transition-colors">
            </div>
        </div>
        
        <!-- Filter Role -->
        <div class="w-full md:w-48">
            <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
            <select name="role" onchange="document.getElementById('filterForm').submit()" class="mt-1 focus:ring-[#5C52E7] focus:border-[#5C52E7] block w-full shadow-sm sm:text-sm border-gray-300 rounded-xl px-4 py-2 border outline-none transition-colors bg-white appearance-none cursor-pointer">
                <option value="">Semua Role</option>
                <option value="super_admin" {{ request('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                <option value="master_wilayah" {{ request('role') == 'master_wilayah' ? 'selected' : '' }}>Master Wilayah</option>
            </select>
        </div>
        
        <!-- Filter Provinsi -->
        <div class="w-full md:w-56">
            <label class="block text-sm font-medium text-gray-700 mb-1">Provinsi (Master Wilayah)</label>
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
        <div class="flex items-center gap-3">
            <a href="{{ route('users.index') }}" class="px-4 py-2 bg-red-50 text-red-600 rounded-xl text-sm font-medium hover:bg-red-100 transition-colors border border-red-100 mt-1 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                Reset Filter
            </a>
        </div>
        @endif
    </form>
</div>"""

content = content.replace(old_form_ui, new_form_ui)

with open('resources/views/admin/users/index.blade.php', 'w') as f:
    f.write(content)
