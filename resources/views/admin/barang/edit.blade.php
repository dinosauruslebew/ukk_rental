@extends('layouts.admin')

@section('content')

<div class="max-w-4xl mx-auto bg-white p-10 rounded-2xl shadow-lg mt-10 border border-gray-100">
<h2 class="text-3xl font-bold mb-8 text-teal-800 flex items-center gap-2">✏️ Edit Barang</h2>

{{-- Tampilkan pesan error validasi jika ada --}}
@if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl relative mb-4" role="alert">
        <strong class="font-bold">Error!</strong>
        <span class="block sm:inline">Terdapat beberapa masalah pada input Anda.</span>
        <ul class="mt-2 list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.barang.update', $barang) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @method('PUT')


    <!-- Nama Barang -->
    <div>
        <label class="block text-gray-700 mb-2 font-semibold">Nama Barang</label>
        <input type="text" name="nama_barang" value="{{ old('nama_barang', $barang->nama_barang) }}"
            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-600 focus:border-transparent transition duration-200" required>
    </div>

    <!-- Stok -->
    <div>
        <label class="block text-gray-700 mb-2 font-semibold">Stok Barang</label>
        <input type="number" name="stok" value="{{ old('stok', $barang->stok) }}"
            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-600 transition duration-200" required>
    </div>

    <!-- Harga Sewa (per malam) -->
    <div>
        <label class="block text-gray-700 mb-2 font-semibold">Harga Sewa (per malam)</label>
        <input type="number" name="harga_sewa" value="{{ old('harga_sewa', $barang->harga_sewa) }}"
            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-600 transition duration-200" required step="1">
    </div>

    <!-- Harga 2 Malam (Opsional) -->
    <div>
        <label class="block text-gray-700 mb-2 font-semibold">Harga 2 Malam (Opsional)</label>
        <input type="number" name="harga_2_malam" value="{{ old('harga_2_malam', $barang->harga_2_malam) }}"
            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-600 transition duration-200" step="1">
        <p class="text-xs text-gray-500 mt-1">Biarkan kosong jika tidak ada diskon harga untuk 2 malam.</p>
    </div>

    <!-- Harga 3 Malam (Opsional) -->
    <div>
        <label class="block text-gray-700 mb-2 font-semibold">Harga 3 Malam (Opsional)</label>
        <input type="number" name="harga_3_malam" value="{{ old('harga_3_malam', $barang->harga_3_malam) }}"
            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-600 transition duration-200" step="1">
        <p class="text-xs text-gray-500 mt-1">Biarkan kosong jika tidak ada diskon harga untuk 3 malam.</p>
    </div>


    <!-- Status Barang -->
    <div>
        <label class="block text-gray-700 mb-2 font-semibold">Status Barang</label>
        <select name="status"
            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-600 transition duration-200" required>
            <option value="tersedia" {{ old('status', $barang->status) == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
            <option value="tidak tersedia" {{ old('status', $barang->status) == 'tidak tersedia' ? 'selected' : '' }}>Tidak Tersedia</option>
        </select>
    </div>

    <!-- Deskripsi -->
    <div>
        <label class="block text-gray-700 mb-2 font-semibold">Deskripsi</label>
        <textarea name="deskripsi" rows="4"
            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-600 transition duration-200">{{ old('deskripsi', $barang->deskripsi) }}</textarea>
    </div>

    <div class="mb-6">
        @if ($barang->gambar)
            <div class="mb-4">
                <label class="block text-gray-700 mb-1">Gambar Saat Ini</label>
                {{-- Pastikan URL gambar menggunakan asset() --}}
                <img src="{{ asset('storage/' . $barang->gambar) }}" alt="Gambar Barang" 
                    class="w-full h-48 object-contain rounded-xl mb-3 border border-gray-200 p-2 bg-white">
                <span class="text-sm text-gray-500">Gambar akan diganti jika Anda mengunggah yang baru.</span>
            </div>
        @endif

        <label class="block text-gray-700 mb-2 font-semibold">Upload Gambar Baru (Opsional)</label>

        <!-- kotak upload -->
        <div 
            id="preview-container" 
            class="w-full h-56 border-2 border-dashed border-gray-300 rounded-2xl flex flex-col items-center justify-center cursor-pointer hover:border-teal-600 bg-gray-50 transition"
            onclick="document.getElementById('gambar').click()"
        >
            <img id="preview" class="hidden w-full h-full object-contain p-2 rounded-2xl" alt="Preview Gambar">
            <div id="upload-text" class="text-gray-500 flex flex-col items-center">
                <i class="fa-solid fa-cloud-arrow-up text-2xl mb-2"></i>
                <span class="text-sm">Klik untuk unggah gambar</span>
            </div>
        </div>

        <!-- input file hidden -->
        <input 
            type="file" 
            name="gambar" 
            id="gambar" 
            accept="image/*" 
            class="hidden" 
            onchange="previewImage(event)"
        >
    </div>

    <!-- Script Preview -->
    <script>
    // Memastikan font awesome sudah dimuat (jika Anda menggunakannya)
    // <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    function previewImage(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('preview');
        const uploadText = document.getElementById('upload-text');

        if (file) {
            const reader = new FileReader();
            reader.onload = e => {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                // Ganti 'object-cover' menjadi 'object-contain' agar gambar tidak terpotong saat preview
                preview.classList.add('object-contain'); 
                uploadText.classList.add('hidden');
            }
            reader.readAsDataURL(file);
        }
    }
    </script>

    <!-- Tombol -->
    <div class="flex justify-end gap-3 pt-4">
        <a href="{{ route('admin.barang.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
            Batal
        </a>
        <button type="submit" class="px-5 py-2 bg-teal-700 text-white rounded-lg hover:bg-teal-800 transition">
            💾 Simpan Perubahan
        </button>
    </div>
</form>


</div>
@endsection