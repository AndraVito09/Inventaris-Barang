<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Dashboard') - Inventaris Barang</title>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    {{-- Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,500;12..96,600;12..96,700;12..96,800&display=swap" rel="stylesheet">

    {{-- CSS dari halaman --}}
    @stack('styles')

    <style>
        :root {
            --navy: #102442;
            --navy-dark: #0a172b;
            --navy-light: #1c365b;
            --gold: #f1b53d;
            --sidebar-width: 255px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Bricolage Grotesque', Arial, sans-serif;
        }

        body[data-bs-theme="light"] {
            --bs-body-bg: #f3f6fb;
            --bs-body-color: #17253b;
            --bs-secondary-color: #748198;
            --bs-border-color: #e0e6ef;
            --app-card: #ffffff;
            --app-hover: #f0f4fa;
            --app-shadow: 0 8px 30px rgba(16, 36, 66, .06);
        }

        body[data-bs-theme="dark"] {
            --bs-body-bg: #0b1424;
            --bs-body-color: #edf2fa;
            --bs-secondary-color: #a2b0c5;
            --bs-border-color: #283850;
            --app-card: #111f34;
            --app-hover: #1a2b43;
            --app-shadow: 0 8px 30px rgba(0, 0, 0, .18);
        }

        .admin-wrapper {
            min-height: 100vh;
        }

        /* SIDEBAR */
        .sidebar {
            width: var(--sidebar-width);
            min-width: var(--sidebar-width);
            height: 100vh;
            min-height: 100vh;
            position: sticky;
            top: 0;
            display: flex;
            flex-direction: column;
            padding: 25px 17px 18px;
            color: #fff;
            background:
                radial-gradient(ellipse at top left, rgba(55, 91, 145, .25), transparent 55%),
                linear-gradient(180deg, var(--navy), var(--navy-dark));
            border-right: 1px solid rgba(255, 255, 255, .07);
            z-index: 1000;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 3px 8px 27px;
            margin-bottom: 18px;
            border-bottom: 1px solid rgba(255, 255, 255, .10);
            color: #fff;
            text-decoration: none;
        }

        .brand-icon {
            width: 43px;
            height: 43px;
            display: flex;
            flex-shrink: 0;
            align-items: center;
            justify-content: center;
            border-radius: 13px;
            color: #18243a;
            background: var(--gold);
            font-size: 22px;
        }

        .brand-name {
            font-size: 16px;
            font-weight: 800;
            line-height: 1.2;
        }

        .brand-subtitle {
            margin-top: 5px;
            color: #aab9d0;
            font-size: 11px;
        }

        .sidebar-label {
            padding: 0 12px;
            margin: 9px 0 12px;
            color: #8294b0;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.7px;
            text-transform: uppercase;
        }

        .sidebar .nav {
            gap: 6px;
        }

        .sidebar .nav-link {
            display: flex;
            align-items: center;
            gap: 13px;
            min-height: 46px;
            padding: 12px 14px;
            color: #c3cee0;
            border: 1px solid transparent;
            border-radius: 11px;
            font-size: 13px;
            font-weight: 600;
            transition: background .2s, color .2s;
        }

        .sidebar .nav-link i {
            width: 20px;
            text-align: center;
            font-size: 17px;
        }

        .sidebar .nav-link:hover {
            color: #fff;
            background: rgba(255, 255, 255, .07);
        }

        .sidebar .nav-link.active {
            color: #fff;
            background: rgba(241, 181, 61, .13);
            border-color: rgba(241, 181, 61, .22);
        }

        .sidebar .nav-link.active i {
            color: var(--gold);
        }

        .sidebar-bottom {
            margin-top: auto;
            padding-top: 22px;
        }

        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 13px 9px;
            margin-bottom: 12px;
            border-top: 1px solid rgba(255, 255, 255, .10);
        }

        .sidebar-avatar {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 37px;
            height: 37px;
            border-radius: 50%;
            background: rgba(241, 181, 61, .18);
            color: var(--gold);
            font-size: 17px;
        }

        .sidebar-user-name {
            color: #fff;
            font-size: 12px;
            font-weight: 700;
        }

        .sidebar-user-role {
            margin-top: 3px;
            color: #9cacc4;
            font-size: 11px;
        }

        .btn-logout {
            width: 100%;
            padding: 10px;
            color: #f0bdc2;
            background: rgba(220, 53, 69, .09);
            border: 1px solid rgba(220, 53, 69, .22);
            border-radius: 10px;
            font-size: 12px;
            font-weight: 700;
        }

        .btn-logout:hover {
            color: #fff;
            background: #dc3545;
        }

        /* KONTEN UTAMA */
        .admin-main {
            flex: 1;
            min-width: 0;
        }

        .topbar {
            min-height: 76px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            padding: 15px 32px;
            background: var(--app-card);
            border-bottom: 1px solid var(--bs-border-color);
        }

        .topbar-title {
            margin: 0;
            font-size: 14px;
            font-weight: 700;
        }

        .topbar-subtitle {
            margin-top: 4px;
            color: var(--bs-secondary-color);
            font-size: 11px;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .theme-toggle,
        .mobile-toggle {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            width: 39px;
            height: 39px;
            color: var(--bs-body-color);
            background: var(--app-card);
            border: 1px solid var(--bs-border-color);
            border-radius: 11px;
            font-size: 17px;
        }

        .theme-toggle:hover,
        .mobile-toggle:hover {
            background: var(--app-hover);
        }

        .main-content {
            padding: 30px 32px;
            min-height: calc(100vh - 76px);
        }

        /* KARTU DAN TABEL */
        .card {
            color: var(--bs-body-color);
            background: var(--app-card);
            border: 1px solid var(--bs-border-color);
            border-radius: 16px;
            box-shadow: var(--app-shadow);
        }

        .card-header {
            background: transparent;
            border-bottom-color: var(--bs-border-color);
        }

        .form-control,
        .form-select,
        .input-group-text {
            color: var(--bs-body-color);
            background-color: var(--app-card);
            border-color: var(--bs-border-color);
        }

        .form-control:focus,
        .form-select:focus {
            color: var(--bs-body-color);
            background-color: var(--app-card);
            border-color: #7398d2;
            box-shadow: 0 0 0 .2rem rgba(70, 116, 185, .15);
        }

        .table {
            --bs-table-color: var(--bs-body-color);
            --bs-table-bg: transparent;
            --bs-table-border-color: var(--bs-border-color);
            --bs-table-hover-bg: var(--app-hover);
            --bs-table-hover-color: var(--bs-body-color);
        }

        .table thead th {
            font-size: 12px;
            font-weight: 700;
        }

        .btn-primary {
            --bs-btn-bg: var(--navy);
            --bs-btn-border-color: var(--navy);
            --bs-btn-hover-bg: var(--navy-light);
            --bs-btn-hover-border-color: var(--navy-light);
        }

        .btn-gold {
            color: #172238;
            background: var(--gold);
            border: 1px solid var(--gold);
            border-radius: 10px;
            font-weight: 700;
        }

        .btn-gold:hover {
            color: #172238;
            background: #f8c65c;
            border-color: #f8c65c;
        }

        .modal-content {
            color: var(--bs-body-color);
            background: var(--app-card);
            border-color: var(--bs-border-color);
            border-radius: 16px;
        }

        .modal-header,
        .modal-footer {
            border-color: var(--bs-border-color);
        }

        [data-bs-theme="dark"] .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%);
        }

        /* RESPONSIVE */
        .mobile-toggle {
            display: none;
        }

        .sidebar-backdrop {
            display: none;
        }

        @media (max-width: 991.98px) {
            .sidebar {
                position: fixed;
                left: 0;
                top: 0;
                transform: translateX(-100%);
                transition: transform .25s ease;
                box-shadow: 10px 0 35px rgba(0, 0, 0, .2);
            }

            body.sidebar-open .sidebar {
                transform: translateX(0);
            }

            body.sidebar-open .sidebar-backdrop {
                display: block;
                position: fixed;
                inset: 0;
                z-index: 999;
                background: rgba(4, 12, 25, .55);
            }

            .mobile-toggle {
                display: inline-flex;
            }

            .topbar {
                padding: 14px 20px;
            }

            .main-content {
                padding: 24px 20px;
            }
        }

        @media (max-width: 575.98px) {
            .topbar {
                min-height: 65px;
                padding: 12px 14px;
            }

            .main-content {
                padding: 20px 13px;
            }

            .topbar-subtitle {
                display: none;
            }
        }
    </style>
</head>

<body data-bs-theme="light">

<div class="admin-wrapper d-flex">

    {{-- SIDEBAR --}}
    <aside class="sidebar" id="adminSidebar">

        <a href="{{ route('dashboard') }}" class="brand">
            <div class="brand-icon">
                <i class="bi bi-box-seam-fill"></i>
            </div>

            <div>
                <div class="brand-name">Inventaris Barang</div>
                <div class="brand-subtitle">Sistem Manajemen Inventaris</div>
            </div>
        </a>

        <div class="sidebar-label">Menu Utama</div>

        <ul class="nav flex-column mb-auto">

            {{-- Dashboard --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}"
                   href="{{ route('dashboard') }}">
                    <i class="bi bi-grid-1x2-fill"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            {{-- Kategori --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->is('kategori*') ? 'active' : '' }}"
                   href="{{ route('kategori.index') }}">
                    <i class="bi bi-collection-fill"></i>
                    <span>Kategori Barang</span>
                </a>
            </li>

            {{-- Data Barang --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->is('barang*') ? 'active' : '' }}"
                   href="{{ route('barang.index') }}">
                    <i class="bi bi-box-seam"></i>
                    <span>Data Barang</span>
                </a>
            </li>

            {{-- Riwayat: gunakan nama route yang benar --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/riwayat*') ? 'active' : '' }}"
                   href="{{ route('admin.riwayat') }}">
                    <i class="bi bi-clock-history"></i>
                    <span>Riwayat Peminjaman</span>
                </a>
            </li>

        </ul>

        <div class="sidebar-bottom">

            <div class="sidebar-user">
                <div class="sidebar-avatar">
                    <i class="bi bi-person-fill"></i>
                </div>

                <div>
                    <div class="sidebar-user-name">
                        {{ auth()->user()->username ?? 'Administrator' }}
                    </div>
                    <div class="sidebar-user-role">Administrator</div>
                </div>
            </div>

            <button type="button"
                    class="btn btn-logout"
                    data-bs-toggle="modal"
                    data-bs-target="#modalLogout">
                <i class="bi bi-box-arrow-left me-2"></i>
                Logout
            </button>

        </div>
    </aside>

    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    {{-- AREA KONTEN --}}
    <div class="admin-main">

        <header class="topbar">

            <div class="d-flex align-items-center gap-3">

                <button type="button"
                        class="mobile-toggle"
                        id="mobileMenuToggle"
                        aria-label="Buka menu">
                    <i class="bi bi-list"></i>
                </button>

                <div>
                    <h1 class="topbar-title">@yield('title', 'Dashboard')</h1>
                    <div class="topbar-subtitle">
                        Inventaris Barang / @yield('title', 'Dashboard')
                    </div>
                </div>

            </div>

            <div class="topbar-actions">
                <button type="button"
                        class="theme-toggle"
                        id="themeToggle"
                        title="Ganti tema"
                        aria-label="Ganti tema">
                    <i class="bi bi-moon-stars-fill" id="themeIcon"></i>
                </button>
            </div>

        </header>

        <main class="main-content">
            @yield('content')
        </main>

    </div>
</div>

{{-- MODAL LOGOUT --}}
<div class="modal fade" id="modalLogout" tabindex="-1"
     aria-labelledby="modalLogoutLabel" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">

            <div class="modal-body text-center p-4">

                <div class="mb-3 text-danger" style="font-size: 38px;">
                    <i class="bi bi-box-arrow-right"></i>
                </div>

                <h2 class="h5 fw-bold" id="modalLogoutLabel">
                    Konfirmasi Logout
                </h2>

                <p class="text-body-secondary small mb-4">
                    Apakah kamu yakin ingin keluar dari akun admin?
                </p>

                <form method="POST"
                      action="{{ route('logout') }}"
                      class="d-flex gap-2 justify-content-center">

                    @csrf

                    <button type="button"
                            class="btn btn-outline-secondary flex-fill"
                            data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit" class="btn btn-danger flex-fill">
                        <i class="bi bi-box-arrow-right me-1"></i>
                        Logout
                    </button>

                </form>

            </div>
        </div>
    </div>
</div>

{{-- Bootstrap JavaScript --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const body = document.body;
    const themeToggle = document.getElementById('themeToggle');
    const themeIcon = document.getElementById('themeIcon');
    const menuToggle = document.getElementById('mobileMenuToggle');
    const backdrop = document.getElementById('sidebarBackdrop');

    // Mode terang dan gelap
    function applyTheme(theme) {
        body.setAttribute('data-bs-theme', theme);

        const dark = theme === 'dark';

        themeIcon.className = dark
            ? 'bi bi-sun-fill'
            : 'bi bi-moon-stars-fill';

        themeToggle.title = dark
            ? 'Gunakan mode terang'
            : 'Gunakan mode gelap';
    }

    let savedTheme = 'light';

    try {
        savedTheme = localStorage.getItem('inventaris-theme') || 'light';
    } catch (error) {
        savedTheme = 'light';
    }

    applyTheme(savedTheme);

    themeToggle.addEventListener('click', function () {
        const nextTheme =
            body.getAttribute('data-bs-theme') === 'dark'
                ? 'light'
                : 'dark';

        applyTheme(nextTheme);

        try {
            localStorage.setItem('inventaris-theme', nextTheme);
        } catch (error) {
            // Tema tetap berjalan tanpa penyimpanan lokal.
        }
    });

    // Sidebar untuk layar kecil
    function closeSidebar() {
        body.classList.remove('sidebar-open');
    }

    menuToggle.addEventListener('click', function () {
        body.classList.toggle('sidebar-open');
    });

    backdrop.addEventListener('click', closeSidebar);

    document.querySelectorAll('.sidebar .nav-link').forEach(function (link) {
        link.addEventListener('click', closeSidebar);
    });
});
</script>

{{-- JavaScript dari halaman --}}
@stack('scripts')

</body>
</html>
```