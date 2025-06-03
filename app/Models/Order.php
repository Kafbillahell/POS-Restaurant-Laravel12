<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_id',
        'nama_menu',
        'harga_menu',
        'gambar_menu',
        'nama_pemesan',
        'jumlah_bayar', 
        'nama_kasir',
        'user_id', 
         'kembalian',// ✅ tambahkan ini
    ];
    

    public function menu()
{
    return $this->belongsTo(Menu::class);
}

public function detailOrders()
{
    return $this->hasMany(DetailOrder::class);
}
public function kasir()
{
    return $this->belongsTo(User::class, 'user_id');
}


}
