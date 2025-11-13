<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
Schema::create('paket_barang', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('id_paket');
    $table->unsignedBigInteger('id_barang');

    $table->foreign('id_paket')->references('id_paket')->on('paket')->cascadeOnDelete();
    $table->foreign('id_barang')->references('id_barang')->on('barangs')->cascadeOnDelete();

    $table->timestamps();
});

    }

    public function down(): void {
        Schema::dropIfExists('paket_barang');
    }
};
