<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use App\Models\Activity_log;

class RiwayatController extends Controller
{
    /**
     * ADMIN: halaman riwayat peminjaman (dijaga middleware auth di route).
     */
    public function index(): View
    {
        return view('admin.riwayat', [
            'riwayat' => $this->ambilRiwayat(),
        ]);
    }

    public function catatCetakPDF()
    {
        $jumlah = Peminjaman::count();

        Activity_log::catat_aktivitas_admin(
            'ekspor',
            'Peminjaman',
            null,
            "Cetak laporan riwayat peminjaman ({$jumlah} data)"
        );

        return response()->noContent();
    }

    /**
     * PEMINJAM (publik, tanpa login): nama peminjam disamarkan.
     */
    public function peminjam(): View
    {
        $riwayat = $this->ambilRiwayat();

        $riwayat->each(function (Peminjaman $row) {
            $nama = $row->peminjam->nama_peminjam ?? '-';

            $row->setAttribute('nama_tampil', $this->samarkanNama($nama));
        });

        return view('peminjam.riwayat-peminjaman', [
            'riwayat' => $riwayat,
        ]);
    }

    /**
     * Query riwayat yang dipakai bersama admin dan peminjam.
     */
    private function ambilRiwayat(): Collection
    {
        return Peminjaman::with(['barang', 'peminjam'])
            ->orderByDesc('tgl_pinjam')
            ->get();
    }

    /**
     * Contoh: "Irlan M*****A"
     */
    private function samarkanNama(string $nama): string
    {
        return collect(explode(' ', $nama))
            ->map(fn(string $kata) => mb_substr($kata, 0, 1) . str_repeat('*', max(mb_strlen($kata) - 1, 0)))
            ->implode(' ');
    }
}
