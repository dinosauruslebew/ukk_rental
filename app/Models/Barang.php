<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_barang';
    public $incrementing = true;
    protected $keyType = 'int';

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

    public function getRouteKeyName()
    {
        return 'id_barang';
    }

    protected static function booted()
    {
        static::saving(function ($barang) {
            if ($barang->stok <= 0) {
                $barang->status = 'tidak tersedia';
            } else {
                $barang->status = 'tersedia';
            }
        });
    }
}


