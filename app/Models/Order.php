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
    ];

    public function menu()
{
    return $this->belongsTo(Menu::class);
}

}
