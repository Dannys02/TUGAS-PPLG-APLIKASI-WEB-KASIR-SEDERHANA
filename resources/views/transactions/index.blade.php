@extends('layouts.app')

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-3">
            <div class="bg-blue-100 text-blue-600 px-4 py-1.5 rounded-lg">
                <i class="fa-solid fa-receipt text-2xl"></i>
            </div>
            Riwayat Transaksi
        </h1>
        <p class="text-gray-500 mt-1">Lihat dan filter semua transaksi penjualan.</p>
    </div>
    <div>
        <a href="{{ route('transactions.print', ['month' => $month, 'year' => $year]) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-5 rounded-lg transition-colors shadow-sm flex items-center gap-2">
            <i class="fa-solid fa-file-pdf"></i> Export PDF
        </a>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Card 1: Total Omzet -->
    <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-6 shadow-sm flex items-center justify-between transition-transform hover:-translate-y-1 duration-300">
        <div>
            <p class="text-blue-800 text-sm font-medium mb-1 flex items-center gap-1.5">
                <i class="fa-solid fa-wallet"></i> Total Omzet
            </p>
            <h2 class="text-3xl font-bold text-gray-900 mt-1">
                Rp {{ number_format($totalOmzet, 0, ',', '.') }}
            </h2>
        </div>
        <div class="bg-white/60 p-4 rounded-full text-blue-500 shadow-inner">
            <i class="fa-solid fa-money-bill-trend-up text-3xl"></i>
        </div>
    </div>

    <!-- Card 2: Produk Paling Laris -->
    <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-xl p-6 shadow-sm flex items-center justify-between transition-transform hover:-translate-y-1 duration-300">
        <div class="w-2/3">
            <p class="text-green-800 text-sm font-medium mb-1 flex items-center gap-1.5">
                <i class="fa-solid fa-fire text-orange-500"></i> Produk Terlaris
            </p>
            <div class="mt-1">
                <h2 class="text-xl font-bold text-gray-900 truncate" title="{{ $bestSeller ? $bestSeller->menu->nama_menu : 'Belum ada' }}">
                    {{ $bestSeller ? $bestSeller->menu->nama_menu : 'Belum ada' }}
                </h2>
                <p class="text-gray-500 text-xs truncate">
                    {{ $bestSeller ? $bestSeller->menu->category->nama_kategori : '-' }}
                </p>
            </div>
            @if ($bestSeller)
                <p class="text-green-700 text-sm font-semibold mt-2 flex items-center gap-1">
                    <i class="fa-solid fa-arrow-trend-up"></i> {{ $bestSeller->total_sold }} terjual
                </p>
            @endif
        </div>
        <div class="bg-white/60 p-4 rounded-full text-green-500 shadow-inner shrink-0">
            <i class="fa-solid fa-award text-3xl"></i>
        </div>
    </div>

    <!-- Card 3: Total Produk Terjual -->
    <div class="bg-gradient-to-br from-amber-50 to-amber-100 border border-amber-200 rounded-xl p-6 shadow-sm flex items-center justify-between transition-transform hover:-translate-y-1 duration-300">
        <div>
            <p class="text-amber-800 text-sm font-medium mb-1 flex items-center gap-1.5">
                <i class="fa-solid fa-box-open"></i> Produk Terjual
            </p>
            <h2 class="text-3xl font-bold text-gray-900 mt-1 flex items-baseline gap-2">
                {{ $totalProductsSold ?? 0 }} <span class="text-sm font-medium text-gray-500">item</span>
            </h2>
        </div>
        <div class="bg-white/60 p-4 rounded-full text-amber-500 shadow-inner">
            <i class="fa-solid fa-boxes-stacked text-3xl"></i>
        </div>
    </div>
</div>

<!-- Transactions Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-gray-50/50">
        <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
            <i class="fa-solid fa-list text-gray-400"></i> Daftar Transaksi
        </h2>

        <!-- Filter Form -->
        <form id="filterForm" method="GET" action="{{ route('transactions.history') }}" class="flex flex-wrap md:flex-nowrap items-center gap-3">
            <div class="flex items-center gap-2">
                <label for="month" class="text-sm font-medium text-gray-600 hidden sm:block">Bulan</label>
                <select id="month" name="month"
                    class="rounded-lg border-gray-300 border py-2 pl-3 pr-8 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-sm cursor-pointer"
                    onchange="document.getElementById('filterForm').submit();">
                    @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" @if ($month == $i) selected @endif>
                            {{ $monthsList[$i] ?? 'Bulan ' . $i }}
                        </option>
                    @endfor
                </select>
            </div>

            <div class="flex items-center gap-2">
                <label for="year" class="text-sm font-medium text-gray-600 hidden sm:block">Tahun</label>
                <select id="year" name="year"
                    class="rounded-lg border-gray-300 border py-2 pl-3 pr-8 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-sm cursor-pointer"
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
                <a href="{{ route('transactions.history') }}" class="text-blue-600 bg-blue-50 hover:bg-blue-100 border border-blue-200 px-3 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-1.5" title="Reset ke Bulan Ini">
                    <i class="fa-solid fa-rotate-left"></i> <span class="hidden sm:inline">Reset</span>
                </a>
            @endif
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 text-sm">
                    <th class="py-3 px-6 font-semibold text-gray-600 w-40"><i class="fa-regular fa-clock mr-1"></i> Waktu</th>
                    <th class="py-3 px-6 font-semibold text-gray-600 w-48"><i class="fa-solid fa-hashtag mr-1"></i> Kode</th>
                    <th class="py-3 px-6 font-semibold text-gray-600 w-40"><i class="fa-solid fa-money-bill-wave mr-1"></i> Total</th>
                    <th class="py-3 px-6 font-semibold text-gray-600"><i class="fa-solid fa-receipt mr-1"></i> Detail Item</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($transactions as $t)
                    <tr class="hover:bg-blue-50/30 transition-colors">
                        <td class="py-4 px-6 text-sm text-gray-600 font-medium">
                            <div class="flex flex-col">
                                <span>{{ $t->created_at->format('d M Y') }}</span>
                                <span class="text-xs text-gray-400">{{ $t->created_at->format('H:i') }}</span>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-gray-100 text-gray-700 font-mono tracking-wider">
                                {{ $t->kode_transaksi }}
                            </span>
                        </td>
                        <td class="py-4 px-6 font-bold text-blue-700">
                            Rp {{ number_format($t->total, 0, ',', '.') }}
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex flex-col gap-2">
                                @foreach ($t->details as $detail)
                                    <div class="flex items-start justify-between text-sm bg-gray-50 p-2 rounded border border-gray-100">
                                        <div>
                                            <span class="font-semibold text-gray-800">{{ $detail->menu->nama_menu ?? 'Menu Terhapus' }}</span>
                                            <span class="text-xs text-gray-500 block">{{ $detail->menu->category->nama_kategori ?? '-' }}</span>
                                        </div>
                                        <div class="text-right whitespace-nowrap ml-4">
                                            <span class="font-medium text-gray-700">{{ $detail->jumlah }}x</span>
                                            <span class="text-gray-500 text-xs block">Rp {{ number_format($detail->harga, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-16 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <div class="bg-gray-100 p-5 rounded-full mb-4 text-gray-400">
                                    <i class="fa-solid fa-receipt text-4xl"></i>
                                </div>
                                <p class="text-xl font-medium text-gray-700 mb-1">Belum ada transaksi</p>
                                <p class="text-sm">Belum ada transaksi yang tercatat untuk periode ini.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
