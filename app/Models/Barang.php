<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_barang';

    protected $fillable = [
        'nama_barang',
        'stok',
        'harga_sewa',       // Harga 1 Malam
        'harga_2_malam',    // Harga Paket 2 Malam
        'harga_3_malam',    // Harga Paket 3 Malam
        'deskripsi',
        'gambar',
        'status',
        'kategori',
    ];

    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }
}
