<?php

namespace Database\Seeders;

use App\Models\Menu;
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

        // Ambil semua menu milik user_id 1
        $menus = Menu::where('user_id', $userId)->get();

        if ($menus->isEmpty()) {
            $this->command->error('❌ Tidak ada menu ditemukan! Jalankan CategorySeeder dan MenuSeeder terlebih dahulu.');
            return;
        }

        $transactionCount = 300; // minimal 100 transaksi

        // Rentang tanggal: 6 bulan terakhir (Maret 2026 - Juli 2026)
        // Agar data tersebar realistis di berbagai bulan
       $startDate = Carbon::create(2026, 3, 1, 0, 0, 0);
        $endDate   = Carbon::create(2026, 7, 1, 23, 59, 59);
        $totalDays = $startDate->diffInDays($endDate);

        for ($i = 0; $i < $transactionCount; $i++) {
            // Generate tanggal acak dalam rentang 6 bulan
            $randomDaysOffset = rand(0, $totalDays);
            $tanggal = $startDate->copy()->addDays($randomDaysOffset);

            // Tambahkan jam acak agar created_at bervariasi
            $tanggal->setHour(rand(8, 21))    // jam operasional 08:00-21:00
                    ->setMinute(rand(0, 59))
                    ->setSecond(rand(0, 59));

            // Jaga-jaga agar tidak ada tanggal yang kebablasan melewati 1 Juli malam akibat penambahan jam
            if ($tanggal->greaterThan($endDate)) {
                $tanggal = $endDate->copy()->setHour(rand(8, 21))->setMinute(rand(0, 59));
            }

            // Generate kode transaksi unik
            $kode = 'TRX-' . $tanggal->format('Ymd') . '-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT);

            // Tentukan jumlah item per transaksi (1-5 item)
            $detailCount = rand(1, 5);

            // Pilih menu acak tanpa duplikasi dalam satu transaksi
            $selectedMenus = $menus->random(min($detailCount, $menus->count()));

            // Hitung total transaksi
            $total = 0;
            $details = [];

            foreach ($selectedMenus as $menu) {
                $jumlah   = rand(1, 5);
                $harga    = $menu->harga;
                $subtotal = $jumlah * $harga;
                $total   += $subtotal;

                $details[] = [
                    'menu_id'  => $menu->id,
                    'jumlah'   => $jumlah,
                    'harga'    => $harga,
                    'subtotal' => $subtotal,
                ];
            }

            // Buat transaksi
            $transaction = Transaction::create([
                'user_id'         => $userId,
                'kode_transaksi'  => $kode,
                'tanggal'         => $tanggal->format('Y-m-d'),
                'total'           => $total,
                'created_at'      => $tanggal,
                'updated_at'      => $tanggal,
            ]);

            // Buat detail transaksi
            foreach ($details as $detail) {
                DetailTransaction::create([
                    'transaction_id' => $transaction->id,
                    'menu_id'        => $detail['menu_id'],
                    'jumlah'         => $detail['jumlah'],
                    'harga'          => $detail['harga'],
                    'subtotal'       => $detail['subtotal'],
                    'created_at'     => $tanggal,
                    'updated_at'     => $tanggal,
                ]);
            }
        }

        $this->command->info("✅ Berhasil membuat {$transactionCount} transaksi (Maret - Juli 2026) dengan detail yang terhubung ke menu!");
    }
}
