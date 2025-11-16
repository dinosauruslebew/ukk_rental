<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CartController extends Controller
{
    // Logic untuk Hitung Harga berdasarkan Durasi
    private function hitungTotalHarga($barang, $durasi)
    {
        if ($durasi == 2 && $barang->harga_2_malam) {
            return $barang->harga_2_malam;
        } elseif ($durasi == 3 && $barang->harga_3_malam) {
            return $barang->harga_3_malam;
        } else {
            // Kalau durasi > 3 atau harga paket tidak diisi, pakai harga normal * durasi
            return $barang->harga_sewa * $durasi;
        }
    }

    public function addToCart(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);
        $durasi = $request->durasi; // 1, 2, atau 3

        // Simpan data ke session 'cart'
        $cart = session()->get('cart', []);

        // Hitung total harga berdasarkan paket
        $totalHarga = $this->hitungTotalHarga($barang, $durasi);

        // Hitung tanggal selesai otomatis
        $tanggalMulai = Carbon::parse($request->tanggal_mulai);
        $tanggalSelesai = $tanggalMulai->copy()->addDays($durasi);

        // Struktur data item di keranjang
        $cartItem = [
            'id_barang' => $barang->id_barang,
            'nama_barang' => $barang->nama_barang,
            'gambar' => $barang->gambar,
            'harga_per_malam' => $barang->harga_sewa,
            'durasi' => $durasi,
            'tanggal_mulai' => $tanggalMulai->format('Y-m-d'),
            'tanggal_selesai' => $tanggalSelesai->format('Y-m-d'),
            'total_harga' => $totalHarga,
        ];

        // Gunakan ID unik (Barang + Durasi + Tanggal) biar user bisa sewa barang sama beda tanggal
        $cartKey = $barang->id_barang . '_' . $request->tanggal_mulai . '_' . $durasi;

        $cart[$cartKey] = $cartItem;

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Barang berhasil masuk keranjang! 🛒');
    }

    public function rentNow(Request $request, $id)
    {
        // Logic sewa sekarang mirip add to cart, tapi langsung redirect ke checkout
        $this->addToCart($request, $id);

        // Redirect ke halaman checkout (nanti kamu buat halaman ini)
        // Untuk sekarang kita redirect ke Keranjang dulu atau Landing
        return redirect()->route('frontend.landing')->with('success', 'Pesanan dibuat! Silakan lanjut ke pembayaran.');
    }
}
