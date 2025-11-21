<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // ================================
        // 1. CEK JIKA USER MEMBUKA PAKET
        // ================================
        if ($request->has('paket')) {

            $paketKey = $request->paket;

            // Data paket manual (bisa dipindah ke database kalau mau)
            $paketList = [
                'solo' => [
                    'nama'  => 'Paket Solo',
                    'harga' => 75000,
                    'gambar' => '/paket-solo.jpg', // opsional, boleh diubah
                    'items' => [
                        '1 Tenda Single',
                        '1 Sleeping Bag',
                        '1 Lampu Camping',
                        '1 Kompor Portable',
                    ],
                ],
                'duo' => [
                    'nama'  => 'Paket Duo',
                    'harga' => 120000,
                    'gambar' => '/paket-duo.jpg',
                    'items' => [
                        '1 Tenda Double',
                        '2 Sleeping Bag',
                        '1 Lampu Camping',
                        'Nesting Masak Set',
                    ],
                ],
                'family' => [
                    'nama'  => 'Paket Family',
                    'harga' => 200000,
                    'gambar' => '/paket-family.jpg',
                    'items' => [
                        '1 Tenda Family 4–6 orang',
                        '4 Sleeping Bag',
                        '1 Lampu Camping',
                        '1 Kompor + Peralatan Masak',
                    ],
                ],
            ];

            // Jika paket tidak valid -> redirect ke produk biasa
            if (!isset($paketList[$paketKey])) {
                return redirect()->route('frontend.produk.index');
            }

            $data = $paketList[$paketKey];

            return view('frontend.produk.paket-detail', compact('data'));
        }

        // ======================================
        // 2. JIKA BUKAN PAKET → PRODUK NORMAL
        // ======================================

        // Mulai query
        $query = Barang::query();

        // Filter search
        $query->when($request->filled('search'), function ($q) use ($request) {
            $searchTerm = '%' . $request->search . '%';
            return $q->where(function ($subQuery) use ($searchTerm) {
                $subQuery->where('nama_barang', 'like', $searchTerm)
                         ->orWhere('deskripsi', 'like', $searchTerm);
            });
        });

        // Filter kategori
        $query->when($request->filled('category') && $request->category !== 'all', function ($q) use ($request) {
            return $q->where('kategori', $request->category);
        });

        // Ambil barang
        $barang = $query->orderBy('created_at', 'desc')->get();

    // daftar kategori fix
    $kategori = [
        'tenda',
        'carrier',
        'penerangan',
        'tidur',
        'dapur',
        'aksesoris',
        'paket',
    ];

    // // jika kategori = paket --> arahkan ke halaman paket
    // if ($request->category === 'paket') {
    //     return view('frontend.produk.paket'); 
    // }

    // query barang
    $query = Barang::query();

    // search
    $query->when($request->filled('search'), function ($q) use ($request) {
        $searchTerm = '%' . $request->search . '%';
        return $q->where(function ($sub) use ($searchTerm) {
            $sub->where('nama_barang', 'like', $searchTerm)
                ->orWhere('deskripsi', 'like', $searchTerm);
        });
    });

    // filter kategori barang satuan
if ($request->filled('category') && $request->category !== 'all') {

    $cat = $request->category;

    $query->where(function ($q) use ($cat) {

        // filter berdasarkan kategori di DB (kalau ada)
        $q->where('kategori', $cat);

        // filter otomatis berdasarkan nama (opsional)
        if ($cat === 'tenda') {
            $q->orWhere('nama_barang', 'like', '%tenda%');
        }

        if ($cat === 'carrier') {
            $q->orWhere('nama_barang', 'like', '%carrier%');
            $q->orWhere('nama_barang', 'like', '%hydropack%');
        }

        if ($cat === 'penerangan') {
            $q->orWhere('nama_barang', 'like', '%lampu%');
            $q->orWhere('nama_barang', 'like', '%senter%');
            $q->orWhere('nama_barang', 'like', '%headlamp%');
        }

        if ($cat === 'tidur') {
            $q->orWhere('nama_barang', 'like', '%matras%');
            $q->orWhere('nama_barang', 'like', '%sleeping%');
        }

        if ($cat === 'dapur') {
            $q->orWhere('nama_barang', 'like', '%kompor%');
            $q->orWhere('nama_barang', 'like', '%cooking%');
            $q->orWhere('nama_barang', 'like', '%gas%');
            $q->orWhere('nama_barang', 'like', '%grill%');
        }

        if ($cat === 'aksesoris') {
            $q->orWhere('nama_barang', 'like', '%tas%');
            $q->orWhere('nama_barang', 'like', '%sarung%');
            $q->orWhere('nama_barang', 'like', '%tripod%');
            $q->orWhere('nama_barang', 'like', '%sekop%');
            $q->orWhere('nama_barang', 'like', '%baterai%');
            $q->orWhere('nama_barang', 'like', '%blanket%');
        }
    });
}


// 4. Ambil Data Barang (Tampilkan stok habis di paling bawah)
$barang = $query
    ->orderByRaw("CASE WHEN stok > 0 THEN 0 ELSE 1 END") // stok ada duluan, stok habis disusul
    ->orderBy('nama_barang', 'asc') // urut nama biar rapi
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
