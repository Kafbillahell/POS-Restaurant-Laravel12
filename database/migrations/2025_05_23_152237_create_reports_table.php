<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsTable extends Migration
{
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal'); // bisa harian atau penanda bulan
            $table->foreignId('kasir_id')->constrained('users')->onDelete('cascade');

            // ringkasan data
            $table->integer('total_order')->default(0); // total order dari kasir tsb
            $table->integer('total_pendapatan')->default(0); // total uang masuk dari pesanan kasir ini

            // pembagian keuntungan
            $table->integer('total_komisi_kasir')->default(0); // 20% untuk kasir dari harga menu
            $table->integer('total_keuntungan_bersih')->default(0); // sisanya untuk admin

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reports');
    }
}
