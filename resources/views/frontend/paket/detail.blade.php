@extends('layouts.app')

@section('content')

<div class="w-full max-w-7xl mx-auto py-24 px-6">

    <!-- Breadcrumb -->
    <div class="mb-6 text-sm flex items-center gap-2 text-gray-500">
        <a href="{{ route('frontend.paket.index') }}" class="hover:text-emerald-600 transition">
            <i class="fa-solid fa-arrow-left mr-1"></i> Kembali ke Semua Paket
        </a>
        <span>/</span>
        <span class="text-gray-900 font-medium truncate">{{ $paket->nama_paket }}</span>
    </div>

    <!-- Alert Sukses/Error -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-100 border border-emerald-200 text-emerald-700 rounded-xl flex items-center gap-2 animate-fade-in-up">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 p-4 bg-red-100 border border-red-200 text-red-700 rounded-xl flex items-center gap-2 animate-fade-in-up">
            <i class="fa-solid fa-triangle-exclamation"></i> {{ session('error') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-600 rounded-xl text-sm">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        </div>
    @endif

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
                            <span class="font-semibold mr-1">{{ $item->pivot->qty }}x</span> {{ $item->nama_barang }}
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

                    {{-- Durasi Sewa (Wajib ada untuk validasi controller) --}}
                    <div class="mb-6">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Durasi Sewa (Malam)</label>
                        <div class="flex items-center border border-gray-300 rounded-xl shadow-sm bg-white overflow-hidden w-fit">
                            <button type="button" onclick="changeDuration(-1)" class="w-10 h-10 text-gray-600 hover:text-emerald-600 font-bold transition hover:bg-gray-100">-</button>
                            <input type="number" name="durasi" id="durasi" value="1" min="1" class="w-16 text-center font-bold text-gray-900 border-0 bg-transparent focus:ring-0" readonly>
                            <button type="button" onclick="changeDuration(1)" class="w-10 h-10 text-gray-600 hover:text-emerald-600 font-bold transition hover:bg-gray-100">+</button>
                        </div>
                    </div>

                    {{-- Input Kuantitas Paket --}}
                    <div class="mb-6">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Kuantitas Paket</label>
                        <div class="flex items-center border border-gray-300 rounded-xl shadow-sm bg-white overflow-hidden w-fit">
                            <button type="button" onclick="changeQuantity(-1)" class="w-10 h-10 text-gray-600 hover:text-emerald-600 font-bold transition hover:bg-gray-100">-</button>
                            <input type="number" name="kuantitas" id="kuantitas" value="1" min="1" class="w-16 text-center font-bold text-gray-900 border-0 bg-transparent focus:ring-0" readonly>
                            <button type="button" onclick="changeQuantity(1)" class="w-10 h-10 text-gray-600 hover:text-emerald-600 font-bold transition hover:bg-gray-100">+</button>
                        </div>
                    </div>

                    {{-- Input Tanggal Mulai --}}
                    <div class="mb-8">
                          <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Tanggal Mulai Sewa</label>
                          <input type="date" name="tanggal_mulai" required min="{{ date('Y-m-d') }}"
                                class="w-full border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm py-3 px-4 bg-white h-12 shadow-sm">
                    </div>

                    <!-- TOMBOL AKSI -->
                    <div class="space-y-4">
                        {{-- Tombol Masukkan Keranjang (Action: cart.addPaket) --}}
                        <button type="submit" onclick="setAction('cart')" class="w-full bg-emerald-600 text-white py-3 rounded-xl font-bold hover:bg-emerald-700 transition shadow-lg shadow-emerald-300 flex items-center justify-center gap-2">
                            <i class="fa-solid fa-cart-plus"></i>
                            Masukkan Keranjang
                        </button>

                        {{-- Tombol Sewa Sekarang (Action: cart.rental.paket.now) --}}
                        <button type="submit" onclick="setAction('rent')" class="w-full bg-white border-2 border-emerald-900 text-emerald-900 py-3 rounded-xl font-bold hover:bg-gray-50 transition shadow-md flex items-center justify-center gap-2">
                             <i class="fa-solid fa-bolt"></i> Sewa Sekarang (Checkout)
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<script>
    // Helper untuk ubah qty
    function changeQuantity(amount) {
        const qtyInput = document.getElementById('kuantitas');
        let currentQty = parseInt(qtyInput.value);
        let newQty = currentQty + amount;
        if (newQty < 1) newQty = 1;
        qtyInput.value = newQty;
    }

    // Helper untuk ubah durasi
    function changeDuration(amount) {
        const durInput = document.getElementById('durasi');
        let currentDur = parseInt(durInput.value);
        let newDur = currentDur + amount;
        if (newDur < 1) newDur = 1;
        durInput.value = newDur;
    }

    // Set Action Form (Cart vs Rent Now)
    function setAction(type) {
        const form = document.getElementById('orderForm');
        if (type === 'cart') {
            form.action = "{{ route('cart.addPaket', $paket->id_paket) }}";
        } else {
            // Gunakan route baru khusus sewa paket
            form.action = "{{ route('cart.rental.paket.now', $paket->id_paket) }}";
        }
    }

    // Auto set tanggal hari ini
    const today = new Date().toISOString().split("T")[0];
    const dateInput = document.querySelector('input[name="tanggal_mulai"]');
    dateInput.min = today;
    if (!dateInput.value) {
         dateInput.value = today;
    }
</script>

@endsection
