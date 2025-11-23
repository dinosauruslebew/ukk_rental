<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Barang;
use Illuminate\Http\Request;

// PASTIKAN TIDAK ADA use App\Http\Controllers\Frontend\OrderController; DI SINI

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
            'status' => 'required|string|in:menunggu konfirmasi,dikonfirmasi,disewa,selesai,dibatalkan',
        ]);

        $newStatus = $request->status;
        $oldStatus = $order->status; 

        // --- LOGIKA STOK (BARU & LENGKAP!) ---

        // 1. Jika pesanan DIBATALKAN (dan sebelumnya BELUM batal/selesai)
        if ($newStatus == 'dibatalkan' && !in_array($oldStatus, ['dibatalkan', 'selesai'])) {
            $order->loadMissing('items.barang'); 
            foreach ($order->items as $item) {
                if ($item->barang) {
                    $item->barang->increment('stok', $item->kuantitas);
                }
            }
        }

        // 2. Jika pesanan DISELESAIKAN secara manual (dan sebelumnya BELUM selesai/batal)
        elseif ($newStatus == 'selesai' && !in_array($oldStatus, ['selesai', 'dibatalkan'])) {
            $order->loadMissing('items.barang');
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

    /**
     * Memproses pengembalian barang dan mengubah status pesanan menjadi 'selesai'.
     */
    public function processReturn(Request $request, Order $order)
    {
        // 1. Validasi status harus 'disewa' untuk diproses pengembalian.
        if ($order->status !== 'disewa') {
            return redirect()->route('admin.order.show', $order->id)
                ->with('error', 'Gagal memproses pengembalian. Pesanan harus dalam status "Disewa".');
        }

        // 2. Kembalikan stok barang ke inventori
        $order->loadMissing('items.barang');
        foreach ($order->items as $item) {
            if ($item->barang) {
                $item->barang->increment('stok', $item->kuantitas);
            }
        }

        // 3. Update status pesanan menjadi 'selesai'
        $order->update(['status' => 'selesai']);

        return redirect()->route('admin.order.show', $order->id)
            ->with('success', 'Pengembalian barang berhasil diproses! Status pesanan diubah menjadi Selesai dan stok telah dikembalikan.');
    }
}