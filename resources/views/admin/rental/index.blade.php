@extends('layouts.admin')

@section('content')
@php
    $statuses = ['Semua', 'Menunggu Konfirmasi', 'Menunggu Pembayaran', 'Selesai', 'Dibatalkan'];
@endphp

<div id="rentalApp" class="p-8  min-h-screen">

    <h1 class="text-3xl font-bold text-gray-800 mb-6">Daftar Penyewaan</h1>

    {{-- alert sukses --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Filter status --}}
    <div class="flex flex-wrap gap-3 mb-6" id="statusFilters">
        @foreach($statuses as $status)
            <button data-status="{{ $status }}" class="px-5 py-2 rounded-full border text-sm font-medium transition-all duration-300 bg-white border-gray-300 text-gray-700 hover:bg-teal-100">
                {{ $status }}
            </button>
        @endforeach
    </div>

    {{-- Card penyewaan --}}
    <div class="space-y-4" id="rentalsList">
        @foreach($rentals as $r)
            @php $rental = (array) $r; @endphp
            <div class="flex items-center justify-between bg-white border border-gray-200 shadow-md rounded-2xl px-6 py-4 hover:shadow-lg transition-all duration-300 rental-card" data-status="{{ $rental['status'] }}" data-rental='@json($rental, JSON_UNESCAPED_UNICODE)'>
                
                {{-- kiri: foto dan data penyewa --}}
                <div class="flex items-center space-x-4">
                <img src="{{ $rental['foto'] ?? 'https://i.pravatar.cc/100' }}" alt="foto penyewa"
                    class="w-14 h-14 rounded-full object-cover border border-teal-200 shadow-sm">

                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="font-semibold text-gray-800 text-lg">{{ $rental['nama'] }}</h3>
                                @php
                                    $status = $rental['status'] ?? '';
                                    $statusClass = match($status) {
                                        'Menunggu Konfirmasi' => 'bg-yellow-100 text-yellow-700',
                                        'Menunggu Pembayaran' => 'bg-purple-100 text-purple-700',
                                        'Selesai' => 'bg-green-100 text-green-700',
                                        'Dibatalkan' => 'bg-red-100 text-red-700',
                                        default => 'bg-gray-100 text-gray-700'
                                    };
                                @endphp
                                <span class="px-2 py-0.5 text-xs font-medium rounded-full {{ $statusClass }}">{{ $status }}</span>
                        </div>
                        <p class="text-sm text-gray-500">{{ $rental['email'] }}</p>
                        <p class="text-xs text-gray-400 italic">{{ $rental['barang'] }}</p>
                    </div>
                </div>

                {{-- kanan: total harga & tombol --}}
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <p class="text-lg font-bold text-gray-800">Rp{{ number_format($rental['total'] ?? 0, 0, ',', '.') }}</p>
                    </div>

                    <button type="button" data-rental='@json($rental, JSON_UNESCAPED_UNICODE)' class="open-detail bg-amber-500 hover:bg-amber-600 text-white font-medium px-4 py-1.5 rounded-full transition-all duration-300 shadow-sm hover:scale-105">Detail</button>
                </div>
            </div>
        @endforeach

        @if(count($rentals) === 0)
            <div class="text-center text-gray-500 italic py-10 bg-white rounded-xl shadow-sm">
                Belum ada data penyewaan.
            </div>
        @endif
    </div>

   {{-- Modal Detail --}}
<div id="rentalModal" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 transition-opacity duration-300">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg p-6 relative transform scale-95 opacity-0 transition-all duration-300" id="modalBox">
        <button id="closeModal" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 text-xl">✕</button>

        <h2 class="text-2xl font-bold text-teal-700 mb-5 flex items-center gap-2">
            <i class="fa-solid fa-receipt text-amber-500"></i> Detail Penyewaan
        </h2>

        <div id="modalContent" class="space-y-3 text-gray-700"></div>

        <div id="modalActions" class="mt-6 flex flex-col gap-3">
            <!-- Buttons muncul dinamis dari JS -->
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const statusButtons = document.querySelectorAll('#statusFilters button[data-status]');
    const rentalCards = document.querySelectorAll('.rental-card');
    const modal = document.getElementById('rentalModal');
    const modalBox = document.getElementById('modalBox');
    const modalContent = document.getElementById('modalContent');
    const modalActions = document.getElementById('modalActions');
    const closeModalBtn = document.getElementById('closeModal');

    // format angka rupiah
    const formatCurrency = (num) => (num || 0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");

    // animasi modal muncul
    const showModal = () => {
        modal.classList.remove('hidden');
        setTimeout(() => {
            modalBox.classList.remove('scale-95', 'opacity-0');
            modalBox.classList.add('scale-100', 'opacity-100');
        }, 50);
    };

    const hideModal = () => {
        modalBox.classList.add('scale-95', 'opacity-0');
        setTimeout(() => modal.classList.add('hidden'), 200);
    };

    // tombol filter status
    function filterByStatus(status) {
        rentalCards.forEach(card => {
            card.style.display = (status === 'Semua' || card.dataset.status === status) ? '' : 'none';
        });
    }

    statusButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            statusButtons.forEach(b => b.classList.remove('bg-teal-600','text-white','border-teal-600','shadow-md'));
            btn.classList.add('bg-teal-600','text-white','border-teal-600','shadow-md');
            filterByStatus(btn.dataset.status);
        });
    });

    // set default active ke "Semua"
    if (statusButtons.length > 0) {
        statusButtons[0].classList.add('bg-teal-600','text-white','border-teal-600','shadow-md');
        filterByStatus('Semua');
    }

    // buka modal detail
    document.querySelectorAll('.open-detail').forEach(btn => {
        btn.addEventListener('click', () => {
            const data = JSON.parse(btn.getAttribute('data-rental'));
            modalContent.innerHTML = `
                <div class="bg-gray-50 p-3 rounded-xl">
                    <p><strong>Nama:</strong> ${data.nama}</p>
                    <p><strong>Email:</strong> ${data.email}</p>
                    <p><strong>No. HP:</strong> ${data.no_hp || '-'}</p>
                    <p><strong>Alamat:</strong> ${data.alamat || '-'}</p>
                </div>

                <div class="bg-gray-50 p-3 rounded-xl">
                    <p><strong>Barang:</strong> ${data.barang}</p>
                    <p><strong>Tanggal Sewa:</strong> ${data.tanggal_sewa || '-'}</p>
                    <p><strong>Tanggal Kembali:</strong> ${data.tanggal_kembali || '-'}</p>
                    <p><strong>Total Harga:</strong> <span class="text-teal-600 font-semibold">Rp${formatCurrency(data.total || 0)}</span></p>
                    <p><strong>Status:</strong> 
                        <span class="px-2 py-0.5 text-xs font-semibold rounded-full 
                            ${data.status === 'Menunggu Konfirmasi' ? 'bg-yellow-100 text-yellow-700' :
                              data.status === 'Menunggu Pembayaran' ? 'bg-purple-100 text-purple-700' :
                              data.status === 'Sedang Disewa' ? 'bg-amber-100 text-amber-700' :
                              data.status === 'Selesai' ? 'bg-green-100 text-green-700' :
                              'bg-gray-100 text-gray-600'}">
                            ${data.status}
                        </span>
                    </p>
                </div>

                <div class="text-sm text-gray-500 italic mt-2">
                    ⚠️ Penyewa wajib meninggalkan jaminan berupa kartu identitas (KTP/SIM) di tempat rental.<br>
                    📅 Jika melewati tanggal kembali, akan dikenakan denda.<br>
                    🔧 Kerusakan barang akan dikenakan biaya penggantian sesuai kerugian.
                </div>
            `;

            // atur tombol sesuai status
            modalActions.innerHTML = '';
            if (data.status === 'Menunggu Konfirmasi') {
                modalActions.innerHTML = `
                    <form action="/admin/rental/${data.id}/confirm" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-teal-600 hover:bg-teal-700 text-white font-semibold py-2.5 rounded-lg transition-all duration-300">Konfirmasi Pesanan</button>
                    </form>
                `;
            } else if (data.status === 'Menunggu Pembayaran') {
                modalActions.innerHTML = `
                    <form action="/admin/rental/${data.id}/confirm-payment" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-amber-500 hover:bg-amber-600 text-white font-semibold py-2.5 rounded-lg transition-all duration-300">Konfirmasi Pembayaran</button>
                    </form>
                `;
            } else if (data.status === 'Sedang Disewa') {
                modalActions.innerHTML = `
                    <form action="/admin/rental/${data.id}/finish" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2.5 rounded-lg transition-all duration-300">Selesaikan Pesanan</button>
                    </form>
                `;
            }

            showModal();
        });
    });

    closeModalBtn.addEventListener('click', hideModal);
    modal.addEventListener('click', e => { if (e.target === modal) hideModal(); });
});
</script>

@endsection