@extends('layouts.app')

@section('content')

    <!-- Pakai pt-24 agar tidak tertutup navbar fixed di atas -->
    <div class="w-full max-w-7xl mx-auto py-24 px-6">

        <!-- Breadcrumb (Jejak Halaman) -->
        <div class="mb-6 text-sm">
            <a href="/" class="text-emerald-600 hover:underline">Home</a>
            <span class="text-gray-500 mx-2">&gt;</span>
            <span class="text-gray-800 font-medium">{{ $barang->nama_barang }}</span>
        </div>

        <!-- Konten Detail Produk -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
            <div class="grid grid-cols-1 md:grid-cols-2">

                <!-- Sisi Kiri: Gambar Produk -->
                <div class="bg-gray-100">
                    <img src="{{ asset('storage/' . $barang->gambar) }}" alt="{{ $barang->nama_barang }}"
                         class="w-full h-full min-h-[300px] md:h-full md:min-h-[500px] object-cover">
                </div>

                <!-- Sisi Kanan: Info & Tombol Aksi -->
                <div class="p-8 md:p-10 flex flex-col">
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3">
                        {{ $barang->nama_barang }}
                    </h1>

                    <!-- Rating (Statis, bisa kamu buat dinamis nanti) -->
                    <div class="flex items-center mb-4">
                        <i class="fa-solid fa-star text-yellow-400"></i>
                        <i class="fa-solid fa-star text-yellow-400"></i>
                        <i class="fa-solid fa-star text-yellow-400"></i>
                        <i class="fa-solid fa-star text-yellow-400"></i>
                        <i class="fa-solid fa-star-half-alt text-yellow-400"></i>
                        <span class="text-gray-500 text-sm ml-2">(4.5 / 500+ Reviews)</span>
                    </div>

                    <!-- Harga -->
                    <p class="text-emerald-600 font-bold text-4xl mb-6">
                        Rp{{ number_format($barang->harga_sewa, 0, ',', '.') }}
                        <span class="text-lg font-normal text-gray-500">/hari</span>
                    </p>

                    <!-- Deskripsi -->
                    <div class="text-gray-700 leading-relaxed mb-8">
                        <h3 class="font-semibold text-lg text-gray-800 mb-2">Deskripsi</h3>

                        {{-- Ini akan menampilkan deskripsi dari database --}}
                        {{-- Jika tidak ada deskripsi, akan tampil pesan default --}}
                        <p>
                            {!! nl2br(e($barang->deskripsi ?? 'Deskripsi untuk barang ini belum tersedia.')) !!}
                        </p>
                    </div>

                    <!-- Spacer agar tombol ke bawah -->
                    <div class="flex-grow"></div>

                    <!-- Form Peminjaman (Sederhana) -->
                    {{--
                        CATATAN:
                        Action form ini (#) belum berfungsi.
                        Ini adalah langkah selanjutnya (membuat logic booking)
                    --}}
                    <form action="#" method="POST">
                        @csrf

                        <!--
                          PERBAIKAN DI SINI!
                          Karena primary key kamu 'id_barang', kita harus pakai $barang->id_barang
                        -->
                        <input type="hidden" name="barang_id" value="{{ $barang->id_barang }}">

                        <!-- Input Tanggal (Ini Fungsionalitas Selanjutnya) -->
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                                <input type="date" id="tanggal_mulai" name="tanggal_mulai" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            </div>
                            <div>
                                <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                                <input type="date" id="tanggal_selesai" name="tanggal_selesai" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            </div>
                        </div>

                        <!-- Tombol Aksi -->
                        @if($barang->status == 'tersedia')
                            <button type="submit" class="w-full bg-emerald-600 text-white py-3 px-6 rounded-lg font-semibold text-lg hover:bg-emerald-700 transition-all duration-300">
                                <i class="fa-solid fa-calendar-check mr-2"></i>
                                Sewa Sekarang
                            </button>
                        @else
                            <button type="button" class="w-full bg-gray-400 text-gray-800 py-3 px-6 rounded-lg font-semibold text-lg cursor-not-allowed" disabled>
                                <i class="fa-solid fa-circle-xmark mr-2"></i>
                                Sedang Disewa
                            </button>
                        @endif
                    </form>

                </div>
            </div>
        </div>
    </div>

@endsection
