<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'kategori_id', 'nama_menu', 'deskripsi', 'harga', 'stok', 'gambar'
    ];
    

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }
    public function detailOrders()
{
    return $this->hasMany(DetailOrder::class);
}

}           