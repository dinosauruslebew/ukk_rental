@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen pt-24 pb-20">
    <div class="max-w-7xl mx-auto px-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Keranjang Sewa</h1>

        <!-- Alert -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-100 border border-emerald-200 text-emerald-700 rounded-xl">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-6 p-4 bg-red-100 border border-red-200 text-red-700 rounded-xl">{{ session('error') }}</div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Daftar Barang -->
            <div class="lg:col-span-2 space-y-4">
                @forelse($cartItems as $key => $item)
                    <div class="flex items-start gap-4 bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
                        {{-- Gambar: Cek apakah dari Paket atau Barang --}}
                        @php
                            $storagePath = (isset($item['type']) && $item['type'] == 'paket') ? 'paket/' : '';
                            // Jika path di DB sudah lengkap (e.g. 'paket/abc.jpg'), biarkan.
                            // Biasanya $item['gambar'] sudah berisi path relatif.
                        @endphp

                        <img src="{{ asset('storage/' . ($item['gambar'] ?? 'placeholder.jpg')) }}"
                             alt="{{ $item['nama_barang'] ?? 'Item' }}"
                             class="w-24 h-24 object-cover rounded-xl bg-gray-100">

                        <div class="flex-1">
                            <h3 class="font-bold text-gray-900">
                                {{-- Badge Paket --}}
                                @if(isset($item['type']) && $item['type'] == 'paket')
                                    <span class="bg-teal-100 text-teal-700 text-[10px] px-2 py-0.5 rounded mr-1">PAKET</span>
                                @endif
                                {{ $item['nama_barang'] ?? 'Item' }}
                            </h3>

                            <p class="text-sm text-gray-500 mt-1">
                                {{ $item['kuantitas'] ?? 1 }} unit x {{ $item['durasi'] ?? 1 }} Malam
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ isset($item['tanggal_mulai']) ? \Carbon\Carbon::parse($item['tanggal_mulai'])->format('d M') : '-' }}
                                -
                                {{ isset($item['tanggal_selesai']) ? \Carbon\Carbon::parse($item['tanggal_selesai'])->format('d M') : '-' }}
                            </p>
                        </div>

                        <div class="text-right flex flex-col items-end h-full">
                            <p class="text-lg font-bold text-emerald-600">Rp{{ number_format($item['subtotal'] ?? 0, 0, ',', '.') }}</p>

                            <form action="{{ route('cart.remove') }}" method="POST" class="mt-auto">
                                @csrf
                                <input type="hidden" name="cartKey" value="{{ $key }}">
                                <button type="submit" class="text-xs text-red-500 hover:text-red-700 hover:underline font-medium">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="bg-white p-10 rounded-2xl text-center border border-gray-100">
                        <p class="text-gray-500">Keranjang kosong.</p>
                        <a href="{{ route('frontend.produk.index') }}" class="inline-block mt-4 text-emerald-600 hover:underline">Mulai Belanja</a>
                    </div>
                @endforelse
            </div>

            <!-- Ringkasan -->
            @if(count($cartItems) > 0)
                <div class="lg:col-span-1">
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 sticky top-24">
                        <h3 class="text-lg font-bold text-gray-900 border-b pb-3 mb-4">Ringkasan</h3>

                        <div class="flex justify-between font-bold text-gray-900 text-lg mb-6">
                            <span>Total</span>
                            <span>Rp{{ number_format($totalKeseluruhan, 0, ',', '.') }}</span>
                        </div>

                        <!-- Data Diri Form (Minimal) -->
                        <form action="{{ route('checkout.store') }}" method="POST">
                            @csrf
                            <!-- ... (isi form data diri dan metode pembayaran kamu yang sebelumnya) ... -->
                             <div class="space-y-4 mb-6">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 mb-1">No WA</label>
                                    <input type="number" name="no_hp" value="{{ Auth::user()->no_hp ?? '' }}" class="w-full border-gray-200 rounded text-sm" required placeholder="08...">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 mb-1">Alamat</label>
                                    <input type="text" name="alamat" value="{{ Auth::user()->alamat ?? '' }}" class="w-full border-gray-200 rounded text-sm" required placeholder="Alamat lengkap">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 mb-1">Metode Bayar</label>
                                    <select name="metode_pembayaran" class="w-full border-gray-200 rounded text-sm">
                                        <option value="transfer">Transfer Bank</option>
                                        <option value="cod">COD (Bayar di Tempat)</option>
                                    </select>
                                </div>
                            </div>

                            <button type="submit" class="w-full bg-gray-900 text-white py-3 rounded-xl font-bold hover:bg-gray-800 transition flex items-center justify-center gap-2">
                                Buat Pesanan
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
