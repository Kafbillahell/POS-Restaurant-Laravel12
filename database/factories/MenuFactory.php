<?php

namespace Database\Factories;

use App\Models\Menu;
use App\Models\Kategori; // Pastikan Anda mengimpor model Kategori
use Illuminate\Database\Eloquent\Factories\Factory;

class MenuFactory extends Factory
{
    protected $model = Menu::class;

    public function definition(): array
    {
        $kategoriId = Kategori::inRandomOrder()->first()->id ?? Kategori::factory()->create()->id;

        $menuName = $this->faker->words(rand(2, 4), true);

        return [

            'kategori_id' => $kategoriId,
            'nama_menu' => ucwords($menuName),
            'deskripsi' => $this->faker->sentence(10),
            'harga' => $this->faker->numberBetween(10000, 150000) / 100 * 100,
            'gambar' => 'placeholder/menu_' . $this->faker->numberBetween(1, 10) . '.jpg',
            'stok' => $this->faker->numberBetween(10, 100),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-6 months', 'now'),


        ];
    }

    public function notAvailable(): static
    {
        return $this->state(fn(array $attributes) => [
            'stok' => 0,
            'deleted_at' => $this->faker->dateTimeBetween('-3 months', 'now'),
        ]);
    }
}