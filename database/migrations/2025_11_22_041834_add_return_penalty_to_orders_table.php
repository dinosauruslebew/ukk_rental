<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Cek dulu biar gak error kalau kolomnya udah ada
            if (!Schema::hasColumn('orders', 'tanggal_pengembalian_aktual')) {
                $table->date('tanggal_pengembalian_aktual')->nullable()->after('status');
            }
            if (!Schema::hasColumn('orders', 'hari_terlambat')) {
                $table->integer('hari_terlambat')->default(0)->after('tanggal_pengembalian_aktual');
            }
            if (!Schema::hasColumn('orders', 'total_denda')) {
                $table->integer('total_denda')->default(0)->after('hari_terlambat');
            }
            if (!Schema::hasColumn('orders', 'total_akhir')) {
                $table->integer('total_akhir')->nullable()->after('total_denda');
            }
            // Pastikan kolom metode_pembayaran ada (untuk fitur COD/Transfer)
            if (!Schema::hasColumn('orders', 'metode_pembayaran')) {
                $table->string('metode_pembayaran')->default('transfer')->after('total_harga_pesanan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'tanggal_pengembalian_aktual',
                'hari_terlambat',
                'total_denda',
                'total_akhir',
                'metode_pembayaran'
            ]);
        });
    }
};
