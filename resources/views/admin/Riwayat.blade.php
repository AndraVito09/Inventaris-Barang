<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Riwayat Peminjaman - Inventaris Barang</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}">

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
            --biru: #1d4fa8;
            --ada: #0f9d7a;
            --telat: #d6336c;
            --emas-teks: #b97d00;
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
            --biru: #6ea8ff;
            --ada: #4fd1ac;
            --telat: #f0708f;
            --emas-teks: #f1b53d;
        }
        :root { --emas: #f1b53d; }

        html { -webkit-text-size-adjust: 100%; }
        body {
            font-family: 'Bricolage Grotesque', system-ui, -apple-system, 'Segoe UI', sans-serif;
            overflow-x: hidden;
        }
        a:focus-visible, button:focus-visible { outline: 3px solid #6ea8ff; outline-offset: 3px; }

        /* Tombol */
        .btn-outline-secondary {
            --bs-btn-color: var(--bs-body-color); --bs-btn-border-color: var(--bs-border-color);
            --bs-btn-hover-color: var(--bs-body-color); --bs-btn-hover-bg: var(--hover); --bs-btn-hover-border-color: var(--bs-secondary-color);
            --bs-btn-active-color: var(--bs-body-color); --bs-btn-active-bg: var(--hover); --bs-btn-active-border-color: var(--bs-secondary-color);
            border-radius: 10px;
        }
        .btn-emas {
            --bs-btn-color: #1a1405; --bs-btn-bg: var(--emas); --bs-btn-border-color: var(--emas);
            --bs-btn-hover-color: #1a1405; --bs-btn-hover-bg: #f7c55c; --bs-btn-hover-border-color: #f7c55c;
            --bs-btn-active-color: #1a1405; --bs-btn-active-bg: #f7c55c; --bs-btn-active-border-color: #f7c55c;
            border-radius: 10px; padding: 11px 20px; min-height: 44px; font-weight: 700;
            transition: transform .2s, background .2s;
        }
        .btn-emas:hover { transform: translateY(-2px); }
        .btn-tema { display: inline-flex; align-items: center; justify-content: center; gap: 8px; min-height: 40px; }
        [data-bs-theme="dark"] .ikon-bulan, [data-bs-theme="light"] .ikon-matahari { display: none; }

        /* ====== Layout: sidebar + konten ====== */
        .layout { display: flex; min-height: 100vh; }
        .sidebar { --bs-offcanvas-width: min(280px, 85vw); background: var(--kartu); }
        .sidebar-inner { width: 100%; min-height: 100%; display: flex; flex-direction: column; padding: 20px 16px; background: var(--kartu); }

        @media (min-width: 992px) {
            .sidebar { flex: 0 0 270px; position: sticky; top: 0; height: 100vh; border-right: 1px solid var(--bs-border-color); }
            .sidebar-inner { width: 270px; height: 100vh; overflow-y: auto; }
        }

        .logo { width: 56px; height: 56px; border-radius: 10px; object-fit: contain; flex-shrink: 0; }
        .brand-teks { font-weight: 800; font-size: 1.35rem; letter-spacing: -.01em; color: var(--bs-body-color); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .side-judul { font-size: .85rem; font-weight: 700; color: var(--bs-secondary-color); padding: 0 14px; margin: 24px 0 8px; }

        .side-link {
            display: flex; align-items: center; gap: 12px;
            padding: 11px 14px; min-height: 44px; border-radius: 10px;
            color: var(--bs-secondary-color); text-decoration: none; font-weight: 500;
            border: 1px solid transparent; transition: background .2s, color .2s;
        }
        .side-link i { font-size: 1.1rem; }
        .side-link:hover { color: var(--bs-body-color); background: var(--hover); }
        .side-link.active { color: var(--bs-body-color); background: var(--hover); border-color: var(--bs-border-color); box-shadow: inset 3px 0 0 var(--emas); }
        .sidebar-inner nav { margin-bottom: 24px; }

        .user-box { margin-top: auto; padding: 14px; border-radius: 14px; background: var(--bs-body-bg); border: 1px solid var(--bs-border-color); }
        .avatar { width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; color: #1a1405; background: var(--emas); flex-shrink: 0; }

        .topbar { position: sticky; top: 0; z-index: 1020; background: var(--nav-bg); backdrop-filter: blur(10px); border-bottom: 1px solid var(--bs-border-color); padding-top: max(.75rem, env(safe-area-inset-top)); }
        .topbar .btn-menu { min-width: 44px; min-height: 44px; }
        .content { flex: 1; min-width: 0; min-height: 100vh; display: flex; flex-direction: column; }

        /* ====== Isi halaman ====== */
        h1 { font-size: clamp(1.75rem, 6vw, 3.2rem); font-weight: 800; letter-spacing: -.025em; line-height: 1.08; overflow-wrap: break-word; }
        h2 { font-size: clamp(1.25rem, 3.5vw, 1.5rem); font-weight: 800; letter-spacing: -.015em; }
        .lead { max-width: 36rem; color: var(--bs-secondary-color); font-size: clamp(1rem, 2.2vw, 1.15rem); }

        .papan { background: var(--kartu); border: 1px solid var(--bs-border-color); border-radius: 16px; overflow: hidden; }
        .papan-kepala { padding: 20px 24px; border-bottom: 1px solid var(--bs-border-color); }

        .ikon-kotak { width: 44px; height: 44px; flex-shrink: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 12px; font-size: 1.25rem; background: color-mix(in srgb, var(--biru) 14%, transparent); color: var(--biru); }
        .badge-jumlah { background: var(--hover); color: var(--bs-body-color); border: 1px solid var(--bs-border-color); font-weight: 600; }

        /* Tabel */
        .tabel-data { margin-bottom: 0; vertical-align: middle; --bs-table-bg: transparent; --bs-table-color: var(--bs-body-color); --bs-table-border-color: var(--bs-border-color); --bs-table-hover-bg: var(--hover); --bs-table-hover-color: var(--bs-body-color); }
        .tabel-data thead th { padding: 14px 16px; color: var(--bs-secondary-color); background: var(--hover); font-size: .85rem; font-weight: 700; white-space: nowrap; border-bottom: 1px solid var(--bs-border-color); }
        .tabel-data tbody td { padding: 16px; border-color: var(--bs-border-color); }
        .nomor { display: inline-flex; width: 34px; height: 34px; align-items: center; justify-content: center; border-radius: 10px; background: var(--hover); font-weight: 700; font-size: .85rem; font-variant-numeric: tabular-nums; }

        .badge-status { font-weight: 600; border: 1px solid; padding: .45em .85em; }
        .b-hijau { color: var(--ada); background: color-mix(in srgb, var(--ada) 12%, transparent); border-color: color-mix(in srgb, var(--ada) 40%, transparent); }
        .b-emas { color: var(--emas-teks); background: color-mix(in srgb, var(--emas-teks) 12%, transparent); border-color: color-mix(in srgb, var(--emas-teks) 40%, transparent); }
        .b-merah { color: var(--telat); background: color-mix(in srgb, var(--telat) 12%, transparent); border-color: color-mix(in srgb, var(--telat) 40%, transparent); }

        .col-aksi { white-space: nowrap; }

        .kosong { padding: 48px 20px !important; color: var(--bs-secondary-color); }
        .kosong i { display: block; font-size: 2.5rem; margin-bottom: 12px; opacity: .65; }
        .alert { border-radius: 12px; }
        .print-only { display: none; }

        footer { border-top: 1px solid var(--bs-border-color); color: var(--bs-secondary-color); font-size: .9rem; padding-bottom: max(1rem, env(safe-area-inset-bottom)); }
        footer a { color: var(--bs-secondary-color); display: inline-block; padding: .25rem 0; }
        footer a:hover { color: var(--bs-body-color); }

        /* ====== Responsif ====== */
        @media (max-width: 991.98px) {
            .sidebar-inner { min-height: 100vh; }
            .papan-kepala { padding: 20px; }
        }

        /* HP: tabel jadi daftar kartu */
        @media screen and (max-width: 767.98px) {
            .papan { border-radius: 14px; }
            .papan-kepala { padding: 16px; }
            .topbar { padding-left: max(1rem, env(safe-area-inset-left)) !important; padding-right: max(1rem, env(safe-area-inset-right)) !important; }

            .aksi-halaman { width: 100%; }
            .aksi-halaman .btn { flex: 1 1 0; }

            .tabel-data thead { display: none; }
            .tabel-data, .tabel-data tbody { display: block; width: 100%; }

            .tabel-data tbody tr:not(.baris-kosong) {
                display: grid;
                grid-template-columns: 1fr auto;
                grid-template-areas: "peminjam status" "barang barang" "pinjam pinjam" "kembali kembali" "aksi aksi";
                column-gap: 12px; row-gap: 4px;
                padding: 16px;
                border-bottom: 1px solid var(--bs-border-color);
            }
            .tabel-data tbody tr:not(.baris-kosong):last-child { border-bottom: 0; }
            .tabel-data tbody tr:not(.baris-kosong) td { display: block; padding: 0; border: 0; }

            .col-no { display: none !important; }
            .col-peminjam { grid-area: peminjam; font-weight: 700; min-width: 0; overflow-wrap: anywhere; }
            .col-status { grid-area: status; align-self: start; }
            .col-barang { grid-area: barang; color: var(--bs-secondary-color); overflow-wrap: anywhere; }
            .col-pinjam { grid-area: pinjam; font-size: .9rem; margin-top: 8px; }
            .col-kembali { grid-area: kembali; font-size: .9rem; }
            .col-pinjam::before, .col-kembali::before { content: attr(data-label) ": "; color: var(--bs-secondary-color); }
            .col-aksi { grid-area: aksi; margin-top: 12px; }
            .col-aksi .btn { width: 100%; min-height: 44px; }

            .tabel-data tbody tr.baris-kosong { display: table-row; }
            .tabel-data tbody tr.baris-kosong td { display: table-cell; }
        }

        @media (max-width: 399.98px) {
            .logo { width: 44px; height: 44px; }
            .brand-teks { font-size: 1.1rem; }
            .papan { border-radius: 12px; }
            .papan-kepala { padding: 14px; }
            .ikon-kotak { width: 38px; height: 38px; font-size: 1.05rem; }
            footer { font-size: .8rem; }
        }

        @media (max-height: 480px) and (orientation: landscape) {
            .topbar { padding-top: .35rem !important; padding-bottom: .35rem !important; }
        }

        @media (min-width: 1400px) { .isi { max-width: 1200px !important; } }

        @media (prefers-reduced-motion: reduce) {
            .btn-emas, .side-link { transition: none; }
            .btn-emas:hover { transform: none; }
        }

        /* ====== Struk peminjaman ====== */
        .struk {
            background: #fff; color: #111;
            max-width: 340px; margin: 0 auto; padding: 20px 18px;
            font-family: 'Courier New', ui-monospace, monospace; font-size: .9rem;
            border-radius: 6px;
        }
        .struk-judul { text-align: center; font-weight: 700; font-size: 1.05rem; letter-spacing: .04em; }
        .struk-sub   { text-align: center; font-size: .8rem; color: #555; }
        .struk hr    { border: 0; border-top: 1px dashed #777; opacity: 1; margin: 12px 0; }
        .struk-baris { display: flex; justify-content: space-between; gap: 12px; padding: 3px 0; }
        .struk-baris span:first-child { color: #555; flex-shrink: 0; }
        .struk-baris span:last-child  { text-align: right; font-weight: 700; overflow-wrap: anywhere; }
        .struk-status { text-align: center; font-weight: 700; border: 1px dashed #111; padding: 6px; margin-top: 4px; }
        .struk-foot   { text-align: center; font-size: .75rem; color: #555; margin-top: 12px; }
        .struk-qr     { display: flex; justify-content: center; padding: 4px 0; }
        .struk-qr canvas, .struk-qr img { max-width: 100%; height: auto !important; }
        .struk-kode   { text-align: center; font-weight: 700; letter-spacing: .08em; overflow-wrap: anywhere; margin-top: 6px; }

        /* ====== Cetak / simpan PDF ====== */
        @page { size: A4 portrait; margin: 12mm; }

        @media print {
            * { -webkit-print-color-adjust: exact; print-color-adjust: exact; }

            [data-bs-theme="dark"], [data-bs-theme="light"] {
                --bs-body-bg: #fff;
                --bs-body-color: #000;
                --bs-secondary-color: #444;
            }

            body { background: #fff !important; color: #000 !important; }
            .sidebar, .topbar, footer, .no-print, .alert { display: none !important; }
            .col-aksi { display: none !important; }
            .layout, .content { display: block !important; min-height: 0; }
            main { padding: 0 !important; }
            .isi { max-width: none !important; }
            .print-only { display: block; margin: 0 0 12px; color: #444; font-size: 12px; }

            .papan { background: #fff; border: 0; border-radius: 0; overflow: visible; }

            .tabel-data th, .tabel-data td {
                padding: 7px 8px !important;
                color: #000 !important;
                background: #fff !important;
                border: 1px solid #999 !important;
                font-size: 12px;
            }
            .tabel-data thead th { background: #e4eaf5 !important; }
            .badge-status { color: #000 !important; background: #fff !important; border: 1px solid #666 !important; }

            thead { display: table-header-group; }
            tr { break-inside: avoid; }

            /* Mode cetak struk: sembunyikan halaman, tampilkan hanya struk */
            body.cetak-struk .layout,
            body.cetak-struk .modal-backdrop { display: none !important; }
            body.cetak-struk .modal {
                position: static !important; display: block !important;
                overflow: visible !important; opacity: 1 !important;
            }
            body.cetak-struk .modal-dialog {
                margin: 0 auto !important; max-width: 340px;
                transform: none !important; height: auto !important;
            }
            body.cetak-struk .modal-content {
                border: 0 !important; background: #fff !important;
                max-height: none !important; overflow: visible !important;
            }
            body.cetak-struk .modal-body { padding: 0 !important; overflow: visible !important; }
        }
    </style>
</head>

<body>
    @php
        $user       = auth()->user();
        $namaUser   = $user->name ?? ($user->username ?? 'Admin');
        $linkBarang = Route::has('barang.index') ? route('barang.index') : route('kategori.index');
    @endphp

    <div class="layout">

        <!-- Sidebar -->
        <aside class="offcanvas-lg offcanvas-start sidebar" tabindex="-1" id="sidebarMenu" aria-label="Menu admin">

            <div class="offcanvas-header justify-content-end pb-0 d-lg-none">
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                        data-bs-target="#sidebarMenu" aria-label="Tutup"></button>
            </div>

            <div class="offcanvas-body p-0">
                <div class="sidebar-inner">

                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-2 text-decoration-none text-reset">
                        <img src="{{ asset('images/logo.png') }}" alt="" width="56" height="56" class="logo">
                        <span class="brand-teks">InventarisBarang</span>
                    </a>

                    <div class="side-judul">Menu</div>

                    <nav class="nav flex-column gap-1" aria-label="Navigasi admin">
                        <a class="side-link" href="{{ route('dashboard') }}">
                            <i class="bi bi-grid-1x2-fill"></i> Dashboard
                        </a>
                        <a class="side-link" href="{{ route('kategori.index') }}">
                            <i class="bi bi-tags"></i> Kategori
                        </a>
                        <a class="side-link" href="{{ $linkBarang }}">
                            <i class="bi bi-box-seam"></i> Daftar Barang
                        </a>
                        <a class="side-link active" href="{{ route('admin.riwayat') }}" aria-current="page">
                            <i class="bi bi-clock-history"></i> Riwayat
                        </a>
                        <a class="side-link" href="{{ route('admin.log') }}" aria-current="page">
                            <i class="bi bi-journal-text"></i> Log
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

            <header class="topbar px-3 px-md-5 pb-3 d-flex align-items-center justify-content-between gap-2">
                <div class="d-flex align-items-center gap-3" style="min-width: 0;">
                    <button class="btn btn-outline-secondary btn-menu d-lg-none" type="button"
                            data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu"
                            aria-controls="sidebarMenu" aria-label="Buka menu">
                        <i class="bi bi-list"></i>
                    </button>

                    <div class="overflow-hidden">
                        <div class="fw-semibold text-truncate">Panel Admin</div>
                        <div class="small text-body-secondary text-truncate">Manajemen inventaris barang</div>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-3 flex-shrink-0">
                    <div class="small text-body-secondary d-none d-sm-block">{{ now()->format('d/m/Y') }}</div>

                    <button type="button" id="btnTema" class="btn btn-outline-secondary btn-sm btn-tema"
                            aria-label="Ganti mode terang atau gelap">
                        <svg class="ikon-matahari" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg>
                        <svg class="ikon-bulan" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8z"/></svg>
                        <span id="labelTema" class="d-none d-sm-inline">Mode</span>
                    </button>
                </div>
            </header>

            <main class="flex-grow-1 px-3 px-md-5">
                <div class="isi mx-auto" style="max-width: 1120px;">

                    {{-- Judul halaman --}}
                    <section class="d-flex justify-content-between align-items-center flex-wrap gap-3 py-4 py-md-5">
                        <div style="min-width: 0;">
                            <h1>Riwayat Peminjaman</h1>
                            <p class="lead mt-3 mb-0">Catatan transaksi peminjaman dan pengembalian barang.</p>
                        </div>

                        <div class="aksi-halaman d-flex gap-2 flex-wrap no-print">
                            <a href="{{ route('dashboard') }}"
                               class="btn btn-outline-secondary d-inline-flex align-items-center justify-content-center"
                               style="min-height: 44px;">
                                <i class="bi bi-arrow-left me-1"></i> Kembali
                            </a>

                            <button type="button" class="btn btn-emas" onclick="window.print()">
                                <i class="bi bi-printer me-1"></i> Cetak / Simpan PDF
                            </button>
                        </div>
                    </section>

                    <p class="print-only">Dicetak pada {{ now()->format('d/m/Y H:i') }}</p>

                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <section class="papan mb-5" aria-labelledby="judul-daftar">

                        <div class="papan-kepala no-print">
                            <div class="d-flex flex-wrap align-items-center gap-3">
                                <div class="ikon-kotak"><i class="bi bi-clock-history"></i></div>

                                <div class="flex-grow-1" style="min-width: 0;">
                                    <h2 id="judul-daftar" class="h5 fw-bold mb-1">Daftar Transaksi</h2>
                                    <div class="small text-body-secondary">Peminjaman yang berjalan dan yang sudah selesai</div>
                                </div>

                                <span class="badge rounded-pill px-3 py-2 badge-jumlah">
                                    {{ $riwayat->count() }} transaksi
                                </span>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover tabel-data">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Peminjam</th>
                                        <th>Barang</th>
                                        <th>Tanggal Pinjam</th>
                                        <th>Tanggal Kembali</th>
                                        <th>Status</th>
                                        <th class="col-aksi">Aksi</th>
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
                                            <td class="col-no"><span class="nomor">{{ $loop->iteration }}</span></td>
                                            <td class="col-peminjam fw-semibold">{{ $item->peminjam->nama_peminjam ?? '-' }}</td>
                                            <td class="col-barang">{{ $item->barang->nama_barang ?? '-' }}</td>
                                            <td class="col-pinjam" data-label="Dipinjam">{{ $item->tgl_pinjam?->format('d/m/Y H:i') ?? '-' }}</td>
                                            <td class="col-kembali" data-label="Dikembalikan">{{ $item->tgl_kembali?->format('d/m/Y H:i') ?? '-' }}</td>
                                            <td class="col-status">
                                                <span class="badge rounded-pill badge-status {{ $kelas }}">{{ $status }}</span>
                                            </td>
                                            <td class="col-aksi">
                                                <button type="button" class="btn btn-outline-secondary btn-sm"
                                                        data-bs-toggle="modal" data-bs-target="#modalStruk"
                                                        data-kode="{{ $item->kode_transaksi ?? str_pad($item->id, 5, '0', STR_PAD_LEFT) }}"
                                                        data-peminjam="{{ $item->peminjam->nama_peminjam ?? '-' }}"
                                                        data-barang="{{ $item->barang->nama_barang ?? '-' }}"
                                                        data-pinjam="{{ $item->tgl_pinjam?->format('d/m/Y H:i') ?? '-' }}"
                                                        data-tempo="{{ $item->tgl_jatuh_tempo?->format('d/m/Y H:i') ?? '-' }}"
                                                        data-kembali="{{ $item->tgl_kembali?->format('d/m/Y H:i') ?? 'Belum dikembalikan' }}"
                                                        data-status="{{ $status }}">
                                                    <i class="bi bi-receipt me-1"></i> Lihat selengkapnya
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr class="baris-kosong">
                                            <td colspan="7" class="text-center kosong">
                                                <i class="bi bi-inbox"></i>
                                                <div class="fw-bold mb-1">Belum ada riwayat peminjaman</div>
                                                <div class="small">Transaksi akan muncul di sini setelah ada peminjaman.</div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </section>

                </div>
            </main>

            <footer class="py-3 mt-auto px-3 px-md-5">
                <div class="isi mx-auto d-flex flex-column flex-sm-row align-items-center justify-content-between gap-2 text-center text-sm-start" style="max-width: 1120px;">
                    <span>&copy; copyright 2026 smkn4padalarang | Lab PPLG PK</span>
                    <span><a href="{{ route('peminjaman.index') }}">Peminjaman</a></span>
                </div>
            </footer>

        </div>
    </div>

    <!-- Modal struk peminjaman -->
    <div class="modal fade" id="modalStruk" tabindex="-1" aria-labelledby="judulStruk" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header no-print">
                    <h2 class="modal-title h5" id="judulStruk">Struk Peminjaman</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <div class="modal-body">
                    <div class="struk">
                        <div class="struk-judul">INVENTARIS BARANG</div>
                        <div class="struk-sub">SMKN 4 Padalarang | Lab PPLG PK</div>
                        <hr>
                        <div class="struk-qr"><div id="sQr"></div></div>
                        <div class="struk-kode" id="sKode">-</div>
                        <hr>
                        <div class="struk-baris"><span>Peminjam</span><span id="sPeminjam">-</span></div>
                        <div class="struk-baris"><span>Barang</span><span id="sBarang">-</span></div>
                        <hr>
                        <div class="struk-baris"><span>Dipinjam</span><span id="sPinjam">-</span></div>
                        <div class="struk-baris"><span>Jatuh tempo</span><span id="sTempo">-</span></div>
                        <div class="struk-baris"><span>Dikembalikan</span><span id="sKembali">-</span></div>
                        <hr>
                        <div class="struk-status" id="sStatus">-</div>
                        <div class="struk-foot">Simpan struk ini sebagai bukti peminjaman.</div>
                    </div>
                </div>

                <div class="modal-footer no-print">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-emas" id="btnCetakStruk">
                        <i class="bi bi-printer me-1"></i> Cetak Struk
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
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

        // Struk peminjaman: isi modal dari data-* tombol, lalu cetak
        (function () {
            const modal = document.getElementById('modalStruk');

            modal.addEventListener('show.bs.modal', (e) => {
                const d = e.relatedTarget.dataset;
                document.getElementById('sKode').textContent     = d.kode;
                const qr = document.getElementById('sQr');
                qr.innerHTML = '';
                new QRCode(qr, { text: d.kode, width: 150, height: 150 });
                document.getElementById('sPeminjam').textContent = d.peminjam;
                document.getElementById('sBarang').textContent   = d.barang;
                document.getElementById('sPinjam').textContent   = d.pinjam;
                document.getElementById('sTempo').textContent    = d.tempo;
                document.getElementById('sKembali').textContent  = d.kembali;
                document.getElementById('sStatus').textContent   = 'STATUS: ' + d.status.toUpperCase();
            });

            document.getElementById('btnCetakStruk').addEventListener('click', () => {
                document.body.classList.add('cetak-struk');
                window.print();
            });
        })();

        // Konfirmasi setelah cetak laporan (struk tidak dihitung sebagai cetak laporan)
        (function () {
            let sedangKonfirmasi = false;

            window.addEventListener('afterprint', () => {
                if (document.body.classList.contains('cetak-struk')) {
                    document.body.classList.remove('cetak-struk');
                    return;
                }

                if (sedangKonfirmasi) return;
                sedangKonfirmasi = true;

                if (confirm('Apakah laporan sudah berhasil dicetak / disimpan sebagai PDF?')) {
                    fetch(@json(route('admin.riwayat.cetak.catat')), {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        keepalive: true,
                    });
                }

                sedangKonfirmasi = false;
            });
        })();
    </script>
</body>
</html>