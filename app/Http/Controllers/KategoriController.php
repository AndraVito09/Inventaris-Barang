<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use Illuminate\Validation\Rule;
use App\Models\Activity_log;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        $kategori = Kategori::withCount('barang')
            ->when($request->q, fn($q, $cari) => $q->where('nama_kategori', 'like', "%{$cari}%"))
            ->orderBy('nama_kategori')
            ->paginate(10)
            ->withQueryString();

        return view('admin.kategori.index', compact('kategori'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_kategori' => ['required', 'string', 'max:100', 'unique:kategori,nama_kategori'],
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.unique'   => 'Nama kategori sudah ada.',
        ]);

        $kategori = Kategori::create($data);

        Activity_log::catat_aktivitas_admin(
            'tambah',
            'kategori',
            $kategori->id_kategori,
            "Tambah kategori {$kategori->id_kategori} ({$kategori->nama_kategori})"
        );

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, Kategori $kategori)
    {
        $data = $request->validate([
            'nama_kategori' => [
                'required',
                'string',
                'max:100',
                Rule::unique('kategori', 'nama_kategori')->ignore($kategori->id_kategori, 'id_kategori'),
            ],
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.unique'   => 'Nama kategori sudah ada.',
        ]);

        $kategori->fill($data);

        if (! $kategori->isDirty()) {
            return redirect()->route('kategori.index')->with('info', 'Data tidak berubah.');
        }

        $lama = $kategori->getOriginal('nama_kategori');
        $kategori->save();

        Activity_log::catat_aktivitas_admin(
            'ubah',
            'kategori',
            $kategori->id_kategori,
            "ubah Kategori: '{$lama}' → '{$kategori->nama_kategori}'"
        );

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Kategori $kategori)
    {
        if ($kategori->barang()->exists()) {
            return redirect()->route('kategori.index')
                ->with('error', 'Kategori tidak bisa dihapus karena masih dipakai oleh barang.');
        }

        Activity_log::catat_aktivitas_admin(
            'hapus',
            'kategori',
            $kategori->id_kategori,
            "Hapus kategori {$kategori->nama_kategori}"
        );
        $kategori->delete();

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
