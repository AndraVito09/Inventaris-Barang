<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Activity_log;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $barang = Barang::with('kategori')
            ->when($request->q, function ($query, $cari) {
                $query->where(function ($w) use ($cari) {
                    $w->where('nama_barang', 'like', "%{$cari}%")
                        ->orWhere('kode_barcode', 'like', "%{$cari}%");
                });
            })
            ->when($request->kategori, fn($query, $id) => $query->where('id_kategori', $id))
            ->orderByDesc('id_barang')
            ->paginate(10)
            ->withQueryString();

        $kategori = Kategori::orderBy('nama_kategori')->get();

        return view('admin.barang.index', compact('barang', 'kategori'));
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules(), $this->messages());

        $barang = DB::transaction(function () use ($data) {
            $data['kode_barcode'] = $this->buatKodeBarcode();
            $data['status_barang'] = 'Tersedia';

            return Barang::create($data);
        });

        return redirect()->route('barang.index')
            ->with('success', "Barang berhasil ditambahkan dengan kode {$barang->kode_barcode}.")
            ->with('cetak_url', route('barang.cetak', $barang->id_barang));
    }

    public function update(Request $request, Barang $barang)
    {
        $data = $request->validate($this->rules(), $this->messages());

        $barang->fill($data);

        if (! $barang->isDirty()) {
            return redirect()->route('barang.index')->with('info', 'Data tidak terubah');
        }

        $barang->save();

        return redirect()->route('barang.index')->with('success', 'Data terupdate.');
    }

    public function destroy(Barang $barang)
    {
        if ($barang->status_barang === 'Dipinjam') {
            return redirect()->route('barang.index')
                ->with('error', 'Barang tidak bisa dihapus karena sedang dipinjam');
        }

        if ($barang->peminjaman()->exists()) {
            return redirect()->route('barang.index')
                ->with('error', 'Barang tidak bisa dihapus karena memiliki riwayat peminjaman');
        }

        try {
            $barang->delete();
        } catch (QueryException $e) {
            return redirect()->route('barang.index')
                ->with('error', 'Barang tidak bisa dihapus karena masih terhubung dengan data lain');
        }

        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus.');
    }

    public function cetak(Request $request, Barang $barang)
    {
        Activity_log::create([
            'id_admin'   => Auth::id(),
            'aktor'      => 'admin',
            'aksi'       => 'cetak_barcode',
            'nama_tabel' => 'barang',
            'id_data'    => $barang->id_barang,
            'deskripsi'  => "Cetak barcode {$barang->kode_barcode} ({$barang->nama_barang})",
            'ip_address' => $request->ip(),
        ]);

        return view('admin.barang.cetak', compact('barang'));
    }

    private function rules(): array
    {
        return [
            'nama_barang' => ['required', 'string', 'max:100'],
            'id_kategori' => ['required', 'integer', 'exists:kategori,id_kategori'],
        ];
    }

    private function messages(): array
    {
        return [
            'nama_barang.required' => 'Nama barang wajib diisi.',
            'nama_barang.max'      => 'Nama barang maksimal 100 karakter.',
            'id_kategori.required' => 'Kategori wajib dipilih.',
            'id_kategori.exists'   => 'Kategori yang dipilih tidak valid.',
        ];
    }

    private function buatKodeBarcode(): string
    {
        $terakhir = Barang::lockForUpdate()
            ->orderByDesc('id_barang')
            ->value('kode_barcode');

        $nomor = $terakhir ? ((int) substr($terakhir, 4)) + 1 : 1;

        return 'BRG-' . str_pad((string) $nomor, 4, '0', STR_PAD_LEFT);
    }
}