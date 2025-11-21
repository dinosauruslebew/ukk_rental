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

    <div class="relative overflow-hidden rounded-3xl shadow-lg w-full h-[450px]">
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

    <div class="absolute inset-0 flex flex-col items-start justify-center px-10 md:px-20 text-white pointer-events-none">
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

<!-- Paket Hemat Section -->
<section id="paket-hemat" class="mt-16 px-6">
    <div class="max-w-7xl mx-auto bg-white rounded-2xl p-8 md:p-12 shadow-lg border border-gray-100">

        <div class="text-center mb-10">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900">
                Coba <span class="text-emerald-600">Paket Lebih Hemat</span>
            </h2>
            <p class="text-gray-600 mt-3 max-w-2xl mx-auto">
                Pilihan paket lengkap untuk kebutuhan camping kamu.  
                Lebih murah daripada sewa satuan!
            </p>
        </div>

        <!-- Grid 3 macam paket -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

            <!-- Paket 1 -->
            <div class="bg-emerald-50 border border-emerald-100 p-6 rounded-xl shadow hover:shadow-lg transition">
                <h3 class="text-xl font-semibold text-emerald-700">Paket Solo</h3>
                <p class="text-gray-600 mt-2 text-sm">Cocok buat solo hiking atau short trip.</p>

                <ul class="mt-4 space-y-2 text-gray-700 text-sm">
                    <li>• 1 Tenda Single</li>
                    <li>• 1 Sleeping Bag</li>
                    <li>• 1 Kompor Portable</li>
                </ul>

                <a href="" 
                   class="mt-6 block bg-emerald-600 text-white text-center py-2 rounded-lg font-semibold hover:bg-emerald-700 transition">
                    Lihat Paket
                </a>
            </div>

            <!-- Paket 2 -->
            <div class="bg-emerald-50 border border-emerald-100 p-6 rounded-xl shadow hover:shadow-lg transition">
                <h3 class="text-xl font-semibold text-emerald-700">Paket Duo</h3>
                <p class="text-gray-600 mt-2 text-sm">Lebih hemat buat camping berdua.</p>

                <ul class="mt-4 space-y-2 text-gray-700 text-sm">
                    <li>• 1 Tenda Double</li>
                    <li>• 2 Sleeping Bag</li>
                    <li>• 1 Nesting</li>
                </ul>

                <a href="" 
                   class="mt-6 block bg-emerald-600 text-white text-center py-2 rounded-lg font-semibold hover:bg-emerald-700 transition">
                    Lihat Paket
                </a>
            </div>

            <!-- Paket 3 -->
            <div class="bg-emerald-50 border border-emerald-100 p-6 rounded-xl shadow hover:shadow-lg transition">
                <h3 class="text-xl font-semibold text-emerald-700">Paket Keluarga</h3>
                <p class="text-gray-600 mt-2 text-sm">Buat family trip atau outing bareng teman.</p>

                <ul class="mt-4 space-y-2 text-gray-700 text-sm">
                    <li>• 1 Tenda Family</li>
                    <li>• 4 Sleeping Bag</li>
                    <li>• Kompor + Peralatan Masak</li>
                </ul>

                <a href="" 
                   class="mt-6 block bg-emerald-600 text-white text-center py-2 rounded-lg font-semibold hover:bg-emerald-700 transition">
                    Lihat Paket
                </a>
            </div>

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

<!-- Gallery Section -->
<section id="gallery" class="mt-15 px-6 scroll-mt-20">
  <div class="max-w-7xl mx-auto">
    <h3 class="text-4xl font-bold text-gray-900 mb-8 text-center">
        <img src="/gunung.png" class="w-20"/>Gallery</h3>

    <!-- Scrollable Container -->
    <div class="flex gap-6 overflow-x-auto pb-6 snap-x snap-mandatory scrollbar-hide scroll-smooth">
      @foreach (['/galeri1.png','/galeri2.png','/galeri3.png','/galeri4.png','/galeri1.png'] as $img)
        <div class="min-w-[280px] snap-center bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300">
          <img src="{{ $img }}" alt="Gallery image"
               class="w-full h-60 object-cover hover:scale-105 transition-transform duration-500">
        </div>
      @endforeach
    </div>
  </div>
</section>

<style>
  .scrollbar-hide::-webkit-scrollbar { display: none; }
  .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>


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
            </div>

            <!-- Kanan: Gambar History (Lekuk Custom) -->
            <div class="overflow-hidden shadow-xl rounded-2xl rounded-tr-[6rem] rounded-bl-[6rem] mt-10">
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
