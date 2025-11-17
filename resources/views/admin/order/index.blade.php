@extends('layouts.admin')

@section('content')
<div class="p-8 min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Manajemen Pesanan</h1>
            <p class="text-sm text-gray-500 mt-1">Konfirmasi, kelola, dan lacak semua pesanan sewa.</p>
        </div>
    </div>

    <!-- Peringatan (Opsional) - Tetap ada -->
    <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-lg flex items-center gap-2">
        <i class="fa-solid fa-triangle-exclamation"></i>
        <p class="text-sm font-medium">Ini adalah sistem pesanan baru. Data dari tabel `rentals` (lama) tidak akan muncul di sini.</p>
    </div>

    <!--
        ========================================
        FILTER STATUS (BARU! - Server Side)
        Menggantikan filter JS kamu yang lama
        ========================================
    -->
    <div class="flex flex-wrap gap-2 mb-6 border-b border-gray-200 pb-4">
        @foreach($statuses as $value => $label)
            <a href="{{ route('admin.order.index', ['status' => $value]) }}"
               class="px-5 py-2 rounded-full text-sm font-medium transition-all duration-300 shadow-sm
               {{ $activeStatus == $value
                    ? 'bg-gray-800 text-white'
                    : 'bg-white border border-gray-300 text-gray-600 hover:bg-gray-100' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <!-- Daftar Tabel Pesanan -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr class="text-xs uppercase text-gray-500 font-semibold">
                        <th class="py-3 px-6">Order ID</th>
                        <th class="py-3 px-6">Pemesan</th>
                        <th class="py-3 px-6">Tanggal Pesan</th>
                        <th class="py-3 px-6">Total</th>
                        <th class="py-3 px-6">Status</th>
                        <!-- PERBAIKAN: class_name -> class -->
                        <th class="py-3 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="py-4 px-6 font-medium text-gray-900">
                                #JENGKI-{{ $order->id }}
                            </td>
                            <td class="py-4 px-6 text-gray-600">
                                {{ $order->user->name ?? 'User Dihapus' }}
                            </td>
                            <td class="py-4 px-6 text-gray-500">
                                {{ $order->created_at->format('d M Y, H:i') }}
                            </td>
                            <td class="py-4 px-6 font-bold text-gray-800">
                                Rp{{ number_format($order->total_harga_pesanan, 0, ',', '.') }}
                            </td>
                            <td class="py-4 px-6">
                                <!-- Badge Status Dinamis (Tetap) -->
                                <span class="px-3 py-1 rounded-full text-xs font-bold capitalize
                                    {{ $order->status == 'menunggu pembayaran' ? 'bg-orange-100 text-orange-700' : '' }}
                                    {{ $order->status == 'menunggu konfirmasi' ? 'bg-blue-100 text-blue-700' : '' }}
                                    {{ $order->status == 'dikonfirmasi' || $order->status == 'disewa' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                    {{ $order->status == 'selesai' ? 'bg-gray-100 text-gray-600' : '' }}
                                    {{ $order->status == 'dibatalkan' ? 'bg-red-100 text-red-700' : '' }}
                                ">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <!--
                                    ========================================
                                    PERBAIKAN: Tombol Aksi difungsikan!
                                    ========================================
                                -->
                                <a href="{{ route('admin.order.show', $order->id) }}" class="text-xs font-medium text-white bg-gray-800 px-3 py-1.5 rounded-md hover:bg-gray-900 transition">
                                    Lihat Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center text-gray-400 italic">
                                Tidak ada pesanan dengan status ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
