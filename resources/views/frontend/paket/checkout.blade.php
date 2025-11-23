@extends('layouts.app')

@section('content')

<script src="https://cdn.tailwindcss.com"></script>

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

<div class="max-w-6xl mx-auto py-12 px-4 sm:px-6 lg:px-8">

<div class="text-center mb-10">
<h1 class="text-4xl font-extrabold text-emerald-700">Finalisasi Pesanan</h1>
<p class="text-gray-500 mt-2">Konfirmasi detail paket dan durasi sewa Anda sebelum melanjutkan pembayaran.</p>
</div>

{{-- Pastikan ini menggunakan POST ke route checkout.store --}}

<form action="{{ route('checkout.store') }}" method="POST" class="lg:grid lg:grid-cols-3 lg:gap-8">
@csrf

{{-- Kolom 1 & 2: Detail Sewa dan Data Penyewa --}}
<div class="lg:col-span-2 space-y-8">

    {{-- Detail Paket yang Dipilih --}}
    <div class="bg-white p-6 rounded-xl shadow-lg border border-emerald-100">
        <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
            <i class="fa-solid fa-box-open text-emerald-500 mr-3"></i> Detail Paket: <span class="text-emerald-600 ml-2">{{ $paket->nama_paket }}</span>
        </h2>
        
        <ul class="space-y-2 mb-4 border-b pb-4">
            @forelse ($paket->items as $item)
                <li class="flex justify-between text-gray-700 text-sm border-b border-dashed pb-1">
                    <span class="font-semibold">{{ $item->pivot->qty }}x {{ $item->barang->nama_barang }}</span>
                    <span class="text-gray-500">Rp{{ number_format($item->barang->harga_sewa, 0, ',', '.') }}/hari</span>
                </li>
            @empty
                <li class="text-center text-gray-500 italic">Paket ini tidak memiliki item.</li>
            @endforelse
        </ul>

        <p class="text-lg font-extrabold text-right text-gray-900">
            Harga Paket/Hari: <span class="text-emerald-600">Rp{{ number_format($paket->harga_paket, 0, ',', '.') }}</span>
        </p>
        
        {{-- Input tersembunyi untuk mengirim ID paket ke controller store --}}
        {{-- Gunakan id_paket jika itu kunci utama model Paket, atau id --}}
        <input type="hidden" name="paket_id" value="{{ $paket->id_paket ?? $paket->id }}">
    </div>

    {{-- Durasi Sewa dan Input Tanggal --}}
    <div class="bg-white p-6 rounded-xl shadow-lg border border-emerald-100">
        <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
            <i class="fa-solid fa-calendar-alt text-emerald-500 mr-3"></i> Durasi Peminjaman
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700">Tanggal Mulai Sewa</label>
                <input type="date" name="tanggal_mulai" id="tanggal_mulai" required 
                       class="form-input mt-1 block w-full rounded-lg border-gray-300 shadow-sm p-2.5" onchange="hitungBiaya()">
            </div>
            <div>
                <label for="tanggal_akhir" class="block text-sm font-medium text-gray-700">Tanggal Akhir Sewa</label>
                <input type="date" name="tanggal_akhir" id="tanggal_akhir" required 
                       class="form-input mt-1 block w-full rounded-lg border-gray-300 shadow-sm p-2.5" onchange="hitungBiaya()">
            </div>
        </div>
        
        {{-- Placeholder untuk Durasi Total dan Total Biaya --}}
        <div id="recap-biaya" class="mt-6 p-4 bg-emerald-50 rounded-lg text-base text-gray-700 font-semibold border-l-4 border-emerald-500">
            <p>Durasi Sewa: <span id="total_hari" class="text-lg text-emerald-700 font-extrabold">0</span> Hari</p>
            <p class="mt-1">Total Biaya Final: <span id="total_biaya" class="text-xl text-emerald-700 font-extrabold">Rp0</span></p>
        </div>

    </div>

    {{-- Data Penyewa (Sudah Login) --}}
    <div class="bg-white p-6 rounded-xl shadow-lg border border-emerald-100">
        <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
            <i class="fa-solid fa-user-circle text-emerald-500 mr-3"></i> Data Penyewa
        </h2>
        
        <div class="space-y-3 text-gray-700">
            <p class="border-b border-dashed pb-2"><strong>Nama:</strong> <span class="font-semibold">{{ $user->name }}</span></p>
            <p class="border-b border-dashed pb-2"><strong>Email:</strong> <span class="font-semibold">{{ $user->email }}</span></p>
            
            <div>
                 <label for="alamat_pengambilan" class="block text-sm font-medium text-gray-700 mt-2">Alamat Pengambilan (Opsional)</label>
                 <textarea name="alamat_pengambilan" id="alamat_pengambilan" rows="3" class="form-input mt-1 block w-full rounded-lg border-gray-300 shadow-sm p-2.5" placeholder="Masukkan alamat jika memerlukan layanan antar jemput"></textarea>
            </div>
        </div>
    </div>
    
</div>

{{-- Kolom 3: Ringkasan Total dan Tombol Konfirmasi --}}
<div class="lg:col-span-1 mt-8 lg:mt-0 sticky lg:top-4 h-fit">
    <div class="bg-emerald-700 p-6 rounded-xl shadow-2xl text-white">
        <h3 class="text-xl font-extrabold border-b border-emerald-500 pb-3 mb-4">Ringkasan Pembayaran</h3>
        
        <div class="space-y-3">
            <div class="flex justify-between">
                <span class="text-emerald-100">Harga Paket/Hari</span>
                <span class="font-semibold">Rp{{ number_format($paket->harga_paket, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-emerald-100">Lama Sewa (Hari)</span>
                <span id="ringkasan_total_hari">0</span>
            </div>
            <div class="flex justify-between pt-3 border-t border-emerald-600">
                <span class="text-lg font-extrabold">TOTAL BAYAR</span>
                <span id="ringkasan_total_biaya" class="text-xl font-extrabold text-yellow-300">Rp0</span>
                <input type="hidden" name="total_biaya_input" id="total_biaya_input">
                <input type="hidden" name="total_hari_input" id="total_hari_input">
            </div>
        </div>

        <button type="submit" id="submit-button" disabled
                class="mt-6 w-full py-3 bg-yellow-400 text-gray-900 font-extrabold rounded-lg shadow-xl hover:bg-yellow-300 transition duration-300 text-lg">
            Konfirmasi & Lanjutkan Pembayaran
        </button>
        <p id="error-message" class="text-center text-red-200 mt-2 text-sm hidden">Mohon lengkapi tanggal sewa yang valid.</p>
    </div>
</div>


</form>

</div>

<script>
// Logika JavaScript untuk menghitung total biaya dan validasi tanggal
document.addEventListener('DOMContentLoaded', function () {
const tanggalMulaiInput = document.getElementById('tanggal_mulai');
const tanggalAkhirInput = document.getElementById('tanggal_akhir');
const submitButton = document.getElementById('submit-button');
const errorMessage = document.getElementById('error-message');
const hargaPaketPerHari = {{ $paket->harga_paket }};

    // Elemen tampilan
    const totalHariElement = document.getElementById(&#39;total_hari&#39;);
    const totalBiayaElement = document.getElementById(&#39;total_biaya&#39;);
    const ringkasanTotalHariElement = document.getElementById(&#39;ringkasan_total_hari&#39;);
    const ringkasanTotalBiayaElement = document.getElementById(&#39;ringkasan_total_biaya&#39;);
    const totalBiayaInputElement = document.getElementById(&#39;total_biaya_input&#39;);
    const totalHariInputElement = document.getElementById(&#39;total_hari_input&#39;);

    // Set minimum tanggal mulai (tidak boleh hari ini atau sebelumnya)
    tanggalMulaiInput.min = new Date().toISOString().split(&quot;T&quot;)[0];


    // Fungsi format Rupiah
    window.formatRupiah = function(angka) {
        // Mengatasi error jika input bukan angka
        if (typeof angka !== &#39;number&#39; || isNaN(angka)) return &#39;Rp0&#39;; 
        
        const number_string = Math.round(angka).toString(); // Pastikan bilangan bulat
        const sisa = number_string.length % 3;
        let rupiah = number_string.substr(0, sisa);
        const ribuan = number_string.substr(sisa).match(/\d{3}/g);

        if (ribuan) {
            const separator = sisa ? &#39;.&#39; : &#39;&#39;;
            rupiah += separator + ribuan.join(&#39;.&#39;);
        }
        return &#39;Rp&#39; + rupiah;
    }

    // Fungsi perhitungan
    window.hitungBiaya = function() {
        const mulai = tanggalMulaiInput.value;
        const akhir = tanggalAkhirInput.value;
        
        if (!mulai || !akhir) {
            updateUI(0, 0, false, &quot;Mohon pilih tanggal mulai dan akhir sewa.&quot;);
            return;
        }

        // Gunakan string YYYY-MM-DD untuk kompatibilitas Date()
        const tglMulai = new Date(mulai + &#39;T00:00:00&#39;);
        const tglAkhir = new Date(akhir + &#39;T00:00:00&#39;);
        const hariIni = new Date();
        hariIni.setHours(0, 0, 0, 0); 

        // Validasi: Mulai harus hari ini atau setelahnya (walaupun min sudah di set, ini untuk validasi server-side)
        if (tglMulai &lt; hariIni) {
            updateUI(0, 0, false, &quot;Tanggal mulai sewa tidak boleh kurang dari hari ini.&quot;);
            return;
        }

        // Validasi: Akhir harus setelah atau sama dengan mulai
        if (tglAkhir &lt; tglMulai) {
            updateUI(0, 0, false, &quot;Tanggal akhir harus setelah atau sama dengan tanggal mulai.&quot;);
            return;
        }
        
        // Hitung perbedaan hari (dalam milidetik)
        const oneDay = 1000 * 60 * 60 * 24;
        const diffTime = tglAkhir.getTime() - tglMulai.getTime();
        // Konversi milidetik ke hari, lalu tambahkan 1 karena menyewa HARI PENUH (count(Tanggal_Mulai) sampai count(Tanggal_Akhir))
        const diffDays = Math.round(diffTime / oneDay) + 1;

        if (diffDays &lt;= 0) {
             updateUI(0, 0, false, &quot;Durasi sewa minimal 1 hari.&quot;);
             return;
        }
        
        const totalBiaya = diffDays * hargaPaketPerHari;

        updateUI(diffDays, totalBiaya, true);
    }

    // Fungsi update UI
    function updateUI(hari, biaya, isValid, errorMsg = &quot;&quot;) {
        totalHariElement.textContent = hari;
        ringkasanTotalHariElement.textContent = hari;

        totalBiayaElement.textContent = formatRupiah(biaya);
        ringkasanTotalBiayaElement.textContent = formatRupiah(biaya);

        totalBiayaInputElement.value = biaya;
        totalHariInputElement.value = hari;

        if (isValid &amp;&amp; biaya &gt; 0) {
            submitButton.disabled = false;
            errorMessage.classList.add(&#39;hidden&#39;);
        } else {
            submitButton.disabled = true;
            errorMessage.textContent = errorMsg;
            errorMessage.classList.remove(&#39;hidden&#39;);
        }
    }

    // Event Listeners sudah ditambahkan inline, tapi kita tambahkan di sini juga untuk jaga-jaga
    // tanggalMulaiInput.addEventListener(&#39;change&#39;, hitungBiaya);
    // tanggalAkhirInput.addEventListener(&#39;change&#39;, hitungBiaya);

    // Inisialisasi pada load (jika form kosong, disable tombol)
    hitungBiaya(); 
});


</script>

@endsection