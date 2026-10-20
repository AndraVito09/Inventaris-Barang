
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PeminjamController;
use App\Http\Controllers\MenuController;


// Halaman utama
Route::get('/', [MenuController::class, 'index'])
    ->name('menu');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');

// Halaman peminjam (publik, tanpa login)
Route::get('/peminjam', [PeminjamController::class, 'index'])->name('peminjam.home');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::resource('kategori', KategoriController::class)
        ->only(['index', 'store', 'update', 'destroy']);
});
