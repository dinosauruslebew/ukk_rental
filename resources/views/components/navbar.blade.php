<nav class="fixed left-0 w-full bg-white shadow-md py-3 px-8 z-50">
    <div class="flex justify-between items-center">
        <div class="flex items-center space-x-2">
            <img src="/logo.png" alt="Logo" class="h-10 w-10 rounded-full border border-emerald-500">
            <h1 class="text-xl font-semibold text-emerald-700">Jengki Adventure</h1>
        </div>

        <ul class="hidden md:flex space-x-10 text-gray-700 font-medium">
            <li><a href="#home" class="hover:text-emerald-600 transition">Home</a></li>
            <li><a href="#products" class="hover:text-emerald-600 transition">Products</a></li>
            <li><a href="#review" class="hover:text-emerald-600 transition">Review</a></li>
            <li><a href="#gallery" class="hover:text-emerald-600 transition">Gallery</a></li>
            <li><a href="#history" class="hover:text-emerald-600 transition">History</a></li>
            <li><a href="#rules" class="hover:text-emerald-600 transition">Rules</a></li> </ul>

        <div class="flex items-center space-x-4">
            <a href="{{ route('cart.index') }}" class="relative text-gray-600 hover:text-emerald-700 transition text-2xl">
                <i class="fa-solid fa-cart-shopping"></i>
                <!-- Counter Keranjang -->
                @if(session('cart') && count(session('cart')) > 0)
                    <span class="absolute -top-2 -right-2 w-5 h-5 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center">
                        {{ count(session('cart')) }}
                    </span>
                @endif
            </a>
            <div class="relative hidden md:block">
                @auth
                    @if(Auth::user()->role === 'admin')
                        <!-- Admin: link ke Dashboard -->
                        <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-white bg-emerald-600 px-4 py-2 rounded-lg hover:bg-emerald-700 transition">
                            Dashboard
                        </a>
                    @else
                        <!-- User biasa: ikon profil -->
                        <button id="userMenuButton" class="text-gray-700 hover:text-emerald-700 text-2xl focus:outline-none" aria-label="User Menu">
                            <i class="fa-regular fa-user"></i>
                        </button>

                        <!-- dropdown menu user -->
                        <div id="userDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white shadow-lg rounded-lg py-2 border border-gray-100">
                            <div class="px-4 py-2 border-b">
                                <p class="text-xs text-gray-500">Halo,</p>
                                <p class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->name }}</p>
                            </div>
                            <a href="{{ route('frontend.order.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-emerald-50">Pesanan Saya</a>
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-700 hover:bg-emerald-50">Profile</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-red-600 hover:bg-red-50">
                                    Logout
                                </button>
                            </form>
                        </div>
                    @endif
                @else
                    <!-- belum login (Guest) -->
                    <button id="guestMenuButton" class="text-gray-700 hover:text-emerald-700 text-2xl focus:outline-none" aria-label="Guest Menu">
                        <i class="fa-regular fa-user"></i>
                    </button>

                    <!-- dropdown menu guest -->
                    <div id="guestDropdown" class="hidden absolute right-0 mt-2 w-40 bg-white shadow-lg rounded-lg py-2 border border-gray-100">
                        <a href="{{ route('login') }}" class="block px-4 py-2 text-gray-700 hover:bg-emerald-50">Login</a>
                        <a href="{{ route('register') }}" class="block px-4 py-2 text-gray-700 hover:bg-emerald-50">Sign Up</a>
                    </div>
                @endauth
            </div>

            <!-- Tombol Hamburger MOBILE -->
            <button id="mobileMenuButton" class="md:hidden text-gray-700 hover:text-emerald-700 text-2xl focus:outline-none" aria-label="Buka Menu">
                <i id="burgerIcon" class="fa-solid fa-bars"></i>
                <i id="closeIcon" class="fa-solid fa-xmark hidden"></i>
            </button>
        </div>
    </div>

    <div id="mobileMenu" class="hidden md:hidden w-full bg-white shadow-md py-4 mt-3 border-t border-gray-100">
        <ul class="flex flex-col items-center space-y-4">
            <li><a href="#home" class="block py-2 text-gray-700 hover:text-emerald-600 transition">Home</a></li>
            <li><a href="#products" class="block py-2 text-gray-700 hover:text-emerald-600 transition">Products</a></li>
            <li><a href="#review" class="block py-2 text-gray-700 hover:text-emerald-600 transition">Review</a></li>
            <li><a href="#gallery" class="block py-2 text-gray-700 hover:text-emerald-600 transition">Gallery</a></li>
            <li><a href="#history" class="block py-2 text-gray-700 hover:text-emerald-600 transition">History</a></li>
            <li><a href="#rules" class="block py-2 text-gray-700 hover:text-emerald-600 transition">Rules</a></li>

            <li class="w-1/2 border-t border-gray-200 my-2"></li>

            @auth
                @if(Auth::user()->role === 'admin')
                    <li><a href="{{ route('admin.dashboard') }}" class="block py-2 text-emerald-600 font-semibold transition">Dashboard</a></li>
                @else
                    <li><a href="{{ route('profile.edit') }}" class="block py-2 text-gray-700 hover:text-emerald-600 transition">Profile</a></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-center py-2 text-red-500 hover:text-red-700 transition">
                                Logout
                            </button>
                        </form>
                    </li>
                @endif
            @else
                <li><a href="{{ route('login') }}" class="block py-2 text-gray-700 hover:text-emerald-600 transition">Login</a></li>
                <li><a href="{{ route('register') }}" class="block py-2 text-gray-700 hover:text-emerald-600 transition">Sign Up</a></li>
            @endauth
        </ul>
    </div>
</nav>
