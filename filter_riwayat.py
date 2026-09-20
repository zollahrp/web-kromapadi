import re

# 1. Update Controller
controller_path = 'app/Http/Controllers/RiwayatController.php'
with open(controller_path, 'r') as f:
    controller_content = f.read()

old_index = """    public function index()
    {
        $user = auth()->user();

        if (!in_array($user->role, ['super_admin', 'master_wilayah'])) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        if ($user->role === 'master_wilayah') {
            // Master Wilayah melihat riwayat scan dari KTD yang berada di wilayahnya
            // RiwayatScan belongsTo Lahan belongsTo KelompokTani belongsTo Wilayah
            // Kita bisa juga menggunakan relasi user->wilayah_id
            $riwayats = RiwayatScan::with(['user', 'lahan.kelompokTani'])
                ->whereHas('user', function ($query) use ($user) {
                    $query->where('wilayah_id', $user->wilayah_id);
                })
                ->latest()
                ->get();
        } else {
            $riwayats = RiwayatScan::with(['user', 'lahan.kelompokTani'])->latest()->get();
        }

        return view('admin.riwayat.index', compact('riwayats'));
    }"""

new_index = """    public function index(Request $request)
    {
        $user = auth()->user();

        if (!in_array($user->role, ['super_admin', 'master_wilayah'])) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $query = RiwayatScan::with(['user', 'lahan.kelompokTani']);

        if ($user->role === 'master_wilayah') {
            $query->whereHas('user', function ($q) use ($user) {
                $q->where('wilayah_id', $user->wilayah_id);
            });
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('penyakit', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('lahan', function($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $riwayats = $query->latest()->get();

        return view('admin.riwayat.index', compact('riwayats'));
    }"""

controller_content = controller_content.replace(old_index, new_index)
with open(controller_path, 'w') as f:
    f.write(controller_content)


# 2. Update View
view_path = 'resources/views/admin/riwayat/index.blade.php'
with open(view_path, 'r') as f:
    view_content = f.read()

# Add Filter Section before table
filter_ui = """<!-- Filter Section -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
    <form action="{{ route('riwayat.index') }}" method="GET" class="flex flex-col md:flex-row md:items-end gap-3" id="filterForm">
        <!-- Search -->
        <div class="flex-1 min-w-[250px] relative">
            <label class="block text-sm font-medium text-gray-700 mb-1 truncate">Cari Penyakit / KTD / Lahan</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" onchange="document.getElementById('filterForm').submit()" placeholder="Ketik kata kunci..." class="pl-10 mt-1 focus:ring-[#5C52E7] focus:border-[#5C52E7] block w-full shadow-sm sm:text-sm border-gray-300 rounded-xl px-4 py-2 border outline-none transition-colors">
            </div>
        </div>
        
        @if(request()->filled('search'))
        <div class="flex items-center gap-3 flex-shrink-0">
            <a href="{{ route('riwayat.index') }}" class="px-4 py-2 bg-red-50 text-red-600 rounded-xl text-sm font-medium hover:bg-red-100 transition-colors border border-red-100 mt-1 flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                Reset
            </a>
        </div>
        @endif
    </form>
</div>
"""
view_content = view_content.replace('<!-- Table Data Riwayat Scan -->', filter_ui + '<!-- Table Data Riwayat Scan -->')

# Update Modal wrappers
old_modal_detail = """id="modal-detail" class="fixed inset-0 z-50 hidden" """
new_modal_detail = """id="modal-detail" class="fixed inset-0 z-50 hidden animate-backdrop" """
view_content = view_content.replace(old_modal_detail, new_modal_detail)

old_inner_detail = """<div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">"""
new_inner_detail = """<div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-visible shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full animate-modal relative z-10">"""
view_content = view_content.replace(old_inner_detail, new_inner_detail)

view_content = re.sub(r'<div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-50 sm:mx-0 sm:h-10 sm:w-10">\s*<svg.*?</svg>\s*</div>\s*<div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">', r'<div class="mt-3 text-center sm:mt-0 sm:text-left w-full">', view_content, flags=re.DOTALL)
view_content = view_content.replace('<h3 class="text-lg leading-6 font-medium text-gray-900" id="detail_title">Detail Penyakit</h3>', '<div class="border-b border-gray-100 pb-4 mb-4"><h3 class="text-lg leading-6 font-bold text-gray-900" id="detail_title">Detail Penyakit</h3></div>')


# Add Top level styles for Modals
top_styles = """@extends('layouts.admin')

@section('content')
<style>
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
"""

view_content = view_content.replace("@extends('layouts.admin')\n\n@section('content')", top_styles)


with open(view_path, 'w') as f:
    f.write(view_content)
