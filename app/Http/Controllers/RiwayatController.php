<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use App\Models\Activity_log;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class RiwayatController extends Controller
{
    private const PER_HALAMAN = 10;

    private const OPSI_STATUS = [
        'dipinjam'  => 'Dipinjam',
        'terlambat' => 'Terlambat',
        'selesai'   => 'Selesai',
    ];

    public function index(Request $request): View
    {
        [$cari, $status] = $this->bacaFilter($request);

        $query = $this->queryAdmin($cari, $status);

        return view('admin.riwayat', [
            // Per halaman, untuk tampilan di layar
            'riwayat'    => (clone $query)->paginate(self::PER_HALAMAN)->withQueryString(),
            // Semua hasil filter, untuk tabel cetak / PDF
            'semua'      => $query->get(),
            'cari'       => $cari,
            'status'     => $status,
            'opsiStatus' => self::OPSI_STATUS,
        ]);
    }

    public function catatCetakPDF()
    {
        [$cari, $status] = $this->bacaFilter($request);

        $jumlah = $this->queryAdmin($cari, $status)->count();
        $filter = ($cari !== '' || $status !== '') ? ', dengan filter' : '';

        Activity_log::catat_aktivitas_admin(
            'ekspor',
            'Peminjaman',
            null,
            "Cetak laporan riwayat peminjaman ({$jumlah} data{$filter})"
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

    private function bacaFilter(Request $request): array
    {
        $cari = mb_substr(trim($request->string('q')->toString()), 0, 100);

        $status = $request->string('status')->toString();
        if (! array_key_exists($status, self::OPSI_STATUS)) {
            $status = '';
        }

        return [$cari, $status];
    }

    private function queryAdmin(string $cari, string $status): Builder
    {
        return Peminjaman::with(['barang', 'peminjam'])
            ->when($cari !== '', function (Builder $query) use ($cari) {
                $query->where(function (Builder $w) use ($cari) {
                    $w->whereHas('peminjam', fn(Builder $p) => $p->where('nama_peminjam', 'like', "%{$cari}%"))
                        ->orWhereHas('barang', function (Builder $b) use ($cari) {
                            $b->where('nama_barang', 'like', "%{$cari}%")
                                ->orWhere('kode_barcode', 'like', "%{$cari}%");
                        });
                });
            })
            ->when($status === 'selesai', fn(Builder $q) => $q->whereNotNull('tgl_kembali'))
            ->when($status === 'terlambat', fn(Builder $q) => $q->whereNull('tgl_kembali')->where('tgl_jatuh_tempo', '<', now()))
            ->when($status === 'dipinjam', function (Builder $q) {
                $q->whereNull('tgl_kembali')
                    ->where(fn(Builder $w) => $w->whereNull('tgl_jatuh_tempo')->orWhere('tgl_jatuh_tempo', '>=', now()));
            })
            ->orderByDesc('tgl_pinjam');
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
