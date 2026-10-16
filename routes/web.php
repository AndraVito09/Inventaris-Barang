<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

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

Route::get('/dashboard', [
    DashboardController::class,
    'index'
])->middleware('auth');

Route::post('/logout', [
    AuthController::class,
    'logout'
])->name('logout');