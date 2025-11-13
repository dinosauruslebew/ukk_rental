@extends('layouts.app')

@section('content')

    <!-- hero section (Home) -->
    <section id="home" class="relative w-full flex justify-center items-center pt-24 px-6">
        <div class="relative w-full max-w-7xl mx-auto">
            <img src="./utama.jpg" alt="Adventure Tent"
                 class="rounded-3xl shadow-lg w-full h-[450px] object-cover brightness-75">

            <div class="absolute inset-0 flex flex-col items-start justify-center px-10 md:px-20 text-white">
                <p class="text-sm font-semibold uppercase tracking-widest drop-shadow-md">OUR SHOP</p>
                <h2 class="text-4xl md:text-5xl font-bold mb-3 drop-shadow-lg mt-2 max-w-lg">
                    Explore Our Top-Quality Outdoor Gear Rentals
                </h2>
                <p class="text-lg md:text-xl mb-6 drop-shadow-md max-w-md">
                    Find the perfect gear for your next adventures!
                </p>
            </div>
        </div>
    </section>

    <!-- Kategori Section (BARU - Sesuai Referensi image_bada25.jpg) -->
    <section id="categories" class="mt-16 px-6 text-center">
        <div class="max-w-7xl mx-auto">
            <h3 class="text-3xl font-bold text-gray-900 mb-8">Browse All You Need</h3>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-6">
                <!-- Kategori Item 1 -->
                <a href="#" class="flex flex-col items-center p-6 bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow border border-gray-100">
                    <i class="fa-solid fa-hiking text-emerald-600 text-4xl mb-3"></i>
                    <span class="font-semibold text-gray-800">Hiking Equipment</span>
                </a>
                <!-- Kategori Item 2 -->
                <a href="#" class="flex flex-col items-center p-6 bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow border border-gray-100">
                    <i class="fa-solid fa-campground text-emerald-600 text-4xl mb-3"></i>
                    <span class="font-semibold text-gray-800">Camping Gear</span>
                </a>
                <!-- Kategori Item 3 -->
                <a href="#" class="flex flex-col items-center p-6 bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow border border-gray-100">
                    <i class="fa-solid fa-camera text-emerald-600 text-4xl mb-3"></i>
                    <span class="font-semibold text-gray-800">Photography Gear</span>
                </a>
                <!-- Kategori Item 4 -->
                <a href="#" class="flex flex-col items-center p-6 bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow border border-gray-100">
                    <i class="fa-solid fa-mountain text-emerald-600 text-4xl mb-3"></i>
                    <span class="font-semibold text-gray-800">Climbing Gear</span>
                </a>
                <!-- Kategori Item 5 -->
                <a href="#" class="flex flex-col items-center p-6 bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow border border-gray-100">
                    <i class="fa-solid fa-vest text-emerald-600 text-4xl mb-3"></i>
                    <span class="font-semibold text-gray-800">Apparel & Accesories</span>
                </a>
            </div>
        </div>
    </section>

        <!-- New Arrival Section (BARU - Sesuai Referensi image_bada25.jpg) -->
    <section id="new-arrivals" class="mt-16 mb-20 px-6">
        <div class="max-w-7xl mx-auto">
            <div class="flex justify-between items-center mb-8">
                <h3 class="text-3xl font-bold text-gray-900">New Arrival</h3>
                <a href="#products" class="text-emerald-600 font-semibold hover:underline">See all products &rarr;</a>
            </div>

            <!-- Grid (Ambil 3 barang terbaru dari $barang) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @forelse($barang->sortByDesc('created_at')->take(3) as $item)
                     <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow border border-gray-100">
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
                                    <a href="{{ route('frontend.produk.detail', $item) }}" class="bg-emerald-600 text-white px-5 py-2 rounded-lg font-semibold hover:bg-emerald-700 transition-all text-sm">
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

    <!-- Promo Section (BARU - Sesuai Referensi image_bada25.jpg) -->
    <section id="promo" class="mt-16 px-6">
        <div class="max-w-7xl mx-auto bg-emerald-50 rounded-2xl p-8 md:p-12 flex flex-col md:flex-row items-center justify-between overflow-hidden shadow-lg border border-emerald-100">
            <div class="md:w-1/2 text-center md:text-left mb-6 md:mb-0">
                <span class="text-emerald-600 font-semibold text-sm uppercase">Weekend Special</span>
                <h3 class="text-3xl lg:text-4xl font-bold text-gray-900 mt-2">Rent 2 or more items<br>and get <span class="text-emerald-600">20% OFF!</span></h3>
                <p class="text-gray-600 mt-3 mb-6">Gunakan kode promo 'WEEKEND20' saat checkout.</p>
                <a href="#products" class="bg-emerald-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-emerald-700 transition shadow-lg hover:shadow-emerald-300/50">
                    Shop Now
                </a>
            </div>
            <div class="md:w-1/2 flex justify-center md:justify-end">
                <!-- Ganti gambar ini dengan gambar promo kamu -->
                <img src="/kompor.jpeg" alt="Promo Tenda dan Ransel" class="max-h-60 rounded-lg shadow-lg">
            </div>
        </div>
    </section>

    <!-- Review Section (LAMA - Tapi dipertahankan) -->
    <section id="review" class="mt-16 py-16 bg-white px-6 scroll-mt-20">
        <div class="max-w-7xl mx-auto">
            <h3 class="text-3xl font-bold text-gray-900 mb-10 text-center">What They Say About Us</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Review Card 1 (Statis) -->
                <div class="bg-gray-50 rounded-xl shadow-md p-6 border border-gray-100">
                    <div class="flex items-center mb-4">
                        <img src="https://via.placeholder.com/50" alt="Avatar" class="w-12 h-12 rounded-full mr-4">
                        <div>
                            <h4 class="font-semibold text-gray-800">Budi Santoso</h4>
                            <p class="text-sm text-gray-500">Pendaki Merbabu</p>
                        </div>
                    </div>
                    <p class="text-gray-600 italic">"Alatnya bersih dan terawat banget. Pelayanannya ramah, proses sewanya cepat. Recommended!"</p>
                </div>
                <!-- Review Card 2 (Statis) -->
                <div class="bg-gray-50 rounded-xl shadow-md p-6 border border-gray-100">
                    <div class="flex items-center mb-4">
                        <img src="https://via.placeholder.com/50" alt="Avatar" class="w-12 h-12 rounded-full mr-4">
                        <div>
                            <h4 class="font-semibold text-gray-800">Siti Aminah</h4>
                            <p class="text-sm text-gray-500">Mahasiswi Pecinta Alam</p>
                        </div>
                    </div>
                    <p class="text-gray-600 italic">"Harga sewanya pas di kantong mahasiswa. Pilihan barangnya lengkap, nggak perlu bingung cari di tempat lain."</p>
                </div>
                <!-- Review Card 3 (Statis) -->
                <div class="bg-gray-50 rounded-xl shadow-md p-6 border border-gray-100">
                    <div class="flex items-center mb-4">
                        <img src="https://via.placeholder.com/50" alt="Avatar" class="w-12 h-12 rounded-full mr-4">
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

    <!-- Gallery Section (LAMA - Tapi dipertahankan) -->
    <section id="gallery" class="mt-16 px-6 scroll-mt-20">
        <div class="max-w-7xl mx-auto">
            <h3 class="text-3xl font-bold text-gray-900 mb-8 text-center">Our Gallery</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <img src="/kompor.jpeg" alt="Gallery 1" class="rounded-lg shadow-md aspect-square object-cover hover:scale-105 transition-transform duration-300">
                <img src="/tenda.jpeg" alt="Gallery 2" class="rounded-lg shadow-md aspect-square object-cover hover:scale-105 transition-transform duration-300">
                <img src="/lampu_tenda.jpeg" alt="Gallery 3" class="rounded-lg shadow-md aspect-square object-cover hover:scale-105 transition-transform duration-300">
                <img src="/utama.jpg" alt="Gallery 4" class="rounded-lg shadow-md aspect-square object-cover hover:scale-105 transition-transform duration-300">
            </div>
        </div>
    </section>

    <!-- SECTION HISTORY (LAMA - Tapi dipertahankan, style lekuk) -->
    <section id="history" class="mt-16 px-6 scroll-mt-20">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
            <!-- Kiri: Teks History -->
            <div>
                <span class="text-sm font-semibold uppercase tracking-widest text-emerald-600">Our Story</span>
                <h3 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6 mt-2">Our History</h3>
                <div class="space-y-4 text-gray-700 leading-relaxed">
                    <p>
                        Berawal dari kecintaan kami terhadap petualangan di alam bebas, Jengki Adventure didirikan pada tahun 2020. Kami mengerti betapa pentingnya memiliki perlengkapan yang andal tanpa harus mengeluarkan biaya besar untuk membelinya.
                    </p>
                    <p>
                        Dari sebuah garasi kecil dengan beberapa tenda dan ransel, kami tumbuh bersama komunitas pendaki. Misi kami sederhana: menyediakan akses mudah ke perlengkapan outdoor berkualitas tinggi bagi semua orang, dari pemula hingga petualang berpengalaman.
                    </p>
                </div>
                <a href="#" class="inline-block mt-8 bg-emerald-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-emerald-700 transition shadow-lg hover:shadow-emerald-300/50">
                    Learn More About Us
                </a>
            </div>

            <!-- Kanan: Gambar History (Lekuk Custom) -->
            <div class="overflow-hidden shadow-xl rounded-2xl rounded-tr-[6rem] rounded-bl-[6rem]">
                <img src="/utama.jpg" alt="Sejarah Jengki Adventure" class="w-full h-80 object-cover transition-transform duration-300 hover:scale-105">
            </div>
        </div>
    </section>

    <!-- SECTION RULES (LAMA - Tapi dipertahankan, style lekuk) -->
    <section id="rules" class="mt-16 bg-gray-50 py-16 px-6 scroll-mt-20">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
            <!-- Kiri: Gambar Rules (Lekuk Custom Cermin) -->
            <div class="overflow-hidden shadow-xl rounded-tl-2xl rounded-br-2xl rounded-tr-[5rem] rounded-bl-[5rem]">
                 <img src="/tenda.jpeg" alt="Peraturan Sewa Jengki Adventure" class="w-full h-80 object-cover transition-transform duration-300 hover:scale-105">
            </div>

            <!-- Kanan: Teks Rules -->
            <div>
                <span class="text-sm font-semibold uppercase tracking-widest text-emerald-600">How To Rent</span>
                <h3 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6 mt-2">Rental Rules</h3>
                <ol class="list-decimal list-inside space-y-3 text-gray-700">
                    <li>Penyewa wajib menunjukkan kartu identitas asli (KTP/SIM) yang masih berlaku.</li>
                    <li>Pembayaran (DP atau lunas) dilakukan saat booking untuk mengamankan barang.</li>
                    <li>Barang yang disewa wajib dijaga dengan baik dan dikembalikan dalam kondisi bersih.</li>
                    <li>Keterlambatan pengembalian akan dikenakan denda sesuai tarif harian.</li>
                    <li>Kerusakan atau kehilangan barang akibat kelalaian penyewa menjadi tanggung jawab penuh penyewa.</li>
                    <li>Pembatalan sewa H-1 akan dikenakan potongan 50% dari total biaya sewa.</li>
                </ol>
            </div>
        </div>
    </section>

@endsection
