@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto mt-10"> {{-- Container lebih lebar sedikit --}}
    
    <div class="bg-white p-8 rounded-2xl shadow-xl border border-gray-100">

        <h2 class="text-3xl font-extrabold text-gray-800 mb-6 border-b pb-3">📦 Buat Paket Rental Baru</h2>

        <form action="{{ route('admin.paket.store') }}" method="POST">
            @csrf

            {{-- Bagian Nama dan Harga Paket --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label for="nama_paket" class="block mb-2 text-sm font-semibold text-gray-700">Nama Paket</label>
                    <input type="text" id="nama_paket" name="nama_paket" 
                        class="w-full border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition duration-150" 
                        placeholder="Contoh: Paket Awal Petualang" required>
                </div>
                <div>
                    <label for="harga_paket" class="block mb-2 text-sm font-semibold text-gray-700">Harga Paket (per hari)</label>
                    <input type="number" id="harga_paket" name="harga_paket" 
                        class="w-full border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition duration-150" 
                        placeholder="Masukkan harga, cth: 150000" required>
                </div>
            </div>

            ---

            {{-- Bagian Pemilihan Barang (Tampilan Kartu Interaktif) --}}
            <h3 class="text-xl font-bold text-gray-800 mb-4">Pilih Item dalam Paket</h3>
            <p class="text-sm text-gray-500 mb-6">Pilih barang yang akan dimasukkan ke dalam paket dan tentukan jumlahnya.</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-8">
                @foreach ($barang as $item)
                    <div class="package-item-card border border-gray-300 rounded-xl p-4 transition duration-200 cursor-pointer hover:shadow-md hover:border-teal-500" 
                         data-item-id="{{ $item->id }}">
                        
                        <label class="flex flex-col h-full justify-between">
                            <div class="flex items-center justify-between mb-3">
                                <p class="font-semibold text-gray-800">{{ $item->nama_barang }}</p>
                                {{-- Checkbox Hidden, akan diaktifkan melalui JavaScript/Tampilan --}}
                                <input type="checkbox" name="barang_id[]" value="{{ $item->id }}" 
                                    class="hidden form-checkbox h-5 w-5 text-teal-600 rounded" 
                                    id="checkbox-{{ $item->id }}"> 
                            </div>
                            
                            <div class="text-sm">
                                <p class="text-gray-500 mb-2">Stok Tersedia: <span class="font-medium text-teal-600">{{ $item->stok }}</span></p>

                                <label for="qty-{{ $item->id }}" class="block text-xs font-medium text-gray-600 mb-1">Jumlah (Qty)</label>
                                <input type="number" name="qty[{{ $item->id }}]" id="qty-{{ $item->id }}"
                                    placeholder="0"
                                    min="0"
                                    max="{{ $item->stok }}"
                                    class="w-full border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:ring-teal-500 focus:border-teal-500" 
                                    disabled>
                            </div>
                        </label>
                    </div>
                @endforeach
            </div>
            
            ---

            {{-- Tombol Aksi --}}
            <div class="pt-4 flex justify-end">
                <button type="submit" class="px-6 py-2.5 bg-teal-600 hover:bg-teal-700 text-white font-semibold rounded-lg shadow-lg transition duration-200 transform hover:scale-[1.01] focus:outline-none focus:ring-4 focus:ring-teal-500 focus:ring-opacity-50">
                    💾 Simpan Paket
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

            // Set initial state
            if (checkbox.checked) {
                card.classList.add('bg-teal-50', 'border-teal-600', 'shadow-inner');
                qtyInput.disabled = false;
            } else {
                qtyInput.disabled = true;
            }

            card.addEventListener('click', function(event) {
                // Hanya toggle jika yang diklik bukan input number
                if (event.target !== qtyInput) {
                    checkbox.checked = !checkbox.checked;
                    
                    if (checkbox.checked) {
                        // Desain saat terpilih
                        card.classList.add('bg-teal-50', 'border-teal-600', 'shadow-inner');
                        qtyInput.disabled = false;
                        qtyInput.focus();
                    } else {
                        // Desain saat tidak terpilih
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