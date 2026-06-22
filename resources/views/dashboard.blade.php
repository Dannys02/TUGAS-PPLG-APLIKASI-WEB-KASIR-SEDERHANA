@extends('layouts.app')

@section('content')
    <!-- Header Dashboard -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-blue-50 text-blue-600 border border-blue-100">
                <i class="fa-solid fa-chart-pie text-xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Dashboard Utama</h1>
                <p class="text-sm text-slate-500">Ringkasan performa penjualan dan pantauan stok menu Anda</p>
            </div>
        </div>
        <div class="shrink-0">
            <a href="{{ route('pos.index') }}" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-3 px-5 rounded-xl transition-colors shadow-sm shadow-blue-500/10 flex items-center justify-center gap-2">
                <i class="fa-solid fa-cash-register text-base"></i> Buka Kasir (POS)
            </a>
        </div>
    </div>

    <!-- Grid Kartu Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
        
        <!-- Kartu 1: Omzet Hari Ini -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm flex items-center justify-between transition-transform hover:-translate-y-1 duration-300 group">
            <div>
                <p class="text-slate-500 text-xs font-semibold uppercase tracking-wider mb-1.5 flex items-center gap-1.5">
                    <i class="fa-solid fa-money-bill-wave text-green-500"></i> Omzet Hari Ini
                </p>
                <h2 class="text-2xl font-bold text-slate-800 tracking-tight group-hover:text-green-600 transition-colors">
                    <span class="text-sm font-normal text-slate-400 mr-0.5">Rp</span>{{ number_format($omzetHariIni ?? 0, 0, ',', '.') }}
                </h2>
            </div>
            <div class="bg-green-50 p-3.5 rounded-xl text-green-600 border border-green-100/60 transition-colors group-hover:bg-green-600 group-hover:text-white">
                <i class="fa-solid fa-hand-holding-dollar text-xl"></i>
            </div>
        </div>

        <!-- Kartu 2: Omzet Bulan Ini -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm flex items-center justify-between transition-transform hover:-translate-y-1 duration-300 group">
            <div>
                <p class="text-slate-500 text-xs font-semibold uppercase tracking-wider mb-1.5 flex items-center gap-1.5">
                    <i class="fa-solid fa-wallet text-blue-500"></i> Omzet Bulan Ini
                </p>
                <h2 class="text-2xl font-bold text-slate-800 tracking-tight group-hover:text-blue-600 transition-colors">
                    <span class="text-sm font-normal text-slate-400 mr-0.5">Rp</span>{{ number_format($omzetBulanIni ?? 0, 0, ',', '.') }}
                </h2>
            </div>
            <div class="bg-blue-50 p-3.5 rounded-xl text-blue-600 border border-blue-100/60 transition-colors group-hover:bg-blue-600 group-hover:text-white">
                <i class="fa-solid fa-money-bill-trend-up text-xl"></i>
            </div>
        </div>

        <!-- Kartu 3: Jumlah Transaksi -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm flex items-center justify-between transition-transform hover:-translate-y-1 duration-300 group">
            <div>
                <p class="text-slate-500 text-xs font-semibold uppercase tracking-wider mb-1.5 flex items-center gap-1.5">
                    <i class="fa-solid fa-receipt text-purple-500"></i> Total Transaksi
                </p>
                <h2 class="text-2xl font-bold text-slate-800 tracking-tight group-hover:text-purple-600 transition-colors mt-1 flex items-baseline gap-1">
                    {{ $jumlahTransaksi ?? 0 }} <span class="text-xs font-medium text-slate-400">nota</span>
                </h2>
            </div>
            <div class="bg-purple-50 p-3.5 rounded-xl text-purple-600 border border-purple-100/60 transition-colors group-hover:bg-purple-600 group-hover:text-white">
                <i class="fa-solid fa-file-invoice text-xl"></i>
            </div>
        </div>

        <!-- Kartu 4: Menu Terlaris -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm flex items-center justify-between transition-transform hover:-translate-y-1 duration-300 group">
            <div class="w-2/3">
                <p class="text-slate-500 text-xs font-semibold uppercase tracking-wider mb-1.5 flex items-center gap-1.5">
                    <i class="fa-solid fa-fire text-orange-500"></i> Menu Terlaris
                </p>
                <div class="mt-1">
                    <h2 class="text-base font-bold text-slate-800 truncate group-hover:text-orange-600 transition-colors" title="{{ $menuTerlaris ? $menuTerlaris->nama_menu : 'Belum ada' }}">
                        {{ $menuTerlaris ? $menuTerlaris->nama_menu : 'Belum ada' }}
                    </h2>
                </div>
                @if ($menuTerlaris)
                    <div class="inline-flex items-center gap-1 mt-1.5 text-[10px] font-bold text-orange-600 bg-orange-50 border border-orange-100 px-2 py-0.5 rounded-md">
                        <i class="fa-solid fa-arrow-trend-up"></i> {{ $menuTerlaris->total_sold ?? 0 }} Terjual
                    </div>
                @endif
            </div>
            <div class="bg-orange-50 p-3.5 rounded-xl text-orange-600 border border-orange-100/60 transition-colors group-hover:bg-orange-600 group-hover:text-white shrink-0">
                <i class="fa-solid fa-award text-xl"></i>
            </div>
        </div>

    </div>

    <!-- Tabel Peringatan Stok Hampir Habis -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        
        <div class="p-5 border-b border-slate-100 flex items-center gap-3 bg-slate-50/50">
            <div class="w-8 h-8 rounded-lg bg-red-50 text-red-500 flex items-center justify-center border border-red-100">
                <i class="fa-solid fa-triangle-exclamation text-sm"></i>
            </div>
            <h2 class="text-base font-bold text-slate-800">
                Peringatan Stok Hampir Habis
            </h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-xs font-semibold text-slate-600 uppercase tracking-wider">
                        <th class="py-3.5 px-6"><i class="fa-solid fa-utensils text-slate-400 mr-1"></i> Nama Menu</th>
                        <th class="py-3.5 px-6"><i class="fa-solid fa-tag text-slate-400 mr-1"></i> Kategori</th>
                        <th class="py-3.5 px-6"><i class="fa-solid fa-boxes-stacked text-slate-400 mr-1"></i> Sisa Stok</th>
                        <th class="py-3.5 px-6"><i class="fa-solid fa-gear text-slate-400 mr-1"></i> Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($stokHampirHabis as $item)
                        <tr class="hover:bg-red-50/20 transition-colors align-middle">
                            <td class="py-4 px-6">
                                <span class="font-bold text-slate-700 block">{{ $item->nama_menu }}</span>
                            </td>
                            <td class="py-4 px-6 text-slate-500">
                                {{ $item->category->nama_kategori ?? '-' }}
                            </td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold {{ $item->stok == 0 ? 'bg-red-100 text-red-700 border-red-200' : 'bg-amber-100 text-amber-700 border-amber-200' }} border">
                                    <i class="fa-solid {{ $item->stok == 0 ? 'fa-circle-xmark' : 'fa-circle-exclamation' }} mr-1.5"></i> {{ $item->stok }} Tersisa
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <!-- Silakan sesuaikan route di bawah ini ke halaman edit menu -->
                                <a href="{{ route('menus.index') }}" class="text-xs font-semibold px-3 py-2 border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 rounded-xl transition-colors inline-flex items-center gap-1.5">
                                    <i class="fa-solid fa-plus text-slate-400"></i> Tambah Stok
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-16 text-center">
                                <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                                    <div class="w-14 h-14 bg-green-50 border border-green-100 rounded-2xl flex items-center justify-center text-green-500 mb-3">
                                        <i class="fa-solid fa-check-circle text-xl"></i>
                                    </div>
                                    <p class="text-sm font-bold text-slate-700">Stok Menu Aman</p>
                                    <p class="text-xs text-slate-400 mt-1">Sistem belum mendeteksi adanya menu dengan jumlah stok yang menipis atau kosong.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
