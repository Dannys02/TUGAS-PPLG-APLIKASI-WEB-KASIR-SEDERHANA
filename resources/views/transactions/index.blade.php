@extends('layouts.app')

@section('content')
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div
                class="flex items-center justify-center w-12 h-12 rounded-xl bg-blue-50 text-blue-600 border border-blue-100">
                <i class="fa-solid fa-receipt text-xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Riwayat Transaksi</h1>
                <p class="text-sm text-slate-500">Pantau seluruh log penjualan, ringkasan omzet bulanan, dan cetak dokumen
                    laporan kasir</p>
            </div>
        </div>
        <div class="shrink-0">
            <a href="{{ route('transactions.print', ['month' => $month, 'year' => $year]) }}"
                class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-3 px-5 rounded-xl transition-colors shadow-sm shadow-blue-500/10 flex items-center justify-center gap-2">
                <i class="fa-solid fa-file-pdf text-base"></i> Export Laporan (PDF)
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">
        <div
            class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm flex items-center justify-between transition-transform hover:-translate-y-1 duration-300 group">
            <div>
                <p class="text-slate-500 text-xs font-semibold uppercase tracking-wider mb-1.5 flex items-center gap-1.5">
                    <i class="fa-solid fa-wallet text-blue-500"></i> Total Omzet
                </p>
                <h2 class="text-2xl font-bold text-slate-800 tracking-tight group-hover:text-blue-600 transition-colors">
                    <span
                        class="text-sm font-normal text-slate-400 mr-0.5">Rp</span>{{ number_format($totalOmzet, 0, ',', '.') }}
                </h2>
            </div>
            <div
                class="bg-blue-50 p-3.5 rounded-xl text-blue-600 border border-blue-100/60 transition-colors group-hover:bg-blue-600 group-hover:text-white">
                <i class="fa-solid fa-money-bill-trend-up text-xl"></i>
            </div>
        </div>

        <div
            class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm flex items-center justify-between transition-transform hover:-translate-y-1 duration-300 group">
            <div class="w-2/3">
                <p class="text-slate-500 text-xs font-semibold uppercase tracking-wider mb-1.5 flex items-center gap-1.5">
                    <i class="fa-solid fa-fire text-orange-500"></i> Produk Terlaris
                </p>
                <div class="mt-1">
                    <h2 class="text-base font-bold text-slate-800 truncate group-hover:text-blue-600 transition-colors"
                        title="{{ $bestSeller ? $bestSeller->menu->nama_menu : 'Belum ada' }}">
                        {{ $bestSeller ? $bestSeller->menu->nama_menu : 'Belum ada' }}
                    </h2>
                    <p class="text-slate-400 text-xs truncate mt-0.5">
                        Kategori: {{ $bestSeller?->menu?->category?->nama_kategori ?? '-' }} </p>
                </div>
                @if ($bestSeller)
                    <div
                        class="inline-flex items-center gap-1 mt-2 text-xs font-bold text-green-600 bg-green-50 border border-green-100 px-2 py-0.5 rounded-md">
                        <i class="fa-solid fa-arrow-trend-up"></i> {{ $bestSeller->total_sold }} Terjual
                    </div>
                @endif
            </div>
            <div
                class="bg-green-50 p-3.5 rounded-xl text-green-600 border border-green-100/60 transition-colors group-hover:bg-green-600 group-hover:text-white shrink-0">
                <i class="fa-solid fa-award text-xl"></i>
            </div>
        </div>

        <div
            class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm flex items-center justify-between transition-transform hover:-translate-y-1 duration-300 group">
            <div>
                <p class="text-slate-500 text-xs font-semibold uppercase tracking-wider mb-1.5 flex items-center gap-1.5">
                    <i class="fa-solid fa-box-open text-amber-500"></i> Produk Terjual
                </p>
                <h2
                    class="text-2xl font-bold text-slate-800 tracking-tight group-hover:text-blue-600 transition-colors mt-1 flex items-baseline gap-1">
                    {{ $totalProductsSold ?? 0 }} <span class="text-xs font-medium text-slate-400">item</span>
                </h2>
            </div>
            <div
                class="bg-amber-50 p-3.5 rounded-xl text-amber-600 border border-amber-100/60 transition-colors group-hover:bg-amber-600 group-hover:text-white">
                <i class="fa-solid fa-boxes-stacked text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div
            class="p-5 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-50/50">
            <h2 class="text-base font-bold text-slate-800 flex items-center gap-2">
                <i class="fa-solid fa-list text-slate-400"></i> Log Riwayat Penjualan
            </h2>

            <form id="filterForm" method="GET" action="{{ route('transactions.history') }}"
                class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                <div class="flex items-center flex-1 sm:flex-none">
                    <select id="month" name="month"
                        class="w-full sm:w-auto pl-9 pr-8 py-2 border border-slate-200 rounded-xl bg-white text-slate-700 text-xs focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all cursor-pointer appearance-none"
                        onchange="document.getElementById('filterForm').submit();">
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" @if ($month == $i) selected @endif>
                                {{ $monthsList[$i] ?? 'Bulan ' . $i }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="flex items-center flex-1 sm:flex-none">
                    <select id="year" name="year"
                        class="w-full sm:w-auto pl-9 pr-8 py-2 border border-slate-200 rounded-xl bg-white text-slate-700 text-xs focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all cursor-pointer appearance-none"
                        onchange="document.getElementById('filterForm').submit();">
                        @php
                            $startYear = 2026;
                            $endYear = (int) date('Y') + 1;
                        @endphp
                        @for ($y = $endYear; $y >= $startYear; $y--)
                            <option value="{{ $y }}" @if ($year == $y) selected @endif>
                                {{ $y }}</option>
                        @endfor
                    </select>
                </div>

                @if ($month != $currentMonth || $year != $currentYear)
                    <a href="{{ route('transactions.history') }}"
                        class="text-xs font-semibold px-3 py-2 border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 rounded-xl transition-colors flex items-center justify-center gap-1.5"
                        title="Reset Periode Sekarang">
                        <i class="fa-solid fa-rotate-left"></i> <span>Reset</span>
                    </a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr
                        class="bg-slate-50 border-b border-slate-200 text-xs font-semibold text-slate-600 uppercase tracking-wider">
                        <th class="py-3.5 px-6"><i class="fa-regular fa-clock text-slate-400 mr-1"></i> Waktu Transaksi
                        </th>
                        <th class="py-3.5 px-6"><i class="fa-solid fa-hashtag text-slate-400 mr-1"></i> ID Nota</th>
                        <th class="py-3.5 px-6"><i class="fa-solid fa-money-bill-wave text-slate-400 mr-1"></i> Total
                            Bayar</th>
                        <th class="py-3.5 px-6"><i class="fa-solid fa-basket-shopping text-slate-400 mr-1"></i> Rincian Menu
                            Pembelanjaan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($transactions as $t)
                        <tr class="hover:bg-blue-50/20 transition-colors align-top">
                            <td class="py-4 px-6 text-slate-600 font-medium">
                                <div class="flex flex-col">
                                    <span
                                        class="text-slate-700 font-semibold text-sm">{{ $t->created_at->format('d M Y') }}</span>
                                    <span class="text-xs text-slate-400 mt-0.5 flex items-center gap-1">
                                        <i class="fa-regular fa-clock text-[10px]"></i> Pukul
                                        {{ $t->created_at->format('H:i') }}
                                    </span>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200 font-mono tracking-wider">
                                    {{ $t->kode_transaksi }}
                                </span>
                            </td>
                            <td class="py-4 px-6 font-bold text-blue-600 text-base tracking-tight">
                                <span
                                    class="text-xs font-normal text-slate-400 mr-0.5">Rp</span>{{ number_format($t->total, 0, ',', '.') }}
                            </td>
                            <td class="py-4 px-6">
                                <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 max-w-2xl">
                                    @foreach ($t->details as $detail)
                                        <div
                                            class="flex flex-col lg:flex-row text-xs bg-slate-50 border border-slate-200/60 p-2.5 rounded-xl transition-all hover:bg-white hover:shadow-sm">
                                            <div class="pr-2">
                                                <span
                                                    class="font-bold text-slate-700 block">{{ $detail->menu->nama_menu ?? '"Menu Terhapus"' }}</span>
                                                <span
                                                    class="text-[10px] text-slate-400 block mt-0.5">{{ $detail->menu->category->nama_kategori ?? 'Kategori Terhapus' }}</span>
                                            </div>
                                            <div class="text-left shrink-0 lg:border-l border-slate-200/80 lg:pl-2">
                                                <span
                                                    class="font-bold text-slate-700 bg-slate-200/50 text-[10px] px-1.5 py-0.5 rounded-md">{{ $detail->jumlah }}x</span>
                                                <span
                                                    class="text-slate-400 text-[10px] block mt-1">@Rp{{ number_format($detail->harga, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-20 text-center">
                                <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                                    <div
                                        class="w-14 h-14 bg-slate-50 border border-slate-100 rounded-2xl flex items-center justify-center text-slate-400 mb-3">
                                        <i class="fa-solid fa-receipt text-xl"></i>
                                    </div>
                                    <p class="text-sm font-bold text-slate-700">Data Transaksi Kosong</p>
                                    <p class="text-xs text-slate-400 mt-1">Sistem belum mendeteksi adanya mutasi riwayat
                                        belanja kasir untuk filter periode ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($transactions->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/30 overflow-x-auto">
                <div class="flex justify-center">
                    {{ $transactions->onEachSide(0)->links() }}
                </div>
            </div>
        @endif
    </div>
@endsection
