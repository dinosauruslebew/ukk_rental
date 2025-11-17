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
        'bukti_pembayaran',
        'catatan_user',
        'catatan_admin',
    ];

    protected $casts = [
        'total_harga_pesanan' => 'integer',
    ];

    // Relasi: 1 Order dimiliki 1 User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: 1 Order punya BANYAK Item Barang
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
