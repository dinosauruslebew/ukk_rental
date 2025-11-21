@extends('layouts.admin')

@section('content')
<!-- FullCalendar CSS -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>

<style>
    /* * KEMBALI KE DESAIN MINIMALIS (BORDERLESS)
     * Persis seperti request kamu sebelumnya
     */

    /* Wrapper */
    .fc {
        font-family: 'Inter', sans-serif;
        color: #1f2937;
    }

    /* HEADER: Title Kiri, Tombol Kanan */
    .fc .fc-toolbar {
        margin-bottom: 1.5rem !important;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .fc .fc-toolbar-title {
        font-size: 1.25rem !important;
        font-weight: 800 !important;
        color: #111827;
    }

    /* Tombol Navigasi (Panah < >) yang bersih */
    .fc .fc-button {
        background: transparent;
        border: 1px solid #f3f4f6;
        color: #374151;
        border-radius: 0.5rem;
        box-shadow: none !important;
        padding: 0.4rem 0.6rem;
    }
    .fc .fc-button:hover {
        background: #f9fafb;
        color: #111827;
    }
    .fc .fc-button:focus {
        box-shadow: none !important;
    }

    /* HILANGKAN BORDER TABLE BIAR BERSIH */
    .fc-theme-standard td,
    .fc-theme-standard th,
    .fc-theme-standard .fc-scrollgrid {
        border: none !important;
    }

    /* Header Hari (M T W T F S S) */
    .fc-col-header-cell-cushion {
        color: #9ca3af; /* abu-abu muda */
        font-weight: 600;
        font-size: 0.8rem;
        text-transform: uppercase;
        padding-bottom: 1rem;
        text-decoration: none !important;
    }

    /* Sel Tanggal */
    .fc-daygrid-day-frame {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 3.5rem; /* Tinggi sel sedikit ditambah biar dot muat */
        cursor: pointer;
    }

    /* Angka Tanggal */
    .fc-daygrid-day-number {
        font-size: 0.95rem;
        font-weight: 500;
        color: #374151;
        width: 2.5rem;
        height: 2.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 9999px; /* Bulat */
        transition: all 0.2s;
        text-decoration: none !important;
        margin: 0 auto;
    }

    /* Hover efek pada angka */
    .fc-daygrid-day:hover .fc-daygrid-day-number {
        background-color: #f3f4f6;
    }

    /* STATE: Tanggal yang Dipilih (Lingkaran Hitam Gelap) */
    .fc-day-selected .fc-daygrid-day-number {
        background-color: #111827 !important; /* Gray-900 */
        color: white !important;
        font-weight: 600;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    /* STATE: Hari Ini */
    .fc-day-today .fc-daygrid-day-number {
        color: #0ea5a3; /* Teal text */
        font-weight: 700;
    }

    /* Sembunyikan event text bawaan */
    .fc-event {
        display: none;
    }

    /* Animasi List */
    .animate-fade-in-up { animation: fadeInUp 0.3s ease-out forwards; }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #e5e7eb; border-radius: 10px; }
</style>

<div class="p-6 md:p-8 min-h-screen bg-[#f7fafc]">

    <!-- Header & Profil -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Dashboard</h1>
            <p class="text-sm text-gray-500 mt-1">Pantau jadwal sewa & pengembalian barang</p>
        </div>

        <div class="flex items-center gap-3 bg-white pl-1.5 pr-4 py-1.5 rounded-full shadow-sm border border-gray-200 hover:shadow-md transition cursor-pointer group">
            <div class="relative">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Admin') }}&background=0f766e&color=fff&size=128" alt="admin" class="w-9 h-9 rounded-full object-cover ring-2 ring-gray-100 group-hover:ring-teal-100 transition">
                <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-500 border-2 border-white rounded-full"></span>
            </div>
            <div class="text-left hidden sm:block">
                <div class="text-[10px] uppercase tracking-wider text-gray-400 font-bold">Admin</div>
                <div class="font-semibold text-gray-700 text-sm leading-tight truncate max-w-[120px]">{{ auth()->user()->name ?? 'Administrator' }}</div>
            </div>
        </div>
    </div>

    <!-- Statistik Cards (Tetap sama) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm hover:shadow-md transition-all duration-200">
            <div class="flex items-center justify-between">
                <div><p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Barang</p><p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalBarang }}</p></div>
                <div class="w-10 h-10 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center"><i class="fa-solid fa-box"></i></div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm hover:shadow-md transition-all duration-200">
            <div class="flex items-center justify-between">
                <div><p class="text-xs font-bold text-gray-400 uppercase tracking-wider">User</p><p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalUsers }}</p></div>
                <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center"><i class="fa-solid fa-users"></i></div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm hover:shadow-md transition-all duration-200">
            <div class="flex items-center justify-between">
                <div><p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Rental Aktif</p><p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalRentalAktif }}</p></div>
                <div class="w-10 h-10 rounded-lg bg-orange-50 text-orange-500 flex items-center justify-center"><i class="fa-solid fa-clock"></i></div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm hover:shadow-md transition-all duration-200">
            <div class="flex items-center justify-between">
                <div><p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Stok Habis</p><p class="text-2xl font-bold text-gray-900 mt-1">{{ $barangTidakTersedia }}</p></div>
                <div class="w-10 h-10 rounded-lg bg-red-50 text-red-500 flex items-center justify-center"><i class="fa-solid fa-triangle-exclamation"></i></div>
            </div>
        </div>
    </div>

    <!-- Main Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- KOLOM KIRI: Kalender & Detail (PENGINGAT UTAMA) -->
        <div class="lg:col-span-1 space-y-6">

            <!-- KALENDER CLEAN (Desain Minimalis) -->
            <div class="bg-white rounded-2xl shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07)] border border-gray-100 p-6">
                <div class="flex gap-3 mb-4 justify-center text-[10px] text-gray-500 font-medium">
                    <div class="flex items-center gap-1"><div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div> Mulai Sewa</div>
                    <div class="flex items-center gap-1"><div class="w-1.5 h-1.5 rounded-full bg-orange-500"></div> Batas Kembali</div>
                </div>
                <div id="calendar"></div>
            </div>

            <!-- DETAIL HARIAN (List di bawah kalender) -->
            <div id="dayDetails" class="hidden bg-white rounded-2xl shadow-sm border border-gray-100 p-5 animate-fade-in-up">
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-50">
                    <div>
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Jadwal Tanggal</p>
                        <h3 class="text-lg font-bold text-gray-800 mt-0.5" id="selectedDateLabel">-</h3>
                    </div>
                    <div class="w-8 h-8 rounded-full bg-teal-50 flex items-center justify-center text-teal-600">
                        <i class="fa-regular fa-calendar-check"></i>
                    </div>
                </div>

                <!-- List Item -->
                <div id="dayEventsList" class="space-y-3 max-h-80 overflow-y-auto pr-1 custom-scrollbar">
                    <!-- Item akan di-inject via JS -->
                </div>
            </div>

        </div>

        <!-- KOLOM KANAN: Grafik (Pendukung) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Grafik -->
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Statistik Pendapatan</h3>
                <div class="relative h-64 w-full">

                    <!--
                        ========================================
                        PERBAIKAN ERROR CHART DI SINI!
                        ========================================
                    -->
                    @if(!empty($chartData['values']) && $chartData['values']->sum() > 0)
                        <canvas id="revenueChart"></canvas>
                    @else
                        <div class="flex flex-col items-center justify-center h-full text-gray-400 border-2 border-dashed border-gray-100 rounded-xl">
                            <i class="fa-solid fa-chart-line text-3xl mb-2 opacity-30"></i>
                            <p class="text-sm">Belum ada data transaksi</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Tabel Transaksi (UPDATE LOGIC) -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-900">Pesanan Terbaru</h3>
                    <!-- Pastikan route ini ada: admin.order.index -->
                    <a href="{{ route('admin.order.index') }}" class="text-xs font-medium text-teal-600 hover:text-teal-700 hover:underline">
                        Lihat Semua
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-50">
                            <tr class="text-xs uppercase text-gray-500 font-semibold">
                                <th class="py-3 px-6">Order ID</th>
                                <th class="py-3 px-6">Penyewa</th>
                                <th class="py-3 px-6">Tanggal</th>
                                <th class="py-3 px-6">Status</th>
                                <th class="py-3 px-6 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-gray-100">
                            <!--
                                ========================================
                                PERBAIKAN LOOPING: DARI 'rentals' ke 'orders'
                                ========================================
                            -->
                            @forelse($recentOrders as $order)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="py-4 px-6 font-medium text-gray-900">
                                        <!-- Pastikan route ini ada: admin.order.show -->
                                        <a href="{{ route('admin.order.show', $order->id) }}" class="hover:underline hover:text-teal-600">
                                            #JENGKI-{{ $order->id }}
                                        </a>
                                    </td>
                                    <td class="py-4 px-6 text-gray-600">{{ $order->user->name ?? 'Guest' }}</td>
                                    <td class="py-4 px-6 text-gray-500 text-xs">{{ $order->created_at->format('d M Y') }}</td>
                                    <td class="py-4 px-6">
                                        <!-- Badge Status (dari order) -->
                                        <span class="px-3 py-1.5 rounded-full text-[10px] font-bold capitalize tracking-wide whitespace-nowrap border
                                        {{ $order->status == 'selesai' ? 'bg-emerald-100 text-emerald-700 border-emerald-200' : 'bg-amber-100 text-amber-700 border-amber-200' }}">
                                            {{ $order->status }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 text-right font-bold text-gray-800">
                                        Rp{{ number_format($order->total_harga_pesanan ?? 0, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-10 text-center text-gray-400 italic">
                                        <div class="flex flex-col items-center">
                                            <i class="fa-regular fa-folder-open text-2xl mb-2 opacity-50"></i>
                                            <span class="text-xs">Belum ada pesanan terbaru.</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {

    // --- DATA DARI CONTROLLER (Sewa & Kembali sudah dipisah) ---
    const eventsData = @json($calendarEvents ?? []);

    const calendarEl = document.getElementById('calendar');
    const dayDetails = document.getElementById('dayDetails');
    const selectedDateLabel = document.getElementById('selectedDateLabel');
    const dayEventsList = document.getElementById('dayEventsList');

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'title',
            center: '',
            right: 'prev,next'
        },
        titleFormat: { year: 'numeric', month: 'long' },
        height: 'auto',
        contentHeight: 'auto',
        aspectRatio: 1.1, // Biar agak kotak
        events: eventsData,

        // --- LOGIKA TAMPILAN DOT (Hijau & Orange) ---
        dayCellContent: function(arg) {
            const dateStr = arg.date.toISOString().split('T')[0];
            const dayEvents = eventsData.filter(e => e.start.startsWith(dateStr));

            let html = `<div class="flex flex-col items-center justify-center h-full py-1">
                            <span class="fc-daygrid-day-number">${arg.dayNumberText}</span>`;

            if (dayEvents.length > 0) {
                html += `<div class="flex gap-1 mt-1">`;

                // Cek tipe event (Sewa atau Kembali?)
                const hasSewa = dayEvents.some(e => e.extendedProps.color === 'emerald');
                const hasKembali = dayEvents.some(e => e.extendedProps.color === 'orange');

                // Dot Hijau (Ada yang mulai sewa)
                if (hasSewa) html += `<div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div>`;
                // Dot Orange (Ada yang harus kembali)
                if (hasKembali) html += `<div class="w-1.5 h-1.5 rounded-full bg-orange-500"></div>`;

                html += `</div>`;
            }

            html += `</div>`;
            return { html: html };
        },

        // --- LOGIKA KLIK TANGGAL (DETAIL PENGINGAT) ---
        dateClick: function(info) {
            // Highlight Seleksi
            document.querySelectorAll('.fc-day-selected').forEach(el => el.classList.remove('fc-day-selected'));
            info.dayEl.classList.add('fc-day-selected');

            const dateStr = info.dateStr;
            const selectedEvents = eventsData.filter(e => e.start.startsWith(dateStr));

            // Format Tanggal
            const dateObj = new Date(info.date);
            selectedDateLabel.innerText = dateObj.toLocaleDateString('id-ID', {
                weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
            });

            dayDetails.classList.remove('hidden');

            if (selectedEvents.length > 0) {
                dayEventsList.innerHTML = selectedEvents.map(e => {
                    const props = e.extendedProps;

                    // Tentukan warna badge & icon berdasarkan tipe

        // --- PERBAIKAN LOGIKA PENENTUAN TIPE ACARA ---
        // Kita gunakan props.color yang dikirim dari controller (emerald/orange)
        const isSewa = props.color === 'emerald';
        
        // Penentuan warna badge
        const badgeColor = isSewa 
            ? 'bg-emerald-100 text-emerald-700 border-emerald-200' 
            : 'bg-orange-100 text-orange-700 border-orange-200';
            
        // Penentuan Label
        const label = isSewa ? 'Mulai Sewa' : 'Batas Kembali';

        // Penentuan URL Detail (Diasumsikan ID Order ada di e.extendedProps.order_id atau e.id)
        // Saya asumsikan ID order ada di e.extendedProps.order_id
        const detailUrl = `/admin/orders/${props.order_id ?? e.id}`; 
        
        // Catatan: Jika Anda menggunakan /admin/rental/xxx/show, pastikan URL-nya sudah benar di route Anda.
        
        // --- HTML Output ---
        return `
            <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-xl border border-gray-100 hover:border-gray-300 transition group">
                <div class="mt-1 w-2 h-2 rounded-full ${isSewa ? 'bg-emerald-500' : 'bg-orange-500'} flex-shrink-0"></div>
                <div class="flex-1 min-w-0">
                    <div class="flex justify-between items-start">
                        <p class="text-sm font-bold text-gray-800 truncate">${props.user_name}</p>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded border ${badgeColor}">
                            ${label}
                        </span>
                    </div>
                    <p class="text-xs text-gray-500 truncate mt-0.5"><i class="fa-solid fa-tent mr-1"></i> ${props.barang}</p>

                    <div class="flex items-center justify-end mt-2">
                        <a href="${detailUrl}" class="text-xs text-gray-500 hover:text-teal-600 font-medium transition">Lihat Detail &rarr;</a>
                    </div>
                </div>
            </div>
        `;
    }).join('');
            } else {
                dayEventsList.innerHTML = `
                    <div class="text-center py-8">
                        <div class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gray-50 mb-2">
                            <i class="fa-regular fa-calendar text-gray-300"></i>
                        </div>
                        <p class="text-gray-400 text-xs italic">Tidak ada jadwal di tanggal ini.</p>
                    </div>
                `;
            }
        }
    });

    calendar.render();

    // --- CHART JS (Tetap) ---
    const chartValues = @json($chartData['values'] ?? []);
    const chartLabels = @json($chartData['labels'] ?? []);
    if(chartValues.length > 0 && chartValues.some(val => val > 0)) {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 250);
        gradient.addColorStop(0, 'rgba(16, 185, 129, 0.2)');
        gradient.addColorStop(1, 'rgba(255, 255, 255, 0)');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [{ label: 'Pendapatan', data: chartValues, borderColor: '#10b981', backgroundColor: gradient, borderWidth: 2, tension: 0.4, pointRadius: 0, pointHoverRadius: 4 }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, grid: { borderDash: [4, 4], color: '#f3f4f6' }, ticks: { font: {size: 10}, color: '#9ca3af' } }, x: { grid: { display: false }, ticks: { font: {size: 10}, color: '#9ca3af' } } } }
        });
    }
});
</script>
@endsection
