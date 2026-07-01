<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userId = 1;

        // Ambil semua kategori milik user_id 1
        $categories = Category::where('user_id', $userId)->pluck('id', 'nama_kategori');

        // Data menu per kategori
        $menus = [
            // Makanan Berat
            'Makanan Berat' => [
                ['nama_menu' => 'Nasi Goreng Spesial',   'harga' => 25000, 'stok' => 50, 'deskripsi' => 'Nasi goreng dengan telur, ayam, dan sayuran segar'],
                ['nama_menu' => 'Mie Ayam Bakso',        'harga' => 20000, 'stok' => 40, 'deskripsi' => 'Mie ayam dengan bakso sapi pilihan'],
                ['nama_menu' => 'Ayam Geprek Sambal',    'harga' => 22000, 'stok' => 35, 'deskripsi' => 'Ayam crispy geprek dengan sambal pedas'],
            ],

            // Makanan Ringan
            'Makanan Ringan' => [
                ['nama_menu' => 'Kentang Goreng',        'harga' => 15000, 'stok' => 60, 'deskripsi' => 'Kentang goreng crispy dengan saus sambal dan mayo'],
                ['nama_menu' => 'Pisang Goreng Keju',    'harga' => 12000, 'stok' => 45, 'deskripsi' => 'Pisang goreng dengan taburan keju dan susu kental manis'],
                ['nama_menu' => 'Tahu Crispy',           'harga' => 10000, 'stok' => 55, 'deskripsi' => 'Tahu goreng crispy dengan bumbu rempah'],
            ],

            // Minuman Dingin
            'Minuman Dingin' => [
                ['nama_menu' => 'Es Teh Manis',          'harga' => 5000,  'stok' => 100, 'deskripsi' => 'Teh manis dingin segar dengan es batu'],
                ['nama_menu' => 'Jus Jeruk Segar',       'harga' => 12000, 'stok' => 40, 'deskripsi' => 'Jus jeruk segar tanpa pengawet'],
                ['nama_menu' => 'Es Kopi Susu',          'harga' => 18000, 'stok' => 50, 'deskripsi' => 'Kopi robusta dengan susu segar dan gula aren'],
            ],

            // Minuman Panas
            'Minuman Panas' => [
                ['nama_menu' => 'Kopi Hitam',            'harga' => 8000,  'stok' => 80, 'deskripsi' => 'Kopi hitam tubruk khas Jawa'],
                ['nama_menu' => 'Teh Hangat',            'harga' => 5000,  'stok' => 90, 'deskripsi' => 'Teh hangat manis dengan aroma melati'],
                ['nama_menu' => 'Coklat Panas',          'harga' => 15000, 'stok' => 45, 'deskripsi' => 'Coklat panas premium dengan whipped cream'],
            ],

            // Dessert
            'Dessert' => [
                ['nama_menu' => 'Puding Coklat',         'harga' => 10000, 'stok' => 30, 'deskripsi' => 'Puding coklat lembut dengan saus vanilla'],
                ['nama_menu' => 'Es Krim Vanilla',       'harga' => 12000, 'stok' => 25, 'deskripsi' => 'Es krim vanilla premium dengan topping coklat'],
            ],

            // Paket Hemat
            'Paket Hemat' => [
                ['nama_menu' => 'Paket Nasi Ayam + Teh', 'harga' => 28000, 'stok' => 30, 'deskripsi' => 'Nasi ayam geprek + Es Teh Manis, hemat Rp4.000'],
                ['nama_menu' => 'Paket Mie + Jus',       'harga' => 30000, 'stok' => 25, 'deskripsi' => 'Mie Ayam Bakso + Jus Jeruk Segar, hemat Rp2.000'],
            ],
        ];

        $totalMenus = 0;

        foreach ($menus as $categoryName => $items) {
            $categoryId = $categories[$categoryName] ?? null;

            if (!$categoryId) {
                $this->command->warn("⚠️  Kategori '{$categoryName}' tidak ditemukan, skip...");
                continue;
            }

            foreach ($items as $item) {
                Menu::create([
                    'user_id'     => $userId,
                    'nama_menu'   => $item['nama_menu'],
                    'kategori_id' => $categoryId,
                    'harga'       => $item['harga'],
                    'stok'        => $item['stok'],
                    'deskripsi'   => $item['deskripsi'],
                ]);
                $totalMenus++;
            }
        }

        $this->command->info("✅ Berhasil membuat {$totalMenus} menu di " . count($menus) . " kategori!");
    }
}
