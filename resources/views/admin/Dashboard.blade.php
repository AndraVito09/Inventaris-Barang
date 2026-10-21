<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Inventaris</title>

    {{-- Terapkan tema tersimpan sebelum render agar tidak berkedip --}}
    <script>
        (function () {
            var tema = null;
            try { tema = localStorage.getItem('tema'); } catch (e) {}
            if (!tema) tema = matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'dark';
            document.documentElement.setAttribute('data-bs-theme', tema);
        })();
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,500;12..96,700;12..96,800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        /* Palet biru tua: terang */
        [data-bs-theme="light"] {
            --bs-body-bg: #eef3fb;
            --bs-body-color: #0b2447;
            --bs-secondary-color: #4a6087;
            --bs-border-color: rgba(11, 36, 71, .16);
            --kartu: #ffffff;
            --nav-bg: rgba(238, 243, 251, .92);
            --hover: rgba(11, 36, 71, .06);
            --bayangan: rgba(11, 36, 71, .14);
            /* Warna status */
            --biru: #1d4fa8;
            --hijau: #1b7f55;
            --emas-teks: #9a6204;
            --merah: #c2334d;
        }
        /* Palet biru tua: gelap */
        [data-bs-theme="dark"] {
            --bs-body-bg: #0a1930;
            --bs-body-color: #e7eefb;
            --bs-secondary-color: #9fb2d3;
            --bs-border-color: rgba(173, 198, 245, .16);
            --kartu: #102445;
            --nav-bg: rgba(10, 25, 48, .92);
            --hover: rgba(173, 198, 245, .1);
            --bayangan: rgba(0, 0, 0, .5);
            /* Warna status */
            --biru: #6ea8ff;
            --hijau: #4cc38a;
            --emas-teks: #f1b53d;
            --merah: #ff7a8e;
        }
        :root { --emas: #f1b53d; }

        html { scroll-behavior: smooth; }
        body { font-family: 'Bricolage Grotesque', system-ui, -apple-system, 'Segoe UI', sans-serif; }
        a:focus-visible, button:focus-visible { outline: 3px solid #6ea8ff; outline-offset: 3px; }

        /* Tombol */
        .btn-emas {
            --bs-btn-color: #1a1405;
            --bs-btn-bg: var(--emas);
            --bs-btn-border-color: var(--emas);
            --bs-btn-hover-color: #1a1405;
            --bs-btn-hover-bg: #f7c55c;
            --bs-btn-hover-border-color: #f7c55c;
            --bs-btn-active-color: #1a1405;
            --bs-btn-active-bg: #f7c55c;
            --bs-btn-active-border-color: #f7c55c;
            font-weight: 700;
            padding: 14px 26px;
            border-radius: 10px;
            transition: transform .2s, background .2s;
        }
        .btn-emas:hover { transform: translateY(-2px); }

        .btn-outline-secondary {
            --bs-btn-color: var(--bs-body-color);
            --bs-btn-border-color: var(--bs-border-color);
            --bs-btn-hover-color: var(--bs-body-color);
            --bs-btn-hover-bg: var(--hover);
            --bs-btn-hover-border-color: var(--bs-secondary-color);
            --bs-btn-active-color: var(--bs-body-color);
            --bs-btn-active-bg: var(--hover);
            --bs-btn-active-border-color: var(--bs-secondary-color);
        }

        .btn-tema { display: inline-flex; align-items: center; justify-content: center; gap: 8px; }
        [data-bs-theme="dark"] .ikon-bulan, [data-bs-theme="light"] .ikon-matahari { display: none; }

        /* Layout: sidebar + konten */
        .layout { display: flex; min-height: 100vh; }

        .sidebar { --bs-offcanvas-width: 280px; }

        .sidebar-inner {
            width: 100%;
            min-height: 100%;
            display: flex;
            flex-direction: column;
            padding: 24px 16px;
            background: var(--kartu);
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
            color: #1a1405;
            background: var(--emas);
            flex-shrink: 0;
        }
        .brand-teks { font-weight: 800; font-size: 1.25rem; letter-spacing: -.01em; }

        .side-label {
            font-size: .7rem;
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
            color: var(--bs-secondary-color);
            text-decoration: none;
            font-weight: 500;
            border: 1px solid transparent;
            transition: all .2s;
        }
        .side-link i { font-size: 1.1rem; }
        .side-link:hover { color: var(--bs-body-color); background: var(--hover); }
        .side-link.active {
            color: var(--bs-body-color);
            background: var(--hover);
            border-color: var(--bs-border-color);
            box-shadow: inset 3px 0 0 var(--emas);
        }

        .user-box {
            margin-top: auto;
            padding: 14px;
            border-radius: 16px;
            background: var(--bs-body-bg);
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
            color: #1a1405;
            background: var(--emas);
            flex-shrink: 0;
        }

        .topbar {
            position: sticky;
            top: 0;
            z-index: 1020;
            background: var(--nav-bg);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--bs-border-color);
        }

        .content {
            flex: 1;
            min-width: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Teks */
        .judul { font-size: clamp(1.8rem, 4.5vw, 2.8rem); font-weight: 800; letter-spacing: -.025em; line-height: 1.1; }

        .badge-live {
            background: var(--hover);
            border: 1px solid var(--bs-border-color);
            color: var(--biru);
            font-weight: 600;
        }

        .section-label {
            font-size: .75rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--bs-secondary-color);
            margin-bottom: 16px;
        }

        /* Kartu ringkasan */
        .stat-card {
            position: relative;
            overflow: hidden;
            height: 100%;
            padding: 20px;
            border-radius: 18px;
            background: var(--kartu);
            border: 1px solid var(--bs-border-color);
            transition: transform .25s, box-shadow .25s;
        }
        .stat-card::before {
            content: '';
            position: absolute;
            inset: 0 0 auto 0;
            height: 2px;
            background: var(--accent);
            pointer-events: none;
        }
        .stat-card:hover { transform: translateY(-4px); box-shadow: 0 16px 40px var(--bayangan); }

        .stat-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            background: color-mix(in srgb, var(--accent) 14%, transparent);
            color: var(--accent);
        }
        .stat-num { font-size: 2.2rem; font-weight: 800; line-height: 1; letter-spacing: -.02em; color: var(--accent); }

        .s-biru  { --accent: var(--biru); }
        .s-hijau { --accent: var(--hijau); }
        .s-emas  { --accent: var(--emas-teks); }
        .s-merah { --accent: var(--merah); }

        /* Kartu menu */
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
            background: var(--kartu);
            border: 1px solid var(--bs-border-color);
            transition: transform .25s, box-shadow .25s, border-color .25s;
        }
        /* Dekorasi tidak boleh menghalangi klik tombol */
        .action-card::after {
            content: '';
            position: absolute;
            right: -40px;
            bottom: -40px;
            width: 140px;
            height: 140px;
            border-radius: 50%;
            opacity: .08;
            background: var(--accent);
            transition: transform .4s, opacity .4s;
            pointer-events: none;
        }
        .action-card > * { position: relative; z-index: 1; }
        .action-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 50px var(--bayangan);
            border-color: color-mix(in srgb, var(--accent) 45%, transparent);
        }
        .action-card:hover::after { transform: scale(2.2); opacity: .12; }

        .action-card .btn-outline-secondary { padding: 14px 26px; border-radius: 10px; font-weight: 700; }

        .action-icon {
            width: 52px;
            height: 52px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            background: color-mix(in srgb, var(--accent) 14%, transparent);
            color: var(--accent);
        }
        .ac-biru { --accent: var(--biru); }
        .ac-emas { --accent: var(--emas-teks); }

        footer { border-top: 1px solid var(--bs-border-color); color: var(--bs-secondary-color); font-size: .9rem; }

        @media (max-width: 991.98px) {
            .sidebar-inner { min-height: 100vh; }
        }
        @media (max-width: 575.98px) {
            .action-card .btn { width: 100%; text-align: center; }
        }
        @media (prefers-reduced-motion: reduce) {
            html { scroll-behavior: auto; }
            .stat-card, .action-card, .action-card::after, .btn-emas, .side-link { transition: none; }
            .stat-card:hover, .action-card:hover, .btn-emas:hover { transform: none; }
            .action-card:hover::after { transform: none; }
        }
    </style>
</head>

<body>
    @php
        $user = auth()->user();
        $namaUser = $user->name ?? ($user->username ?? 'Admin');
    @endphp

    <div class="layout">

        <!-- Sidebar -->
        <aside class="offcanvas-lg offcanvas-start sidebar" tabindex="-1" id="sidebarMenu">

            <div class="offcanvas-header justify-content-end pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                        data-bs-target="#sidebarMenu" aria-label="Tutup"></button>
            </div>

            <div class="offcanvas-body p-0">
                <div class="sidebar-inner">

                    <a href="{{ route('dashboard') }}"
                       class="d-flex align-items-center gap-3 text-decoration-none text-reset mb-2">
                        <div class="brand-mark"><i class="bi bi-box-seam-fill"></i></div>
                        <span class="brand-teks">InventarisBarang</span>
                    </a>

                    <div class="side-label">Menu</div>

                    <nav class="nav flex-column gap-1">
                        <a class="side-link active" href="{{ route('dashboard') }}" aria-current="page">
                            <i class="bi bi-grid-1x2-fill"></i> Beranda
                        </a>
                        <a class="side-link" href="{{ route('kategori.index') }}">
                            <i class="bi bi-box-seam"></i> Data Kategori
                        </a>
                        <a class="side-link" href="{{ route('admin.riwayat') }}">
                            <i class="bi bi-clock-history"></i> Riwayat
                        </a>
                    </nav>

                    <div class="user-box">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="avatar">{{ mb_strtoupper(mb_substr($namaUser, 0, 1)) }}</div>
                            <div class="overflow-hidden">
                                <div class="fw-semibold text-truncate">{{ $namaUser }}</div>
                                <small class="text-body-secondary">Admin</small>
                            </div>
                        </div>

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-secondary btn-sm w-100">
                                <i class="bi bi-box-arrow-right me-1"></i> Logout
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </aside>

        <!-- Konten utama -->
        <div class="content">

            <header class="topbar px-3 px-md-5 py-3 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                    <button class="btn btn-outline-secondary d-lg-none" type="button"
                            data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu"
                            aria-controls="sidebarMenu" aria-label="Buka menu">
                        <i class="bi bi-list"></i>
                    </button>

                    <div>
                        <div class="fw-semibold">Panel Admin</div>
                        <div class="small text-body-secondary">Manajemen inventaris barang</div>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <div class="small text-body-secondary d-none d-sm-block">{{ now()->format('d/m/Y') }}</div>

                    <button type="button" id="btnTema" class="btn btn-outline-secondary btn-sm btn-tema"
                            aria-label="Ganti mode terang atau gelap">
                        <svg class="ikon-matahari" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg>
                        <svg class="ikon-bulan" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8z"/></svg>
                        <span id="labelTema" class="d-none d-sm-inline">Mode</span>
                    </button>
                </div>
            </header>

            <main class="flex-grow-1 px-3 px-md-5 py-4 py-md-5">
                <div class="mx-auto" style="max-width: 1100px;">

                    <!-- Header halaman -->
                    <section class="mb-5">
                        <span class="badge rounded-pill badge-live px-3 py-2 mb-3">
                            Sistem Peminjaman &middot; 2026
                        </span>

                        <h1 class="judul mb-2">Selamat Datang</h1>

                        <p class="text-body-secondary mb-0" style="max-width: 520px;">
                            Halo, {{ $namaUser }}. Kelola data barang, peminjaman,
                            dan pengembalian dalam satu tempat.
                        </p>
                    </section>

                    <!-- Ringkasan -->
                    <h2 class="section-label">Ringkasan</h2>

                    <div class="row g-3 mb-5">
                        <div class="col-6 col-xl-3">
                            <div class="stat-card s-biru">
                                <div class="stat-icon mb-3"><i class="bi bi-boxes"></i></div>
                                <div class="stat-num">{{ $totalBarang ?? 0 }}</div>
                                <small class="text-body-secondary">Total Barang</small>
                            </div>
                        </div>

                        <div class="col-6 col-xl-3">
                            <div class="stat-card s-hijau">
                                <div class="stat-icon mb-3"><i class="bi bi-check2-circle"></i></div>
                                <div class="stat-num">{{ $barangTersedia ?? 0 }}</div>
                                <small class="text-body-secondary">Tersedia</small>
                            </div>
                        </div>

                        <div class="col-6 col-xl-3">
                            <div class="stat-card s-emas">
                                <div class="stat-icon mb-3"><i class="bi bi-arrow-left-right"></i></div>
                                <div class="stat-num">{{ $sedangDipinjam ?? 0 }}</div>
                                <small class="text-body-secondary">Dipinjam</small>
                            </div>
                        </div>

                        <div class="col-6 col-xl-3">
                            <div class="stat-card s-merah">
                                <div class="stat-icon mb-3"><i class="bi bi-alarm"></i></div>
                                <div class="stat-num">{{ $terlambat ?? 0 }}</div>
                                <small class="text-body-secondary">Terlambat</small>
                            </div>
                        </div>
                    </div>

                    <!-- Menu utama -->
                    <h2 class="section-label">Menu Utama</h2>

                    <div class="row g-3">

                        <!-- Kartu Data Barang -->
                        <div class="col-md-6">
                            <div class="action-card ac-biru">
                                <div class="action-icon"><i class="bi bi-box-seam"></i></div>

                                <div>
                                    <h3 class="h5 fw-bold mb-1">Data Barang</h3>
                                    <p class="text-body-secondary small mb-0">
                                        Lihat daftar barang beserta kategori dan statusnya.
                                    </p>
                                </div>

                                <a href="{{ route('kategori.index') }}" class="btn btn-emas mt-auto">
                                    <i class="bi bi-box-arrow-up-right me-1"></i>
                                    Melihat Data Barang
                                </a>
                            </div>
                        </div>

                        <!-- Kartu Riwayat -->
                        <div class="col-md-6">
                            <div class="action-card ac-emas">
                                <div class="action-icon"><i class="bi bi-clock-history"></i></div>

                                <div>
                                    <h3 class="h5 fw-bold mb-1">Riwayat</h3>
                                    <p class="text-body-secondary small mb-0">
                                        Catatan peminjaman dan pengembalian barang.
                                    </p>
                                </div>

                                <a href="{{ route('admin.riwayat') }}" class="btn btn-outline-secondary mt-auto">
                                    <i class="bi bi-clock-history me-1"></i>
                                    Lihat Riwayat Pengembalian dan Peminjaman
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </main>

            <!-- Footer -->
            <footer class="text-center py-4 mt-auto">
                <small>&copy; 2026 Inventaris Barang. Semua hak dilindungi.</small>
            </footer>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Ganti tema terang/gelap (pilihan tersimpan di browser)
        (function () {
            const html = document.documentElement;
            const label = document.getElementById('labelTema');
            const tampilkanLabel = () => {
                label.textContent = html.getAttribute('data-bs-theme') === 'dark' ? 'Mode terang' : 'Mode gelap';
            };
            tampilkanLabel();
            document.getElementById('btnTema').addEventListener('click', () => {
                const baru = html.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
                html.setAttribute('data-bs-theme', baru);
                try { localStorage.setItem('tema', baru); } catch (e) {}
                tampilkanLabel();
            });
        })();
    </script>
</body>
</html>