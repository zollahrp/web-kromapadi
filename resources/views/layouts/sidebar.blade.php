<!-- Sidebar -->
<aside class="w-[280px] bg-white border-r border-gray-100 flex flex-col h-full flex-shrink-0 transition-all duration-300">
    <!-- Logo Area -->
    <div class="h-20 flex items-center px-6 border-b border-gray-50">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-[#5C52E7] rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-sm">
                <!-- Leaf Icon SVG (placeholder) -->
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
            </div>
            <div>
                <h2 class="text-gray-900 font-bold text-lg leading-tight">Mulyaharja</h2>
                <p class="text-gray-400 text-xs">Admin Panel</p>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <div class="flex-1 overflow-y-auto py-6 px-4 space-y-1">
        
        <!-- Active Menu Item / Dashboard -->
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-colors mt-2 {{ request()->routeIs('dashboard') ? 'bg-[#F0EFFF] text-[#5C52E7]' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            Dashboard
        </a>

        @if(auth()->user()->role === 'super_admin')
        <!-- Wilayah -->
        <a href="{{ route('wilayah.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-colors mt-2 {{ request()->routeIs('wilayah.*') ? 'bg-[#F0EFFF] text-[#5C52E7]' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            Wilayah
        </a>
        @endif

        <!-- Kelompok Tani Dewasa -->
        <a href="{{ route('ktd.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-colors mt-2 {{ request()->routeIs('ktd.*') ? 'bg-[#F0EFFF] text-[#5C52E7]' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            Kelompok Tani Dewasa
        </a>



        <!-- Lahan -->
        <a href="{{ route('lahan.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-colors mt-2 {{ request()->routeIs('lahan.*') ? 'bg-[#F0EFFF] text-[#5C52E7]' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
            Lahan
        </a>

        <!-- Tandur -->
        <a href="{{ route('tandur.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-colors mt-2 {{ request()->routeIs('tandur.*') ? 'bg-[#F0EFFF] text-[#5C52E7]' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            Manajemen Tandur
        </a>

        <!-- Scan & Riwayat -->
        <a href="{{ route('riwayat.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-colors mt-2 {{ request()->routeIs('riwayat.*') ? 'bg-[#F0EFFF] text-[#5C52E7]' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
            Scan & Riwayat
        </a>

        <!-- Laporan -->
        <a href="{{ route('laporan.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-colors mt-2 {{ request()->routeIs('laporan.*') ? 'bg-[#F0EFFF] text-[#5C52E7]' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Laporan
        </a>

        @if(auth()->user()->role === 'super_admin')
        <!-- Pengguna -->
        <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-colors mt-2 {{ request()->routeIs('users.*') ? 'bg-[#F0EFFF] text-[#5C52E7]' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            Pengguna
        </a>
        @endif

        <!-- Pengaturan -->
        <a href="{{ route('settings.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-colors mt-2 {{ request()->routeIs('settings.*') ? 'bg-[#F0EFFF] text-[#5C52E7]' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            Pengaturan
        </a>
    </div>

    <!-- Bottom Info Cards -->
    <div class="p-4 border-t border-gray-50 space-y-4">
        
        @if(auth()->user()->role === 'master_wilayah')
        <!-- Akun Master Wilayah Card -->
        <div class="bg-[#F8F9FE] p-4 rounded-xl border border-[#EDEEF6]">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-full bg-[#E5E4FF] text-[#5C52E7] flex items-center justify-center flex-shrink-0 mt-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
                <div>
                    <h4 class="text-xs font-semibold text-gray-900">Akun Master Wilayah</h4>
                    <p class="text-xs text-gray-500 mt-1 truncate w-40">{{ auth()->user()->wilayah ? auth()->user()->wilayah->name : 'Belum Ditentukan' }}</p>
                </div>
            </div>
            <a href="#" class="text-xs text-[#5C52E7] font-medium mt-3 inline-block hover:underline">Lihat Detail Wilayah &gt;</a>
        </div>
        @endif

        @if(auth()->user()->role === 'super_admin')
        <!-- Super Admin Info Card -->
        <div class="bg-white p-4 rounded-xl border border-gray-100 flex justify-between items-center shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)]">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-gray-100 text-[#5C52E7] flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                <div>
                    <h4 class="text-xs font-semibold text-gray-900">Super Admin</h4>
                    <p class="text-xs text-gray-500">Dikelola oleh kami</p>
                </div>
            </div>
            <div class="w-2 h-2 rounded-full bg-green-500"></div>
        </div>
        @endif

        <!-- Logout Button -->
        <form method="POST" action="{{ route('logout') }}" class="pt-2">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-red-600 bg-red-50 hover:bg-red-100 rounded-xl transition-colors border border-red-100">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                Keluar
            </button>
        </form>
    </div>
</aside>
