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
            $table->id(); // Kolom id
            
            // Bisa nullable karena kita akan pakai kolom detail_order untuk banyak menu
            $table->unsignedBigInteger('menu_id')->nullable(); 

            // Bisa nullable juga, kalau kamu simpan data menu di detail_order JSON
            $table->string('nama_menu')->nullable();
            $table->decimal('harga_menu', 10, 2)->nullable();
            $table->string('gambar_menu')->nullable();

            $table->string('nama_pemesan'); // Nama pemesan
            $table->string('nama_kasir'); // Nama kasir

            $table->decimal('jumlah_bayar', 10, 2)->default(0); // Jumlah bayar

            // Kolom JSON untuk menyimpan detail menu dan jumlahnya
            $table->json('detail_order')->nullable();

            $table->unsignedBigInteger('user_id');

            $table->timestamps();

            // Foreign key untuk user_id
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Foreign key untuk menu_id, nullable, jadi tidak wajib
            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
