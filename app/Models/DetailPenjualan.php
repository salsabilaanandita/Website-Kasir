<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPenjualan extends Model
{
    use HasFactory;

    protected $fillable = [
        'penjualan_id',
        'product_id',
        'qty',
        'harga_satuan',
        'diskon',
        'subtotal',
    ];

    protected $casts = [
        'harga_satuan' => 'decimal:2',
        'diskon'       => 'decimal:2',
        'subtotal'     => 'decimal:2',
    ];

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
