@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-teal-700">Daftar Paket</h1>

        <a href="{{ route('admin.paket.create') }}"
            class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition duration-150 shadow-md">
            + Tambah Paket
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-lg" role="alert">
            <p class="font-bold">Berhasil</p>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <div class="bg-white shadow-lg rounded-xl overflow-hidden">
        <table class="min-w-full text-left">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="py-3 px-6 text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Paket</th>
                    <th class="py-3 px-6 text-xs font-medium text-gray-500 uppercase tracking-wider">Harga Paket</th>
                    <th class="py-3 px-6 text-xs font-medium text-gray-500 uppercase tracking-wider">Total Item</th>
                    <th class="py-3 px-6 text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
                @foreach ($paket as $p)
                    <tr>
                        <td class="py-4 px-6 font-semibold text-gray-800">{{ $p->nama_paket }}</td>
                        <td class="py-4 px-6 text-gray-600">Rp{{ number_format($p->harga_paket, 0, ',', '.') }}</td>
                        <td class="py-4 px-6 text-gray-600">
                            {{-- PERBAIKAN DI SINI: Menggunakan items_count --}}
                            <span class="font-medium text-teal-600">{{ $p->items_count }}</span> Barang
                        </td>
                        <td class="py-4 px-6 flex gap-3 items-center">
                            <a href="{{ route('admin.paket.edit', $p->id_paket) }}" 
                                class="text-blue-600 hover:text-blue-800 font-medium transition duration-150">
                                Edit
                            </a>

                            <form action="{{ route('admin.paket.destroy', $p->id_paket) }}" method="POST"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus paket {{ $p->nama_paket }}? Aksi ini tidak dapat dibatalkan.')">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="text-red-600 hover:text-red-800 font-medium transition duration-150">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach

                @if($paket->isEmpty())
                    <tr>
                        <td colspan="4" class="py-10 text-center text-gray-500 bg-white">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada Paket</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Mulai dengan menambahkan paket rental baru.
                            </p>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection