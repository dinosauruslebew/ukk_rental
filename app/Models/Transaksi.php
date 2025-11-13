<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_transaksi',
        'id_pelanggan',
        'id_paket',
        'user_id',
        'tanggal_pinjam',
        'tanggal_kembali',
        'biaya',
        'denda',
        'status', // menunggu, aktif, selesai, telat
    ];

    protected $dates = ['tanggal_pinjam', 'tanggal_kembali'];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_pelanggan');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function paket()
    {
        return $this->belongsTo(Paket::class, 'id_paket');
    }

    // otomatis hitung denda
    public function hitungDenda()
    {
        $hariTelat = now()->diffInDays($this->tanggal_kembali, false);
        if ($hariTelat > 0) {
            $this->denda = $hariTelat * 20000; // contoh: denda 20rb per hari
        } else {
            $this->denda = 0;
        }
    }
}
