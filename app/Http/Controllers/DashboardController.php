<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Peminjaman;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBarang    = Barang::count();
        $barangTersedia = Barang::where('status_barang', 'Tersedia')->count();

        // Dipinjam + Terlambat (Blade akan mengurangi dengan $terlambat)
        $sedangDipinjam = Peminjaman::whereIn('status_peminjaman', ['Dipinjam', 'Terlambat'])->count();
        $terlambat      = Peminjaman::where('status_peminjaman', 'Terlambat')->count();

        // BARU: sudah dikembalikan (samakan nama status dengan yang kamu simpan saat barang kembali)
        $sudahKembali   = Peminjaman::whereIn('status_peminjaman', ['Dikembalikan', 'Selesai'])->count();

        return view('admin/Dashboard', compact(
            'totalBarang',
            'barangTersedia',
            'sedangDipinjam',
            'terlambat',
            'sudahKembali'
        ));
    }
}