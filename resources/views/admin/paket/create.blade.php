@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto mt-10">

    <div class="bg-white p-8 rounded-2xl shadow-xl border border-gray-100">

        <div class="flex justify-between items-center mb-6 border-b pb-3">
            <h2 class="text-3xl font-extrabold text-gray-800">📦 Buat Paket Rental Baru</h2>
            <a href="{{ route('admin.paket.index') }}" class="text-gray-500 hover:text-gray-700 text-sm font-medium transition">
                <i class="fa-solid fa-arrow-left mr-1"></i> Batal
            </a>
        </div>

        <form action="{{ route('admin.paket.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Bagian Nama dan Harga Paket --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label for="nama_paket" class="block mb-2 text-sm font-semibold text-gray-700">Nama Paket</label>
                    <input type="text" id="nama_paket" name="nama_paket" value="{{ old('nama_paket') }}"
                        class="w-full border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition duration-150"
                        placeholder="Contoh: Paket Awal Petualang" required>
                    @error('nama_paket') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="harga_paket" class="block mb-2 text-sm font-semibold text-gray-700">Harga Paket (per hari)</label>
                    <div class="relative">
                        <span class="absolute left-4 top-2.5 text-gray-500 text-sm">Rp</span>
                        <input type="number" id="harga_paket" name="harga_paket" value="{{ old('harga_paket') }}"
                            class="w-full border-gray-300 rounded-lg pl-10 pr-4 py-2.5 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition duration-150"
                            placeholder="0">
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Kosongkan jika ingin hitung otomatis (opsional).</p>
                </div>
            </div>

            <div class="mb-8">
                <label for="deskripsi" class="block mb-2 text-sm font-semibold text-gray-700">Deskripsi Paket</label>
                <textarea name="deskripsi" id="deskripsi" rows="3"
                    class="w-full border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition duration-150"
                    placeholder="Jelaskan apa saja keunggulan paket ini...">{{ old('deskripsi') }}</textarea>
            </div>

            <div class="mb-8">
                <label class="block mb-2 text-sm font-semibold text-gray-700">Gambar Paket</label>
                <input type="file" name="gambar" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 transition">
            </div>

            <hr class="border-gray-100 my-8">

            {{-- Bagian Pemilihan Barang (Tampilan Kartu Interaktif) --}}
            <h3 class="text-xl font-bold text-gray-800 mb-2">Pilih Item dalam Paket</h3>
            <p class="text-sm text-gray-500 mb-6">Klik kartu barang untuk memilihnya, lalu tentukan jumlah (Qty).</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-8 max-h-[600px] overflow-y-auto pr-2 custom-scrollbar">
                @foreach ($barang as $item)
                    {{-- Cek apakah barang ini sebelumnya dipilih (jika validasi gagal) --}}
                    @php
                        $isChecked = is_array(old('barang_id')) && in_array($item->id_barang, old('barang_id'));
                        $oldQty = old('qty.'.$item->id_barang) ?? 1;
                    @endphp

                    <div class="package-item-card border {{ $isChecked ? 'bg-teal-50 border-teal-600 shadow-md' : 'border-gray-300' }} rounded-xl p-4 transition duration-200 cursor-pointer hover:shadow-md hover:border-teal-500 relative group"
                         data-item-id="{{ $item->id_barang }}">

                        {{-- Checkbox Hidden --}}
                        <input type="checkbox" name="barang_id[]" value="{{ $item->id_barang }}"
                            class="hidden item-checkbox"
                            id="checkbox-{{ $item->id_barang }}"
                            {{ $isChecked ? 'checked' : '' }}>

                        <div class="flex flex-col h-full justify-between">
                            <div class="flex items-start justify-between mb-2">
                                <div>
                                    <p class="font-bold text-gray-800 text-sm">{{ $item->nama_barang }}</p>
                                    <p class="text-xs text-gray-500 mt-1">Stok: {{ $item->stok }}</p>
                                </div>
                                {{-- Indikator Centang --}}
                                <div class="check-indicator w-6 h-6 rounded-full border-2 {{ $isChecked ? 'bg-teal-600 border-teal-600' : 'border-gray-300' }} flex items-center justify-center transition-colors">
                                    <i class="fa-solid fa-check text-white text-xs {{ $isChecked ? '' : 'hidden' }}"></i>
                                </div>
                            </div>

                            <div class="mt-3 pt-3 border-t border-gray-100">
                                <label for="qty-{{ $item->id_barang }}" class="flex items-center justify-between text-xs font-medium text-gray-600 mb-1">
                                    <span>Jumlah (Qty)</span>
                                </label>
                                <input type="number" name="qty[{{ $item->id_barang }}]" id="qty-{{ $item->id_barang }}"
                                    value="{{ $oldQty }}"
                                    min="1" max="{{ $item->stok }}"
                                    class="qty-input w-full border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:ring-teal-500 focus:border-teal-500 text-center font-bold"
                                    {{ $isChecked ? '' : 'disabled' }}> {{-- Disabled by default kecuali old data ada --}}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @error('barang_id') <p class="text-red-500 text-sm mb-4 text-center">{{ $message }}</p> @enderror

            {{-- Tombol Aksi --}}
            <div class="pt-4 flex justify-end border-t border-gray-100">
                <button type="submit" class="px-8 py-3 bg-teal-600 hover:bg-teal-700 text-white font-bold rounded-xl shadow-lg transition duration-200 transform hover:-translate-y-0.5 flex items-center gap-2">
                    <i class="fa-solid fa-save"></i> Simpan Paket
                </button>
            </div>
        </form>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.package-item-card');

        cards.forEach(card => {
            const checkbox = card.querySelector('.item-checkbox');
            const qtyInput = card.querySelector('.qty-input');
            const indicator = card.querySelector('.check-indicator');
            const icon = indicator.querySelector('i');

            // Fungsi update tampilan kartu
            const updateCardState = () => {
                if (checkbox.checked) {
                    card.classList.add('bg-teal-50', 'border-teal-600', 'shadow-md');
                    card.classList.remove('border-gray-300');

                    indicator.classList.add('bg-teal-600', 'border-teal-600');
                    indicator.classList.remove('border-gray-300');
                    icon.classList.remove('hidden');

                    qtyInput.disabled = false;
                } else {
                    card.classList.remove('bg-teal-50', 'border-teal-600', 'shadow-md');
                    card.classList.add('border-gray-300');

                    indicator.classList.remove('bg-teal-600', 'border-teal-600');
                    indicator.classList.add('border-gray-300');
                    icon.classList.add('hidden');

                    qtyInput.disabled = true;
                }
            };

            // Event Listener pada Kartu
            card.addEventListener('click', function(event) {
                // Jangan toggle kalau user klik input number
                if (event.target !== qtyInput) {
                    checkbox.checked = !checkbox.checked;
                    updateCardState();
                    if (checkbox.checked) {
                        qtyInput.focus(); // Fokus ke input qty saat dipilih
                    }
                }
            });

            // Mencegah event bubbling pada input qty
            qtyInput.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });
    });
</script>

<style>
    /* Custom Scrollbar biar cantik */
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-track { background-color: transparent; }
</style>
@endsection
