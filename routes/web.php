<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PeminjamanController;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');

// Halaman peminjam (publik, tanpa login)
Route::get('/peminjam', [PeminjamanController::class, 'index']);
Route::get('/peminjam/pinjam', [PeminjamanController::class, 'formPinjam']);
Route::post('/peminjam/pinjam', [PeminjamanController::class, 'simpan'])->middleware('throttle:10,1');
Route::get('/peminjam/barang/{kode}', [PeminjamanController::class, 'cekBarang'])->middleware('throttle:60,1');
Route::get('/peminjam/struk/{kode}', [PeminjamanController::class, 'struk']);

// Halaman admin (wajib login)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::resource('kategori', KategoriController::class)
        ->only(['index', 'store', 'update', 'destroy']);
});