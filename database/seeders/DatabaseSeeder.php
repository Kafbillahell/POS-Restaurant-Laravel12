<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Schema; 
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        $this->call([
            UserSeeder::class, 
            KategoriSeeder::class, 
            MenuSeeder::class, 
        ]);

        Schema::enableForeignKeyConstraints();
    }
}