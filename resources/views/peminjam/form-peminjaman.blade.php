<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Peminjaman Barang - Inventaris Barang</title>
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
            --bs-btn-disabled-color: #060d0a; --bs-btn-disabled-bg: var(--teal); --bs-btn-disabled-border-color: var(--teal);
            font-weight: 700;
        }

        .btn-gold {
            --bs-btn-color: var(--gold); --bs-btn-bg: rgba(245, 166, 35, 0.1); --bs-btn-border-color: rgba(245, 166, 35, 0.4);
            --bs-btn-hover-color: var(--gold); --bs-btn-hover-bg: rgba(245, 166, 35, 0.18); --bs-btn-hover-border-color: var(--gold);
            font-weight: 700;
        }

        .card { background: rgba(255, 255, 255, 0.02); border-color: var(--bs-border-color); }
        .form-control, .form-select { background-color: rgba(255, 255, 255, 0.03); border-color: var(--bs-border-color); }
        .form-control:focus, .form-select:focus { border-color: var(--teal); box-shadow: 0 0 0 .2rem rgba(0, 212, 138, 0.2); }

        /* Daftar pilihan dropdown: latar gelap, teks terang (sebelumnya putih dan tidak terbaca) */
        .form-select { color-scheme: dark; }
        .form-select option { background-color: #0d1a14; color: #e8f5ef; }

        .kode-struk { font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace; letter-spacing: .08em; color: var(--teal); }

        .footer-dark {
            background: #040a07;
            border-top: 2px solid;
            border-image: linear-gradient(90deg, var(--teal), var(--gold), var(--rose)) 1;
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
        <div class="mx-auto" style="max-width: 640px;">
            <h1 class="fw-bold mb-2">Peminjaman Barang</h1>
            <p class="text-secondary mb-4">Pilih tipe peminjam, scan barcode barang, lalu isi data peminjam.</p>

            <form method="POST" action="{{ url('/peminjam/pinjam') }}" class="card p-4" id="form-pinjam">
                @csrf

                {{-- 1. Tipe peminjam --}}
                <div class="mb-3">
                    <label for="tipe_peminjam" class="form-label">Saya meminjam sebagai</label>
                    <select id="tipe_peminjam" name="tipe_peminjam" class="form-select @error('tipe_peminjam') is-invalid @enderror" required>
                        <option value="">Pilih tipe peminjam</option>
                        @foreach ($daftarTipe as $tipe)
                            <option value="{{ $tipe }}" @selected(old('tipe_peminjam') === $tipe)>{{ $tipe }}</option>
                        @endforeach
                    </select>
                    @error('tipe_peminjam') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div id="bagian-form" class="d-none">

                    {{-- 2. Scan barcode barang --}}
                    <div class="mb-3">
                        <label for="kode_barcode" class="form-label">Kode barang (scan barcode)</label>
                        <input type="text" id="kode_barcode" name="kode_barcode" value="{{ old('kode_barcode') }}"
                               class="form-control form-control-lg kode-struk @error('kode_barcode') is-invalid @enderror"
                               placeholder="Arahkan scanner ke barcode barang" autocomplete="off" required>
                        @error('kode_barcode') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div id="hasil-barang" class="small mt-2" aria-live="polite"></div>
                    </div>

                    {{-- 3. Data peminjam --}}
                    <div class="mb-3">
                        <label for="nama_peminjam" class="form-label">Nama lengkap</label>
                        <input type="text" id="nama_peminjam" name="nama_peminjam" value="{{ old('nama_peminjam') }}"
                               class="form-control @error('nama_peminjam') is-invalid @enderror" maxlength="100" required>
                        @error('nama_peminjam') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Kolom khusus tiap tipe. Yang tidak dipilih disembunyikan dan di-disable, jadi tidak ikut terkirim. --}}
                    <div data-tipe="Siswa" class="d-none">
                        <div class="mb-3">
                            <label for="nis" class="form-label">NIS</label>
                            <input type="text" id="nis" name="nis" value="{{ old('nis') }}" class="form-control @error('nis') is-invalid @enderror" maxlength="30" required>
                            @error('nis') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="kelas" class="form-label">Kelas</label>
                            <input type="text" id="kelas" name="kelas" value="{{ old('kelas') }}" class="form-control @error('kelas') is-invalid @enderror" maxlength="30" placeholder="Contoh: XI RPL 2" required>
                            @error('kelas') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div data-tipe="Guru" class="d-none">
                        <div class="mb-3">
                            <label for="nip" class="form-label">NIP <span class="text-secondary">(opsional)</span></label>
                            <input type="text" id="nip" name="nip" value="{{ old('nip') }}" class="form-control @error('nip') is-invalid @enderror" maxlength="30">
                            @error('nip') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div data-tipe="Staff" class="d-none">
                        <div class="mb-3">
                            <label for="bagian" class="form-label">Bagian / jabatan</label>
                            <input type="text" id="bagian" name="bagian" value="{{ old('bagian') }}" class="form-control @error('bagian') is-invalid @enderror" maxlength="100" placeholder="Contoh: Tata Usaha" required>
                            @error('bagian') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div data-tipe="Orang Tua/Lainnya" class="d-none">
                        <div class="mb-3">
                            <label for="no_hp" class="form-label">Nomor HP</label>
                            <input type="tel" id="no_hp" name="no_hp" value="{{ old('no_hp') }}" class="form-control @error('no_hp') is-invalid @enderror" maxlength="20" required>
                            @error('no_hp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="keperluan" class="form-label">Keperluan atau nama siswa yang diwakili</label>
                            <input type="text" id="keperluan" name="keperluan" value="{{ old('keperluan') }}" class="form-control @error('keperluan') is-invalid @enderror" maxlength="150" required>
                            @error('keperluan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="lama_pinjam" class="form-label">Lama peminjaman</label>
                        <select id="lama_pinjam" name="lama_pinjam" class="form-select @error('lama_pinjam') is-invalid @enderror">
                            @foreach ([1, 2, 3, 5, 7, 14] as $hari)
                                <option value="{{ $hari }}" @selected((int) old('lama_pinjam', 3) === $hari)>{{ $hari }} hari</option>
                            @endforeach
                        </select>
                        @error('lama_pinjam') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <button type="submit" id="tombol-simpan" class="btn btn-teal btn-lg rounded-pill w-100" disabled>Pinjam dan buat struk</button>
                </div>
            </form>
        </div>
    </main>

    <!-- Pop up konfirmasi sebelum data disimpan -->
    <div class="modal fade" id="modalKonfirmasi" tabindex="-1" aria-labelledby="judulKonfirmasi" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-color: var(--bs-border-color);">
                <div class="modal-header">
                    <h2 class="modal-title h5" id="judulKonfirmasi">Yakin data sudah benar?</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <p class="text-secondary small">Periksa kembali data berikut sebelum disimpan.</p>
                    <dl class="row mb-0" id="ringkasan"></dl>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-gold" data-bs-dismiss="modal">Periksa lagi</button>
                    <button type="button" class="btn btn-teal" id="tombol-yakin">Ya, simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer-dark text-center py-4 mt-auto">
        <small class="text-secondary">&copy; 2026 Inventaris Barang. Semua hak dilindungi.</small>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const formPinjam      = document.getElementById('form-pinjam');
        const pilihTipe       = document.getElementById('tipe_peminjam');
        const bagianForm      = document.getElementById('bagian-form');
        const inputKode       = document.getElementById('kode_barcode');
        const hasilBarang     = document.getElementById('hasil-barang');
        const tombolSimpan    = document.getElementById('tombol-simpan');
        const ringkasan       = document.getElementById('ringkasan');
        const tombolYakin     = document.getElementById('tombol-yakin');
        const modalKonfirmasi = new bootstrap.Modal(document.getElementById('modalKonfirmasi'));

        let namaBarang = '';

        // Tampilkan kolom sesuai tipe peminjam
        function aturTipe() {
            const tipe = pilihTipe.value;
            bagianForm.classList.toggle('d-none', !tipe);

            document.querySelectorAll('[data-tipe]').forEach(grup => {
                const aktif = grup.dataset.tipe === tipe;
                grup.classList.toggle('d-none', !aktif);
                grup.querySelectorAll('input').forEach(input => input.disabled = !aktif);
            });

            if (tipe) inputKode.focus();
        }

        // Cek barang hasil scan: harus ada dan berstatus Tersedia
        async function cekBarang() {
            const kode = inputKode.value.trim();
            tombolSimpan.disabled = true;
            namaBarang = '';
            if (!kode) { hasilBarang.textContent = ''; return; }

            try {
                const respon = await fetch(`{{ url('/peminjam/barang') }}/${encodeURIComponent(kode)}`, {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await respon.json();

                if (data.ok) {
                    namaBarang = data.nama;
                    hasilBarang.className = 'small mt-2 text-success';
                    hasilBarang.textContent = `${data.nama} tersedia.`;
                    tombolSimpan.disabled = false;
                    document.getElementById('nama_peminjam').focus();
                } else {
                    hasilBarang.className = 'small mt-2 text-danger';
                    hasilBarang.textContent = data.pesan;
                }
            } catch (e) {
                hasilBarang.className = 'small mt-2 text-danger';
                hasilBarang.textContent = 'Gagal memeriksa barang. Periksa koneksi lalu scan ulang.';
            }
        }

        // Tambah satu baris ke ringkasan (dilewati jika nilainya kosong)
        function tambahBaris(label, nilai) {
            if (!nilai) return;
            const dt = document.createElement('dt');
            dt.className = 'col-5 text-secondary fw-normal';
            dt.textContent = label;
            const dd = document.createElement('dd');
            dd.className = 'col-7';
            dd.textContent = nilai;
            ringkasan.append(dt, dd);
        }

        // Saat tombol simpan ditekan: tampilkan pop up konfirmasi dulu, form belum terkirim
        formPinjam.addEventListener('submit', e => {
            e.preventDefault();
            ringkasan.replaceChildren();

            tambahBaris('Tipe peminjam', pilihTipe.value);
            tambahBaris('Barang', `${namaBarang} (${inputKode.value.trim()})`);
            tambahBaris('Nama', document.getElementById('nama_peminjam').value.trim());

            document.querySelectorAll('[data-tipe]:not(.d-none) input').forEach(input => {
                const label = document.querySelector(`label[for="${input.id}"]`).textContent.replace('(opsional)', '').trim();
                tambahBaris(label, input.value.trim());
            });

            const lama = document.getElementById('lama_pinjam');
            tambahBaris('Lama pinjam', lama.options[lama.selectedIndex].text);

            modalKonfirmasi.show();
        });

        // Setelah dikonfirmasi: kirim form (tombol dikunci agar tidak terkirim dua kali)
        tombolYakin.addEventListener('click', () => {
            tombolYakin.disabled = true;
            tombolYakin.textContent = 'Menyimpan...';
            formPinjam.submit();
        });

        // Scanner mengirim Enter setelah kode: jangan kirim form, cukup cek barang
        inputKode.addEventListener('keydown', e => { if (e.key === 'Enter') { e.preventDefault(); cekBarang(); } });
        inputKode.addEventListener('input', () => { tombolSimpan.disabled = true; hasilBarang.textContent = ''; });
        inputKode.addEventListener('change', cekBarang);
        pilihTipe.addEventListener('change', aturTipe);

        // Jika pengguna menekan tombol Back, kembalikan tombol konfirmasi ke keadaan awal
        window.addEventListener('pageshow', () => {
            tombolYakin.disabled = false;
            tombolYakin.textContent = 'Ya, simpan';
        });

        aturTipe();
        if (inputKode.value.trim()) cekBarang();
    </script>
</body>
</html>