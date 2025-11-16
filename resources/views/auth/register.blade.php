<x-guest-layout>

    <!-- LOGO + DESKRIPSI -->
    <div class="flex flex-col items-center mb-6">
        <img src="/logo.png" class="w-20 h-20 object-contain mb-3" alt="Logo Jengki Adventure">
    </div>

    <h2 class="text-3xl font-bold text-center mb-6 text-gray-900">Register</h2>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="'Name'" class="text-gray-800" />
            <x-text-input id="name"
                          class="block mt-1 w-full bg-white text-gray-800 focus:border-emerald-500 focus:ring-emerald-500"
                          type="text"
                          name="name"
                          :value="old('name')"
                          required autofocus />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="'Email'" class="text-gray-800" />
            <x-text-input id="email"
                          class="block mt-1 w-full bg-white text-gray-800 focus:border-emerald-500 focus:ring-emerald-500"
                          type="email"
                          name="email"
                          :value="old('email')"
                          required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="'Password'" class="text-gray-800" />
            <x-text-input id="password"
                          class="block mt-1 w-full bg-white text-gray-800 focus:border-emerald-500 focus:ring-emerald-500"
                          type="password"
                          name="password"
                          required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm -->
        <div>
            <x-input-label for="password_confirmation" :value="'Confirm Password'" class="text-gray-800" />
            <x-text-input id="password_confirmation"
                          class="block mt-1 w-full bg-white text-gray-800 focus:border-emerald-500 focus:ring-emerald-500"
                          type="password"
                          name="password_confirmation"
                          required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- BUTTON -->
        <div class="flex items-center justify-between mt-4">
            <a class="text-sm text-gray-600 hover:text-gray-900 hover:underline"
               href="{{ route('login') }}">
                Already registered?
            </a>

            <button class="bg-emerald-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-emerald-700 transition">
                Register
            </button>
        </div>

    </form>

</x-guest-layout>
