<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelians extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'customer_name',
        'invoice_number',
        'grand_total',
        'tanggal',
        'dibuat_oleh'
    ];

    /**
     * Relasi ke tabel detail (One to Many)
     */
    public function details()
    {
        // Pakai 'pembelian_id' sesuai hasil tinker di tabel pembelian_details
        return $this->hasMany(DetailPembelian::class, 'pembelian_id');
    }

    /**
     * Relasi Many to Many ke Product via tabel pivot
     */
    public function products()
    {
        // 1. 'pembelian_details' = tabel pivot
        // 2. 'pembelian_id' = FK model ini di pivot
        // 3. 'id_produk' = FK model Product di pivot (Hasil Tinker)
        return $this->belongsToMany(Product::class, 'pembelian_details', 'pembelian_id', 'id_produk')
                    ->withPivot('quantity', 'total_price') // Pakai total_price sesuai hasil tinker
                    ->withTimestamps();
    }

    /**
     * Relasi ke User yang membuat transaksi
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh', 'id');
    }

    /**
     * Relasi ke Member (Opsional)
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}