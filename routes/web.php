<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [
    AuthController::class,
    'showLoginForm'
])->name('login');

Route::post('/login', [
    AuthController::class,
    'login'
])->name('login.process');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [
        DashboardController::class,
        'index'
    ]);

    Route::post('/logout', [
        AuthController::class,
        'logout'
    ])->name('logout');
    Route::resource('kategori', KategoriController::class)
        ->only(['index', 'store', 'update', 'destroy']);
});
