<?php

namespace App\Http\Controllers;

use App\Models\Barang;

class DashboardController extends Controller
{
    public function index()
    {
        // Semua data diambil dari tabel barang (kolom status_barang)
        $totalBarang    = Barang::count();
        $barangTersedia = Barang::where('status_barang', 'Tersedia')->count();
        $barangDipinjam = Barang::where('status_barang', 'Dipinjam')->count();

        return view('admin/Dashboard', compact(
            'totalBarang',
            'barangTersedia',
            'barangDipinjam'
        ));
    }
}