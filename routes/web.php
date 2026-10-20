
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MenuController;

// Halaman utama
Route::get('/', [MenuController::class, 'index'])
    ->name('menu');

// Halaman Menu
Route::get('/Menu', [MenuController::class, 'index'])
    ->name('menu.page');

// Form login admin
Route::get('/login', [AuthController::class, 'showLoginForm'])
    ->name('login');

// Proses login admin
Route::post('/login', [AuthController::class, 'login'])
    ->name('login.process');

// Dashboard admin (harus login)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

// Halaman peminjaman
Route::view('/peminjaman', 'peminjaman')
    ->name('peminjaman.index');

// Proses logout (harus login)
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');
