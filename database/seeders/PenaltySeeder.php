<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Barang;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class PenaltySeeder extends Seeder
{
    public function run()
    {
        // 1. USER (yang telat mengembalikan)
        $user = User::firstOrCreate(
            ['email' => 'telat@example.com'],
            [
                'name' => 'Budi Tukang Telat',
                'password' => Hash::make('password'),
                'no_hp' => '081234567890',
                'alamat' => 'Jl. Keterlambatan No. 5, Semarang',
                'role' => 'user'
            ]
        );

        // 2. BARANG dummy
        $barang = Barang::firstOrCreate(
            ['nama_barang' => 'Tenda Sultan 4P (Dummy)'],
            [
                'stok' => 10,
                'harga_sewa' => 50000,
                'deskripsi' => 'Tenda khusus untuk testing keterlambatan.',
                'status' => 'tersedia'
            ]
        );

        // 3. SKENARIO WAKTU
        $durasi = 2;
        $kuantitas = 1;

        $tglSewa = Carbon::now()->subDays(5);        // Sewa 5 hari yang lalu
        $tglHarusKembali = Carbon::now()->subDays(3); // Harusnya kembali 3 hari lalu
        $tglKembaliAktual = Carbon::now();           // Tapi baru kembali hari ini

        $hariTerlambat = $tglKembaliAktual->diffInDays($tglHarusKembali); // = 3 hari

        // 4. HITUNGAN UANG
        $hargaPerHari = $barang->harga_sewa;
        $totalPesanan = $hargaPerHari * $durasi * $kuantitas; // 100.000

        // Denda = harga harian * lama telat * qty
        $denda = ($hargaPerHari * $hariTerlambat * $kuantitas); // 150.000

        $totalAkhir = $totalPesanan + $denda; // 250.000

        // 5. ORDER (sudah selesai, jadi denda tersimpan)
        $order = Order::create([
            'user_id' => $user->id,
            'total_harga_pesanan' => $totalPesanan,
            'total_denda' => $denda,
            'total_akhir' => $totalAkhir,
            'hari_terlambat' => $hariTerlambat,
            'tanggal_pengembalian_aktual' => $tglKembaliAktual,
            'metode_pembayaran' => 'transfer',
            'status' => 'selesai',
            'catatan_admin' => "Denda telat {$hariTerlambat} hari (Dummy Testing).",
            'bukti_pembayaran' => 'dummy_bukti.jpg'
        ]);

        // 6. ORDER ITEM
        OrderItem::create([
            'order_id' => $order->id,
            'barang_id' => $barang->id_barang, // penting!
            'kuantitas' => $kuantitas,
            'durasi' => $durasi,
            'tanggal_sewa' => $tglSewa,
            'tanggal_kembali' => $tglHarusKembali,
            'nama_barang_saat_checkout' => $barang->nama_barang,
            'harga_paket_saat_checkout' => ($hargaPerHari * $durasi),
            'subtotal' => $totalPesanan,
        ]);

        $this->command->info("=======================================");
        $this->command->info("  ✔ Dummy denda berhasil dibuat!");
        $this->command->info("  Order ID : ORDER-{$order->id}");
        $this->command->info("  Penyewa  : {$user->name}");
        $this->command->info("  Telat    : {$hariTerlambat} hari");
        $this->command->info("  Denda    : Rp " . number_format($denda));
        $this->command->info("=======================================");
    }
}
