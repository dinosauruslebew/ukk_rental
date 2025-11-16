<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Menampilkan halaman semua produk (All Products) dengan fitur filter.
     */
    public function index(Request $request)
    {
        // Filter rentang harga
        $priceRanges = [
            '0-50000' => 'Rp 0 - Rp 50.000',
            '50001-100000' => 'Rp 50.001 - Rp 100.000',
            '100001-200000' => 'Rp 100.001 - Rp 200.000',
            '200001-9999999' => 'Diatas Rp 200.000',
        ];

        // Mulai query barang
        $query = Barang::query();

        // Filter pencarian
        $query->when($request->filled('search'), function ($q) use ($request) {
            $searchTerm = '%' . $request->search . '%';
            return $q->where(function ($subQuery) use ($searchTerm) {
                $subQuery->where('nama_barang', 'like', $searchTerm)
                         ->orWhere('deskripsi', 'like', $searchTerm);
            });
        });

        // Filter ketersediaan
        $query->when($request->filled('availability'), function ($q) use ($request) {
            return $q->where('status', $request->availability);
        });

        // Filter rentang harga
        $query->when($request->filled('price_range'), function ($q) use ($request) {
            $prices = explode('-', $request->price_range);
            return $q->whereBetween('harga_sewa', [$prices[0], $prices[1]]);
        });

        // Filter kategori
        $query->when($request->filled('category'), function ($q) use ($request) {
            return $q->where('kategori', $request->category);
        });

        // Ambil hasil akhir
        $barang = $query->orderBy('created_at', 'desc')->get();

        // Ambil semua kategori unik untuk sidebar
        $kategori = Barang::select('kategori')->distinct()->pluck('kategori');

        // Return ke view
        return view('frontend.produk.index', [
            'barang' => $barang,
            'priceRanges' => $priceRanges,
        ]);
    }

    /**
     * Menampilkan halaman detail produk.
     */
    public function show(Barang $barang)
    {
        return view('frontend.produk.detail', [
            'barang' => $barang,
        ]);
    }
}
