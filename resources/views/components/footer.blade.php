<footer class="bg-gray-800 text-gray-300 py-12 px-6 mt-auto">
    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-8">

        <div>
            <h3 class="font-semibold text-white text-lg mb-3">Jengki Adventure</h3>
            <p class="text-sm leading-relaxed">
                Menyediakan perlengkapan hiking dan petualangan outdoor berkualitas
                untuk menemani setiap langkah perjalananmu.
            </p>
        </div>

        <div>
            <h4 class="font-semibold text-white text-lg mb-3">Tautan Cepat</h4>
            <ul class="space-y-2 text-sm">
                <li><a href="#home" class="hover:text-emerald-400 transition">Home</a></li>
                <li><a href="#categories" class="hover:text-emerald-400 transition">Kategori</a></li>
                <li><a href="#products" class="hover:text-emerald-400 transition">Produk</a></li>
                <li><a href="#promo" class="hover:text-emerald-400 transition">Promo</a></li>
            </ul>
        </div>

        <div>
            <h4 class="font-semibold text-white text-lg mb-3">Informasi</h4>
            <ul class="space-y-2 text-sm">
                <li><a href="#" class="hover:text-emerald-400 transition">Syarat & Ketentuan Sewa</a></li>
                <li><a href="#" class="hover:text-emerald-400 transition">Kebijakan Privasi</a></li>
                <li><a href="#" class="hover:text-emerald-400 transition">Hubungi Kami</a></li>
            </ul>
        </div>

        <div>
            <h4 class="font-semibold text-white text-lg mb-3">Ikuti Kami</h4>
            <div class="flex space-x-4 text-2xl">
                <a href="#" aria-label="Instagram" class="hover:text-emerald-400 transition"><i class="fab fa-instagram"></i></a>
                <a href="#" aria-label="WhatsApp" class="hover:text-emerald-400 transition"><i class="fab fa-whatsapp"></i></a>
                <a href="#" aria-label="Facebook" class="hover:text-emerald-400 transition"><i class="fab fa-facebook"></i></a>
            </div>
        </div>
    </div>

    <div class="text-center text-gray-500 text-sm mt-10 border-t border-gray-700 pt-8">
        © {{ date('Y') }} {{ config('app.name', 'Jengki Adventure') }}. Dibuat dengan <i class="fa-solid fa-heart text-emerald-500"></i> untuk UKK.
    </div>
</footer>
