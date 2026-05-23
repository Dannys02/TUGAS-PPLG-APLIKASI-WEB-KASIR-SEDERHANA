<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan</title>

    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        h2 {
            text-align: center;
        }

        .report-header {
            text-align: center;
            margin-bottom: 15px;
        }

        .report-period {
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        .stats-section {
            margin-top: 30px;
            margin-bottom: 30px;
            page-break-inside: avoid;
        }

        .stat-item {
            margin-bottom: 8px;
            padding: 8px;
            border: 1px solid #ccc;
            background-color: #fafafa;
        }

        .stat-label {
            font-weight: bold;
            display: inline-block;
            width: 40%;
        }

        .stat-value {
            display: inline-block;
            width: 55%;
            text-align: right;
        }
    </style>
</head>

<body>

    <div class="report-header">
        <h2>LAPORAN KEUANGAN</h2>
    </div>

    <div class="report-period">
        Periode: {{ $monthName }} {{ $year }}
    </div>

    <!-- Statistics Section -->
    <div class="stats-section">
        <h3 style="text-align: center; margin-bottom: 15px;">Ringkasan Transaksi</h3>

        <div class="stat-item">
            <span class="stat-label">Total Omzet:</span>
            <span class="stat-value">Rp {{ number_format($totalOmzet, 0, ',', '.') }}</span>
        </div>

        @if ($bestSeller)
            <div class="stat-item">
                <span class="stat-label">Produk Paling Laris:</span>
                <span class="stat-value">{{ $bestSeller->menu->nama_menu }} ({{ $bestSeller->total_sold }}x)</span>
            </div>
        @endif

        <div class="stat-item">
            <span class="stat-label">Total Produk Terjual:</span>
            <span class="stat-value">{{ $totalProductsSold }} produk</span>
        </div>
    </div>

    <!-- Transactions Table -->
    <h3 style="margin-bottom: 10px;">Detail Transaksi</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Transaksi</th>
                <th>Tanggal</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transactions as $i => $trx)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $trx->kode_transaksi }}</td>
                    <td>{{ $trx->created_at->format('d-m-Y H:i') }}</td>
                    <td style="text-align: right;">Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center; padding: 15px;">
                        Tidak ada transaksi untuk periode ini
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 30px; text-align: right; font-size: 11px; color: #999;">
        <p>Laporan dibuat pada: {{ now()->format('d-m-Y H:i') }}</p>
    </div>

</body>

</html>
