@extends('layouts.app')

@section('content')
<section class="pt-28 pb-16 px-6 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-10 text-center">
            <h1 class="text-4xl font-bold text-gray-900">All Products</h1>
            <p class="text-gray-600 mt-2 text-lg">Temukan semua perlengkapan outdoor terbaik untuk petualanganmu.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
            <!-- Sidebar Filter -->
            <aside class="bg-white rounded-xl shadow-md p-6 border border-gray-100 h-fit">
                <form action="{{ route('frontend.produk.index') }}" method="GET" class="space-y-6">

                    <!-- Search -->
                    <div>
                        <label for="search" class="block text-sm font-semibold text-gray-700 mb-2">Search</label>
                        <input type="text" name="search" id="search" placeholder="Cari tenda, kompor, dll..."
                            value="{{ request('search') }}"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
                    </div>

                    <!-- Category Filter -->
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 mb-3">By Category</h3>
                        <div class="space-y-2">
                            <label class="flex items-center gap-2">
                                <input type="radio" name="category" value="" {{ request('category') == '' ? 'checked' : '' }}
                                    class="text-emerald-600 focus:ring-emerald-500">
                                <span>All Categories</span>
                            </label>
                            {{-- @foreach($categories as $cat)
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="category" value="{{ $cat->kategori }}"
                                        {{ request('category') == $cat->kategori ? 'checked' : '' }}
                                        class="text-emerald-600 focus:ring-emerald-500">
                                    <span>{{ ucfirst($cat->kategori) }}</span>
                                </label>
                            @endforeach --}}
                        </div>
                    </div>

                    <!-- Price Range -->
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 mb-2">Price Range</h3>
                        <select name="price_range" id="price_range"
                            class="w-full border-gray-300 rounded-lg p-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="">All Prices</option>
                            @foreach($priceRanges as $value => $label)
                                <option value="{{ $value }}" {{ request('price_range') == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Availability -->
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 mb-2">Availability</h3>
                        <select name="availability" id="availability"
                            class="w-full border-gray-300 rounded-lg p-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="">All</option>
                            <option value="tersedia" {{ request('availability') == 'tersedia' ? 'selected' : '' }}>Available</option>
                            <option value="tidak tersedia" {{ request('availability') == 'tidak tersedia' ? 'selected' : '' }}>Unavailable</option>
                        </select>
                    </div>

                    <!-- Buttons -->
                    <div class="flex flex-col gap-2">
                        <button type="submit"
                            class="bg-emerald-600 text-white w-full py-2 rounded-lg font-semibold hover:bg-emerald-700 transition">
                            Apply Filter
                        </button>
                        <a href="{{ route('frontend.produk.index') }}"
                            class="bg-gray-200 text-gray-700 w-full py-2 rounded-lg text-center font-semibold hover:bg-gray-300 transition">
                            Reset
                        </a>
                    </div>
                </form>
            </aside>

            <!-- Products -->
            <div class="md:col-span-3">
                @if($barang->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($barang as $item)
                            <div
                                class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-all border border-gray-100 group">
                                <a href="{{ route('frontend.produk.detail', $item) }}">
                                    <img src="{{ asset('storage/' . $item->gambar) }}"
                                        alt="{{ $item->nama_barang }}"
                                        class="w-full h-56 object-cover group-hover:scale-105 transition-transform duration-500">
                                </a>

                                <div class="p-5">
                                    <h4 class="font-bold text-gray-900 text-lg truncate"
                                        title="{{ $item->nama_barang }}">
                                        {{ $item->nama_barang }}
                                    </h4>

                                    <p class="text-sm text-gray-500 mt-1">
                                        {{ ucfirst($item->kategori ?? 'Peralatan Outdoor') }}
                                    </p>

                                    <!-- Rating -->
                                    <div class="flex items-center mt-2 text-yellow-400">
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-regular fa-star-half-stroke"></i>
                                        <span class="text-gray-500 text-sm ml-2">(4.5)</span>
                                    </div>

                                    <div class="flex justify-between items-center mt-4">
                                        <p class="text-emerald-600 font-bold text-lg">
                                            Rp{{ number_format($item->harga_sewa, 0, ',', '.') }}
                                            <span class="text-sm text-gray-500 font-normal">/hari</span>
                                        </p>

                                        @if($item->status == 'tersedia')
                                            <a href="{{ route('frontend.produk.detail', $item) }}"
                                                class="bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-emerald-700 transition">
                                                Rent Now
                                            </a>
                                        @else
                                            <button
                                                class="bg-gray-300 text-gray-600 px-4 py-2 rounded-lg text-sm font-semibold cursor-not-allowed">
                                                Unavailable
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-center text-gray-500 italic mt-20 text-lg">Tidak ada produk ditemukan.</p>
                @endif

                {{-- <!-- Pagination -->
                <div class="mt-10 flex justify-center">
                    {{ $barang->links('pagination::tailwind') }}
                </div> --}}
            </div>
        </div>
    </div>
</section>
@endsection
