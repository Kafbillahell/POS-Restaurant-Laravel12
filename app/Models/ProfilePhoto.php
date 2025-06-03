<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfilePhoto extends Model
{
    protected $fillable = ['user_id', 'photo_path','filter'];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
