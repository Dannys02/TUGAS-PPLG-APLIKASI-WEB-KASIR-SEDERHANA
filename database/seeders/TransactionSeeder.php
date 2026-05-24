<?php

namespace Database\Seeders;

use App\Models\Transaction;
use App\Models\DetailTransaction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TransactionSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userId = 1;
        $menuId = 1;
        $transactionCount = rand(800, 1000); // 800-1000 transaksi

        for ($i = 0; $i < $transactionCount; $i++) {
            // Generate random tanggal April 2026
            $randomDay = rand(1, 30);
            $tanggal = Carbon::create(2026, 4, $randomDay);

            // Generate kode transaksi unik
            $kode = 'TRX-' . $tanggal->format('YmdHis') . '-' . rand(100, 999);

            // Hitung total dari detail transaksi yang akan dibuat
            $total = 0;
            $detailCount = rand(1, 5); // 1-5 item per transaksi

            // Create transaction
            $transaction = Transaction::create([
                'user_id' => $userId,
                'kode_transaksi' => $kode,
                'tanggal' => $tanggal->format('Y-m-d'),
                'total' => 0, // Will be updated after creating details
            ]);

            // Create detail transaksi
            for ($j = 0; $j < $detailCount; $j++) {
                $quantity = rand(1, 10);
                $harga = rand(5, 50) * 1000; // 5000 - 50000
                $subtotal = $quantity * $harga;
                $total += $subtotal;

                DetailTransaction::create([
                    'transaction_id' => $transaction->id,
                    'menu_id' => $menuId,
                    'jumlah' => $quantity,
                    'harga' => $harga,
                    'subtotal' => $subtotal,
                ]);
            }

            // Update transaction total
            $transaction->update(['total' => $total]);
        }

        $this->command->info("✅ Berhasil generate {$transactionCount} transaksi dummy untuk April 2026!");
    }
}
