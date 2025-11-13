@extends('layouts.admin')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="p-8 min-h-screen bg-gradient-to-br from-blue-50 to-pink-50 py-10 px-8">

    {{-- ✨ judul --}}
    <div class="mb-10 text-center">
        <h2 class="text-4xl font-bold text-gray-800">Dashboard Overview</h2>
    </div>

    {{-- ✨ statistik cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
        <div class="bg-white shadow-lg rounded-2xl p-6 border border-gray-100 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Barang</p>
                    <h3 class="text-3xl font-extrabold text-teal-700 mt-1">{{ $totalBarang }}</h3>
                </div>
                <div class="bg-teal-100 text-teal-700 p-3 rounded-xl">
                    <i class="fa-solid fa-box text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-lg rounded-2xl p-6 border border-gray-100 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">User Terdaftar</p>
                    <h3 class="text-3xl font-extrabold text-green-700 mt-1">{{ $totalUsers }}</h3>
                </div>
                <div class="bg-green-100 text-green-700 p-3 rounded-xl">
                    <i class="fa-solid fa-users text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-lg rounded-2xl p-6 border border-gray-100 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Rental Aktif</p>
                    <h3 class="text-3xl font-extrabold text-yellow-600 mt-1">{{ $totalRentalAktif }}</h3>
                </div>
                <div class="bg-yellow-100 text-yellow-600 p-3 rounded-xl">
                    <i class="fa-solid fa-calendar-check text-2xl"></i>
                </div>
            </div>
        </div>

        {{-- tambahan insight --}}
        <div class="bg-white shadow-lg rounded-2xl p-6 border border-gray-100 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Barang Tidak Tersedia</p>
                    <h3 class="text-3xl font-extrabold text-red-600 mt-1">{{ $barangTidakTersedia ?? 0 }}</h3>
                </div>
                <div class="bg-red-100 text-red-600 p-3 rounded-xl">
                    <i class="fa-solid fa-triangle-exclamation text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- ✨ chart revenue (muncul kalau ada pesanan) --}}
    @if(!empty($chartData['values']) && count(array_filter($chartData['values'])) > 0)
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 mb-12">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-chart-line text-teal-600"></i> Pendapatan Bulanan
            </h3>
            <span class="text-sm text-gray-400">Diperbarui {{ now()->translatedFormat('F Y') }}</span>
        </div>
        <canvas id="revenueChart" height="120"></canvas>
    </div>
    @else
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-8 mb-12 text-center text-gray-500 italic">
        <i class="fa-regular fa-circle-question text-4xl mb-3 text-gray-400"></i>
        <p>Belum ada transaksi yang masuk, grafik pendapatan akan tampil setelah ada pesanan.</p>
    </div>
    @endif

    {{-- ✨ recent rentals --}}
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
        <h3 class="text-lg font-semibold text-gray-800 mb-6 flex items-center gap-2">
            <i class="fa-solid fa-receipt text-teal-600"></i> Penyewaan Terbaru
        </h3>

        <table class="w-full text-left border-t border-gray-100">
            <thead>
                <tr class="text-gray-500 text-sm border-b">
                    <th class="py-2">Penyewa</th>
                    <th>Barang</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentRentals as $rental)
                    <tr class="border-b hover:bg-teal-50/50 transition">
                        <td class="py-2">{{ $rental->user->name ?? '-' }}</td>
                        <td>{{ $rental->barang->nama_barang ?? '-' }}</td>
                        <td>{{ $rental->created_at->format('d M Y') }}</td>
                        <td>
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                {{ $rental->status == 'aktif' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ ucfirst($rental->status) }}
                            </span>
                        </td>
                        <td>Rp{{ number_format($rental->total_harga ?? 0, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-6 text-center text-gray-500 italic">
                            Belum ada transaksi penyewaan terbaru.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if(!empty($chartData['values']) && count(array_filter($chartData['values'])) > 0)
<script>
    const ctx = document.getElementById('revenueChart').getContext('2d');

    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(56, 178, 172, 0.3)');
    gradient.addColorStop(1, 'rgba(255, 255, 255, 0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($chartData['labels']),
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: @json($chartData['values']),
                borderColor: '#319795',
                backgroundColor: gradient,
                borderWidth: 3,
                pointRadius: 5,
                pointBackgroundColor: '#2C7A7B',
                tension: 0.4,
                fill: true,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#f1f1f1' },
                    ticks: { color: '#4a5568', callback: val => 'Rp' + val.toLocaleString('id-ID') }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: '#4a5568' }
                }
            }
        }
    });
</script>
@endif

@endsection
