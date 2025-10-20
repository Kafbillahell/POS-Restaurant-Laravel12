<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // ✅ Tambahkan ini
use App\Models\DetailOrder;

class Menu extends Model
{
    use HasFactory, SoftDeletes; // ✅ Pastikan SoftDeletes dipakai

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

    public function promo()
{
    return $this->hasOne(Promo::class);
}

}
