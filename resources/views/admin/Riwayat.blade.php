<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Peminjaman</title>

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

        footer { border-top: 1px solid var(--bs-border-color); color: var(--bs-secondary-color); font-size: .9rem; }

        /* Isi halaman */
        .judul { font-size: clamp(1.6rem, 4vw, 2.2rem); font-weight: 800; letter-spacing: -.02em; line-height: 1.1; }

        .panel {
            border-radius: 18px;
            overflow: hidden;
            background: var(--kartu);
            border: 1px solid var(--bs-border-color);
        }

        .table {
            --bs-table-bg: transparent;
            --bs-table-color: var(--bs-body-color);
            --bs-table-border-color: var(--bs-border-color);
            --bs-table-hover-bg: var(--hover);
            --bs-table-hover-color: var(--bs-body-color);
            margin-bottom: 0;
            vertical-align: middle;
        }
        .table thead th {
            padding: 16px;
            color: var(--bs-secondary-color);
            font-size: .75rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            white-space: nowrap;
            border-bottom: 1px solid var(--bs-border-color);
        }
        .table tbody td { padding: 14px 16px; }

        /* Badge status */
        .badge-status { font-weight: 600; border: 1px solid; }
        .b-hijau { color: var(--hijau);
                   background: color-mix(in srgb, var(--hijau) 12%, transparent);
                   border-color: color-mix(in srgb, var(--hijau) 40%, transparent); }
        .b-emas  { color: var(--emas-teks);
                   background: color-mix(in srgb, var(--emas-teks) 12%, transparent);
                   border-color: color-mix(in srgb, var(--emas-teks) 40%, transparent); }
        .b-merah { color: var(--merah);
                   background: color-mix(in srgb, var(--merah) 12%, transparent);
                   border-color: color-mix(in srgb, var(--merah) 40%, transparent); }

        .print-only { display: none; }

        @media (max-width: 991.98px) {
            .sidebar-inner { min-height: 100vh; }
        }
        @media (prefers-reduced-motion: reduce) {
            html { scroll-behavior: auto; }
            .btn-emas, .side-link { transition: none; }
            .btn-emas:hover { transform: none; }
        }

        /* ---------- Tampilan saat dicetak / disimpan sebagai PDF ---------- */
        @page { size: A4 portrait; margin: 12mm; }

        @media print {
            * { -webkit-print-color-adjust: exact; print-color-adjust: exact; }

            [data-bs-theme="dark"], [data-bs-theme="light"] {
                --bs-body-bg: #fff;
                --bs-body-color: #000;
                --bs-secondary-color: #444;
            }

            body { background: #fff !important; color: #000 !important; }

            .sidebar, .topbar, .no-print, .alert, footer { display: none !important; }

            .layout { display: block; }
            .content { min-height: 0; }
            .area { padding: 0 !important; }

            .print-only { display: block; margin: 0 0 12px; color: #444; font-size: 12px; }

            .panel { background: #fff; border: 0; border-radius: 0; overflow: visible; }

            .table th, .table td {
                padding: 7px 8px !important;
                color: #000 !important;
                background: #fff !important;
                border: 1px solid #999 !important;
                font-size: 12px;
            }
            .table thead th { background: #e4eaf5 !important; letter-spacing: 0; text-transform: none; }

            .badge-status { color: #000 !important; background: #fff !important; border: 1px solid #666 !important; }

            thead { display: table-header-group; }
            tr { break-inside: avoid; }
        }
    </style>
</head>

<body>
    @php
        $user = auth()->user();
        $namaUser = $user->name ?: ($user->username ?? 'Admin');
    @endphp

    <div class="layout">

        {{-- Sidebar --}}
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
                        <a class="side-link" href="{{ route('dashboard') }}">
                            <i class="bi bi-grid-1x2-fill"></i> Beranda
                        </a>
                        <a class="side-link" href="{{ route('kategori.index') }}">
                            <i class="bi bi-box-seam"></i> Data Kategori
                        </a>
                        <a class="side-link active" href="{{ route('admin.riwayat') }}" aria-current="page">
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

        {{-- Konten utama --}}
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

            <main class="area flex-grow-1 px-3 px-md-5 py-4 py-md-5">
                <div class="mx-auto" style="max-width: 1100px;">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                        <div>
                            <h1 class="judul mb-1">Riwayat Peminjaman</h1>
                            <p class="text-body-secondary mb-0">
                                Catatan transaksi peminjaman dan pengembalian barang.
                            </p>
                        </div>

                        <div class="d-flex gap-2 flex-wrap no-print">
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary py-2 px-3">
                                <i class="bi bi-arrow-left me-1"></i> Kembali
                            </a>

                            <button type="button" class="btn btn-emas py-2 px-3" onclick="window.print()">
                                <i class="bi bi-printer me-1"></i> Cetak / Simpan PDF
                            </button>
                        </div>
                    </div>

                    <p class="print-only">Dicetak pada {{ now()->format('d/m/Y H:i') }}</p>

                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <section class="panel">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Peminjam</th>
                                        <th>Barang</th>
                                        <th>Tanggal Pinjam</th>
                                        <th>Tanggal Kembali</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse ($riwayat as $item)
                                        @php
                                            if ($item->tgl_kembali) {
                                                $status = 'Selesai';
                                                $kelas  = 'b-hijau';
                                            } elseif ($item->tgl_jatuh_tempo && $item->tgl_jatuh_tempo->isPast()) {
                                                $status = 'Terlambat';
                                                $kelas  = 'b-merah';
                                            } else {
                                                $status = 'Dipinjam';
                                                $kelas  = 'b-emas';
                                            }
                                        @endphp
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->peminjam->nama_peminjam ?? '-' }}</td>
                                            <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                                            <td>{{ $item->tgl_pinjam?->format('d/m/Y H:i') ?? '-' }}</td>
                                            <td>{{ $item->tgl_kembali?->format('d/m/Y H:i') ?? '-' }}</td>
                                            <td>
                                                <span class="badge rounded-pill badge-status {{ $kelas }}">{{ $status }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-body-secondary py-5">
                                                Belum ada riwayat peminjaman.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </section>

                    <p class="text-body-secondary small mt-3 mb-0 no-print">Total {{ $riwayat->count() }} transaksi.</p>

                </div>
            </main>

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