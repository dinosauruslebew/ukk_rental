@extends('layouts.frontend')

@section('content')
<div class="max-w-5xl mx-auto mt-6">

    <h1 class="text-2xl font-bold mb-5">Paket Camping</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

        @foreach($paket as $p)
        <div class="bg-white shadow rounded-lg p-4 hover:shadow-lg duration-150">

            @if($p->gambar)
                <img src="{{ asset('storage/' . $p->gambar) }}" class="rounded mb-3">
            @endif

            <h2 class="font-semibold text-lg">{{ $p->nama_paket }}</h2>

            <p class="text-teal-700 font-bold mt-2">
                Rp{{ number_format($p->harga_paket) }}
            </p>

            <p class="text-gray-500 text-sm">
                {{ $p->items->count() }} Barang
            </p>

            <a href="{{ route('frontend.paket.detail', $p->id_paket) }}"
               class="mt-3 inline-block bg-teal-600 text-white rounded-lg px-4 py-2">
                Lihat Detail
            </a>

        </div>
        @endforeach

    </div>

</div>
@endsection
