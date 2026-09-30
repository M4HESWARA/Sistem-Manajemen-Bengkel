<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     *
     * Urutan penting: UserSeeder harus jalan lebih dulu karena
     * DemoDataSeeder mereferensikan user admin & mekanik.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            DemoDataSeeder::class,
        ]);
    }
}
