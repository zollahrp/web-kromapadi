import os

os.makedirs('resources/views/admin/petani', exist_ok=True)

with open('resources/views/admin/users/index.blade.php', 'r') as f:
    content = f.read()

# Replace variables and routes
content = content.replace('$users', '$petanis')
content = content.replace('$user', '$petani')
content = content.replace("route('users.", "route('petani.")
content = content.replace("Manajemen Pengguna", "Manajemen Petani")
content = content.replace("Kelola akses dan akun pengguna sistem", "Kelola akun petani untuk aplikasi mobile")
content = content.replace("Tambah Pengguna", "Tambah Petani")
content = content.replace("Ubah Pengguna", "Ubah Petani")
content = content.replace("Hapus Pengguna", "Hapus Petani")
content = content.replace("Nama Pengguna", "Nama Petani")

# Replace role specific things
content = content.replace("""
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($petani->role === 'super_admin')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            Super Admin
                                        </span>
                                    @elseif($petani->role === 'master_wilayah')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Master Wilayah
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            KTD
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $petani->wilayah ? $petani->wilayah->name : '-' }}
                                </td>
""", """
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Petani
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $petani->kelompokTani ? $petani->kelompokTani->name : '-' }}
                                </td>
""")

content = content.replace("""<th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Wilayah</th>""",
                            """<th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">KTD</th>""")

content = content.replace("""
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                            <select name="role" required class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#5C52E7] focus:border-transparent transition-all">
                                <option value="super_admin">Super Admin</option>
                                <option value="master_wilayah">Master Wilayah</option>
                                <option value="ktd">KTD</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Wilayah</label>
                            <select name="wilayah_id" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#5C52E7] focus:border-transparent transition-all">
                                <option value="">Pilih Wilayah (Opsional)</option>
                                @foreach($wilayahs as $wilayah)
                                    <option value="{{ $wilayah->id }}">{{ $wilayah->name }}</option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-gray-500">Pilih wilayah jika role adalah Master Wilayah atau KTD</p>
                        </div>
""", """
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kelompok Tani Dewasa</label>
                            <select name="kelompok_tani_id" required class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#5C52E7] focus:border-transparent transition-all">
                                <option value="">Pilih KTD</option>
                                @foreach($ktds as $ktd)
                                    <option value="{{ $ktd->id }}">{{ $ktd->name }}</option>
                                @endforeach
                            </select>
                        </div>
""")

content = content.replace("""
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                            <select name="role" required class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#5C52E7] focus:border-transparent transition-all">
                                <option value="super_admin" {{ $petani->role === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                                <option value="master_wilayah" {{ $petani->role === 'master_wilayah' ? 'selected' : '' }}>Master Wilayah</option>
                                <option value="ktd" {{ $petani->role === 'ktd' ? 'selected' : '' }}>KTD</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Wilayah</label>
                            <select name="wilayah_id" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#5C52E7] focus:border-transparent transition-all">
                                <option value="">Pilih Wilayah (Opsional)</option>
                                @foreach($wilayahs as $wilayah)
                                    <option value="{{ $wilayah->id }}" {{ $petani->wilayah_id == $wilayah->id ? 'selected' : '' }}>{{ $wilayah->name }}</option>
                                @endforeach
                            </select>
                        </div>
""", """
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kelompok Tani Dewasa</label>
                            <select name="kelompok_tani_id" required class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#5C52E7] focus:border-transparent transition-all">
                                <option value="">Pilih KTD</option>
                                @foreach($ktds as $ktd)
                                    <option value="{{ $ktd->id }}" {{ $petani->kelompok_tani_id == $ktd->id ? 'selected' : '' }}>{{ $ktd->name }}</option>
                                @endforeach
                            </select>
                        </div>
""")

# Fix filter bar (from Wilayah to KTD filter)
content = content.replace("""
                <form action="{{ route('petani.index') }}" method="GET" id="filterForm" class="flex flex-col sm:flex-row gap-3">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..." onchange="document.getElementById('filterForm').submit()"
                            class="w-full sm:w-64 pl-10 pr-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#5C52E7] focus:border-transparent transition-all text-sm">
                    </div>
                </form>
""", """
                <form action="{{ route('petani.index') }}" method="GET" id="filterForm" class="flex flex-col sm:flex-row gap-3">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..." onchange="document.getElementById('filterForm').submit()"
                            class="w-full sm:w-64 pl-10 pr-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#5C52E7] focus:border-transparent transition-all text-sm">
                    </div>
                    
                    <select name="ktd_id" onchange="document.getElementById('filterForm').submit()" class="px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#5C52E7] focus:border-transparent transition-all text-sm bg-white">
                        <option value="">Semua KTD</option>
                        @foreach($ktds as $ktd)
                            <option value="{{ $ktd->id }}" {{ request('ktd_id') == $ktd->id ? 'selected' : '' }}>{{ $ktd->name }}</option>
                        @endforeach
                    </select>
                </form>
""")

with open('resources/views/admin/petani/index.blade.php', 'w') as f:
    f.write(content)

print("Done creating petani views")
