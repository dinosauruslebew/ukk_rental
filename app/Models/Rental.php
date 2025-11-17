<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    use HasFactory;

    /**
     * Tentukan primary key jika bukan 'id'
     * Jika primary key kamu 'id_rental', uncomment baris di bawah
     */
    // protected $primaryKey = 'id_rental';

    /**
     * Tentukan field yang boleh diisi (SESUAI MODEL KAMU)
     */
    protected $fillable = [
        'user_id',
        'barang_id',
        'tanggal_sewa',
        'tanggal_kembali',
        'durasi',
        'total_harga',
        'status',
        'catatan',
        'bukti_pembayaran',
    ];

    /**
     * Casts (SESUAI MODEL KAMU, INI BAGUS!)
     */
    protected $casts = [
        'tanggal_sewa' => 'date',
        'tanggal_kembali' => 'date',
        'total_harga' => 'integer',
        'durasi' => 'integer',
    ];

    /**
     * Relasi ke User (Satu rental dimiliki satu user)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Barang (Satu rental untuk satu barang)
     * KITA PERBAIKI DI SINI:
     * Kita kasih tau foreign key 'barang_id' nyambungnya ke 'id_barang' di tabel barangs
     */
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id', 'id_barang');
    }
}
