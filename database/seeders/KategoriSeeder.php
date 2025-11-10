<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $kategoris = [
            ['nama_kategori' => 'Makanan Utama'],
            ['nama_kategori' => 'Minuman Panas'],
            ['nama_kategori' => 'Minuman Dingin'],
            ['nama_kategori' => 'Dessert & Kue'],
            ['nama_kategori' => 'Snack & Camilan'],
            ['nama_kategori' => 'Paket Hemat'],
        ];

        Kategori::truncate();

        foreach ($kategoris as $kategori) {
            Kategori::create($kategori);
        }
    }
}