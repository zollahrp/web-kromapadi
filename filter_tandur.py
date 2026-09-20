import re

# 1. Update Controller
controller_path = 'app/Http/Controllers/TandurController.php'
with open(controller_path, 'r') as f:
    controller_content = f.read()

old_index = """    public function index()
    {
        $user = auth()->user();

        if (!in_array($user->role, ['super_admin', 'master_wilayah'])) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        if ($user->role === 'master_wilayah') {
            $tandurs = Tandur::with(['kelompokTani', 'lahan'])
                ->whereHas('kelompokTani', function($query) use ($user) {
                    $query->where('wilayah_id', $user->wilayah_id);
                })
                ->latest()
                ->get();
            $ktds = KelompokTani::where('wilayah_id', $user->wilayah_id)->get();
            // Lahans should also be filtered by these KTDs
            $lahans = Lahan::whereIn('kelompok_tani_id', $ktds->pluck('id'))->get();
        } else {
            $tandurs = Tandur::with(['kelompokTani', 'lahan'])->latest()->get();
            $ktds = KelompokTani::all();
            $lahans = Lahan::all();
        }

        return view('admin.tandur.index', compact('tandurs', 'ktds', 'lahans'));
    }"""

new_index = """    public function index(Request $request)
    {
        $user = auth()->user();

        if (!in_array($user->role, ['super_admin', 'master_wilayah'])) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $query = Tandur::with(['kelompokTani', 'lahan']);

        if ($user->role === 'master_wilayah') {
            $query->whereHas('kelompokTani', function($q) use ($user) {
                $q->where('wilayah_id', $user->wilayah_id);
            });
            $ktds = KelompokTani::where('wilayah_id', $user->wilayah_id)->get();
            $lahans = Lahan::whereIn('kelompok_tani_id', $ktds->pluck('id'))->get();
        } else {
            $ktds = KelompokTani::all();
            $lahans = Lahan::all();
        }
        
        // Filter Pencarian (Cari berdasarkan nama KTD atau Lahan)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('kelompokTani', function($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('lahan', function($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%");
                });
            });
        }
        
        // Filter by KTD
        if ($request->filled('ktd_id')) {
            $query->where('kelompok_tani_id', $request->ktd_id);
        }

        $tandurs = $query->latest()->get();

        return view('admin.tandur.index', compact('tandurs', 'ktds', 'lahans'));
    }"""

controller_content = controller_content.replace(old_index, new_index)
with open(controller_path, 'w') as f:
    f.write(controller_content)


# 2. Update View
view_path = 'resources/views/admin/tandur/index.blade.php'
with open(view_path, 'r') as f:
    view_content = f.read()

# Add Filter Section before table
filter_ui = """<!-- Filter Section -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
    <form action="{{ route('tandur.index') }}" method="GET" class="flex flex-col md:flex-row md:items-end gap-3" id="filterForm">
        <!-- Search Name -->
        <div class="flex-1 min-w-[250px] relative">
            <label class="block text-sm font-medium text-gray-700 mb-1 truncate">Cari KTD / Lahan</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama KTD atau Lahan..." class="pl-10 mt-1 focus:ring-[#5C52E7] focus:border-[#5C52E7] block w-full shadow-sm sm:text-sm border-gray-300 rounded-xl px-4 py-2 border outline-none transition-colors">
            </div>
        </div>
        
        <!-- Filter KTD -->
        <div class="w-full md:w-64 flex-shrink-0">
            <label class="block text-sm font-medium text-gray-700 mb-1 truncate">Kelompok Tani (KTD)</label>
            <select name="ktd_id" id="filter_ktd_id" onchange="document.getElementById('filterForm').submit()" class="mt-1 block w-full focus:ring-[#5C52E7] focus:border-[#5C52E7] shadow-sm sm:text-sm border-gray-300 rounded-xl px-4 py-2 border outline-none transition-colors">
                <option value="">Semua KTD</option>
                @if(isset($ktds))
                    @foreach($ktds as $ktd)
                        <option value="{{ $ktd->id }}" {{ request('ktd_id') == $ktd->id ? 'selected' : '' }}>{{ $ktd->name }}</option>
                    @endforeach
                @endif
            </select>
        </div>
        
        @if(request()->hasAny(['search', 'ktd_id']))
        <div class="flex items-center gap-3 flex-shrink-0">
            <a href="{{ route('tandur.index') }}" class="px-4 py-2 bg-red-50 text-red-600 rounded-xl text-sm font-medium hover:bg-red-100 transition-colors border border-red-100 mt-1 flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                Reset
            </a>
        </div>
        @endif
    </form>
</div>
"""
view_content = view_content.replace('<!-- Table -->', filter_ui + '<!-- Table -->')

# Update Alerts to Toasts
old_alerts = """<!-- Tampilkan alert sukses -->
@if(session('success'))
<div id="success-alert" class="mb-4 p-4 rounded-xl bg-green-50 border border-green-100 flex items-start gap-3">
    <div class="flex-shrink-0">
        <svg class="w-5 h-5 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
    </div>
    <div>
        <h3 class="text-sm font-medium text-green-800">Berhasil!</h3>
        <p class="text-sm text-green-600 mt-1">{{ session('success') }}</p>
    </div>
</div>
@endif

<!-- Tampilkan alert error -->
@if($errors->any())
<div class="mb-4 p-4 rounded-xl bg-red-50 border border-red-100 flex items-start gap-3">
    <div class="flex-shrink-0">
        <svg class="w-5 h-5 text-red-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
    </div>
    <div>
        <h3 class="text-sm font-medium text-red-800">Terdapat Kesalahan:</h3>
        <ul class="mt-1 list-disc list-inside text-sm text-red-600">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif"""

new_alerts = """@if(session('success'))
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
@endif"""

view_content = view_content.replace(old_alerts, new_alerts)

# Add Top level styles for Modals and TomSelect
top_styles = """@extends('layouts.admin')

@section('title', 'Manajemen Tandur')

@section('content')
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
"""

view_content = view_content.replace("@extends('layouts.admin')\n\n@section('title', 'Manajemen Tandur')\n\n@section('content')", top_styles)


# 1. Update Modal wrappers (Tambah)
old_modal_tambah = """id="modal-tambah" class="fixed inset-0 z-50 hidden" """
new_modal_tambah = """id="modal-tambah" class="fixed inset-0 z-50 hidden animate-backdrop" """
view_content = view_content.replace(old_modal_tambah, new_modal_tambah)

old_inner_tambah = """<div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">"""
new_inner_tambah = """<div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-visible shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full animate-modal relative z-10">"""
view_content = view_content.replace(old_inner_tambah, new_inner_tambah)

# Remove old header styles inside modal for Tambah
view_content = re.sub(r'<div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-\[\#F0EFFF\] sm:mx-0 sm:h-10 sm:w-10">\s*<svg.*?</svg>\s*</div>\s*<div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">', r'<div class="mt-3 text-center sm:mt-0 sm:text-left w-full">', view_content, flags=re.DOTALL)
view_content = view_content.replace('<h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Tambah Data Tandur</h3>', '<div class="border-b border-gray-100 pb-4 mb-4"><h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">Tambah Data Tandur</h3></div>')


# 2. Update Modal wrappers (Edit)
old_modal_edit = """id="modal-edit" class="fixed inset-0 z-50 hidden" """
new_modal_edit = """id="modal-edit" class="fixed inset-0 z-50 hidden animate-backdrop" """
view_content = view_content.replace(old_modal_edit, new_modal_edit)

old_inner_edit = """<div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">"""
new_inner_edit = """<div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-visible shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full animate-modal relative z-10">"""
view_content = view_content.replace(old_inner_edit, new_inner_edit)

view_content = re.sub(r'<div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-50 sm:mx-0 sm:h-10 sm:w-10">\s*<svg.*?</svg>\s*</div>\s*<div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">', r'<div class="mt-3 text-center sm:mt-0 sm:text-left w-full">', view_content, flags=re.DOTALL)
view_content = view_content.replace('<h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Edit Data Tandur</h3>', '<div class="border-b border-gray-100 pb-4 mb-4"><h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">Edit Data Tandur</h3></div>')


ts_init = """
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if(document.getElementById('filter_ktd_id')) {
            new TomSelect("#filter_ktd_id", {
                create: false,
                sortField: { field: "text", direction: "asc" },
                placeholder: "Semua KTD"
            });
        }
    });
</script>
"""
view_content = view_content.replace('@endsection', ts_init + '\n@endsection')


with open(view_path, 'w') as f:
    f.write(view_content)
