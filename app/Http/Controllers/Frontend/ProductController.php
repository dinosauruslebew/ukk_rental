<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Paket;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // ... (index method di sini) ...
    public function index(Request $request)
    {
        // Daftar kategori (Perlu diubah jika kategori paket ingin dipisah dari daftar ini)
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
        // LOGIKA KHUSUS PAKET
        // ==============================
        if ($request->category === 'paket') {
            // Ambil semua paket dan relasinya
            // FIX: Mengubah ['items.barang'] menjadi 'items'
            $pakets = Paket::with('items')->get();
            
            // Map paket ke struktur $barang agar bisa diolah oleh forelse
            $barang = $pakets->map(function ($p) {
                // Di sini kita tidak perlu map detail item karena kita akses langsung via relasi di view
                return (object)[
                    'is_paket'     => true,
                    'id_paket'     => $p->id_paket,
                    'nama_paket'   => $p->nama_paket,
                    'gambar'       => $p->gambar ?? 'default.jpg',
                    'harga_paket'  => $p->harga_paket,
                    'total_item'   => $p->items->count(),
                    'items'        => $p->items, // Kirim objek relasi penuh
                ];
            });

            return view('frontend.produk.index', [
                'barang' => $barang, // Mengirim objek paket yang dimodifikasi
                'kategori' => $kategori,
                'activeCategory' => 'paket',
            ]);
        }

        // ==============================
        // Halaman produk biasa (barang satuan)
        // ==============================
        $query = Barang::query();

        // Search dan Filter logic...
        if ($request->filled('search')) {
             $searchTerm = "%" . $request->search . "%";
             $query->where(function ($sub) use ($searchTerm) {
                 $sub->where('nama_barang', 'like', $searchTerm)
                     ->orWhere('deskripsi', 'like', $searchTerm);
             });
        }
        
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

    public function showBarang(Barang $barang)
    {
        return view('frontend.produk.detail', compact('barang'));
    }

    public function showPaket(Paket $paket)
    {
        $paket->load('items');
        return view('frontend.paket.detail', compact('paket'));
    }


    /**
     * Metode Show untuk Detail Barang Satuan dan Paket
     */
     public function show(Request $request)
     {
         // Mendapatkan nilai model yang telah di-resolve oleh Model Binding
         // Mencari parameter 'barang' atau 'paket' di route
         $model = $request->route()->parameter('barang') ?? $request->route()->parameter('paket');
 
         if ($model instanceof Barang) {
             // Ini detail produk satuan
             // Jika Model Binding berhasil, model sudah terisi data.
             return view('frontend.produk.detail', ['barang' => $model]);
         }
         
         if ($model instanceof Paket) {
             // Ini detail paket
             // Memuat relasi item dan barang untuk ditampilkan di detail
             $model->load('items.barang'); 
             return view('frontend.paket.detail', ['paket' => $model]); 
         }
 
         // Jika Model Binding gagal atau tidak ada parameter
         // PENTING: Jika error 404 terjadi di sini, artinya data ID di URL tidak ditemukan di database.
         abort(404, 'Detail produk atau paket tidak ditemukan.');
     }
}