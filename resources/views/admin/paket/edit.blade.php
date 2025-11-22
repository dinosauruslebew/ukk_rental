@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto mt-10">
    
    <div class="bg-white p-8 rounded-2xl shadow-xl border border-gray-100">

        <h2 class="text-3xl font-extrabold text-gray-800 mb-6 border-b pb-3">✏️ Edit Paket: {{ $paket->nama_paket }}</h2>

        <form action="{{ route('admin.paket.update', $paket) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Bagian Nama dan Harga Paket --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label for="nama_paket" class="block mb-2 text-sm font-semibold text-gray-700">Nama Paket</label>
                    <input type="text" id="nama_paket" name="nama_paket" value="{{ old('nama_paket', $paket->nama_paket) }}"
                        class="w-full border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition duration-150" 
                        placeholder="Contoh: Paket Awal Petualang" required>
                </div>
                <div>
                    <label for="harga_paket" class="block mb-2 text-sm font-semibold text-gray-700">Harga Paket (per hari)</label>
                    <input type="number" id="harga_paket" name="harga_paket" value="{{ old('harga_paket', $paket->harga_paket) }}"
                        class="w-full border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition duration-150" 
                        placeholder="Masukkan harga, cth: 150000" required>
                </div>
            </div>

            <hr class="mb-8 border-gray-200">

            {{-- Bagian Pemilihan Barang (Tampilan Kartu Interaktif) --}}
            <h3 class="text-xl font-bold text-gray-800 mb-4">Ubah Item dalam Paket</h3>
            <p class="text-sm text-gray-500 mb-6">Pilih barang dan tentukan jumlahnya untuk paket ini.</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-8">
                @foreach ($barang as $b)
                    @php
                        // LOGIKA PENTING: Cek apakah barang ini sudah masuk paket
                        // Karena kita eager load, ini harus bekerja.
                        $included = $paket->items->firstWhere('id_barang', $b->id_barang);
                        $qty = $included ? $included->pivot->qty : '';
                        $isChecked = $included ? 'checked' : '';
                        $isDisabled = $included ? '' : 'disabled';
                        $cardClass = $included ? 'bg-teal-50 border-teal-600 shadow-inner' : 'border-gray-300';
                    @endphp

                    <div class="package-item-card border rounded-xl p-4 transition duration-200 cursor-pointer hover:shadow-md hover:border-teal-500 {{ $cardClass }}" 
                         data-item-id="{{ $b->id_barang }}">
                        
                        <label class="flex flex-col h-full justify-between">
                            <div class="flex items-center justify-between mb-3">
                                <p class="font-semibold text-gray-800">{{ $b->nama_barang }}</p>
                                {{-- Checkbox Hidden, akan diaktifkan melalui JavaScript/Tampilan --}}
                                <input type="checkbox" name="barang_id[]" value="{{ $b->id_barang }}" {{ $isChecked }}
                                    class="hidden form-checkbox h-5 w-5 text-teal-600 rounded" 
                                    id="checkbox-{{ $b->id_barang }}"> 
                            </div>
                            
                            <div class="text-sm">
                                <p class="text-gray-500 mb-2">Stok Tersedia: <span class="font-medium text-teal-600">{{ $b->stok }}</span></p>

                                <label for="qty-{{ $b->id_barang }}" class="block text-xs font-medium text-gray-600 mb-1">Jumlah (Qty)</label>
                                <input type="number" name="qty[{{ $b->id_barang }}]" id="qty-{{ $b->id_barang }}"
                                    placeholder="0"
                                    min="0"
                                    max="{{ $b->stok }}"
                                    value="{{ $qty }}"
                                    class="w-full border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:ring-teal-500 focus:border-teal-500" 
                                    {{ $isDisabled }}>
                            </div>
                        </label>
                    </div>
                @endforeach
            </div>
            
            {{-- Deskripsi --}}
            <label for="deskripsi" class="block mb-2 text-sm font-semibold text-gray-700">Deskripsi Paket (Opsional)</label>
            <textarea id="deskripsi" name="deskripsi" rows="4"
                class="w-full border-gray-300 rounded-lg px-4 py-2.5 mb-8 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition duration-150" 
                placeholder="Tulis deskripsi singkat tentang paket ini.">{{ old('deskripsi', $paket->deskripsi) }}</textarea>


            <hr class="mb-6 border-gray-200">

            {{-- Tombol Aksi --}}
            <div class="pt-4 flex justify-end">
                <button type="submit" class="px-6 py-2.5 bg-teal-600 hover:bg-teal-700 text-white font-semibold rounded-lg shadow-lg transition duration-200 transform hover:scale-[1.01] focus:outline-none focus:ring-4 focus:ring-teal-500 focus:ring-opacity-50">
                    💾 Simpan Perubahan
                </button>
            </div>
        </form>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.package-item-card');

        cards.forEach(card => {
            const checkbox = card.querySelector('input[type="checkbox"]');
            const qtyInput = card.querySelector('input[type="number"]');

            // --- Logika Interaksi Klik Kartu ---
            card.addEventListener('click', function(event) {
                // Hanya toggle jika yang diklik bukan input number
                if (event.target !== qtyInput) {
                    checkbox.checked = !checkbox.checked;
                    
                    if (checkbox.checked) {
                        card.classList.add('bg-teal-50', 'border-teal-600', 'shadow-inner');
                        qtyInput.disabled = false;
                        qtyInput.focus();
                    } else {
                        card.classList.remove('bg-teal-50', 'border-teal-600', 'shadow-inner');
                        qtyInput.disabled = true;
                        qtyInput.value = ''; // Reset Qty
                    }
                }
            });

            // Pastikan Qty Input tidak memicu toggle card
            qtyInput.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });
    });
</script>
@endsection