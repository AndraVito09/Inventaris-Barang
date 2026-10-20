<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        /* Warna tema (sama seperti sebelumnya) */
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

        /* ===== Layout: sidebar + konten ===== */
        .layout { display: flex; min-height: 100vh; }

        .sidebar { --bs-offcanvas-width: 280px; }

        .sidebar-inner {
            width: 100%;
            min-height: 100%;
            display: flex;
            flex-direction: column;
            padding: 24px 16px;
            background: rgba(6, 13, 10, 0.95);
        }

        @media (min-width: 992px) {
            .sidebar {
                flex: 0 0 270px;
                position: sticky;
                top: 0;
                height: 100vh;
                border-right: 1px solid var(--bs-border-color);
            }
            .sidebar-inner {
                width: 270px;
                height: 100vh;
                overflow-y: auto;
            }
        }

        .brand-mark {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            color: #060d0a;
            background: linear-gradient(135deg, var(--teal), var(--teal-2));
            box-shadow: 0 0 20px rgba(0, 212, 138, 0.35);
        }

        .side-label {
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--bs-secondary-color);
            padding: 0 14px;
            margin: 28px 0 10px;
        }

        .side-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 14px;
            border-radius: 12px;
            color: rgba(232, 245, 239, 0.65);
            text-decoration: none;
            font-weight: 500;
            border: 1px solid transparent;
            transition: all 0.2s;
        }
        .side-link i { font-size: 1.1rem; }
        .side-link:hover {
            color: var(--bs-body-color);
            background: rgba(0, 212, 138, 0.06);
        }
        .side-link.active {
            color: var(--teal);
            background: rgba(0, 212, 138, 0.1);
            border-color: rgba(0, 212, 138, 0.25);
        }

        .user-box {
            margin-top: auto;
            padding: 14px;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--bs-border-color);
        }
        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            color: #060d0a;
            background: linear-gradient(135deg, var(--teal), var(--gold));
            flex-shrink: 0;
        }

        .topbar {
            background: rgba(6, 13, 10, 0.85);
            backdrop-filter: blur(24px);
            border-bottom: 1px solid var(--bs-border-color);
            z-index: 1020;
        }

        .content {
            flex: 1;
            min-width: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background:
                radial-gradient(ellipse 600px 400px at 10% 0%, rgba(0, 212, 138, 0.07), transparent 70%),
                radial-gradient(ellipse 500px 400px at 100% 100%, rgba(245, 166, 35, 0.04), transparent 70%);
        }

        .section-label {
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--bs-secondary-color);
            margin-bottom: 16px;
        }

        /* ===== Kartu statistik ===== */
        .stat-card {
            position: relative;
            overflow: hidden;
            height: 100%;
            padding: 20px;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--bs-border-color);
            transition: transform 0.25s, box-shadow 0.25s;
        }
        .stat-card::before {
            content: '';
            position: absolute;
            inset: 0 0 auto 0;
            height: 2px;
            background: var(--line);
        }
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.5);
        }
        .stat-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            background: var(--tint);
            color: var(--accent);
        }
        .stat-num {
            font-size: 2.2rem;
            font-weight: 800;
            line-height: 1;
            color: var(--accent);
        }
        .s-total { --line: linear-gradient(90deg, var(--teal), var(--gold), var(--rose)); --tint: rgba(232, 245, 239, 0.08); --accent: var(--bs-body-color); }
        .s-teal  { --line: linear-gradient(90deg, var(--teal), var(--teal-2)); --tint: rgba(0, 212, 138, 0.1); --accent: var(--teal); }
        .s-gold  { --line: var(--gold); --tint: rgba(245, 166, 35, 0.1); --accent: var(--gold); }
        .s-rose  { --line: var(--rose); --tint: rgba(240, 90, 126, 0.1); --accent: var(--rose); }

        /* ===== Kartu menu ===== */
        .action-card {
            position: relative;
            overflow: hidden;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 14px;
            padding: 28px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--bs-border-color);
            transition: transform 0.25s, box-shadow 0.25s, border-color 0.25s;
        }
        .action-card::after {
            content: '';
            position: absolute;
            right: -40px;
            bottom: -40px;
            width: 140px;
            height: 140px;
            border-radius: 50%;
            opacity: 0.07;
            background: var(--glow);
            transition: transform 0.4s, opacity 0.4s;
        }
        .action-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.6);
        }
        .action-card:hover::after { transform: scale(2.2); opacity: 0.1; }
        .action-icon {
            width: 52px;
            height: 52px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            background: var(--tint);
            color: var(--accent);
        }
        .ac-teal { --glow: var(--teal); --tint: rgba(0, 212, 138, 0.1); --accent: var(--teal); }
        .ac-gold { --glow: var(--gold); --tint: rgba(245, 166, 35, 0.1); --accent: var(--gold); }
        .ac-teal:hover { border-color: rgba(0, 212, 138, 0.35); }
        .ac-gold:hover { border-color: rgba(245, 166, 35, 0.4); }
    </style>
</head>
<body>

    @php
        $user = auth()->user();
        $namaUser = $user->name ?: ($user->username ?? 'Admin');
    @endphp

    <!-- Topbar (hanya tampil di layar kecil) -->
    <header class="topbar d-lg-none sticky-top d-flex align-items-center gap-3 px-3 py-2">
        <button class="btn btn-gold btn-sm rounded-pill" type="button"
                data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu">
            <i class="bi bi-list"></i>
        </button>
        <span class="fw-bold fs-5">Inventaris<span class="text-gradient">Barang</span></span>
    </header>

    <div class="layout">

        <!-- Sidebar -->
        <aside class="offcanvas-lg offcanvas-start sidebar" tabindex="-1" id="sidebarMenu">
            <div class="offcanvas-header justify-content-end pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                        data-bs-target="#sidebarMenu" aria-label="Tutup"></button>
            </div>

            <div class="offcanvas-body p-0">
                <div class="sidebar-inner">

                    <a href="/dashboard" class="d-flex align-items-center gap-3 text-decoration-none text-reset mb-2">
                        <div class="brand-mark"><i class="bi bi-box-seam-fill"></i></div>
                        <span class="fw-bold fs-5">Inventaris<span class="text-gradient">Barang</span></span>
                    </a>

                    <div class="side-label">Menu</div>
                    <nav class="nav flex-column gap-1">
                        <a class="side-link active" href="/dashboard"><i class="bi bi-grid-1x2-fill"></i> Beranda</a>
                        <a class="side-link" href="#"><i class="bi bi-box-seam"></i> Data Barang</a>
                        <a class="side-link" href="#"><i class="bi bi-clock-history"></i> Riwayat</a>
                    </nav>

                    <div class="user-box">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="avatar">{{ mb_strtoupper(mb_substr($namaUser, 0, 1)) }}</div>
                            <div class="overflow-hidden">
                                <div class="fw-semibold text-truncate">{{ $namaUser }}</div>
                                <small class="text-secondary">Admin</small>
                            </div>
                        </div>

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-gold btn-sm rounded-pill w-100">
                                <i class="bi bi-box-arrow-right me-1"></i> Logout
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </aside>

        <!-- Konten -->
        <div class="content">
            <main class="flex-grow-1 px-3 px-md-5 py-4 py-md-5">
                <div class="mx-auto" style="max-width: 1100px;">

                    <!-- Header halaman -->
                    <section class="mb-5">
                        <span class="badge rounded-pill badge-live px-3 py-2 mb-3">Sistem Peminjaman &middot; 2026</span>
                        <h1 class="display-5 fw-bold text-gradient d-inline-block d-block mb-2">Selamat Datang</h1>
                        <p class="text-secondary mb-0" style="max-width: 520px;">
                            Halo, {{ $namaUser }}. Kelola data barang, peminjaman, dan pengembalian dalam satu tempat.
                        </p>
                    </section>

                    <!-- Ringkasan (angka dari DashboardController) -->
                    <h2 class="section-label">Ringkasan</h2>
                    <div class="row g-3 mb-5">
                        <div class="col-6 col-xl-3">
                            <div class="stat-card s-total">
                                <div class="stat-icon mb-3"><i class="bi bi-boxes"></i></div>
                                <div class="stat-num">{{ $totalBarang ?? 0 }}</div>
                                <small class="text-secondary">Total Barang</small>
                            </div>
                        </div>
                        <div class="col-6 col-xl-3">
                            <div class="stat-card s-teal">
                                <div class="stat-icon mb-3"><i class="bi bi-check2-circle"></i></div>
                                <div class="stat-num">{{ $barangTersedia ?? 0 }}</div>
                                <small class="text-secondary">Tersedia</small>
                            </div>
                        </div>
                        <div class="col-6 col-xl-3">
                            <div class="stat-card s-gold">
                                <div class="stat-icon mb-3"><i class="bi bi-arrow-left-right"></i></div>
                                <div class="stat-num">{{ $sedangDipinjam ?? 0 }}</div>
                                <small class="text-secondary">Dipinjam</small>
                            </div>
                        </div>
                        <div class="col-6 col-xl-3">
                            <div class="stat-card s-rose">
                                <div class="stat-icon mb-3"><i class="bi bi-alarm"></i></div>
                                <div class="stat-num">{{ $terlambat ?? 0 }}</div>
                                <small class="text-secondary">Terlambat</small>
                            </div>
                        </div>
                    </div>

                    <!-- Menu utama -->
                    <h2 class="section-label">Menu Utama</h2>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="action-card ac-teal">
                                <div class="action-icon"><i class="bi bi-box-seam"></i></div>
                                <div>
                                    <h3 class="h5 fw-bold mb-1">Data Barang</h3>
                                    <p class="text-secondary small mb-0">Lihat daftar barang beserta kategori dan statusnya.</p>
                                </div>
                                <button type="button" class="btn btn-teal rounded-pill px-4 mt-auto">Melihat Data Barang</button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="action-card ac-gold">
                                <div class="action-icon"><i class="bi bi-clock-history"></i></div>
                                <div>
                                    <h3 class="h5 fw-bold mb-1">Riwayat</h3>
                                    <p class="text-secondary small mb-0">Catatan peminjaman dan pengembalian barang.</p>
                                </div>
                                <button type="button" class="btn btn-gold rounded-pill px-4 mt-auto">Lihat Riwayat Pengembalian dan Peminjaman</button>
                            </div>
                        </div>
                    </div>

                </div>
            </main>

            <!-- Footer -->
            <footer class="footer-dark text-center py-4 mt-auto">
                <small class="text-secondary">&copy; 2026 Inventaris Barang. Semua hak dilindungi.</small>
            </footer>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>