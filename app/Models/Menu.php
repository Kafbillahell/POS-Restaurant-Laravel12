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
                
                // Amankan akses array dengan Null Coalescing Operator (?? 0)
                $hargaPromo = $attributes['harga_promo'] ?? 0;
                $hargaNormal = $attributes['harga'] ?? 0;
                $durasiHari = (int)($attributes['durasi_promo_hari'] ?? 0);
                $durasiJam = (int)($attributes['durasi_promo_jam'] ?? 0);
                $durasiMenit = (int)($attributes['durasi_promo_menit'] ?? 0);
                
                // Pengecekan 1: Diskon harus valid (promo > 0 DAN promo < harga normal)
                $hasDiscount = $hargaPromo > 0 && $hargaPromo < $hargaNormal;
                
                // Pengecekan 2: Durasi harus valid (salah satu durasi > 0)
                $hasDuration = $durasiHari > 0 || $durasiJam > 0 || $durasiMenit > 0;
                
                // Pengecekan 3: Promo harus diaktifkan/memiliki waktu mulai
                $hasStartTime = !is_null($this->promo_start_at);

                if (!$hasDiscount || !$hasDuration || !$hasStartTime) {
                    return false; 
                }

                // Hitung waktu berakhir
                $endTime = $this->promo_start_at
                    ->copy()
                    ->addDays($durasiHari)
                    ->addHours($durasiJam)
                    ->addMinutes($durasiMenit);

                // Pengecekan 4: Waktu sekarang harus kurang dari waktu berakhir
                return Carbon::now()->lessThan($endTime);
            }
        );
    }
    
    protected function hargaJual(): Attribute
    {
        // $this->isPromoActive akan memanggil accessor di atas (aman)
        return Attribute::make(
            get: fn ($value, $attributes) => $this->isPromoActive ? (int)($attributes['harga_promo'] ?? 0) : (int)($attributes['harga'] ?? 0)
        );
    }
    
    protected function durationText(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $parts = [];
                // Amankan akses array
                $d = $attributes['durasi_promo_hari'] ?? 0;
                $h = $attributes['durasi_promo_jam'] ?? 0;
                $m = $attributes['durasi_promo_menit'] ?? 0;
                
                if ($d > 0) $parts[] = $d . ' Hari';
                if ($h > 0) $parts[] = $h . ' Jam';
                if ($m > 0) $parts[] = $m . ' Menit';
                
                return empty($parts) ? 'Nonaktif' : implode(', ', $parts);
            },
        );
    }
}