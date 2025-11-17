@extends('layouts.admin')

@section('content')
<div class="p-8 min-h-screen bg-gray-50">

    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <a href="{{ route('admin.order.index') }}" class="text-sm text-teal-600 hover:underline flex items-center gap-1">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Pesanan
            </a>
            <h1 class="text-3xl font-bold text-gray-800 mt-2">Detail Pesanan #JENGKI-{{ $order->id }}</h1>
        </div>

        <!-- Badge Status -->
        <span class="px-4 py-2 rounded-full text-sm font-bold capitalize
            {{ $order->status == 'menunggu pembayaran' ? 'bg-orange-100 text-orange-700' : '' }}
            {{ $order->status == 'menunggu konfirmasi' ? 'bg-blue-100 text-blue-700' : '' }}
            {{ $order->status == 'dikonfirmasi' || $order->status == 'disewa' ? 'bg-emerald-100 text-emerald-700' : '' }}
            {{ $order->status == 'selesai' ? 'bg-gray-100 text-gray-600' : '' }}
            {{ $order->status == 'dibatalkan' ? 'bg-red-100 text-red-700' : '' }}
        ">
            {{ $order->status }}
        </span>
    </div>

    <!-- Alert Sukses -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-100 border border-emerald-200 text-emerald-700 rounded-lg shadow-sm flex items-center gap-2">
            <i class="fa-solid fa-circle-check"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Layout 2 Kolom -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Kolom Kiri (Detail Pesanan & Bukti) -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Card 1: Detail Barang Pesanan (Items) -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <h3 class="p-5 text-lg font-bold text-gray-800 border-b border-gray-100">Barang Pesanan</h3>
                <div class="divide-y divide-gray-100">
                    @foreach($order->items as $item)
                        <div class="p-5 flex items-start gap-4">
                            @if($item->barang)
                                <img src="{{ asset('storage/' . $item->barang->gambar) }}" alt="{{ $item->nama_barang_saat_checkout }}" class="w-20 h-20 object-cover rounded-xl bg-gray-100">
                            @else
                                <div class="w-20 h-20 rounded-xl bg-gray-100 flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-store-slash text-2xl text-gray-400"></i></div>
                            @endif

                            <div class="flex-1">
                                <h4 class="font-bold text-gray-900">{{ $item->nama_barang_saat_checkout }}</h4>
                                <p class="text-sm text-gray-500 mt-1">
                                    <span class="font-medium text-gray-700">{{ $item->kuantitas }} unit</span> x
                                    <span class="font-medium text-gray-700">{{ $item->durasi }} Malam</span>
                                </p>
                                <p class="text-xs text-gray-500">
                                    Tgl: <span class="font-medium text-gray-700">{{ $item->tanggal_sewa->format('d M Y') }} - {{ $item->tanggal_kembali->format('d M Y') }}</span>
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-bold text-emerald-600">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</p>
                                <p class="text-xs text-gray-400">(@ Rp{{ number_format($item->harga_paket_saat_checkout, 0) }})</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <!-- Total di Footer Card -->
                <div class="p-5 bg-gray-50 border-t border-gray-100 flex justify-end items-center">
                    <span class="text-gray-500 font-medium">Total Pesanan:</span>
                    <span class="text-2xl font-bold text-gray-900 ml-3">Rp{{ number_format($order->total_harga_pesanan, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Card 2: Bukti Pembayaran -->
            @if($order->bukti_pembayaran || $order->status == 'menunggu pembayaran')
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <h3 class="p-5 text-lg font-bold text-gray-800 border-b border-gray-100">Bukti Pembayaran</h3>
                <div class="p-5">
                    @if($order->bukti_pembayaran)
                        <img src="{{ asset('storage/' . $order->bukti_pembayaran) }}" alt="Bukti Pembayaran" class="w-full max-w-md rounded-lg border border-gray-200">
                    @else
                        <p class="text-sm text-gray-500 italic text-center py-4">User belum meng-upload bukti pembayaran.</p>
                    @endif
                </div>
            </div>
            @endif

        </div>

        <!-- Kolom Kanan (Info & Aksi) -->
        <div class="lg:col-span-1 space-y-6">

            <!-- Card 3: Detail Pelanggan -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <h3 class="p-5 text-lg font-bold text-gray-800 border-b border-gray-100">Pelanggan</h3>
                <div class="p-5 space-y-3">
                    <div class="flex items-center gap-3">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($order->user->name ?? 'U') }}&background=0f766e&color=fff" alt="avatar" class="w-10 h-10 rounded-full">
                        <div>
                            <p class="font-semibold text-gray-900">{{ $order->user->name ?? 'User Dihapus' }}</p>
                            <p class="text-xs text-gray-500">{{ $order->user->email ?? '-' }}</p>
                        </div>
                    </div>
                    <div>
                        <a href="https://wa.me/{{ $order->user->no_hp ?? '' }}?text=Halo%20{{ $order->user->name }},%20kami%20dari%20Jengki%20Adventure%20mau%20konfirmasi%20pesanan%20Anda%20%23JENGKI-{{ $order->id }}" target="_blank" class="w-full inline-flex items-center justify-center gap-2 bg-green-500 text-white px-3 py-2 rounded-lg font-semibold hover:bg-green-600 text-sm">
                            <i class="fa-brands fa-whatsapp"></i> Chat via WhatsApp
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card 4: Aksi Admin -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <h3 class="p-5 text-lg font-bold text-gray-800 border-b border-gray-100">Aksi Admin</h3>
                <div class="p-5 space-y-3">

                    @if($order->status == 'menunggu konfirmasi')
                    <!-- Form Konfirmasi Pesanan -->
                    <form action="{{ route('admin.order.updateStatus', $order->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="dikonfirmasi">
                        <button type="submit" class="w-full bg-emerald-600 text-white py-3 rounded-lg font-bold hover:bg-emerald-700 transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-check"></i> Konfirmasi Pesanan
                        </button>
                    </form>

                    @elseif($order->status == 'dikonfirmasi')
                    <!-- Form Tandai Disewa -->
                    <form action="{{ route('admin.order.updateStatus', $order->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="disewa">
                        <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg font-bold hover:bg-blue-700 transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-truck-fast"></i> Tandai Disewa
                        </button>
                    </form>

                    @elseif($order->status == 'disewa')
                    <!-- Form Tandai Selesai -->
                    <form action="{{ route('admin.order.updateStatus', $order->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="selesai">
                        <button type="submit" class="w-full bg-gray-800 text-white py-3 rounded-lg font-bold hover:bg-gray-900 transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-flag-checkered"></i> Tandai Selesai
                        </button>
                    </form>
                    @endif

                    <!-- Form Batalkan Pesanan (Tampil jika belum Selesai/Batal) -->
                    @if(!in_array($order->status, ['selesai', 'dibatalkan']))
                    <form action="{{ route('admin.order.updateStatus', $order->id) }}" method="POST" onsubmit="return confirm('PERINGATAN! Stok barang akan dikembalikan. Yakin ingin membatalkan pesanan ini?');">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="dibatalkan">
                        <button type="submit" class="w-full bg-red-100 text-red-700 py-3 rounded-lg font-bold hover:bg-red-200 transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-times"></i> Batalkan Pesanan
                        </button>
                    </form>
                    @endif

                    @if($order->status == 'selesai' || $order->status == 'dibatalkan')
                        <p class="text-sm text-gray-500 italic text-center">Tidak ada aksi lebih lanjut untuk pesanan ini.</p>
                    @endif

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
