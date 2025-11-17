<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\User;
use App\Models\Order; // <-- GANTI ke Order
use App\Models\OrderItem; // <-- TAMBAHKAN OrderItem
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // --- STATISTIK KARTU (UPDATE LOGIC) ---
        $totalBarang = Barang::count();
        $totalUsers = User::where('role', '!=', 'admin')->count();

        // Hitung order yang sedang aktif (bukan 'selesai' atau 'dibatalkan')
        $totalRentalAktif = Order::whereNotIn('status', ['selesai', 'dibatalkan'])->count();

        // Hitung barang yang stoknya habis atau statusnya 'tidak tersedia'
        $barangTidakTersedia = Barang::where('stok', '<=', 0)
                                     ->orWhere('status', '!=', 'tersedia')
                                     ->count();


        // --- CHART PENDAPATAN (UPDATE LOGIC) ---
        // Ambil data dari tabel 'orders'
        $pendapatan = Order::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as bulan'),
            DB::raw('SUM(total_harga_pesanan) as total')
        )
        ->where('status', 'selesai') // Hanya hitung yang sudah selesai
        ->where('created_at', '>=', Carbon::now()->subMonths(6)) // 6 bulan terakhir
        ->groupBy('bulan')
        ->orderBy('bulan', 'asc')
        ->get();

        $chartData = [
            // Kita pakai ->values() dan ->pluck() biar jadi Collection, bukan array
            'labels' => $pendapatan->pluck('bulan')->map(fn($m) => Carbon::parse($m)->format('M Y')),
            'values' => $pendapatan->pluck('total') // Ini akan jadi Collection
        ];


        // --- DATA KALENDER (UPDATE LOGIC) ---
        // Ambil dari 'order_items' karena di sana ada tanggal
        $items = OrderItem::with(['order.user']) // Ambil relasi order & user-nya
            ->where('tanggal_sewa', '>=', Carbon::now()->subMonths(3)) // Ambil 3 bulan terakhir aja biar ringan
            ->whereHas('order', fn($q) => $q->where('status', '!=', 'dibatalkan')) // Jangan tampilkan yang batal
            ->get();

        $calendarEvents = [];

        foreach ($items as $item) {
            // Lewati jika data order atau user tidak lengkap (misal: user dihapus)
            if (!$item->order || !$item->order->user) continue;

            // 1. Event Tanggal MULAI SEWA (Hijau)
            $calendarEvents[] = [
                'id' => $item->order_id, // ID dari Order-nya
                'title' => 'Sewa: ' . $item->order->user->name,
                'start' => $item->tanggal_sewa->format('Y-m-d'),
                'type' => 'sewa',
                'extendedProps' => [
                    'user_name' => $item->order->user->name,
                    'barang' => $item->nama_barang_saat_checkout . ' (x' . $item->kuantitas . ')',
                    'status' => $item->order->status,
                    'type_label' => 'Mulai Sewa',
                    'color' => 'emerald' // Penanda warna
                ]
            ];

            // 2. Event Tanggal PENGEMBALIAN (Orange)
            $calendarEvents[] = [
                'id' => $item->order_id,
                'title' => 'Kembali: ' . $item->order->user->name,
                'start' => $item->tanggal_kembali->format('Y-m-d'),
                'type' => 'kembali',
                'extendedProps' => [
                    'user_name' => $item->order->user->name,
                    'barang' => $item->nama_barang_saat_checkout . ' (x' . $item->kuantitas . ')',
                    'status' => $item->order->status,
                    'type_label' => 'Batas Kembali',
                    'color' => 'orange' // Penanda warna
                ]
            ];
        }

        // --- TRANSAKSI TERBARU (UPDATE LOGIC) ---
        // Ambil dari 'orders' (induk pesanan)
        $recentOrders = Order::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalBarang', 'totalUsers', 'totalRentalAktif', 'barangTidakTersedia',
            'chartData', 'calendarEvents',
            'recentOrders' // <-- GANTI 'recentRentals' jadi 'recentOrders'
        ));
    }
}
