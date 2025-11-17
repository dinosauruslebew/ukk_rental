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
        'harga_sewa',  
        'harga_2_malam',
        'harga_3_malam',
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
