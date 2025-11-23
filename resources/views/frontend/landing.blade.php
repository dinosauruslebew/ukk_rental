@extends('layouts.app')

@section('content')

<!-- hero section (Home) -->


<section id="home" class="relative w-full flex justify-center items-center pt-10 px-6 scroll-mt-20">

<div class="relative w-full max-w-7xl mx-auto"
    x-data="{ active: 0, images: [
       './utama.jpg',
       './home2.jpg',
       './home3.jpg',
       './home4.jpg'
    ] }"
    x-init="setInterval(() => { active = (active + 1) % images.length }, 5000)">

    <div class="relative overflow-hidden rounded-3xl shadow-2xl shadow-emerald-400/50 w-full h-[450px]">
        <template x-for="(img, index) in images" :key="index">
            <div
                class="absolute inset-0 transition-all duration-1000"
                x-show="active === index"

                x-transition:enter="transform ease-in-out duration-1000"
                x-transition:enter-start="translate-x-full"
                x-transition:enter-end="translate-x-0"

                x-transition:leave="transform ease-in-out duration-1000"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="-translate-x-full"
            >
                <img :src="img" alt="Gallery image"
                        class="w-full h-full object-cover brightness-75 rounded-3xl">
            </div>
        </template>
    </div>

    <div class="absolute inset-0 flex flex-col items-start justify-center px-10 md:px-20 text-white">
        <h2 class="text-4xl md:text-6xl font-extrabold mb-3 drop-shadow-xl mt-2 max-w-2xl leading-tight tracking-wide">
            Adventure Awaits, Gear Ready!
        </h2>
        <p class="text-lg md:text-xl mb-6 drop-shadow-md max-w-md font-medium text-gray-100">
            Sewa perlengkapan camping dan hiking terbaik tanpa ribet, langsung siap berangkat.
        </p>
        <a href="#products" class="px-8 py-3 bg-yellow-400 text-gray-900 font-bold rounded-full shadow-lg hover:bg-yellow-500 transition duration-300 pointer-events-auto">
            Mulai Sewa Sekarang
        </a>
    </div>

</div>
</section>

<!-- SECTION NEW: Why Choose Us / Our Services -->
<section id="services" class="mt-16 px-6 text-center">
    <div class="max-w-7xl mx-auto">
        <span class="text-sm font-semibold uppercase tracking-widest text-emerald-600">Layanan Terbaik</span>
        <h3 class="text-3xl font-bold text-gray-900 mb-8">Kenapa Memilih Jengki Adventure?</h3>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            
            <!-- Layanan Item 1 -->
            <div class="flex flex-col items-center p-6 bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow border border-emerald-100 group">
                <div class="w-16 h-16 flex items-center justify-center rounded-full bg-emerald-100 group-hover:bg-emerald-600 transition duration-300 mb-4">
                    <i class="fa-solid fa-tent text-3xl text-emerald-600 group-hover:text-white"></i>
                </div>
                <span class="font-bold text-gray-900">Alat Selalu Bersih</span>
                <p class="text-sm text-gray-500 mt-1">Setiap alat dicuci bersih & disanitasi setelah digunakan.</p>
            </div>

            <!-- Layanan Item 2 -->
            <div class="flex flex-col items-center p-6 bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow border border-emerald-100 group">
                <div class="w-16 h-16 flex items-center justify-center rounded-full bg-emerald-100 group-hover:bg-emerald-600 transition duration-300 mb-4">
                    <i class="fa-solid fa-star text-3xl text-emerald-600 group-hover:text-white"></i>
                </div>
                <span class="font-bold text-gray-900">Kualitas Premium</span>
                <p class="text-sm text-gray-500 mt-1">Hanya menyediakan gear dari merek-merek ternama.</p>
            </div>

            <!-- Layanan Item 3 -->
            <div class="flex flex-col items-center p-6 bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow border border-emerald-100 group">
                <div class="w-16 h-16 flex items-center justify-center rounded-full bg-emerald-100 group-hover:bg-emerald-600 transition duration-300 mb-4">
                    <i class="fa-solid fa-wallet text-3xl text-emerald-600 group-hover:text-white"></i>
                </div>
                <span class="font-bold text-gray-900">Harga Terjangkau</span>
                <p class="text-sm text-gray-500 mt-1">Nikmati petualangan tanpa menguras isi dompet Anda.</p>
            </div>

            <!-- Layanan Item 4 -->
            <div class="flex flex-col items-center p-6 bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow border border-emerald-100 group">
                <div class="w-16 h-16 flex items-center justify-center rounded-full bg-emerald-100 group-hover:bg-emerald-600 transition duration-300 mb-4">
                    <i class="fa-solid fa-headset text-3xl text-emerald-600 group-hover:text-white"></i>
                </div>
                <span class="font-bold text-gray-900">Layanan Cepat</span>
                <p class="text-sm text-gray-500 mt-1">Proses booking dan pengambilan barang yang mudah dan cepat.</p>
            </div>

        </div>
    </div>
</section>

<!-- New Arrival Section (BARU - Sesuai Referensi image_bada25.jpg) -->
<section id="products" class="mt-16 mb-20 px-6">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <h3 class="text-3xl font-bold text-gray-900">New Arrival</h3>
            <a href="{{ route('frontend.produk.index') }}"
                class="text-emerald-600 font-semibold hover:underline">
                See all products &rarr;
            </a>

        </div>

        <!-- Grid (Ambil 4 barang terbaru dari $barang) -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            @forelse($barang->where('stok', '>', 0)
            ->where('status', 'tersedia')
            ->sortByDesc('created_at')
            ->take(4) as $item)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-shadow border border-gray-100">
                    <a href="{{ route('frontend.produk.detail', $item) }}">
                        <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->nama_barang }}"
                                class="w-full h-56 object-cover transition-transform duration-300 hover:scale-105">
                    </a>
                    <div class="p-5">
                        <a href="{{ route('frontend.produk.detail', $item) }}">
                            <h4 class="font-bold text-lg text-gray-900 truncate" title="{{ $item->nama_barang }}">{{ $item->nama_barang }}</h4>
                        </a>

                        <div class="flex items-center my-2">
                            <span class="text-gray-500 text-sm"><i class="fa-solid fa-clock-rotate-left mr-1"></i> Baru ditambahkan!</span>
                        </div>

                        <div class="flex justify-between items-center mt-4">
                            <p class="text-emerald-600 font-bold text-xl">
                                Rp{{ number_format($item->harga_sewa, 0, ',', '.') }}<span class="text-sm font-normal text-gray-500">/hari</span>
                            </p>

                            @if($item->status == 'tersedia')
                                <a href="{{ route('frontend.produk.detail', $item) }}" class="bg-emerald-600 text-white px-5 py-2 rounded-lg font-semibold hover:bg-emerald-700 transition-all text-sm shadow-md">
                                    Rent Now
                                </a>
                            @else
                                <button class="bg-gray-300 text-gray-600 px-5 py-2 rounded-lg font-semibold cursor-not-allowed text-sm" disabled>
                                    Waitlist
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <p class="col-span-full text-center text-gray-500 italic">Belum ada barang baru.</p>
            @endforelse
        </div>
    </div>
</section>


<!-- Paket Hemat Section -->

<section id="paket-hemat-promo" class="py-12 px-2 md:px-4">
<div class="max-w-6xl mx-auto bg-white rounded-2xl p-6 md:p-8 shadow-xl border-2 border-emerald-100">

<div class="text-center mb-10">
    <span class="inline-block bg-yellow-100 text-yellow-700 text-xs font-semibold px-3 py-1 rounded-full uppercase tracking-widest border border-yellow-300">
        PROMO KHUSUS
    </span>
    <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 mt-3 leading-snug">
        Pilih <span class="text-emerald-600">Paket Camping Hemat</span> Kami
    </h2>
    <p class="text-gray-500 mt-2 max-w-2xl mx-auto text-base">
        Mulai petualangan Anda dengan paket bundling yang paling sesuai dengan kebutuhan dan budget.
    </p>
</div>

<!-- Grid untuk menampilkan paket dari database (Maks 3) -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    {{-- ASUMSI: Variabel $pakets dikirim dari LandingController (sudah dibatasi 3) --}}
    @forelse ($pakets as $index => $paket)
        @php
            
            // Class Solid untuk semua card
            $cardClass = 'bg-emerald-50 border border-emerald-200 shadow-lg';
            $titleClass = 'text-gray-900 text-2xl'; 
            $priceClass = 'text-emerald-600 text-3xl'; 
            $subtextColor = 'text-gray-500';
            $textColor = 'text-gray-700';
            $iconColor = 'text-emerald-500'; 
            $buttonClass = 'bg-emerald-600 text-white hover:bg-emerald-700 font-semibold shadow-md';
            $priceTextSize = 'text-3xl';
            $borderItem = 'border-gray-200'; // Border item list

            $bookingLink = route('order.create', $paket->id); 
        @endphp

        <div class="package-card rounded-xl relative flex flex-col justify-between {{ $cardClass }} p-6">
            
            {{-- PERUBAHAN: Badge Paling Laris Dihilangkan sepenuhnya --}}
            
            <div>
                <h3 class="font-bold {{ $titleClass }}">{{ strtoupper($paket->nama_paket) }}</h3>
                <p class="{{ $subtextColor }} mt-1 text-sm">{{ $paket->deskripsi ?? 'Paket rental perlengkapan camping.' }}</p>

                <div class="my-4">
                    <p class="{{ $priceTextSize }} font-extrabold {{ $priceClass }}">
                        Rp {{ number_format($paket->harga_paket, 0, ',', '.') }}
                    </p>
                    <p class="text-xs text-gray-600">Per hari</p>
                </div>

                <ul class="mt-3 space-y-1 {{ $textColor }} text-sm border-t pt-3 {{ $borderItem }}">
                    @forelse ($paket->items as $item)
                        <li class="flex items-center">
                            {{-- Icon Checkmark --}}
                            <svg class="w-4 h-4 {{ $iconColor }} mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            
                            {{-- Menggunakan item->barang->nama_barang --}}
                            {{ $item->pivot->qty }}x {{ $item->nama_barang }} 
                        </li>
                        {{-- Batasi tampilan item agar card tidak terlalu panjang --}}
                        @if ($loop->iteration >= 5)
                            <li class="flex items-center text-xs italic {{ $subtextColor }}">...dan item lainnya ({{ $paket->items->count() - 5 }} lagi).</li>
                            @break 
                        @endif
                    @empty
                        <li class="{{ $textColor }}">Belum ada item terdaftar.</li>
                    @endforelse
                </ul>
            </div>

            <a href="{{ $bookingLink }}" 
                class="mt-4 block text-center py-2.5 rounded-lg transition duration-300 text-sm {{ $buttonClass }}">
                Pilih Paket Ini
            </a>
        </div>
    @empty
        <!-- Tampilan jika tidak ada paket di database -->
        <div class="md:col-span-3 text-center py-10 text-gray-500 bg-gray-50 rounded-xl border border-dashed border-gray-300">
            <p class="text-lg font-semibold">Tidak Ada Paket Tersedia Saat Ini.</p>
            <p class="text-sm">Silakan cek kembali nanti atau hubungi kami untuk paket kustom.</p>
        </div>
    @endforelse
</div>

<!-- Main CTA Area for Full List - LINK DIPERBAIKI KE frontend.paket.index -->
<div class="mt-8 text-center pt-6 border-t border-emerald-100">
    <a href="{{ route('frontend.produk.index', ['category' => 'paket']) }}"
    class="inline-flex items-center px-6 py-3 bg-emerald-600 text-white text-base font-bold rounded-xl shadow-md shadow-emerald-300 hover:bg-emerald-700 transition duration-300">
        Lihat Semua Pilihan Paket
        <svg class="w-4 h-4 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
    </a>
    <p class="text-xs text-gray-500 mt-3">Atau langsung chat admin untuk detail item.</p>
</div>


</div>
</section>

<!-- Review Section (LAMA - Tapi dipertahankan) -->
<section id="review" class="mt-16 py-16 bg-white px-6 scroll-mt-20">
    <div class="max-w-7xl mx-auto">
        <h3 class="text-3xl font-bold text-gray-900 mb-10 text-center">What They Say About Us</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Review Card 1 (Statis) -->
            <div class="bg-gray-50 rounded-xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition">
                <div class="flex items-center mb-4">
                    <img src="https://placehold.co/50x50/10B981/ffffff?text=B" alt="Avatar" class="w-12 h-12 rounded-full mr-4 ring-2 ring-emerald-500">
                    <div>
                        <h4 class="font-semibold text-gray-800">Budi Santoso</h4>
                        <p class="text-sm text-gray-500">Pendaki Merbabu</p>
                    </div>
                </div>
                <p class="text-gray-600 italic">"Alatnya bersih dan terawat banget. Pelayanannya ramah, proses sewanya cepat. Recommended!"</p>
            </div>
            <!-- Review Card 2 (Statis) -->
            <div class="bg-gray-50 rounded-xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition">
                <div class="flex items-center mb-4">
                    <img src="https://placehold.co/50x50/34D399/ffffff?text=S" alt="Avatar" class="w-12 h-12 rounded-full mr-4 ring-2 ring-emerald-500">
                    <div>
                        <h4 class="font-semibold text-gray-800">Fadhillya Isna</h4>
                        <p class="text-sm text-gray-500">Mahasiswi Pecinta Alam</p>
                    </div>
                </div>
                <p class="text-gray-600 italic">"Harga sewanya pas di kantong mahasiswa. Pilihan barangnya lengkap, nggak perlu bingung cari di tempat lain."</p>
            </div>
            <!-- Review Card 3 (Statis) -->
            <div class="bg-gray-50 rounded-xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition">
                <div class="flex items-center mb-4">
                    <img src="https://placehold.co/50x50/047857/ffffff?text=R" alt="Avatar" class="w-12 h-12 rounded-full mr-4 ring-2 ring-emerald-500">
                    <div>
                        <h4 class="font-semibold text-gray-800">Rian Dwi</h4>
                        <p class="text-sm text-gray-500">Camping Keluarga</p>
                    </div>
                </div>
                <p class="text-gray-600 italic">"Bawa keluarga camping jadi tenang. Tenda dan alat masaknya semua dalam kondisi prima. Mantap Jengki Adventure!"</p>
            </div>
        </div>
    </div>
</section>


<!-- Gallery Section -->

<section id="gallery" class="mt-15 px-6 scroll-mt-20 bg-gray-50 py-16">
<div class="max-w-7xl mx-auto">
<span class="text-sm font-semibold uppercase tracking-widest text-emerald-600 block text-center">Petualangan Mereka</span>
<h3 class="text-4xl font-extrabold text-gray-900 mb-10 text-center flex justify-center items-center">
<i class="fa-solid fa-mountain-sun text-emerald-600 text-3xl mr-3"></i> Momen Terbaik Bersama Kami
</h3>

    <!-- Scrollable Container -->
    <div class="flex gap-6 overflow-x-auto pb-6 snap-x snap-mandatory scrollbar-hide scroll-smooth">
        @foreach (['/galeri1.png','/galeri2.png','/galeri3.png','/galeri4.png','/galeri1.png'] as $img)
            <div class="min-w-[320px] snap-center bg-white rounded-2xl shadow-xl overflow-hidden 
                        hover:shadow-emerald-300/50 transition-all duration-300 border-4 border-white 
                        group relative">
                <img src="{{ $img }}" alt="Gallery image"
                        class="w-full h-72 object-cover group-hover:scale-105 transition-transform duration-500">
                
                {{-- Overlay sederhana saat hover --}}
                <div class="absolute inset-0 bg-gray-900/10 opacity-0 group-hover:opacity-100 transition duration-500 flex items-center justify-center">
                </div>
            </div>
        @endforeach
    </div>
    
    <div class="text-center mt-8">
         <a href="https://www.instagram.com/jengki.adventure/" class="text-emerald-600 font-semibold hover:underline text-lg">
            Lihat Semua Galeri di Instagram &rarr;
        </a>
    </div>
</div>


</section>


<style>
.scrollbar-hide::-webkit-scrollbar { display: none; }
.scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<section id="rules" class="mt-16 py-16 px-6 scroll-mt-20 bg-emerald-50">
<div class="max-w-3xl mx-auto">

    <div class="text-center mb-10">
        <span class="text-sm font-semibold uppercase tracking-widest text-emerald-600">KETENTUAN MUDAH</span>
        <h3 class="text-4xl font-extrabold text-gray-900 mt-2">7 Aturan Utama Penyewaan</h3>
        <p class="text-gray-600 mt-2">Ikuti panduan singkat ini untuk proses sewa yang cepat dan tanpa masalah.</p>
    </div>

    {{-- Container utama rules: Simple Vertical List --}}
    <div class="space-y-4">
        
        {{-- ITEM 1: Jaminan ID --}}
        <div class="p-5 bg-white rounded-xl shadow-lg border border-emerald-200 flex items-start group">
            <span class="text-2xl font-extrabold text-emerald-600 mr-4 flex-shrink-0 group-hover:scale-110 transition duration-200">1.</span>
            <div>
                <p class="font-bold text-gray-900">Jaminan Identitas Wajib</p>
                <p class="text-sm text-gray-600">Penyewa wajib meninggalkan KTP/KTM/SIM/Kartu Pelajar yang masih berlaku sebagai jaminan.</p>
            </div>
        </div>

        {{-- ITEM 2: Foto Pengambilan --}}
        <div class="p-5 bg-white rounded-xl shadow-lg border border-emerald-200 flex items-start group">
            <span class="text-2xl font-extrabold text-emerald-600 mr-4 flex-shrink-0 group-hover:scale-110 transition duration-200">2.</span>
            <div>
                <p class="font-bold text-gray-900">Dokumentasi Pengambilan</p>
                <p class="text-sm text-gray-600">Penyewa berkenan diambil foto saat proses pengambilan barang.</p>
            </div>
        </div>

        {{-- ITEM 3: Kerusakan/Kehilangan (KRITIS) --}}
        <div class="p-5 bg-red-50 rounded-xl shadow-xl border-2 border-red-400 flex items-start group">
            <span class="text-2xl font-extrabold text-red-600 mr-4 flex-shrink-0 group-hover:scale-110 transition duration-200">3.</span>
            <div>
                <p class="font-extrabold text-red-700">TANGGUNG JAWAB PENUH (Wajib Baca)</p>
                <p class="text-sm text-red-600 font-semibold">Penyewa wajib mengganti atau membayar penuh biaya kerusakan/kehilangan barang. Kerusakan dianggap berat jika fungsi alat hilang/rusak.</p>
            </div>
        </div>
        
        {{-- ITEM 4: Skema Pembayaran --}}
        <div class="p-5 bg-white rounded-xl shadow-lg border border-emerald-200 flex items-start group">
            <span class="text-2xl font-extrabold text-emerald-600 mr-4 flex-shrink-0 group-hover:scale-110 transition duration-200">4.</span>
            <div>
                <p class="font-bold text-gray-900">Skema Pembayaran</p>
                <p class="text-sm text-gray-600">Wajib DP saat booking. Pelunasan dapat dilakukan saat pengambilan/pengembalian barang.</p>
            </div>
        </div>

        {{-- ITEM 5: Ketentuan Pembatalan --}}
        <div class="p-5 bg-white rounded-xl shadow-lg border border-emerald-200 flex items-start group">
            <span class="text-2xl font-extrabold text-emerald-600 mr-4 flex-shrink-0 group-hover:scale-110 transition duration-200">5.</span>
            <div>
                <p class="font-bold text-gray-900">Ketentuan Pembatalan</p>
                <p class="text-sm text-gray-600">Jika sudah DP, dana yang masuk dapat digunakan untuk sewa kembali maksimal 1 bulan (non-refundable).</p>
            </div>
        </div>

        {{-- ITEM 6: Denda Keterlambatan --}}
        <div class="p-5 bg-white rounded-xl shadow-lg border border-emerald-200 flex items-start group">
            <span class="text-2xl font-extrabold text-emerald-600 mr-4 flex-shrink-0 group-hover:scale-110 transition duration-200">6.</span>
            <div>
                <p class="font-bold text-gray-900">Denda Keterlambatan</p>
                <p class="text-sm text-gray-600">Keterlambatan pengembalian akan dikenakan denda sesuai dengan durasi waktu yang telah disepakati.</p>
            </div>
        </div>

        {{-- ITEM 7: Persetujuan Akhir --}}
        <div class="p-5 bg-white rounded-xl shadow-lg border border-emerald-200 flex items-start group">
            <span class="text-2xl font-extrabold text-emerald-600 mr-4 flex-shrink-0 group-hover:scale-110 transition duration-200">7.</span>
            <div>
                <p class="font-bold text-gray-900">Persetujuan Ketentuan</p>
                <p class="text-sm text-gray-600">Menyewa berarti penyewa telah membaca, memahami, dan menyetujui semua persyaratan dan ketentuan yang berlaku.</p>
            </div>
        </div>
        
    </div>
</div>


</section>

<!-- SECTION LOKASI RENTAL (BARU) -->

<section id="location" class="mt-16 py-16 px-6 scroll-mt-20">
<div class="max-w-7xl mx-auto">

    <div class="text-center mb-10">
        <span class="text-sm font-semibold uppercase tracking-widest text-gray-600">DATANG DAN KUNJUNGI KAMI</span>
        <h3 class="text-4xl font-extrabold text-gray-900 mt-2">Lokasi Toko Rental Kami</h3>
        <p class="text-gray-500 mt-2">Kami siap melayani kebutuhan petualangan Anda di lokasi fisik kami.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 bg-white rounded-2xl shadow-2xl overflow-hidden border border-gray-100">

        <!-- KIRI: Detail Kontak dan Alamat -->
        <div class="p-8 space-y-6">
            
            <div class="flex items-start space-x-4">
                <i class="fa-solid fa-map-marker-alt text-3xl text-emerald-600 flex-shrink-0 mt-1"></i>
                <div>
                    <h4 class="text-xl font-bold text-gray-900">Alamat Lengkap</h4>
                    {{-- ALAMAT DIPERBARUI --}}
                    <p class="text-gray-600">Jl. Villa Siberi No.32, Siberi, Banjarejo, Kec. Boja, Kabupaten Kendal, Jawa Tengah 51381 (Vjengki Adventure Pos 2)</p>
                    {{-- LINK MAPS DIPERBARUI --}}
                    <a href="https://maps.app.goo.gl/vNXo1hFRcNg4REwD8" target="_blank" class="text-sm font-semibold text-emerald-500 hover:text-emerald-700 transition mt-1 inline-block">
                        Lihat di Google Maps &rarr;
                    </a>
                </div>
            </div>

            <div class="flex items-start space-x-4">
                <i class="fa-solid fa-clock text-3xl text-emerald-600 flex-shrink-0 mt-1"></i>
                <div>
                    <h4 class="text-xl font-bold text-gray-900">Jam Operasional</h4>
                    <p class="text-gray-600">Senin - Minggu: 08.00 - 22.00 WIB</p>
                    <p class="text-xs text-gray-500 italic">Tutup saat Hari Raya Besar Nasional</p>
                </div>
            </div>

            <div class="flex items-start space-x-4">
                <i class="fa-solid fa-phone text-3xl text-emerald-600 flex-shrink-0 mt-1"></i>
                <div>
                    <h4 class="text-xl font-bold text-gray-900">Hubungi Kami</h4>
                    <p class="text-gray-600">Rental Admin: (022) 123-4567</p>
                    <p class="text-gray-600">Customer Service: +62 895 3266 22614</p>
                </div>
            </div>

        </div>

        <!-- KANAN: Peta (Interaktif) -->
        <div class="min-h-[300px] lg:min-h-full">
            <iframe 
                {{-- SRC MAPS DIPERBARUI DENGAN ALAMAT LENGKAP --}}
                src="https://maps.google.com/maps?q=Jl.+Villa+Siberi+No.32,+Siberi,+Banjarejo,+Kec.+Boja,+Kabupaten+Kendal,+Jawa+Tengah+51381&t=&z=14&ie=UTF8&iwloc=&output=embed" 
                frameborder="0" 
                scrolling="no" 
                marginheight="0" 
                marginwidth="0"
                class="w-full h-full border-0 rounded-xl">
            </iframe>
        </div>

    </div>
</div>


</section>

</section>


@endsection