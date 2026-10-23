<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>Dashboard Peminjam - Inventaris Barang</title>
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
            --ada: #0f9d7a;
            --pinjam: #d99a14;
            --telat: #d6336c;
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
            --ada: #4fd1ac;
            --pinjam: #f1b53d;
            --telat: #f0708f;
        }
        :root { --emas: #f1b53d; }

        html { -webkit-text-size-adjust: 100%; }
        body {
            font-family: 'Bricolage Grotesque', system-ui, -apple-system, 'Segoe UI', sans-serif;
            overflow-x: hidden; /* cegah scroll ke samping di layar kecil */
        }
        a:focus-visible, button:focus-visible { outline: 3px solid #6ea8ff; outline-offset: 3px; }

        /* Wadah utama: ikut lebar layar, hormati area aman (notch) di HP */
        .wadah {
            max-width: 1120px;
            padding-left: max(var(--bs-gutter-x, .75rem), env(safe-area-inset-left));
            padding-right: max(var(--bs-gutter-x, .75rem), env(safe-area-inset-right));
        }

        /* Navbar */
        .navbar { background: var(--nav-bg); backdrop-filter: blur(10px); border-bottom: 1px solid var(--bs-border-color); padding-top: .75rem; padding-bottom: .75rem; }
        .navbar-brand { font-weight: 800; font-size: 1.5rem; letter-spacing: -.01em; color: var(--bs-body-color); min-width: 0; }
        .navbar-brand span { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .logo { width: 56px; height: 56px; border-radius: 10px; object-fit: contain; flex-shrink: 0; }
        .navbar-toggler { min-width: 44px; min-height: 44px; border-color: var(--bs-border-color); }
        .navbar .nav-link { color: var(--bs-secondary-color); font-weight: 500; border-bottom: 2px solid transparent; padding-left: 0; padding-right: 0; }
        .navbar .nav-link:hover, .navbar .nav-link.active { color: var(--bs-body-color); border-color: var(--emas); }
        .btn-outline-secondary {
            --bs-btn-color: var(--bs-body-color); --bs-btn-border-color: var(--bs-border-color);
            --bs-btn-hover-color: var(--bs-body-color); --bs-btn-hover-bg: var(--hover); --bs-btn-hover-border-color: var(--bs-secondary-color);
            --bs-btn-active-color: var(--bs-body-color); --bs-btn-active-bg: var(--hover); --bs-btn-active-border-color: var(--bs-secondary-color);
        }
        .btn-tema, .btn-kembali { display: inline-flex; align-items: center; justify-content: center; gap: 8px; min-height: 40px; }
        [data-bs-theme="dark"] .ikon-bulan, [data-bs-theme="light"] .ikon-matahari { display: none; }

        /* Judul */
        h1 { font-size: clamp(1.75rem, 6vw, 3.6rem); font-weight: 800; letter-spacing: -.025em; line-height: 1.08; overflow-wrap: break-word; }
        h2 { font-size: clamp(1.25rem, 3.5vw, 1.5rem); font-weight: 800; letter-spacing: -.015em; }
        .lead { max-width: 28rem; color: var(--bs-secondary-color); font-size: clamp(1rem, 2.2vw, 1.15rem); }

        /* Kondisi barang: daftar polos, satu baris per status */
        .papan { background: var(--kartu); border: 1px solid var(--bs-border-color); border-radius: 16px; }
        .papan-kepala { display: flex; justify-content: space-between; align-items: baseline; flex-wrap: wrap; gap: .25rem 1rem; }
        .kondisi { list-style: none; margin: 0; padding: 0; }
        .kondisi li { display: grid; grid-template-columns: 6.5rem 1fr 2.5rem; align-items: center; gap: 14px; padding: .7rem 0; border-bottom: 1px solid var(--bs-border-color); }
        .kondisi li:last-child { border-bottom: 0; }
        .kondisi .nama { font-weight: 500; }
        .kondisi .jumlah { text-align: right; font-weight: 700; font-variant-numeric: tabular-nums; }
        .jalur { height: 6px; border-radius: 3px; background: var(--bs-border-color); overflow: hidden; }
        .jalur > span { display: block; height: 100%; border-radius: 3px; background: var(--c); }
        .k-ada { --c: var(--ada); }
        .k-pinjam { --c: var(--pinjam); }
        .k-telat { --c: var(--telat); }
        .peringatan { color: var(--telat); font-weight: 500; }

        /* Layanan */
        .layanan { display: block; padding: 2rem; border-radius: 14px; background: var(--emas); color: #1a1405; text-decoration: none; transition: transform .2s, background .2s; }
        .layanan:hover { background: #f7c55c; color: #1a1405; transform: translateY(-3px); }
        .layanan h3 { font-size: clamp(1.25rem, 3.5vw, 1.5rem); font-weight: 800; letter-spacing: -.01em; }
        .layanan-kembali { background: transparent; border: 1.5px solid var(--bs-secondary-color); color: var(--bs-body-color); }
        .layanan-kembali p { color: var(--bs-secondary-color); }
        .layanan-kembali:hover { background: var(--hover); border-color: var(--bs-body-color); color: var(--bs-body-color); }

        .daftar .list-group-item { background: transparent; color: var(--bs-body-color); display: grid; grid-template-columns: 16rem 1fr; gap: 20px; padding: 1.1rem .5rem; min-height: 56px; }
        .daftar .list-group-item:hover { background: var(--hover); }
        .daftar span { color: var(--bs-secondary-color); }

        .aturan { border: 1.5px solid rgba(241, 181, 61, .45); background: rgba(241, 181, 61, .1); color: var(--bs-secondary-color); border-radius: 10px; }
        .aturan strong { color: #b97d00; }
        [data-bs-theme="dark"] .aturan strong { color: var(--emas); }

        footer { border-top: 1px solid var(--bs-border-color); color: var(--bs-secondary-color); font-size: .9rem; padding-bottom: max(1rem, env(safe-area-inset-bottom)); }
        footer a { color: var(--bs-secondary-color); display: inline-block; padding: .25rem 0; }
        footer a:hover { color: var(--bs-body-color); }

        /* ====== Responsif ====== */

        /* Laptop kecil & tablet landscape (di bawah 992px): hero sudah menumpuk, rapikan jarak */
        @media (max-width: 991.98px) {
            .lead { max-width: 36rem; }
            .daftar .list-group-item { grid-template-columns: 12rem 1fr; gap: 16px; }
        }

        /* Tablet portrait & HP (di bawah 768px) */
        @media (max-width: 767.98px) {
            .navbar-brand { font-size: 1.2rem; }
            .logo { width: 46px; height: 46px; }
            .navbar .nav-link { padding-top: .6rem; padding-bottom: .6rem; border-bottom-width: 0; border-left: 3px solid transparent; padding-left: .75rem; }
            .navbar .nav-link:hover, .navbar .nav-link.active { border-left-color: var(--emas); }
            .navbar-nav .btn { width: 100%; }
            .layanan { padding: 1.5rem; }
            .daftar .list-group-item { grid-template-columns: 1fr; gap: 2px; }
            .kondisi li { grid-template-columns: 5.5rem 1fr 2rem; gap: 10px; }
            .btn-kembali { width: 100%; }        /* tombol kembali selebar layar agar mudah ditekan */
            .aturan { padding: 1rem !important; margin-top: 2rem !important; margin-bottom: 2rem !important; }
            footer .wadah { text-align: center; }
        }

        /* HP kecil (di bawah 400px) */
        @media (max-width: 399.98px) {
            .navbar-brand { font-size: 1.05rem; gap: .5rem !important; }
            .logo { width: 38px; height: 38px; }
            .kondisi li { grid-template-columns: 4.75rem 1fr 1.75rem; gap: 8px; }
            .papan { border-radius: 12px; }
            .layanan { padding: 1.25rem; }
            footer { font-size: .8rem; }
        }

        /* Layar pendek (HP landscape): kurangi jarak vertikal */
        @media (max-height: 480px) and (orientation: landscape) {
            .navbar { padding-top: .35rem; padding-bottom: .35rem; }
            .logo { width: 38px; height: 38px; }
            section.row { padding-top: 1rem !important; padding-bottom: 1rem !important; }
        }

        /* Layar sangat lebar */
        @media (min-width: 1400px) {
            .wadah { max-width: 1200px; }
        }

        @media (prefers-reduced-motion: reduce) {
            .layanan { transition: none; }
            .layanan:hover { transform: none; }
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

    @php
        $total    = (int) ($totalBarang ?? 0);
        $ada      = (int) ($barangTersedia ?? 0);
        $telat    = (int) ($terlambat ?? 0);
        $pinjam   = max((int) ($sedangDipinjam ?? 0) - $telat, 0);
        $dasar    = max($total, 1);
        $lebar    = fn ($n) => round(min($n / $dasar * 100, 100), 2);
    @endphp

    <nav class="navbar navbar-expand-md sticky-top" aria-label="Menu utama">
        <div class="container wadah">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/peminjam') }}">
                <img src="{{ asset('images/logo.png') }}" alt="" width="56" height="56" class="logo">
                <span>InventarisBarang</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu" aria-controls="menu" aria-expanded="false" aria-label="Buka menu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="menu">
                <ul class="navbar-nav ms-auto align-items-md-center gap-md-4 py-3 py-md-0">
                    <li class="nav-item"><a class="nav-link active" href="{{ url('/peminjam') }}" aria-current="page">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/peminjam/katalog') }}">Daftar Barang</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/peminjam/riwayat') }}">Riwayat</a></li>
                    <li class="nav-item mt-2 mt-md-0">
                        <button type="button" id="btnTema" class="btn btn-outline-secondary btn-sm btn-tema" aria-label="Ganti mode terang atau gelap">
                            <svg class="ikon-matahari" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg>
                            <svg class="ikon-bulan" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8z"/></svg>
                            <span id="labelTema">Mode</span>
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container wadah flex-grow-1">
        <div class="pt-4">
            <a class="btn btn-outline-secondary btn-sm btn-kembali" href="{{ url('/') }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 18l-6-6 6-6"/></svg>
                Kembali ke menu
            </a>
        </div>

        <section class="row g-4 g-lg-5 align-items-center py-4 py-md-5">
            <div class="col-12 col-lg-5">
                <h1>Selamat datang di layanan peminjaman.</h1>
                <p class="lead mt-3">Pinjam dan kembalikan barang sekolah di satu tempat, tanpa perlu login.</p>
            </div>

            <div class="col-12 col-lg-7">
                <div class="papan p-3 p-md-4">
                    <div class="papan-kepala mb-2">
                        <h2 class="h5 fw-bold mb-0">Kondisi barang saat ini</h2>
                        <span class="small text-secondary">{{ $total }} barang terdaftar</span>
                    </div>

                    <ul class="kondisi">
                        <li class="k-ada">
                            <span class="nama">Tersedia</span>
                            <span class="jalur" aria-hidden="true"><span style="width: {{ $lebar($ada) }}%"></span></span>
                            <span class="jumlah">{{ $ada }}</span>
                        </li>
                        <li class="k-pinjam">
                            <span class="nama">Dipinjam</span>
                            <span class="jalur" aria-hidden="true"><span style="width: {{ $lebar($pinjam) }}%"></span></span>
                            <span class="jumlah">{{ $pinjam }}</span>
                        </li>
                        <li class="k-telat">
                            <span class="nama">Terlambat</span>
                            <span class="jalur" aria-hidden="true"><span style="width: {{ $lebar($telat) }}%"></span></span>
                            <span class="jumlah">{{ $telat }}</span>
                        </li>
                    </ul>

                    <div class="border-top mt-2 pt-3 small">
                        @if ($telat > 0)
                            <p class="peringatan mb-0">{{ $telat }} barang melewati batas kembali dan belum dikembalikan.</p>
                        @else
                            <p class="text-secondary mb-0">Tidak ada barang yang terlambat.</p>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        <section aria-labelledby="judul-layanan">
            <h2 id="judul-layanan" class="mb-3">Layanan</h2>

            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <a class="layanan h-100" href="{{ url('/peminjam/pinjam') }}">
                        <h3 class="mb-2">Peminjaman</h3>
                        <p class="mb-0">Scan barcode barang, isi data, lalu dapatkan struk.</p>
                    </a>
                </div>
                <div class="col-12 col-md-6">
                    <a class="layanan layanan-kembali h-100" href="{{ url('/peminjam/kembali') }}">
                        <h3 class="mb-2">Pengembalian</h3>
                        <p class="mb-0">Scan QR pada struk untuk mengembalikan barang.</p>
                    </a>
                </div>
            </div>

            <div class="list-group list-group-flush daftar mt-4 border-top border-bottom">
                <a href="{{ url('/peminjam/katalog') }}" class="list-group-item list-group-item-action">
                    <strong>Daftar barang</strong>
                    <span>Cek barang yang tersedia dan yang sedang dipinjam.</span>
                </a>
                <a href="{{ url('/peminjam/riwayat') }}" class="list-group-item list-group-item-action">
                    <strong>Riwayat peminjaman dan pengembalian</strong>
                    <span>Pantau transaksi yang berjalan dan yang sudah selesai.</span>
                </a>
            </div>
        </section>

        <div class="aturan px-4 py-3 mt-5 mb-5 small">
            <strong>Ketentuan keterlambatan:</strong>
            batas pengembalian tertera di struk. Siswa yang terlambat mengembalikan barang wajib membersihkan lab.
        </div>
    </main>

    <footer class="py-3">
        <div class="container wadah d-flex flex-column flex-sm-row align-items-center justify-content-between gap-2 text-center text-sm-start">
            <span>&copy; copyright 2026 smkn4padalarang | Lab PPLG PK</span>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Ganti tema terang/gelap
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
    </script>
</body>
</html>