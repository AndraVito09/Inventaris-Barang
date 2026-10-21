<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\RiwayatController;

Route::get('/', [MenuController::class, 'index'])->name('menu');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');

// Halaman peminjam (publik, tanpa login)
Route::get('/peminjam', [PeminjamanController::class, 'index'])->name('peminjaman.index');
Route::get('/peminjam/pinjam', [PeminjamanController::class, 'formPinjam']);
Route::post('/peminjam/pinjam', [PeminjamanController::class, 'simpan'])->middleware('throttle:10,1');
Route::get('/peminjam/barang/{kode}', [PeminjamanController::class, 'cekBarang'])->middleware('throttle:60,1');
Route::get('/peminjam/struk/{kode}', [PeminjamanController::class, 'struk']);

Route::get('/peminjam/kembali', [PengembalianController::class, 'index']);
Route::post('/peminjam/kembali/cek', [PengembalianController::class, 'cek'])->middleware('throttle:60,1');
Route::post('/peminjam/kembali/proses', [PengembalianController::class, 'proses'])->middleware('throttle:10,1');
Route::get('/peminjam/daftar-barang',  [PeminjamanController::class, 'katalog'])
    ->name('peminjam.daftar-barang');

// Riwayat peminjaman untuk peminjam (nama peminjam disamarkan)
Route::get('/peminjam/riwayat', [RiwayatController::class, 'peminjam'])
    ->name('peminjaman.riwayat')
    ->middleware('throttle:60,1');

// Halaman admin (wajib login)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Data Kategori
    Route::resource('kategori', KategoriController::class)
        ->only(['index', 'store', 'update', 'destroy']);

    Route::get('barang/{barang}/cetak', [BarangController::class, 'cetak'])->name('barang.cetak');

    Route::resource('barang', BarangController::class)
        ->only(['index', 'store', 'update', 'destroy']);
    // Halaman Riwayat Peminjaman
    Route::get('/admin/riwayat', [RiwayatController::class, 'index'])
        ->name('admin.riwayat');

    // Cetak Riwayat Peminjaman ke PDF
    Route::get('/admin/riwayat/cetak-pdf', [RiwayatController::class, 'cetakPdf'])
        ->name('admin.riwayat.pdf');

});
