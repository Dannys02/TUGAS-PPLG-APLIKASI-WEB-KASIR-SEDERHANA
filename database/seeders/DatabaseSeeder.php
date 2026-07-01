<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Dannys Martha Favrillia',
            'email' => 'danisprogaming000@gmail.com',
            'password' => Hash::make('password'),
        ]);

        // Seed data secara berurutan:
        // 1. Kategori → 2. Menu (butuh kategori) → 3. Transaksi (butuh menu)
        $this->call([
            CategorySeeder::class,
            MenuSeeder::class,
            TransactionSeeder::class,
        ]);
    }
}
