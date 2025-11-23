<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_harga_pesanan',
        'status',
        'metode_pembayaran',
        'tanggal_pengembalian_aktual', // <-- Penting untuk Denda
        'hari_terlambat',              // <-- Penting untuk Denda
        'total_denda',                 // <-- Penting untuk Denda
        'total_akhir',                 // <-- Penting untuk Denda
        'bukti_pembayaran',
        'catatan_user',
        'catatan_admin',
    ];

    protected $casts = [
        'total_harga_pesanan' => 'integer',
        'total_denda' => 'integer',
        'total_akhir' => 'integer',
        'hari_terlambat' => 'integer',
        'tanggal_pengembalian_aktual' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
