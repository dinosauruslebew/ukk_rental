<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
    public function up(): void
    {
        Schema::create('paket_barang', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('paket_id');
        $table->unsignedBigInteger('barang_id');
        $table->integer('qty')->default(1);


        $table->foreign('paket_id')->references('id_paket')->on('pakets')->onDelete('cascade');
        $table->foreign('barang_id')->references('id_barang')->on('barangs')->onDelete('cascade');
        });
    }


    public function down(): void
    {
    Schema::dropIfExists('paket_barang');
    }
    };
