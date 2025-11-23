<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Jengki Adventure') }}</title>

    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
    crossorigin="anonymous"
    referrerpolicy="no-referrer" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* CSS Tambahan untuk Animasi Menu Mobile */
        .mobile-menu-active {
            /* Pastikan menu mobile ada di atas semua konten */
            position: fixed;
            top: 0;
            right: 0;
            height: 100vh;
            width: 75%; /* Lebar menu mobile, bisa disesuaikan */
            z-index: 60; /* Harus lebih tinggi dari navbar */
            box-shadow: -4px 0 10px rgba(0, 0, 0, 0.1);
            
            /* Class ini akan ditambahkan/dihapus oleh JS */
            transform: translateX(0);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    @php
    $photo = null;

    if(Auth::check()) {
        $photo = Auth::user()->profile_photo
            ? asset('storage/' . Auth::user()->profile_photo)
            : "https://ui-avatars.com/api/?name=" 
                . urlencode(Auth::user()->name) 
                . "&background=random&color=fff&size=128";
    }
    @endphp


    <nav class="fixed left-0 w-full bg-white shadow-md py-3 px-8 z-50">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <img src="/logo.png" alt="Logo" class="h-10 w-10 rounded-full border border-emerald-500">
                <h1 class="text-xl font-semibold text-emerald-700">Jengki Adventure</h1>
            </div>

            <ul class="hidden md:flex space-x-10 text-gray-700 font-medium">
                <li><a href="/#home" class="hover:text-emerald-600 transition">Home</a></li>
                <li><a href="/#products" class="hover:text-emerald-600 transition">Products</a></li>
                <li><a href="/#review" class="hover:text-emerald-600 transition">Review</a></li>
                <li><a href="/#gallery" class="hover:text-emerald-600 transition">Gallery</a></li>
                <li><a href="/#rules" class="hover:text-emerald-600 transition">Rules</a></li>
            </ul>

            <div class="flex items-center space-x-4">
                <a href="{{ route('cart.index') }}" class="relative text-gray-600 hover:text-emerald-700 transition text-2xl">
                    <i class="fa-solid fa-cart-shopping"></i>
                    @if(session('cart') && count(session('cart')) > 0)
                        <span class="absolute -top-2 -right-2 w-5 h-5 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center">
                            {{ count(session('cart')) }}
                        </span>
                    @endif
                </a>
                <div class="relative hidden md:block">
                    @auth
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-white bg-emerald-600 px-4 py-2 rounded-lg hover:bg-emerald-700 transition">
                                Dashboard
                            </a>
                        @else
                        <button id="userMenuButton"
                            class="w-10 h-10 rounded-full border-2 border-gray-300 overflow-hidden hover:border-emerald-600 transition">

                            <img src="{{ $photo }}" class="w-full h-full object-cover">
                        </button>



    <div id="userDropdown"
    class="hidden absolute right-0 mt-2 w-64 bg-white rounded-2xl shadow-xl border border-gray-100 py-4 z-50">

    <!-- Header user -->
    <div class="flex items-center gap-3 px-4 pb-3 border-b">
        <div class="w-12 h-12 rounded-full overflow-hidden border border-gray-200">
            <img src="{{ $photo }}" class="w-full h-full object-cover">
        </div>

        <div>
            <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</p>
            <p class="text-xs text-gray-400">{{ Auth::user()->email }}</p>
        </div>
    </div>


            <!-- Menu list -->
            <a href="{{ route('profile.edit') }}"
                class="flex items-center gap-3 px-4 py-3 hover:bg-emerald-50 text-gray-700 transition">
                <i class="fa-solid fa-user-pen w-5"></i>
                Edit Profil
            </a>

            <a href="{{ route('frontend.order.index') }}"
                class="flex items-center gap-3 px-4 py-3 hover:bg-emerald-50 text-gray-700 transition">
                <i class="fa-solid fa-bag-shopping w-5"></i>
                Pesanan Saya
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="flex items-center gap-3 px-4 py-3 text-red-600 hover:bg-red-50 w-full text-left transition">
                    <i class="fa-solid fa-right-from-bracket w-5"></i>
                    Logout
                </button>
            </form>
        </div>

                        @endif
                    @else
                        <button id="guestMenuButton" class="text-gray-700 hover:text-emerald-700 text-2xl focus:outline-none" aria-label="Guest Menu">
                            <i class="fa-regular fa-user"></i>
                        </button>

                        <div id="guestDropdown" class="hidden absolute right-0 mt-2 w-40 bg-white shadow-lg rounded-lg py-2 border border-gray-100 z-50">
                            <a href="{{ route('login') }}" class="block px-4 py-2 text-gray-700 hover:bg-emerald-50">Login</a>
                            <a href="{{ route('register') }}" class="block px-4 py-2 text-gray-700 hover:bg-emerald-50">Sign Up</a>
                        </div>
                    @endauth
                </div>

                <button id="mobileMenuButton" class="md:hidden text-gray-700 hover:text-emerald-700 text-2xl focus:outline-none" aria-label="Buka Menu">
                    <i id="burgerIcon" class="fa-solid fa-bars"></i>
                    <i id="closeIcon" class="fa-solid fa-xmark hidden"></i>
                </button>
            </div>
        </div>
        
        <div id="mobileMenu" 
             class="md:hidden fixed top-0 right-0 h-full w-3/4 bg-white shadow-xl 
                    transform translate-x-full transition-transform duration-300 ease-in-out 
                    rounded-tl-xl rounded-bl-xl z-50 overflow-y-auto pt-20">
            
            <ul class="flex flex-col items-start space-y-2 p-6">
                <li><a href="/#home" class="block py-2 text-gray-700 hover:text-emerald-600 font-semibold transition">Home</a></li>
                <li><a href="/#products" class="block py-2 text-gray-700 hover:text-emerald-600 font-semibold transition">Products</a></li>
                <li><a href="/#review" class="block py-2 text-gray-700 hover:text-emerald-600 font-semibold transition">Review</a></li>
                <li><a href="/#gallery" class="block py-2 text-gray-700 hover:text-emerald-600 font-semibold transition">Gallery</a></li>
                <li><a href="/#rules" class="block py-2 text-gray-700 hover:text-emerald-600 font-semibold transition">Rules</a></li>
                <li class="w-full border-t border-gray-200 my-4"></li>

                @auth
                    @if(Auth::user()->role === 'admin')
                        <li><a href="{{ route('admin.dashboard') }}" class="block py-2 text-emerald-600 font-bold transition">Dashboard Admin</a></li>
                    @else
                        <li><a href="{{ route('frontend.order.index') }}" class="block py-2 text-gray-700 hover:text-emerald-600 font-semibold transition">Pesanan Saya</a></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left py-2 text-red-600 hover:text-red-700 font-semibold transition">
                                    Logout
                                </button>
                            </form>
                        </li>
                    @endif
                @else
                    <li><a href="{{ route('login') }}" class="block py-2 text-gray-700 hover:text-emerald-600 font-semibold transition">Login</a></li>
                    <li><a href="{{ route('register') }}" class="block py-2 text-gray-700 hover:text-emerald-600 font-semibold transition">Sign Up</a></li>
                @endauth
            </ul>
        </div>
        
    </nav>

    <main class="flex-grow pt-[64px]"> @yield('content')
    </main>

    {{-- FOOTER --}}
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
                    <li><a href="/#home" class="hover:text-emerald-400 transition">Home</a></li>
                    <li><a href="/#categories" class="hover:text-emerald-400 transition">Kategori</a></li>
                    <li><a href="/#products" class="hover:text-emerald-400 transition">Produk</a></li>
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
                    <a href="https://www.instagram.com/jengki.adventure/" aria-label="Instagram" class="hover:text-emerald-400 transition"><i class="fab fa-instagram"></i></a>
                    <a href="#" aria-label="WhatsApp" class="hover:text-emerald-400 transition"><i class="fab fa-whatsapp"></i></a>
                    <a href="#" aria-label="Facebook" class="hover:text-emerald-400 transition"><i class="fab fa-facebook"></i></a>
                </div>
            </div>
        </div>

        <div class="text-center text-gray-500 text-sm mt-10 border-t border-gray-700 pt-8">
            © {{ date('Y') }} {{ config('app.name', 'Jengki Adventure') }}. Dibuat dengan <i class="fa-solid fa-heart text-emerald-500"></i> untuk UKK.
        </div>
    </footer>


    <script>
        // USER DROPDOWN
        const userButton = document.getElementById("userMenuButton");
        const userDropdown = document.getElementById("userDropdown");

        if (userButton && userDropdown) {
            userButton.addEventListener("click", function (e) {
                e.stopPropagation();
                userDropdown.classList.toggle("hidden");
            });

            document.addEventListener("click", function (e) {
                if (!userDropdown.contains(e.target)) {
                    userDropdown.classList.add("hidden");
                }
            });
        }

        // GUEST DROPDOWN
        const guestButton = document.getElementById("guestMenuButton");
        const guestDropdown = document.getElementById("guestDropdown");

        if (guestButton && guestDropdown) {
            guestButton.addEventListener("click", function (e) {
                e.stopPropagation();
                guestDropdown.classList.toggle("hidden");
            });

            document.addEventListener("click", function (e) {
                if (!guestDropdown.contains(e.target)) {
                    guestDropdown.classList.add("hidden");
                }
            });
        }

        
        // klik di luar dropdown => tutup menu
        document.addEventListener('click', function(e) {
            [userDropdown, guestDropdown].forEach(dropdown => {
                const button = dropdown ? dropdown.previousElementSibling : null;
                if (dropdown && button && !dropdown.classList.contains('hidden') && !button.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.classList.add('hidden');
                }
            });
        });


        // === Menu Hamburger (Mobile) ===

        const mobileMenuButton = document.getElementById('mobileMenuButton');
        const mobileMenu = document.getElementById('mobileMenu');
        const burgerIcon = document.getElementById('burgerIcon');
        const closeIcon = document.getElementById('closeIcon');

        // Fungsi untuk membuka/menutup menu
        function toggleMobileMenu() {
            const isActive = mobileMenu.classList.contains('translate-x-0');

            if (isActive) {
                // Tutup menu
                mobileMenu.classList.remove('translate-x-0');
                mobileMenu.classList.add('translate-x-full');
                burgerIcon.classList.remove('hidden');
                closeIcon.classList.add('hidden');
                document.body.style.overflow = ''; // Aktifkan scroll body lagi
            } else {
                // Buka menu
                mobileMenu.classList.remove('translate-x-full');
                mobileMenu.classList.add('translate-x-0');
                burgerIcon.classList.add('hidden');
                closeIcon.classList.remove('hidden');
                document.body.style.overflow = 'hidden'; // Nonaktifkan scroll body
            }
        }

        mobileMenuButton.addEventListener('click', toggleMobileMenu);

        // Menutup menu jika salah satu link di dalamnya di klik (untuk navigasi)
        mobileMenu.querySelectorAll('a[href^="#"], button[type="submit"]').forEach(link => {
            link.addEventListener('click', () => {
                if (!mobileMenu.classList.contains('translate-x-full')) {
                    toggleMobileMenu();
                }
            });
        });

        // Menutup menu jika user klik di luar menu (tapi tidak di tombol hamburger)
        document.addEventListener('click', function(event) {
            if (window.innerWidth < 768) { // Hanya berlaku di tampilan mobile
                const isClickInsideMenu = mobileMenu.contains(event.target);
                const isClickOnButton = mobileMenuButton.contains(event.target);

                // Jika menu terbuka dan klik terjadi di luar menu dan di luar tombol
                if (!mobileMenu.classList.contains('translate-x-full') && !isClickInsideMenu && !isClickOnButton) {
                    toggleMobileMenu();
                }
            }
        });

        // Smooth Scroll ke anchor
        document.querySelectorAll('a[href^="/#"], a[href^="#"]').forEach(link => {
            link.addEventListener('click', function(e) {
                const targetID = this.getAttribute('href').replace('/', '');
                const target = document.querySelector(targetID);

                if (target) {
                    e.preventDefault();
                    window.scrollTo({
                        top: target.offsetTop - 70, // kasih jarak biar ga ketutup navbar
                        behavior: "smooth"
                    });

                    // update URL
                    history.pushState(null, null, this.getAttribute('href'));
                }
            });
        });

        
    </script>
</body>
</html>