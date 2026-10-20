<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Symfony\Component\HttpFoundation\Response;

class RiwayatController extends Controller
{
    /**
     * Menampilkan halaman riwayat peminjaman.
     */
    public function index(): View
    {
        $riwayat = Peminjaman::with(['barang', 'peminjam'])
            ->orderByDesc('tgl_pinjam')
            ->get();

        return view('admin.riwayat', [
            'riwayat' => $riwayat,
        ]);
    }

    /**
     * Mencetak laporan riwayat peminjaman ke PDF.
     */
    public function cetakPdf(): Response
    {
        $riwayat = Peminjaman::with(['barang', 'peminjam'])
            ->orderByDesc('tgl_pinjam')
            ->get();

        $pdf = Pdf::loadView('admin.riwayat_pdf', [
            'riwayat' => $riwayat,
        ]);

        $pdf->setPaper('a4', 'portrait');

        return $pdf->stream('laporan-riwayat-peminjaman.pdf');
    }
}