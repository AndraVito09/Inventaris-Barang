@extends('layouts.admin')

@section('title', 'Kategori')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Kategori Barang</h4>
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTambah">+ Tambah Kategori</button>
</div>

{{-- Pesan notifikasi --}}
@foreach (['success' => 'success', 'error' => 'danger', 'info' => 'info'] as $key => $warna)
    @if (session($key))
        <div class="alert alert-{{ $warna }} alert-dismissible fade show" role="alert">
            {{ session($key) }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
@endforeach

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ $errors->first() }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card shadow-sm">
    <div class="card-body">
        {{-- Pencarian --}}
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-4">
                <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari kategori...">
            </div>
            <div class="col-auto">
                <button class="btn btn-outline-success">Cari</button>
                @if (request('q'))
                    <a href="{{ route('kategori.index') }}" class="btn btn-outline-secondary">Reset</a>
                @endif
            </div>
        </form>

        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th style="width:60px">No</th>
                    <th>Nama Kategori</th>
                    <th style="width:130px">Jumlah Barang</th>
                    <th style="width:170px" class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kategori as $item)
                    <tr>
                        <td>{{ $kategori->firstItem() + $loop->index }}</td>
                        <td>{{ $item->nama_kategori }}</td>
                        <td>{{ $item->barang_count }}</td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-warning btn-edit"
                                    data-url="{{ route('kategori.update', $item->id_kategori) }}"
                                    data-nama="{{ $item->nama_kategori }}">Edit</button>
                            <button class="btn btn-sm btn-danger btn-hapus"
                                    data-url="{{ route('kategori.destroy', $item->id_kategori) }}"
                                    data-nama="{{ $item->nama_kategori }}">Hapus</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted py-4">Belum ada data kategori.</td></tr>
                @endforelse
            </tbody>
        </table>

        {{ $kategori->links('pagination::bootstrap-5') }}
    </div>
</div>

{{-- Modal Tambah --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="{{ route('kategori.store') }}" class="modal-content">
            @csrf
            <div class="modal-header"><h5 class="modal-title">Tambah Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <label class="form-label">Nama Kategori</label>
                <input type="text" name="nama_kategori" class="form-control" maxlength="100" required>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Edit --}}
<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" id="formEdit" class="modal-content">
            @csrf @method('PUT')
            <div class="modal-header"><h5 class="modal-title">Edit Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <label class="form-label">Nama Kategori</label>
                <input type="text" name="nama_kategori" id="editNama" class="form-control" maxlength="100" required>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Hapus --}}
<div class="modal fade" id="modalHapus" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <form method="POST" id="formHapus" class="modal-content">
            @csrf @method('DELETE')
            <div class="modal-body text-center">
                <p class="mb-1">Yakin hapus kategori</p>
                <p class="fw-bold" id="hapusNama"></p>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tidak</button>
                    <button type="submit" class="btn btn-danger btn-sm">Ya, Hapus</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', () => {
            document.getElementById('formEdit').action = btn.dataset.url;
            document.getElementById('editNama').value = btn.dataset.nama;
            new bootstrap.Modal('#modalEdit').show();
        });
    });

    document.querySelectorAll('.btn-hapus').forEach(btn => {
        btn.addEventListener('click', () => {
            document.getElementById('formHapus').action = btn.dataset.url;
            document.getElementById('hapusNama').textContent = btn.dataset.nama;
            new bootstrap.Modal('#modalHapus').show();
        });
    });
</script>
@endpush