@extends('layouts.app')

@section('content')

<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap');
    body { font-family: 'Inter', sans-serif; background-color: #f7fbee; }
    .form-input {
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .form-input:focus {
        border-color: #059669;
        box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.3);
    }
</style>

<div class="max-w-4xl mx-auto py-16 px-6" x-data="{ activeTab: '{{ session('activeTab', 'akun') }}' }">
    {{-- Menggunakan session('activeTab') untuk memastikan tab tetap aktif setelah redirect --}}

    <div class="text-center mb-10">
        <h1 class="text-3xl font-extrabold text-emerald-700">Pengaturan Profil</h1>
        <p class="text-gray-500 mt-2">Kelola data akun dan informasi rental Anda di sini.</p>
    </div>

    <!-- Alert Sukses Global -->
    @if(session('success') || session('warning'))
        <div 
            class="mb-6 p-4 @if(session('success')) bg-emerald-100 border-emerald-300 text-emerald-700 @else bg-yellow-100 border-yellow-300 text-yellow-700 @endif border rounded-xl flex items-center gap-2 font-medium">
            <i class="fa-solid fa-bell"></i>
            {{ session('success') ?? session('warning') }}
        </div>
    @endif
    
    <!-- Tab Navigation -->
    <div class="flex border-b border-gray-200 mb-8 bg-white p-2 rounded-xl shadow-lg">
        <button 
            @click="activeTab = 'akun'"
            :class="{ 'bg-emerald-600 text-white shadow-md': activeTab === 'akun', 'text-gray-600 hover:bg-gray-50': activeTab !== 'akun' }"
            class="flex-1 py-2.5 px-4 font-semibold rounded-lg transition duration-300 flex items-center justify-center gap-2">
            <i class="fa-solid fa-user-circle"></i> Info Akun
        </button>
        <button 
            @click="activeTab = 'rental'"
            :class="{ 'bg-emerald-600 text-white shadow-md': activeTab === 'rental', 'text-gray-600 hover:bg-gray-50': activeTab !== 'rental' }"
            class="flex-1 py-2.5 px-4 font-semibold rounded-lg transition duration-300 flex items-center justify-center gap-2">
            <i class="fa-solid fa-house-user"></i> Data Rental (Penting)
        </button>
    </div>

    <!-- Tab Content: Info Akun -->
    <div x-show="activeTab === 'akun'" class="bg-white p-8 rounded-2xl shadow-xl border border-gray-100">
        
        <h2 class="text-2xl font-bold text-gray-900 mb-6 border-b pb-3">Informasi Akun Dasar</h2>

        <form action="{{ route('profile.update') }}" 
            method="POST" enctype="multipart/form-data"
            class="space-y-6">
            @csrf

            {{-- FOTO PROFIL --}}
            <div class="flex flex-col items-center justify-center">

                <div class="relative w-32 h-32">
                    {{-- foto --}}
                    <img id="previewPhoto"
                        src="{{ Auth::user()->profile_photo 
                            ? asset('storage/' . Auth::user()->profile_photo) 
                            : 'https://api.dicebear.com/7.x/thumbs/png?seed=' . Auth::user()->email }}"
                        class="w-32 h-32 rounded-full object-cover shadow-md border-4 border-gray-100 ring-2 ring-emerald-300">

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
                <label class="block font-semibold mb-2 text-gray-700">Nama Lengkap</label>
                <input type="text" name="name" 
                        {{-- PERBAIKAN: Menggunakan old() dengan fallback user->name --}}
                        value="{{ old('name', $user->name) }}"
                        class="form-input w-full rounded-lg border-gray-300 p-3">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- EMAIL --}}
            <div>
                <label class="block font-semibold mb-2 text-gray-700">Email</label>
                <input type="email" name="email" 
                        {{-- PERBAIKAN: Menggunakan old() dengan fallback user->email --}}
                        value="{{ old('email', $user->email) }}"
                        class="form-input w-full rounded-lg border-gray-300 p-3">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- PASSWORD --}}
            <div>
                <label class="block font-semibold mb-2 text-gray-700">Password Baru (Opsional)</label>
                <input type="password" name="password"
                        placeholder="Biarkan kosong jika tidak ingin ganti password"
                        class="form-input w-full rounded-lg border-gray-300 p-3">
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- SUBMIT --}}
            <div class="pt-4">
                <button type="submit" 
                        class="w-full bg-emerald-600 text-white py-3 rounded-lg 
                            font-semibold hover:bg-emerald-700 transition shadow-md">
                    Simpan Perubahan Akun
                </button>
            </div>
        </form>
    </div>

    <!-- Tab Content: Data Rental -->
    <div x-show="activeTab === 'rental'" class="bg-white p-8 rounded-2xl shadow-xl border border-gray-100">
        
        <h2 class="text-2xl font-bold text-gray-900 mb-6 border-b pb-3">Informasi Kontak & Data Diri Dasar</h2>
        <p class="text-gray-600 mb-6">Data ini **wajib** diisi agar pesanan sewa Anda dapat diproses oleh admin.</p>

        {{-- PENTING: Form menggunakan enctype untuk upload file --}}
        <form action="{{ route('profile.rental.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- NAMA LENGKAP (Readonly untuk Konsistensi) --}}
            <div>
                <label class="block font-semibold mb-2 text-gray-700">Nama Lengkap (Sesuai KTP)</label>
                {{-- Memastikan nama yang diisi di tab Akun selalu terlihat di sini --}}
                <input type="text" value="{{ $user->name }}" disabled class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-100 text-gray-500 shadow-sm p-3 cursor-not-allowed">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- TEMPAT LAHIR (Opsional) --}}
                <div>
                    <label for="birth_place" class="block font-semibold mb-2 text-gray-700">Tempat Lahir (Opsional)</label>
                    <input type="text" name="birth_place" id="birth_place"
                           {{-- Menggunakan old() dengan fallback user->birth_place --}}
                           value="{{ old('birth_place', $user->birth_place) }}"
                           class="form-input w-full rounded-lg border-gray-300 p-3"
                           placeholder="Contoh: Semarang">
                    @error('birth_place')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                {{-- TANGGAL LAHIR (Opsional) --}}
                <div>
                    <label for="birth_date" class="block font-semibold mb-2 text-gray-700">Tanggal Lahir (Opsional)</label>
                    <input type="date" name="birth_date" id="birth_date"
                           {{-- Menggunakan old() dengan fallback user->birth_date --}}
                           value="{{ old('birth_date', $user->birth_date) }}"
                           class="form-input w-full rounded-lg border-gray-300 p-3">
                    @error('birth_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- JENIS KELAMIN (Opsional) --}}
            <div class="mb-4">
                <label class="block font-semibold mb-2 text-gray-700">Jenis Kelamin (Opsional)</label>
                <div class="flex items-center space-x-6">
                    <label class="inline-flex items-center">
                        <input type="radio" name="gender" value="Laki-laki" 
                               {{-- Menggunakan old() untuk sticky radio button --}}
                               {{ old('gender', $user->gender) == 'Laki-laki' ? 'checked' : '' }} 
                               class="form-radio text-emerald-600 border-gray-300 w-5 h-5 focus:ring-emerald-500">
                        <span class="ml-2 text-gray-700">Laki-laki</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="gender" value="Perempuan" 
                               {{-- Menggunakan old() untuk sticky radio button --}}
                               {{ old('gender', $user->gender) == 'Perempuan' ? 'checked' : '' }} 
                               class="form-radio text-emerald-600 border-gray-300 w-5 h-5 focus:ring-emerald-500">
                        <span class="ml-2 text-gray-700">Perempuan</span>
                    </label>
                </div>
                @error('gender')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            {{-- ALAMAT LENGKAP --}}
            <div>
                <label for="address" class="block font-semibold mb-2 text-gray-700 required">
                    Alamat Lengkap (Domisili) <span class="text-red-500">*</span>
                </label>
                <textarea name="address" id="address" rows="4" required
                          class="form-input w-full rounded-lg border-gray-300 p-3"
                          placeholder="Masukkan alamat domisili lengkap Anda untuk keperluan verifikasi.">{{ old('address', $user->address) }}</textarea>
                    {{-- Menggunakan old() dengan fallback user->address --}}
                @error('address')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- NOMOR TELEPON --}}
            <div>
                <label for="phone_number" class="block font-semibold mb-2 text-gray-700 required">
                    Nomor Telepon / WhatsApp <span class="text-red-500">*</span>
                </label>
                <input type="text" name="phone_number" id="phone_number" required
                       {{-- Menggunakan old() dengan fallback user->phone_number --}}
                       value="{{ old('phone_number', $user->phone_number) }}"
                       class="form-input w-full rounded-lg border-gray-300 p-3"
                       placeholder="Contoh: 081234567890">
                @error('phone_number')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            
            <div class="pt-4">
                <button type="submit" 
                        class="w-full bg-emerald-600 text-white py-3 rounded-lg 
                            font-semibold hover:bg-emerald-700 transition shadow-md">
                    Simpan Data Rental
                </button>
            </div>
            
            {{-- PENTING: Hidden field untuk NIK dan KTP yang dihapus, 
               agar tidak terjadi error di Controller jika validation logic belum diupdate --}}
            <input type="hidden" name="nik_ktp" value="{{ $user->nik_ktp ?? '0000000000000000' }}">
            <input type="hidden" name="ktp_photo_dummy" value="">
        </form>
    </div>

</div>

{{-- PREVIEW SCRIPT --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const photoInput = document.getElementById("photoInput");
        const preview = document.getElementById("previewPhoto");

        if (photoInput && preview) {
            photoInput.addEventListener("change", function() {
                const file = this.files[0];
                if (file && file.type.match('image.*')) {
                    preview.src = URL.createObjectURL(file);
                } else {
                    console.error("File yang dipilih bukan gambar.");
                    photoInput.value = ''; 
                }
            });
        }
    });
</script>

@endsection