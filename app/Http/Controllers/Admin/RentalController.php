<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rental;
use App\Models\Barang;
use Illuminate\Support\Facades\Schema;

class RentalController extends Controller
{
    /**
     * Display a listing of rentals for admin.
     */
    public function index()
    {
        // load rentals with related user and barang to avoid N+1
        $rentals = Rental::with(['user', 'barang'])->orderBy('created_at', 'desc')->get();

        // map rentals into a simple structure the Blade view expects
        $data = $rentals->map(function ($r) {
            return [
                'id' => $r->id,
                'nama' => $r->user?->name ?? '-',
                'email' => $r->user?->email ?? '-',
                'no_hp' => $r->user?->no_hp ?? null,
                'alamat' => $r->user?->alamat ?? null,
                'barang' => $r->barang?->nama_barang ?? 'N/A',
                'tanggal_sewa' => $r->tanggal_sewa?->toDateString() ?? null,
                'tanggal_kembali' => $r->tanggal_kembali?->toDateString() ?? null,
                'durasi' => $r->durasi,
                'total' => (int) $r->total_harga,
                'status' => ucfirst($this->statusLabel($r->status)),
                'foto' => $r->user?->foto ? asset('storage/' . $r->user->foto) : 'https://i.pravatar.cc/100',
            ];
        });

        return view('admin.rental.index', ['rentals' => $data]);
    }

    /**
     * Confirm a rental (called from admin UI).
     */
    public function confirm($id)
    {
        $rental = Rental::findOrFail($id);

        // set rental status to 'aktif' (meaning confirmed / next step)
        $rental->status = 'aktif';
        $rental->save();

        // update barang status to 'disewa' only if column exists
        try {
            if (Schema::hasTable('barangs') && Schema::hasColumn('barangs', 'status')) {
                $barang = $rental->barang;
                if ($barang) {
                    $barang->status = 'disewa';
                    $barang->save();
                }
            }
        } catch (\Throwable $e) {
            // ignore failures updating barang status to avoid breaking confirmation
        }

        return redirect()->route('admin.rental.index')->with('success', 'Pesanan berhasil dikonfirmasi!');
    }

    protected function statusLabel($status)
    {
        return match ($status) {
            'menunggu' => 'Menunggu Konfirmasi',
            'aktif' => 'Menunggu Pembayaran',
            'selesai' => 'Selesai',
            'batal' => 'Dibatalkan',
            default => ucfirst($status ?? 'menunggu'),
        };
    }
}
// <?php

// namespace App\Http\Controllers\Admin;

// use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
// use App\Models\Rental;
// use App\Models\Barang;

// class RentalController extends Controller
// {
//     // menampilkan semua data penyewaan
//     public function index()
//     {
//         $rentals = Rental::with(['user', 'barang'])->latest()->get();
//         return view('admin.rental.index', compact('rentals'));
//     }

//     // konfirmasi pesanan
//     public function confirm($id)
//     {
//         $rental = Rental::findOrFail($id);

//         // ubah status rental
//         $rental->status = 'Dikonfirmasi';
//         $rental->save();

//         // update status barang menjadi 'disewa'
//         if ($rental->barang_id) {
//             $barang = Barang::find($rental->barang_id);
//             if ($barang) {
//                 $barang->status = 'disewa';
//                 $barang->save();
//             }
//         }

//         // redirect balik dengan notifikasi sukses
//         return redirect()->route('admin.rental.index')->with('success', 'Pesanan berhasil dikonfirmasi!');
//     }
// }
