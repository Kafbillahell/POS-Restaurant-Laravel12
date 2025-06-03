<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal', 'kasir_id', 'total_order', 'total_pendapatan', 'total_komisi_kasir', 'total_keuntungan_bersih',
    ];

    public function kasir()
    {
        return $this->belongsTo(User::class, 'kasir_id');
    }
}

