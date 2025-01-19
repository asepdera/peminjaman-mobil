<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mobil extends Model
{
    protected $fillable = [
        'nama',
        'merk',
        'warna',
        'plat_nomor',
        'tahun',
        'unit',
        'harga_sewa',
        'deskripsi',
        'foto',
        'status',
        'model',
    ];
    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class);
    }
}
