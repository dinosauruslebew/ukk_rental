<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
    Schema::create('transaksi', function (Blueprint $table) {
        $table->id('no_transaksi');

        $table->unsignedBigInteger('id_pelanggan');
        $table->unsignedBigInteger('id_paket')->nullable();

        $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

        $table->date('tanggal_pinjam');
        $table->date('tanggal_kembali');
        $table->string('jaminan')->nullable();
        $table->string('dokumentasi')->nullable();
        $table->decimal('biaya', 10, 2);
        $table->decimal('denda', 10, 2)->nullable();
        $table->timestamps();

        $table->foreign('id_pelanggan')
            ->references('id_pelanggan')
            ->on('customers')
            ->cascadeOnDelete();

        $table->foreign('id_paket')
            ->references('id_paket')
            ->on('pakets')
            ->nullOnDelete();
    });


    }

    public function down(): void {
        Schema::dropIfExists('transaksi');
    }
};
