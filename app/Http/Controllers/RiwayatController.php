<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Activity_log;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class RiwayatController extends Controller
{
    private const PER_HALAMAN = 10;

    /**
     * Status riwayat:
     * - dipinjam           : belum kembali, belum lewat jatuh tempo
     * - terlambat          : belum kembali, sudah lewat jatuh tempo
     * - selesai            : sudah kembali tepat waktu
     * - selesai_terlambat  : sudah kembali tapi melewati jatuh tempo
     */
    private const OPSI_STATUS = [
        'dipinjam'          => 'Dipinjam',
        'terlambat'         => 'Terlambat',
        'selesai'           => 'Selesai',
        'selesai_terlambat' => 'Selesai (Terlambat)',
    ];

    private const NAMA_BULAN = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    ];

    public function index(Request $request): View
    {
        [$cari, $status] = $this->bacaFilter($request);

        return view('admin.riwayat', [
            'riwayat'    => $this->queryAdmin($cari, $status)->paginate(self::PER_HALAMAN)->withQueryString(),
            'cari'       => $cari,
            'status'     => $status,
            'opsiStatus' => self::OPSI_STATUS,
            'namaBulan'  => self::NAMA_BULAN,
        ]);
    }

    /**
     * Laporan mingguan / bulanan / tahunan.
     * Menampilkan laporan siap cetak (view admin.riwayat mode laporan) yang langsung membuka dialog
     * cetak browser, sehingga bisa disimpan sebagai PDF (tanpa library tambahan).
     * Rentang dihitung dari tanggal pinjam; pencarian & filter status ikut diterapkan.
     */
    public function unduh(Request $request): View
    {
        $data = $request->validate([
            'periode' => ['required', 'in:mingguan,bulanan,tahunan'],
            'tanggal' => ['nullable', 'required_if:periode,mingguan', 'date'],
            'bulan'   => ['nullable', 'required_if:periode,bulanan', 'integer', 'between:1,12'],
            'tahun'   => ['nullable', 'required_if:periode,bulanan,tahunan', 'integer', 'between:2000,2100'],
        ]);

        [$cari, $status] = $this->bacaFilter($request);

        switch ($data['periode']) {
            case 'mingguan':
                $dasar = Carbon::parse($data['tanggal']);
                $awal  = $dasar->copy()->startOfWeek(Carbon::MONDAY);
                $akhir = $dasar->copy()->endOfWeek(Carbon::SUNDAY);
                $nama  = 'mingguan_' . $awal->format('Ymd') . '-' . $akhir->format('Ymd');
                $judul = 'Mingguan';
                break;

            case 'bulanan':
                $awal  = Carbon::create((int) $data['tahun'], (int) $data['bulan'], 1)->startOfMonth();
                $akhir = $awal->copy()->endOfMonth();
                $nama  = 'bulanan_' . $awal->format('Y-m');
                $judul = 'Bulanan (' . self::NAMA_BULAN[$awal->month] . ' ' . $awal->year . ')';
                break;

            default: // tahunan
                $awal  = Carbon::create((int) $data['tahun'], 1, 1)->startOfYear();
                $akhir = $awal->copy()->endOfYear();
                $nama  = 'tahunan_' . $awal->format('Y');
                $judul = 'Tahunan (' . $awal->year . ')';
        }

        $rows = $this->queryAdmin($cari, $status)
            ->whereBetween('tgl_pinjam', [$awal, $akhir])
            ->reorder('tgl_pinjam')
            ->get();

        $rows->each(fn(Peminjaman $r) => $r->setAttribute('status_label', $this->labelStatus($r)));

        $filter = ($cari !== '' || $status !== '') ? ', dengan filter' : '';

        Activity_log::catat_aktivitas_admin(
            'ekspor',
            'Peminjaman',
            null,
            "Laporan PDF riwayat peminjaman {$judul} ({$awal->format('d/m/Y')} - {$akhir->format('d/m/Y')}, {$rows->count()} data{$filter})"
        );

        return view('admin.riwayat', [
            'laporan'   => true,
            'rows'      => $rows,
            'judul'     => $judul,
            'awal'      => $awal,
            'akhir'     => $akhir,
            'cari'      => $cari,
            'statusLbl' => $status !== '' ? self::OPSI_STATUS[$status] : '',
            'namaFile'  => "laporan_peminjaman_{$nama}",
            'ringkasan' => [
                'Dipinjam'            => $rows->where('status_label', 'Dipinjam')->count(),
                'Terlambat'           => $rows->where('status_label', 'Terlambat')->count(),
                'Selesai'             => $rows->where('status_label', 'Selesai')->count(),
                'Selesai (Terlambat)' => $rows->where('status_label', 'Selesai (Terlambat)')->count(),
            ],
        ]);
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

    /**
     * Tentukan label status satu transaksi.
     */
    private function labelStatus(Peminjaman $item): string
    {
        // Sudah dikembalikan: cek tepat waktu atau terlambat
        if ($item->tgl_kembali) {
            if ($item->tgl_jatuh_tempo && $item->tgl_kembali->gt($item->tgl_jatuh_tempo)) {
                return 'Selesai (Terlambat)';
            }
            return 'Selesai';
        }

        // Belum dikembalikan: cek sudah lewat jatuh tempo atau belum
        if ($item->tgl_jatuh_tempo && $item->tgl_jatuh_tempo->isPast()) {
            return 'Terlambat';
        }

        return 'Dipinjam';
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
            // Selesai: sudah kembali dan tepat waktu (kembali <= jatuh tempo, atau tidak ada jatuh tempo)
            ->when($status === 'selesai', function (Builder $q) {
                $q->whereNotNull('tgl_kembali')
                    ->where(fn(Builder $w) => $w->whereNull('tgl_jatuh_tempo')
                        ->orWhereColumn('tgl_kembali', '<=', 'tgl_jatuh_tempo'));
            })
            // Selesai (Terlambat): sudah kembali tapi melewati jatuh tempo
            ->when($status === 'selesai_terlambat', function (Builder $q) {
                $q->whereNotNull('tgl_kembali')
                    ->whereNotNull('tgl_jatuh_tempo')
                    ->whereColumn('tgl_kembali', '>', 'tgl_jatuh_tempo');
            })
            // Terlambat: belum kembali dan sudah lewat jatuh tempo
            ->when($status === 'terlambat', fn(Builder $q) => $q->whereNull('tgl_kembali')->where('tgl_jatuh_tempo', '<', now()))
            // Dipinjam: belum kembali dan belum lewat jatuh tempo
            ->when($status === 'dipinjam', function (Builder $q) {
                $q->whereNull('tgl_kembali')
                    ->where(fn(Builder $w) => $w->whereNull('tgl_jatuh_tempo')->orWhere('tgl_jatuh_tempo', '>=', now()));
            })
            ->orderByDesc('tgl_pinjam');
    }

    private function ambilRiwayat(): Collection
    {
        return Peminjaman::with(['barang', 'peminjam'])
            ->orderByDesc('tgl_pinjam')
            ->get();
    }

    private function samarkanNama(string $nama): string
    {
        return collect(explode(' ', $nama))
            ->map(fn(string $kata) => mb_substr($kata, 0, 1) . str_repeat('*', max(mb_strlen($kata) - 1, 0)))
            ->implode(' ');
    }
}