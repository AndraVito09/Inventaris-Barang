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
            'barangTersedia'  => Barang::where('status', 'Tersedia')->count(),
            'sedangDipinjam'  => Barang::where('status', 'Dipinjam')->count(),
            'terlambat'       => Peminjaman::where('status', 'Dipinjam')
                                    ->where('jatuh_tempo', '<', now())->count(),
        ]);
    }
}