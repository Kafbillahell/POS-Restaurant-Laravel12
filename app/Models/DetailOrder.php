<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_id',
        'menu_id',
        'nama_menu',
        'harga_menu',
        'jumlah',
        'subtotal',
    ];

    protected $dates = ['deleted_at'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // ✅ INI YANG WAJIB ADA UNTUK MENGHILANGKAN ERROR
    public function menu()
    {
        return $this->belongsTo(Menu::class)->withTrashed(); // jika menu menggunakan SoftDeletes
    }
}
