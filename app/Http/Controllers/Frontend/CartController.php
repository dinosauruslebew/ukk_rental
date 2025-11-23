<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Paket;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CartController extends Controller
{
    // --- TAMPILKAN KERANJANG ---
    public function index()
    {
        $cart = session()->get('cart', []);

        $totalKeseluruhan = 0;
        foreach ($cart as $item) {
            // Hitung total (fallback ke 0 jika error data lama)
            $totalKeseluruhan += $item['subtotal'] ?? $item['total_harga'] ?? 0;
        }

        return view('frontend.cart.index', [
            'cartItems' => $cart,
            'totalKeseluruhan' => $totalKeseluruhan
        ]);
    }

    // --- TAMBAH BARANG SATUAN ---
    public function addToCart(Request $request, $id_barang)
    {
        $request->validate([
            'durasi' => 'required|integer|in:1,2,3',
            'kuantitas' => 'required|integer|min:1',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
        ]);

        $barang = Barang::findOrFail($id_barang);
        $durasi = (int) $request->durasi;
        $kuantitas = (int) $request->kuantitas;

        if ($kuantitas > $barang->stok) {
            return redirect()->back()->with('error', "Maaf, stok '{$barang->nama_barang}' hanya tersisa {$barang->stok} unit.");
        }

        $cart = session()->get('cart', []);
        $hargaPaket = $this->hitungTotalHarga($barang, $durasi);
        $subtotal = $hargaPaket * $kuantitas;

        $tanggalMulai = Carbon::parse($request->tanggal_mulai);
        $tanggalSelesai = $tanggalMulai->copy()->addDays($durasi);

        $cartItem = [
            'type' => 'barang', // Penanda tipe penting!
            'id_barang' => $barang->id_barang,
            'id_paket' => null,
            'nama_barang' => $barang->nama_barang,
            'gambar' => $barang->gambar,
            'kuantitas' => $kuantitas,
            'durasi' => $durasi,
            'tanggal_mulai' => $tanggalMulai->format('Y-m-d'),
            'tanggal_selesai' => $tanggalSelesai->format('Y-m-d'),
            'harga_paket_satuan' => $hargaPaket,
            'subtotal' => $subtotal,
        ];

        // Key unik biar barang sama beda tanggal gak numpuk
        $cartKey = 'barang_' . $barang->id_barang . '_' . $request->tanggal_mulai . '_' . $durasi;
        $cart[$cartKey] = $cartItem;
        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Barang masuk keranjang! 🛒');
    }

    // --- TAMBAH PAKET (INI YANG PENTING!) ---
    public function addPaket(Request $request, $id_paket)
    {
        // Validasi sama kayak barang
        $request->validate([
            'durasi' => 'required|integer|min:1',
            'kuantitas' => 'required|integer|min:1',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
        ]);

        // Ambil paket beserta detail item-nya
        $paket = Paket::with('items')->findOrFail($id_paket);
        $durasi = (int) $request->durasi;
        $kuantitas = (int) $request->kuantitas;

        // 1. Cek Stok Semua Barang di Dalam Paket
        foreach($paket->items as $item) {
            // Stok yang dibutuhkan = (qty di paket) * (jumlah paket yg mau disewa)
            $butuh = $item->pivot->qty * $kuantitas;

            if ($item->stok < $butuh) {
                 return redirect()->back()->with('error', "Maaf, stok barang '{$item->nama_barang}' di dalam paket ini tidak cukup.");
            }
        }

        $cart = session()->get('cart', []);

        // 2. Hitung Harga
        // Harga Paket x Durasi (Asumsi harga paket flat per hari)
        $hargaSatuan = $paket->harga_paket * $durasi;
        $subtotal = $hargaSatuan * $kuantitas;

        $tanggalMulai = Carbon::parse($request->tanggal_mulai);
        $tanggalSelesai = $tanggalMulai->copy()->addDays($durasi);

        // 3. Struktur Data Paket (Harus mirip barang biar view gak error)
        $cartItem = [
            'type' => 'paket', // Penanda tipe penting!
            'id_barang' => null,
            'id_paket' => $paket->id_paket,
            'nama_barang' => $paket->nama_paket, // Kita pakai key 'nama_barang' juga
            'gambar' => $paket->gambar,
            'kuantitas' => $kuantitas,
            'durasi' => $durasi,
            'tanggal_mulai' => $tanggalMulai->format('Y-m-d'),
            'tanggal_selesai' => $tanggalSelesai->format('Y-m-d'),
            'harga_paket_satuan' => $hargaSatuan,
            'subtotal' => $subtotal,
            // Simpan detail item buat jaga-jaga (opsional)
            'items_detail' => $paket->items->map(function($i) {
                return ['id' => $i->id_barang, 'nama' => $i->nama_barang, 'qty' => $i->pivot->qty];
            })
        ];

        $cartKey = 'paket_' . $paket->id_paket . '_' . $request->tanggal_mulai . '_' . $durasi;

        $cart[$cartKey] = $cartItem;
        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Paket berhasil masuk keranjang! 📦');
    }

    // --- HAPUS ITEM ---
    public function remove(Request $request)
    {
        $request->validate(['cartKey' => 'required|string']);

        if ($request->cartKey) {
            session()->forget("cart." . $request->cartKey);
            return redirect()->back()->with('success', 'Item dihapus.');
        }
        return redirect()->back()->with('error', 'Gagal menghapus.');
    }

    // --- HELPER HITUNG HARGA BARANG ---
    private function hitungTotalHarga($barang, $durasi) {
        if ($durasi == 2 && $barang->harga_2_malam) return $barang->harga_2_malam;
        if ($durasi == 3 && $barang->harga_3_malam) return $barang->harga_3_malam;
        return $barang->harga_sewa * $durasi;
    }

    public function rentPacketNow(Request $request, $id_paket) {
        // 1. Kosongkan keranjang lama (agar langsung checkout item ini saja)
        session()->forget('cart');

        // 2. Panggil fungsi addPaket untuk memasukkan item ini
        $this->addPaket($request, $id_paket);

        // 3. Langsung arahkan ke halaman keranjang (Checkout)
        return redirect()->route('cart.index');
    }

    public function rentNow(Request $request, $id_barang) {
        // Kosongkan keranjang lama biar fokus ke item ini
        session()->forget('cart');
        // Panggil fungsi add biasa
        $this->addToCart($request, $id_barang);
        // Langsung ke halaman keranjang
        return redirect()->route('cart.index');
    }
}
