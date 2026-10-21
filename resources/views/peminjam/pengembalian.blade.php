<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pengembalian - Inventaris Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html { font-size: clamp(15px, 0.85vw, 18px); }

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
            background: rgba(240, 90, 126, 0.08);
            border: 1.5px solid rgba(240, 90, 126, 0.3);
            color: var(--rose);
        }

        .card { background: rgba(255, 255, 255, 0.02); border-color: var(--bs-border-color); }
        .card-aksen { border-top: 4px solid var(--aksen, var(--rose)); }

        /* Area siaga scanner */
        #areaScan {
            position: relative;
            width: 100%;
            flex: 1;
            min-height: 11rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.5rem 1rem;
            border: 2px dashed rgba(240, 90, 126, 0.45);
            border-radius: 1rem;
            overflow: hidden;
            background: rgba(0, 0, 0, 0.35);
            text-align: center;
            cursor: text;
        }
        #areaScan.siap { border-style: solid; border-color: var(--rose); }
        #areaScan .garis-scan {
            position: absolute; left: 8%; right: 8%; top: 0; height: 2px;
            background: var(--rose); box-shadow: 0 0 12px var(--rose);
            opacity: 0; animation: sapu 2.4s ease-in-out infinite;
        }
        #areaScan.siap .garis-scan { opacity: 1; }
        @keyframes sapu { 0%, 100% { top: 12%; } 50% { top: 88%; } }
        #statusScanner { font-weight: 600; }
        #kosong { flex: 1; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; }
        #statusScanner.siap { color: var(--teal); }
        #statusScanner.mati { color: var(--gold); }

        .detail-baris { font-size: 1.05rem; display: flex; justify-content: space-between; gap: 1rem; padding: .75rem 0; border-bottom: 1px solid var(--bs-border-color); }
        .detail-baris:last-child { border-bottom: 0; }
        .detail-baris span:first-child { color: var(--bs-secondary-color); }

        .catatan-terlambat {
            border: 1.5px solid rgba(245, 166, 35, 0.4);
            background: rgba(245, 166, 35, 0.08);
        }

        /* Modal alert terlambat */
        .modal-terlambat .modal-content {
            background: #14080c;
            border: 2px solid var(--rose);
            box-shadow: 0 0 0 0 rgba(240, 90, 126, 0.6);
            animation: denyut 1s ease-in-out infinite;
        }
        .modal-terlambat .ikon-alert {
            width: 4.5rem; height: 4.5rem; border-radius: 50%;
            background: rgba(240, 90, 126, 0.15);
            border: 2px solid var(--rose);
            color: var(--rose);
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 2.4rem; font-weight: 800;
        }
        .sanksi-box {
            border: 1.5px solid rgba(245, 166, 35, 0.5);
            background: rgba(245, 166, 35, 0.1);
            border-radius: .75rem;
        }
        @keyframes denyut {
            0%, 100% { box-shadow: 0 0 0 0 rgba(240, 90, 126, 0.55); }
            50%      { box-shadow: 0 0 0 14px rgba(240, 90, 126, 0); }
        }

        .footer-dark {
            background: #040a07;
            border-top: 2px solid;
            border-image: linear-gradient(90deg, var(--teal), var(--gold), var(--rose)) 1;
        }

        /* Layar berkedip merah saat terlambat (di atas backdrop modal, di bawah kotak modal) */
        body.layar-darurat::after {
            content: ""; position: fixed; inset: 0; z-index: 1052;
            background: rgba(240, 40, 80, 0.45);
            pointer-events: none;
            animation: kedip-merah 1s ease-in-out infinite;
        }
        @keyframes kedip-merah { 0%, 100% { opacity: 0; } 50% { opacity: 1; } }

        @media (prefers-reduced-motion: reduce) {
            .modal-terlambat .modal-content { animation: none; }
            body.layar-darurat::after { animation: none; opacity: .5; }
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

    <!-- Header / Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top navbar-glass">
        <div class="container-fluid px-4 px-xl-5">
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

    <main class="container-fluid px-4 px-xl-5 flex-grow-1 py-4 d-flex flex-column">

        <!-- Judul ringkas dalam satu baris -->
        <section class="d-flex flex-wrap align-items-center gap-2 gap-md-3 mb-4">
            <span class="badge rounded-pill badge-live px-3 py-2">Pengembalian Barang</span>
            <h1 class="h2 fw-bold mb-0 text-gradient">Kembalikan Barang</h1>
            <span class="text-secondary">Silakan scan QR dari struk saat peminjaman.</span>
        </section>

        <div class="row g-4 flex-grow-1">

            <!-- Kolom kiri: scanner -->
            <div class="col-lg-6 d-flex">
                <div class="card card-aksen p-4 w-100 d-flex flex-column" style="--aksen: var(--rose);">
                    <h2 class="h5 fw-bold mb-3">Scan Struk</h2>

                    <div id="areaScan" class="mb-3">
                        <div class="garis-scan" aria-hidden="true"></div>
                        <div class="fw-bold fs-4 mb-1">Arahkan scanner ke QR pada struk</div>
                        <div id="statusScanner" class="small mati" role="status" aria-live="polite">Klik di sini untuk mengaktifkan scanner</div>
                    </div>

                    <label for="inputKode" class="visually-hidden">Kode struk</label>
                    <div class="input-group">
                        <input id="inputKode" type="text" class="form-control" placeholder="Kode struk, mis. TRX-20261021-00002-FB842" autocomplete="off" autofocus>
                        <button id="tombolCari" class="btn btn-outline-light px-4">Cari</button>
                    </div>
                    <div class="text-secondary mt-2">Scanner belum terbaca? Ketik kode di struk lalu tekan Enter.</div>
                </div>
            </div>

            <!-- Kolom kanan: detail transaksi -->
            <div class="col-lg-6 d-flex">
                <div class="card card-aksen p-4 w-100 d-flex flex-column" style="--aksen: var(--teal);">
                    <h2 class="h5 fw-bold mb-3">Detail Peminjaman</h2>

                    <div id="kosong" class="text-secondary text-center">
                        Belum ada struk yang discan.
                    </div>

                    <div id="galat" class="alert alert-danger py-2 d-none" role="alert"></div>

                    <div id="detail" class="d-none my-auto">
                        <div class="detail-baris"><span>Kode</span><strong id="dKode"></strong></div>
                        <div class="detail-baris"><span>Nama peminjam</span><strong id="dNama"></strong></div>
                        <div class="detail-baris" id="barisKelas"><span>Kelas</span><strong id="dKelas"></strong></div>
                        <div class="detail-baris"><span>Barang</span><strong id="dBarang" class="text-end"></strong></div>
                        <div class="detail-baris"><span>Batas pengembalian</span><strong id="dBatas"></strong></div>
                        <div class="detail-baris"><span>Status</span><strong id="dStatus"></strong></div>

                        <button id="tombolKonfirmasi" class="btn w-100 mt-3 fw-bold" style="background: var(--teal); color: #04120b;">
                            Konfirmasi Pengembalian
                        </button>
                    </div>

                    <div id="sukses" class="alert alert-success py-2 d-none mt-2 mb-0" role="status"></div>
                </div>
            </div>
        </div>

        <!-- Ketentuan keterlambatan (satu baris) -->
        <div class="card catatan-terlambat px-4 py-3 mt-4">
            <div class="text-secondary small">
                <strong style="color: var(--gold);">Ketentuan keterlambatan:</strong>
                batas pengembalian tertera di struk. Siswa yang terlambat mengembalikan barang wajib membersihkan lab.
            </div>
        </div>
    </main>

    <!-- Modal alert terlambat -->
    <div class="modal fade modal-terlambat" id="modalTerlambat" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="judulTerlambat" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center p-4">
                <div class="mb-3"><span class="ikon-alert" aria-hidden="true">!</span></div>
                <h2 id="judulTerlambat" class="h3 fw-bold mb-2" style="color: var(--rose);">Anda Terlambat!</h2>
                <p class="mb-1">Barang dikembalikan melewati batas waktu.</p>
                <p class="fs-5 fw-bold mb-4" id="lamaTerlambat"></p>

                <!-- Sanksi hanya tampil untuk siswa -->
                <div id="kotakSanksi" class="sanksi-box p-3 mb-4 text-start d-none">
                    <div class="fw-bold mb-1" style="color: var(--gold);">Sanksi</div>
                    <div>Wajib <strong>membersihkan lab</strong> sebelum meninggalkan ruangan. Lapor ke petugas setelah selesai.</div>
                </div>

                <div class="d-grid gap-2">
                    <button id="tombolMengerti" class="btn btn-lg fw-semibold" style="background: var(--rose); color: #fff;">Saya Mengerti</button>
                    <button id="tombolBisu" class="btn btn-sm btn-outline-secondary">Matikan Suara</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Suara sirine: taruh file di public/audio/sirine.mp3 -->
    <audio id="suaraTerlambat" src="{{ asset('audio/sirine.mp3') }}" preload="auto" loop></audio>

    <footer class="footer-dark text-center py-3 mt-auto">
        <small class="text-secondary">&copy; 2026 Inventaris Barang. Semua hak dilindungi.</small>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    (function () {
        const URL_CEK = "{{ url('/peminjam/kembali/cek') }}";
        const URL_PROSES = "{{ url('/peminjam/kembali/proses') }}";
        const CSRF = document.querySelector('meta[name="csrf-token"]').content;

        const $ = (id) => document.getElementById(id);
        const modalTerlambat = new bootstrap.Modal($('modalTerlambat'));

        let transaksi = null;
        let sedangMemproses = false;

        /* ---------- Tipe peminjam ---------- */
        // Alert terlambat berlaku untuk semua peminjam, tetapi sanksi hanya untuk siswa.
        // Server harus mengirim field "tipe" (isi dari tipe_peminjam) di dalam data.
        function apakahSiswa(d) {
            if (d.tipe === undefined || d.tipe === null) {
                console.warn('[Pengembalian] Field "tipe" tidak dikirim server, dianggap bukan siswa.');
                return false;
            }
            return String(d.tipe).trim().toLowerCase().includes('siswa');
        }

        /* ---------- Suara alarm (file audio) ---------- */
        const suara = $('suaraTerlambat');
        let timerSuara = null;

        // Browser memblokir suara sebelum ada klik pengguna.
        // Audio "dibuka kuncinya" dengan memutarnya dalam keadaan bisu saat interaksi pertama.
        function siapkanAudio() {
            if (suara.dataset.siap) return;
            suara.muted = true;
            suara.play().then(() => {
                suara.pause();
                suara.currentTime = 0;
                suara.muted = false;
                suara.dataset.siap = '1';
            }).catch(() => { suara.muted = false; });
        }

        function mulaiSirene() {
            hentikanSirene();
            suara.currentTime = 0;
            suara.play().catch(() => {});
            timerSuara = setTimeout(hentikanSirene, 6000); // berhenti otomatis setelah 6 detik
        }

        function hentikanSirene() {
            clearTimeout(timerSuara);
            suara.pause();
            suara.currentTime = 0;
        }

        /* ---------- Scanner fisik (bekerja seperti keyboard) ---------- */
        // Scanner mengetik isi QR ke kolom yang sedang fokus lalu menekan Enter.
        // Karena itu kolom input dijaga selalu fokus.
        const input = $('inputKode');
        const area = $('areaScan');
        const status = $('statusScanner');
        let modalTerbuka = false;

        function fokus() {
            if (!modalTerbuka) input.focus();
        }

        function perbaruiStatus() {
            const siap = document.activeElement === input;
            area.classList.toggle('siap', siap);
            status.classList.toggle('siap', siap);
            status.classList.toggle('mati', !siap);
            status.textContent = siap ? 'Scanner siap, silakan scan struk' : 'Klik di sini untuk mengaktifkan scanner';
        }

        /* ---------- Ambil kode dari hasil scan ---------- */
        // Isi QR bisa berupa kode murni, URL, atau JSON. Fungsi ini mengambil kodenya saja.
        function ekstrakKode(teks) {
            teks = (teks || '').trim();

            // 1) Cari pola kode struk di mana pun posisinya (URL, JSON, teks lain)
            const m = teks.match(/TRX-\d{8}-\d+-[A-Z0-9]+/i);
            if (m) return m[0].toUpperCase();

            // 2) Kalau JSON, coba ambil field kode
            try {
                const obj = JSON.parse(teks);
                if (obj && obj.kode) return String(obj.kode).trim();
            } catch (_) { /* bukan JSON */ }

            // 3) Kalau URL, ambil segmen terakhir dari path
            if (/^https?:\/\//i.test(teks)) {
                try {
                    const u = new URL(teks);
                    const seg = u.pathname.split('/').filter(Boolean).pop();
                    if (seg) return decodeURIComponent(seg).trim();
                } catch (_) { /* URL tidak valid */ }
            }

            return teks;
        }

        /* ---------- Ambil data transaksi ---------- */
        async function cariTransaksi(mentah) {
            if (!mentah || sedangMemproses) return;
            sedangMemproses = true;
            siapkanAudio();
            sembunyikanPesan();
            input.value = '';

            const kode = ekstrakKode(mentah);
            console.log('[Pengembalian] Terbaca dari scanner:', mentah, '→ dikirim:', kode);

            try {
                const res = await fetch(URL_CEK, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({ kode })
                });

                const teks = await res.text();
                let json;
                try {
                    json = JSON.parse(teks);
                } catch (_) {
                    console.error('[Pengembalian] Respons bukan JSON. Status', res.status, teks.slice(0, 300));
                    if (res.status === 419) throw new Error('Sesi habis (419). Muat ulang halaman lalu coba lagi.');
                    if (res.status === 404) throw new Error('Route /peminjam/kembali/cek tidak ditemukan (404).');
                    if (res.status === 405) throw new Error('Route harus menerima method POST (405).');
                    if (res.redirected || res.status === 302) throw new Error('Anda belum login atau sesi berakhir.');
                    throw new Error('Server error (' + res.status + '). Cek storage/logs/laravel.log');
                }

                console.log('[Pengembalian] Respons server:', res.status, json);

                const berhasil = json.ok ?? json.success;
                if (!res.ok || !berhasil) {
                    throw new Error(json.pesan || json.message || 'Struk tidak ditemukan.');
                }
                if (!json.data) throw new Error('Respons server tidak berisi data peminjaman.');

                transaksi = json.data;
                tampilkanDetail(transaksi);
            } catch (e) {
                console.error('[Pengembalian] Galat:', e);
                tampilkanGalat(e.message || 'Terjadi kesalahan. Coba lagi.');
            } finally {
                // jeda singkat agar QR yang sama tidak terbaca berulang
                setTimeout(() => { sedangMemproses = false; }, 800);
                fokus();
            }
        }

        /* ---------- Tampilan detail ---------- */
        function hitungTerlambat(batasISO) {
            const selisihMs = Date.now() - new Date(batasISO).getTime();
            if (isNaN(selisihMs) || selisihMs <= 0) return null;
            const menit = Math.floor(selisihMs / 60000);
            const hari = Math.floor(menit / 1440);
            const jam = Math.floor((menit % 1440) / 60);
            const sisaMenit = menit % 60;
            const bagian = [];
            if (hari) bagian.push(hari + ' hari');
            if (jam) bagian.push(jam + ' jam');
            if (sisaMenit || !bagian.length) bagian.push(sisaMenit + ' menit');
            return bagian.join(' ');
        }

        function tampilkanDetail(d) {
            $('kosong').classList.add('d-none');
            $('galat').classList.add('d-none');
            $('detail').classList.remove('d-none');
            const tk = $('tombolKonfirmasi');
            tk.classList.remove('d-none'); tk.disabled = false; tk.textContent = 'Konfirmasi Pengembalian';

            const siswa = apakahSiswa(d);

            $('dKode').textContent = d.kode;
            $('dNama').textContent = d.nama;
            // Baris kelas hanya relevan untuk siswa
            $('barisKelas').classList.toggle('d-none', !siswa);
            $('dKelas').textContent = d.kelas || '-';
            $('dBarang').textContent = Array.isArray(d.barang) ? d.barang.join(', ') : d.barang;
            $('dBatas').textContent = new Date(d.batas_kembali).toLocaleString('id-ID', { dateStyle: 'medium', timeStyle: 'short' });

            const lama = hitungTerlambat(d.batas_kembali);
            const dStatus = $('dStatus');
            if (lama) {
                dStatus.textContent = 'Terlambat ' + lama;
                dStatus.style.color = 'var(--rose)';
            } else {
                dStatus.textContent = 'Tepat waktu';
                dStatus.style.color = 'var(--teal)';
            }

            // Alert & suara muncul untuk semua peminjam yang terlambat; sanksi hanya untuk siswa
            if (lama) bukaAlertTerlambat(lama, siswa);
        }

        function bukaAlertTerlambat(lama, siswa) {
            $('lamaTerlambat').textContent = 'Terlambat ' + lama;
            // Kotak sanksi hanya tampil untuk siswa
            $('kotakSanksi').classList.toggle('d-none', !siswa);
            $('tombolBisu').textContent = 'Matikan Suara';
            modalTerbuka = true;
            document.body.classList.add('layar-darurat');
            modalTerlambat.show();
            mulaiSirene();
        }

        function tutupAlertTerlambat() {
            hentikanSirene();
            modalTerlambat.hide();
        }

        /* ---------- Konfirmasi pengembalian ---------- */
        async function konfirmasi() {
            if (!transaksi) return;
            const tombol = $('tombolKonfirmasi');
            tombol.disabled = true;
            tombol.textContent = 'Memproses...';

            try {
                const res = await fetch(URL_PROSES, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({ kode: transaksi.kode })
                });

                const teks = await res.text();
                let json;
                try {
                    json = JSON.parse(teks);
                } catch (_) {
                    console.error('[Pengembalian] Respons proses bukan JSON. Status', res.status, teks.slice(0, 300));
                    throw new Error('Server error (' + res.status + '). Cek storage/logs/laravel.log');
                }
                console.log('[Pengembalian] Respons proses:', res.status, json);

                const berhasil = json.ok ?? json.success;
                if (!res.ok || !berhasil) throw new Error(json.pesan || json.message || 'Pengembalian gagal diproses.');

                const lama = hitungTerlambat(transaksi.batas_kembali);
                const siswa = apakahSiswa(transaksi);
                const sukses = $('sukses');
                sukses.classList.remove('d-none');
                if (lama && siswa) {
                    sukses.textContent = 'Barang berhasil dikembalikan. Jangan lupa laksanakan sanksi: membersihkan lab.';
                } else if (lama) {
                    sukses.textContent = 'Barang berhasil dikembalikan, tetapi melewati batas waktu.';
                } else {
                    sukses.textContent = 'Barang berhasil dikembalikan. Terima kasih!';
                }
                tombol.classList.add('d-none');
                transaksi = null;
                fokus();
            } catch (e) {
                tombol.disabled = false;
                tombol.textContent = 'Konfirmasi Pengembalian';
                tampilkanGalat(e.message);
            }
        }

        function tampilkanGalat(pesan) {
            const g = $('galat');
            g.textContent = pesan;
            g.classList.remove('d-none');
        }
        function sembunyikanPesan() {
            $('galat').classList.add('d-none');
            $('sukses').classList.add('d-none');
        }

        /* ---------- Event ---------- */
        input.addEventListener('keydown', (e) => {
            if (e.key !== 'Enter') return;
            e.preventDefault();
            if (modalTerbuka) return;
            siapkanAudio();
            cariTransaksi(input.value.trim());
        });
        $('tombolCari').addEventListener('click', () => { siapkanAudio(); cariTransaksi(input.value.trim()); });
        area.addEventListener('click', fokus);

        input.addEventListener('focus', perbaruiStatus);
        input.addEventListener('blur', () => {
            perbaruiStatus();
            // kembalikan fokus otomatis, kecuali pengguna sedang menekan tombol atau modal terbuka
            setTimeout(() => {
                const aktif = document.activeElement;
                if (!modalTerbuka && (!aktif || aktif === document.body)) fokus();
            }, 150);
        });

        // Browser baru mengizinkan suara setelah ada interaksi; scan pertama (ketikan scanner) juga dihitung.
        document.addEventListener('keydown', siapkanAudio, { once: true });
        document.addEventListener('pointerdown', siapkanAudio, { once: true });

        $('tombolKonfirmasi').addEventListener('click', konfirmasi);
        $('tombolMengerti').addEventListener('click', tutupAlertTerlambat);
        $('tombolBisu').addEventListener('click', () => { hentikanSirene(); $('tombolBisu').textContent = 'Suara dimatikan'; });
        $('modalTerlambat').addEventListener('shown.bs.modal', () => { modalTerbuka = true; });
        $('modalTerlambat').addEventListener('hidden.bs.modal', () => { modalTerbuka = false; document.body.classList.remove('layar-darurat'); hentikanSirene(); fokus(); });

        fokus();
        perbaruiStatus();
    })();
    </script>
</body>
</html>