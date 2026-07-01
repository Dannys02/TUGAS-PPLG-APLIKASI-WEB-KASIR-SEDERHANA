<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userId = 1;

        $categories = [
            ['nama_kategori' => 'Makanan Berat'],
            ['nama_kategori' => 'Makanan Ringan'],
            ['nama_kategori' => 'Minuman Dingin'],
            ['nama_kategori' => 'Minuman Panas'],
            ['nama_kategori' => 'Dessert'],
            ['nama_kategori' => 'Paket Hemat'],
        ];

        foreach ($categories as $category) {
            Category::create([
                'user_id' => $userId,
                'nama_kategori' => $category['nama_kategori'],
            ]);
        }

        $this->command->info("✅ Berhasil membuat " . count($categories) . " kategori!");
    }
}
