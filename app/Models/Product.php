<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'kode_produk',
        'nama_produk',
        'harga',
        'stok',
        'img',
        'deskripsi'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Accessor untuk URL gambar
    public function getImageUrlAttribute()
    {
        if ($this->img) {
            return asset('storage/' . $this->img);
        }
        return asset('img/default-product.png');
    }
    

    // Accessor untuk format harga
    public function getFormattedHargaAttribute()
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    // Scope untuk produk yang tersedia (stok > 0)
    public function scopeAvailable($query)
    {
        return $query->where('stok', '>', 0);
    }
}