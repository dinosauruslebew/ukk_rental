@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white shadow-lg rounded-2xl p-8 mt-16 mb-20">
    <h2 class="text-2xl font-bold text-emerald-700 mb-6">Form Penyewaan</h2>

    <div class="flex items-center gap-4 mb-6 border-b pb-4">
        <img src="{{ asset('storage/' . $barang->gambar) }}" alt="{{ $barang->nama_barang }}" class="w-24 h-24 rounded-lg object-cover border">
        <div>
            <h3 class="font-semibold text-gray-800 text-lg">{{ $barang->nama_barang }}</h3>
            <p class="text-gray-500 text-sm">Rp{{ number_format($barang->harga_sewa,0,',','.') }}/hari</p>
        </div>
    </div>

    <form method="POST" action="{{ route('frontend.rental.store') }}">
    @csrf
    <input type="hidden" name="barang_id" value="{{ $barang->id_barang }}">

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-gray-700 text-sm mb-1">Tanggal Sewa</label>
                <input type="date" name="tanggal_sewa" required class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-gray-700 text-sm mb-1">Tanggal Kembali</label>
                <input type="date" name="tanggal_kembali" required class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500">
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm mb-1">Alamat</label>
            <textarea name="alamat" rows="2" required class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500"></textarea>
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 text-sm mb-1">Nomor HP</label>
            <input type="text" name="no_hp" required class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500">
        </div>

        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white py-2.5 rounded-lg font-semibold transition">
            Kirim Pesanan
        </button>
    </form>
</div>
@endsection
