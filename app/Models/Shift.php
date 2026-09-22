<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'waktu_buka',
        'waktu_tutup',
        'modal_awal',
        'total_penjualan',
        'total_tunai',
        'kas_akhir',
        'catatan',
        'status',
    ];

    protected $casts = [
        'waktu_buka'       => 'datetime',
        'waktu_tutup'      => 'datetime',
        'modal_awal'       => 'decimal:2',
        'total_penjualan'  => 'decimal:2',
        'total_tunai'      => 'decimal:2',
        'kas_akhir'        => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function penjualans()
    {
        return $this->hasMany(Penjualan::class);
    }
}
