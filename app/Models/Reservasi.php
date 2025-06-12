<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'nama_pemesan',
        'no_telp',
        'tanggal_reservasi',
        'jumlah_orang',
        'down_payment',
        'status',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
