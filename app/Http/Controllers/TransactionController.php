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
    public function index()
    {
        $menus = Menu::with('category')->where('stok', '>', 0)->get();
        return view('pos.index', compact('menus'));
    }

    public function store(Request $request)
    {
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
                    'total' => 0
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

            return response()->json(['success' => true, 'message' => 'Transaksi berhasil!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    public function history()
    {
        $now = Carbon::now();
        // $transactions = Transaction::orderBy('created_at', 'desc')->get();
        $transactions = Transaction::whereMonth('created_at', $now->month)
        ->whereYear('created_at', $now->year)
        ->orderBy('created_at', 'desc')
        ->get();

        $totalOmzet = Transaction::sum('total');

        $bestSeller = DetailTransaction::selectRaw('menu_id, SUM(jumlah) as total_sold')
            ->groupBy('menu_id')
            ->orderByDesc('total_sold')
            ->first();

        $totalProductsSold = DetailTransaction::sum('jumlah');

        return view('transactions.index', compact('transactions', 'totalOmzet', 'bestSeller', 'totalProductsSold'));
    }

    public function print()
    {
        $transactions = Transaction::latest()->get();

        $totalOmzet = Transaction::sum('total');

        $bestSeller = DetailTransaction::selectRaw('menu_id, SUM(jumlah) as total_sold')
            ->groupBy('menu_id')
            ->orderByDesc('total_sold')
            ->first();

        $totalProductsSold = DetailTransaction::sum('jumlah');

        $date = Carbon::now()->format('d_m_Y');
        $fileName = "laporan_keuangan_{$date}.pdf";

        $pdf = Pdf::loadView('transactions.print', compact(
            'transactions',
            'totalOmzet',
            'bestSeller',
            'totalProductsSold'
        ));

        return $pdf->download($fileName);
    }

    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->delete();

        return redirect()->back()->with('success', 'Hapus transaksi berhasil');
    }
}
