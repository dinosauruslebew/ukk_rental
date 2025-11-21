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

    <!-- Filter Status -->
    <div class="flex flex-wrap gap-2 mb-6 border-b border-gray-200 pb-4">
        @foreach($statuses as $value => $label)
            <a href="{{ route('admin.order.index', ['status' => $value]) }}"
               class="px-5 py-2 rounded-full text-xs font-medium transition-all duration-300 shadow-sm
               {{ $activeStatus == $value
                    ? 'bg-gray-800 text-white'
                    : 'bg-white border border-gray-300 text-gray-600 hover:bg-gray-100' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-100 border-b border-gray-200">
                    <tr class="text-xs uppercase text-gray-500 font-semibold tracking-wide">
                        <th class="py-3 px-6">Order ID</th>
                        <th class="py-3 px-6">Pemesan</th>
                        <th class="py-3 px-6">Tanggal Pesan</th>
                        <th class="py-3 px-6">Total</th>
                        <th class="py-3 px-6">Status</th>
                        <th class="py-3 px-6 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="text-sm divide-y divide-gray-100">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap">
                                #JENGKI-{{ $order->id }}
                            </td>

                            <td class="py-4 px-6 text-gray-700">
                                {{ $order->user->name ?? 'User Dihapus' }}
                            </td>

                            <td class="py-4 px-6 text-gray-500 whitespace-nowrap">
                                {{ $order->created_at->format('d M Y, H:i') }}
                            </td>

                            <td class="py-4 px-6 font-bold text-gray-800 whitespace-nowrap">
                                Rp{{ number_format($order->total_harga_pesanan, 0, ',', '.') }}
                            </td>

                            <td class="py-4 px-6">
                                <span class="px-3 py-1.5 rounded-full text-xs font-bold capitalize whitespace-nowrap
                                    @if($order->status == 'menunggu pembayaran') bg-orange-100 text-orange-700
                                    @elseif($order->status == 'menunggu konfirmasi') bg-blue-100 text-blue-700
                                    @elseif($order->status == 'dikonfirmasi' || $order->status == 'disewa') bg-emerald-100 text-emerald-700
                                    @elseif($order->status == 'selesai') bg-gray-200 text-gray-700
                                    @elseif($order->status == 'dibatalkan') bg-red-100 text-red-700
                                    @endif
                                ">
                                    {{ $order->status }}
                                </span>
                            </td>

                            <td class="py-4 px-6 text-center">
                                <a href="{{ route('admin.order.show', $order->id) }}"
                                   class="inline-block text-xs font-medium text-white bg-gray-800 px-4 py-1.5 rounded-md hover:bg-gray-900 transition">
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
