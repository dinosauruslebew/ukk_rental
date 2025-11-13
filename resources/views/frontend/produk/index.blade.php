@extends('layouts.app')

@section('content')

    <!-- Pakai pt-24 agar tidak tertutup navbar fixed -->
    <div class="w-full max-w-7xl mx-auto py-24 px-6">

        <!-- Judul Halaman -->
        <div class="mb-10">
            <h1 class="text-4xl font-bold text-gray-900 text-center">All Products</h1>
            <p class="text-lg text-gray-600 text-center mt-2">Temukan semua perlengkapan yang kamu butuhkan di sini.</p>
        </div>

        <!-- Ini adalah section 'products' kamu yang lama, kita pindah ke sini -->
        <section id="products-page">
            <div class="max-w-7xl mx-auto">

                <!--
                  =======================================================
                  --- PEROMBAKAN UTAMA DIMULAI DARI SINI ---
                  Filter Bar sekarang adalah FORM yang berfungsi
                  =======================================================
                -->
                <form action="{{ route('frontend.produk.index') }}" method="GET" class="mb-8">
                    <div class="flex flex-col md:flex-row gap-4 items-center p-4 bg-white rounded-xl shadow border border-gray-100">

                        <!-- 1. Search Bar BARU -->
                        <div class="flex-grow w-full">
                            <label for="search" class="sr-only">Cari...</label>
                            <input
                                type="text"
                                name="search"
                                id="search"
                                placeholder="Cari tenda, ransel, kompor..."
                                value="{{ request('search') }}" {{-- Ini biar "inget" pencarian lama --}}
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                            >
                        </div>

                        <!-- 2. Filter Rentang Harga (Dinamis) -->
                        <div class="w-full md:w-auto">
                            <label for="price_range" class="sr-only">Rentang Harga</label>
                            <select name="price_range" id="price_range" class="w-full border-gray-300 rounded-lg p-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="">Price Range</option>
                                {{-- Loop data $priceRanges dari Controller --}}
                                @foreach($priceRanges as $value => $label)
                                    <option value="{{ $value }}" {{ request('price_range') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- 3. Filter Ketersediaan (Status) -->
                        <div class="w-full md:w-auto">
                            <label for="availability" class="sr-only">Ketersediaan</label>
                            <select name="availability" id="availability" class="w-full border-gray-300 rounded-lg p-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="">Availability</option>
                                {{-- Kita hanya peduli 'tersedia', karena itu yang dicari user --}}
                                <option value="tersedia" {{ request('availability') == 'tersedia' ? 'selected' : '' }}>
                                    Tersedia
                                </option>
                            </select>
                        </div>

                        <!-- 4. Tombol Filter & Reset -->
                        <div class="flex gap-2 w-full md:w-auto">
                            <button type="submit" class="w-full md:w-auto bg-emerald-600 text-white px-5 py-2 rounded-lg font-semibold hover:bg-emerald-700 transition">
                                Filter
                            </button>
                            <a href="{{ route('frontend.produk.index') }}" class="w-full md:w-auto text-center bg-gray-200 text-gray-700 px-5 py-2 rounded-lg hover:bg-gray-300 transition">
                                Reset
                            </a>
                        </div>
                    </div>
                </form>
                <!-- --- AKHIR DARI PEROMBAKAN FORM --- -->


                <!-- Product Grid (Menggunakan loop $barang kamu) -->
                {{-- Ini tidak berubah, karena $barang sudah difilter dari Controller --}}
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-8">

                    @forelse($barang as $item)
                        <!-- Product Card (Sesuai Desain) -->
                        <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow border border-gray-100">
                            <!-- Link ke Halaman Detail -->
                            <a href="{{ route('frontend.produk.detail', $item) }}">
                                <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->nama_barang }}"
                                     class="w-full h-56 object-cover transition-transform duration-300 hover:scale-105">
                            </a>
                            <div class="p-5">
                                <a href="{{ route('frontend.produk.detail', $item) }}">
                                    <h4 class="font-bold text-lg text-gray-900 truncate" title="{{ $item->nama_barang }}">{{ $item->nama_barang }}</h4>
                                </a>

                                <!-- Rating (Statis) -->
                                <div class="flex items-center my-2">
                                    <i class="fa-solid fa-star text-yellow-400"></i>
                                    <i class="fa-solid fa-star text-yellow-400"></i>
                                    <i class="fa-solid fa-star text-yellow-400"></i>
                                    <i class="fa-solid fa-star text-yellow-400"></i>
                                    <i class="fa-solid fa-star-half-alt text-yellow-400"></i>
                                    <span class="text-gray-500 text-sm ml-2">(4.5 / 500+ Reviews)</span>
                                </div>

                                <div class="flex justify-between items-center mt-4">
                                    <!-- Harga -->
                                    <p class="text-emerald-600 font-bold text-xl">
                                        Rp{{ number_format($item->harga_sewa, 0, ',', '.') }}<span class="text-sm font-normal text-gray-500">/hari</span>
                                    </p>

                                    <!-- Tombol Aksi (Sesuai Ketersediaan) -->
                                    @if($item->status == 'tersedia')
                                        <button classg="bg-emerald-600 text-white px-5 py-2 rounded-lg font-semibold hover:bg-emerald-700 transition-all text-sm">
                                            Rent Now
                                        </button>
                                    @else
                                        <button class="bg-gray-300 text-gray-600 px-5 py-2 rounded-lg font-semibold cursor-not-allowed text-sm" disabled>
                                            Waitlist
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        {{-- Pesan kalau hasil filter kosong --}}
                        <p class="col-span-full text-center text-gray-500 italic text-lg py-10">
                            Oops! Barang yang kamu cari tidak ditemukan. <br>
                            Coba reset filter atau ganti kata kuncimu.
                        </p>
                    @endforelse
                </div>

            </div>
        </section>

    </div>
@endsection
