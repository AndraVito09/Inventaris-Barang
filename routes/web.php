<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PeminjamanController;

// Halaman utama (menu pilihan: login admin atau halaman peminjaman)
Route::get('/', [MenuController::class, 'index'])
    ->name('menu');

// Tombol "Login sebagai Admin" -> form login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');

// Tombol "Masuk ke Halaman Peminjaman" -> dashboard-peminjam (publik, tanpa login)
Route::get('/peminjam', [PeminjamanController::class, 'index'])->name('peminjaman.index');
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