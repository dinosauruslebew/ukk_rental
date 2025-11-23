<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'barang_id',
        'paket_id', 
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
        'subtotal' => 'integer',
        'tanggal_sewa' => 'date',
        'tanggal_kembali' => 'date',
    ];

    public function order() { return $this->belongsTo(Order::class); }
    public function barang() { return $this->belongsTo(Barang::class, 'barang_id', 'id_barang'); }
    // Relasi Paket (Opsional jika ingin ambil detail paket nanti)
    public function paket() { return $this->belongsTo(Paket::class, 'paket_id', 'id_paket'); }
}
