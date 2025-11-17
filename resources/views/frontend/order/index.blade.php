@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen pt-24 pb-20">
    <div class="max-w-7xl mx-auto px-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Pesanan Saya</h1>
        <p class="text-gray-500 mb-8">Riwayat sewa dan status pembayaranmu ada di sini.</p>

        <!-- Alert -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-100 border border-emerald-200 text-emerald-700 rounded-xl flex items-center gap-2 animate-fade-in-up">
                <i class="fa-solid fa-circle-check"></i>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 p-4 bg-red-100 border border-red-200 text-red-700 rounded-xl">{{ session('error') }}</div>
        @endif

        <div class="space-y-6">
            @forelse($orders as $order)
                <!-- 1 KARTU = 1 PESANAN (ORDER) -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <!-- Header Kartu Pesanan -->
                    <div class="p-4 bg-gray-50 border-b border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center">
                        <div>
                            <p class="text-xs text-gray-500">Order ID: <span class="font-bold text-gray-700">#JENGKI-{{ $order->id }}</span></p>
                            <p class="text-xs text-gray-500">Tanggal Pesan: {{ $order->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <span class="mt-2 md:mt-0 px-3 py-1 rounded-full text-xs font-bold capitalize
                            {{ $order->status == 'menunggu pembayaran' ? 'bg-orange-100 text-orange-700' : '' }}
                            {{ $order->status == 'menunggu konfirmasi' ? 'bg-blue-100 text-blue-700' : '' }}
                            {{ $order->status == 'dikonfirmasi' || $order->status == 'disewa' ? 'bg-emerald-100 text-emerald-700' : '' }}
                            {{ $order->status == 'selesai' ? 'bg-gray-100 text-gray-600' : '' }}
                            {{ $order->status == 'dibatalkan' ? 'bg-red-100 text-red-700' : '' }}
                        ">
                            {{ $order->status }}
                        </span>
                    </div>

                    <!-- List Barang di dalam Pesanan (Order Items) -->
                    <div class="divide-y divide-gray-50">
                        @foreach($order->items as $item)
                        <div class="p-5 flex items-start gap-4">
                            @if($item->barang)
                                <img src="{{ asset('storage/' . $item->barang->gambar) }}" alt="{{ $item->nama_barang_saat_checkout }}" class="w-20 h-20 object-cover rounded-xl bg-gray-100">
                            @else
                                <div class="w-20 h-20 rounded-xl bg-gray-100 flex items-center justify-center flex-shrink-0">
                                    <i class="fa-solid fa-store-slash text-2xl text-gray-400"></i>
                                </div>
                            @endif

                            <div class="flex-1">
                                <h3 class="font-bold text-gray-900">{{ $item->nama_barang_saat_checkout }}</h3>
                                <p class="text-sm text-gray-500 mt-1">
                                    <span class="font-medium text-gray-700">{{ $item->kuantitas }} unit</span> x
                                    <span class="font-medium text-gray-700">{{ $item->durasi }} Malam</span>
                                </p>
                                <p class="text-xs text-gray-500">
                                    Tgl: <span class="font-medium text-gray-700">{{ $item->tanggal_sewa->format('d M') }} - {{ $item->tanggal_kembali->format('d M') }}</span>
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-bold text-emerald-600">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</p>
                                <p class="text-xs text-gray-400">(@ Rp{{ number_format($item->harga_paket_saat_checkout, 0) }})</p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Total & Aksi (per Order) -->
                    <div class="p-5 bg-gray-50 border-t border-gray-100">
                        <div class="flex justify-end items-center mb-4">
                            <span class="text-gray-500 font-medium">Total Pesanan:</span>
                            <span class="text-xl font-bold text-gray-900 ml-3">Rp{{ number_format($order->total_harga_pesanan, 0, ',', '.') }}</span>
                        </div>

                        <!-- Area Aksi (Upload & WA) -->
                        @if($order->status == 'menunggu pembayaran')
                            <div class="p-4 bg-orange-50 border border-orange-100 rounded-lg">
                                <h4 class="font-semibold text-orange-800">Lakukan Pembayaran DP</h4>
                                <p class="text-xs text-orange-700 mt-1">Silakan transfer DP (min. 50%) ke: <br> <span class="font-bold">BCA 123456789 a.n Jengki Adventure</span>.</p>

                                <form action="{{ route('frontend.order.uploadProof', $order->id) }}" method="POST" enctype="multipart/form-data" class="mt-4 flex flex-col sm:flex-row gap-3">
                                    @csrf
                                    <input type="file" name="bukti_pembayaran" required class="flex-1 block w-full text-xs text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-white focus:outline-none file:bg-gray-100 file:border-0 file:px-3 file:py-2 file:mr-3">
                                    <button type="submit" class="bg-gray-900 text-white px-4 py-2 rounded-lg font-semibold hover:bg-gray-800 text-sm">
                                        <i class="fa-solid fa-upload mr-1"></i> Upload Bukti
                                    </button>
                                </form>
                                @error('bukti_pembayaran') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                            </div>

                        @elseif($order->status == 'menunggu konfirmasi')
                            <div class="p-4 bg-blue-50 border border-blue-100 rounded-lg">
                                <h4 class="font-semibold text-blue-800">Bukti Terkirim!</h4>
                                <p class="text-xs text-blue-700 mt-1">Admin akan segera melakukan konfirmasi pesananmu.</p>
                                <div class="mt-3">
                                    <a href="https://wa.me/6281234567890?text=Halo%20Admin%2C%20saya%20sudah%20upload%20bukti%20bayar%20untuk%20Order%20%23JENGKI-{{ $order->id }}" target="_blank" class="inline-flex items-center gap-2 bg-green-500 text-white px-4 py-2 rounded-lg font-semibold hover:bg-green-600 text-sm">
                                        <i class="fa-brands fa-whatsapp"></i> Chat Admin
                                    </a>
                                </div>
                            </div>

                        @elseif($order->status == 'dikonfirmasi')
                            <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-lg">
                                <h4 class="font-semibold text-emerald-800">Pembayaran Dikonfirmasi!</h4>
                                <p class="text-xs text-emerald-700 mt-1">Pesananmu siap diambil sesuai tanggal sewa.</p>
                            </div>

                        @elseif($order->status == 'selesai')
                            <div class="p-4 bg-gray-100 border border-gray-200 rounded-lg">
                                <h4 class="font-semibold text-gray-700">Pesanan Selesai</h4>
                                <p class="text-xs text-gray-500 mt-1">Terima kasih sudah menyewa!</p>

                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-white p-10 rounded-2xl shadow-sm border border-gray-100 text-center">
                    <p class="text-gray-500">Kamu belum punya riwayat pesanan.</p>
                    <a href="{{ route('frontend.produk.index') }}" class="inline-block mt-4 bg-gray-900 text-white px-5 py-2 rounded-lg font-semibold hover:bg-gray-800 text-sm">
                        Mulai Menyewa
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
