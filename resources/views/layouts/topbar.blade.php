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
        <button class="relative text-gray-400 hover:text-gray-500 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
            <span class="absolute top-0 right-0 block h-2.5 w-2.5 rounded-full bg-red-500 ring-2 ring-white"></span>
        </button>

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
