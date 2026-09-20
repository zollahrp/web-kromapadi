import re

with open('resources/views/layouts/sidebar.blade.php', 'r') as f:
    content = f.read()

old_block = """        <!-- Akun Master Wilayah Card -->
        <div class="bg-[#F8F9FE] p-4 rounded-xl border border-[#EDEEF6]">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-full bg-[#E5E4FF] text-[#5C52E7] flex items-center justify-center flex-shrink-0 mt-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
                <div>
                    <h4 class="text-xs font-semibold text-gray-900">Akun Master Wilayah</h4>
                    <p class="text-xs text-gray-500 mt-1 truncate w-40">{{ auth()->user()->wilayah ? auth()->user()->wilayah->name : 'Mulyaharja' }}</p>
                </div>
            </div>
            <a href="#" class="text-xs text-[#5C52E7] font-medium mt-3 inline-block hover:underline">Lihat Detail Wilayah &gt;</a>
        </div>"""

new_block = """        @if(auth()->user()->role === 'master_wilayah')
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
        @endif"""

content = content.replace(old_block, new_block)

old_admin_block = """        <!-- Super Admin Info Card -->
        <div class="bg-white p-4 rounded-xl border border-gray-100 flex justify-between items-center shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)]">"""

new_admin_block = """        @if(auth()->user()->role === 'super_admin')
        <!-- Super Admin Info Card -->
        <div class="bg-white p-4 rounded-xl border border-gray-100 flex justify-between items-center shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)]">"""

content = content.replace(old_admin_block, new_admin_block)

old_admin_end = """            <div class="w-2 h-2 rounded-full bg-green-500"></div>
        </div>"""

new_admin_end = """            <div class="w-2 h-2 rounded-full bg-green-500"></div>
        </div>
        @endif"""

content = content.replace(old_admin_end, new_admin_end)

with open('resources/views/layouts/sidebar.blade.php', 'w') as f:
    f.write(content)
