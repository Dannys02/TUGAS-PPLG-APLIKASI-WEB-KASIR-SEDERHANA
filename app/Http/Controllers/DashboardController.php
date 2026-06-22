<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Transaction;
use App\Models\DetailTransaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $userId = auth()->id();
        $today = Carbon::today();
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Omzet Hari Ini
        $omzetHariIni = Transaction::where('user_id', $userId)
            ->whereDate('created_at', $today)
            ->sum('total'); // Sesuaikan 'total' dengan nama kolom total di tabel transaksi Anda

        // Omzet Bulan Ini
        $omzetBulanIni = Transaction::where('user_id', $userId)
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->sum('total');

        // umlah Transaksi Bulan Ini (Atau sesuaikan jika ingin jumlah transaksi hari ini)
        $jumlahTransaksi = Transaction::where('user_id', $userId)
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

        // Menu Terlaris (Diambil dari detail transaksi bulan ini)
        // Query ini mengelompokkan detail transaksi berdasarkan menu_id, menjumlahkan qty ('jumlah'), 
        // lalu mengambil 1 menu dengan jumlah tertinggi.
        $menuTerlaris = Menu::where('menus.user_id', $userId)
            ->join('detail_transactions', 'menus.id', '=', 'detail_transactions.menu_id')
            ->join('transactions', 'transactions.id', '=', 'detail_transactions.transaction_id')
            ->whereMonth('transactions.created_at', $currentMonth)
            ->whereYear('transactions.created_at', $currentYear)
            ->select('menus.*', DB::raw('SUM(detail_transactions.jumlah) as total_sold')) // Sesuaikan 'jumlah' dengan nama kolom qty Anda
            ->groupBy('menus.id')
            ->orderByDesc('total_sold')
            ->first();

        // Stok Hampir Habis (Misal batas ambang stok tipis adalah di bawah atau sama dengan 5)
        $stokHampirHabis = Menu::where('user_id', $userId)
            ->with('category') // Mengambil relasi kategori seperti yang ada di pos.index Anda
            ->where('stok', '<=', 5) // Mengambil stok yang bernilai 0 sampai 5
            ->orderBy('stok', 'asc') // Menampilkan stok yang paling kritis / kosong di atas
            ->get();

        return view('dashboard', compact(
            'omzetHariIni',
            'omzetBulanIni',
            'jumlahTransaksi',
            'menuTerlaris',
            'stokHampirHabis'
        ));
    }
}

