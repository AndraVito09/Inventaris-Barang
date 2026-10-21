<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Peminjam - Inventaris Barang</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,500;12..96,700;12..96,800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0d2622;
            --bg2: #12302b;
            --garis: rgba(232, 239, 235, .14);
            --teks: #e8efeb;
            --redup: #9bb3aa;
            --emas: #f1b53d;
            --tosca: #4fd1ac;
            --mawar: #f0708f;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body {
            min-height: 100vh; display: flex; flex-direction: column;
            background: var(--bg); color: var(--teks);
            font-family: 'Bricolage Grotesque', system-ui, -apple-system, 'Segoe UI', sans-serif;
            line-height: 1.6;
        }
        a:focus-visible, button:focus-visible { outline: 3px solid var(--tosca); outline-offset: 3px; }

        /* Navbar */
        .nav { position: sticky; top: 0; z-index: 20; background: rgba(13, 38, 34, .92); backdrop-filter: blur(10px); border-bottom: 1px solid var(--garis); }
        .nav-isi { max-width: 1120px; margin: 0 auto; padding: 14px 24px; display: flex; align-items: center; gap: 32px; position: relative; }
        .merek { display: flex; align-items: center; gap: 12px; color: var(--teks); text-decoration: none; font-weight: 800; font-size: 1.25rem; letter-spacing: -.01em; }
        .menu { display: flex; align-items: center; gap: 28px; margin-left: auto; }
        .tautan { color: var(--redup); text-decoration: none; font-weight: 500; padding: 4px 0; border-bottom: 2px solid transparent; transition: color .2s, border-color .2s; }
        .tautan:hover, .tautan.aktif { color: var(--teks); border-color: var(--emas); }
        .toggle { display: none; margin-left: auto; background: none; border: 1.5px solid var(--garis); color: var(--teks); border-radius: 8px; padding: 8px 14px; font: inherit; cursor: pointer; }

        main { flex: 1; width: 100%; max-width: 1120px; margin: 0 auto; padding: 0 24px; }

        /* Bagian atas */
        .atas { display: grid; grid-template-columns: .9fr 1.1fr; gap: 56px; align-items: center; padding: 64px 0 48px; }
        h1 { font-size: clamp(2.3rem, 4.6vw, 3.6rem); font-weight: 800; letter-spacing: -.025em; line-height: 1.05; }
        .lead { margin-top: 18px; max-width: 28rem; color: var(--redup); font-size: 1.1rem; }

        /* Papan kondisi barang (gerakan satu-satunya: bar terisi dan angka naik sekali) */
        .papan { background: var(--bg2); border: 1px solid var(--garis); border-radius: 16px; padding: 26px 28px 24px; }
        .papan-judul { font-weight: 700; font-size: 1.05rem; margin-bottom: 16px; }
        .bar { display: flex; height: 14px; border-radius: 7px; overflow: hidden; background: rgba(232, 239, 235, .08); }
        .seg { display: block; flex: none; width: var(--w); height: 100%; animation: isi 1.3s cubic-bezier(.2, .7, .2, 1) .2s both; }
        .s-ada { background: var(--tosca); }
        .s-pinjam { background: var(--emas); }
        .s-telat { background: var(--mawar); }
        @keyframes isi { from { width: 0; } to { width: var(--w); } }

        .angka { margin-top: 22px; display: grid; grid-template-columns: repeat(4, 1fr); }
        .angka div { padding: 0 18px; border-left: 1px solid var(--garis); }
        .angka div:first-child { padding-left: 0; border-left: 0; }
        .angka dt { color: var(--redup); font-size: .9rem; display: flex; align-items: center; gap: 8px; }
        .angka dt::before { content: ""; width: 9px; height: 9px; border-radius: 2px; background: var(--c, transparent); border: 1.5px solid var(--c, var(--redup)); }
        .angka dd { font-size: 2.5rem; font-weight: 800; line-height: 1.15; letter-spacing: -.02em; font-variant-numeric: tabular-nums; }
        .a-ada { --c: var(--tosca); }
        .a-pinjam { --c: var(--emas); }
        .a-telat { --c: var(--mawar); }
        .ket { margin-top: 16px; padding-top: 14px; border-top: 1px solid var(--garis); color: var(--redup); font-size: .92rem; }
        .ket.peringatan { color: var(--mawar); font-weight: 500; }

        /* Layanan */
        h2 { font-size: 1.5rem; font-weight: 800; letter-spacing: -.015em; margin-bottom: 18px; }
        .utama { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
        .utama a { display: block; padding: 30px 30px 32px; border-radius: 14px; text-decoration: none; transition: transform .2s, background .2s, border-color .2s; }
        .utama h3 { font-size: 1.5rem; font-weight: 800; letter-spacing: -.01em; }
        .utama p { margin-top: 8px; max-width: 24rem; }
        .u-pinjam { background: var(--emas); color: #1a1405; }
        .u-pinjam:hover { background: #f7c55c; transform: translateY(-3px); }
        .u-kembali { border: 1.5px solid rgba(232, 239, 235, .35); color: var(--teks); }
        .u-kembali p { color: var(--redup); }
        .u-kembali:hover { border-color: var(--teks); background: rgba(232, 239, 235, .05); transform: translateY(-3px); }

        .lainnya { list-style: none; margin-top: 26px; }
        .lainnya li { border-top: 1px solid var(--garis); }
        .lainnya li:last-child { border-bottom: 1px solid var(--garis); }
        .lainnya a { display: grid; grid-template-columns: 16rem 1fr; gap: 20px; padding: 18px 8px; color: var(--teks); text-decoration: none; transition: background .2s; }
        .lainnya a:hover { background: rgba(232, 239, 235, .05); }
        .lainnya strong { font-weight: 700; }
        .lainnya span { color: var(--redup); }

        .aturan { margin: 40px 0 72px; padding: 16px 20px; border: 1.5px solid rgba(241, 181, 61, .4); background: rgba(241, 181, 61, .08); border-radius: 10px; color: var(--redup); font-size: .95rem; }
        .aturan strong { color: var(--emas); }

        .footer { border-top: 1px solid var(--garis); padding: 22px 24px; color: var(--redup); font-size: .9rem; text-align: center; }

        @media (max-width: 860px) {
            .atas { grid-template-columns: 1fr; gap: 36px; padding-top: 40px; }
            .utama { grid-template-columns: 1fr; }
            .lainnya a { grid-template-columns: 1fr; gap: 2px; }
            .angka { grid-template-columns: repeat(2, 1fr); row-gap: 20px; }
            .angka div:nth-child(3) { padding-left: 0; border-left: 0; }
            .toggle { display: block; }
            .menu { display: none; position: absolute; top: 100%; left: 0; right: 0; margin: 0; padding: 18px 24px 24px; flex-direction: column; align-items: flex-start; gap: 16px; background: var(--bg); border-bottom: 1px solid var(--garis); }
            .menu.buka { display: flex; }
        }
        @media (prefers-reduced-motion: reduce) {
            html { scroll-behavior: auto; }
            .seg { animation: none; }
            .utama a, .tautan, .lainnya a { transition: none; }
            .u-pinjam:hover, .u-kembali:hover { transform: none; }
        }
    </style>
</head>
<body>

    @php
        $total    = (int) ($totalBarang ?? 0);
        $ada      = (int) ($barangTersedia ?? 0);
        $telat    = (int) ($terlambat ?? 0);
        $pinjam   = max((int) ($sedangDipinjam ?? 0) - $telat, 0);
        $dasar    = max($total, 1);
        $lebar    = fn ($n) => round(min($n / $dasar * 100, 100), 2);
    @endphp

    <nav class="nav" aria-label="Menu utama">
        <div class="nav-isi">
            <a class="merek" href="{{ url('/peminjam') }}">
                <svg width="34" height="34" viewBox="0 0 34 34" aria-hidden="true">
                    <rect width="34" height="34" rx="9" fill="#f1b53d"/>
                    <path fill="#1a1405" d="M8 9h2v16H8zM12 9h1v16h-1zM15 9h3v16h-3zM20 9h1v16h-1zM23 9h3v16h-3z"/>
                </svg>
                <span>InventarisBarang</span>
            </a>
            <button class="toggle" id="toggle" type="button" aria-expanded="false" aria-controls="menu">Menu</button>
            <div class="menu" id="menu">
                <a class="tautan aktif" href="{{ url('/peminjam') }}" aria-current="page">Beranda</a>
                <a class="tautan" href="{{ url('/peminjam/katalog') }}">Daftar Barang</a>
                <a class="tautan" href="{{ url('/peminjam/riwayat') }}">Riwayat</a>
                <a class="tautan" href="{{ url('/') }}#tentang">Tentang</a>
            </div>
        </div>
    </nav>

    <main>
        <section class="atas">
            <div>
                <h1>Selamat datang di layanan peminjaman.</h1>
                <p class="lead">Pinjam dan kembalikan barang sekolah di satu tempat, tanpa perlu login.</p>
            </div>

            <div class="papan">
                <div class="papan-judul">Kondisi barang saat ini</div>
                <div class="bar" role="img" aria-label="Dari {{ $total }} barang: {{ $ada }} tersedia, {{ $pinjam }} dipinjam, {{ $telat }} terlambat">
                    <span class="seg s-ada" style="--w: {{ $lebar($ada) }}%"></span>
                    <span class="seg s-pinjam" style="--w: {{ $lebar($pinjam) }}%"></span>
                    <span class="seg s-telat" style="--w: {{ $lebar($telat) }}%"></span>
                </div>
                <dl class="angka">
                    <div><dt>Total barang</dt><dd data-hitung="{{ $total }}">{{ $total }}</dd></div>
                    <div class="a-ada"><dt>Tersedia</dt><dd data-hitung="{{ $ada }}">{{ $ada }}</dd></div>
                    <div class="a-pinjam"><dt>Dipinjam</dt><dd data-hitung="{{ $pinjam }}">{{ $pinjam }}</dd></div>
                    <div class="a-telat"><dt>Terlambat</dt><dd data-hitung="{{ $telat }}">{{ $telat }}</dd></div>
                </dl>
                @if ($telat > 0)
                    <p class="ket peringatan">{{ $telat }} barang melewati batas kembali dan belum dikembalikan.</p>
                @else
                    <p class="ket">Tidak ada barang yang terlambat.</p>
                @endif
            </div>
        </section>

        <section aria-labelledby="judul-layanan">
            <h2 id="judul-layanan">Pilih layanan</h2>

            <div class="utama">
                <a class="u-pinjam" href="{{ url('/peminjam/pinjam') }}">
                    <h3>Peminjaman</h3>
                    <p>Scan barcode barang, isi data, lalu dapatkan struk.</p>
                </a>
                <a class="u-kembali" href="{{ url('/peminjam/kembali') }}">
                    <h3>Pengembalian</h3>
                    <p>Scan QR pada struk untuk mengembalikan barang.</p>
                </a>
            </div>

            <ul class="lainnya">
                <li>
                    <a href="{{ url('/peminjam/katalog') }}">
                        <strong>Daftar barang</strong>
                        <span>Cek barang yang tersedia dan yang sedang dipinjam.</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('/peminjam/riwayat') }}">
                        <strong>Riwayat peminjaman dan pengembalian</strong>
                        <span>Pantau transaksi yang berjalan dan yang sudah selesai.</span>
                    </a>
                </li>
            </ul>
        </section>

        <div class="aturan">
            <strong>Ketentuan keterlambatan:</strong>
            batas pengembalian tertera di struk. Siswa yang terlambat mengembalikan barang wajib membersihkan lab.
        </div>
    </main>

    <footer class="footer">
        &copy; 2026 Inventaris Barang. Semua hak dilindungi.
    </footer>

    <script>
        // Menu mobile
        const toggle = document.getElementById('toggle');
        const menu = document.getElementById('menu');
        toggle.addEventListener('click', () => {
            const buka = menu.classList.toggle('buka');
            toggle.setAttribute('aria-expanded', buka);
        });

        // Angka naik dari 0 sekali, selaras dengan bar yang terisi
        if (!matchMedia('(prefers-reduced-motion: reduce)').matches) {
            document.querySelectorAll('[data-hitung]').forEach(el => {
                const akhir = Number(el.dataset.hitung);
                if (!akhir) return;
                el.textContent = 0;
                setTimeout(() => {
                    const mulai = performance.now();
                    const langkah = t => {
                        const p = Math.min((t - mulai) / 1200, 1);
                        el.textContent = Math.round(akhir * (1 - Math.pow(1 - p, 3)));
                        if (p < 1) requestAnimationFrame(langkah);
                    };
                    requestAnimationFrame(langkah);
                }, 200);
            });
        }
    </script>
</body>
</html>