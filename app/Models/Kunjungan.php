<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kunjungan extends Model
{
    use HasFactory;

    protected $fillable = [
        'anggota_id',
        'waktu_masuk',
        'waktu_keluar',
    ];

    // Relasi ke tabel anggota
    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }
}
