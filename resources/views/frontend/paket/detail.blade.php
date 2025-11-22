@extends('layouts.frontend')

@section('content')
<div class="max-w-5xl mx-auto mt-6">

    <div class="bg-white shadow rounded-lg p-6">

        <div class="flex gap-6">

            @if($paket->gambar)
                <img src="{{ asset('storage/' . $paket->gambar) }}" class="w-64 rounded">
            @endif

            <div>
                <h1 class="text-2xl font-bold mb-2">{{ $paket->nama_paket }}</h1>

                <p class="text-lg text-teal-700 font-bold">
                    Rp{{ number_format($paket->harga_paket) }}
                </p>

                <p class="mt-3 text-gray-600">
                    {{ $paket->deskripsi ?? 'Tidak ada deskripsi.' }}
                </p>

                {{-- Tombol Tambah ke Cart Paket --}}
                <form action="{{ route('cart.addPaket', $paket->id_paket) }}" method="POST" class="mt-4">
                    @csrf
                    <button class="px-4 py-2 bg-teal-700 text-white rounded-lg">
                        Tambah ke Keranjang
                    </button>
                </form>
            </div>

        </div>

        <hr class="my-6">

        <h2 class="font-semibold text-xl mb-3">Isi Paket:</h2>

        <div class="space-y-2">
            @foreach ($paket->items as $item)
                <div class="flex justify-between border-b py-2">
                    <p>{{ $item->nama_barang }}</p>
                    <p class="font-semibold">x{{ $item->pivot->qty }}</p>
                </div>
            @endforeach
        </div>

    </div>

</div>
@endsection
