@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen pt-24 pb-20">
    <div class="max-w-7xl mx-auto px-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Keranjang Sewa</h1>
        <p class="text-gray-500 mb-8">Periksa barang sewaanmu sebelum melanjutkan ke pembayaran.</p>

        <!-- Alert -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-100 border border-emerald-200 text-emerald-700 rounded-xl">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-6 p-4 bg-red-100 border border-red-200 text-red-700 rounded-xl">{{ session('error') }}</div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Daftar Barang (Kolom Kiri) -->
            <div class="lg:col-span-2 space-y-4">
                @forelse($cartItems as $key => $item)
                    <div class="flex items-start gap-4 bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
                        {{-- Cek fallback gambar jika barang dihapus --}}
                        <img src="{{ asset('storage/' . ($item['gambar'] ?? 'placeholder.jpg')) }}" alt="{{ $item['nama_barang'] ?? 'Barang' }}" class="w-24 h-24 object-cover rounded-xl bg-gray-100">

                        <div class="flex-1">
                            <h3 class="font-bold text-gray-900">{{ $item['nama_barang'] ?? '[Barang Dihapus]' }}</h3>

                            <!-- PERBAIKAN: Tambahkan fallback '?? 1' dan '?? ...' -->
                            <p class="text-sm text-gray-500 mt-1">
                                Kuantitas: <span class="font-medium text-gray-700">{{ $item['kuantitas'] ?? 1 }} unit</span>
                            </p>
                            <p class="text-sm text-gray-500">
                                Durasi: <span class="font-medium text-gray-700">{{ $item['durasi'] ?? 1 }} Malam</span>
                            </p>
                            <p class="text-xs text-gray-500">
                                Tgl: <span class="font-medium text-gray-700">{{ \Carbon\Carbon::parse($item['tanggal_mulai'] ?? now())->format('d M') }} - {{ \Carbon\Carbon::parse($item['tanggal_selesai'] ?? now()->addDay())->format('d M') }}</span>
                            </p>
                        </div>

                        <div class="text-right flex flex-col items-end h-full">
                            <!-- PERBAIKAN: Tambahkan fallback 'total_harga' -->
                            <p class="text-lg font-bold text-emerald-600">Rp{{ number_format($item['subtotal'] ?? $item['total_harga'] ?? 0, 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-400">
                                @if(isset($item['harga_paket_satuan']))
                                    (@ Rp{{ number_format($item['harga_paket_satuan'], 0) }})
                                @else
                                    (Harga Lama)
                                @endif
                            </p>

                            <!-- Form Hapus Item -->
                            <form action="{{ route('cart.remove') }}" method="POST" class="mt-auto">
                                @csrf
                                <input type="hidden" name="cartKey" value="{{ $key }}">
                                <button type="submit" class="text-xs text-red-500 hover:text-red-700 hover:underline font-medium">
                                    <i class="fa-solid fa-trash-can mr-1"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="bg-white p-10 rounded-2xl shadow-sm border border-gray-100 text-center">
                        <p class="text-gray-500">Keranjang sewa kamu masih kosong.</p>
                        <a href="{{ route('frontend.produk.index') }}" class="inline-block mt-4 bg-gray-900 text-white px-5 py-2 rounded-lg font-semibold hover:bg-gray-800 text-sm">
                            Mulai Cari Barang
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Ringkasan (Kolom Kanan) -->
            @if(count($cartItems) > 0)
                <div class="lg:col-span-1">
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 sticky top-24">
                        <h3 class="text-lg font-bold text-gray-900 border-b pb-3 mb-4">Ringkasan Pesanan</h3>

                        <div class="space-y-2 mb-4">
                            <div class="flex justify-between text-gray-600">
                                <span>Subtotal ({{ count($cartItems) }} jenis barang)</span>
                                <span class="font-medium">Rp{{ number_format($totalKeseluruhan, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Biaya Admin</span>
                                <span class="font-medium text-emerald-600">Gratis</span>
                            </div>
                        </div>

                        <div class="flex justify-between font-bold text-gray-900 text-lg border-t pt-4 mb-6">
                            <span>Total Tagihan</span>
                            <span>Rp{{ number_format($totalKeseluruhan, 0, ',', '.') }}</span>
                        </div>

                        <!-- Form Checkout -->
                        <form action="{{ route('checkout.store') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-gray-900 text-white py-3 rounded-xl font-bold hover:bg-gray-800 transition shadow-lg shadow-gray-200 flex items-center justify-center gap-2">
                                <i class="fa-solid fa-lock"></i>
                                Lanjut ke Pembayaran
                            </button>
                        </form>
                        <p class="text-xs text-gray-400 mt-3 text-center">Pesananmu akan disimpan di halaman "Pesanan Saya".</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
