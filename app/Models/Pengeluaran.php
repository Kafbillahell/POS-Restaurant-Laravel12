<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    use HasFactory;

    /**
     * Properti yang dapat diisi secara massal (mass assignable attributes).
     * @var array<int, string>
     */
    protected $fillable = [
        'tanggal',
        'deskripsi',
        'jumlah',
    ];

    /**
     * Atribut yang harus di-casting ke tipe native.
     * @var array
     */
    protected $casts = [
        'tanggal' => 'date',
        'jumlah' => 'decimal:2', // Memastikan jumlah adalah float dengan 2 desimal
    ];
}