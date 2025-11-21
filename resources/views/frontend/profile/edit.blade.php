@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-16 px-6">

    <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Edit Profil</h1>

    @if(session('success'))
        <div 
            class="mb-4 p-3 bg-emerald-100 border border-emerald-300 
                   text-emerald-700 rounded-lg text-center font-medium">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('profile.update') }}" 
          method="POST" enctype="multipart/form-data"
          class="space-y-6 bg-white p-8 rounded-2xl shadow-md border border-gray-100">
        @csrf

    {{-- FOTO PROFIL --}}
    <div class="flex flex-col items-center justify-center">

        {{-- container untuk avatar & tombol edit --}}
        <div class="relative w-32 h-32">

            {{-- foto --}}
            <img id="previewPhoto"
                src="{{ Auth::user()->profile_photo 
                    ? asset('storage/' . Auth::user()->profile_photo) 
                    : 'https://api.dicebear.com/7.x/thumbs/png?seed=' . Auth::user()->email }}"
                class="w-32 h-32 rounded-full object-cover shadow-md border">

            {{-- ikon pena, POSISI FIXED --}}
            <label for="photoInput"
                class="absolute bottom-0 right-0 translate-x-1/3 translate-y-1/3
                    bg-emerald-600 text-white w-10 h-10 rounded-full 
                    flex items-center justify-center cursor-pointer 
                    hover:bg-emerald-700 shadow-lg border-2 border-white">
                <i class="fa-solid fa-pen text-sm"></i>
            </label>

            <input type="file" id="photoInput" name="photo" accept="image/*" class="hidden">
        </div>

    </div>



        {{-- NAMA --}}
        <div>
            <label class="block font-semibold mb-2 text-gray-700">Nama</label>
            <input type="text" name="name" value="{{ $user->name }}"
                   class="w-full rounded-lg border-gray-300 
                          focus:ring-emerald-500 focus:border-emerald-500">
        </div>

        {{-- EMAIL --}}
        <div>
            <label class="block font-semibold mb-2 text-gray-700">Email</label>
            <input type="email" name="email" value="{{ $user->email }}"
                   class="w-full rounded-lg border-gray-300 
                          focus:ring-emerald-500 focus:border-emerald-500">
        </div>

        {{-- PASSWORD --}}
        <div>
            <label class="block font-semibold mb-2 text-gray-700">Password Baru (Opsional)</label>
            <input type="password" name="password"
                   placeholder="Biarkan kosong jika tidak ingin ganti password"
                   class="w-full rounded-lg border-gray-300 
                          focus:ring-emerald-500 focus:border-emerald-500">
        </div>

        {{-- SUBMIT --}}
        <div class="pt-4">
            <button 
                class="w-full bg-emerald-600 text-white py-3 rounded-lg 
                       font-semibold hover:bg-emerald-700 transition">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

{{-- PREVIEW SCRIPT --}}
<script>
    const photoInput = document.getElementById("photoInput");
    const preview = document.getElementById("previewPhoto");

    photoInput.addEventListener("change", function() {
        const file = this.files[0];
        if (file) {
            preview.src = URL.createObjectURL(file);
        }
    });
</script>

@endsection
