<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // 1. Mulai Query
        $query = Barang::query();

        // 2. Filter Pencarian (Search)
        $query->when($request->filled('search'), function ($q) use ($request) {
            $searchTerm = '%' . $request->search . '%';
            return $q->where(function ($subQuery) use ($searchTerm) {
                $subQuery->where('nama_barang', 'like', $searchTerm)
                         ->orWhere('deskripsi', 'like', $searchTerm);
            });
        });

        // 3. Filter Kategori (PENTING: Ini logic untuk Tab Kategori)
        // Jika ada request 'category' dan isinya BUKAN 'all', kita filter.
        $query->when($request->filled('category') && $request->category !== 'all', function ($q) use ($request) {
            return $q->where('kategori', $request->category);
        });

        // 4. Ambil Data Barang
        $barang = $query->orderBy('created_at', 'desc')->get();

        // 5. Ambil Daftar Kategori Unik untuk Tab Navigasi
        $kategori = Barang::select('kategori')->whereNotNull('kategori')->distinct()->pluck('kategori');

        // 6. Kirim ke View
        return view('frontend.produk.index', [
            'barang' => $barang,
            'kategori' => $kategori,
            'activeCategory' => $request->category ?? 'all', // Untuk menandai tab yang aktif
        ]);
    }

    public function show(Barang $barang)
    {
        return view('frontend.produk.detail', [
            'barang' => $barang,
        ]);
    }
}
