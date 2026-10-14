<!-- Sidebar -->
<aside class="w-[280px] bg-white border-r border-gray-100 flex flex-col h-full flex-shrink-0 transition-all duration-300">
    <!-- Logo Area -->
    <div class="h-20 flex items-center px-6 border-b border-gray-100 flex-shrink-0 bg-white">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
            <div class="w-10 h-10 rounded-xl overflow-hidden flex items-center justify-center bg-gradient-to-br from-emerald-50 via-indigo-50 to-purple-50 border border-gray-100 p-1.5 shadow-xs group-hover:scale-105 transition-all flex-shrink-0">
                <img src="{{ asset('images/logo.png') }}" 
                     onerror="this.onerror=null; this.src='{{ asset('images/padinexa-logo-1.jpg') }}';" 
                     alt="PadiNexa Logo" 
                     class="w-full h-full object-contain">
            </div>
            <div class="flex flex-col">
                <div class="flex items-center gap-1.5">
                    <span class="text-gray-900 font-extrabold text-base leading-tight tracking-tight group-hover:text-[#5C52E7] transition-colors">PadiNexa</span>
                    <span class="px-1.5 py-0.5 text-[9px] font-extrabold text-emerald-700 bg-emerald-100/70 rounded-md tracking-wider">AI</span>
                </div>
                <span class="text-gray-400 text-[11px] font-medium tracking-wide">Smart Agriculture</span>
            </div>
        </a>
    </div>

    <!-- Navigation List -->
    <div class="flex-1 overflow-y-auto py-5 px-3.5 space-y-5">
        
        <!-- Menu Utama -->
        <div>
            <div class="px-3 mb-2 text-[10px] font-bold tracking-wider text-gray-400 uppercase">Menu Utama</div>
            <div class="space-y-1">
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}" 
                   class="relative group flex items-center gap-3 px-3 py-2 rounded-xl text-sm transition-all duration-150 {{ request()->routeIs('dashboard') ? 'bg-[#F0EFFF] text-[#5C52E7] font-semibold' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50/80 font-medium' }}">
                    @if(request()->routeIs('dashboard'))
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-5 bg-[#5C52E7] rounded-r-full"></span>
                    @endif
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-150 {{ request()->routeIs('dashboard') ? 'bg-[#5C52E7] text-white shadow-xs' : 'text-gray-400 group-hover:text-[#5C52E7] group-hover:bg-[#F0EFFF]/60' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                    </div>
                    <span>Dashboard</span>
                </a>
            </div>
        </div>

        <!-- Pertanian -->
        <div>
            <div class="px-3 mb-2 text-[10px] font-bold tracking-wider text-gray-400 uppercase">Pertanian</div>
            <div class="space-y-1">
                <!-- Kelompok Tani (KTD) -->
                <a href="{{ route('ktd.index') }}" 
                   class="relative group flex items-center gap-3 px-3 py-2 rounded-xl text-sm transition-all duration-150 {{ request()->routeIs('ktd.*') ? 'bg-[#F0EFFF] text-[#5C52E7] font-semibold' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50/80 font-medium' }}">
                    @if(request()->routeIs('ktd.*'))
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-5 bg-[#5C52E7] rounded-r-full"></span>
                    @endif
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-150 {{ request()->routeIs('ktd.*') ? 'bg-[#5C52E7] text-white shadow-xs' : 'text-gray-400 group-hover:text-[#5C52E7] group-hover:bg-[#F0EFFF]/60' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <span class="truncate">Kelompok Tani (KTD)</span>
                </a>

                <!-- Data Lahan -->
                <a href="{{ route('lahan.index') }}" 
                   class="relative group flex items-center gap-3 px-3 py-2 rounded-xl text-sm transition-all duration-150 {{ request()->routeIs('lahan.*') ? 'bg-[#F0EFFF] text-[#5C52E7] font-semibold' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50/80 font-medium' }}">
                    @if(request()->routeIs('lahan.*'))
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-5 bg-[#5C52E7] rounded-r-full"></span>
                    @endif
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-150 {{ request()->routeIs('lahan.*') ? 'bg-[#5C52E7] text-white shadow-xs' : 'text-gray-400 group-hover:text-[#5C52E7] group-hover:bg-[#F0EFFF]/60' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                        </svg>
                    </div>
                    <span>Data Lahan</span>
                </a>

                <!-- Manajemen Tandur -->
                <a href="{{ route('tandur.index') }}" 
                   class="relative group flex items-center gap-3 px-3 py-2 rounded-xl text-sm transition-all duration-150 {{ request()->routeIs('tandur.*') ? 'bg-[#F0EFFF] text-[#5C52E7] font-semibold' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50/80 font-medium' }}">
                    @if(request()->routeIs('tandur.*'))
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-5 bg-[#5C52E7] rounded-r-full"></span>
                    @endif
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-150 {{ request()->routeIs('tandur.*') ? 'bg-[#5C52E7] text-white shadow-xs' : 'text-gray-400 group-hover:text-[#5C52E7] group-hover:bg-[#F0EFFF]/60' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <span>Manajemen Tandur</span>
                </a>
            </div>
        </div>

        <!-- Monitoring & AI -->
        <div>
            <div class="px-3 mb-2 text-[10px] font-bold tracking-wider text-gray-400 uppercase">Monitoring & AI</div>
            <div class="space-y-1">
                <!-- Scan & Riwayat -->
                <a href="{{ route('riwayat.index') }}" 
                   class="relative group flex items-center gap-3 px-3 py-2 rounded-xl text-sm transition-all duration-150 {{ request()->routeIs('riwayat.*') ? 'bg-[#F0EFFF] text-[#5C52E7] font-semibold' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50/80 font-medium' }}">
                    @if(request()->routeIs('riwayat.*'))
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-5 bg-[#5C52E7] rounded-r-full"></span>
                    @endif
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-150 {{ request()->routeIs('riwayat.*') ? 'bg-[#5C52E7] text-white shadow-xs' : 'text-gray-400 group-hover:text-[#5C52E7] group-hover:bg-[#F0EFFF]/60' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </div>
                    <span>Scan & Riwayat</span>
                </a>

                <!-- Laporan Analisis -->
                <a href="{{ route('laporan.index') }}" 
                   class="relative group flex items-center gap-3 px-3 py-2 rounded-xl text-sm transition-all duration-150 {{ request()->routeIs('laporan.*') ? 'bg-[#F0EFFF] text-[#5C52E7] font-semibold' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50/80 font-medium' }}">
                    @if(request()->routeIs('laporan.*'))
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-5 bg-[#5C52E7] rounded-r-full"></span>
                    @endif
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-150 {{ request()->routeIs('laporan.*') ? 'bg-[#5C52E7] text-white shadow-xs' : 'text-gray-400 group-hover:text-[#5C52E7] group-hover:bg-[#F0EFFF]/60' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <span>Laporan Analisis</span>
                </a>
            </div>
        </div>

        <!-- Sistem -->
        <div>
            <div class="px-3 mb-2 text-[10px] font-bold tracking-wider text-gray-400 uppercase">Sistem</div>
            <div class="space-y-1">
                @if(auth()->user()->role === 'super_admin')
                <!-- Wilayah -->
                <a href="{{ route('wilayah.index') }}" 
                   class="relative group flex items-center gap-3 px-3 py-2 rounded-xl text-sm transition-all duration-150 {{ request()->routeIs('wilayah.*') ? 'bg-[#F0EFFF] text-[#5C52E7] font-semibold' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50/80 font-medium' }}">
                    @if(request()->routeIs('wilayah.*'))
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-5 bg-[#5C52E7] rounded-r-full"></span>
                    @endif
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-150 {{ request()->routeIs('wilayah.*') ? 'bg-[#5C52E7] text-white shadow-xs' : 'text-gray-400 group-hover:text-[#5C52E7] group-hover:bg-[#F0EFFF]/60' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <span>Data Wilayah</span>
                </a>

                <!-- Kelola Pengguna -->
                <a href="{{ route('users.index') }}" 
                   class="relative group flex items-center gap-3 px-3 py-2 rounded-xl text-sm transition-all duration-150 {{ request()->routeIs('users.*') ? 'bg-[#F0EFFF] text-[#5C52E7] font-semibold' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50/80 font-medium' }}">
                    @if(request()->routeIs('users.*'))
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-5 bg-[#5C52E7] rounded-r-full"></span>
                    @endif
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-150 {{ request()->routeIs('users.*') ? 'bg-[#5C52E7] text-white shadow-xs' : 'text-gray-400 group-hover:text-[#5C52E7] group-hover:bg-[#F0EFFF]/60' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <span>Kelola Pengguna</span>
                </a>
                @endif

                <!-- Pengaturan -->
                <a href="{{ route('settings.index') }}" 
                   class="relative group flex items-center gap-3 px-3 py-2 rounded-xl text-sm transition-all duration-150 {{ request()->routeIs('settings.*') ? 'bg-[#F0EFFF] text-[#5C52E7] font-semibold' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50/80 font-medium' }}">
                    @if(request()->routeIs('settings.*'))
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-5 bg-[#5C52E7] rounded-r-full"></span>
                    @endif
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-150 {{ request()->routeIs('settings.*') ? 'bg-[#5C52E7] text-white shadow-xs' : 'text-gray-400 group-hover:text-[#5C52E7] group-hover:bg-[#F0EFFF]/60' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <span>Pengaturan</span>
                </a>
            </div>
        </div>

    </div>

    <!-- Bottom Section: Role Info & User Account -->
    <div class="p-3.5 border-t border-gray-100 flex-shrink-0 bg-white">
        <div class="p-2.5 rounded-2xl bg-gray-50/70 border border-gray-100 flex items-center justify-between gap-2.5">
            <div class="flex items-center gap-2.5 min-w-0">
                <div class="relative flex-shrink-0">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-[#5C52E7] to-indigo-600 text-white flex items-center justify-center text-xs font-bold shadow-xs">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                    <span class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 rounded-full bg-emerald-500 ring-2 ring-white"></span>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-semibold text-gray-800 truncate leading-tight">{{ auth()->user()->name }}</p>
                    <div class="flex items-center gap-1.5 mt-0.5">
                        <span class="text-[10px] font-medium text-gray-400 truncate">
                            @if(auth()->user()->role === 'super_admin')
                                Super Admin
                            @else
                                {{ auth()->user()->wilayah ? auth()->user()->wilayah->name : 'Penyuluh' }}
                            @endif
                        </span>
                        <span class="px-1.5 py-0.2 text-[8px] font-bold text-[#5C52E7] bg-white rounded border border-gray-200/60 flex-shrink-0">
                            {{ auth()->user()->role === 'super_admin' ? 'Akses Penuh' : 'Online' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Logout Button -->
            <form method="POST" action="{{ route('logout') }}" class="flex-shrink-0">
                @csrf
                <button type="submit" 
                        class="p-2 text-gray-400 hover:text-red-600 hover:bg-white rounded-xl transition-all duration-150 cursor-pointer"
                        title="Keluar dari akun">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</aside>
