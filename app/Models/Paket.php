<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paket extends Model
{
    protected $table = 'pakets';
    protected $primaryKey = 'id_paket';

    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nama_paket',
        'deskripsi',
        'harga_paket',
        'gambar',
    ];

    /**
     * Tentukan kolom yang akan digunakan untuk Route Model Binding di URL.
     */
    public function getRouteKeyName()
    {
        return 'id_paket';
    }

    public function items()
    {
        return $this->belongsToMany(
            Barang::class,
            'paket_barang',   // <-- nama pivot table BENAR
            'paket_id',       // FK di pivot
            'barang_id'       // FK di pivot
        )->withPivot('qty')->withTimestamps();
    }
}