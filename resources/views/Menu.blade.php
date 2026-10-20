<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventaris Barang</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --bg: #050d0a;
            --green: #00e5a0;
            --white: #f3f7f5;
            --muted: #8b9b93;
        }

        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: radial-gradient(
                ellipse at center,
                #111a17 0%,
                var(--bg) 75%
            );
            color: var(--white);
            font-family: Arial, Helvetica, sans-serif;
        }

        /* HEADER */
        .header {
            min-height: 130px;
            padding: 15px 40px;
            display: flex;
            align-items: center;
            gap: 25px;
            border-bottom: 2px solid var(--green);
            box-shadow: 0 5px 20px #00e5a008;
        }

        .logo {
            width: 68px;
            height: 68px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 18px;
            background: var(--green);
            color: #052116;
            font-size: 32px;
            font-weight: bold;
            box-shadow: 0 0 22px #00e5a022;
        }

        .brand {
            display: flex;
            flex-direction: column;
            gap: 7px;
        }

        .brand-name {
            font-size: clamp(24px, 4vw, 42px);
            font-weight: 800;
            letter-spacing: -1px;
        }

        .brand-name span {
            color: var(--green);
        }

        .brand-subtitle {
            color: var(--muted);
            font-size: 12px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        /* KONTEN UTAMA */
        .main {
            flex: 1;
            width: 100%;
            padding: 55px 20px 70px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .welcome-label {
            margin-bottom: 22px;
            padding: 8px 17px;
            border: 1px solid #075b40;
            border-radius: 30px;
            color: var(--green);
            background: #052219;
            font-size: 11px;
            letter-spacing: 1px;
        }

        .welcome-title {
            margin-bottom: 15px;
            font-size: clamp(36px, 6vw, 68px);
            font-weight: 800;
            letter-spacing: -2px;
            background: linear-gradient(90deg, #00e5a0, #a3d85d);
            color: transparent;
            background-clip: text;
            -webkit-background-clip: text;
        }

        .welcome-description {
            max-width: 520px;
            margin-bottom: 42px;
            color: var(--muted);
            font-size: 15px;
            line-height: 1.8;
        }

        /* TOMBOL */
        .menu {
            width: 100%;
            max-width: 420px;
            display: flex;
            flex-direction: column;
            gap: 22px;
        }

        .menu-button {
            min-height: 62px;
            padding: 18px 25px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 12px;
            border: 1px solid var(--green);
            border-radius: 14px;
            background: var(--green);
            color: #04150e;
            font-size: 16px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.25s ease;
        }

        .menu-button:hover {
            transform: translateY(-3px);
            background: #36f6ba;
            box-shadow: 0 8px 28px #00e5a025;
        }

        .menu-button.secondary {
            background: transparent;
            color: var(--green);
            border-color: #08744f;
        }

        .menu-button.secondary:hover {
            background: #062b1e;
            border-color: var(--green);
        }

        .button-icon {
            font-size: 20px;
        }

        /* FOOTER */
        .footer {
            padding: 20px;
            border-top: 1px solid #123729;
            color: #6f8178;
            text-align: center;
            font-size: 11px;
        }

        /* RESPONSIVE */
        @media (max-width: 600px) {
            .header {
                min-height: 100px;
                padding: 15px 20px;
                gap: 15px;
            }

            .logo {
                width: 52px;
                height: 52px;
                border-radius: 14px;
                font-size: 25px;
            }

            .brand-subtitle {
                font-size: 9px;
                letter-spacing: 1px;
            }

            .main {
                padding-top: 45px;
                padding-bottom: 45px;
            }

            .welcome-description {
                font-size: 13px;
                margin-bottom: 32px;
            }

            .menu {
                max-width: 350px;
                gap: 17px;
            }

            .menu-button {
                min-height: 58px;
                padding: 15px;
                font-size: 14px;
            }
        }
    </style>
</head>

<body>

    <!-- HEADER -->
    <header class="header">
        <div class="logo">
            <span>▦</span>
        </div>

        <div class="brand">
            <div class="brand-name">
                Inventaris<span>Barang</span>
            </div>

            <div class="brand-subtitle">
                Sistem Informasi Peminjaman Barang
            </div>
        </div>
    </header>

    <!-- HALAMAN UTAMA -->
    <main class="main">

        <div class="welcome-label">
            SISTEM INVENTARIS BARANG
        </div>

        <h1 class="welcome-title">
            Selamat Datang
        </h1>

        <p class="welcome-description">
            Selamat datang di website Inventaris Barang.
            Silakan pilih menu di bawah untuk masuk sebagai
            admin atau menuju halaman peminjaman barang.
        </p>

        <nav class="menu" aria-label="Menu utama">

            <!-- Selalu menuju form login -->
            <a href="{{ route('login') }}" class="menu-button">
                <span class="button-icon">♙</span>
                Login sebagai Admin
            </a>

            <!-- Menu peminjaman -->
            <a href="{{ route('peminjaman.index') }}"
               class="menu-button secondary">
                <span class="button-icon">▣</span>
                Masuk ke Halaman Peminjaman
            </a>

        </nav>
    </main>

    <!-- FOOTER -->
    <footer class="footer">
        © 2026 Inventaris Barang. Semua hak dilindungi.
    </footer>

</body>
</html>
```