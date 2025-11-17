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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');

            // Kita pakai nullable() dan onDelete('set null')
            // Jadi walau barang dihapus, history pesanan tetap ada
            $table->foreignId('barang_id')->nullable()->constrained('barangs', 'id_barang')->onDelete('set null');

            $table->integer('kuantitas'); // Misal: 2 (biji)
            $table->integer('durasi'); // Misal: 3 (malam)

            $table->date('tanggal_sewa');
            $table->date('tanggal_kembali');

            // Mencatat harga pas checkout (biar nggak berubah kalau harga barang naik)
            $table->string('nama_barang_saat_checkout');
            $table->integer('harga_paket_saat_checkout'); // harga satuan paket (misal 30k/2malam)
            $table->integer('subtotal'); // (kuantitas * harga_paket)

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
