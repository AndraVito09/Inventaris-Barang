<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - Inventaris Barang</title>

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
            --hover: rgba(11, 36, 71, .06);
            --bayangan: rgba(11, 36, 71, .14);
            --biru: #1d4fa8;
        }
        /* Palet biru tua: gelap */
        [data-bs-theme="dark"] {
            --bs-body-bg: #0a1930;
            --bs-body-color: #e7eefb;
            --bs-secondary-color: #9fb2d3;
            --bs-border-color: rgba(173, 198, 245, .16);
            --kartu: #102445;
            --hover: rgba(173, 198, 245, .1);
            --bayangan: rgba(0, 0, 0, .5);
            --biru: #6ea8ff;
        }
        :root { --emas: #f1b53d; }

        body { font-family: 'Bricolage Grotesque', system-ui, -apple-system, 'Segoe UI', sans-serif; }
        a:focus-visible, button:focus-visible { outline: 3px solid #6ea8ff; outline-offset: 3px; }

        .link-kembali { color: var(--bs-secondary-color); }
        .link-kembali:hover { color: var(--bs-body-color); }

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
            padding: 12px 26px;
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

        /* Kartu login */
        .login-card {
            width: 100%;
            max-width: 400px;
            padding: 32px;
            border-radius: 20px;
            background: var(--kartu);
            border: 1px solid var(--bs-border-color);
            box-shadow: 0 20px 50px var(--bayangan);
        }

        .brand-mark {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: #1a1405;
            background: var(--emas);
            flex-shrink: 0;
        }

        .judul { font-size: 1.5rem; font-weight: 800; letter-spacing: -.02em; line-height: 1.15; }

        .form-label { font-size: .875rem; font-weight: 500; color: var(--bs-secondary-color); }
        .form-control { padding: .7rem .9rem; border-radius: 10px; }
        .form-control:focus {
            border-color: var(--biru);
            box-shadow: 0 0 0 .2rem color-mix(in srgb, var(--biru) 25%, transparent);
        }

        @media (max-width: 575.98px) {
            .login-card { padding: 24px; }
        }
        @media (prefers-reduced-motion: reduce) {
            .btn-emas { transition: none; }
            .btn-emas:hover { transform: none; }
        }
    </style>
</head>
<body>

<div class="d-flex flex-column min-vh-100">

    <!-- Bar atas -->
    <div class="d-flex align-items-center justify-content-between p-3 p-md-4">
        <a href="{{ route('menu') }}" class="link-kembali text-decoration-none small">
            <i class="bi bi-arrow-left"></i> Kembali ke beranda
        </a>

        <button type="button" id="btnTema" class="btn btn-outline-secondary btn-sm btn-tema"
                aria-label="Ganti mode terang atau gelap">
            <svg class="ikon-matahari" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg>
            <svg class="ikon-bulan" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8z"/></svg>
            <span id="labelTema" class="d-none d-sm-inline">Mode</span>
        </button>
    </div>

    <!-- Form login -->
    <main class="flex-grow-1 d-flex align-items-center justify-content-center px-3 pb-5">
        <div class="login-card">

            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="brand-mark"><i class="bi bi-box-seam-fill"></i></div>
                <div>
                    <h1 class="judul mb-0">Login Inventaris</h1>
                    <div class="small text-body-secondary">Masuk sebagai admin</div>
                </div>
            </div>

            <form action="{{ route('login.process') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>

                    <input
                        type="text"
                        class="form-control @error('username') is-invalid @enderror"
                        id="username"
                        name="username"
                        placeholder="Masukkan username"
                        value="{{ old('username') }}"
                        autocomplete="username"
                        required
                        autofocus
                    >

                    @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>

                    <input
                        type="password"
                        class="form-control @error('password') is-invalid @enderror"
                        id="password"
                        name="password"
                        placeholder="Masukkan password"
                        autocomplete="current-password"
                        required
                    >

                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                @if (session('login_error'))
                    <div class="alert alert-danger py-2 small" role="alert">
                        {{ session('login_error') }}
                    </div>
                @endif

                <button type="submit" class="btn btn-emas w-100 mt-2">
                    Masuk
                </button>
            </form>

        </div>
    </main>

</div>

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