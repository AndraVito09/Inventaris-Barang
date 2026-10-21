<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengembalianController extends Controller
{
    // GET /peminjam/kembali -> halaman scan QR
    public function index()
    {
        return view('peminjam.pengembalian'); // sesuaikan dengan lokasi file blade kamu
    }

    private function cariPeminjaman(string $kode, bool $kunci = false): ?Peminjaman
    {
        $kode = strtoupper(trim($kode));

        $q = Peminjaman::with(['barang', 'peminjam']);
        if ($kunci) {
            $q->lockForUpdate();
        }

        if (preg_match('/^TRX-(\d{8})-(\d+)-/', $kode, $m)) {
            return $q->where('id_peminjaman', (int) $m[2])->first();
        }

        return $q->whereHas('barang', fn ($b) => $b->where('kode_barcode', $kode))
            ->whereNull('tgl_kembali')
            ->latest('id_peminjaman')
            ->first();
    }

    // POST /peminjam/kembali/cek -> baca kode dari QR, kirim detail transaksi
    public function cek(Request $request): JsonResponse
    {
        $request->validate(['kode' => 'required|string|max:100']);

        $pinjam = $this->cariPeminjaman($request->kode);

        if (!$pinjam) {
            return response()->json(['ok' => false, 'pesan' => 'Struk tidak ditemukan.'], 404);
        }

        if ($pinjam->tgl_kembali !== null) {
            return response()->json(['ok' => false, 'pesan' => 'Barang pada struk ini sudah dikembalikan.'], 422);
        }

        return response()->json([
            'ok'   => true,
            'data' => [
                'kode'          => $request->kode,
                'nama'          => $pinjam->peminjam->nama_peminjam ?? '-',
                'kelas'         => $pinjam->peminjam->keterangan ?? ($pinjam->peminjam->tipe_peminjam ?? '-'),
                'barang'        => [$pinjam->barang->nama_barang ?? '-'],
                'batas_kembali' => $pinjam->tgl_jatuh_tempo->toIso8601String(),
            ],
        ]);
    }

    // POST /peminjam/kembali/proses -> tandai dikembalikan
    public function proses(Request $request): JsonResponse
    {
        $request->validate(['kode' => 'required|string|max:100']);

        return DB::transaction(function () use ($request) {
            $pinjam = $this->cariPeminjaman($request->kode, true);

            if (!$pinjam) {
                return response()->json(['ok' => false, 'pesan' => 'Struk tidak ditemukan.'], 404);
            }

            if ($pinjam->tgl_kembali !== null) {
                return response()->json(['ok' => false, 'pesan' => 'Barang sudah dikembalikan sebelumnya.'], 422);
            }

            $sekarang  = now();
            $batas     = $pinjam->tgl_jatuh_tempo;
            $terlambat = $sekarang->greaterThan($batas);

            $pinjam->update([
                'tgl_kembali'       => $sekarang,
                'status_peminjaman' => $terlambat ? 'Terlambat' : 'Selesai',
            ]);

            // Barang kembali tersedia
            $pinjam->barang?->update(['status_barang' => 'Tersedia']);

            return response()->json([
                'ok'              => true,
                'terlambat'       => $terlambat,
                'menit_terlambat' => $terlambat ? (int) $batas->diffInMinutes($sekarang, true) : 0,
                'sanksi'          => $terlambat ? 'Membersihkan lab' : null,
            ]);
        });
    }
}