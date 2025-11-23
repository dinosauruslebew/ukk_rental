@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen pt-24 pb-20">
    <div class="max-w-7xl mx-auto px-6">

        <h1 class="text-3xl font-bold text-gray-900 mb-6">Pesanan Saya</h1>

        <!-- SECTION: LOKASI & RULES (BARU!) -->
        <!-- Ditampilkan di atas agar user langsung lihat info penting ini setelah checkout -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

            <!-- 1. Lokasi Pengambilan (Map) -->
            <div class="md:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-1">
                <div class="p-4 pb-2">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2">
                        <i class="fa-solid fa-map-location-dot text-emerald-600"></i> Lokasi Pengambilan
                    </h3>
                    <p class="text-xs text-gray-500">Jengki Adventure Basecamp</p>
                </div>
                <!-- Embed Google Maps (Ganti URL src dengan lokasi aslimu) -->
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.226077620866!2d110.4173643147733!3d-6.982626394955958!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e708b4d3f3f3f3f%3A0x1234567890abcdef!2sSimpang%20Lima%20Semarang!5e0!3m2!1sid!2sid!4v1625637234567!5m2!1sid!2sid"
                    width="100%" height="250" style="border:0; border-radius: 12px;" allowfullscreen="" loading="lazy">
                </iframe>
            </div>

            <!-- 2. Peraturan Singkat (Rules) -->
            <div class="md:col-span-1 bg-emerald-900 text-white rounded-2xl shadow-sm p-6 relative overflow-hidden">
                <!-- Dekorasi Background -->
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white opacity-10 rounded-full"></div>

                <h3 class="font-bold text-lg mb-4 flex items-center gap-2 relative z-10">
                    <i class="fa-solid fa-clipboard-list"></i> Aturan Sewa
                </h3>
                <ul class="space-y-3 text-sm text-emerald-100 relative z-10">
                    <li class="flex gap-2 items-start">
                        <i class="fa-solid fa-check mt-1 text-emerald-400"></i>
                        <span>Wajib meninggalkan kartu identitas asli (KTP/SIM) sebagai jaminan.</span>
                    </li>
                    <li class="flex gap-2 items-start">
                        <i class="fa-solid fa-check mt-1 text-emerald-400"></i>
                        <span>Cek kondisi barang sebelum meninggalkan toko.</span>
                    </li>
                    <li class="flex gap-2 items-start">
                        <i class="fa-solid fa-check mt-1 text-emerald-400"></i>
                        <span>Keterlambatan pengembalian dikenakan denda harian.</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Alert -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-100 border border-emerald-200 text-emerald-700 rounded-xl flex items-center gap-2 animate-fade-in-up">
                <i class="fa-solid fa-circle-check"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- LIST PESANAN (Kode sebelumnya) -->
        <div class="space-y-6">
            @forelse($orders as $order)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-4 bg-gray-50 border-b border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center">
                        <div>
                            <p class="text-xs text-gray-500">Order ID: <span class="font-bold text-gray-700">#JENGKI-{{ $order->id }}</span></p>
                            <p class="text-xs text-gray-500">Tanggal: {{ $order->created_at->format('d M Y, H:i') }}</p>
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

                    <div class="divide-y divide-gray-50">
                        @foreach($order->items as $item)
                        <div class="p-5 flex items-start gap-4">
                            @if($item->barang)
                                <img src="{{ asset('storage/' . $item->barang->gambar) }}" alt="{{ $item->nama_barang_saat_checkout }}" class="w-16 h-16 object-cover rounded-xl bg-gray-100">
                            @else
                                <div class="w-16 h-16 rounded-xl bg-gray-100 flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-store-slash text-gray-400"></i></div>
                            @endif

                            <div class="flex-1">
                                <h3 class="font-bold text-gray-900 text-sm">{{ $item->nama_barang_saat_checkout }}</h3>
                                <p class="text-xs text-gray-500 mt-0.5">
                                    <span class="font-medium text-gray-700">{{ $item->kuantitas }} unit</span> x {{ $item->durasi }} Malam
                                </p>
                                <p class="text-[10px] text-gray-400 mt-1">
                                    {{ $item->tanggal_sewa->format('d M') }} - {{ $item->tanggal_kembali->format('d M') }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-emerald-600">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="p-4 bg-gray-50 border-t border-gray-100">
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-xs text-gray-500 font-medium uppercase tracking-wider">{{ $order->catatan_user ?? 'Transfer' }}</span>
                            <span class="text-lg font-bold text-gray-900">Rp{{ number_format($order->total_harga_pesanan, 0, ',', '.') }}</span>
                        </div>

                        <!-- Area Aksi -->
                        @if($order->status == 'menunggu pembayaran')
                            <div class="p-4 bg-orange-50 border border-orange-100 rounded-lg">
                                <p class="text-xs text-orange-700 mb-3">Silakan transfer ke <span class="font-bold">BCA 123456789</span></p>
                                <form action="{{ route('frontend.order.uploadProof', $order->id) }}" method="POST" enctype="multipart/form-data" class="flex gap-2">
                                    @csrf
                                    <input type="file" name="bukti_pembayaran" required class="block w-full text-xs text-gray-500 border border-gray-300 rounded-lg cursor-pointer bg-white">
                                    <button type="submit" class="bg-gray-900 text-white px-4 py-2 rounded-lg text-xs font-bold hover:bg-gray-800">Upload</button>
                                </form>
                            </div>
                        @elseif($order->status == 'menunggu konfirmasi')
                            <div class="p-3 bg-blue-50 border border-blue-100 rounded-lg flex justify-between items-center">
                                <p class="text-xs text-blue-700">Pesanan sedang dicek Admin.</p>
                                <a href="https://wa.me/6281234567890" target="_blank" class="text-xs bg-white border border-blue-200 text-blue-600 px-3 py-1.5 rounded-md font-medium hover:bg-blue-50">Chat Admin</a>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-white p-10 rounded-2xl shadow-sm border border-gray-100 text-center">
                    <p class="text-gray-500">Belum ada pesanan.</p>
                    <a href="{{ route('frontend.produk.index') }}" class="inline-block mt-4 text-emerald-600 hover:underline font-medium">Sewa Sekarang</a>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
