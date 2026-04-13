<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPeminjaman extends Model
{
    use HasFactory;
    protected $table = 'detail_peminjamans';
    protected $fillable = ['peminjaman_id', 'buku_id'];
    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }
}
