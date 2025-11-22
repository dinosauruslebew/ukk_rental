<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Paket;
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
    if (!Auth::check()) {
        return redirect()->route('login')
            ->with('info', 'Silakan login dulu untuk melanjutkan checkout.');
    }

    $userId = Auth::id();

    // ============================
    // 1) CHECKOUT PAKET
    // ============================
    if ($request->type === 'paket') {

        $request->validate([
            'paket_id' => 'required|exists:paket,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        $paket = Paket::with('items')->findOrFail($request->paket_id);

        // Hitung total harga paket
        $totalHarga = $paket->harga_paket;

        try {
            DB::beginTransaction();

            // 1. Buat order
            $order = Order::create([
                'user_id' => $userId,
                'total_harga_pesanan' => $totalHarga,
                'status' => 'menunggu pembayaran',
            ]);

            // 2. Loop barang dalam paket & buat OrderItem
            foreach ($paket->items as $paketItem) {

                // Cek stok
                $barang = Barang::findOrFail($paketItem->id_barang);

                if ($barang->stok < $paketItem->pivot->qty) {
                    throw new \Exception("Stok {$barang->nama_barang} tidak cukup untuk paket!");
                }

                // Buat order_items
                OrderItem::create([
                    'order_id' => $order->id,
                    'barang_id' => $barang->id_barang,
                    'kuantitas' => $paketItem->pivot->qty,
                    'durasi' => null,
                    'tanggal_sewa' => $request->tanggal_mulai,
                    'tanggal_kembali' => $request->tanggal_selesai,
                    'nama_barang_saat_checkout' => $barang->nama_barang,
                    'harga_paket_saat_checkout' => null,
                    'subtotal' => 0,
                ]);

                // Kurangi stok
                $barang->decrement('stok', $paketItem->pivot->qty);
            }

            DB::commit();

            return redirect()->route('frontend.order.index')
                ->with('success', 'Paket berhasil disewa!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    // ============================
    // 2) CHECKOUT BARANG SATUAN
    // ============================
    $cart = session()->get('cart', []);
    if (empty($cart)) {
        return redirect()->route('frontend.produk.index')
            ->with('error', 'Keranjang kamu kosong!');
    }

    $total = array_sum(array_column($cart, 'subtotal'));

    try {
        DB::beginTransaction();

        // 1. Buat order
        $order = Order::create([
            'user_id' => $userId,
            'total_harga_pesanan' => $total,
            'status' => 'menunggu pembayaran',
        ]);

        // 2. Tambahkan order items
        foreach ($cart as $item) {

            $barang = Barang::find($item['id_barang']);
            if (!$barang || $barang->stok < $item['kuantitas']) {
                throw new \Exception("Stok {$item['nama_barang']} habis!");
            }

            OrderItem::create([
                'order_id' => $order->id,
                'barang_id' => $item['id_barang'],
                'kuantitas' => $item['kuantitas'],
                'durasi' => $item['durasi'],
                'tanggal_sewa' => $item['tanggal_mulai'],
                'tanggal_kembali' => $item['tanggal_selesai'],
                'nama_barang_saat_checkout' => $item['nama_barang'],
                'harga_paket_saat_checkout' => $item['harga_paket_satuan'],
                'subtotal' => $item['subtotal'],
            ]);

            // Kurangi stok
            $barang->decrement('stok', $item['kuantitas']);
        }

        session()->forget('cart');
        DB::commit();

        return redirect()->route('frontend.order.index')
            ->with('success', 'Pesanan berhasil dibuat!');

    } catch (\Exception $e) {
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
