@extends('layouts.app')

@section('title', 'Daftar Ruang - Smart Classroom')
@section('page_title', 'Ketersediaan Ruang')
@section('page_subtitle', 'Temukan dan booking ruangan kampus')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&display=swap" rel="stylesheet">

<style>
    .font-sora { font-family: 'Sora', sans-serif; }

    .room-card {
        transition: transform 0.22s ease, box-shadow 0.22s ease;
    }
    .room-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 48px -12px rgba(0,0,0,0.18);
    }
    .room-img {
        transition: transform 0.45s ease;
    }
    .room-card:hover .room-img {
        transform: scale(1.06);
    }

    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(18px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .fade-up { animation: fadeUp 0.4s ease both; }
    .stagger > *:nth-child(1) { animation-delay: 0.05s; }
    .stagger > *:nth-child(2) { animation-delay: 0.10s; }
    .stagger > *:nth-child(3) { animation-delay: 0.15s; }
    .stagger > *:nth-child(4) { animation-delay: 0.20s; }
    .stagger > *:nth-child(5) { animation-delay: 0.25s; }
    .stagger > *:nth-child(6) { animation-delay: 0.30s; }

    .filter-btn { transition: all 0.15s ease; }
    .filter-btn.active {
        background: #0f172a;
        color: white;
        border-color: #0f172a;
    }
</style>

<div class="max-w-7xl mx-auto font-sora space-y-8">

    {{-- ===== HERO HEADER ===== --}}
    <div class="fade-up">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <p class="text-xs font-semibold text-blue-600 uppercase tracking-widest mb-2">
                    ⊞ Koleksi Ruangan
                </p>
                <h1 class="text-3xl md:text-4xl font-bold text-slate-900 leading-tight">
                    Temukan ruang yang sesuai
                </h1>
                <p class="text-slate-500 mt-2 text-sm max-w-lg">
                    Informasi lengkap kapasitas, fasilitas, dan status ketersediaan waktu real-time (data simulasi).
                </p>
            </div>

            {{-- Badge summary --}}
            <div class="flex items-center gap-3 flex-shrink-0">
                <div class="flex items-center gap-2 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-2 rounded-2xl text-sm font-semibold">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Tersedia
                </div>
                <div class="flex items-center gap-2 bg-red-50 border border-red-200 text-red-600 px-4 py-2 rounded-2xl text-sm font-semibold">
                    <span class="w-2 h-2 rounded-full bg-red-500"></span>
                    Dipakai
                </div>
            </div>
        </div>
    </div>

    {{-- ===== FILTER & SEARCH ===== --}}
    <div class="fade-up flex flex-col sm:flex-row gap-3">

        {{-- Search --}}
        <div class="relative flex-1">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm">🔍</span>
            <input type="text" id="searchInput" placeholder="Cari nama ruangan, kode, atau gedung..."
                   class="w-full pl-10 pr-4 py-3 rounded-2xl border border-slate-200 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
        </div>

        {{-- Filter status --}}
        <div class="flex gap-2">
            <button class="filter-btn active px-5 py-3 rounded-2xl border border-slate-200 bg-white text-sm font-semibold"
                    data-filter="semua">Semua</button>
            <button class="filter-btn px-5 py-3 rounded-2xl border border-slate-200 bg-white text-sm font-semibold text-slate-600"
                    data-filter="Tersedia">Tersedia</button>
            <button class="filter-btn px-5 py-3 rounded-2xl border border-slate-200 bg-white text-sm font-semibold text-slate-600"
                    data-filter="Dipakai">Dipakai</button>
        </div>
    </div>

    {{-- ===== GRID RUANGAN ===== --}}
    <div id="roomGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 stagger">

        @foreach($rooms as $i => $room)
        <div class="room-card fade-up bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden"
             data-status="{{ $room['status'] }}"
             data-nama="{{ strtolower($room['nama']) }} {{ strtolower($room['kode']) }} {{ strtolower($room['gedung']) }}">

            {{-- ===== FOTO ===== --}}
            <div class="relative h-48 overflow-hidden bg-slate-200">
                <img src="{{ $room['foto'] }}"
                     alt="{{ $room['nama'] }}"
                     class="room-img w-full h-full object-cover">

                {{-- Overlay gradient --}}
                <div class="absolute inset-0 bg-gradient-to-t from-black/55 via-black/10 to-transparent"></div>

                {{-- Badge Status --}}
                <span class="absolute top-3 right-3 flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold
                    {{ $room['status'] == 'Tersedia'
                        ? 'bg-emerald-400/90 text-emerald-900'
                        : 'bg-red-400/90 text-red-900' }}">
                    <span class="w-1.5 h-1.5 rounded-full
                        {{ $room['status'] == 'Tersedia' ? 'bg-emerald-700' : 'bg-red-700' }}
                        {{ $room['status'] == 'Tersedia' ? 'animate-pulse' : '' }}"></span>
                    {{ $room['status'] }}
                </span>

                {{-- Nomor urut --}}
                <span class="absolute top-3 left-3 w-8 h-8 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center text-white font-bold text-xs">
                    #{{ $i + 1 }}
                </span>

                {{-- Nama ruang di atas foto --}}
                <div class="absolute bottom-3 left-4 right-4">
                    <h3 class="text-white font-bold text-base leading-tight">{{ $room['nama'] }}</h3>
                    <p class="text-white/65 text-xs font-mono mt-0.5">{{ $room['kode'] }} · {{ $room['gedung'] }}, Lt. {{ $room['lantai'] }}</p>
                </div>
            </div>

            {{-- ===== BODY ===== --}}
            <div class="p-5">

                {{-- Kapasitas --}}
                <div class="flex items-center gap-4 mb-4 pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-2 text-sm">
                        <span class="text-base">👥</span>
                        <span class="font-semibold text-slate-700">{{ $room['kapasitas'] }} orang</span>
                    </div>
                    <div class="w-px h-4 bg-slate-200"></div>
                    <div class="flex items-center gap-2 text-sm text-slate-500">
                        <span class="text-base">🕐</span>
                        <span>{{ $room['jam_buka'] }} – {{ $room['jam_tutup'] }}</span>
                    </div>
                </div>

                {{-- Fasilitas --}}
                <div class="mb-4">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Fasilitas</p>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach(array_slice($room['fasilitas'], 0, 4) as $f)
                        <span class="bg-slate-100 text-slate-600 text-xs px-2.5 py-1 rounded-xl font-medium">
                            {{ $f }}
                        </span>
                        @endforeach
                        @if(count($room['fasilitas']) > 4)
                        <span class="bg-blue-50 text-blue-600 text-xs px-2.5 py-1 rounded-xl font-medium">
                            +{{ count($room['fasilitas']) - 4 }} lainnya
                        </span>
                        @endif
                    </div>
                </div>

                {{-- Deskripsi --}}
                <p class="text-xs text-slate-500 leading-relaxed line-clamp-2 mb-4 border-l-2 border-slate-200 pl-3 italic">
                    {{ $room['deskripsi'] }}
                </p>

                {{-- Footer Meta --}}
                <div class="flex items-center gap-3 text-xs text-slate-400 pb-1">
                    <span>🪑 kursi terstandar</span>
                    <span>•</span>
                    <span>📶 WiFi cepat</span>
                    <span>•</span>
                    <span>⚡ listrik tersedia</span>
                </div>
            </div>

            {{-- ===== FOOTER ===== --}}
            <div class="border-t border-slate-100 px-5 py-4 bg-slate-50/60 flex items-center justify-between gap-3">
                <a href="{{ route('rooms.show', $room['id']) }}"
                   class="text-sm font-semibold text-slate-600 hover:text-slate-900 transition flex items-center gap-1">
                    Lihat Detail
                    <span class="text-xs">→</span>
                </a>

                @if($room['status'] == 'Tersedia')
                <a href="{{ route('bookings.create', ['room_id' => $room['id']]) }}"
                   class="bg-slate-900 hover:bg-slate-700 text-white text-xs font-semibold px-4 py-2.5 rounded-xl transition flex items-center gap-1.5">
                    <span>➕</span> Booking
                </a>
                @else
                <span class="text-xs font-semibold text-red-500 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 inline-block"></span>
                    Sedang digunakan
                </span>
                @endif
            </div>

        </div>
        @endforeach

    </div>

    {{-- Empty state --}}
    <div id="emptyState" class="hidden text-center py-20 text-slate-400">
        <p class="text-5xl mb-4">🔍</p>
        <p class="font-semibold text-slate-500">Ruangan tidak ditemukan</p>
        <p class="text-sm mt-1">Coba kata kunci atau filter lain</p>
    </div>

</div>

@endsection

@push('scripts')
<script>
$(document).ready(function () {
    let currentFilter = 'semua';
    let currentSearch = '';

    function filterRooms() {
        let visible = 0;

        $('#roomGrid .room-card').each(function () {
            const status = $(this).data('status');
            const nama   = $(this).data('nama');

            const matchFilter = currentFilter === 'semua' || status === currentFilter;
            const matchSearch = currentSearch === '' || nama.includes(currentSearch.toLowerCase());

            if (matchFilter && matchSearch) {
                $(this).removeClass('hidden');
                visible++;
            } else {
                $(this).addClass('hidden');
            }
        });

        if (visible === 0) {
            $('#emptyState').removeClass('hidden');
        } else {
            $('#emptyState').addClass('hidden');
        }
    }

    // Filter tombol
    $('.filter-btn').on('click', function () {
        $('.filter-btn').removeClass('active');
        $(this).addClass('active');
        currentFilter = $(this).data('filter');
        filterRooms();
    });

    // Search
    $('#searchInput').on('input', function () {
        currentSearch = $(this).val().trim();
        filterRooms();
    });
});
</script>
@endpush