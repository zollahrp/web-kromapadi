<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

// KTD Mobile Login
Route::post('/login', [ApiController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return collect($request->user())->merge(['wilayah' => $request->user()->wilayah]);
    });

    Route::post('/scan', [ApiController::class, 'scan']);
    Route::get('/history', [ApiController::class, 'history']);
    Route::get('/lahan', [ApiController::class, 'getLahan']);
    Route::get('/wilayah', [ApiController::class, 'getWilayah']);
    Route::get('/petani', [ApiController::class, 'getPetani']);
});
Route::get('storage/{path}', function ($path) { $fullPath = storage_path('app/public/' . $path); if (!file_exists($fullPath)) abort(404); return response()->file($fullPath, ['Access-Control-Allow-Origin' => '*']); })->where('path', '.*');
