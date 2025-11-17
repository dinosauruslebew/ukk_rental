<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id(); // ID Pesanan (misal: #1001)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            $table->integer('total_harga_pesanan');
            $table->string('status')->default('menunggu pembayaran'); // menunggu pembayaran, menunggu konfirmasi, dikonfirmasi, disewa, selesai, dibatalkan

            $table->string('bukti_pembayaran')->nullable();
            $table->text('catatan_user')->nullable();
            $table->text('catatan_admin')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
