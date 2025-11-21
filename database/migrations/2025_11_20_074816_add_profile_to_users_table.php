<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {

            // Data Diri Penyewa
            $table->string('nama_lengkap')->nullable();
            $table->string('no_telepon')->nullable();
            $table->text('alamat_lengkap')->nullable();
            $table->string('kota')->nullable();
            $table->string('provinsi')->nullable();

            // Pengecekan apakah data diri sudah lengkap
            $table->boolean('is_data_diri_complete')->default(false);

            // Catatan tambahan (opsional)
            $table->string('emergency_contact')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropColumn([
                'nama_lengkap',
                'no_telepon',
                'alamat_lengkap',
                'kota',
                'provinsi',
                'is_data_diri_complete',
                'emergency_contact',
            ]);

        });
    }
};
