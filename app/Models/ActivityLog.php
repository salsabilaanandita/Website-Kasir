<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_name',
        'action',
        'modul',
        'deskripsi',
        'ip_address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Helper statis untuk mencatat aktivitas
     */
    public static function catat(string $action, string $modul = null, string $deskripsi = null)
    {
        $user = auth()->user();
        static::create([
            'user_id'    => $user?->id,
            'user_name'  => $user?->name ?? 'System',
            'action'     => $action,
            'modul'      => $modul,
            'deskripsi'  => $deskripsi,
            'ip_address' => request()->ip(),
        ]);
    }
}
