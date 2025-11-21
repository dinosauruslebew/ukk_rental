<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Jengki Adventure</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Gaya tambahan untuk gambar latar belakang */
        .image-bg {
            background-image: url('/utama.jpg');
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body class="">

    <div class="flex h-screen">

        <div class="relative hidden lg:block lg:w-1/2 image-bg">
            
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent"></div>

            <div class="absolute bottom-0 p-12 text-white">
                <h1 class="text-5xl font-extrabold mb-4 leading-tight">
                    Jengki Adventure
                </h1>
                <p class="text-xl font-light">
                    Sewa peralatan *camping* dan *hiking* berkualitas terbaik. Mulai petualanganmu sekarang!
                </p>
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 overflow-y-auto">
            <div class="w-full max-w-md bg-white p-8">
                
                <div class="flex flex-col items-center mb-6">
                    <img src="/logo.png" class="w-20 h-20 object-contain mb-3" alt="Logo Jengki Adventure">
                    <h2 class="text-3xl font-bold text-center text-gray-900">Register</h2>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="name" class="block font-medium text-sm text-gray-800">Name</label>
                        <input id="name"
                            class="block mt-1 w-full border-gray-300 rounded-md shadow-sm bg-white text-gray-800 focus:border-emerald-500 focus:ring-emerald-500"
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            required autofocus />
                        @error('name')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block font-medium text-sm text-gray-800">Email</label>
                        <input id="email"
                            class="block mt-1 w-full border-gray-300 rounded-md shadow-sm bg-white text-gray-800 focus:border-emerald-500 focus:ring-emerald-500"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required autocomplete="username" />
                        @error('email')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block font-medium text-sm text-gray-800">Password</label>
                        <input id="password"
                            class="block mt-1 w-full border-gray-300 rounded-md shadow-sm bg-white text-gray-800 focus:border-emerald-500 focus:ring-emerald-500"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />
                        @error('password')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block font-medium text-sm text-gray-800">Confirm Password</label>
                        <input id="password_confirmation"
                            class="block mt-1 w-full border-gray-300 rounded-md shadow-sm bg-white text-gray-800 focus:border-emerald-500 focus:ring-emerald-500"
                            type="password"
                            name="password_confirmation"
                            required autocomplete="new-password" />
                        @error('password_confirmation')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between pt-4">
                        <a class="text-sm text-gray-600 hover:text-gray-900 hover:underline"
                        href="{{ route('login') }}">
                            Already registered?
                        </a>
                        <button class="bg-emerald-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-emerald-700 transition">
                            Register
                        </button>
                    </div>
                </form>
                </div>
        </div>

    </div>

</body>
</html>