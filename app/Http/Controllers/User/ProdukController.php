<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    // Menampilkan semua produk dengan filter
    public function index(Request $request)
    {
        $query = Barang::query();

        // 🔍 Search
        if ($request->filled('search')) {
            $query->where('nama_barang', 'like', '%' . $request->search . '%');
        }

        // 🏕️ Filter kategori
        if ($request->filled('category')) {
            $query->where('kategori', $request->category);
        }

        // 💰 Filter rentang harga
        if ($request->filled('price_range')) {
            [$min, $max] = explode('-', $request->price_range);
            $query->whereBetween('harga_sewa', [(int)$min, (int)$max]);
        }

        // ✅ Filter status ketersediaan
        if ($request->filled('availability')) {
            $query->where('status', $request->availability);
        }

        // 🔢 Ambil kategori unik (buat sidebar)
        $categories = Barang::select('kategori')->distinct()->get();

        // 💸 Daftar rentang harga statis
        $priceRanges = [
            '0-50000' => 'Di bawah 50k',
            '50000-100000' => '50k - 100k',
            '100000-200000' => '100k - 200k',
            '200000-99999999' => 'Di atas 200k',
        ];

        // 🔄 Ambil produk sesuai filter
        $barang = $query->paginate(12);

        return view('frontend.products.index', compact('barang', 'categories', 'priceRanges'));
    }

    // Menampilkan detail produk
    public function show(Barang $barang)
    {
        return view('frontend.products.detail', compact('barang'));
    }
}
