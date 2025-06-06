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
        Schema::create('reservasis', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('member_id');
        $table->dateTime('tanggal_reservasi');
        $table->integer('jumlah_orang');
        $table->text('catatan')->nullable();
        $table->timestamps();
    
        $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
    });
    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservasis');
    }
};
