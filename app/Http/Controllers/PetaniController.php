<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\KelompokTani;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PetaniController extends Controller
{
    public function index(Request $request, $ktd_id)
    {
        $user = auth()->user();
        
        $ktd = KelompokTani::with(['wilayah', 'masterAdmin'])->findOrFail($ktd_id);
        
        // Ensure Master Wilayah only accesses KTD in their Wilayah
        if ($user->role === 'master_wilayah' && $ktd->wilayah_id !== $user->wilayah_id) {
            abort(403, 'Anda tidak memiliki akses ke kelompok tani ini.');
        }
        
        $query = User::where('role', 'petani')
                     ->where('kelompok_tani_id', $ktd->id)
                     ->latest();
        
        // Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $petanis = $query->get();

        return view('admin.petani.index', compact('petanis', 'ktd'));
    }

    public function store(Request $request, $ktd_id)
    {
        $ktd = KelompokTani::findOrFail($ktd_id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make('password'),
            'role' => 'petani',
            'wilayah_id' => $ktd->wilayah_id,
            'kelompok_tani_id' => $ktd->id
        ]);

        return back()->with('success', 'Akun Petani berhasil ditambahkan dengan password bawaan: password');
    }

    public function update(Request $request, $ktd_id, $id)
    {
        $ktd = KelompokTani::findOrFail($ktd_id);
        $petani = User::where('kelompok_tani_id', $ktd->id)->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($petani->id)],
            'password' => 'nullable|string|min:8'
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $petani->update($data);

        return back()->with('success', 'Akun Petani berhasil diperbarui!');
    }

    public function destroy($ktd_id, $id)
    {
        $ktd = KelompokTani::findOrFail($ktd_id);
        $petani = User::where('kelompok_tani_id', $ktd->id)->findOrFail($id);
        $petani->delete();

        return back()->with('success', 'Akun Petani berhasil dihapus!');
    }
}
