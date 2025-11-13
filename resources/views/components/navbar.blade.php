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

            <div class="relative hidden md:block">
                @auth
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}"
                           class="text-emerald-700 font-medium hover:text-emerald-800">
                            Dashboard
                        </a>
                    @else
                        <button id="userMenuButton" class="text-gray-700 hover:text-emerald-700 text-2xl focus:outline-none">
                            <i class="fa-regular fa-user"></i>
                        </button>

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
                    <button id="guestMenuButton" class="text-gray-700 hover:text-emerald-700 text-2xl focus:outline-none">
                        <i class="fa-regular fa-user"></i>
                    </button>

                    <div id="guestDropdown" class="hidden absolute right-0 mt-2 w-40 bg-white shadow-lg rounded-lg py-2 border border-gray-100">
                        <a href="{{ route('login') }}" class="block px-4 py-2 text-gray-700 hover:bg-emerald-50">Login</a>
                        <a href="{{ route('register') }}" class="block px-4 py-2 text-gray-700 hover:bg-emerald-50">Sign Up</a>
                    </div>
                @endauth
            </div>

            <button id="mobileMenuButton" class="md:hidden text-gray-700 hover:text-emerald-700 text-2xl focus:outline-none">
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
