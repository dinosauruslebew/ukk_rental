@extends('layouts.app')

@section('content')

    <!-- Bungkus utama dengan background abu-abu tipis agar card terlihat kontras -->
    <div class="min-h-screen bg-gray-50 pt-24 pb-20 px-6">
        <div class="max-w-7xl mx-auto">

            <!-- HEADER: Judul & Search -->
            <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Products</h1>
                    <p class="text-gray-500 text-sm mt-1">Pilih perlengkapan petualanganmu</p>
                </div>

                <!-- Search Bar (Style Pill seperti referensi) -->
                <form action="{{ route('frontend.produk.index') }}" method="GET" class="w-full md:w-96">
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    <div class="relative group">
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search product..."
                            class="w-full pl-10 pr-4 py-3 bg-white border border-gray-200 rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent shadow-sm transition-all"
                        >
                        <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 group-focus-within:text-gray-900"></i>
                    </div>
                </form>
            </div>

            <!--
                ========================================
                CATEGORY TABS (Pills Navigation)
                ========================================
            -->
            <div class="mb-10 overflow-x-auto pb-2 no-scrollbar">
            <div class="flex gap-2 md:gap-4 min-w-max">
                <a href="{{ route('frontend.produk.index', ['category' => 'all']) }}"
                class="px-5 py-2 rounded-full text-sm font-medium border border-gray-400
                {{ $activeCategory == 'all' ? 'bg-gray-900 text-white' : 'text-gray-500 hover:bg-gray-200' }}">
                    All Products
                </a>

                @foreach($kategori as $cat)
                    <a href="{{ route('frontend.produk.index', ['category' => $cat]) }}"
                    class="px-5 py-2 rounded-full text-sm font-medium capitalize border border-gray-400
                    {{ $activeCategory == $cat ? 'bg-gray-900 text-white' : 'text-gray-500 hover:bg-gray-200' }}">
                        {{ $cat }}
                    </a>
                @endforeach

                </div>

                </div>
            </div>

            <!--
========================================
PRODUCT GRID
========================================
-->

@if($activeCategory == 'paket')
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 items-stretch">

    @foreach($barang as $item)
    {{-- PERBAIKAN: Menambahkan min-h-[400px] agar card tidak terlalu lonjong/tinggi --}}
    <div class="bg-white border border-emerald-100 rounded-3xl shadow-lg hover:shadow-xl p-6 relative transition flex flex-col justify-between h-full min-h-[300px]">

        <div class="flex-grow">
            <!-- Nama Paket -->
            <h3 class="text-xl font-extrabold text-gray-900 uppercase mb-1 leading-snug">
                {{ $item->nama_paket }}
            </h3>

            <!-- Harga -->
            <p class="text-3xl font-extrabold text-emerald-600 mt-2">
                Rp {{ number_format($item->harga_paket, 0, ',', '.') }}
            </p>
            <p class="text-sm text-gray-500 mb-4">Per hari</p>

            <!-- LIST BARANG DALAM PAKET -->
            <div class="mb-6 pt-4 border-t border-gray-100">
                <p class="text-sm font-semibold text-gray-700 mb-2">Termasuk:</p>
                
                <ul class="space-y-1 text-sm text-gray-700">
                    @php $totalItems = count($item->items); @endphp
                    
                    {{-- Loop hanya untuk 3 item pertama --}}
                    @foreach($item->items->take(3) as $barangPaket)
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-check text-emerald-600 mt-1"></i>
                        <span>
                            {{ $barangPaket->nama_barang }}
                            @if($barangPaket->pivot->qty > 1)
                                (x{{ $barangPaket->pivot->qty }})
                            @endif
                        </span>
                    </li>
                    @endforeach
                    
                    {{-- Tambahan untuk item yang disembunyikan --}}
                    @if ($totalItems > 3)
                        <li class="text-xs text-gray-500 italic mt-2">
                            + {{ $totalItems - 3 }} item lainnya...
                        </li>
                    @endif
                </ul>
            </div>
        </div>

        <!-- Tombol CTA (Ditempatkan di bagian bawah karena flex-col justify-between) -->
        <a href="{{ route('frontend.paket.detail', $item->id_paket) }}"
            class="mt-auto block w-full text-center bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl py-3 transition shadow-md">
            Pilih Paket Ini
        </a>

    </div>
    @endforeach

</div>

            @else
                {{-- Tampilan barang biasa --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @forelse($barang as $item)
                        <!-- Card Wrapper -->
                        <div class="bg-white p-4 rounded-[2rem] shadow-sm hover:shadow-md transition-shadow duration-300 border border-gray-100 group relative">

                            <!-- Gambar Produk -->
                            <div class="relative bg-gray-100 rounded-[1.5rem] overflow-hidden aspect-square mb-4 {{ $item->stok == 0 ? 'opacity-50 grayscale' : '' }}">
                                 <!-- Link Full Cover ke Detail -->
                                @if($item->stok > 0)
                                    <a href="{{ route('frontend.produk.detail', $item) }}" class="absolute inset-0 z-10"></a>
                                @endif

                                <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->nama_barang }}"
                                        class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-500">
                                    @if($item->stok == 0)
                                        <div class="absolute top-3 left-3 bg-red-600 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">
                                            Stok Habis
                                        </div>
                                    @endif

                                <!-- Tombol Panah Kecil (Hiasan/Link ke Detail) -->
                                <div class="absolute bottom-3 right-3 bg-white w-8 h-8 rounded-full flex items-center justify-center shadow-sm z-20 pointer-events-none group-hover:bg-gray-900 group-hover:text-white transition-colors">
                                    <i class="fa-solid fa-arrow-right -rotate-45 text-xs"></i>
                                </div>
                            </div>

                            <!-- Info Produk -->
                            <div class="px-1">
                                <a href="{{ route('frontend.produk.detail', $item) }}">
                                    <h3 class="font-bold text-gray-900 text-lg truncate hover:text-emerald-600 transition">
                                        {{ $item->nama_barang }}
                                    </h3>
                                </a>

                                <!-- Harga & Stok -->
                                <div class="flex justify-between items-end mt-2">
                                    <div>
                                        <p class="text-gray-900 font-semibold">
                                            Rp{{ number_format($item->harga_sewa, 0, ',', '.') }}
                                        </p>
                                        <p class="text-xs text-gray-400 mt-0.5">/hari</p>
                                    </div>

                                    <div class="text-right">
                                        <p class="text-xs text-gray-500">
                                            Stok: <span class="font-medium text-gray-900">{{ $item->stok }}</span>
                                        </p>
                                        <!-- Status Dot -->
                                        <div class="flex items-center justify-end gap-1 mt-1">
                                            <span class="w-2 h-2 rounded-full {{ $item->status == 'tersedia' ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                                            <span class="text-[10px] text-gray-400 uppercase">{{ $item->status == 'tersedia' ? 'Ready' : 'Out' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    @empty
                        <!-- Tampilan Kosong -->
                        <div class="col-span-full flex flex-col items-center justify-center py-20 text-center">
                            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                <i class="fa-solid fa-box-open text-3xl text-gray-400"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Produk tidak ditemukan</h3>
                            <p class="text-gray-500 mt-1">Coba pilih kategori lain.</p>
                            <a href="{{ route('frontend.produk.index') }}" class="mt-4 text-sm font-medium text-gray-900 underline hover:text-emerald-600">
                                Reset Filter
                            </a>
                        </div>
                    @endforelse
                </div>
            @endif
        </div>
    </div>
@endsection