@extends('layouts.admin')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="min-h-screen p-5">
    {{-- Header --}}
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Daftar Barang</h2>
        <a href="{{ route('admin.barang.create') }}" 
           class="bg-indigo-600 text-white px-5 py-2.5 rounded-xl shadow-md hover:bg-indigo-700 hover:scale-105 transition-all duration-300 ease-out">
           + Tambah Barang
        </a>
    </div>

    {{-- ✅ Toast Notification --}}
    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2500,
                timerProgressBar: true,
                background: '#ffffff',
                color: '#333',
                showClass: { popup: 'animate__animated animate__fadeInDown' },
                hideClass: { popup: 'animate__animated animate__fadeOutUp' }
            })
            Toast.fire({
                icon: 'success',
                title: '{{ session('success') }}'
            })
        })
    </script>
    @endif

    {{-- 📋 Table --}}
    <div class="bg-white rounded-3xl shadow-lg p-6 border border-gray-100 backdrop-blur-md transition-all duration-300 ease-in-out hover:shadow-xl">
        <table class="min-w-full text-sm text-left text-gray-700">
            <thead class="bg-gray-100 text-gray-600 uppercase tracking-wide text-xs">
                <tr>
                    <th class="py-3 px-6">Barang</th>
                    <th class="py-3 px-6">Harga / Hari</th>
                    <th class="py-3 px-6">Stok</th>
                    <th class="py-3 px-6">Status</th>
                    <th class="py-3 px-6 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
                @forelse($barang as $item)
                <tr class="hover:bg-gray-50 transition-all duration-300 ease-out">
                    {{-- 🖼️ Foto + Nama --}}
                    <td class="py-4 px-6 flex items-center gap-4">
                        @if($item->gambar)
                            <img src="{{ asset('storage/' . $item->gambar) }}" 
                                 class="w-12 h-12 rounded-xl object-cover border border-gray-200 shadow-sm hover:scale-110 transition-transform duration-300 ease-out"
                                 alt="{{ $item->nama_barang }}">
                        @else
                            <div class="w-12 h-12 rounded-xl bg-gray-200 flex items-center justify-center text-gray-500">
                                <i class="fa fa-image"></i>
                            </div>
                        @endif
                        <span class="font-semibold text-gray-800">{{ $item->nama_barang }}</span>
                    </td>

                    <td class="py-4 px-6 text-gray-600">Rp {{ number_format($item->harga_sewa, 0, ',', '.') }}</td>
                    <td class="py-4 px-6 text-gray-600">{{ $item->stok }}</td>

                    <td class="py-4 px-6">
                        <span class="px-3 py-1 text-xs font-semibold rounded-full 
                            {{ $item->status == 'tersedia' 
                                ? 'bg-green-100 text-green-700' 
                                : 'bg-yellow-100 text-yellow-700' }}">
                            {{ ucfirst($item->status) }}
                        </span>
                    </td>

                    {{-- 🎛️ Tombol Aksi --}}
                    <td class="py-4 px-6 text-center">
                        <div class="flex justify-center gap-3">
                            {{-- Tombol Edit --}}
                            <a href="{{ route('admin.barang.edit', $item) }}" 
                               class="text-white px-4 py-2 hover:scale-105 transition-all duration-300 ease-out">
                               <i class="fa-regular fa-pen-to-square" style="color: black;"></i>
                            </a>

                            {{-- Tombol Hapus --}}
                            <form action="{{ route('admin.barang.destroy', $item) }}" method="POST" class="inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="button" 
                                        class="px-4 py-2 hover:scale-105 transition-all duration-300 ease-out delete-btn">
                                        <i class="fa-solid fa-trash" style="color:black;"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-8 text-center text-gray-500 italic">Belum ada data barang.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- 🔥 Konfirmasi hapus --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('.delete-form');

            Swal.fire({
                title: 'Yakin mau hapus barang ini?',
                text: 'Data yang sudah dihapus tidak bisa dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal',
                showClass: { popup: 'animate__animated animate__fadeInDown' },
                hideClass: { popup: 'animate__animated animate__fadeOutUp' }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});
</script>
@endsection
