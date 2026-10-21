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

        .card { background: rgba(255, 255, 255, 0.02); border-color: var(--bs-border-color); }

        /* Kartu statistik: garis warna di sisi kiri */
        .kartu-statistik { border-left: 4px solid var(--aksen, var(--teal)); }

        /* Kartu menu: warna aksen diatur lewat --aksen */
        .kartu-menu {
            display: block;
            color: inherit;
            text-decoration: none;
            border-top: 4px solid var(--aksen, var(--teal));
            transition: transform .15s, background .15s;
        }
        .kartu-menu:hover, .kartu-menu:focus-visible {
            color: inherit;
            background: rgba(255, 255, 255, 0.05);
            transform: translateY(-4px);
        }
        .kartu-menu .judul { color: var(--aksen, var(--teal)); }
        .kartu-menu .nomor {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2.25rem;
            height: 2.25rem;
            border: 1.5px solid var(--aksen, var(--teal));
            border-radius: 50%;
            color: var(--aksen, var(--teal));
            font-weight: 700;
        }

        .catatan-terlambat {
            border: 1.5px solid rgba(245, 166, 35, 0.4);
            background: rgba(245, 166, 35, 0.08);
        }

        .footer-dark {
            background: #040a07;
            border-top: 2px solid;
            border-image: linear-gradient(90deg, var(--teal), var(--gold), var(--rose)) 1;
        }

        @media (prefers-reduced-motion: reduce) { .kartu-menu { transition: none; } }
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
                    <li class="nav-item"><a class="nav-link" href="{{ url('/peminjam/daftar-barang') }}">Daftar Barang</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/peminjam/riwayat') }}">Riwayat</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Isi dashboard -->
    <main class="container flex-grow-1 py-5">

        <!-- Bagian atas: sambutan (kiri) dan statistik (kanan) -->
        <section class="row align-items-center g-4 mb-5">
            <div class="col-lg-6">
                <span class="badge rounded-pill badge-live px-3 py-2 mb-4">Dashboard Peminjam &middot; 2026</span>
                <h1 class="display-4 fw-bold mb-3 text-gradient">Selamat Datang</h1>
                <p class="lead text-secondary mb-0">
                    Pinjam dan kembalikan barang sekolah dalam satu tempat, tanpa perlu login.
                </p>
            </div>

            <div class="col-lg-6">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="card kartu-statistik p-3" style="--aksen: var(--teal-2);">
                            <small class="text-secondary">Total Barang</small>
                            <div class="fs-1 fw-bold">{{ $totalBarang ?? 0 }}</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card kartu-statistik p-3" style="--aksen: var(--teal);">
                            <small class="text-secondary">Tersedia</small>
                            <div class="fs-1 fw-bold" style="color: var(--teal);">{{ $barangTersedia ?? 0 }}</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card kartu-statistik p-3" style="--aksen: var(--gold);">
                            <small class="text-secondary">Dipinjam</small>
                            <div class="fs-1 fw-bold" style="color: var(--gold);">{{ $sedangDipinjam ?? 0 }}</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card kartu-statistik p-3" style="--aksen: var(--rose);">
                            <small class="text-secondary">Terlambat</small>
                            <div class="fs-1 fw-bold" style="color: var(--rose);">{{ $terlambat ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Menu utama -->
        <h2 class="h5 text-secondary mb-3">Pilih layanan</h2>
        <section class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-3">
            <div class="col">
                <a href="{{ url('/peminjam/pinjam') }}" class="card kartu-menu h-100 p-4" style="--aksen: var(--teal);">
                    <span class="nomor mb-3">1</span>
                    <h3 class="judul h5 fw-bold">Peminjaman</h3>
                    <p class="text-secondary small mb-0">Scan barcode barang, isi data, lalu dapatkan struk.</p>
                </a>
            </div>
            <div class="col">
                <a href="{{ url('/peminjam/kembali') }}" class="card kartu-menu h-100 p-4" style="--aksen: var(--rose);">
                    <span class="nomor mb-3">2</span>
                    <h3 class="judul h5 fw-bold">Pengembalian</h3>
                    <p class="text-secondary small mb-0">Scan QR pada struk untuk mengembalikan barang.</p>
                </a>
            </div>
            <div class="col">
                <a href="{{ url('/peminjam/daftar-barang') }}" class="card kartu-menu h-100 p-4" style="--aksen: var(--teal-2);">
                    <span class="nomor mb-3">3</span>
                    <h3 class="judul h5 fw-bold">Lihat Daftar Barang</h3>
                    <p class="text-secondary small mb-0">Cek barang yang tersedia dan yang sedang dipinjam.</p>
                </a>
            </div>
            <div class="col">
                <a href="{{ url('/peminjam/riwayat') }}" class="card kartu-menu h-100 p-4" style="--aksen: var(--gold);">
                    <span class="nomor mb-3">4</span>
                    <h3 class="judul h5 fw-bold">Lihat Riwayat Peminjaman dan Pengembalian</h3>
                    <p class="text-secondary small mb-0">Pantau transaksi yang berjalan dan yang sudah selesai.</p>
                </a>
            </div>
        </section>

        <!-- Ketentuan keterlambatan -->
        <div class="card catatan-terlambat p-3 mt-4">
            <div class="fw-semibold mb-1" style="color: var(--gold);">Ketentuan keterlambatan</div>
            <small class="text-secondary">
                Batas pengembalian tertera di struk. Siswa yang terlambat mengembalikan barang wajib membersihkan lab.
            </small>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer-dark text-center py-4 mt-auto">
        <small class="text-secondary">&copy; 2026 Inventaris Barang. Semua hak dilindungi.</small>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>