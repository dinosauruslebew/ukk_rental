<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order;


class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'barang_id',
        'kuantitas',
        'durasi',
        'tanggal_sewa',
        'tanggal_kembali',
        'nama_barang_saat_checkout',
        'harga_paket_saat_checkout',
        'subtotal',
    ];

    protected $casts = [
        'kuantitas' => 'integer',
        'durasi' => 'integer',
        'harga_paket_saat_checkout' => 'integer',
        'subtotal' => 'integer',
        'tanggal_sewa' => 'date',
        'tanggal_kembali' => 'date',
    ];

    // Relasi: 1 Item milik 1 Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Relasi: 1 Item merujuk ke 1 Barang
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id', 'id_barang');
    }
}
