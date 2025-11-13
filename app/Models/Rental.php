<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'barang_id',
        'tanggal_sewa',
        'tanggal_kembali',
        'durasi',
        'total_harga',
        'status',
        'catatan',
    ];

    /**
     * Cast columns to proper types so dates become Carbon instances.
     * Prevents errors when calling ->toDateString() in controllers.
     *
     * @var array<string,string>
     */
    protected $casts = [
        'tanggal_sewa' => 'date',
        'tanggal_kembali' => 'date',
        'total_harga' => 'integer',
        'durasi' => 'integer',
    ];

    // relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // relasi ke barang
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
