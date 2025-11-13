<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    /**
     * Menampilkan halaman landing page utama (beranda).
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // 1. Ambil data barang dari database
        // Kita ambil semua barang, diurutkan berdasarkan yang terbaru
        // File landing.blade.php kamu nanti yang akan mengurus
        // mana yang 'New Arrival' dan mana yang 'Product'
        $barang = Barang::orderBy('created_at', 'desc')->get();

        // 2. Kirim data barang ke view 'landing'
        // PERUBAHAN DI SINI:
        // Kita panggil view 'landing' yang ada di dalam folder 'Frontend'
        return view('frontend.landing', [
            'barang' => $barang
        ]);
    }
}
