<?php

namespace App\Http\Controllers;

use App\Models\Activity_log;
use App\Models\Auth_log;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class LogController extends Controller
{
    private const PER_HALAMAN = 20;

    private const OPSI_AKSI = [
        'tambah'        => 'Tambah',
        'ubah'          => 'Ubah',
        'hapus'         => 'Hapus',
        'pinjam'        => 'Pinjam',
        'kembali'       => 'Kembali',
        'ekspor'        => 'Ekspor',
        'cetak_barcode' => 'Cetak Barcode',
    ];

    private const OPSI_AKTOR = [
        'admin'    => 'Admin',
        'peminjam' => 'Peminjam',
        'sistem'   => 'Sistem',
    ];

    private const OPSI_HASIL = [
        'login_berhasil' => 'Login berhasil',
        'login_gagal'    => 'Login gagal',
        'logout'         => 'Logout',
    ];


    public function index(Request $request): View
    {
        $tab = $request->query('tab') === 'keamanan' ? 'keamanan' : 'aktivitas';

        $validator = Validator::make($request->query(), [
            'dari'   => ['nullable', 'date'],
            'sampai' => ['nullable', 'date', 'after_or_equal:dari'],
            'aksi'   => ['nullable', Rule::in(array_keys(self::OPSI_AKSI))],
            'aktor'  => ['nullable', Rule::in(array_keys(self::OPSI_AKTOR))],
            'hasil'  => ['nullable', Rule::in(array_keys(self::OPSI_HASIL))],
        ], [
            'dari.date'              => 'Tanggal awal tidak valid.',
            'sampai.date'            => 'Tanggal akhir tidak valid.',
            'sampai.after_or_equal'  => 'Tanggal akhir tidak boleh sebelum tanggal awal.',
            'aksi.in'                => 'Pilihan aksi tidak valid.',
            'aktor.in'               => 'Pilihan aktor tidak valid.',
            'hasil.in'               => 'Pilihan hasil tidak valid.',
        ]);

        // Filter yang tidak valid diabaikan semua, lalu pesan kesalahan ditampilkan.
        $filter = $validator->fails() ? [] : $validator->validated();

        $data = $tab === 'aktivitas'
            ? $this->queryAktivitas($filter)
            : $this->queryKeamanan($filter);

        return view('admin.log.index', [
            'tab'        => $tab,
            'data'       => $data->withQueryString(),
            'filter'     => $filter,
            'opsiAksi'   => self::OPSI_AKSI,
            'opsiAktor'  => self::OPSI_AKTOR,
            'opsiHasil'  => self::OPSI_HASIL,
        ])->withErrors($validator);
    }

    private function queryAktivitas(array $filter)
    {
        return Activity_log::with('admin')
            ->when($filter['dari'] ?? null, fn($q, $v) => $q->where('created_at', '>=', Carbon::parse($v)->startOfDay()))
            ->when($filter['sampai'] ?? null, fn($q, $v) => $q->where('created_at', '<=', Carbon::parse($v)->endOfDay()))
            ->when($filter['aksi'] ?? null, fn($q, $v) => $q->where('aksi', $v))
            ->when($filter['aktor'] ?? null, fn($q, $v) => $q->where('aktor', $v))
            ->orderByDesc('created_at')
            ->orderByDesc('id_activity_log')
            ->paginate(self::PER_HALAMAN);
    }

    private function queryKeamanan(array $filter)
    {
        return Auth_log::query()
            ->when($filter['dari'] ?? null, fn($q, $v) => $q->where('created_at', '>=', Carbon::parse($v)->startOfDay()))
            ->when($filter['sampai'] ?? null, fn($q, $v) => $q->where('created_at', '<=', Carbon::parse($v)->endOfDay()))
            ->when($filter['hasil'] ?? null, fn($q, $v) => $q->where('aksi', $v))
            ->orderByDesc('created_at')
            ->orderByDesc('id_auth_log')
            ->paginate(self::PER_HALAMAN);
    }
}
