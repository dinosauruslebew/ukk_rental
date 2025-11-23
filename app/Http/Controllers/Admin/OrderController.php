<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Barang;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OrderController extends Controller
{
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

    public function show(Order $order)
    {
        $order->load(['items.barang', 'user']);
        return view('admin.order.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|string|in:dikonfirmasi,disewa,dibatalkan',
        ]);

        $newStatus = $request->status;

        // Jika Dibatalkan, kembalikan stok
        if ($newStatus == 'dibatalkan' && $order->status != 'dibatalkan') {
            foreach ($order->items as $item) {
                if ($item->barang) {
                    $item->barang->increment('stok', $item->kuantitas);
                }
            }
        }

        $order->update(['status' => $newStatus]);

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui!');
    }

    /**
     * LOGIKA HITUNG DENDA & PENGEMBALIAN (SINKRON DENGAN DATABASE)
     */
    public function processReturn(Request $request, Order $order)
    {
        $request->validate([
            'tanggal_kembali_aktual' => 'required|date',
        ]);

        $tglAktual = Carbon::parse($request->tanggal_kembali_aktual);
        $totalDenda = 0;
        $maxHariTelat = 0;

        // 1. Loop barang untuk balikin stok & hitung denda
        foreach ($order->items as $item) {
            // Kembalikan Stok
            if ($item->barang) {
                $item->barang->increment('stok', $item->kuantitas);
            }

            // Cek Keterlambatan
            $tglHarusKembali = Carbon::parse($item->tanggal_kembali);

            if ($tglAktual->gt($tglHarusKembali)) {
                // Hitung selisih hari
                $hariTelat = $tglAktual->diffInDays($tglHarusKembali);

                if ($hariTelat > $maxHariTelat) {
                    $maxHariTelat = $hariTelat;
                }

                // Rumus Denda: (Harga Harian Barang x Kuantitas) x Hari Telat
                $hargaPerHariSatuan = $item->harga_paket_saat_checkout / $item->durasi;
                $dendaItem = ($hargaPerHariSatuan * $item->kuantitas) * $hariTelat;

                $totalDenda += $dendaItem;
            }
        }

        // 2. Update Order dengan nama kolom yang BENAR
        $order->update([
            'status' => 'selesai',
            'tanggal_pengembalian_aktual' => $tglAktual, // Sesuai DB
            'hari_terlambat' => $maxHariTelat,           // Sesuai DB
            'total_denda' => $totalDenda,                // Sesuai DB
            'total_akhir' => $order->total_harga_pesanan + $totalDenda, // Sesuai DB
            'catatan_admin' => $totalDenda > 0
                ? "Terlambat {$maxHariTelat} hari. Denda: Rp " . number_format($totalDenda, 0, ',', '.')
                : "Dikembalikan tepat waktu.",
        ]);

        return redirect()->back()->with('success', 'Barang dikembalikan! ' . ($totalDenda > 0 ? 'Denda tercatat.' : 'Tanpa denda.'));
    }
}
