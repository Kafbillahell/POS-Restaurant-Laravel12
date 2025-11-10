<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{

    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'), 
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Kasir Utama',
            'email' => 'kasir@example.com',
            'password' => Hash::make('kasir123'), 
            'role' => 'kasir', 
            'email_verified_at' => now(),
        ]);
        
        User::factory()->count(50)->create();
    }
}