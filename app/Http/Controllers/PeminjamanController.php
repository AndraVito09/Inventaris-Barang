<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Peminjam;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PeminjamanController extends Controller
{
    // Pilihan pada dropdown form peminjaman
    private const TIPE_PEMINJAM = ['Siswa', 'Guru', 'Staff', 'Orang Tua/Lainnya'];

    // Dashboard peminjam
    public function index()
    {
        return view('peminjam.dashboard-peminjam', [
            'totalBarang'    => Barang::count(),
            'barangTersedia' => Barang::where('status_barang', 'Tersedia')->count(),
            'sedangDipinjam' => Barang::where('status_barang', 'Dipinjam')->count(),
            'terlambat'      => Peminjaman::where('status_peminjaman', 'Dipinjam')
                                    ->where('tgl_jatuh_tempo', '<', now())->count(),
        ]);
    }

    // Tampilkan form peminjaman
    public function formPinjam()
    {
        return view('peminjam.form-peminjaman', ['daftarTipe' => self::TIPE_PEMINJAM]);
    }

    // Cek barang hasil scan barcode (dipanggil lewat fetch dari form)
    public function cekBarang(string $kode)
    {
        $barang = Barang::where('kode_barcode', trim($kode))->first();

        if (! $barang) {
            return response()->json(['ok' => false, 'pesan' => 'Barang tidak ditemukan. Periksa kembali barcode.'], 404);
        }

        if ($barang->status_barang !== 'Tersedia') {
            return response()->json(['ok' => false, 'pesan' => "{$barang->nama_barang} sedang dipinjam."], 422);
        }

        return response()->json(['ok' => true, 'nama' => $barang->nama_barang]);
    }

    // Simpan peminjaman dan ubah status barang
    public function simpan(Request $request)
    {
        $tipe = $request->input('tipe_peminjam');

        $request->validate([
            'tipe_peminjam' => ['required', Rule::in(self::TIPE_PEMINJAM)],
            'kode_barcode'  => ['required', 'string', 'max:50'],
            'nama_peminjam' => ['required', 'string', 'max:100'],
            'lama_pinjam'   => ['required', 'integer', 'between:1,14'],
            'nis'           => [Rule::requiredIf($tipe === 'Siswa'), 'nullable', 'string', 'max:30'],
            'kelas'         => [Rule::requiredIf($tipe === 'Siswa'), 'nullable', 'string', 'max:30'],
            'nip'           => ['nullable', 'string', 'max:30'],
            'bagian'        => [Rule::requiredIf($tipe === 'Staff'), 'nullable', 'string', 'max:100'],
            'no_hp'         => [Rule::requiredIf($tipe === 'Orang Tua/Lainnya'), 'nullable', 'string', 'max:20'],
            'keperluan'     => [Rule::requiredIf($tipe === 'Orang Tua/Lainnya'), 'nullable', 'string', 'max:150'],
        ], [
            'tipe_peminjam.required' => 'Pilih tipe peminjam.',
            'kode_barcode.required'  => 'Scan barcode barang terlebih dahulu.',
            'nama_peminjam.required' => 'Nama peminjam wajib diisi.',
            'nis.required'           => 'NIS wajib diisi.',
            'kelas.required'         => 'Kelas wajib diisi.',
            'bagian.required'        => 'Bagian atau jabatan wajib diisi.',
            'no_hp.required'         => 'Nomor HP wajib diisi.',
            'keperluan.required'     => 'Keperluan wajib diisi.',
        ]);

        // Data tambahan digabung ke kolom "keterangan" sesuai tipe peminjam
        $keterangan = match ($tipe) {
            'Siswa' => "Kelas {$request->kelas}, NIS {$request->nis}",
            'Guru'  => $request->filled('nip') ? "NIP {$request->nip}" : null,
            'Staff' => $request->bagian,
            default => $request->keperluan,
        };
        $noHp = $tipe === 'Orang Tua/Lainnya' ? $request->no_hp : null;

        $peminjaman = DB::transaction(function () use ($request, $tipe, $keterangan, $noHp) {
            // Kunci baris barang supaya tidak bisa dipinjam dua orang sekaligus
            $barang = Barang::where('kode_barcode', trim($request->kode_barcode))->lockForUpdate()->first();

            if (! $barang) {
                throw ValidationException::withMessages(['kode_barcode' => 'Barang tidak ditemukan.']);
            }

            if ($barang->status_barang !== 'Tersedia') {
                throw ValidationException::withMessages(['kode_barcode' => "{$barang->nama_barang} sedang dipinjam."]);
            }

            $peminjam = Peminjam::create([
                'nama_peminjam' => $request->nama_peminjam,
                'tipe_peminjam' => $tipe,
                'keterangan'    => $keterangan,
                'no_hp'         => $noHp,
            ]);

            $barang->update(['status_barang' => 'Dipinjam']);

            return Peminjaman::create([
                'id_barang'         => $barang->id_barang,
                'id_peminjam'       => $peminjam->id_peminjam,
                'tgl_pinjam'        => now(),
                'tgl_jatuh_tempo'   => now()->addDays((int) $request->lama_pinjam),
                'status_peminjaman' => 'Dipinjam',
            ]);
        });

        // kode_transaksi dihitung otomatis dari model (tidak disimpan di database)
        return redirect('/peminjam/struk/' . $peminjaman->kode_transaksi)
            ->with('berhasil', 'Data peminjaman berhasil disimpan.');
    }

    // Struk + QR code bukti peminjaman
    public function struk(string $kode)
    {
        $peminjaman = Peminjaman::cariDariKode($kode);

        abort_if($peminjaman === null, 404);

        $peminjaman->load(['barang', 'peminjam']);

        return view('peminjam.struk', compact('peminjaman'));
    }
}