<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'email',
        'no_telp',
        'points',
    ];

    // Contoh relasi jika ingin menghubungkan ke reservasi:
    public function reservasis()
    {
        return $this->hasMany(Reservasi::class);
    }
}
