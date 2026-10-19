<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Peminjam - Inventaris Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
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

        .card { background: rgba(255, 255, 255, 0.02); border-color: var(--bs-border-color); }

        /* Tombol menu: warna aksen diatur lewat --accent */
        .btn-menu {
            --accent: var(--teal);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 88px;
            padding: 1rem 1.5rem;
            font-weight: 700;
            font-size: 1.15rem;
            color: var(--accent);
            background: rgba(255, 255, 255, 0.02);
            border: 1.5px solid var(--accent);
            border-radius: 1rem;
            text-decoration: none;
            transition: background .15s, color .15s;
        }
        .btn-menu:hover, .btn-menu:focus-visible { background: var(--accent); color: #060d0a; }
        .btn-menu.primary { background: var(--teal); color: #060d0a; box-shadow: 0 6px 28px rgba(0, 212, 138, 0.35); }
        .btn-menu.primary:hover, .btn-menu.primary:focus-visible { background: var(--teal-2); border-color: var(--teal-2); }

        .footer-dark {
            background: #040a07;
            border-top: 2px solid;
            border-image: linear-gradient(90deg, var(--teal), var(--gold), var(--rose)) 1;
        }

        @media (prefers-reduced-motion: reduce) { .btn-menu { transition: none; } }
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
                    <li class="nav-item"><a class="nav-link active" href="{{ url('/peminjam') }}">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/peminjam/katalog') }}">Daftar Barang</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/peminjam/riwayat') }}">Riwayat</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Isi dashboard -->
    <main class="container flex-grow-1 d-flex flex-column align-items-center justify-content-center text-center py-5">
        <span class="badge rounded-pill badge-live px-3 py-2 mb-4">Dashboard Peminjam &middot; 2026</span>

        <h1 class="display-3 fw-bold mb-3 text-gradient">Selamat Datang</h1>
        <p class="lead text-secondary mb-5" style="max-width: 500px;">
            Pinjam dan kembalikan barang sekolah tanpa login.
        </p>

        <!-- Kartu ringkasan (angka dari PeminjamController; tampil 0 jika belum dikirim) -->
        <div class="row g-3 mb-5 w-100" style="max-width: 800px;">
            <div class="col-6 col-md-3">
                <div class="card text-center p-3">
                    <div class="fs-2 fw-bold">{{ $totalBarang ?? 0 }}</div>
                    <small class="text-secondary">Total Barang</small>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card text-center p-3">
                    <div class="fs-2 fw-bold" style="color: var(--teal);">{{ $barangTersedia ?? 0 }}</div>
                    <small class="text-secondary">Tersedia</small>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card text-center p-3">
                    <div class="fs-2 fw-bold" style="color: var(--gold);">{{ $sedangDipinjam ?? 0 }}</div>
                    <small class="text-secondary">Dipinjam</small>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card text-center p-3">
                    <div class="fs-2 fw-bold" style="color: var(--rose);">{{ $terlambat ?? 0 }}</div>
                    <small class="text-secondary">Terlambat</small>
                </div>
            </div>
        </div>

        <!-- Menu utama -->
        <div class="row g-3 w-100" style="max-width: 720px;">
            <div class="col-12 col-md-6">
                <a href="{{ url('/peminjam/pinjam') }}" class="btn-menu primary">Peminjaman</a>
            </div>
            <div class="col-12 col-md-6">
                <a href="{{ url('/peminjam/kembali') }}" class="btn-menu" style="--accent: var(--rose);">Pengembalian</a>
            </div>
            <div class="col-12 col-md-6">
                <a href="{{ url('/peminjam/katalog') }}" class="btn-menu" style="--accent: var(--teal);">Lihat Daftar Barang</a>
            </div>
            <div class="col-12 col-md-6">
                <a href="{{ url('/peminjam/riwayat') }}" class="btn-menu" style="--accent: var(--gold);">Lihat Riwayat Peminjaman dan Pengembalian</a>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer-dark text-center py-4 mt-auto">
        <small class="text-secondary">&copy; 2026 Inventaris Barang. Semua hak dilindungi.</small>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>