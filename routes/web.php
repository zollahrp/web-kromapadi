<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

use App\Http\Controllers\WilayahController;
use App\Http\Controllers\KelompokTaniController;
use App\Http\Controllers\LahanController;
use App\Http\Controllers\TandurController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PetaniController;

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::resource('wilayah', WilayahController::class);
    Route::resource('ktd', KelompokTaniController::class);
    Route::resource('ktd.petani', PetaniController::class)->except(['create', 'show', 'edit']);
    Route::resource('lahan', LahanController::class);
    Route::resource('tandur', TandurController::class);
    Route::get('riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');
    Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');

    // Pengguna (Khusus Super Admin)
    Route::resource('users', UserController::class)->middleware('can:is_super_admin');

    // Pengaturan Profil
    Route::get('settings', [ProfileController::class, 'index'])->name('settings.index');
    Route::put('settings/profile', [ProfileController::class, 'updateProfile'])->name('settings.profile');
    Route::put('settings/password', [ProfileController::class, 'updatePassword'])->name('settings.password');
});
