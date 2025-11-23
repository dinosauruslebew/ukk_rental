<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Paket;
use App\Models\Order; 

class OrderController extends Controller
{
    /**
     * Menampilkan form checkout.
     * Menerima $paket_id dari route('order.create', $paket->id)
     */
    public function create($paket_id = null)
    {
        $user = Auth::user();
        
        // Asumsi: Middleware 'auth' sudah menangani jika user belum login. 
        // Namun, jika belum login, Laravel akan redirect ke /login
        if (!$user) {
            return redirect()->route('login')->with('error', 'Anda harus login untuk melanjutkan pemesanan.');
        }

        // Jika user mengakses /order/create tanpa ID, kembalikan ke landing page
        if (!$paket_id) {
            return redirect()->route('frontend.landing')->with('error', 'Mohon pilih paket yang ingin Anda sewa terlebih dahulu dari halaman utama.');
        }

        // 1. Mengambil paket beserta item barang yang ada di dalamnya
        // CATATAN PENTING: Jika model Paket Anda menggunakan kunci utama (Primary Key) selain 'id', 
        // gunakan findOrFail atau find(..., 'id_paket') sesuai konfigurasi model.
        $paket = Paket::with(['items.barang'])->find($paket_id);
        
        if (!$paket) {
            return redirect()->route('frontend.landing')->with('error', 'Paket yang Anda pilih tidak valid atau sudah tidak tersedia.');
        }

        // 2. Tampilkan view checkout dan kirim data paket
        return view('frontend.checkout', compact('user', 'paket'));
    }

    /**
     * Menyimpan pesanan ke database (Dipanggil oleh route('checkout.store')).
     */
    public function store(Request $request)
    {
        // Logika penyimpanan Order: mengambil data dari form checkout, 
        // membuat record Order, membuat OrderItems, dan mengurangi stok.
        
        // Contoh:
        // $request->validate([...]);
        // $order = Order::create([...]);
        // $order->items()->createMany([...]);
        
        return redirect()->route('frontend.order.index')->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran.');
    }

    /**
     * Menampilkan daftar pesanan milik pengguna.
     */
    public function index()
    {
        // Mengambil 3 pesanan terbaru milik user yang sedang login
        $orders = Order::where('user_id', Auth::id())
                        ->latest()
                        ->take(3) 
                        ->get();
                        
        return view('frontend.order.index', compact('orders'));
    }

    public function uploadProof(Request $request, $id)
    {
        // Logika upload bukti bayar
        return redirect()->back()->with('success', 'Bukti pembayaran berhasil diupload dan menunggu konfirmasi admin.');
    }
}