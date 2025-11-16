<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            // Menambahkan kolom harga khusus
            $table->integer('harga_2_malam')->nullable()->after('harga_sewa');
            $table->integer('harga_3_malam')->nullable()->after('harga_2_malam');
        });
    }

    public function down(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            $table->dropColumn(['harga_2_malam', 'harga_3_malam']);
        });
    }
};
