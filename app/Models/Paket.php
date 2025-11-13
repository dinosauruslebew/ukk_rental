<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paket extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_paket',
        'harga_paket',
        'deskripsi',
        'gambar',
    ];

    public function barang()
    {
        return $this->belongsToMany(Barang::class, 'paket_barang', 'id_paket', 'id_barang');
    }

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'id_paket');
    }
}
