@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto p-6">
    <h2 class="text-3xl font-bold mb-6">Semua Paket Camping</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        @foreach($paket as $p)
        <div class="bg-white rounded-xl p-4 shadow">
            <h3 class="text-xl font-bold">{{ $p->nama_paket }}</h3>
            <p class="text-emerald-600 font-bold mt-1">
                Rp {{ number_format($p->harga_paket) }}
            </p>

            <ul class="mt-3 text-gray-700 text-sm">
                @foreach($p->items as $item)
                <li>• {{ $item->nama_barang }} (x{{ $item->pivot->qty }})</li>
                @endforeach
            </ul>

        </div>
        @endforeach

    </div>
</div>

@endsection
