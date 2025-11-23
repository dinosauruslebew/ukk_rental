<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Barang;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class ActiveLateSeeder extends Seeder
{
    public function run()
    {
        // 1. Buat User "Si Telat Banget"
        $user = User::firstOrCreate(
            ['email' => 'telat_aktif@example.com'],
            [
                'name' => 'Doni Telat (Aktif)',
                'password' => Hash::make('password'),
                'no_hp' => '081299998888',
                'alamat' => 'Jl. Karet No. 10 (Pura-pura Lupa Balikin)',
                'role' => 'user'
            ]
        );

        // 2. Buat Barang Mahal (Biar dendanya kerasa)
        $barang = Barang::firstOrCreate(
            ['nama_barang' => 'Tenda Sultan 4P (Pro)'],
            [
                'stok' => 5,
                'harga_sewa' => 100000, // 100rb per malam
                'deskripsi' => 'Tenda mahal untuk tes denda.',
                'status' => 'tersedia'
            ]
        );

        // 3. Skenario Waktu
        $durasi = 2; // Sewa 2 hari
        $kuantitas = 1;
        $hargaPerMalam = $barang->harga_sewa;

        // Sewa seminggu yang lalu
        $tglSewa = Carbon::now()->subDays(7);
        // Harusnya kembali 5 hari yang lalu (TELAT PARAH!)
        $tglHarusKembali = Carbon::now()->subDays(5);

        // Total Pesanan Awal
        $totalPesanan = $hargaPerMalam * $durasi * $kuantitas;

        // 4. Buat Order dengan status 'disewa' (SEDANG BERJALAN)
        $order = Order::create([
            'user_id' => $user->id,
            'total_harga_pesanan' => $totalPesanan,
            'status' => 'disewa', // <--- KUNCI AGAR ALERT MUNCUL
            'metode_pembayaran' => 'transfer',
            'bukti_pembayaran' => 'dummy_bukti.jpg',
            // Kolom denda masih 0 karena belum diproses admin,
            // tapi sistem view akan menghitung estimasinya secara real-time.
            'hari_terlambat' => 0,
            'total_denda' => 0,
            'total_akhir' => $totalPesanan,
        ]);

        // 5. Buat Item
        OrderItem::create([
            'order_id' => $order->id,
            'barang_id' => $barang->id_barang,
            'kuantitas' => $kuantitas,
            'durasi' => $durasi,
            'tanggal_sewa' => $tglSewa,
            'tanggal_kembali' => $tglHarusKembali, // Tanggal lampau
            'nama_barang_saat_checkout' => $barang->nama_barang,

            // PENTING: Harga ini harus ada biar kalkulator denda jalan
            'harga_paket_saat_checkout' => ($hargaPerMalam * $durasi),

            'subtotal' => $totalPesanan,
        ]);

        $this->command->info("✅ DATA DUMMY TELAT AKTIF DIBUAT!");
        $this->command->info("   Cek Order ID: #JENGKI-{$order->id}");
        $this->command->info("   Seharusnya Kembali: {$tglHarusKembali->format('d M Y')} (5 Hari Lalu)");
    }
}
