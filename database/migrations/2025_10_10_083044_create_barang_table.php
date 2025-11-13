<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('barangs', function (Blueprint $table) {
            $table->id('id_barang');
            $table->string('nama_barang');
            $table->string('jenis_barang')->nullable();
            $table->integer('stok')->nullable();
            $table->decimal('harga_sewa', 10, 2)->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('gambar')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('barangs'); 
    }
};
