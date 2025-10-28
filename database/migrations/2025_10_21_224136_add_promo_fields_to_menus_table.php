<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            
            if (!Schema::hasColumn('menus', 'durasi_promo_hari')) {
                $table->unsignedSmallInteger('durasi_promo_hari')->default(0)->after('stok_promo');
            }
            if (!Schema::hasColumn('menus', 'durasi_promo_jam')) {
                $table->unsignedTinyInteger('durasi_promo_jam')->default(0)->after('durasi_promo_hari');
            }
            if (!Schema::hasColumn('menus', 'durasi_promo_menit')) {
                $table->unsignedTinyInteger('durasi_promo_menit')->default(0)->after('durasi_promo_jam');
            }

            if (!Schema::hasColumn('menus', 'promo_start_at')) {
                $table->timestamp('promo_start_at')->nullable()->after('durasi_promo_menit');
            }
        });
    }

    public function down(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            $columnsToDrop = [
                'harga_promo', 
                'stok_promo',
                'durasi_promo_hari',
                'durasi_promo_jam',
                'durasi_promo_menit',
                'promo_start_at',
            ];

            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('menus', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};