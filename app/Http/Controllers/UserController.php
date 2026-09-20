<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
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
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => ['required', Rule::in(['super_admin', 'master_wilayah'])],
            'wilayah_id' => 'required_if:role,master_wilayah|nullable|exists:wilayahs,id',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make('password'),
            'role' => $request->role,
            'wilayah_id' => $request->role === 'master_wilayah' ? $request->wilayah_id : null,
        ]);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil ditambahkan. (Kata sandi default: password)');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in(['super_admin', 'master_wilayah'])],
            'wilayah_id' => 'required_if:role,master_wilayah|nullable|exists:wilayahs,id',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'wilayah_id' => $request->role === 'master_wilayah' ? $request->wilayah_id : null,
        ];

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil diubah.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
