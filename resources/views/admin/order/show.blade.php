@extends('layouts.admin')

@section('content')
<div class="p-8 min-h-screen bg-gray-50">

    {{--
        ============================================
        FITUR BARU: LOGIKA DETEKSI KETERLAMBATAN
        ============================================
        Hanya berjalan jika status 'disewa'.
        Mengecek apakah hari ini > tanggal kembali item manapun.
    --}}
    @php
        $isLate = false;
        $lateDays = 0;
        $estimasiDenda = 0;

        if ($order->status == 'disewa') {
            foreach($order->items as $item) {
                // Cek jika hari ini > tanggal kembali
                if (\Carbon\Carbon::now()->startOfDay()->gt($item->tanggal_kembali)) {
                    $isLate = true;

                    // Hitung selisih hari
                    $hari = \Carbon\Carbon::now()->diffInDays($item->tanggal_kembali);
                    $lateDays = max($lateDays, $hari);

                    // Hitung Estimasi Denda (Harian x Kuantitas x Hari Telat)
                    // Rumus: (Harga Paket / Durasi) = Harga Harian
                    $hargaPerHari = $item->harga_paket_saat_checkout / $item->durasi;
                    $estimasiDenda += ($hargaPerHari * $item->kuantitas * $hari);
                }
            }
        }
    @endphp

    {{-- TAMPILKAN ALERT MERAH JIKA TERLAMBAT --}}
    @if($isLate)
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl flex items-start gap-3 shadow-sm animate-pulse">
            <div class="p-2 bg-red-100 rounded-full text-red-600">
                <i class="fa-solid fa-triangle-exclamation text-xl"></i>
            </div>
            <div>
                <h3 class="font-bold text-red-800 text-lg">PERINGATAN: Pesanan Ini Terlambat {{ $lateDays }} Hari!</h3>
                <p class="text-sm text-red-600 mt-1">
                    Barang belum dikembalikan. Estimasi denda sementara:
                    <span class="font-extrabold bg-red-100 px-2 rounded">Rp{{ number_format($estimasiDenda, 0, ',', '.') }}</span>
                </p>
                <p class="text-xs text-red-500 mt-1 italic">*Klik "Proses Pengembalian" di bawah untuk menghitung denda final & menyelesaikan pesanan.</p>
            </div>
        </div>
    @endif

    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <a href="{{ route('admin.order.index') }}" class="text-sm text-teal-600 hover:underline flex items-center gap-1">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Pesanan
            </a>
            <div class="flex items-center gap-3 mt-2">
                <h1 class="text-3xl font-bold text-gray-800">Detail Pesanan #JENGKI-{{ $order->id }}</h1>
                <!-- Badge Metode Pembayaran -->
                <span class="px-3 py-1 rounded-md text-xs font-bold uppercase border
                    {{ $order->metode_pembayaran == 'cod' ? 'bg-gray-800 text-white border-gray-800' : 'bg-blue-100 text-blue-700 border-blue-200' }}">
                    {{ $order->metode_pembayaran }}
                </span>
            </div>
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

    <!-- Pesan Sukses/Error -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-100 border border-emerald-200 text-emerald-700 rounded-lg shadow-sm flex items-center gap-2">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 p-4 bg-red-100 border border-red-200 text-red-700 rounded-lg shadow-sm flex items-center gap-2">
            <i class="fa-solid fa-circle-xmark"></i> {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- KIRI: Detail Barang & Perhitungan -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-5 border-b border-gray-100"><h3 class="font-bold text-gray-800">Barang Pesanan</h3></div>
                <div class="divide-y divide-gray-100">
                    @foreach($order->items as $item)
                        <div class="p-5 flex items-start gap-4">
                            @if($item->barang)
                                <img src="{{ asset('storage/' . $item->barang->gambar) }}" alt="{{ $item->nama_barang_saat_checkout }}" class="w-20 h-20 object-cover rounded-xl bg-gray-100">
                            @else
                                <div class="w-20 h-20 rounded-xl bg-gray-100 flex items-center justify-center flex-shrink-0 text-gray-400"><i class="fa-solid fa-ban"></i></div>
                            @endif

                            <div class="flex-1">
                                <h4 class="font-bold text-gray-900">{{ $item->nama_barang_saat_checkout }}</h4>
                                <p class="text-sm text-gray-500 mt-1">
                                    <span class="font-medium text-gray-700">{{ $item->kuantitas }} unit</span> x
                                    <span class="font-medium text-gray-700">{{ $item->durasi }} Malam</span>
                                </p>
                                <p class="text-xs text-gray-500">
                                    Jadwal: {{ $item->tanggal_sewa->format('d M Y') }} - {{ $item->tanggal_kembali->format('d M Y') }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-bold text-emerald-600">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</p>
                                <p class="text-xs text-gray-400">(@ Rp{{ number_format($item->harga_paket_saat_checkout, 0) }})</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- RINGKASAN BIAYA (Footer Card) -->
                <div class="p-5 bg-gray-50 border-t border-gray-100 space-y-2">
                    <div class="flex justify-between text-gray-600">
                        <span class="font-medium">Subtotal Pesanan:</span>
                        <span class="font-bold text-gray-900">Rp{{ number_format($order->total_harga_pesanan, 0, ',', '.') }}</span>
                    </div>

                    <!-- TAMPILKAN DENDA JIKA ADA (Hanya muncul jika denda > 0) -->
                    @if($order->total_denda > 0)
                        <div class="flex justify-between text-red-600 bg-red-50 p-3 rounded-lg border border-red-100 mt-2">
                            <span class="flex items-center gap-2 font-bold">
                                <i class="fa-solid fa-triangle-exclamation"></i> Denda Keterlambatan ({{ $order->hari_terlambat }} hari):
                            </span>
                            <span class="font-extrabold text-lg">+ Rp{{ number_format($order->total_denda, 0, ',', '.') }}</span>
                        </div>

                        <div class="flex justify-between items-center pt-4 border-t border-gray-200 mt-2">
                            <span class="text-gray-800 font-extrabold text-lg uppercase tracking-wider">TOTAL DIBAYAR:</span>
                            <span class="text-3xl font-black text-teal-700">Rp{{ number_format($order->total_akhir, 0, ',', '.') }}</span>
                        </div>
                    @else
                         <!-- Total Biasa Jika Tidak Ada Denda -->
                         <div class="flex justify-between items-center pt-4 border-t border-gray-200 mt-2">
                            <span class="text-gray-800 font-extrabold text-lg uppercase tracking-wider">TOTAL DIBAYAR:</span>
                            <span class="text-3xl font-black text-teal-700">Rp{{ number_format($order->total_harga_pesanan, 0, ',', '.') }}</span>
                        </div>
                    @endif

                    @if ($order->total_denda > 0)
                    <div class="flex justify-between mt-3 text-red-700">
                        <span class="font-semibold">Denda Keterlambatan:</span>
                        <span>Rp{{ number_format($order->total_denda, 0, ',', '.') }}</span>
                    </div>

                    <div class="flex justify-between mt-3 text-green-700 text-lg font-bold">
                        <span>Total Akhir:</span>
                        <span>Rp{{ number_format($order->total_akhir, 0, ',', '.') }}</span>
                    </div>
                    @endif

                </div>
            </div>

            <!-- Bukti Pembayaran (Hanya jika Transfer) -->
            @if($order->metode_pembayaran == 'transfer' && $order->bukti_pembayaran)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-5 border-b border-gray-100"><h3 class="font-bold text-gray-800">Bukti Pembayaran</h3></div>
                    <div class="p-5 text-center bg-gray-50">
                        <img src="{{ asset('storage/' . $order->bukti_pembayaran) }}" alt="Bukti Transfer" class="max-h-96 mx-auto rounded-lg shadow-sm border border-gray-200 cursor-pointer hover:opacity-90 transition" onclick="window.open(this.src)">
                    </div>
                </div>
            @endif

            @if ($order->hari_terlambat > 0)
            <div class="p-4 mb-4 bg-red-50 border border-red-300 rounded-lg">
                <h4 class="font-bold text-red-700 mb-2">⚠️ Keterlambatan Pengembalian</h4>

                <p><strong>Hari Terlambat:</strong> {{ $order->hari_terlambat }} Hari</p>
                <p><strong>Total Denda:</strong> Rp{{ number_format($order->total_denda, 0, ',', '.') }}</p>

                <p class="mt-2 text-sm text-red-600">
                    {{ $order->catatan_admin }}
                </p>
            </div>
            @endif

        </div>

        <!-- KANAN: Aksi Admin -->
        <div class="lg:col-span-1 space-y-6">

            <!-- Card Pelanggan -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <h3 class="font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100">Pelanggan</h3>
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-teal-100 text-teal-600 flex items-center justify-center font-bold text-xl">
                            {{ substr($order->user->name ?? 'U', 0, 1) }}
                        </div>
                        <div>
                            <p class="font-bold text-gray-900 text-lg">{{ $order->user->name ?? 'User Dihapus' }}</p>
                            <p class="text-xs text-gray-500">{{ $order->user->email ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="pt-3 border-t border-gray-50">
                        <p class="text-xs text-gray-400 font-bold uppercase mb-1">WhatsApp</p>
                        <p class="text-sm font-medium text-gray-800">{{ $order->user->no_hp ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-bold uppercase mb-1">Alamat</p>
                        <p class="text-sm text-gray-600">{{ $order->user->alamat ?? '-' }}</p>
                    </div>

                    @if($order->user && $order->user->no_hp)
                        <a href="https://wa.me/{{ $order->user->no_hp }}" target="_blank" class="mt-4 w-full flex items-center justify-center gap-2 bg-green-500 text-white py-2.5 rounded-lg font-bold hover:bg-green-600 transition shadow-sm">
                            <i class="fa-brands fa-whatsapp text-lg"></i> Hubungi Penyewa
                        </a>
                    @endif
                </div>
            </div>

            <!-- Card Aksi -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <h3 class="font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100">Aksi Admin</h3>
                <div class="space-y-3">

                    {{-- 1. Konfirmasi Pesanan --}}
                    @if($order->status == 'menunggu konfirmasi' || ($order->status == 'menunggu pembayaran' && $order->metode_pembayaran == 'cod'))
                        <form action="{{ route('admin.order.updateStatus', $order->id) }}" method="POST">
                            @csrf @method('PATCH') <input type="hidden" name="status" value="dikonfirmasi">
                            <button class="w-full bg-emerald-600 text-white py-3 rounded-lg font-bold hover:bg-emerald-700 transition shadow-md flex items-center justify-center gap-2">
                                <i class="fa-solid fa-check-circle"></i> Konfirmasi Pesanan
                            </button>
                        </form>
                    @endif

                    {{-- 2. Barang Diambil (Mulai Sewa) --}}
                    @if($order->status == 'dikonfirmasi')
                        <form action="{{ route('admin.order.updateStatus', $order->id) }}" method="POST">
                            @csrf @method('PATCH') <input type="hidden" name="status" value="disewa">
                            <button class="w-full bg-blue-600 text-white py-3 rounded-lg font-bold hover:bg-blue-700 transition shadow-md flex items-center justify-center gap-2">
                                <i class="fa-solid fa-truck-fast"></i> Barang Diambil
                            </button>
                        </form>
                    @endif

                    {{-- 3. Proses Pengembalian (Hitung Denda) --}}
                    @if($order->status == 'disewa')
                        <button onclick="document.getElementById('returnModal').classList.remove('hidden')" class="w-full bg-gray-800 text-white py-3 rounded-lg font-bold hover:bg-gray-900 transition shadow-md flex items-center justify-center gap-2">
                            <i class="fa-solid fa-box-open"></i> Proses Pengembalian
                        </button>
                    @endif

                    {{-- 4. Batalkan Pesanan --}}
                    @if(!in_array($order->status, ['selesai', 'dibatalkan']))
                        <form action="{{ route('admin.order.updateStatus', $order->id) }}" method="POST" onsubmit="return confirm('PERINGATAN! Stok barang akan dikembalikan. Yakin ingin membatalkan pesanan ini?');">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="dibatalkan">
                            <button class="w-full bg-white text-red-600 border-2 border-red-100 py-2.5 rounded-lg font-bold hover:bg-red-50 transition flex items-center justify-center gap-2 mt-2">
                                <i class="fa-solid fa-ban"></i> Batalkan Pesanan
                            </button>
                        </form>
                    @endif

                    @if($order->status == 'selesai')
                        <div class="p-4 bg-green-50 text-green-800 rounded-lg text-center font-bold border border-green-100 flex flex-col items-center justify-center gap-1">
                            <i class="fa-solid fa-check-double text-2xl"></i>
                            <span>Pesanan Selesai</span>
                        </div>
                    @endif

                    @if($order->status == 'dibatalkan')
                        <div class="p-4 bg-red-50 text-red-800 rounded-lg text-center font-bold border border-red-100 flex flex-col items-center justify-center gap-1">
                            <i class="fa-solid fa-circle-xmark text-2xl"></i>
                            <span>Pesanan Dibatalkan</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL INPUT TANGGAL KEMBALI (Untuk Hitung Denda) -->
<div id="returnModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4 backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 relative transform transition-all scale-100">
        <button onclick="document.getElementById('returnModal').classList.add('hidden')" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>

        <div class="text-center mb-6">
            <div class="w-14 h-14 bg-teal-100 text-teal-600 rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm">
                <i class="fa-solid fa-calendar-check text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800">Proses Pengembalian</h3>
            <p class="text-sm text-gray-500 mt-1">Masukkan tanggal pengembalian aktual. <br>Sistem akan otomatis menghitung denda jika ada keterlambatan.</p>
        </div>

        <form action="{{ route('admin.order.processReturn', $order->id) }}" method="POST">
            @csrf @method('PATCH')

            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal Aktual Kembali</label>
                <input type="date" name="tanggal_kembali_aktual" required
                       value="{{ date('Y-m-d') }}"
                       class="w-full border-gray-300 rounded-xl focus:ring-teal-500 focus:border-teal-500 py-3 px-4 shadow-sm text-gray-700 font-medium">
                <p class="text-xs text-gray-400 mt-2 flex items-center gap-1">
                    <i class="fa-solid fa-circle-info"></i> Stok barang akan otomatis bertambah kembali.
                </p>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="document.getElementById('returnModal').classList.add('hidden')" class="flex-1 px-4 py-3 bg-gray-100 text-gray-700 rounded-xl font-bold hover:bg-gray-200 transition">
                    Batal
                </button>
                <button type="submit" class="flex-1 px-4 py-3 bg-teal-600 text-white rounded-xl font-bold hover:bg-teal-700 transition shadow-md flex items-center justify-center gap-2">
                    <i class="fa-solid fa-save"></i> Simpan & Selesai
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
