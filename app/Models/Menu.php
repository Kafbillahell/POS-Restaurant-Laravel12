<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 
use Illuminate\Database\Eloquent\Casts\Attribute; 
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon; 

class Menu extends Model
{
    use HasFactory, SoftDeletes; 

    protected $fillable = [
        'nama_menu',
        'deskripsi',
        'kategori_id',
        'gambar',
        'harga', 
        'harga_promo', 
        'stok',
        'stok_promo', 
        'durasi_promo_hari',
        'durasi_promo_jam', 
        'durasi_promo_menit',
        'promo_start_at',
    ];
    
    protected $casts = [
        'harga' => 'integer',
        'stok' => 'integer',
        'harga_promo' => 'integer',
        'stok_promo' => 'integer',
        'durasi_promo_hari' => 'integer',
        'durasi_promo_jam' => 'integer',
        'durasi_promo_menit' => 'integer',
        'promo_start_at' => 'datetime', 
    ];
    
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }
    
    public function detailOrders(): HasMany
    {
        return $this->hasMany(DetailOrder::class);
    }

    protected function isPromoActive(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $hasDiscount = $attributes['harga_promo'] > 0 && $attributes['harga_promo'] < $attributes['harga'];
                $hasDuration = (int)$attributes['durasi_promo_hari'] > 0 || (int)$attributes['durasi_promo_jam'] > 0 || (int)$attributes['durasi_promo_menit'] > 0;
                
                if (!$hasDiscount || !$hasDuration || is_null($this->promo_start_at)) {
                    return false; 
                }

                $endTime = $this->promo_start_at
                    ->copy()
                    ->addDays((int)$attributes['durasi_promo_hari'])
                    ->addHours((int)$attributes['durasi_promo_jam'])
                    ->addMinutes((int)$attributes['durasi_promo_menit']);

                return Carbon::now()->lessThan($endTime);
            }
        );
    }
    
    protected function hargaJual(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this->isPromoActive ? (int)$attributes['harga_promo'] : (int)$attributes['harga']
        );
    }
    
    protected function durationText(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $parts = [];
                $d = $attributes['durasi_promo_hari'];
                $h = $attributes['durasi_promo_jam'];
                $m = $attributes['durasi_promo_menit'];
                
                if ($d > 0) $parts[] = $d . ' Hari';
                if ($h > 0) $parts[] = $h . ' Jam';
                if ($m > 0) $parts[] = $m . ' Menit';
                
                return empty($parts) ? 'Nonaktif' : implode(', ', $parts);
            },
        );
    }
}