<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Barang;
use App\Models\Rental;
use Illuminate\Support\Facades\Schema;


class DashboardController extends Controller
{
    public function index()
    {
        $totalBarang = Barang::count();
        $totalUsers = User::count();
             // total rental aktif
        // cek dulu apakah kolom 'status' ada di tabel rentals
        if (Schema::hasColumn('rentals', 'rental_status')) {
            $totalRentalAktif = Rental::where('rental_status', 'aktif')->count();
        } else {
            $totalRentalAktif = 0; // kalau belum ada kolom, default 0
        }

        // contoh data chart
        $chartData = [
            'labels' => ['1 Aug', '8 Aug', '15 Aug', '22 Aug', '31 Aug', '1 Sept'],
            'values' => [50000, 80000, 120000, 90000, 130000, 130000],
        ];

        // contoh data rental terbaru
        $recentRentals = Rental::latest()->take(3)->get();

        return view('admin.dashboard', compact(
            'totalBarang',
            'totalUsers',
            'totalRentalAktif',
            'chartData',
            'recentRentals'
        ));
    }
}