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
        Schema::create('detail_orders', function (Blueprint $table) {
    $table->id();
    $table->foreignId('order_id')->constrained()->onDelete('cascade');
    $table->foreignId('menu_id')->constrained()->onDelete('cascade');
    $table->string('nama_menu');    // Backup nama menu
    $table->integer('harga_menu');  // Backup harga menu
    $table->integer('jumlah');
    $table->integer('subtotal');
    $table->timestamps();
    $table->softDeletes();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_orders');
    }
};
