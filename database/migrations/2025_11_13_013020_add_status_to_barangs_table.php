<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void  // <-- Saya tambahkan :void ya, ini best practice
    {
        Schema::table('barangs', function (Blueprint $table) {
            $table->string('status')->default('tersedia');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            // INI BAGIAN YANG PENTING
            // Hapus kolom 'status' jika migrasi di-rollback
            $table->dropColumn('status');
        });
    }
};
