<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_transaksi',
        'user_id',
        'member_id',
        'shift_id',
        'subtotal',
        'diskon',
        'poin_digunakan',
        'total',
        'bayar',
        'kembalian',
        'metode_bayar',
        'status',
        'kasir_nama',
    ];

    protected $casts = [
        'subtotal'       => 'decimal:2',
        'diskon'         => 'decimal:2',
        'poin_digunakan' => 'decimal:2',
        'total'          => 'decimal:2',
        'bayar'          => 'decimal:2',
        'kembalian'      => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function details()
    {
        return $this->hasMany(DetailPenjualan::class);
    }

    public function returns()
    {
        return $this->hasMany(ReturnPenjualan::class);
    }
}
