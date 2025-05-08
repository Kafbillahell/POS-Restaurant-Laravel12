<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'email', 'no_telp'];
    public function orders()
{
    return $this->hasMany(Order::class);
}

    
    

}
