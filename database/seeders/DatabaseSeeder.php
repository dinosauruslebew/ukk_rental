<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Barang;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // jalankan seeder pengguna (admin + user biasa)
        $this->call([
            UserSeeder::class,
        ]);

        // tambahkan beberapa barang contoh bila belum ada
        if (Barang::count() === 0) {
            Barang::create([
                'nama_barang' => 'Tenda Dome 4 Orang',
                'jenis_barang' => 'Tenda',
                'stok' => 5,
                'harga_sewa' => 75000,
                'deskripsi' => 'Tenda ringan untuk 4 orang, lengkap dengan terpal dan tiang.',
                'gambar' => null,
                'foto' => null,
            ]);

            Barang::create([
                'nama_barang' => 'Kompor Gas Portable',
                'jenis_barang' => 'Kompor',
                'stok' => 8,
                'harga_sewa' => 25000,
                'deskripsi' => 'Kompor portable 1 tungku, cocok untuk memasak di luar ruangan.',
                'gambar' => null,
                'foto' => null,
            ]);

            Barang::create([
                'nama_barang' => 'Sleeping Bag - Single',
                'jenis_barang' => 'Sleeping Bag',
                'stok' => 10,
                'harga_sewa' => 20000,
                'deskripsi' => 'Sleeping bag hangat untuk cuaca dingin.',
                'gambar' => null,
                'foto' => null,
            ]);
        }

        // jalankan seeder rental untuk mengisi data rental
        $this->call([
            RentalSeeder::class,
        ]);
    }
}


