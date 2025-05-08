<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservasi extends Model
{
    use HasFactory;

    protected $fillable = ['member_id', 'tanggal_reservasi', 'jumlah_orang', 'catatan'];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
    