<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body class="bg-gray-100 text-gray-800 flex min-h-screen">

    <!-- SIDEBAR -->
    <aside class="fixed top-0 left-0 h-screen w-64 bg-teal-950 text-white flex flex-col shadow-lg">
        <div class="p-6 text-2xl font-bold border-b border-teal-800 tracking-wide">
            Jengki Adventure
        </div>

        <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-4 py-2 rounded-lg transition-all duration-200 hover:bg-teal-900 {{ request()->routeIs('admin.dashboard') ? 'bg-teal-900' : '' }}">
               <i class="fa-solid fa-house text-lg"></i>
               <span>Dashboard</span>
            </a>

            <a href="{{ route('admin.barang.index') }}"
               class="flex items-center gap-3 px-4 py-2 rounded-lg transition-all duration-200 hover:bg-teal-900 {{ request()->routeIs('admin.barang.*') ? 'bg-teal-900' : '' }}">
               <i class="fa-solid fa-box text-lg"></i>
               <span>Barang</span>
            </a>

            <a href="{{ route('admin.order.index') }}"
               class="flex items-center gap-3 px-4 py-2 rounded-lg transition-all duration-200 hover:bg-teal-900 {{ request()->routeIs('admin.rentals.*') ? 'bg-teal-900' : '' }}">
               <i class="fa-solid fa-file-contract text-lg"></i>
               <span>Rental</span>
            </a>

        </nav>

        <div class="p-4 border-t border-teal-800">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="w-full bg-red-900 hover:bg-red-800 px-3 py-2 rounded text-white flex items-center justify-center gap-2 transition-all duration-300 ease-out hover:scale-105">
                    <i class="fa-solid fa-right-from-bracket rotate-180"></i>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 ml-64 flex flex-col">

        {{-- <!-- ✨ Header Modern -->
        <header class="sticky top-0 bg-white/80 backdrop-blur-xl border-gray-200 p-3 flex justify-between items-center transition-all duration-300 ease-in-out z-40">
            <div>
                <h1 class="text-xl font-semibold text-gray-700">Dashboard Admin</h1>
            </div>

            <div class="flex items-center gap-4">
                <button class="relative group">
                    <i class="fa-regular fa-bell text-gray-500 text-xl hover:text-teal-700 transition-all duration-300"></i>
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full px-1.5 py-0.5">3</span>
                    <span class="absolute right-1/2 top-8 opacity-0 group-hover:opacity-100 text-xs bg-gray-800 text-white px-2 py-1 rounded-md shadow-md transition-opacity duration-300">
                        Notifikasi
                    </span>
                </button>

                <div class="flex items-center gap-3 bg-white px-3 py-2 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-teal-600 to-teal-800 flex items-center justify-center text-white text-lg font-bold shadow-inner">
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <div class="text-right">
                        <span class="block text-gray-700 font-medium">{{ auth()->user()->name ?? 'Admin' }}</span>
                        <span class="text-xs text-gray-500">Administrator</span>
                    </div>
                </div>
            </div>
        </header> --}}

        <!-- ✳️ Konten utama -->
        <div class="flex-1 p-6 overflow-y-auto bg-gradient-to-b from-blue-50 to-pink-50">
            @yield('content')
        </div>
    </main>

    {{-- 🎉 Modal selamat datang --}}
    @if (session('login_success'))
    <div id="welcomeModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-40 z-50">
        <div class="bg-white rounded-2xl shadow-xl p-8 text-center animate-fadeIn relative max-w-sm w-full">
            <h2 class="text-2xl font-bold text-teal-800 mb-2">Selamat datang, Admin! 👋</h2>
            <p class="text-gray-600 mb-4">
                {{ now()->translatedFormat('l, d F Y • H:i') }}
            </p>
            <p class="text-gray-700">Semoga hari ini produktif 💪</p>

            <button onclick="closeModal()" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
    </div>

    <script>
        function closeModal() {
            document.getElementById('welcomeModal').classList.add('hidden');
        }
        setTimeout(() => closeModal(), 4000);
    </script>

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeIn { animation: fadeIn 0.5s ease-out; }
    </style>
    @endif

</body>
</html>
