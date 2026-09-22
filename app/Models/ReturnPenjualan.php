<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnPenjualan extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_return',
        'penjualan_id',
        'product_id',
        'qty',
        'total_return',
        'alasan',
        'status',
        'user_id',
    ];

    protected $casts = [
        'total_return' => 'decimal:2',
    ];

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
