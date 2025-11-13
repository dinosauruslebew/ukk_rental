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
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

    <x-navbar />

    <main class="flex-grow">
        @yield('content')
    </main>

    <x-footer />


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
                if (dropdown && !dropdown.classList.contains('hidden') && !dropdown.previousElementSibling.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.classList.add('hidden');
                }
            });
        });
    </script>
</body>
</html>
