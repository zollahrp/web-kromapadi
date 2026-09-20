import re

with open('app/Http/Controllers/UserController.php', 'r') as f:
    content = f.read()

# Update index method
old_index = """    public function index()
    {
        // Hanya menampilkan super_admin dan master_wilayah. KTD diatur di Kelompok Tani.
        $users = User::with('wilayah')->whereIn('role', ['super_admin', 'master_wilayah'])->get();
        $wilayahs = Wilayah::all();
        return view('admin.users.index', compact('users', 'wilayahs'));
    }"""

new_index = """    public function index(Request $request)
    {
        $query = User::with('wilayah')->whereIn('role', ['super_admin', 'master_wilayah']);
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        
        if ($request->filled('provinsi')) {
            $provinsi = $request->provinsi;
            $query->whereHas('wilayah', function($q) use ($provinsi) {
                $q->where('provinsi', $provinsi);
            });
        }

        $users = $query->latest()->get();
        $wilayahs = Wilayah::all();
        $provinsis = Wilayah::select('provinsi')->whereNotNull('provinsi')->distinct()->pluck('provinsi');
        
        return view('admin.users.index', compact('users', 'wilayahs', 'provinsis'));
    }"""

content = content.replace(old_index, new_index)

with open('app/Http/Controllers/UserController.php', 'w') as f:
    f.write(content)
