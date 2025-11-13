@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-10 rounded-2xl shadow-lg mt-10 border border-gray-100">
    <h2 class="text-3xl font-bold mb-8 text-teal-800 flex items-center gap-2">
        <i class="fa-solid fa-box"></i>
        Tambah Barang Baru
    </h2>

    <form action="{{ route('admin.barang.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Nama Barang -->
        <div>
            <label class="block text-gray-700 mb-2 font-semibold">Nama Barang</label>
            <input type="text" name="nama_barang" value="{{ old('nama_barang') }}"
                class="w-full border border-gray-300 rounded-xl px-4 py-2.5 shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-600 focus:border-transparent transition duration-200" required>
        </div>

        <!-- Stok -->
        <div>
            <label class="block text-gray-700 mb-2 font-semibold">Stok Barang</label>
            <input type="number" name="stok" value="{{ old('stok') }}"
                class="w-full border border-gray-300 rounded-xl px-4 py-2.5 shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-600 transition duration-200" required>
        </div>

        <!-- Harga -->
        <div>
            <label class="block text-gray-700 mb-2 font-semibold">Harga Sewa (per malam)</label>
            <input type="number" name="harga_sewa" value="{{ old('harga_sewa') }}"
                class="w-full border border-gray-300 rounded-xl px-4 py-2.5 shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-600 transition duration-200" required>
        </div>

        <!-- Deskripsi -->
        <div>
            <label class="block text-gray-700 mb-2 font-semibold">Deskripsi</label>
            <textarea name="deskripsi" rows="4"
                class="w-full border border-gray-300 rounded-xl px-4 py-2.5 shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-600 transition duration-200">{{ old('deskripsi') }}</textarea>
        </div>

        <!-- Status -->
        <div>
            <label class="block text-gray-700 mb-2 font-semibold">Status Barang</label>
            <select name="status" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 shadow-sm focus:ring-2 focus:ring-teal-600 focus:border-transparent">
                <option value="tersedia" {{ old('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                <option value="disewa" {{ old('status') == 'disewa' ? 'selected' : '' }}>Disewa</option>
            </select>
        </div>

        <!-- Upload Gambar -->
        <div>
            <label class="block text-gray-700 mb-2 font-semibold">Gambar Barang</label>

            <!-- Kotak Upload -->
            <div 
                id="preview-container" 
                class="w-full h-56 border-2 border-dashed border-gray-300 rounded-2xl flex flex-col items-center justify-center cursor-pointer hover:border-teal-600 bg-gray-50 transition"
                onclick="document.getElementById('gambar').click()"
            >
                <img id="preview" class="hidden w-full h-full object-cover rounded-2xl" alt="Preview Gambar">
                <div id="upload-text" class="text-gray-500 flex flex-col items-center">
                    <i class="fa-solid fa-cloud-arrow-up text-2xl mb-2"></i>
                    <span class="text-sm">Klik untuk unggah gambar</span>
                </div>
            </div>

            <!-- Input File Hidden -->
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
        function previewImage(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('preview');
            const uploadText = document.getElementById('upload-text');
            const container = document.getElementById('preview-container');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    uploadText.classList.add('hidden');
                    container.classList.add('border-teal-600', 'bg-white');
                }
                reader.readAsDataURL(file);
            }
        }
        </script>

        <!-- Tombol -->
        <div class="flex justify-end gap-3 pt-4">
            <a href="{{ route('admin.barang.index') }}" 
               class="px-5 py-2.5 border border-gray-300 rounded-xl text-gray-600 font-medium hover:bg-gray-100 transition">
                <i class="fa-solid fa-xmark mr-1"></i> Batal
            </a>
            <button type="submit" 
               class="px-5 py-2.5 bg-teal-700 text-white font-medium rounded-xl hover:bg-teal-800 transition shadow-sm hover:shadow-md">
                <i class="fa-solid fa-circle-plus mr-1"></i> Tambah
            </button>
        </div>
    </form>
</div>
@endsection
