<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Barang;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Menampilkan halaman daftar semua pesanan (orders)
     */
    public function index(Request $request)
    {
        $statuses = [
            'semua' => 'Semua',
            'menunggu pembayaran' => 'Menunggu Pembayaran',
            'menunggu konfirmasi' => 'Menunggu Konfirmasi',
            'dikonfirmasi' => 'Dikonfirmasi',
            'disewa' => 'Disewa',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
        ];

        $query = Order::with('user')->latest();

        $activeStatus = 'semua';
        if ($request->filled('status') && $request->status != 'semua') {
            $query->where('status', $request->status);
            $activeStatus = $request->status;
        }

        $orders = $query->get();

        return view('admin.order.index', compact('orders', 'statuses', 'activeStatus'));
    }

    /**
     * Menampilkan detail satu pesanan
     */
    public function show(Order $order)
    {
        $order->load(['items.barang', 'user']);
        return view('admin.order.show', compact('order'));
    }

    /**
     * Mengupdate status pesanan
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|string|in:dikonfirmasi,disewa,selesai,dibatalkan',
        ]);

        $newStatus = $request->status;
        $oldStatus = $order->status; // Simpan status lama

        // --- LOGIKA STOK (BARU & LENGKAP!) ---

        // 1. Jika pesanan DIBATALKAN (dan sebelumnya BELUM batal/selesai)
        //    (Stok dikunci, tapi dibatalin -> Balikin!)
        if ($newStatus == 'dibatalkan' && !in_array($oldStatus, ['dibatalkan', 'selesai'])) {
            foreach ($order->items as $item) {
                if ($item->barang) {
                    $item->barang->increment('stok', $item->kuantitas);
                }
            }
        }

        // 2. (INI PERBAIKAN DARI KAMU!)
        //    Jika pesanan DISELESAIKAN (dan sebelumnya BELUM selesai/batal)
        //    (Barang udah kembali -> Balikin!)
        elseif ($newStatus == 'selesai' && !in_array($oldStatus, ['selesai', 'dibatalkan'])) {
            foreach ($order->items as $item) {
                if ($item->barang) {
                    $item->barang->increment('stok', $item->kuantitas);
                }
            }
        }

        // 3. Update status pesanan (order)
        $order->update(['status' => $newStatus]);

        return redirect()->route('admin.order.show', $order->id)->with('success', 'Status pesanan berhasil diperbarui!');
    }
}
