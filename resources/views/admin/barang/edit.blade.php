@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-10 rounded-2xl shadow-lg mt-10 border border-gray-100">
    <h2 class="text-3xl font-bold mb-8 text-teal-800 flex items-center gap-2">✏️ Edit Barang</h2>

    <form action="{{ route('admin.barang.update', $barang) }}" method="POST" enctype="multipart/form-data">
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

        <!-- Harga -->
        <div>
            <label class="block text-gray-700 mb-2 font-semibold">Harga Sewa (per malam)</label>
            <input type="number" name="harga_sewa" value="{{ old('harga_sewa', $barang->harga_sewa) }}"
                class="w-full border border-gray-300 rounded-xl px-4 py-2.5 shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-600 transition duration-200" required>
        </div>
        <div>
            <label class="block text-gray-700 mb-2 font-semibold {{ !$barang->harga_2_malam ? 'opacity-50 cursor-not-allowed' : '' }}">Harga 2 Malam</label>
            <input type="number" name="harga_2_malam" value="{{ old('harga_2_malam', $barang->harga_2_malam) }}"
                class="w-full border border-gray-300 rounded-xl px-4 py-2.5 shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-600 transition duration-200" required>
        </div>
        <div>
            <label class="block text-gray-700 mb-2 font-semibold">Harga 3 Malam</label>
            <input type="number" name="harga_3_malam" value="{{ old('harga_sewa', $barang->harga_3_malam) }}"
                class="w-full border border-gray-300 rounded-xl px-4 py-2.5 shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-600 transition duration-200" required>
        </div>


         <!-- Deskripsi -->
        <div>
            <label class="block text-gray-700 mb-2 font-semibold">Deskripsi</label>
            <textarea name="deskripsi" rows="4"
                class="w-full border border-gray-300 rounded-xl px-4 py-2.5 shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-600 transition duration-200">{{ old('deskripsi', $barang->deskripsi) }}</textarea>
        </div>


            <!-- Status -->
        <div>
            <label class="block text-gray-700 mb-2 font-semibold">Status Barang</label>
            <select name="status" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-teal-600 focus:border-transparent">
                <option value="tersedia" {{ $barang->status == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                <option value="disewa" {{ $barang->status == 'disewa' ? 'selected' : '' }}>Disewa</option>
            </select>
        </div>

        <div class="mb-6">
            @if ($barang->gambar)
                <div class="mb-4">
                    <label class="block text-gray-700 mb-1">Gambar Saat Ini</label>
                    <img src="{{ asset('storage/' . $barang->gambar) }}" alt="Gambar Barang" 
                        class="w-full h-48 object-contain rounded-xl mb-3">
                </div>
            @endif

            <label class="block text-gray-700 mb-2">Upload Gambar</label>

            <!-- kotak upload -->
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
        function previewImage(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('preview');
            const uploadText = document.getElementById('upload-text');

            if (file) {
                const reader = new FileReader();
                reader.onload = e => {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
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
