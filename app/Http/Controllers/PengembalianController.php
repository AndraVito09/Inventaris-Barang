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

    // POST /peminjam/kembali/cek -> baca kode dari QR, kirim detail transaksi
    public function cek(Request $request): JsonResponse
    {
        $request->validate(['kode' => 'required|string|max:50']);

        $pinjam = Peminjaman::with('barang')
            ->where('kode', $request->kode)
            ->first();

        if (!$pinjam) {
            return response()->json(['ok' => false, 'pesan' => 'Struk tidak ditemukan.'], 404);
        }

        if ($pinjam->status === 'dikembalikan') {
            return response()->json(['ok' => false, 'pesan' => 'Barang pada struk ini sudah dikembalikan.'], 422);
        }

        return response()->json([
            'ok'   => true,
            'data' => [
                'kode'          => $pinjam->kode,
                'nama'          => $pinjam->nama_peminjam,
                'kelas'         => $pinjam->kelas,
                'barang'        => $pinjam->barang->pluck('nama')->all(),
                'batas_kembali' => $pinjam->batas_kembali->toIso8601String(),
            ],
        ]);
    }

    // POST /peminjam/kembali/proses -> tandai dikembalikan, catat keterlambatan di server
    public function proses(Request $request): JsonResponse
    {
        $request->validate(['kode' => 'required|string|max:50']);

        return DB::transaction(function () use ($request) {
            $pinjam = Peminjaman::with('barang')
                ->where('kode', $request->kode)
                ->lockForUpdate()
                ->first();

            if (!$pinjam) {
                return response()->json(['ok' => false, 'pesan' => 'Struk tidak ditemukan.'], 404);
            }

            if ($pinjam->status === 'dikembalikan') {
                return response()->json(['ok' => false, 'pesan' => 'Barang sudah dikembalikan sebelumnya.'], 422);
            }

            $sekarang = now();
            $terlambat = $sekarang->greaterThan($pinjam->batas_kembali);

            $pinjam->update([
                'status'           => 'dikembalikan',
                'dikembalikan_pada' => $sekarang,
                'terlambat'        => $terlambat,
                'menit_terlambat'  => $terlambat ? $pinjam->batas_kembali->diffInMinutes($sekarang) : 0,
            ]);

            // Barang kembali tersedia (sesuaikan nama kolom/status di tabel barang kamu)
            foreach ($pinjam->barang as $barang) {
                $barang->update(['status' => 'tersedia']);
            }

            return response()->json([
                'ok'        => true,
                'terlambat' => $terlambat,
                'sanksi'    => $terlambat ? 'Membersihkan lab' : null,
            ]);
        });
    }
}