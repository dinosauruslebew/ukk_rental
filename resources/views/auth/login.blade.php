<x-guest-layout>

    <!-- LOGO -->
    <div class="flex flex-col items-center mb-6">
        <img src="/logo.png" class="w-20 h-20 object-contain mb-3" alt="Logo">
        <p class="text-gray-600 text-center text-sm max-w-xs">
            Masuk untuk melanjutkan penyewaan dan melihat status pemesananmu.
        </p>
    </div>

    <h2 class="text-3xl font-bold text-center mb-6 text-gray-900">Login</h2>

    <!-- Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="'Email'" class="text-gray-800"/>
            <x-text-input id="email"
                          class="block mt-1 w-full bg-white text-gray-800 focus:border-emerald-500 focus:ring-emerald-500"
                          type="email"
                          name="email"
                          :value="old('email')"
                          required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="'Password'" class="text-gray-800"/>
            <x-text-input id="password"
                          class="block mt-1 w-full bg-white text-gray-800 focus:border-emerald-500 focus:ring-emerald-500"
                          type="password"
                          name="password"
                          required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember -->
        <div class="flex items-center">
            <input id="remember_me"
                   type="checkbox"
                   class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500"
                   name="remember">
            <label for="remember_me" class="ml-2 text-sm text-gray-700">
                Remember Me
            </label>
        </div>

        <!-- BUTTON -->
        <div class="flex items-center justify-between mt-4">
            <a class="text-sm text-gray-600 hover:text-gray-900 hover:underline"
               href="{{ route('register') }}">
                Create an account
            </a>

            <button class="bg-emerald-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-emerald-700 transition">
                Log in
            </button>
        </div>

    </form>

</x-guest-layout>
