<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->date('tanggal_kembali_sebenarnya')->nullable()->after('bukti_pembayaran');
            $table->integer('hari_terlambat')->default(0)->after('tanggal_kembali_sebenarnya');
            $table->integer('denda')->default(0)->after('hari_terlambat'); // simpan dalam rupiah (integer)
            $table->integer('total_akhir')->nullable()->after('denda'); // total_harga_pesanan + denda
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'tanggal_kembali_sebenarnya',
                'hari_terlambat',
                'denda',
                'total_akhir',
            ]);
        });
    }
};
