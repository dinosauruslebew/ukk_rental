<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rental;
use App\Models\User;
use App\Models\Barang;
use Carbon\Carbon;

class RentalSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'user')->get();
        $barangs = Barang::all();

        // pastikan ada data dulu
        if ($users->isEmpty() || $barangs->isEmpty()) {
            $this->command->warn('⚠️ Tambahkan minimal 1 user dan 1 barang sebelum menjalankan seeder.');
            return;
        }

        foreach (range(1, 10) as $i) {
            $user = $users->random();
            $barang = $barangs->random();

            $durasi = rand(1, 5);
            $tanggalSewa = Carbon::now()->subDays(rand(0, 30));
            $tanggalKembali = (clone $tanggalSewa)->addDays($durasi);

            Rental::create([
                'user_id' => $user->id,
                'barang_id' => $barang->id,
                'tanggal_sewa' => $tanggalSewa,
                'tanggal_kembali' => $tanggalKembali,
                'durasi' => $durasi,
                'total_harga' => $barang->harga_sewa * $durasi,
                'status' => collect(['menunggu', 'aktif', 'selesai'])->random(),
                'catatan' => fake()->sentence(),
            ]);
        }

        $this->command->info('✅ Dummy data rental berhasil ditambahkan!');
    }
}
