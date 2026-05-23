@extends('layouts.app')

@section('content')
    <div
        style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
        <h1 class="header-title" style="margin-bottom: 0;"><i class="fa-solid fa-receipt"></i> Riwayat Transaksi</h1>
        <a href="{{ route('transactions.print', ['month' => $month, 'year' => $year]) }}" class="btn btn-primary">
            <i class="fa-solid fa-file-pdf"></i> Export PDF
        </a>
    </div>

    <!-- Stats Cards -->
    <div
        style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        <!-- Card 1: Total Omzet -->
        <div class="menu-card"
            style="background: linear-gradient(135deg, rgba(212, 163, 115, 0.1), rgba(212, 163, 115, 0.05));">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <p class="text-left"
                        style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 0.5rem; font-weight: 500;">💰
                        Total Omzet</p>
                    <h2 style="color: var(--primary-dark); font-size: 2rem; font-weight: 700; margin: 0;">
                        Rp {{ number_format($totalOmzet, 0, ',', '.') }}
                    </h2>
                </div>
                <div style="font-size: 3rem; color: var(--accent-color); opacity: 0.2;">
                    <i class="fa-solid fa-money-bill-wave"></i>
                </div>
            </div>
        </div>

        <!-- Card 2: Produk Paling Laris -->
        <div class="menu-card"
            style="background: linear-gradient(135deg, rgba(30, 212, 124, 0.1), rgba(30, 212, 124, 0.05));">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <p class="text-left"
                        style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 0.5rem; font-weight: 500;">🔥
                        Produk Paling Laris</p>

                    <div style="display: flex; align-items: flex-start; flex-direction: column; gap: 0.3rem;">
                        <h2 class="text-left"
                            style="color: var(--primary-dark); font-size: 1.2rem; font-weight: 700; margin: 0;">
                            {{ $bestSeller ? $bestSeller->menu->nama_menu : 'Belum ada' }}
                        </h2>
                        <p style="color: var(--text-muted); font-size: 0.85rem; margin: 0;">
                            ({{ $bestSeller ? $bestSeller->menu->category->nama_kategori : '-' }})
                        </p>
                    </div>
                    @if ($bestSeller)
                        <p class="text-left"
                            style="color: var(--success); font-size: 0.9rem; margin-top: 0.5rem; font-weight: 600;">
                            ✓ {{ $bestSeller->total_sold }} terjual</p>
                    @endif
                </div>
                <div style="font-size: 3rem; color: var(--success); opacity: 0.2;">
                    <i class="fa-solid fa-fire"></i>
                </div>
            </div>
        </div>

        <!-- Card 3: Total Produk Terjual -->
        <div class="menu-card"
            style="background: linear-gradient(135deg, rgba(255, 189, 9, 0.1), rgba(255, 189, 9, 0.05));">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 0.5rem; font-weight: 500;">📦
                        Total Produk Terjual</p>
                    <h2 style="color: var(--primary-dark); font-size: 2rem; font-weight: 700; margin: 0;">
                        {{ $totalProductsSold ?? 0 }} <span style="font-size: 1rem;">produk</span>
                    </h2>
                </div>
                <div style="font-size: 3rem; color: var(--warning); opacity: 0.2;">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
            <h2 style="margin: 0; color: var(--primary-dark);"><i class="fa-solid fa-list"></i> Daftar Transaksi</h2>

            <!-- Filter Form -->
            <form id="filterForm" method="GET" action="{{ route('transactions.history') }}" style="display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap;">
                <div style="display: flex; gap: 0.5rem; align-items: center;">
                    <label for="month" style="font-size: 0.9rem; color: var(--text-muted); font-weight: 500;">Bulan:</label>
                    <select id="month" name="month"
                        style="padding: 0.5rem 0.75rem; border: 1px solid var(--border-color); border-radius: 4px; background-color: white; color: var(--text-dark); font-size: 0.9rem; cursor: pointer;"
                        onchange="document.getElementById('filterForm').submit();">
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" @if ($month == $i) selected @endif>
                                {{ $monthsList[$i] ?? 'Bulan ' . $i }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div style="display: flex; justify-content: space-between; gap: 0.5rem; align-items: center;">
                    <label for="year" style="font-size: 0.9rem; color: var(--text-muted); font-weight: 500;">Tahun:</label>
                    <select id="year" name="year"
                        style="padding: 0.5rem 1.75rem; border: 1px solid var(--border-color); border-radius: 4px; background-color: white; color: var(--text-dark); font-size: 0.9rem; cursor: pointer;"
                        onchange="document.getElementById('filterForm').submit();">
                        @php
                            $startYear = 2020;
                            $endYear = (int) date('Y') + 1;
                        @endphp
                        @for ($y = $endYear; $y >= $startYear; $y--)
                            <option value="{{ $y }}" @if ($year == $y) selected @endif>{{ $y }}</option>
                        @endfor
                    </select>
                </div>

                @if ($month != $currentMonth || $year != $currentYear)
                    <a href="{{ route('transactions.history') }}" style="color: var(--primary-color); text-decoration: none; font-size: 0.9rem; font-weight: 500; cursor: pointer; padding: 0.5rem 0.75rem; border: 1px solid var(--primary-color); border-radius: 4px; transition: all 0.3s ease;">
                        <i class="fa-solid fa-rotate-left"></i> Reset ke Bulan Ini
                    </a>
                @endif
            </form>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th><i class="fa-solid fa-clock"></i> Waktu</th>
                        <th><i class="fa-solid fa-hashtag"></i> Kode Transaksi</th>
                        <th><i class="fa-solid fa-money-bill"></i> Total</th>
                        <th><i class="fa-solid fa-items"></i> Detail Item</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $t)
                        <tr>
                            <td style="font-weight: 500;">{{ $t->created_at->format('d/m/Y H:i') }}</td>
                            <td><strong style="color: var(--primary-color);">{{ $t->kode_transaksi }}</strong></td>
                            <td style="color: var(--accent-color); font-weight: bold;">Rp
                                {{ number_format($t->total, 0, ',', '.') }}</td>
                            <td>
                                <ul
                                    style="padding-left: 1.2rem; color: var(--text-muted); font-size: 0.9rem; margin: 0; list-style: none;">
                                    @foreach ($t->details as $detail)
                                        <li style="padding: 0.2rem 0;">
                                            <strong
                                                style="color: var(--primary-dark);">{{ $detail->menu->nama_menu ?? 'Menu Terhapus' }}</strong><br>
                                            <span style="font-size: 0.85rem;">
                                                {{ $detail->menu->category->nama_kategori ?? '-' }} |
                                                {{ $detail->jumlah }}x Rp {{ number_format($detail->harga, 0, ',', '.') }}
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 3rem;">
                                <p style="color: var(--text-muted);">Belum ada transaksi yang tercatat.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
