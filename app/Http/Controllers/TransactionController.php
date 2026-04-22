<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        $menus = \App\Models\Menu::where('stok', '>', 0)->get();
        return view('pos.index', compact('menus'));
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'cart' => 'required|array',
            'cart.*.id' => 'required|exists:menus,id',
            'cart.*.qty' => 'required|integer|min:1',
            'cart.*.price' => 'required|integer|min:0'
        ]);

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
                $total = 0;
                $kode = 'TRX-' . date('YmdHis') . '-' . rand(100, 999);
                
                $transaction = \App\Models\Transaction::create([
                    'kode_transaksi' => $kode,
                    'tanggal' => date('Y-m-d'),
                    'total' => 0
                ]);

                foreach ($request->cart as $item) {
                    $subtotal = $item['qty'] * $item['price'];
                    $total += $subtotal;

                    \App\Models\DetailTransaction::create([
                        'transaction_id' => $transaction->id,
                        'menu_id' => $item['id'],
                        'jumlah' => $item['qty'],
                        'harga' => $item['price'],
                        'subtotal' => $subtotal
                    ]);

                    $menu = \App\Models\Menu::find($item['id']);
                    if($menu->stok < $item['qty']) {
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
        $transactions = \App\Models\Transaction::orderBy('created_at', 'desc')->get();
        return view('transactions.index', compact('transactions'));
    }
}
