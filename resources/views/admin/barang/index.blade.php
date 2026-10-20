@extends('layouts.admin')

@section('title', 'Data Barang')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Data Barang</h4>
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTambah">+ Tambah Barang</button>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        {{-- Cari & filter kategori --}}
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-4">
                <input type="text" name="q" value="{{ request('q') }}" class="form-control"
                       placeholder="Cari nama atau kode barang...">
            </div>
            <div class="col-md-3">
                <select name="kategori" class="form-select">
                    <option value="">Semua kategori</option>
                    @foreach ($kategori as $k)
                        <option value="{{ $k->id_kategori }}" @selected(request('kategori') == $k->id_kategori)>
                            {{ $k->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-outline-success">Cari</button>
                @if (request('q') || request('kategori'))
                    <a href="{{ route('barang.index') }}" class="btn btn-outline-secondary">Reset</a>
                @endif
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width:60px">No</th>
                        <th style="width:140px">Kode Barcode</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th style="width:120px">Status</th>
                        <th style="width:170px" class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($barang as $item)
                        <tr>
                            <td>{{ $barang->firstItem() + $loop->index }}</td>
                            <td><code>{{ $item->kode_barcode }}</code></td>
                            <td>{{ $item->nama_barang }}</td>
                            <td>{{ $item->kategori->nama_kategori ?? '-' }}</td>
                            <td>
                                <span class="badge {{ $item->status_barang === 'Tersedia' ? 'bg-success' : 'bg-warning text-dark' }}">
                                    {{ $item->status_barang }}
                                </span>
                            </td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-warning btn-edit"
                                        data-url="{{ route('barang.update', $item->id_barang) }}"
                                        data-nama="{{ $item->nama_barang }}"
                                        data-kategori="{{ $item->id_kategori }}">Edit</button>
                                <button class="btn btn-sm btn-danger btn-hapus"
                                        data-url="{{ route('barang.destroy', $item->id_barang) }}"
                                        data-nama="{{ $item->nama_barang }}">Hapus</button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">Belum ada data barang.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $barang->links('pagination::bootstrap-5') }}
    </div>
</div>

{{-- Modal Tambah --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="{{ route('barang.store') }}" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Tambah Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nama Barang</label>
                    <input type="text" name="nama_barang" value="{{ old('nama_barang') }}"
                           class="form-control" maxlength="100" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Kategori</label>
                    <select name="id_kategori" class="form-select" required>
                        <option value="">-- Pilih kategori --</option>
                        @foreach ($kategori as $k)
                            <option value="{{ $k->id_kategori }}" @selected(old('id_kategori') == $k->id_kategori)>
                                {{ $k->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <small class="text-muted">Kode barcode (mis. BRG-0001) dibuat otomatis dan status awal "Tersedia".</small>
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
            <div class="modal-header">
                <h5 class="modal-title">Edit Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nama Barang</label>
                    <input type="text" name="nama_barang" id="editNama" class="form-control" maxlength="100" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Kategori</label>
                    <select name="id_kategori" id="editKategori" class="form-select" required>
                        @foreach ($kategori as $k)
                            <option value="{{ $k->id_kategori }}">{{ $k->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

{{-- Form hapus (dikirim setelah konfirmasi) --}}
<form method="POST" id="formHapus" class="d-none">
    @csrf @method('DELETE')
</form>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const tema = { background: '#0d1a14', color: '#e8f5ef' };

    // ===== Popup hasil (dari session flash / error validasi) =====
    @if (session('success'))
        Swal.fire({ ...tema, icon: 'success', title: 'Berhasil', text: @json(session('success')) });
    @elseif (session('info'))
        Swal.fire({ ...tema, icon: 'info', title: 'Info', text: @json(session('info')) });
    @elseif (session('error'))
        Swal.fire({ ...tema, icon: 'error', title: 'Gagal', text: @json(session('error')) });
    @elseif ($errors->any())
        Swal.fire({ ...tema, icon: 'error', title: 'Data tidak valid', text: @json($errors->first()) });
    @endif

    // ===== Edit: isi modal, lalu konfirmasi sebelum kirim =====
    const formEdit = document.getElementById('formEdit');

    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', () => {
            formEdit.action = btn.dataset.url;
            document.getElementById('editNama').value = btn.dataset.nama;
            document.getElementById('editKategori').value = btn.dataset.kategori;
            new bootstrap.Modal('#modalEdit').show();
        });
    });

    formEdit.addEventListener('submit', e => {
        e.preventDefault();
        Swal.fire({
            ...tema,
            icon: 'question',
            title: 'Yakin ingin mengupdate?',
            showCancelButton: true,
            confirmButtonText: 'Ya, update',
            cancelButtonText: 'Batal',
        }).then(r => { if (r.isConfirmed) formEdit.submit(); });
    });

    // ===== Hapus: konfirmasi dulu =====
    const formHapus = document.getElementById('formHapus');

    document.querySelectorAll('.btn-hapus').forEach(btn => {
        btn.addEventListener('click', () => {
            Swal.fire({
                ...tema,
                icon: 'warning',
                title: 'Yakin hapus barang?',
                text: btn.dataset.nama,
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Tidak',
            }).then(r => {
                if (r.isConfirmed) {
                    formHapus.action = btn.dataset.url;
                    formHapus.submit();
                }
            });
        });
    });
</script>
@endpush