<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Jengki Adventure</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        .image-bg {
            background-image: url('/utama2.jpg');
            background-size: cover;
            background-position: center;
        }
    </style>
</head>

<body class="bg-gray-50">

    <div class="flex h-screen">

        <!-- LEFT IMAGE -->
        <div class="hidden lg:flex lg:w-1/2 image-bg relative">
            <div class="absolute inset-0 bg-black/50"></div>

            <div class="absolute bottom-0 p-12 text-white">
                <h1 class="text-4xl font-bold mb-3">Jengki Adventure</h1>
                <p class="text-lg leading-relaxed text-gray-200">
                    Sewa peralatan camping & hiking terbaik untuk perjalananmu.
                </p>
            </div>
        </div>

        <!-- RIGHT FORM -->
        <div class="w-full lg:w-1/2 flex items-center justify-center px-8 py-10 overflow-y-auto">       
            <div class="w-full max-w-md rounded-2xl p-10">

                <!-- Logo -->
                <div class="flex flex-col items-center mb-8">
                    <img src="/logo.png" class="w-16 h-16 object-contain mb-3" alt="Logo Jengki Adventure">
                    <p class="text-gray-500 text-sm text-center">
                        Masuk untuk menyewa barang & melihat pesananmu.
                    </p>
                </div>

                <h2 class="text-2xl font-bold text-center text-gray-900 mb-6">
                    Login
                </h2>

                <!-- Alert -->
                @if (session('status'))
                <div class="mb-4 text-sm bg-green-100 text-green-700 px-4 py-2 rounded-lg">
                    {{ session('status') }}
                </div>
                @endif

                @if ($errors->any())
                <div class="mb-4 text-sm bg-red-100 text-red-700 px-4 py-2 rounded-lg">
                    {{ $errors->first() }}
                </div>
                @endif

                <!-- FORM -->
                <form method="POST" action="/login" class="space-y-5">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label for="email" class="text-sm font-medium text-gray-700 mb-1 block">
                            Email
                        </label>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg
                                focus:outline-none focus:ring-2 focus:ring-emerald-500 
                                focus:border-emerald-500 transition"
                        >
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="text-sm font-medium text-gray-700 mb-1 block">
                            Password
                        </label>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg
                                focus:outline-none focus:ring-2 focus:ring-emerald-500 
                                focus:border-emerald-500 transition"
                        >
                    </div>

                    <!-- Remember -->
                    <div class="flex items-center">
                        <input id="remember_me" type="checkbox" name="remember"
                            class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-600">
                        <label for="remember_me" class="ml-2 text-sm text-gray-600">
                            Remember me
                        </label>
                    </div>

                    <!-- Actions -->
                    <div class="pt-2 flex justify-between items-center">
                        <a href="{{ route('register') }}"
                            class="text-sm text-gray-500 hover:text-gray-700 hover:underline">
                            Create Account
                        </a>

                        <button
                            class="bg-emerald-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-emerald-700 transition">
                            Login
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>

</body>
</html>
