<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\User;
use App\Models\Rental;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Basic stats
        $totalBarang = Barang::count();
        $totalUsers = User::where('role', 'user')->count();
        $totalRentalAktif = Rental::where('status', 'aktif')->count();
        $barangTidakTersedia = Barang::where('status', 'disewa')->count();

        // Chart data: last 6 months revenue (calculate from total_harga if exists, fallback to barang harga * durasi)
        $months = collect();
        $values = collect();

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months->push($date->format('M Y'));

            $sum = Rental::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->with('barang')
                ->get()
                ->sum(function ($r) {
                    if (isset($r->total_harga)) {
                        return (float) $r->total_harga;
                    }
                    return optional($r->barang)->harga_sewa * ($r->durasi ?? 1);
                });

            $values->push($sum);
        }

        $chartData = [
            'labels' => $months->toArray(),
            'values' => $values->toArray(),
        ];

        // Calendar events: for FullCalendar we produce start/end, title, color (optional)
        $rentals = Rental::with(['user','barang'])->get();

        $calendarEvents = $rentals->map(function ($r) {
            $start = $r->tanggal_sewa ? Carbon::parse($r->tanggal_sewa)->toDateString() : $r->created_at->toDateString();
            // FullCalendar treats end as exclusive, add 1 day for inclusive range if tanggal_kembali exists
            $end = $r->tanggal_kembali ? Carbon::parse($r->tanggal_kembali)->addDay()->toDateString() : null;

            // title: nama + barang
            $title = ($r->user->name ?? 'Penyewa') . ' — ' . ($r->barang->nama_barang ?? 'Item');

            // color by status (optional)
            $color = match(strtolower($r->status)) {
                'menunggu konfirmasi', 'menunggu' => '#F6E05E', // yellow
                'menunggu pembayaran' => '#C084FC', // purple
                'aktif' => '#4ADE80', // green
                'selesai' => '#34D399',
                'dibatalkan','batal' => '#FCA5A5',
                default => '#60A5FA'
            };

            return [
                'id' => $r->id,
                'title' => $title,
                'start' => $start,
                'end' => $end, // can be null
                'extendedProps' => [
                    'user_name' => $r->user->name ?? null,
                    'user_email' => $r->user->email ?? null,
                    'barang' => $r->barang->nama_barang ?? null,
                    'status' => $r->status,
                    'total_harga' => $r->total_harga ?? (optional($r->barang)->harga_sewa * ($r->durasi ?? 1)),
                ],
                'color' => $color,
            ];
        })->toArray();

        // Recent rentals
        $recentRentals = Rental::with(['user','barang'])->latest()->take(6)->get();

        return view('admin.dashboard', compact(
            'totalBarang',
            'totalUsers',
            'totalRentalAktif',
            'barangTidakTersedia',
            'chartData',
            'calendarEvents',
            'recentRentals'
        ));
    }
}
