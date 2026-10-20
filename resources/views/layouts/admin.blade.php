<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Inventaris Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f4f9; font-family: Arial, sans-serif; }
        .sidebar { width: 230px; min-height: 100vh; background: #198754; }
        .sidebar .nav-link { color: #e8f5ee; border-radius: 6px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background: rgba(255,255,255,.2); color: #fff; }
    </style>
</head>
<body>
<div class="d-flex">
    <aside class="sidebar p-3 d-flex flex-column">
        <h5 class="text-white mb-4">Inventaris Barang</h5>
        <ul class="nav flex-column gap-1 mb-auto">
            <li><a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="/dashboard">Dashboard</a></li>
            <li><a class="nav-link {{ request()->is('kategori*') ? 'active' : '' }}" href="{{ route('kategori.index') }}">Kategori</a></li>
            {{-- Menu Barang & Riwayat ditambah nanti --}}
        </ul>
        <button class="btn btn-light btn-sm mt-3" data-bs-toggle="modal" data-bs-target="#modalLogout">Logout</button>
    </aside>

    <main class="flex-grow-1 p-4">
        @yield('content')
    </main>
</div>

{{-- Modal konfirmasi logout --}}
<div class="modal fade" id="modalLogout" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center">
                <p class="mb-3">Yakin akan logout?</p>
                <form method="POST" action="{{ route('logout') }}" class="d-flex gap-2 justify-content-center">
                    @csrf
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tidak</button>
                    <button type="submit" class="btn btn-danger btn-sm">Ya</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>