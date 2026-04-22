@extends('layouts.app')

@section('content')
<h1 class="header-title">Riwayat Transaksi</h1>

<div class="card">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Kode Transaksi</th>
                    <th>Total</th>
                    <th>Detail Item</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $t)
                <tr>
                    <td>{{ $t->created_at->format('d/m/Y H:i') }}</td>
                    <td><strong>{{ $t->kode_transaksi }}</strong></td>
                    <td style="color: var(--accent-color); font-weight: bold;">Rp {{ number_format($t->total, 0, ',', '.') }}</td>
                    <td>
                        <ul style="padding-left: 1.2rem; color: var(--text-muted); font-size: 0.9rem; margin: 0;">
                            @foreach($t->details as $detail)
                                <li>{{ $detail->menu->nama_menu ?? 'Menu Terhapus' }} ({{ $detail->jumlah }}x)</li>
                            @endforeach
                        </ul>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center;">Belum ada transaksi yang tercatat.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
