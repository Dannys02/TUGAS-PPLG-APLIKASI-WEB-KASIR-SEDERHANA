@extends('layouts.app')

@section('content')
    <h1 class="header-title">Riwayat Transaksi</h1>

    <!-- Statistics Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        <!-- Card 1: Total Omzet -->
        <div class="menu-card">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <p class="text-left" style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 0.5rem;">Total Omzet</p>
                    <h2 style="color: var(--primary-dark); font-size: 1.8rem; font-weight: 700; margin: 0;">
                        Rp {{ number_format($totalOmzet, 0, ',', '.') }}
                    </h2>
                </div>
                <div style="font-size: 2.5rem; color: var(--accent-color);">
                    <i class="fa-solid fa-money-bill-wave"></i>
                </div>
            </div>
        </div>

        <!-- Card 2: Produk Paling Laris -->
        <div class="menu-card">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 0.5rem;">Produk Paling Laris</p>
                    <h2 class="text-left" style="color: var(--primary-dark); font-size: 1.3rem; font-weight: 700; margin: 0;">
                        {{ $bestSeller ? $bestSeller->menu->nama_menu : 'Belum ada' }}
                    </h2>
                    @if ($bestSeller)
                        <p class="text-left" style="color: var(--accent-color); font-size: 0.9rem; margin-top: 0.3rem;">{{ $bestSeller->total_sold }} terjual</p>
                    @endif
                </div>
                <div style="font-size: 2.5rem; color: var(--success);">
                    <i class="fa-solid fa-fire"></i>
                </div>
            </div>
        </div>

        <!-- Card 3: Total Produk Terjual -->
        <div class="menu-card">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 0.5rem;">Total Produk Terjual</p>
                    <h2 style="color: var(--primary-dark); font-size: 1.8rem; font-weight: 700; margin: 0;">
                        {{ $totalProductsSold ?? 0 }} produk
                    </h2>
                </div>
                <div style="font-size: 2.5rem; color: var(--warning);">
                    <i class="fa-solid fa-box"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Kode Transaksi</th>
                        <th>Total</th>
                        <th>Detail Item</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $t)
                        <tr>
                            <td>{{ $t->created_at->format('d/m/Y H:i') }}</td>
                            <td><strong>{{ $t->kode_transaksi }}</strong></td>
                            <td style="color: var(--accent-color); font-weight: bold;">Rp
                                {{ number_format($t->total, 0, ',', '.') }}</td>
                            <td>
                                <ul style="padding-left: 1.2rem; color: var(--text-muted); font-size: 0.9rem; margin: 0;">
                                    @foreach ($t->details as $detail)
                                        <li>{{ $detail->menu->nama_menu ?? 'Menu Terhapus' }} ({{ $detail->menu->category->nama_kategori ?? '' }}) {{ $detail->jumlah }}x</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>
                                <form action="{{ route('pos.destroy', $t->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Hapus transaksi ini')"
                                        class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i> Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center;">Belum ada transaksi yang tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
