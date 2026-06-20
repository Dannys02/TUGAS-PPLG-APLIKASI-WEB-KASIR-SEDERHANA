<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Transaction;
use App\Models\DetailTransaction;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class TransactionController extends Controller
{
  public function index(Request $request) {
    // Mulai susun query-nya dulu (Jangan pakai ->get() di awal)
    $query = Menu::where('user_id', auth()->id())
    ->with('category')
    ->where('stok', '>', 0);

    // Jika ada input pencarian, tambahkan kondisi WHERE 'like' ke dalam query
    if ($request->filled('search')) {
      $search = $request->input('search');
      $query->where('nama_menu', 'like', "%{$search}%");
    }

    // Eksekusi query ke database di paling akhir
    $menus = $query->get();

    return view('pos.index', compact('menus'));
  }

  public function store(Request $request) {
    $request->validate([
      'cart' => 'required|array',
      'cart.*.id' => 'required|exists:menus,id',
      'cart.*.qty' => 'required|integer|min:1',
      'cart.*.price' => 'required|integer|min:0'
    ]);

    try {
      DB::transaction(function () use ($request) {
        $total = 0;
        $kode = 'TRX-' . date('YmdHis') . '-' . rand(100, 999);

        $transaction = Transaction::create([
          'kode_transaksi' => $kode,
          'tanggal' => date('Y-m-d'),
          'total' => 0,
          'user_id' => auth()->user()->id
        ]);

        foreach ($request->cart as $item) {
          $subtotal = $item['qty'] * $item['price'];
          $total += $subtotal;

          DetailTransaction::create([
            'transaction_id' => $transaction->id,
            'menu_id' => $item['id'],
            'jumlah' => $item['qty'],
            'harga' => $item['price'],
            'subtotal' => $subtotal
          ]);

          $menu = Menu::find($item['id']);
          if ($menu->stok < $item['qty']) {
            throw new \Exception('Stok menu ' . $menu->nama_menu . ' tidak cukup.');
          }
          $menu->decrement('stok', $item['qty']);
        }

        $transaction->update(['total' => $total]);
      });

      return response()->json(['success' => true,
        'message' => 'Transaksi berhasil!']);
    } catch (\Exception $e) {
      return response()->json(['success' => false,
        'message' => $e->getMessage()],
        400);
    }
  }

  public function history(Request $request) {
    // Get filter parameters dengan default bulan-tahun sekarang
    $now = Carbon::now();
    $month = $request->get('month',
      $now->month);
    $year = $request->get('year',
      $now->year);

    // Validasi input filter
    $month = max(1,
      min(12, (int)$month));
    $year = max(2020,
      min(date('Y') + 1, (int)$year));

    // Query transaksi berdasarkan filter bulan-tahun DAN user
    $transactions = Transaction::where('user_id',
      auth()->user()->id)
    ->byMonthYear($month,
      $year)
    ->orderBy('created_at',
      'desc')
    ->paginate(30);

    // Hitung statistik berdasarkan filter yang dipilih
    $totalOmzet = $this->calculateTotalOmzet($month,
      $year);
    $bestSeller = $this->getBestSellerByMonthYear($month,
      $year);
    $totalProductsSold = $this->calculateTotalProductsSold($month,
      $year);

    // Data untuk dropdown filter
    $currentMonth = $now->month;
    $currentYear = $now->year;

    // Generate list bulan untuk dropdown (12 bulan terakhir dan bulan depan)
    $monthsList = $this->generateAvailableMonths();

    return view('transactions.index',
      compact(
        'transactions',
        'totalOmzet',
        'bestSeller',
        'totalProductsSold',
        'month',
        'year',
        'currentMonth',
        'currentYear',
        'monthsList'
      ));
  }

  public function print(Request $request) {
    // Get filter parameters dengan default bulan-tahun sekarang
    $now = Carbon::now();
    $month = $request->get('month',
      $now->month);
    $year = $request->get('year',
      $now->year);

    // Validasi input filter
    $month = max(1,
      min(12, (int)$month));
    $year = max(2020,
      min(date('Y') + 1, (int)$year));

    // Query transaksi berdasarkan filter bulan-tahun DAN user
    $transactions = Transaction::where('user_id',
      auth()->user()->id)
    ->byMonthYear($month,
      $year)
    ->orderBy('created_at',
      'desc')
    ->get();

    // Hitung statistik berdasarkan filter yang dipilih
    $totalOmzet = $this->calculateTotalOmzet($month,
      $year);
    $bestSeller = $this->getBestSellerByMonthYear($month,
      $year);
    $totalProductsSold = $this->calculateTotalProductsSold($month,
      $year);

    // Generate nama file dengan bulan-tahun
    $monthName = $this->getMonthName($month);
    $fileName = "Laporan_Keuangan_{$monthName}_{$year}.pdf";

    $pdf = Pdf::loadView('transactions.print',
      compact(
        'transactions',
        'totalOmzet',
        'bestSeller',
        'totalProductsSold',
        'month',
        'year',
        'monthName'
      ));

    return $pdf->download($fileName);
  }

  /**
  * Hitung total omzet berdasarkan bulan-tahun filter DAN user
  */
  private function calculateTotalOmzet(int $month,
    int $year): int
  {
    return (int) Transaction::where('user_id',
      auth()->user()->id)
    ->byMonthYear($month,
      $year)
    ->sum('total');
  }

  /**
  * Dapatkan produk terlaris berdasarkan bulan-tahun filter DAN user
  */
  private function getBestSellerByMonthYear(int $month,
    int $year) {
    return DetailTransaction::selectRaw('menu_id, SUM(jumlah) as total_sold')
    ->whereHas('transaction',
      function ($query) use ($month, $year) {
        $query->where('user_id', auth()->user()->id)
        ->byMonthYear($month, $year);
      })
    ->groupBy('menu_id')
    ->orderByDesc('total_sold')
    ->first();
  }

  /**
  * Hitung total produk terjual berdasarkan bulan-tahun filter DAN user
  */
  private function calculateTotalProductsSold(int $month,
    int $year): int
  {
    return (int) DetailTransaction::whereHas('transaction',
      function ($query) use ($month, $year) {
        $query->where('user_id', auth()->user()->id)
        ->byMonthYear($month, $year);
      })->sum('jumlah');
  }

  /**
  * Generate list bulan yang tersedia untuk dropdown
  */
  private function generateAvailableMonths(): array
  {
    $months = [];
    for ($i = 12; $i >= 1; $i--) {
      $months[$i] = $this->getMonthName($i);
    }
    return $months;
  }

  /**
  * Get nama bulan dalam bahasa Indonesia
  */
  private function getMonthName(int $month): string
  {
    $monthNames = [
      1 => 'Januari',
      2 => 'Februari',
      3 => 'Maret',
      4 => 'April',
      5 => 'Mei',
      6 => 'Juni',
      7 => 'Juli',
      8 => 'Agustus',
      9 => 'September',
      10 => 'Oktober',
      11 => 'November',
      12 => 'Desember'
    ];
    return $monthNames[$month] ?? 'Tidak Dikenal';
  }

  public function destroy($id) {
    $transaction = Transaction::where('user_id', auth()->user()->id)->findOrFail($id);
    $transaction->delete();

    return redirect()->back()->with('success', 'Hapus transaksi berhasil');
  }
}