<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Kategori;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MenuSeeder extends Seeder
{
    /**
     * Jalankan seed database untuk mengisi data menu.
     */
    public function run(): void
    {
        // Mendapatkan ID Kategori (Penting: Pastikan KategoriSeeder sudah dijalankan lebih dahulu)
        $kategori = [
            'Makanan Utama'  => Kategori::where('nama_kategori', 'Makanan Utama')->first()->id ?? 1,
            'Minuman Panas'  => Kategori::where('nama_kategori', 'Minuman Panas')->first()->id ?? 2,
            'Minuman Dingin' => Kategori::where('nama_kategori', 'Minuman Dingin')->first()->id ?? 3,
            'Dessert & Kue'  => Kategori::where('nama_kategori', 'Dessert & Kue')->first()->id ?? 4,
            'Snack & Camilan' => Kategori::where('nama_kategori', 'Snack & Camilan')->first()->id ?? 5,
            'Paket Hemat'    => Kategori::where('nama_kategori', 'Paket Hemat')->first()->id ?? 6,
        ];
        
        // Hapus data lama di tabel 'menus' sebelum mengisi yang baru
        // Catatan: Jika Anda menggunakan Schema::disableForeignKeyConstraints() di DatabaseSeeder, truncate() akan aman
        Schema::disableForeignKeyConstraints();
        DB::table('menus')->truncate();
        Schema::enableForeignKeyConstraints();


        // Data 10 Menu
        $menus = [
            // Makanan Utama (2)
            [
                'kategori_id' => $kategori['Makanan Utama'],
                'nama_menu' => 'Nasi Goreng Spesial',
                'deskripsi' => 'Nasi goreng lengkap dengan ayam suwir, telur mata sapi, dan acar.',
                'gambar' => 'default_nasgor.jpg', // Ganti dengan nama file gambar yang sebenarnya
                'harga' => 28000,
                'stok' => 50,
            ],
            [
                'kategori_id' => $kategori['Makanan Utama'],
                'nama_menu' => 'Ayam Geprek Sambal Matah',
                'deskripsi' => 'Ayam goreng renyah digeprek dengan sambal matah pedas segar.',
                'gambar' => 'default_ayamgeprek.jpg',
                'harga' => 25000,
                'stok' => 45,
            ],
            // Minuman Dingin (2)
            [
                'kategori_id' => $kategori['Minuman Dingin'],
                'nama_menu' => 'Es Kopi Susu Aren',
                'deskripsi' => 'Kopi espresso dengan susu dan gula aren. Pilihan terlaris.',
                'gambar' => 'default_eskopsus.jpg',
                'harga' => 20000,
                'stok' => 80,
            ],
            [
                'kategori_id' => $kategori['Minuman Dingin'],
                'nama_menu' => 'Lychee Tea',
                'deskripsi' => 'Es teh segar rasa leci dengan buah leci utuh.',
                'gambar' => 'default_lycheetea.jpg',
                'harga' => 18000,
                'stok' => 70,
            ],
            // Minuman Panas (2)
            [
                'kategori_id' => $kategori['Minuman Panas'],
                'nama_menu' => 'Cappuccino',
                'deskripsi' => 'Paduan espresso dan susu steam dengan lapisan busa tebal.',
                'gambar' => 'default_cappuccino.jpg',
                'harga' => 22000,
                'stok' => 60,
            ],
            [
                'kategori_id' => $kategori['Minuman Panas'],
                'nama_menu' => 'Teh Jahe Madu',
                'deskripsi' => 'Teh hangat dengan sensasi jahe yang menghangatkan dan madu asli.',
                'gambar' => 'default_tehjahe.jpg',
                'harga' => 15000,
                'stok' => 40,
            ],
            // Dessert & Kue (1)
            [
                'kategori_id' => $kategori['Dessert & Kue'],
                'nama_menu' => 'Red Velvet Cake Slice',
                'deskripsi' => 'Potongan kue red velvet lembut dengan cream cheese frosting.',
                'gambar' => 'default_redvelvet.jpg',
                'harga' => 35000,
                'stok' => 25,
            ],
            // Snack & Camilan (2)
            [
                'kategori_id' => $kategori['Snack & Camilan'],
                'nama_menu' => 'Kentang Goreng Keju',
                'deskripsi' => 'Kentang goreng renyah disajikan dengan saus keju spesial.',
                'gambar' => 'default_kentangkeju.jpg',
                'harga' => 17000,
                'stok' => 55,
            ],
            [
                'kategori_id' => $kategori['Snack & Camilan'],
                'nama_menu' => 'Roti Bakar Cokelat Keju',
                'deskripsi' => 'Roti tebal dibakar dengan isian cokelat meses dan parutan keju.',
                'gambar' => 'default_rotibakar.jpg',
                'harga' => 20000,
                'stok' => 35,
            ],
            // Paket Hemat (1)
            [
                'kategori_id' => $kategori['Paket Hemat'],
                'nama_menu' => 'Paket Kenyang 1',
                'deskripsi' => 'Kombinasi Nasi Goreng Spesial dan Es Kopi Susu Aren (Harga Paket Hemat).',
                'gambar' => 'default_paket.jpg',
                'harga' => 45000, 
                'stok' => 30,
            ],
        ];

        // Masukkan Data
        foreach ($menus as $menu) {
            Menu::create($menu);
        }
    }
}