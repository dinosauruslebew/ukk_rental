<x-app-layout>
    <div class="max-w-3xl mx-auto bg-white shadow-xl rounded-2xl p-10 mt-14 border border-emerald-100">
        <h2 class="text-3xl font-bold mb-8 text-emerald-700 text-center">Edit Profile</h2>

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <!-- foto profil -->
            <div class="flex flex-col items-center mb-8">
                <div class="relative">
                    <img src="{{ auth()->user()->profile_photo ? asset('storage/' . auth()->user()->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}"
                        alt="Profile Photo"
                        class="h-32 w-32 rounded-full object-cover border-4 border-emerald-400 shadow-md">
                    <label for="profile_photo" class="absolute bottom-0 right-0 bg-emerald-600 text-white p-2 rounded-full cursor-pointer hover:bg-emerald-700 transition">
                        <i class="fa-solid fa-camera"></i>
                    </label>
                </div>
                <input type="file" name="profile_photo" id="profile_photo" class="hidden" accept="image/*">
                <p class="text-gray-500 text-sm mt-2">Klik ikon kamera untuk mengganti foto profil</p>
            </div>

            <!-- name -->
            <div class="mb-5">
                <label class="block text-gray-700 mb-2 font-medium">Name</label>
                <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}"
                    class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
            </div>

            <!-- email -->
            <div class="mb-5">
                <label class="block text-gray-700 mb-2 font-medium">Email</label>
                <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}"
                    class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
            </div>

            <!-- tombol submit -->
            <div class="flex justify-center">
                <button type="submit"
                    class="bg-emerald-600 text-white px-8 py-2 rounded-full hover:bg-emerald-700 shadow-lg transition">
                    <i class="fa-solid fa-floppy-disk mr-2"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
