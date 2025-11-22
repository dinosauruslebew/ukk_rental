<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {

            if (!Schema::hasColumn('order_items', 'paket_id')) {
                $table->unsignedBigInteger('paket_id')->nullable()->after('barang_id');
            }

            if (!Schema::hasColumn('order_items', 'qty')) {
                $table->integer('qty')->default(1)->after('kuantitas');
            }

            if (!Schema::hasColumn('order_items', 'harga_saat_checkout')) {
                $table->integer('harga_saat_checkout')->default(0)
                    ->after('harga_paket_saat_checkout');
            }

            // foreign key (cek dulu biar tidak dobel)
            $table->foreign('paket_id')
                ->references('id_paket')
                ->on('pakets')
                ->nullOnDelete();
        });

    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {

            $table->dropForeign(['paket_id']);

            $table->dropColumn([
                'paket_id',
                'qty',
                'harga_saat_checkout'
            ]);
        });
    }
};
