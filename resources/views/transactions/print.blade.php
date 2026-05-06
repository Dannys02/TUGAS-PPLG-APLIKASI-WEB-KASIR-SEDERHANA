@extends('layouts.app')
@section('content')
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
        </style>
    </head>

    <body>

        <h2>Laporan Keuangan</h2>
        <p>Tanggal: {{ now()->format('d-m-Y') }}</p>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Total</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transactions as $i => $trx)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $trx->kode_transaksi }}</td>
                        <td>{{ number_format($trx->total, 0, ',', '.') }}</td>
                        <td>{{ $trx->created_at->format('d-m-Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p style="margin-top:20px;">
            <strong>Total Omzet:
                {{ number_format($totalOmzet, 0, ',', '.') }}
            </strong>
        </p>

    </body>

    </html>
@endsection
