<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Peminjaman;

class PeminjamController extends Controller
{
    public function index()
    {
        return view('peminjam', [
            'totalBarang'     => Barang::count(),
            'barangTersedia'  => Barang::where('status_barang', 'Tersedia')->count(),
            'sedangDipinjam'  => Barang::where('status_barang', 'Dipinjam')->count(),
            'terlambat'       => Peminjaman::where('status_peminjaman', 'Dipinjam')
                                    ->where('tgl_jatuh_tempo', '<', now())->count(),
        ]);
    }
}