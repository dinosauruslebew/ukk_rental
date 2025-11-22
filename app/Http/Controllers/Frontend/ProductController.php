<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Paket;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Daftar kategori
        $kategori = [
            'tenda',
            'carrier',
            'penerangan',
            'tidur',
            'dapur',
            'aksesoris',
            'paket',
        ];

        // ==============================
        // Jika kategori = paket
        // ==============================
        if ($request->category === 'paket') {
        $paket = Paket::with('items')->get();

        $barang = $paket->map(function ($p) {
            return (object)[
                'is_paket'     => true,
                'id_paket'     => $p->id_paket,
                'nama_paket'   => $p->nama_paket,
                'gambar'       => $p->gambar ?? 'default.jpg',
                'harga_paket'  => $p->harga_paket,
                'total_item'   => $p->items->count(),
                'items'       => $p->items,

            ];
        });

        return view('frontend.produk.index', [
            'barang' => $barang,
            'kategori' => $kategori,
            'activeCategory' => 'paket',
        ]);
    }


        // ==============================
        // Halaman produk biasa (barang satuan)
        // ==============================
        $query = Barang::query();

        // Search
        if ($request->filled('search')) {
            $searchTerm = "%" . $request->search . "%";
            $query->where(function ($sub) use ($searchTerm) {
                $sub->where('nama_barang', 'like', $searchTerm)
                    ->orWhere('deskripsi', 'like', $searchTerm);
            });
        }

        // Filter kategori
        if ($request->filled('category') && $request->category !== 'all') {
            $cat = $request->category;

            $query->where(function ($q) use ($cat) {
                $q->where('kategori', $cat);

                if ($cat === 'tenda') $q->orWhere('nama_barang', 'like', '%tenda%');
                if ($cat === 'carrier') $q->orWhere('nama_barang', 'like', '%carrier%')
                                          ->orWhere('nama_barang', 'like', '%hydropack%');
                if ($cat === 'penerangan') $q->orWhere('nama_barang', 'like', '%lampu%')
                                             ->orWhere('nama_barang', 'like', '%senter%')
                                             ->orWhere('nama_barang', 'like', '%headlamp%');
                if ($cat === 'tidur') $q->orWhere('nama_barang', 'like', '%matras%')
                                        ->orWhere('nama_barang', 'like', '%sleeping%');
                if ($cat === 'dapur') $q->orWhere('nama_barang', 'like', '%kompor%')
                                        ->orWhere('nama_barang', 'like', '%gas%')
                                        ->orWhere('nama_barang', 'like', '%cooking%');
                if ($cat === 'aksesoris') $q->orWhere('nama_barang', 'like', '%tas%')
                                            ->orWhere('nama_barang', 'like', '%sarung%')
                                            ->orWhere('nama_barang', 'like', '%baterai%')
                                            ->orWhere('nama_barang', 'like', '%sekop%');
            });
        }

        // Hasil barang → stok ada ditaruh paling atas
        $barang = $query
            ->orderByRaw("CASE WHEN stok > 0 THEN 0 ELSE 1 END")
            ->orderBy('nama_barang', 'asc')
            ->get();

        return view('frontend.produk.index', [
            'barang' => $barang,
            'kategori' => $kategori,
            'activeCategory' => $request->category ?? 'all',
        ]);
    }

    public function show(Barang $barang)
    {
        return view('frontend.produk.detail', [
            'barang' => $barang,
        ]);
    }
}
