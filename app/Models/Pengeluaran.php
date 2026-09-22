<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'keterangan',
        'jumlah',
        'kategori',
        'tanggal',
        'user_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah'  => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
