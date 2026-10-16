<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Warna tema (Bootstrap tidak punya palet teal/emas bawaan) */
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

        .btn-teal {
            --bs-btn-color: #060d0a;
            --bs-btn-bg: var(--teal);
            --bs-btn-border-color: var(--teal);
            --bs-btn-hover-color: #060d0a;
            --bs-btn-hover-bg: var(--teal-2);
            --bs-btn-hover-border-color: var(--teal-2);
            --bs-btn-active-color: #060d0a;
            --bs-btn-active-bg: var(--teal-2);
            --bs-btn-active-border-color: var(--teal-2);
            font-weight: 700;
            box-shadow: 0 6px 28px rgba(0, 212, 138, 0.4);
        }

        .btn-gold {
            --bs-btn-color: var(--gold);
            --bs-btn-bg: rgba(245, 166, 35, 0.1);
            --bs-btn-border-color: rgba(245, 166, 35, 0.4);
            --bs-btn-hover-color: var(--gold);
            --bs-btn-hover-bg: rgba(245, 166, 35, 0.18);
            --bs-btn-hover-border-color: var(--gold);
            --bs-btn-active-color: var(--gold);
            --bs-btn-active-bg: rgba(245, 166, 35, 0.18);
            --bs-btn-active-border-color: var(--gold);
            font-weight: 700;
        }

        .footer-dark {
            background: #040a07;
            border-top: 2px solid;
            border-image: linear-gradient(90deg, var(--teal), var(--gold), var(--rose)) 1;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top navbar-glass">
        <div class="container">
            <a class="navbar-brand fw-bold fs-4" href="/dashboard">Inventaris<span class="text-gradient">Barang</span></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="menuNavbar">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link active" href="/dashboard">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Data Barang</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Riwayat</a></li>
                </ul>

                <span class="text-secondary me-3 small">Halo, {{ auth()->user()->name }}</span>

                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-gold btn-sm rounded-pill px-4">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Header / isi halaman -->
    <main class="container flex-grow-1 d-flex flex-column align-items-center justify-content-center text-center py-5">
        <span class="badge rounded-pill badge-live px-3 py-2 mb-4">Sistem Peminjaman &middot; 2026</span>

        <h1 class="display-3 fw-bold mb-3 text-gradient">Selamat Datang</h1>
        <p class="lead text-secondary mb-5" style="max-width: 500px;">
            Kelola data barang, peminjaman, dan pengembalian dalam satu tempat.
        </p>

        <!-- Kartu ringkasan (angka dari DashboardController) -->
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

        <div class="d-grid gap-3 d-sm-flex justify-content-center">
            <button type="button" class="btn btn-teal btn-lg rounded-pill px-5">Melihat Data Barang</button>
            <button type="button" class="btn btn-gold btn-lg rounded-pill px-5">Lihat Riwayat Pengembalian dan Peminjaman</button>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer-dark text-center py-4 mt-auto">
        <small class="text-secondary">&copy; 2026 Inventaris Barang. Semua hak dilindungi.</small>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>