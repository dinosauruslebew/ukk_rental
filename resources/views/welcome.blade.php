@extends ('layouts.footer')

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Jengki Adventure') }}</title>

        <!-- font awesome dulu -->
    <link rel="stylesheet" 
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" 
    integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" 
    crossorigin="anonymous" 
    referrerpolicy="no-referrer" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-b  min-h-screen flex flex-col">

    <!-- navbar -->
<nav class="fixed left-0 w-full bg-white shadow-md py-3 px-8 flex justify-between items-center z-50 rounded-full mx">
    <!-- logo -->
    <div class="flex items-center space-x-2">
        <img src="/logo.png" alt="Logo" class="h-10 w-10 rounded-full border border-emerald-500">
        <h1 class="text-xl font-semibold text-emerald-700">Jengki Adventure</h1>
    </div>

    <!-- menu -->
    <ul class="hidden md:flex space-x-10 text-gray-700 font-medium right-20 absolute pr-10">
        <li><a href="#home" class="hover:text-emerald-600 transition">Home</a></li>
        <li><a href="#products" class="hover:text-emerald-600 transition">Products</a></li>
        <li><a href="#review" class="hover:text-emerald-600 transition">Review</a></li>
        <li><a href="#gallery" class="hover:text-emerald-600 transition">Gallery</a></li>
        <li><a href="#history" class="hover:text-emerald-600 transition">History</a></li>
        <li><a href="#history" class="hover:text-emerald-600 transition">Rules</a></li>
    </ul>

   <!-- auth dropdown -->
<div class="relative">
    @auth
        @if(Auth::user()->role === 'admin')
            <!-- admin: langsung dashboard -->
            <a href="{{ route('admin.dashboard') }}"
               class="text-emerald-700 font-medium hover:text-emerald-800">
               Dashboard
            </a>
        @else
            <!-- user biasa: ikon profil -->
            <button id="userMenuButton" class="text-gray-700 hover:text-emerald-700 text-2xl focus:outline-none">
                <i class="fa-regular fa-user"></i>
            </button>

            <!-- dropdown menu user -->
            <div id="userDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white shadow-lg rounded-lg py-2 border border-gray-100">
                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-700 hover:bg-emerald-50">Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-emerald-50">
                        Logout
                    </button>
                </form>
            </div>
        @endif
    @else
        <!-- belum login -->
        <button id="guestMenuButton" class="text-gray-700 hover:text-emerald-700 text-2xl focus:outline-none">
            <i class="fa-regular fa-user"></i>
        </button>

        <!-- dropdown menu guest -->
        <div id="guestDropdown" class="hidden absolute right-0 mt-2 w-40 bg-white shadow-lg rounded-lg py-2 border border-gray-100">
            <a href="{{ route('login') }}" class="block px-4 py-2 text-gray-700 hover:bg-emerald-50">Login</a>
            <a href="{{ route('register') }}" class="block px-4 py-2 text-gray-700 hover:bg-emerald-50">Sign Up</a>
        </div>
    @endauth
</div>
</nav>

<!-- hero section -->
    <section id="home" class="relative w-full flex justify-center items-center pt-24 px-6">
        <!-- background image -->
        <div class="relative w-full max-w-6xl">
            <img src="./utama.jpg" alt="Adventure Tent"
                class="rounded-3xl shadow-lg w-full h-[400px] object-cover brightness-75">
            
            <!-- overlay text -->
            <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-6 text-white">
                <h2 class="text-4xl md:text-5xl font-bold mb-3 drop-shadow-lg">
                    Ready for Your Next Adventure?
                </h2>
                <p class="text-lg md:text-xl mb-6 drop-shadow-md">
                    Nikmati pengalaman camping terbaik dengan perlengkapan outdoor berkualitas dari Jengki Adventure.
                </p>
                {{-- <a href="/rental"
                    class="bg-emerald-600 text-white px-8 py-3 rounded-full font-semibold hover:bg-emerald-700 transition">
                    Explore Now
                </a> --}}
            </div>
        </div>
    </section>


<!-- rekomendasi section -->
<section id="products" class="mt-20 px-6 text-center">
    <h3 class="text-2xl md:text-3xl font-bold text-gray-900 mb-10">Recommendation</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-6xl mx-auto">
        <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition">
            <img src="/tenda.jpeg" alt="Tenda" class="rounded-xl mb-4">
            <h4 class="font-semibold text-lg text-gray-800">Tenda Camping</h4>
            <p class="text-gray-500 text-sm">Nyaman, kuat, dan cocok untuk segala cuaca.</p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition">
            <img src="/kompor.jpeg" alt="Kompor" class="rounded-xl mb-4">
            <h4 class="font-semibold text-lg text-gray-800">Kompor Portable</h4>
            <p class="text-gray-500 text-sm">Masak dengan praktis di alam terbuka.</p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition">
            <img src="/lampu_tenda.jpeg" alt="Lampu" class="rounded-xl mb-4">
            <h4 class="font-semibold text-lg text-gray-800">Lampu Tenda</h4>
            <p class="text-gray-500 text-sm">Terangi malam camping kamu dengan lampu LED hangat.</p>
        </div>
    </div>
</section>

<!-- rekomendasi section -->
<section id="products" class="mt-20 px-6 text-center">
    <h3 class="text-2xl md:text-3xl font-bold text-gray-900 mb-10">Barang woi</h3>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 max-w-6xl mx-auto">
        @forelse($barang as $item)
            <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition">
                <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->nama_barang }}" class="rounded-xl mb-4 w-full">
                <h4 class="font-semibold text-lg text-gray-800">{{ $item->nama_barang }}</h4>
                {{-- <p class="text-gray-500 text-sm mt-1 mb-3">{{ Str::limit($item->deskripsi, 60, '...') }}</p> --}}
                <p class="text-emerald-600 font-bold text-lg mb-4">
                    Rp{{ number_format($item->harga_sewa, 0, ',', '.') }}/hari
                </p>
                <button class="w-full bg-emerald-600 text-white py-2 rounded-lg hover:bg-emerald-700 transition-all duration-300">
                    Sewa Sekarang
                </button>
            </div>
        @empty
            <p class="col-span-full text-center text-gray-500 italic">Belum ada barang tersedia.</p>
        @endforelse
    </div>
</section>


<script>
    // dropdown untuk user yang sudah login
    const userButton = document.getElementById('userMenuButton');
    const userDropdown = document.getElementById('userDropdown');
    if (userButton && userDropdown) {
        userButton.addEventListener('click', () => {
            userDropdown.classList.toggle('hidden');
        });
    }

    // dropdown untuk guest (belum login)
    const guestButton = document.getElementById('guestMenuButton');
    const guestDropdown = document.getElementById('guestDropdown');
    if (guestButton && guestDropdown) {
        guestButton.addEventListener('click', () => {
            guestDropdown.classList.toggle('hidden');
        });
    }

    // klik di luar dropdown => tutup menu
    document.addEventListener('click', function(e) {
        [userDropdown, guestDropdown].forEach(dropdown => {
            if (dropdown && !dropdown.contains(e.target) && !dropdown.previousElementSibling.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });
    });
</script>


</body>
</html>



