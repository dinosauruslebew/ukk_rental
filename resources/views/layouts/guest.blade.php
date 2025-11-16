<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased bg-gray-50">

    <div class="min-h-screen grid grid-cols-1 md:grid-cols-2">

        <!-- KIRI: GAMBAR -->
        <div class="relative hidden md:block">
            <img src="/utama.jpg"
                 class="w-full h-full object-cover brightness-75"
                 alt="Jengki Adventure">

            <!-- Overlay -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>

            <!-- Teks Branding -->
            <div class="absolute bottom-10 left-10 text-white">
                <h1 class="text-4xl font-bold drop-shadow-lg">Jengki Adventure</h1>
                <p class="text-lg mt-2 max-w-sm drop-shadow-md">
                    Solusi perlengkapan outdoor lengkap untuk pendakian & camping.
                </p>
            </div>
        </div>

        <!-- KANAN: FORM -->
        <div class="flex justify-center items-center px-6 py-10">
            <div class="w-full max-w-md rounded-2xl p-8">
                {{ $slot }}
            </div>
        </div>

    </div>

</body>
</html>
