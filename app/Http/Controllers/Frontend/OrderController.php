<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Paket; // <-- PENTING: Import Model Paket
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    /**
     * Menampilkan daftar pesanan milik user.
     */
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
                         ->with(['items.barang', 'items.paket']) // Load barang & paket biar lengkap
                         ->latest()
                         ->get();

        return view('frontend.order.index', compact('orders'));
    }

    /**
     * Proses Checkout (Menyimpan Order).
     */
    public function store(Request $request)
    {
        // 1. Validasi Input User
        $request->validate([
            'no_hp' => 'required|numeric',
            'alamat' => 'required|string|max:500',
            'metode_pembayaran' => 'required|in:transfer,cod',
        ]);

        // 2. Cek Keranjang
        $cart = session()->get('cart', []);
        if (!Auth::check()) return redirect()->route('login');
        if (empty($cart)) return redirect()->route('frontend.produk.index')->with('error', 'Keranjang kamu kosong!');

        $user = Auth::user();

        // Hitung total (fallback ke 0 jika data lama error)
        $totalKeseluruhan = 0;
        foreach ($cart as $c) {
            $totalKeseluruhan += $c['subtotal'] ?? $c['total_harga'] ?? 0;
        }

        try {
            DB::beginTransaction();

            // 3. Update Data Diri User (Biar gak isi ulang terus)
            $user->update([
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
            ]);

            // 4. Tentukan Status Awal
            $statusAwal = ($request->metode_pembayaran === 'cod') ? 'menunggu konfirmasi' : 'menunggu pembayaran';
            $catatanUser = 'Metode Pembayaran: ' . strtoupper($request->metode_pembayaran);

            // 5. Buat Order Induk (Struk Utama)
            $order = Order::create([
                'user_id' => $user->id,
                'total_harga_pesanan' => $totalKeseluruhan,
                'status' => $statusAwal,
                'metode_pembayaran' => $request->metode_pembayaran,
                'catatan_user' => $catatanUser,
                'hari_terlambat' => 0,
                'total_denda' => 0,
                'total_akhir' => $totalKeseluruhan,
            ]);

            // 6. Proses Setiap Item di Keranjang
            foreach ($cart as $key => $item) {

                $barangId = null;
                $paketId = null;
                $namaItem = $item['nama_barang'] ?? 'Item';
                $hargaSatuan = $item['harga_paket_satuan'] ?? 0;

                // --- SKENARIO A: ITEM ADALAH BARANG SATUAN ---
                if (isset($item['type']) && $item['type'] == 'barang') {
                    $barang = Barang::find($item['id_barang']);

                    // Cek Stok Barang
                    if (!$barang || $barang->stok < $item['kuantitas']) {
                        throw new \Exception("Maaf, stok barang '{$namaItem}' tidak mencukupi!");
                    }

                    // Kurangi Stok Barang
                    $barang->decrement('stok', $item['kuantitas']);
                    $barangId = $barang->id_barang;
                }

                // --- SKENARIO B: ITEM ADALAH PAKET ---
                elseif (isset($item['type']) && $item['type'] == 'paket') {
                    $paket = Paket::with('items')->find($item['id_paket']);

                    if (!$paket) throw new \Exception("Maaf, paket '{$namaItem}' tidak ditemukan.");

                    // Cek Stok SEMUA Barang di dalam Paket
                    foreach($paket->items as $komponenPaket) {
                        // Rumus: (Qty per paket * Jumlah paket yg disewa)
                        $stokDibutuhkan = $komponenPaket->pivot->qty * $item['kuantitas'];

                        // Ambil data barang asli untuk cek stok real-time
                        $barangAsli = Barang::find($komponenPaket->id_barang);

                        if(!$barangAsli || $barangAsli->stok < $stokDibutuhkan) {
                             throw new \Exception("Gagal checkout! Stok barang '{$barangAsli->nama_barang}' di dalam paket ini tidak cukup.");
                        }

                        // Kurangi Stok Barang Asli
                        $barangAsli->decrement('stok', $stokDibutuhkan);
                    }

                    $paketId = $paket->id_paket;
                }

                // --- SKENARIO C: DATA LAMA (Fallback) ---
                else {
                     // Asumsikan barang biasa jika data session lama
                     $barangId = $item['id_barang'] ?? null;
                }

                // 7. Simpan ke Order Items (Detail Pesanan)
                OrderItem::create([
                    'order_id' => $order->id,
                    'barang_id' => $barangId,
                    'paket_id' => $paketId, // Simpan ID Paket (Kolom baru kita)
                    'kuantitas' => $item['kuantitas'] ?? 1,
                    'durasi' => $item['durasi'] ?? 1,
                    'tanggal_sewa' => $item['tanggal_mulai'],
                    'tanggal_kembali' => $item['tanggal_selesai'],
                    'nama_barang_saat_checkout' => $namaItem,
                    'harga_paket_saat_checkout' => $hargaSatuan,
                    'subtotal' => $item['subtotal'] ?? 0,
                ]);
            }

            // 8. Bersihkan Keranjang & Simpan Transaksi
            session()->forget('cart');
            DB::commit();

            return redirect()->route('frontend.order.index')
                             ->with('success', 'Pesanan berhasil dibuat! Silakan cek detail pesananmu.');

        } catch (\Exception $e) {
            DB::rollBack();
            // Kembalikan ke keranjang dengan pesan error
            return redirect()->route('cart.index')->with('error', $e->getMessage());
        }
    }

    /**
     * Upload bukti pembayaran (untuk metode Transfer).
     */
    public function uploadProof(Request $request, $id)
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

        return redirect()->back()->with('success', 'Bukti pembayaran berhasil di-upload! Tunggu konfirmasi admin ya.');
    }

    // Tambahan kecil: fungsi create() untuk handle redirect link lama
    public function create()
    {
        return redirect()->route('cart.index');
    }
}
