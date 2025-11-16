@extends('layouts.app')

@section('content')

    <div class="w-full max-w-7xl mx-auto py-24 px-6">

        <!-- Alert Sukses -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-100 border border-emerald-200 text-emerald-700 rounded-xl flex items-center gap-2 animate-fade-in-up">
                <i class="fa-solid fa-circle-check"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- Breadcrumb -->
        <div class="mb-6 text-sm flex items-center gap-2 text-gray-500">
            <a href="{{ route('frontend.produk.index') }}" class="hover:text-emerald-600 transition">
                <i class="fa-solid fa-arrow-left mr-1"></i> Back to Products
            </a>
            <span>/</span>
            <span class="text-gray-900 font-medium truncate">{{ $barang->nama_barang }}</span>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="grid grid-cols-1 md:grid-cols-2">

                <!-- Gambar Produk -->
                <div class="bg-gray-50 p-6 flex items-center justify-center relative">
                    <img src="{{ asset('storage/' . $barang->gambar) }}" alt="{{ $barang->nama_barang }}"
                         class="w-full h-auto max-h-[500px] object-contain rounded-2xl shadow-sm hover:scale-105 transition-transform duration-500">

                    <!-- Label Harga Paket (Jika Ada) -->
                    @if($barang->harga_2_malam || $barang->harga_3_malam)
                        <div class="absolute top-6 left-6 bg-orange-100 text-orange-700 px-3 py-1 rounded-full text-xs font-bold border border-orange-200 shadow-sm">
                            <i class="fa-solid fa-tag mr-1"></i> Tersedia Paket Hemat!
                        </div>
                    @endif
                </div>

                <!-- Info & Form -->
                <div class="p-8 md:p-12 flex flex-col justify-center">

                    @if($barang->kategori)
                        <span class="inline-block bg-emerald-50 text-emerald-700 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide mb-4 w-fit">
                            {{ $barang->kategori }}
                        </span>
                    @endif

                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">{{ $barang->nama_barang }}</h1>

                    <!-- Harga Utama (Dinamis via JS) -->
                    <div class="flex items-center gap-4 mb-6">
                         <p class="text-3xl text-emerald-600 font-bold" id="displayPrice">
                            Rp{{ number_format($barang->harga_sewa, 0, ',', '.') }}
                        </p>
                        <span class="text-sm text-gray-400 font-medium bg-gray-100 px-2 py-1 rounded-md" id="priceLabel">/ 1 Malam</span>
                    </div>

                    <!-- Deskripsi -->
                    <div class="prose prose-sm text-gray-600 mb-8">
                        <h3 class="text-gray-900 font-semibold mb-2">Description</h3>
                        <p>{!! nl2br(e($barang->deskripsi ?? 'Tidak ada deskripsi.')) !!}</p>
                    </div>

                    <!-- FORM PEMESANAN -->
                    <!-- Default action ke Add to Cart, nanti diubah via JS kalau klik Sewa Sekarang -->
                    <form action="{{ route('cart.add', $barang->id_barang) }}" method="POST" id="orderForm">
                        @csrf

                        <!-- Pilihan Durasi (Pills) -->
                        <div class="mb-6">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Pilih Durasi Sewa</label>
                            <div class="flex gap-3">
                                <!-- Opsi 1 Malam -->
                                <label class="cursor-pointer flex-1">
                                    <input type="radio" name="durasi" value="1" class="peer hidden" checked onchange="updatePrice(1)">
                                    <div class="text-center py-2 rounded-xl border border-gray-200 bg-white text-gray-600 peer-checked:bg-emerald-600 peer-checked:text-white peer-checked:border-emerald-600 transition hover:border-emerald-400 shadow-sm">
                                        <span class="block text-sm font-bold">1 Malam</span>
                                        <span class="text-[10px]">Rp{{ number_format($barang->harga_sewa/1000, 0) }}k</span>
                                    </div>
                                </label>

                                <!-- Opsi 2 Malam -->
                                <label class="cursor-pointer flex-1">
                                    <input type="radio" name="durasi" value="2" class="peer hidden" onchange="updatePrice(2)">
                                    <div class="text-center py-2 rounded-xl border border-gray-200 bg-white text-gray-600 peer-checked:bg-emerald-600 peer-checked:text-white peer-checked:border-emerald-600 transition hover:border-emerald-400 shadow-sm">
                                        <span class="block text-sm font-bold">2 Malam</span>
                                        <span class="text-[10px]">
                                            @if($barang->harga_2_malam) Rp{{ number_format($barang->harga_2_malam/1000, 0) }}k
                                            @else <span class="text-gray-300">--</span> @endif
                                        </span>
                                    </div>
                                </label>

                                <!-- Opsi 3 Malam -->
                                <label class="cursor-pointer flex-1">
                                    <input type="radio" name="durasi" value="3" class="peer hidden" onchange="updatePrice(3)">
                                    <div class="text-center py-2 rounded-xl border border-gray-200 bg-white text-gray-600 peer-checked:bg-emerald-600 peer-checked:text-white peer-checked:border-emerald-600 transition hover:border-emerald-400 shadow-sm">
                                        <span class="block text-sm font-bold">3 Malam</span>
                                        <span class="text-[10px]">
                                            @if($barang->harga_3_malam) Rp{{ number_format($barang->harga_3_malam/1000, 0) }}k
                                            @else <span class="text-gray-300">--</span> @endif
                                        </span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Input Tanggal Mulai -->
                        <div class="mb-8">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" required
                                   class="w-full border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm py-3 px-4 bg-gray-50">
                        </div>

                        <!-- TOMBOL AKSI -->
                        <div class="flex gap-3">
                            @if($barang->status == 'tersedia')
                                {{-- Tombol Masukkan Keranjang --}}
                                <button type="submit" onclick="setAction('cart')" class="flex-1 border-2 border-gray-900 text-gray-900 py-3 rounded-xl font-bold hover:bg-gray-50 transition flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-cart-plus"></i>
                                    <span class="hidden sm:inline">Keranjang</span>
                                </button>

                                {{-- Tombol Sewa Sekarang --}}
                                <button type="submit" onclick="setAction('rent')" class="flex-[2] bg-gray-900 text-white py-3 rounded-xl font-bold hover:bg-gray-800 transition shadow-lg shadow-gray-200 flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-bolt"></i>
                                    Sewa Sekarang
                                </button>
                            @else
                                <button type="button" class="w-full bg-gray-100 text-gray-400 py-4 rounded-xl font-bold cursor-not-allowed border border-gray-200" disabled>
                                    <i class="fa-solid fa-lock mr-2"></i> Stok Habis / Sedang Disewa
                                </button>
                            @endif
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- Javascript untuk Update Harga Real-time -->
    <script>
        // Data Harga dari Database ke JS
        const prices = {
            1: {{ $barang->harga_sewa }},
            2: {{ $barang->harga_2_malam ?? ($barang->harga_sewa * 2) }}, // Fallback kalau null
            3: {{ $barang->harga_3_malam ?? ($barang->harga_sewa * 3) }}  // Fallback kalau null
        };

        function updatePrice(duration) {
            const price = prices[duration];
            const formattedPrice = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(price);

            // Update tampilan
            document.getElementById('displayPrice').innerText = formattedPrice;
            document.getElementById('priceLabel').innerText = '/ ' + duration + ' Malam';
        }

        function setAction(type) {
            const form = document.getElementById('orderForm');
            if (type === 'cart') {
                form.action = "{{ route('cart.add', $barang->id_barang) }}";
            } else {
                form.action = "{{ route('rental.now', $barang->id_barang) }}";
            }
        }

        // Set min date to today
        document.querySelector('input[name="tanggal_mulai"]').min = new Date().toISOString().split("T")[0];
    </script>

@endsection
