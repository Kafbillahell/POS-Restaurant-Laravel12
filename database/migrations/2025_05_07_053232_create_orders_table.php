<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id(); // Menambahkan kolom id
            $table->unsignedBigInteger('menu_id'); // Kolom menu_id
            $table->string('nama_menu'); // Kolom nama_menu
            $table->decimal('harga_menu', 10, 2); // Kolom harga_menu
            $table->string('gambar_menu')->nullable(); // Kolom gambar_menu (nullable)
            $table->timestamps(); // Kolom created_at dan updated_at

            // Menambahkan foreign key untuk menu_id
            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Menghapus tabel orders
        Schema::dropIfExists('orders');
    }
};
