<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'username' => 'admin',
            'password' => bcrypt('admin123'),
            'full_name' => 'Admin Bengkel',
            'role' => 'admin',
            'phone' => '081200000001',
            'is_active' => true,
        ]);

        User::create([
            'username' => 'mekanik1',
            'password' => bcrypt('mekanik123'),
            'full_name' => 'Budi Mekanik',
            'role' => 'mekanik',
            'phone' => '081200000002',
            'is_active' => true,
        ]);
    }
}
