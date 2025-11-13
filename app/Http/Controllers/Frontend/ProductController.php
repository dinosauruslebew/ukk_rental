<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use Illuminate\Http\Request; // <-- 1. Import Request

class ProductController extends Controller
{
    /**
     * Menampilkan halaman galeri semua produk DENGAN FILTER.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request) // <-- 2. Tambahkan Request $request
    {
        // Siapkan 'cetakan' harga untuk dropdown filter
        $priceRanges = [
            '0-50000' => 'Rp 0 - Rp 50.000',
            '50001-100000' => 'Rp 50.001 - Rp 100.000',
            '100001-200000' => 'Rp 100.001 - Rp 200.000',
            '200001-9999999' => 'Diatas Rp 200.000',
        ];

        // 3. Mulai query, tapi jangan 'get()' dulu
        $query = Barang::query();

        // 4. Terapkan filter PENCARIAN jika ada
        $query->when($request->filled('search'), function ($q) use ($request) {
            $searchTerm = '%' . $request->search . '%';
            // Cari di nama barang ATAU di deskripsi
            return $q->where(function($subQuery) use ($searchTerm) {
                $subQuery->where('nama_barang', 'like', $searchTerm)
                         ->orWhere('deskripsi', 'like', $searchTerm);
            });
        });

        // 5. Terapkan filter KETERSEDIAAN (Status) jika ada
        $query->when($request->filled('availability'), function ($q) use ($request) {
            return $q->where('status', $request->availability);
        });

        // 6. Terapkan filter RENTANG HARGA jika ada
        $query->when($request->filled('price_range'), function ($q) use ($request) {
            // Pecah '0-50000' menjadi [0, 50000]
            $prices = explode('-', $request->price_range);
            return $q->whereBetween('harga_sewa', [$prices[0], $prices[1]]);
        });

        // 7. Ambil hasilnya (->get()) setelah semua filter diterapkan
        $barang = $query->orderBy('created_at', 'desc')->get();

        // 8. Kirim data barang DAN data $priceRanges ke view
        return view('frontend.produk.index', [
            'barang' => $barang,
            'priceRanges' => $priceRanges // Kirim ini untuk dropdown
        ]);
    }

    /**
     * Menampilkan halaman detail untuk satu produk.
     * (Tidak berubah, sudah benar)
     *
     * @param  \App\Models\Barang  $barang
     * @return \Illuminate\View\View
     */
    public function show(Barang $barang)
    {
        return view('frontend.produk.detail', [
            'barang' => $barang
        ]);
    }
}
