<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Jika tabel sudah dibuat oleh migration sebelumnya, tambahkan kolom yang belum ada.
        if (Schema::hasTable('rentals')) {
            Schema::table('rentals', function (Blueprint $table) {
                if (!Schema::hasColumn('rentals', 'user_id')) {
                    $table->foreignId('user_id')
                          ->constrained('users')
                          ->onDelete('cascade');
                }

                if (!Schema::hasColumn('rentals', 'barang_id')) {
                    // barangs menggunakan primary key `id_barang`
                    $table->unsignedBigInteger('barang_id')->nullable();
                    $table->foreign('barang_id')->references('id_barang')->on('barangs')->cascadeOnDelete();
                }

                if (!Schema::hasColumn('rentals', 'tanggal_sewa')) {
                    $table->date('tanggal_sewa')->nullable();
                }

                if (!Schema::hasColumn('rentals', 'tanggal_kembali')) {
                    $table->date('tanggal_kembali')->nullable();
                }

                if (!Schema::hasColumn('rentals', 'durasi')) {
                    $table->integer('durasi')->default(1);
                }

                if (!Schema::hasColumn('rentals', 'total_harga')) {
                    $table->bigInteger('total_harga')->default(0);
                }

                if (!Schema::hasColumn('rentals', 'status')) {
                    $table->enum('status', [
                        'menunggu',
                        'aktif',
                        'selesai',
                        'batal'
                    ])->default('menunggu');
                }

                if (!Schema::hasColumn('rentals', 'catatan')) {
                    $table->text('catatan')->nullable();
                }

                if (!Schema::hasColumn('rentals', 'created_at')) {
                    $table->timestamps();
                }
            });
        } else {
            // Jika tabel belum ada, buat baru (skenario fresh install)
            Schema::create('rentals', function (Blueprint $table) {
                $table->id();

                $table->foreignId('user_id')
                      ->constrained('users')
                      ->onDelete('cascade');

                // barangs primary key adalah id_barang
                $table->unsignedBigInteger('barang_id');
                $table->foreign('barang_id')->references('id_barang')->on('barangs')->cascadeOnDelete();

                $table->date('tanggal_sewa')->nullable();
                $table->date('tanggal_kembali')->nullable();
                $table->integer('durasi')->default(1);
                $table->bigInteger('total_harga')->default(0);
                $table->enum('status', ['menunggu', 'aktif', 'selesai', 'batal'])->default('menunggu');
                $table->text('catatan')->nullable();

                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        // Hapus kolom yang ditambahkan oleh migration ini jika ada.
        if (Schema::hasTable('rentals')) {
            Schema::table('rentals', function (Blueprint $table) {
                if (Schema::hasColumn('rentals', 'catatan')) {
                    $table->dropColumn('catatan');
                }
                if (Schema::hasColumn('rentals', 'status')) {
                    $table->dropColumn('status');
                }
                if (Schema::hasColumn('rentals', 'total_harga')) {
                    $table->dropColumn('total_harga');
                }
                if (Schema::hasColumn('rentals', 'durasi')) {
                    $table->dropColumn('durasi');
                }
                if (Schema::hasColumn('rentals', 'tanggal_kembali')) {
                    $table->dropColumn('tanggal_kembali');
                }
                if (Schema::hasColumn('rentals', 'tanggal_sewa')) {
                    $table->dropColumn('tanggal_sewa');
                }
                if (Schema::hasColumn('rentals', 'barang_id')) {
                    // drop foreign if exists
                    try {
                        $table->dropForeign(['barang_id']);
                    } catch (\Throwable $e) {
                        // ignore if foreign doesn't exist
                    }
                    $table->dropColumn('barang_id');
                }
                if (Schema::hasColumn('rentals', 'user_id')) {
                    try {
                        $table->dropForeign(['user_id']);
                    } catch (\Throwable $e) {
                        // ignore
                    }
                    $table->dropColumn('user_id');
                }
            });
        }
    }
};
