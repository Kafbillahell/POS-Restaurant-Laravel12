<?php

namespace Database\Factories;

use App\Models\Kategori;
use Illuminate\Database\Eloquent\Factories\Factory;

class KategoriFactory extends Factory
{
    protected $model = Kategori::class;

    public function definition(): array
    {
        $kategoriNames = ['Makanan Utama', 'Minuman Dingin', 'Dessert', 'Camilan', 'Kopi', 'Appetizer'];

        return [
            'nama_kategori' => $this->faker->unique()->randomElement($kategoriNames),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}