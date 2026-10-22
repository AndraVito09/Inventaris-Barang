@extends('layouts.admin')

@section('title', 'Kategori Barang')

@push('styles')
<style>
    /* Tema mengikuti Bootstrap data-bs-theme */
    [data-bs-theme="light"] {
        --kat-kartu: #ffffff;
        --kat-hover: rgba(11, 36, 71, .06);
        --kat-bayangan: rgba(11, 36, 71, .12);
        --kat-emas: #f1b53d;
        --kat-border: rgba(11, 36, 71, .16);
        --kat-biru: #1d4fa8;
    }

    [data-bs-theme="dark"] {
        --kat-kartu: #102445;
        --kat-hover: rgba(173, 198, 245, .10);
        --kat-bayangan: rgba(0, 0, 0, .35);
        --kat-emas: #f1b53d;
        --kat-border: rgba(173, 198, 245, .16);
        --kat-biru: #6ea8ff;
    }

    .kategori-halaman {
        font-family: 'Bricolage Grotesque', system-ui, sans-serif;
    }

    .kategori-judul {
        font-size: clamp(1.7rem, 4vw, 2.4rem);
        font-weight: 800;
        letter-spacing: -.025em;
        line-height: 1.15;
    }

    .kategori-label {
        color: var(--bs-secondary-color);
        font-size: .75rem;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
    }

    .kategori-kartu {
        background: var(--kat-kartu);
        border: 1px solid var(--kat-border);
        border-radius: 18px;
        box-shadow: 0 8px 25px var(--kat-bayangan);
        overflow: hidden;
    }

    .kategori-kartu-header {
        padding: 22px 24px;
        border-bottom: 1px solid var(--kat-border);
    }

    .kategori-kartu-body {
        padding: 24px;
    }

    .kategori-ikon {
        width: 48px;
        height: 48px;
        flex-shrink: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        background: color-mix(in srgb, var(--kat-biru) 14%, transparent);
        color: var(--kat-biru);
        font-size: 1.35rem;
    }

    .btn-kategori-emas {
        --bs-btn-color: #1a1405;
        --bs-btn-bg: var(--kat-emas);
        --bs-btn-border-color: var(--kat-emas);
        --bs-btn-hover-color: #1a1405;
        --bs-btn-hover-bg: #f7c55c;
        --bs-btn-hover-border-color: #f7c55c;
        --bs-btn-active-color: #1a1405;
        --bs-btn-active-bg: #f7c55c;
        --bs-btn-active-border-color: #f7c55c;
        border-radius: 10px;
        padding: 11px 18px;
        font-weight: 700;
        transition: transform .2s, background .2s;
    }

    .btn-kategori-emas:hover {
        transform: translateY(-2px);
    }

    .kategori-halaman .btn-outline-secondary {
        --bs-btn-color: var(--bs-body-color);
        --bs-btn-border-color: var(--kat-border);
        --bs-btn-hover-color: var(--bs-body-color);
        --bs-btn-hover-bg: var(--kat-hover);
        --bs-btn-hover-border-color: var(--bs-secondary-color);
        --bs-btn-active-color: var(--bs-body-color);
        --bs-btn-active-bg: var(--kat-hover);
        --bs-btn-active-border-color: var(--bs-secondary-color);
        border-radius: 9px;
    }

    .kategori-search {
        min-height: 44px;
        border-radius: 10px;
        background-color: var(--kat-kartu);
        border-color: var(--kat-border);
        color: var(--bs-body-color);
    }

    .kategori-search:focus {
        background-color: var(--kat-kartu);
        color: var(--bs-body-color);
        border-color: var(--kat-biru);
        box-shadow: 0 0 0 .2rem color-mix(in srgb, var(--kat-biru) 20%, transparent);
    }

    .kategori-tabel {
        margin-bottom: 0;
        vertical-align: middle;
        --bs-table-bg: transparent;
        --bs-table-color: var(--bs-body-color);
        --bs-table-border-color: var(--kat-border);
        --bs-table-hover-bg: var(--kat-hover);
        --bs-table-hover-color: var(--bs-body-color);
    }

    .kategori-tabel thead th {
        padding: 15px 16px;
        color: var(--bs-secondary-color);
        background: var(--kat-hover);
        font-size: .76rem;
        font-weight: 700;
        letter-spacing: .7px;
        text-transform: uppercase;
        white-space: nowrap;
        border-bottom: 1px solid var(--kat-border);
    }

    .kategori-tabel tbody td {
        padding: 17px 16px;
        border-color: var(--kat-border);
    }

    .kategori-nomor {
        display: inline-flex;
        width: 34px;
        height: 34px;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: var(--kat-hover);
        font-weight: 700;
        font-size: .85rem;
    }

    .kategori-jumlah {
        display: inline-flex;
        min-width: 38px;
        justify-content: center;
        padding: 6px 11px;
        border-radius: 8px;
        background: color-mix(in srgb, var(--kat-biru) 13%, transparent);
        color: var(--kat-biru);
        font-weight: 700;
        font-size: .85rem;
    }

    .kategori-aksi {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 7px 11px;
        border-radius: 8px;
        font-size: .82rem;
        font-weight: 600;
    }

    .kategori-kosong {
        padding: 50px 20px !important;
        color: var(--bs-secondary-color);
    }

    .kategori-kosong i {
        display: block;
        font-size: 2.5rem;
        margin-bottom: 12px;
        opacity: .65;
    }

    .kategori-halaman .modal-content {
        color: var(--bs-body-color);
        background: var(--kat-kartu);
        border: 1px solid var(--kat-border);
        border-radius: 18px;
        box-shadow: 0 20px 60px var(--kat-bayangan);
    }

    .kategori-halaman .modal-header,
    .kategori-halaman .modal-footer {
        border-color: var(--kat-border);
    }

    .kategori-halaman .form-control {
        color: var(--bs-body-color);
        background-color: var(--kat-kartu);
        border-color: var(--kat-border);
    }

    .kategori-halaman .form-control:focus {
        border-color: var(--kat-biru);
        box-shadow: 0 0 0 .2rem color-mix(in srgb, var(--kat-biru) 20%, transparent);
    }

    [data-bs-theme="dark"] .kategori-halaman .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
    }

    .kategori-halaman .pagination {
        gap: 5px;
        margin-bottom: 0;
    }

    .kategori-halaman .page-link {
        color: var(--bs-body-color);
        background: var(--kat-kartu);
        border-color: var(--kat-border);
        border-radius: 8px;
    }

    .kategori-halaman .page-item.active .page-link {
        color: #1a1405;
        background: var(--kat-emas);
        border-color: var(--kat-emas);
    }

    .kategori-halaman .page-item.disabled .page-link {
        color: var(--bs-secondary-color);
        background: var(--kat-hover);
    }

    .kategori-halaman .alert {
        border-radius: 12px;
    }

    @media (max-width: 767.98px) {
        .kategori-kartu-header,
        .kategori-kartu-body {
            padding: 17px;
        }

        .kategori-judul {
            font-size: 1.65rem;
        }

        .kategori-tabel thead th,
        .kategori-tabel tbody td {
            padding: 12px 10px;
        }

        .kategori-aksi {
            padding: 7px 9px;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .btn-kategori-emas {
            transition: none;
        }

        .btn-kategori-emas:hover {
            transform: none;
        }
    }
</style>
@endpush

@section('content')
<div class="kategori-halaman">

    {{-- Header halaman --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <div class="kategori-label mb-2">
                <i class="bi bi-grid me-1"></i> Manajemen Inventaris
            </div>

            <h1 class="kategori-judul mb-2">Kategori Barang</h1>

            <p class="text-body-secondary mb-0">
                Kelola kategori dan lihat jumlah barang pada setiap kategori.
            </p>
        </div>

        <button type="button"
                class="btn btn-kategori-emas"
                data-bs-toggle="modal"
                data-bs-target="#modalTambah">
            <i class="bi bi-plus-lg me-2"></i> Tambah Kategori
        </button>
    </div>

    {{-- Notifikasi --}}
    @foreach (['success' => 'success', 'error' => 'danger', 'info' => 'info'] as $key => $warna)
        @if (session($key))
            <div class="alert alert-{{ $warna }} alert-dismissible fade show"
                 role="alert">
                <i class="bi bi-info-circle-fill me-2"></i>
                {{ session($key) }}
                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Tutup"></button>
            </div>
        @endif
    @endforeach

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ $errors->first() }}
            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Tutup"></button>
        </div>
    @endif

    {{-- Kartu tabel kategori --}}
    <div class="kategori-kartu">

        <div class="kategori-kartu-header">
            <div class="d-flex flex-wrap align-items-center gap-3">
                <div class="kategori-ikon">
                    <i class="bi bi-collection-fill"></i>
                </div>

                <div class="flex-grow-1">
                    <h2 class="h5 fw-bold mb-1">Daftar Kategori</h2>
                    <div class="small text-body-secondary">
                        Data kategori barang inventaris
                    </div>
                </div>

                <span class="badge rounded-pill px-3 py-2"
                      style="background: var(--kat-hover); color: var(--bs-body-color); border: 1px solid var(--kat-border);">
                    <i class="bi bi-database me-1"></i>
                    {{ $kategori->total() }} kategori
                </span>
            </div>
        </div>

        <div class="kategori-kartu-body">

            {{-- Pencarian --}}
            <form method="GET"
                  action="{{ route('kategori.index') }}"
                  class="row g-2 align-items-center mb-4">

                <div class="col-12 col-md-5">
                    <div class="input-group">
                        <span class="input-group-text kategori-search">
                            <i class="bi bi-search"></i>
                        </span>

                        <input type="text"
                               name="q"
                               value="{{ request('q') }}"
                               class="form-control kategori-search"
                               placeholder="Cari nama kategori..."
                               aria-label="Cari kategori">
                    </div>
                </div>

                <div class="col-auto">
                    <button type="submit" class="btn btn-kategori-emas">
                        <i class="bi bi-search me-1"></i> Cari
                    </button>

                    @if (request('q'))
                        <a href="{{ route('kategori.index') }}"
                           class="btn btn-outline-secondary ms-1">
                            <i class="bi bi-arrow-counterclockwise me-1"></i>
                            Reset
                        </a>
                    @endif
                </div>
            </form>

            {{-- Tabel --}}
            <div class="table-responsive">
                <table class="table table-hover kategori-tabel">
                    <thead>
                        <tr>
                            <th style="width: 75px;">No.</th>
                            <th>Nama Kategori</th>
                            <th style="width: 155px;">Jumlah Barang</th>
                            <th style="width: 180px;" class="text-end">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($kategori as $item)
                            <tr>
                                <td>
                                    <span class="kategori-nomor">
                                        {{ $kategori->firstItem() + $loop->index }}
                                    </span>
                                </td>

                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="kategori-ikon"
                                             style="width: 38px; height: 38px; border-radius: 10px; font-size: 1rem;">
                                            <i class="bi bi-box-seam"></i>
                                        </div>

                                        <span class="fw-semibold">
                                            {{ $item->nama_kategori }}
                                        </span>
                                    </div>
                                </td>

                                <td>
                                    <span class="kategori-jumlah">
                                        {{ $item->barang_count }}
                                    </span>
                                    <span class="small text-body-secondary ms-1">
                                        barang
                                    </span>
                                </td>

                                <td class="text-end">
                                    <div class="d-inline-flex flex-wrap justify-content-end gap-2">
                                        <button type="button"
                                                class="btn btn-outline-secondary kategori-aksi btn-edit"
                                                data-url="{{ route('kategori.update', $item->id_kategori) }}"
                                                data-nama="{{ $item->nama_kategori }}">
                                            <i class="bi bi-pencil-square"></i>
                                            Edit
                                        </button>

                                        <button type="button"
                                                class="btn btn-outline-danger kategori-aksi btn-hapus"
                                                data-url="{{ route('kategori.destroy', $item->id_kategori) }}"
                                                data-nama="{{ $item->nama_kategori }}">
                                            <i class="bi bi-trash3"></i>
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center kategori-kosong">
                                    <i class="bi bi-inbox"></i>

                                    <div class="fw-bold mb-1">
                                        Belum ada data kategori
                                    </div>

                                    <div class="small">
                                        @if (request('q'))
                                            Kategori dengan kata kunci tersebut tidak ditemukan.
                                        @else
                                            Klik tombol Tambah Kategori untuk menambahkan data baru.
                                        @endif
                                    </div>

                                    @if (request('q'))
                                        <a href="{{ route('kategori.index') }}"
                                           class="btn btn-outline-secondary btn-sm mt-3">
                                            <i class="bi bi-arrow-counterclockwise me-1"></i>
                                            Reset pencarian
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($kategori->hasPages())
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mt-4">
                    <small class="text-body-secondary">
                        Menampilkan
                        {{ $kategori->firstItem() ?? 0 }}–{{ $kategori->lastItem() ?? 0 }}
                        dari {{ $kategori->total() }} kategori
                    </small>

                    <div>
                        {{ $kategori->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>

{{-- Modal Tambah --}}
<div class="modal fade" id="modalTambah" tabindex="-1"
     aria-labelledby="judulModalTambah" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST"
              action="{{ route('kategori.store') }}"
              class="modal-content">
            @csrf

            <div class="modal-header">
                <div>
                    <h2 class="modal-title fs-5 fw-bold" id="judulModalTambah">
                        <i class="bi bi-plus-circle me-2"></i>
                        Tambah Kategori
                    </h2>
                    <div class="small text-body-secondary mt-1">
                        Tambahkan kategori barang baru.
                    </div>
                </div>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Tutup"></button>
            </div>

            <div class="modal-body p-4">
                <label for="namaKategori" class="form-label fw-semibold">
                    Nama Kategori
                </label>

                <input type="text"
                       name="nama_kategori"
                       id="namaKategori"
                       class="form-control"
                       maxlength="100"
                       placeholder="Contoh: Elektronik"
                       required>
            </div>

            <div class="modal-footer p-3">
                <button type="button"
                        class="btn btn-outline-secondary"
                        data-bs-dismiss="modal">
                    Batal
                </button>

                <button type="submit" class="btn btn-kategori-emas">
                    <i class="bi bi-check-lg me-1"></i>
                    Simpan Kategori
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Edit --}}
<div class="modal fade" id="modalEdit" tabindex="-1"
     aria-labelledby="judulModalEdit" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" id="formEdit" class="modal-content">
            @csrf
            @method('PUT')

            <div class="modal-header">
                <div>
                    <h2 class="modal-title fs-5 fw-bold" id="judulModalEdit">
                        <i class="bi bi-pencil-square me-2"></i>
                        Edit Kategori
                    </h2>
                    <div class="small text-body-secondary mt-1">
                        Ubah nama kategori barang.
                    </div>
                </div>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Tutup"></button>
            </div>

            <div class="modal-body p-4">
                <label for="editNama" class="form-label fw-semibold">
                    Nama Kategori
                </label>

                <input type="text"
                       name="nama_kategori"
                       id="editNama"
                       class="form-control"
                       maxlength="100"
                       required>
            </div>

            <div class="modal-footer p-3">
                <button type="button"
                        class="btn btn-outline-secondary"
                        data-bs-dismiss="modal">
                    Batal
                </button>

                <button type="submit" class="btn btn-kategori-emas">
                    <i class="bi bi-check-lg me-1"></i>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Hapus --}}
<div class="modal fade" id="modalHapus" tabindex="-1"
     aria-labelledby="judulModalHapus" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <form method="POST" id="formHapus" class="modal-content">
            @csrf
            @method('DELETE')

            <div class="modal-body text-center p-4">
                <div class="kategori-ikon mx-auto mb-3"
                     style="width: 58px; height: 58px; color: var(--bs-danger); background: color-mix(in srgb, var(--bs-danger) 12%, transparent);">
                    <i class="bi bi-trash3"></i>
                </div>

                <h2 class="h5 fw-bold mb-2" id="judulModalHapus">
                    Hapus Kategori?
                </h2>

                <p class="text-body-secondary small mb-1">
                    Apakah kamu yakin ingin menghapus kategori ini?
                </p>

                <p class="fw-bold mb-4" id="hapusNama"></p>

                <div class="d-flex gap-2 justify-content-center">
                    <button type="button"
                            class="btn btn-outline-secondary flex-fill"
                            data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit" class="btn btn-danger flex-fill">
                        <i class="bi bi-trash3 me-1"></i>
                        Ya, Hapus
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Modal Edit
    document.querySelectorAll('.btn-edit').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.getElementById('formEdit').action = this.dataset.url;
            document.getElementById('editNama').value = this.dataset.nama;

            bootstrap.Modal.getOrCreateInstance(
                document.getElementById('modalEdit')
            ).show();
        });
    });

    // Modal Hapus
    document.querySelectorAll('.btn-hapus').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.getElementById('formHapus').action = this.dataset.url;
            document.getElementById('hapusNama').textContent = this.dataset.nama;

            bootstrap.Modal.getOrCreateInstance(
                document.getElementById('modalHapus')
            ).show();
        });
    });
});
</script>
@endpush