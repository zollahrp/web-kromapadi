import re
import os

# 1. Update Controller
controller_path = 'app/Http/Controllers/LahanController.php'
with open(controller_path, 'r') as f:
    controller_content = f.read()

old_index = """    public function index()
    {
        $user = auth()->user();

        if (!in_array($user->role, ['super_admin', 'master_wilayah'])) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        if ($user->role === 'master_wilayah') {
            // Master Wilayah hanya bisa melihat lahan yang mereka unggah, atau yang KTD-nya ada di wilayah mereka
            $lahans = Lahan::with(['kelompokTani', 'uploader'])
                           ->where('uploaded_by', $user->id)
                           ->latest()
                           ->get();
            
            // Ambil daftar Kelompok Tani di wilayah ini untuk Dropdown Tambah/Edit
            $ktds = KelompokTani::where('wilayah_id', $user->wilayah_id)->get();
        } else {
            $lahans = Lahan::with(['kelompokTani', 'uploader'])->latest()->get();
            $ktds = KelompokTani::all();
        }

        return view('admin.lahan.index', compact('lahans', 'ktds'));
    }"""

new_index = """    public function index(Request $request)
    {
        $user = auth()->user();

        if (!in_array($user->role, ['super_admin', 'master_wilayah'])) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $query = Lahan::with(['kelompokTani', 'uploader']);

        if ($user->role === 'master_wilayah') {
            $query->where('uploaded_by', $user->id);
            $ktds = KelompokTani::where('wilayah_id', $user->wilayah_id)->get();
        } else {
            $ktds = KelompokTani::all();
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('kelompokTani', function($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        if ($request->filled('klasifikasi')) {
            $query->where('klasifikasi', $request->klasifikasi);
        }

        $lahans = $query->latest()->get();
        $klasifikasis = Lahan::select('klasifikasi')->whereNotNull('klasifikasi')->where('klasifikasi', '!=', '')->distinct()->pluck('klasifikasi');

        return view('admin.lahan.index', compact('lahans', 'ktds', 'klasifikasis'));
    }"""

controller_content = controller_content.replace(old_index, new_index)
with open(controller_path, 'w') as f:
    f.write(controller_content)


# 2. Update View
view_path = 'resources/views/admin/lahan/index.blade.php'
with open(view_path, 'r') as f:
    view_content = f.read()

# Replace old alerts with Toasts
old_alerts = """@if(session('success'))
<div id="success-alert" class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-r-xl">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="ml-3">
            <p class="text-sm text-green-700">
                {{ session('success') }}
            </p>
        </div>
    </div>
</div>
@endif

@if($errors->any())
<div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-xl">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="ml-3">
            <ul class="list-disc list-inside text-sm text-red-700">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
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

# Add Filter Section before table
filter_ui = """<!-- Filter Section -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
    <form action="{{ route('lahan.index') }}" method="GET" class="flex flex-col md:flex-row md:items-end gap-3" id="filterForm">
        <!-- Search Name -->
        <div class="flex-1 min-w-[250px] relative">
            <label class="block text-sm font-medium text-gray-700 mb-1 truncate">Cari Lahan / KTD</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama lahan atau KTD lalu Enter..." class="pl-10 mt-1 focus:ring-[#5C52E7] focus:border-[#5C52E7] block w-full shadow-sm sm:text-sm border-gray-300 rounded-xl px-4 py-2 border outline-none transition-colors">
            </div>
        </div>
        
        <!-- Filter Klasifikasi -->
        <div class="w-full md:w-48 flex-shrink-0">
            <label class="block text-sm font-medium text-gray-700 mb-1 truncate">Klasifikasi</label>
            <select name="klasifikasi" onchange="document.getElementById('filterForm').submit()" class="mt-1 focus:ring-[#5C52E7] focus:border-[#5C52E7] block w-full shadow-sm sm:text-sm border-gray-300 rounded-xl px-4 py-2 border outline-none transition-colors bg-white appearance-none cursor-pointer">
                <option value="">Semua Klasifikasi</option>
                @if(isset($klasifikasis))
                    @foreach($klasifikasis as $klas)
                        <option value="{{ $klas }}" {{ request('klasifikasi') == $klas ? 'selected' : '' }}>{{ $klas }}</option>
                    @endforeach
                @endif
            </select>
        </div>
        
        @if(request()->hasAny(['search', 'klasifikasi']))
        <div class="flex items-center gap-3 flex-shrink-0">
            <a href="{{ route('lahan.index') }}" class="px-4 py-2 bg-red-50 text-red-600 rounded-xl text-sm font-medium hover:bg-red-100 transition-colors border border-red-100 mt-1 flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                Reset
            </a>
        </div>
        @endif
    </form>
</div>
"""
view_content = view_content.replace('<!-- Table Data Lahan -->', filter_ui + '<!-- Table Data Lahan -->')

# Update Modals for Add & Edit to match the clean style of Users
# We will use regex to find the modals and replace them to ensure animation classes are added, 
# and the TomSelect styling is applied.
# Actually I'll just replace the whole modal wrappers and internal selects manually or by simple str replaces.

# 1. Add `animate-backdrop` to the modal hidden wrapper
view_content = view_content.replace('id="modal-tambah" class="fixed inset-0 z-50 hidden"', 'id="modal-tambah" class="fixed inset-0 z-50 hidden animate-backdrop"')
view_content = view_content.replace('id="modal-edit" class="fixed inset-0 z-50 hidden"', 'id="modal-edit" class="fixed inset-0 z-50 hidden animate-backdrop"')

# 2. Add `animate-modal` to the modal dialog wrapper
view_content = view_content.replace('inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative z-10', 'inline-block align-bottom bg-white rounded-2xl text-left overflow-visible shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full animate-modal relative z-10')

# 3. Modify Select Classes for TomSelect (removing the focus borders and padding to avoid double border)
# Find `<select name="kelompok_tani_id" required class="...">` inside Add Modal
old_select = '<select name="kelompok_tani_id" required class="mt-1 focus:ring-[#5C52E7] focus:border-[#5C52E7] block w-full shadow-sm sm:text-sm border-gray-300 rounded-xl px-4 py-2 border outline-none transition-colors">'
new_select = '<select name="kelompok_tani_id" id="add_ktd" required class="mt-1 block w-full">'
view_content = view_content.replace(old_select, new_select)

# Inside Edit Modal: `<select name="kelompok_tani_id" id="edit_kelompok_tani_id" required class="...">`
old_edit_select = '<select name="kelompok_tani_id" id="edit_kelompok_tani_id" required class="mt-1 focus:ring-[#5C52E7] focus:border-[#5C52E7] block w-full shadow-sm sm:text-sm border-gray-300 rounded-xl px-4 py-2 border outline-none transition-colors">'
new_edit_select = '<select name="kelompok_tani_id" id="edit_ktd" required class="mt-1 block w-full">'
view_content = view_content.replace(old_edit_select, new_edit_select)

# 4. Underline Title fix
view_content = view_content.replace('<h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Tambah Lahan Baru</h3>', '<div class="border-b border-gray-100 pb-4 mb-4"><h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">Tambah Lahan Baru</h3></div>')
view_content = view_content.replace('<h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Edit Lahan</h3>', '<div class="border-b border-gray-100 pb-4 mb-4"><h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">Edit Lahan</h3></div>')

# 5. Remove the little blue icon and centering layout of modal header (which was in older styles, let's keep it simple)
# In Lahan, it has: `<div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-50 sm:mx-0 sm:h-10 sm:w-10">...</div>`
view_content = re.sub(r'<div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-[^>]+>.*?</div>\s*<div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">', r'<div class="mt-3 text-center sm:mt-0 sm:text-left w-full">', view_content, flags=re.DOTALL)

# 6. Include TomSelect and scripts at the end of the file
ts_scripts = """
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

<script>
    let tsEditKtd;
    document.addEventListener('DOMContentLoaded', function() {
        new TomSelect("#add_ktd", {
            create: false,
            sortField: { field: "text", direction: "asc" },
            placeholder: "Pilih Kelompok Tani..."
        });

        tsEditKtd = new TomSelect("#edit_ktd", {
            create: false,
            sortField: { field: "text", direction: "asc" },
            placeholder: "Pilih Kelompok Tani..."
        });
    });

    function openEditModal(id, name, kelompokTaniId, klasifikasi) {
        document.getElementById('form-edit').action = '/lahan/' + id;
        document.getElementById('edit_name').value = name;
        if(tsEditKtd) tsEditKtd.setValue(kelompokTaniId);
        document.getElementById('edit_klasifikasi').value = klasifikasi;
        
        document.getElementById('modal-edit').classList.remove('hidden');
    }
</script>
"""

# replace the old openEditModal script and insert TomSelect
old_script = """<script>
    function openEditModal(id, name, kelompokTaniId, klasifikasi) {
        document.getElementById('form-edit').action = '/lahan/' + id;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_kelompok_tani_id').value = kelompokTaniId;
        document.getElementById('edit_klasifikasi').value = klasifikasi;
        
        document.getElementById('modal-edit').classList.remove('hidden');
    }
</script>"""

view_content = view_content.replace(old_script, ts_scripts)

with open(view_path, 'w') as f:
    f.write(view_content)
