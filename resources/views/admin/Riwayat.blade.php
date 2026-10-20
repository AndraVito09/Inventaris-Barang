<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Peminjaman</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <style>
        :root {
            --bg: #050e0b;
            --sidebar: #06110d;
            --card: #0d1713;
            --border: #123c2d;
            --green: #00d99a;
            --orange: #f5a623;
            --text: #e5f5ee;
            --muted: #91a99d;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--bg);
            color: var(--text);
            font-family: Arial, sans-serif;
        }

        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            width: 245px;
            padding: 28px 18px;
            background: var(--sidebar);
            border-right: 1px solid var(--border);
            z-index: 1000;
        }

        .brand {
            color: var(--green);
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 35px;
        }

        .menu-link {
            display: block;
            padding: 12px 14px;
            margin-bottom: 8px;
            border-radius: 8px;
            color: var(--muted);
            text-decoration: none;
            transition: .2s;
        }

        .menu-link:hover,
        .menu-link.active {
            color: var(--green);
            background: #0b2b20;
        }

        .logout-button {
            width: 100%;
            padding: 11px;
            color: #ff8e8e;
            background: transparent;
            border: 1px solid #542626;
            border-radius: 8px;
            cursor: pointer;
        }

        .logout-button:hover {
            background: #351616;
        }

        .main-content {
            min-height: 100vh;
            margin-left: 245px;
            padding: 32px;
        }

        .page-title {
            margin-bottom: 7px;
            font-size: 27px;
            font-weight: bold;
        }

        .subtitle {
            color: var(--muted);
        }

        .panel {
            padding: 24px;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 14px;
        }

        .table {
            --bs-table-bg: transparent;
            --bs-table-color: var(--text);
            --bs-table-border-color: var(--border);
            margin-bottom: 0;
            vertical-align: middle;
        }

        .table thead th {
            padding: 14px;
            color: var(--green);
            background: #0a2118;
            white-space: nowrap;
        }

        .table tbody td {
            padding: 14px;
        }

        .table tbody tr:hover td {
            background: #10251c;
        }

        .btn-back {
            display: inline-block;
            padding: 9px 16px;
            color: var(--green);
            border: 1px solid var(--green);
            border-radius: 25px;
            text-decoration: none;
        }

        .btn-back:hover {
            color: #07110c;
            background: var(--green);
        }

        .nav-btn-icon {
            padding: 8px 14px;
            color: var(--green);
            background: transparent;
            border: 1px solid var(--green);
            border-radius: 25px;
            font-size: 16px;
            line-height: 1;
            cursor: pointer;
            transition: .2s;
        }

        .nav-btn-icon:hover {
            background: #0b2b20;
        }

        .empty-state {
            padding: 30px;
            color: var(--muted);
            text-align: center;
        }

        .print-only {
            display: none;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 190px;
                padding: 20px 12px;
            }

            .main-content {
                margin-left: 190px;
                padding: 20px 12px;
            }

            .panel {
                padding: 12px;
            }
        }

        @media (max-width: 520px) {
            .sidebar {
                width: 145px;
                padding: 18px 8px;
            }

            .brand {
                font-size: 15px;
            }

            .menu-link {
                padding: 10px 8px;
                font-size: 12px;
            }

            .main-content {
                margin-left: 145px;
                padding: 16px 8px;
            }

            .page-title {
                font-size: 21px;
            }
        }

        /* ---------- Tampilan saat dicetak / disimpan sebagai PDF ---------- */
        @page {
            size: A4 portrait;
            margin: 12mm;
        }

        @media print {
            * {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            body {
                background: #fff !important;
                color: #000 !important;
            }

            .sidebar,
            .no-print,
            .alert {
                display: none !important;
            }

            .print-only {
                display: block;
                margin: 0 0 12px;
                color: #444;
                font-size: 12px;
            }

            .main-content {
                min-height: 0;
                margin-left: 0 !important;
                padding: 0 !important;
            }

            .page-title,
            .subtitle {
                color: #000 !important;
            }

            .panel {
                padding: 0;
                background: #fff;
                border: 0;
            }

            .table {
                --bs-table-color: #000;
            }

            .table th,
            .table td {
                padding: 7px 8px !important;
                color: #000 !important;
                background: #fff !important;
                border: 1px solid #999 !important;
                font-size: 12px;
            }

            .table thead th {
                background: #e8f3ee !important;
            }

            .badge {
                color: #000 !important;
                background: #fff !important;
                border: 1px solid #666;
            }

            thead {
                display: table-header-group;
            }

            tr {
                break-inside: avoid;
            }
        }
    </style>
</head>

<body>

    {{-- Sidebar --}}
    <aside class="sidebar">
        <div class="brand">INVENTORY ADMIN</div>

        <a href="{{ route('dashboard') }}" class="menu-link">
            Beranda
        </a>

        <a href="{{ route('kategori.index') }}" class="menu-link">
            Data Kategori
        </a>

        <a href="{{ route('admin.riwayat') }}" class="menu-link active">
            Riwayat Peminjaman
        </a>

        <form action="{{ route('logout') }}" method="POST" class="mt-4">
            @csrf
            <button type="submit" class="logout-button">
                Keluar
            </button>
        </form>
    </aside>

    {{-- Konten --}}
    <main class="main-content">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
            <div>
                <h1 class="page-title">Riwayat Peminjaman</h1>
                <p class="subtitle mb-0">
                    Catatan transaksi peminjaman dan pengembalian barang.
                </p>
            </div>

            <div class="d-flex gap-2 flex-wrap no-print">
                <a href="{{ route('dashboard') }}" class="btn-back">
                    Kembali
                </a>

                <button type="button" class="nav-btn-icon" onclick="window.print()"
                        title="Cetak / Simpan PDF" aria-label="Cetak">🖨️</button>
            </div>
        </div>

        <p class="print-only">Dicetak pada {{ now()->format('d/m/Y H:i') }}</p>

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <section class="panel">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Peminjam</th>
                            <th>Barang</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Kembali</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($riwayat as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <td>
                                    {{ $item->peminjam->nama
                                        ?? $item->peminjam->name
                                        ?? '-' }}
                                </td>

                                <td>
                                    {{ $item->barang->nama_barang
                                        ?? $item->barang->nama
                                        ?? '-' }}
                                </td>

                                <td>
                                    {{ $item->tgl_pinjam ?? '-' }}
                                </td>

                                <td>
                                    {{ $item->tgl_kembali ?? '-' }}
                                </td>

                                <td>
                                    @php
                                        $status = $item->status ?? 'Tidak diketahui';
                                    @endphp

                                    <span class="badge rounded-pill
                                        {{ strtolower($status) === 'dikembalikan'
                                            ? 'text-bg-success'
                                            : 'text-bg-warning' }}">
                                        {{ ucfirst($status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="empty-state">
                                    Belum ada riwayat peminjaman.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

    </main>

</body>
</html>