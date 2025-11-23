@extends('layouts.app')

@section('content')

<script src="https://cdn.tailwindcss.com"></script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap');
body { font-family: 'Inter', sans-serif; background-color: #f7fbee; }
</style>

<div class="w-full max-w-7xl mx-auto py-24 px-6">

<!-- Breadcrumb -->
<div class="mb-6 text-sm flex items-center gap-2 text-gray-500">
    <a href="{{ route('frontend.paket.index') }}" class="hover:text-emerald-600 transition">
        <i class="fa-solid fa-arrow-left mr-1"></i> Kembali ke Semua Paket
    </a>
    <span>/</span>
    <span class="text-gray-900 font-medium truncate">{{ $paket->nama_paket }}</span>
</div>

<div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
    <div class="grid grid-cols-1 md:grid-cols-2">

        <!-- Detail Paket & Items -->
        <div class="p-8 md:p-12 flex flex-col justify-center">

            <span class="inline-block bg-emerald-50 text-emerald-700 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide mb-4 w-fit">
                PAKET SPESIAL
            </span>

            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">{{ $paket->nama_paket }}</h1>

            <!-- Harga Utama -->
            <div class="flex items-center gap-4 mb-6">
                <p class="text-4xl text-emerald-600 font-bold">
                    Rp{{ number_format($paket->harga_paket, 0, ',', '.') }}
                </p>
                <span class="text-sm text-gray-400 font-medium bg-gray-100 px-3 py-1 rounded-full">/ Hari</span>
            </div>

            <!-- Deskripsi -->
            <div class="prose prose-sm text-gray-600 mb-8 border-b pb-6">
                <h3 class="text-gray-900 font-semibold mb-2">Deskripsi Paket</h3>
                <p>{!! nl2br(e($paket->deskripsi ?? 'Tidak ada deskripsi paket.')) !!}</p>
            </div>
            
            <!-- Daftar Item -->
            <div class="text-gray-700 mb-8">
                <h3 class="text-gray-900 font-semibold text-lg mb-3 flex items-center">
                    <i class="fa-solid fa-list-check text-emerald-600 mr-2"></i> Isi Paket:
                </h3>
                <ul class="space-y-2">
                    @forelse ($paket->items as $item)
                    <li class="flex items-center text-sm">
                        <i class="fa-solid fa-check-circle text-emerald-500 mr-2 flex-shrink-0"></i>
                        <span class="font-semibold">{{ $item->pivot->qty }}x</span> {{ $item->nama_barang }}
                    </li>

                    @empty
                        <li class="text-sm text-gray-500 italic">Daftar item kosong.</li>
                    @endforelse
                </ul>
            </div>
        </div>
        
        <!-- Form Aksi (Add to Cart/Rent Now) -->
        <div class="bg-emerald-50 p-8 md:p-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 border-b pb-3">Pesan Paket Ini</h2>

            <!-- FORM PEMESANAN PAKET -->
            <form action="{{ route('cart.addPaket', $paket->id_paket) }}" method="POST" id="orderForm">
                @csrf
                
                {{-- Input Kuantitas (Asumsi Paket bisa disewa > 1 kali) --}}
                <div class="mb-8">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Kuantitas Paket</label>
                    <div class="flex items-center border border-gray-300 rounded-xl shadow-sm bg-white">
                        <button type="button" onclick="changeQuantity(-1)" class="w-10 h-10 text-gray-600 hover:text-emerald-600 text-lg font-bold transition rounded-l-xl hover:bg-gray-100">-</button>
                        <input type="number" name="kuantitas" id="kuantitas" value="1" min="1" max="100" {{-- Max 100 sebagai placeholder, sesuaikan dengan stok --}}
                               class="w-full text-center font-bold text-gray-900 border-0 bg-transparent focus:ring-0" readonly>
                        <button type="button" onclick="changeQuantity(1)" class="w-10 h-10 text-gray-600 hover:text-emerald-600 text-lg font-bold transition rounded-r-xl hover:bg-gray-100">+</button>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Stok item dalam paket diasumsikan mencukupi.</p>
                </div>

                {{-- Input Tanggal Mulai (untuk Add to Cart Paket, ini diperlukan) --}}
                <div class="mb-8">
                     <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Tanggal Mulai Sewa</label>
                     <input type="date" name="tanggal_mulai" required min="{{ date('Y-m-d') }}"
                            class="w-full border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm py-3 px-4 bg-white h-12 shadow-sm">
                </div>

                <!-- TOMBOL AKSI -->
                <div class="space-y-4">
                    {{-- Tombol Masukkan Keranjang (Action: cart.addPaket) --}}
                    <button type="submit" class="w-full bg-emerald-600 text-white py-3 rounded-xl font-bold hover:bg-emerald-700 transition shadow-lg shadow-emerald-300 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-cart-plus"></i>
                        Masukkan Keranjang
                    </button>
                    
                    {{-- Tombol Sewa Sekarang (Action: order.create) --}}
                    <a href="{{ route('order.create', $paket->id) }}" class="w-full block text-center border-2 border-gray-900 text-gray-900 py-3 rounded-xl font-bold hover:bg-gray-100 transition shadow-md">
                         <i class="fa-solid fa-bolt"></i> Sewa Sekarang (Checkout)
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>


</div>

<script>
function changeQuantity(amount) {
const qtyInput = document.getElementById('kuantitas');
let currentQty = parseInt(qtyInput.value);
let newQty = currentQty + amount;

    // Batasi min 1
    if (newQty &lt; 1) newQty = 1;
    
    // Batasi max 100 (Placeholder max, idealnya berdasarkan stok terendah dari item dalam paket)
    if (newQty &gt; 100) newQty = 100; 

    qtyInput.value = newQty;
}


</script>

@endsection