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
        $sedangDipinjam = Peminjaman::where('status_peminjaman', 'Dipinjam')->count();
        $terlambat      = Peminjaman::where('status_peminjaman', 'Terlambat')->count();

        return view('admin/Dashboard', compact(
            'totalBarang',
            'barangTersedia',
            'sedangDipinjam',
            'terlambat'
        ));
    }
}