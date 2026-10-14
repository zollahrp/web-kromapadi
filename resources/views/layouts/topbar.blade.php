<!-- Topbar -->
<header class="h-20 bg-white border-b border-gray-100 flex items-center justify-between px-6 lg:px-8 flex-shrink-0">
    
    <!-- Search Bar -->
    <div class="flex-1 max-w-md relative">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>
        <input type="text" class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl leading-5 bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-1 focus:ring-[#5C52E7] focus:border-[#5C52E7] sm:text-sm transition-colors" placeholder="Cari sesuatu...">
    </div>

    <!-- Right Actions -->
    <div class="flex items-center gap-6">
        
        <!-- Notification -->
        <div class="relative" id="notificationContainer">
            <button id="notificationBtn" class="relative text-gray-400 hover:text-gray-500 transition-colors focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                <span class="absolute top-0 right-0 block h-2.5 w-2.5 rounded-full bg-red-500 ring-2 ring-white"></span>
            </button>
            
            <!-- Notification Dropdown -->
            <div id="notificationDropdown" class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-[0_4px_20px_-4px_rgba(6,81,237,0.1)] border border-gray-100 hidden z-50 overflow-hidden transform opacity-0 scale-95 transition-all duration-200 origin-top-right">
                <div class="px-5 py-4 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                    <h3 class="text-sm font-bold text-gray-900">Notifikasi</h3>
                    <span class="text-[10px] font-bold text-[#5C52E7] bg-[#F0EFFF] px-2 py-0.5 rounded-md">3 Baru</span>
                </div>
                <div class="max-h-[320px] overflow-y-auto divide-y divide-gray-50">
                    <!-- Item 1 -->
                    <a href="#" class="block px-5 py-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-xl bg-[#E8F8F1] text-[#22C55E] flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900 mb-0.5">Pelatihan Pengendalian OPT</p>
                                <p class="text-xs text-gray-500 leading-relaxed">Jadwal pelatihan untuk kelompok tani hari ini pukul 09:00 WIB.</p>
                                <p class="text-[10px] text-gray-400 mt-1.5 font-medium">Baru saja</p>
                            </div>
                        </div>
                    </a>
                    
                    <!-- Item 2 -->
                    <a href="#" class="block px-5 py-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-xl bg-[#E0F2FE] text-[#0EA5E9] flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900 mb-0.5">Monitoring Lahan</p>
                                <p class="text-xs text-gray-500 leading-relaxed">Terdapat 3 lahan yang perlu dimonitoring minggu ini.</p>
                                <p class="text-[10px] text-gray-400 mt-1.5 font-medium">2 jam yang lalu</p>
                            </div>
                        </div>
                    </a>

                    <!-- Item 3 -->
                    <a href="#" class="block px-5 py-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-xl bg-[#FEE2E2] text-[#EF4444] flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900 mb-0.5">Evaluasi Bulanan</p>
                                <p class="text-xs text-gray-500 leading-relaxed">Laporan evaluasi kelompok tani bulan ini telah tersedia.</p>
                                <p class="text-[10px] text-gray-400 mt-1.5 font-medium">Kemarin</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="p-3 border-t border-gray-50 text-center bg-gray-50/30">
                    <a href="#" class="text-xs font-semibold text-[#5C52E7] hover:text-[#4d44c9] transition-colors">Lihat semua notifikasi</a>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const notifBtn = document.getElementById('notificationBtn');
                const notifDropdown = document.getElementById('notificationDropdown');
                
                notifBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    if (notifDropdown.classList.contains('hidden')) {
                        notifDropdown.classList.remove('hidden');
                        // Small delay to allow display block to apply before animating opacity/transform
                        setTimeout(() => {
                            notifDropdown.classList.remove('opacity-0', 'scale-95');
                            notifDropdown.classList.add('opacity-100', 'scale-100');
                        }, 10);
                    } else {
                        notifDropdown.classList.remove('opacity-100', 'scale-100');
                        notifDropdown.classList.add('opacity-0', 'scale-95');
                        setTimeout(() => {
                            notifDropdown.classList.add('hidden');
                        }, 200);
                    }
                });

                document.addEventListener('click', function(e) {
                    if (!notifBtn.contains(e.target) && !notifDropdown.contains(e.target)) {
                        notifDropdown.classList.remove('opacity-100', 'scale-100');
                        notifDropdown.classList.add('opacity-0', 'scale-95');
                        setTimeout(() => {
                            notifDropdown.classList.add('hidden');
                        }, 200);
                    }
                });
            });
        </script>

        <div class="h-8 w-px bg-gray-200"></div>

        <!-- User Profile -->
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-indigo-100 overflow-hidden border border-gray-200">
                <!-- User Avatar Placeholder -->
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=5C52E7&color=fff" alt="User Avatar" class="w-full h-full object-cover">
            </div>
            <div class="hidden sm:block">
                <div class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</div>
                <div class="text-xs text-gray-500">{{ ucwords(str_replace('_', ' ', auth()->user()->role)) }}</div>
            </div>
            
            <!-- Dropdown Trigger (simulated with logout for now) -->
            <form action="{{ route('logout') }}" method="POST" class="ml-2">
                @csrf
                <button type="submit" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
            </form>
        </div>
        
    </div>
</header>
