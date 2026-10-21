<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Riwayat Peminjaman dan Pengembalian</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
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
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .badge-live {
            background: rgba(0, 212, 138, 0.08);
            border: 1.5px solid rgba(0, 212, 138, 0.25);
            color: var(--teal);
        }

        .footer-dark {
            background: #040a07;
            border-top: 2px solid;
            border-image: linear-gradient(90deg, var(--teal), var(--gold), var(--rose)) 1;
        }

        .table-card {
            border-radius: 20px;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--bs-border-color);
        }

        .table {
            --bs-table-bg: transparent;
            --bs-table-color: var(--bs-body-color);
            --bs-table-border-color: var(--bs-border-color);
            --bs-table-hover-bg: rgba(0, 212, 138, 0.05);
            --bs-table-hover-color: var(--bs-body-color);
        }

        .table thead th {
            color: var(--teal);
            font-size: 0.75rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 16px;
            border-bottom: 1px solid rgba(0, 212, 138, 0.25);
            white-space: nowrap;
        }

        .table tbody td { padding: 14px 16px; }

        .badge-status { font-weight: 600; border: 1px solid; }
        .badge-teal { background: rgba(0, 212, 138, 0.1); border-color: rgba(0, 212, 138, 0.35); color: var(--teal); }
        .badge-gold { background: rgba(245, 166, 35, 0.1); border-color: rgba(245, 166, 35, 0.4); color: var(--gold); }
        .badge-rose { background: rgba(240, 90, 126, 0.1); border-color: rgba(240, 90, 126, 0.4); color: var(--rose); }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

    <main class="flex-grow-1 container py-5">

        <a href="{{ route('peminjaman.index') }}" class="text-secondary text-decoration-none small">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>

        <div class="mt-3 mb-4">
            <span class="badge rounded-pill badge-live px-3 py-2 mb-3">Riwayat &middot; 2026</span><br>
            <h1 class="display-6 fw-bold text-gradient d-inline-block mb-2">Riwayat Peminjaman dan Pengembalian</h1>
            <p class="text-secondary mb-0" style="max-width: 560px;">
                Pantau transaksi yang berjalan dan yang sudah selesai. Nama peminjam disamarkan untuk menjaga privasi.
            </p>
        </div>

        <div class="table-card">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Peminjam</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Kembali</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($riwayat as $row)
                            @php
                                if ($row->tgl_kembali) {
                                    $status = 'Selesai';
                                    $kelas  = 'badge-teal';
                                } elseif ($row->tgl_jatuh_tempo && $row->tgl_jatuh_tempo->isPast()) {
                                    $status = 'Terlambat';
                                    $kelas  = 'badge-rose';
                                } else {
                                    $status = 'Dipinjam';
                                    $kelas  = 'badge-gold';
                                }
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $row->barang->nama_barang ?? '-' }}</td>
                                <td>{{ $row->nama_tampil }}</td>
                                <td>{{ $row->tgl_pinjam?->format('d/m/Y H:i') }}</td>
                                <td>
                                    @if ($row->tgl_kembali)
                                        {{ $row->tgl_kembali->format('d/m/Y H:i') }}
                                    @else
                                        <span class="text-secondary">-</span>
                                    @endif
                                </td>
                                <td><span class="badge rounded-pill badge-status {{ $kelas }}">{{ $status }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-secondary py-5">
                                    Belum ada transaksi peminjaman.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <p class="text-secondary small mt-3 mb-0">Total {{ $riwayat->count() }} transaksi.</p>

    </main>

    <footer class="footer-dark text-center py-4 mt-auto">
        <small class="text-secondary">&copy; 2026 Inventaris Barang. Semua hak dilindungi.</small>
    </footer>

</body>
</html>