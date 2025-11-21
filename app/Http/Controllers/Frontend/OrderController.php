<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    /**
     * Tampilkan halaman "Pesanan Saya" (Menggantikan RentalController lama)
     */
    public function index()
    {
        // Ambil semua pesanan (orders) milik user yg login
        // 'with('items.barang')' -> Ambil juga detail item & barangnya
        $orders = Order::where('user_id', Auth::id())
                         ->with('items.barang')
                         ->latest()
                         ->get();

        return view('frontend.order.index', compact('orders')); // Arahkan ke view baru
    }

    /**
     * Proses checkout dan simpan ke database (Menggantikan RentalController lama)
     */
    public function store(Request $request)
    {
        $cart = session()->get('cart', []);
        if (!Auth::check()) {
            return redirect()->route('login')->with('info', 'Silakan login dulu untuk melanjutkan checkout.');
        }
        if (empty($cart)) {
            return redirect()->route('frontend.produk.index')->with('error', 'Keranjang kamu kosong!');
        }

        $userId = Auth::id();
        $totalKeseluruhan = array_sum(array_column($cart, 'subtotal'));

        // Kita pakai DB Transaction biar aman
        // Kalau ada 1 barang gagal, semua pesanan dibatalkan
        try {
            DB::beginTransaction();

            // 1. Buat 1 Induk Pesanan (Order)
            $order = Order::create([
                'user_id' => $userId,
                'total_harga_pesanan' => $totalKeseluruhan,
                'status' => 'menunggu pembayaran',
            ]);

            // 2. Looping keranjang, masukkan barang ke OrderItems
            foreach ($cart as $key => $item) {
                // Cek stok lagi (Final check)
                $barang = Barang::find($item['id_barang']);
                if (!$barang || $barang->stok < $item['kuantitas']) {
                    // Jika stok kurang, batalkan semua
                    throw new \Exception("Maaf, stok '{$item['nama_barang']}' sudah habis/tidak cukup!");
                }

                // Buat Order Item
                OrderItem::create([
                    'order_id' => $order->id, // Sambungkan ke Induk Pesanan
                    'barang_id' => $item['id_barang'],
                    'kuantitas' => $item['kuantitas'],
                    'durasi' => $item['durasi'],
                    'tanggal_sewa' => $item['tanggal_mulai'],
                    'tanggal_kembali' => $item['tanggal_selesai'],
                    'nama_barang_saat_checkout' => $item['nama_barang'],
                    'harga_paket_saat_checkout' => $item['harga_paket_satuan'],
                    'subtotal' => $item['subtotal'],
                ]);

                // Kurangi stok barang
                $barang->decrement('stok', $item['kuantitas']);
            }

            // 3. Jika semua berhasil, Hapus Keranjang
            session()->forget('cart');

            // 4. Commit ke database
            DB::commit();

            // 5. Redirect ke halaman "Pesanan Saya"
            return redirect()->route('frontend.order.index')
                             ->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran DP.');

        } catch (\Exception $e) {
            // 6. Jika ada error, batalkan semua
            DB::rollBack();
            return redirect()->route('cart.index')->with('error', $e->getMessage());
        }
    }

    /**
     * Upload bukti DP (Logika sama, tapi ke Order)
     */
    public function uploadProof(Request $request, $id) // $id di sini adalah $order_id
    {
        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $order = Order::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        if ($order->bukti_pembayaran) {
            Storage::disk('public')->delete($order->bukti_pembayaran);
        }

        $path = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');

        $order->update([
            'bukti_pembayaran' => $path,
            'status' => 'menunggu konfirmasi',
        ]);

        return redirect()->back()->with('success', 'Bukti pembayaran berhasil di-upload!');
    }
}
