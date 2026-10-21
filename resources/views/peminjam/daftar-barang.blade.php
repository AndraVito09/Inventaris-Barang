<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Barang - Inventaris Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        html[data-bs-theme="dark"] {
            --bs-body-bg: #060d0a;
            --bs-body-color: #e8f5ef;
            --bs-secondary-color: #5a8a70;
            --bs-border-color: rgba(0, 212, 138, 0.15);
            --teal: #00d48a;
            --teal-2: #00ffaa;
            --gold: #f5a623;
            --rose: #f05a7e;
        }

        .text-gradient {
            background: linear-gradient(135deg, var(--teal) 0%, var(--gold) 55%, var(--rose) 100%);
            -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;
        }

        .navbar-glass {
            background: rgba(6, 13, 10, 0.85);
            backdrop-filter: blur(24px);
            border-bottom: 1px solid var(--bs-border-color);
        }

        .badge-live {
            background: rgba(0, 212, 138, 0.08);
            border: 1.5px solid rgba(0, 212, 138, 0.25);
            color: var(--teal);
        }

        .footer-dark {
            background: #040a07;
            border-top: 2px solid;
            border-image: linear-gradient(90deg, var(--teal), var(--gold), var(--rose)) 1;
        }

        .table-card {
            border-radius: 20px;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--bs-border-color);
        }

        .table {
            --bs-table-bg: transparent;
            --bs-table-color: var(--bs-body-color);
            --bs-table-border-color: var(--bs-border-color);
            --bs-table-hover-bg: rgba(0, 212, 138, 0.05);
            --bs-table-hover-color: var(--bs-body-color);
        }

        .table thead th {
            color: var(--teal);
            font-size: 0.75rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 16px;
            border-bottom: 1px solid rgba(0, 212, 138, 0.25);
            white-space: nowrap;
        }

        .table tbody td { padding: 14px 16px; }

        .badge-status { font-weight: 600; border: 1px solid; }
        .badge-teal { background: rgba(0, 212, 138, 0.1); border-color: rgba(0, 212, 138, 0.35); color: var(--teal); }
        .badge-gold { background: rgba(245, 166, 35, 0.1); border-color: rgba(245, 166, 35, 0.4); color: var(--gold); }

        .filter-bar .form-control,
        .filter-bar .form-select,
        .filter-bar .input-group-text {
            background-color: rgba(255, 255, 255, 0.03);
            border-color: var(--bs-border-color);
        }
        .filter-bar .form-control:focus,
        .filter-bar .form-select:focus {
            border-color: var(--teal);
            box-shadow: 0 0 0 .2rem rgba(0, 212, 138, 0.15);
        }
        .btn-teal { background: var(--teal); border-color: var(--teal); color: #04100b; font-weight: 600; }
        .btn-teal:hover { background: var(--teal-2); border-color: var(--teal-2); color: #04100b; }

        .pagination {
            --bs-pagination-bg: transparent;
            --bs-pagination-border-color: var(--bs-border-color);
            --bs-pagination-color: var(--teal);
            --bs-pagination-hover-bg: rgba(0, 212, 138, 0.1);
            --bs-pagination-hover-color: var(--teal-2);
            --bs-pagination-hover-border-color: var(--bs-border-color);
            --bs-pagination-active-bg: var(--teal);
            --bs-pagination-active-border-color: var(--teal);
            --bs-pagination-active-color: #04100b;
            --bs-pagination-disabled-bg: transparent;
            --bs-pagination-disabled-border-color: var(--bs-border-color);
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

    <!-- Header / Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top navbar-glass">
        <div class="container">
            <a class="navbar-brand fw-bold fs-4" href="{{ url('/peminjam') }}">Inventaris<span class="text-gradient">Barang</span></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuNavbar" aria-label="Buka menu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="menuNavbar">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ url('/peminjam') }}">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ url('/peminjam/daftar-barang') }}">Daftar Barang</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/peminjam/riwayat') }}">Riwayat</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="flex-grow-1 container py-5">

        <div class="mb-4">
            <span class="badge rounded-pill badge-live px-3 py-2 mb-3">Daftar Barang &middot; 2026</span><br>
            <h1 class="display-6 fw-bold text-gradient d-inline-block mb-2">Daftar Barang</h1>
            <p class="text-secondary mb-0" style="max-width: 560px;">
                Cek barang yang tersedia dan yang sedang dipinjam.
            </p>
        </div>

        {{-- Pencarian + filter kategori --}}
        <form method="GET" action="{{ route('peminjam.daftar-barang') }}" class="filter-bar row g-2 mb-4">
            <div class="col-12 col-md">
                <div class="input-group">
                    <span class="input-group-text text-secondary"><i class="bi bi-search"></i></span>
                    <input type="search" name="cari" value="{{ request('cari') }}"
                           class="form-control" placeholder="Cari nama barang..." maxlength="100"
                           aria-label="Cari nama barang">
                </div>
            </div>
            <div class="col-12 col-md-4 col-lg-3">
                <select name="kategori" class="form-select" aria-label="Filter kategori" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    @foreach ($kategori as $k)
                        <option value="{{ $k->id_kategori }}" @selected((string) request('kategori') === (string) $k->id_kategori)>
                            {{ $k->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-auto d-flex gap-2">
                <button type="submit" class="btn btn-teal flex-grow-1">Cari</button>
                @if (request()->filled('cari') || request()->filled('kategori'))
                    <a href="{{ route('peminjam.daftar-barang') }}" class="btn btn-outline-secondary">Reset</a>
                @endif
            </div>
        </form>

        <div class="table-card">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width: 70px;">No</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Status Barang</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($barang as $item)
                            <tr>
                                <td>{{ $barang->firstItem() + $loop->index }}</td>
                                <td>{{ $item->nama_barang }}</td>
                                <td>{{ $item->kategori->nama_kategori ?? '-' }}</td>
                                <td>
                                    <span class="badge rounded-pill badge-status {{ $item->status_barang === 'Tersedia' ? 'badge-teal' : 'badge-gold' }}">
                                        {{ $item->status_barang }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-secondary py-5">
                                    @if (request()->filled('cari') || request()->filled('kategori'))
                                        Tidak ada barang yang cocok dengan pencarian.
                                    @else
                                        Belum ada data barang.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mt-3">
            <p class="text-secondary small mb-0">Total {{ $barang->total() }} barang.</p>
            @if ($barang->hasPages())
                {{ $barang->links('pagination::bootstrap-5') }}
            @endif
        </div>

    </main>

    <footer class="footer-dark text-center py-4 mt-auto">
        <small class="text-secondary">&copy; 2026 Inventaris Barang. Semua hak dilindungi.</small>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>