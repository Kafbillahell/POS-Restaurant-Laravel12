<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    use HasFactory;

    protected $fillable = ['menu_id', 'diskon_persen', 'mulai', 'selesai'];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function isActive()
    {
        return now()->between($this->mulai, $this->selesai);
    }
}
