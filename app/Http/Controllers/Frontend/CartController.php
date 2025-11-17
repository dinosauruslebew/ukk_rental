<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CartController extends Controller
{
    // --- FUNGSI BARU: Tampilkan Halaman Keranjang ---
    public function index()
    {
        $cart = session()->get('cart', []);

        $totalKeseluruhan = 0;
        foreach ($cart as $item) {
            // --- PERBAIKAN ERROR DI SINI ---
            // Kita cek, kalau ada 'subtotal' (sistem baru) pakai itu.
            // Kalau nggak ada, kita cari 'total_harga' (sistem lama).
            // Kalau nggak ada juga, anggap aja 0.
            $totalKeseluruhan += $item['subtotal'] ?? $item['total_harga'] ?? 0;
        }

        return view('frontend.cart.index', [ // Pastikan file ini ada
            'cartItems' => $cart,
            'totalKeseluruhan' => $totalKeseluruhan
        ]);
    }

    // --- FUNGSI DIROMBAK: Paham Kuantitas ---
// ... (sisa file CartController.php kamu SAMA PERSIS kayak sebelumnya) ...
    public function addToCart(Request $request, $id_barang)
    {
        // 1. Validasi Input
        $request->validate([
            'durasi' => 'required|integer|in:1,2,3',
            'kuantitas' => 'required|integer|min:1',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
        ]);

        $barang = Barang::findOrFail($id_barang);
        $durasi = (int) $request->durasi;
        $kuantitas = (int) $request->kuantitas;

        // 2. Cek Stok
        if ($kuantitas > $barang->stok) {
            return redirect()->back()->with('error', "Maaf, stok '{$barang->nama_barang}' hanya tersisa {$barang->stok} unit.");
        }

        // 3. Ambil keranjang
        $cart = session()->get('cart', []);

        // 4. Hitung harga paket & tanggal
        $hargaPaket = $this->hitungTotalHarga($barang, $durasi);
        $subtotal = $hargaPaket * $kuantitas;
        $tanggalMulai = Carbon::parse($request->tanggal_mulai);
        $tanggalSelesai = $tanggalMulai->copy()->addDays($durasi); // Tanggal kembali adalah tanggal mulai + durasi

        // 5. Buat Item Keranjang
        $cartItem = [
            'id_barang' => $barang->id_barang,
            'nama_barang' => $barang->nama_barang,
            'gambar' => $barang->gambar,
            'kuantitas' => $kuantitas, // <-- BARU!
            'durasi' => $durasi,
            'tanggal_mulai' => $tanggalMulai->format('Y-m-d'),
            'tanggal_selesai' => $tanggalSelesai->format('Y-m-d'),
            'harga_paket_satuan' => $hargaPaket, // 30k (utk 2 malam)
            'subtotal' => $subtotal, // (30k * 2 biji) = 60k
        ];

        // 6. Buat Kunci Unik
        // (Barang yg sama, di tanggal yg sama, dgn durasi yg sama = 1 item)
        $cartKey = $barang->id_barang . '_' . $request->tanggal_mulai . '_' . $durasi;

        // 7. Masukkan ke Session
        $cart[$cartKey] = $cartItem; // Langsung timpa/update jika key-nya sama
        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Barang berhasil masuk keranjang! 🛒');
    }

    // --- FUNGSI "SEWA SEKARANG" (Update) ---
    public function rentNow(Request $request, $id_barang)
    {
        // 1. Validasi Input (Sama kayak addToCart)
        $request->validate([
            'durasi' => 'required|integer|in:1,2,3',
            'kuantitas' => 'required|integer|min:1',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
        ]);

        $barang = Barang::findOrFail($id_barang);
        $kuantitas = (int) $request->kuantitas;

        // 2. Cek Stok
        if ($kuantitas > $barang->stok) {
            return redirect()->back()->with('error', "Maaf, stok '{$barang->nama_barang}' hanya tersisa {$barang->stok} unit.");
        }

        // 3. Kosongkan keranjang lama
        session()->forget('cart');

        // 4. Panggil logic addToCart untuk 1 item ini
        $this->addToCart($request, $id_barang);

        // 5. Langsung lempar ke halaman keranjang (checkout)
        return redirect()->route('cart.index');
    }

    // --- FUNGSI HAPUS ITEM (Tetap) ---
    public function remove(Request $request)
    {
        $request->validate(['cartKey' => 'required|string']);

        if ($request->cartKey) {
            session()->forget("cart." . $request->cartKey);
            return redirect()->back()->with('success', 'Barang berhasil dihapus.');
        }
        return redirect()->back()->with('error', 'Gagal menghapus barang.');
    }

    // --- FUNGSI HITUNG HARGA (Tetap) ---
    private function hitungTotalHarga($barang, $durasi)
    {
        if ($durasi == 2 && $barang->harga_2_malam) {
            return $barang->harga_2_malam;
        } elseif ($durasi == 3 && $barang->harga_3_malam) {
            return $barang->harga_3_malam;
        } else {
            // Jika durasi 1 malam, atau paket null, atau durasi > 3
            // Kita anggap > 3 pakai harga harian (misal 4 malam = 4 * harga_sewa)
            return $barang->harga_sewa * $durasi;
        }
    }
}
