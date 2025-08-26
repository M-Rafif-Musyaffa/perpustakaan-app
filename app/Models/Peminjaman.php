<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang benar di database.
     *
     * @var string
     */
    protected $table = 'peminjamans'; // <-- TAMBAHKAN BARIS INI

    protected $fillable = ['anggota_id', 'tanggal_pinjam', 'tanggal_kembali', 'status'];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    public function detailPeminjaman()
    {
        return $this->hasMany(DetailPeminjaman::class);
    }
}
