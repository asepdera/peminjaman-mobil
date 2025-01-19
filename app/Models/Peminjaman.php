<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';
    protected $fillable = [
        'mobil_id',
        'user_id',
        'tanggal_peminjaman',
        'tanggal_pengembalian',
        'status',
        'alasan',
        'total_harga',
        'denda',
        'catatan',
    ];

    public function mobil()
    {
        return $this->belongsTo(Mobil::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
