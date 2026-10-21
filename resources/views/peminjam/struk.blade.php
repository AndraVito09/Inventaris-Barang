<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Struk Peminjaman - Inventaris Barang</title>
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

        .btn-teal {
            --bs-btn-color: #060d0a; --bs-btn-bg: var(--teal); --bs-btn-border-color: var(--teal);
            --bs-btn-hover-color: #060d0a; --bs-btn-hover-bg: var(--teal-2); --bs-btn-hover-border-color: var(--teal-2);
            --bs-btn-active-color: #060d0a; --bs-btn-active-bg: var(--teal-2); --bs-btn-active-border-color: var(--teal-2);
            font-weight: 700;
        }

        .btn-gold {
            --bs-btn-color: var(--gold); --bs-btn-bg: rgba(245, 166, 35, 0.1); --bs-btn-border-color: rgba(245, 166, 35, 0.4);
            --bs-btn-hover-color: var(--gold); --bs-btn-hover-bg: rgba(245, 166, 35, 0.18); --bs-btn-hover-border-color: var(--gold);
            font-weight: 700;
        }

        .card { background: rgba(255, 255, 255, 0.02); border-color: var(--bs-border-color); }

        .kode-struk { font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace; letter-spacing: .08em; color: var(--teal); }

        .footer-dark {
            background: #040a07;
            border-top: 2px solid;
            border-image: linear-gradient(90deg, var(--teal), var(--gold), var(--rose)) 1;
        }

        /* Saat dicetak: hanya struk yang tampil, warna hitam putih */
        @media print {
            .navbar, .footer-dark, .tanpa-cetak { display: none !important; }
            body { background: #fff !important; color: #000 !important; }
            .card { border: 1px solid #000 !important; background: #fff !important; }
            .kode-struk, .text-secondary { color: #000 !important; }
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
                    <li class="nav-item"><a class="nav-link" href="{{ url('/peminjam/katalog') }}">Daftar Barang</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/peminjam/riwayat') }}">Riwayat</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Isi halaman -->
    <main class="container flex-grow-1 py-5">
        <div class="mx-auto" style="max-width: 520px;">
            <div class="card p-4 text-center">
                <h1 class="h4 fw-bold mb-1">Bukti Peminjaman</h1>
                <p class="text-secondary small mb-4">Simpan struk ini. Scan QR atau ketik kodenya saat mengembalikan barang.</p>

                <div class="kode-struk fs-3 fw-bold mb-3">{{ $peminjaman->kode_transaksi }}</div>

                <div class="d-inline-block bg-white p-3 rounded mx-auto mb-4">
                    <div id="qr"></div>
                </div>

                <dl class="row text-start mb-0">
                    <dt class="col-5 text-secondary fw-normal">Barang</dt>
                    <dd class="col-7">{{ $peminjaman->barang->nama_barang }}</dd>

                    <dt class="col-5 text-secondary fw-normal">Peminjam</dt>
                    <dd class="col-7">{{ $peminjaman->peminjam->nama_peminjam }} ({{ $peminjaman->peminjam->tipe_peminjam }})</dd>

                    @if ($peminjaman->peminjam->keterangan)
                        <dt class="col-5 text-secondary fw-normal">Keterangan</dt>
                        <dd class="col-7">{{ $peminjaman->peminjam->keterangan }}</dd>
                    @endif

                    @if ($peminjaman->peminjam->no_hp)
                        <dt class="col-5 text-secondary fw-normal">No. HP</dt>
                        <dd class="col-7">{{ $peminjaman->peminjam->no_hp }}</dd>
                    @endif

                    <dt class="col-5 text-secondary fw-normal">Tanggal pinjam</dt>
                    <dd class="col-7">{{ $peminjaman->tgl_pinjam->format('d/m/Y H:i') }}</dd>

                    <dt class="col-5 text-secondary fw-normal">Batas kembali</dt>
                    <dd class="col-7">{{ $peminjaman->tgl_jatuh_tempo->format('d/m/Y H:i') }}</dd>

                    <dt class="col-5 text-secondary fw-normal">Status</dt>
                    <dd class="col-7">{{ $peminjaman->status_peminjaman }}</dd>
                </dl>

                {{-- Pengingat sanksi, hanya untuk siswa --}}
                @if ($peminjaman->peminjam->tipe_peminjam === 'Siswa')
                    <p class="small mt-4 mb-0 text-start" style="color: var(--gold);">
                        Pengingat: siswa yang terlambat mengembalikan barang wajib membersihkan lab.
                    </p>
                @endif
            </div>

            <div class="d-grid gap-2 d-sm-flex justify-content-center mt-4 tanpa-cetak">
                <button type="button" class="btn btn-teal rounded-pill px-4" onclick="window.print()">Cetak struk</button>
                <a href="{{ url('/peminjam') }}" class="btn btn-gold rounded-pill px-4">Kembali ke beranda</a>
            </div>
        </div>
    </main>

    {{-- Pop up berhasil (hanya muncul sekali, tepat setelah data disimpan) --}}
    @if (session('berhasil'))
    <div class="modal fade" id="modalBerhasil" tabindex="-1" aria-labelledby="judulBerhasil" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center" style="border-color: var(--bs-border-color);">
                <div class="modal-body p-4">
                    <h2 class="h4 fw-bold mb-2" id="judulBerhasil" style="color: var(--teal);">Peminjaman berhasil</h2>
                    <p class="mb-1">{{ session('berhasil') }}</p>
                    <p class="text-secondary small mb-4">
                        Kode transaksi: <span class="kode-struk">{{ $peminjaman->kode_transaksi }}</span><br>
                        Cetak atau simpan struk untuk mengembalikan barang.
                    </p>
                    <button type="button" class="btn btn-teal rounded-pill px-4" data-bs-dismiss="modal">Lihat struk</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Footer -->
    <footer class="footer-dark text-center py-4 mt-auto">
        <small class="text-secondary">&copy; copyright 2026 smkn4padalarang | Lab PPLG PK</small>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        new QRCode(document.getElementById('qr'), {
            text: @json($peminjaman->kode_transaksi),
            width: 180,
            height: 180,
        });

        @if (session('berhasil'))
            new bootstrap.Modal(document.getElementById('modalBerhasil')).show();
        @endif
    </script>
</body>
</html>