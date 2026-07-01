@extends('layouts.app')

@section('title', 'Daftar Ruang - Smart Classroom')
@section('page_title', 'Daftar Ruangan')
@section('page_subtitle', 'Temukan dan booking ruangan kampus')

@section('content')

<div class="max-w-7xl mx-auto font-sora space-y-8">

    {{-- ============================================================ --}}
    {{-- HERO HEADER --}}
    {{-- ============================================================ --}}
    <div class="fade-up">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 uppercase tracking-widest bg-indigo-50 dark:bg-indigo-900/30 px-3 py-1 rounded-full">
                        <i class="fas fa-building mr-1"></i> Koleksi Ruangan
                    </span>
                </div>
                <h1 class="text-3xl md:text-4xl font-bold text-slate-900 dark:text-white leading-tight">
                    Temukan Ruang yang Sesuai
                </h1>
                <p class="text-slate-500 dark:text-slate-400 mt-2 text-sm max-w-lg">
                    Informasi lengkap kapasitas, fasilitas, dan status ketersediaan ruangan kampus
                </p>
            </div>

            {{-- Badge summary --}}
            <div class="flex items-center gap-3 shrink-0 flex-wrap">
                <div class="flex items-center gap-2 bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 px-4 py-2 rounded-2xl text-sm font-semibold">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 {{ $totalTersedia > 0 ? 'animate-pulse' : '' }}"></span>
                    <i class="fas fa-check-circle text-emerald-500 mr-0.5"></i>
                    Tersedia ({{ $totalTersedia ?? 0 }})
                </div>
                <div class="flex items-center gap-2 bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-800 text-red-600 dark:text-red-300 px-4 py-2 rounded-2xl text-sm font-semibold">
                    <span class="w-2 h-2 rounded-full bg-red-500"></span>
                    <i class="fas fa-times-circle text-red-500 mr-0.5"></i>
                    Dipakai / Maintenance
                </div>
                <div class="flex items-center gap-2 bg-blue-50 dark:bg-blue-950/30 border border-blue-200 dark:border-blue-800 text-blue-600 dark:text-blue-300 px-4 py-2 rounded-2xl text-sm font-semibold">
                    <i class="fas fa-door-open mr-0.5"></i>
                    Total {{ $totalRooms ?? 0 }} Ruang
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- FILTER & SEARCH --}}
    {{-- ============================================================ --}}
    <div class="fade-up bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-5">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            {{-- Search --}}
            <div class="relative md:col-span-2">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-search text-slate-400 dark:text-slate-500"></i>
                </div>
                <input
                    type="text"
                    id="searchInput"
                    placeholder="Cari nama ruangan, kode, atau gedung..."
                    class="w-full pl-11 pr-4 py-3.5 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-2xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none text-sm placeholder:text-slate-400 dark:text-white transition">
            </div>

            {{-- Filter status --}}
            <div>
                <label class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Status</label>
                <select id="filterStatus" class="w-full mt-1 rounded-xl border border-slate-200 dark:border-slate-600 px-4 py-2.5 text-sm bg-white dark:bg-slate-700 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition">
                    <option value="semua">Semua Status</option>
                    <option value="Tersedia">Tersedia</option>
                    <option value="Maintenance">Maintenance</option>
                </select>
            </div>

            {{-- Filter kapasitas --}}
            <div>
                <label class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Kapasitas Minimal</label>
                <input type="number"
                    id="minCapacity"
                    placeholder="Minimal orang"
                    min="0"
                    class="w-full mt-1 rounded-xl border border-slate-200 dark:border-slate-600 px-4 py-2.5 text-sm bg-white dark:bg-slate-700 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition">
            </div>
        </div>

        {{-- Reset Filter --}}
        <div class="mt-3 flex justify-end">
            <button id="resetFiltersBtn"
                    class="px-4 py-2 text-sm bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-600 dark:text-slate-300 rounded-xl font-semibold transition">
                <i class="fas fa-undo mr-1"></i> Reset Filter
            </button>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- GRID RUANGAN --}}
    {{-- ============================================================ --}}
    <div id="roomGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 stagger">

        @forelse(($rooms ?? []) as $i => $room)
        <div class="room-card fade-up bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden flex flex-col h-full transition-all duration-300 hover:shadow-lg hover:-translate-y-1"
             data-status="{{ $room['status'] ?? '' }}"
             data-nama="{{ strtolower($room['search_keywords'] ?? $room['nama'] ?? '') }}"
             data-kapasitas="{{ $room['kapasitas'] ?? 0 }}"
             data-faculty="{{ $room['faculty_id'] ?? '' }}">

            {{-- ===== FOTO ===== --}}
            <div class="relative h-52 overflow-hidden bg-slate-200 dark:bg-slate-700 flex-shrink-0">
                <img src="{{ $room['foto'] ?? asset('images/default-room.jpg') }}"
                     alt="{{ $room['nama'] ?? 'Ruangan' }}"
                     class="room-img w-full h-full object-cover transition-transform duration-500 hover:scale-105"
                     loading="lazy">

                {{-- Overlay gradient --}}
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent"></div>

                {{-- Badge Status --}}
                <span class="absolute top-3 right-3 flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold shadow-lg
                    {{ $room['status'] == 'Tersedia' ? 'bg-emerald-500/90 text-white' : 'bg-red-500/90 text-white' }}">
                    <span class="w-1.5 h-1.5 rounded-full
                        {{ $room['status'] == 'Tersedia' ? 'bg-white animate-pulse' : 'bg-white' }}"></span>
                    <i class="fas {{ $room['status'] == 'Tersedia' ? 'fa-check-circle' : 'fa-times-circle' }} mr-0.5"></i>
                    {{ $room['status'] ?? 'Unknown' }}
                </span>

                {{-- Nomor urut --}}
                <span class="absolute top-3 left-3 w-8 h-8 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center text-white font-bold text-xs shadow-lg">
                    #{{ $i + 1 }}
                </span>

                {{-- Kapasitas Badge --}}
                <span class="absolute bottom-16 left-3 px-3 py-1 bg-black/50 backdrop-blur-sm rounded-xl text-white text-xs font-semibold flex items-center gap-1.5">
                    <i class="fas fa-users"></i>
                    {{ $room['kapasitas'] ?? 0 }} org
                </span>

                {{-- Nama ruang di atas foto --}}
                <div class="absolute bottom-3 left-4 right-4">
                    <h3 class="text-white font-bold text-base leading-tight">{{ $room['nama'] }}</h3>
                    <p class="text-white/70 text-xs font-mono mt-0.5 flex items-center gap-2">
                        <span>{{ $room['kode'] }}</span>
                        <span class="w-1 h-1 rounded-full bg-white/50"></span>
                        <span>{{ $room['gedung'] }}, Lt. {{ $room['lantai'] }}</span>
                    </p>
                </div>
            </div>

            {{-- ===== BODY ===== --}}
            <div class="p-5 flex-grow">

                {{-- Jam Operasional --}}
                <div class="flex items-center gap-3 mb-3 text-xs text-slate-500 dark:text-slate-400">
                    <span class="flex items-center gap-1">
                        <i class="fas fa-clock text-indigo-400"></i>
                        {{ $room['jam_buka'] ?? '-' }} – {{ $room['jam_tutup'] ?? '-' }}
                    </span>
                    <span class="w-px h-3 bg-slate-200 dark:bg-slate-600"></span>
                    <span class="flex items-center gap-1">
                        <i class="fas fa-hourglass-half text-indigo-400"></i>
                        {{ $room['max_durasi'] ?? '-' }}
                    </span>
                </div>

                {{-- Fasilitas --}}
                <div class="mb-3">
                    <div class="flex flex-wrap gap-1.5">
                        @forelse(array_slice($room['fasilitas'] ?? [], 0, 4) as $f)
                            <span class="bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 text-xs px-2.5 py-1 rounded-xl font-medium">
                                <i class="fas fa-check-circle text-emerald-500 text-[8px] mr-1"></i>
                                {{ $f }}
                            </span>
                        @empty
                            <span class="text-xs text-slate-400 dark:text-slate-500 italic">Tidak ada fasilitas</span>
                        @endforelse
                        @if(count($room['fasilitas'] ?? []) > 4)
                            <span class="bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-300 text-xs px-2.5 py-1 rounded-xl font-medium">
                                +{{ count($room['fasilitas'] ?? []) - 4 }} lainnya
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Alamat --}}
                @if(!empty($room['alamat']))
                <div class="flex items-start gap-2 mb-3">
                    <i class="fas fa-map-marker-alt text-slate-400 dark:text-slate-500 text-xs mt-0.5"></i>
                    <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($room['maps_query'] ?? $room['alamat'] ?? '') }}"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed hover:text-indigo-600 dark:hover:text-indigo-400 transition line-clamp-1">
                        {{ $room['alamat'] }}
                    </a>
                </div>
                @endif

                {{-- Deskripsi --}}
                @if(!empty($room['deskripsi']))
                <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed line-clamp-2 border-l-2 border-indigo-200 dark:border-indigo-800 pl-3 italic">
                    {{ $room['deskripsi'] }}
                </p>
                @endif

            </div> {{-- End Body --}}

            {{-- ===== FOOTER (Booking) ===== --}}
            <div class="border-t border-slate-100 dark:border-slate-700 px-5 py-4 bg-slate-50/50 dark:bg-slate-700/30 flex items-center justify-between gap-3 flex-shrink-0 mt-auto">
                <a href="{{ route('rooms.show', $room['id']) }}"
                   class="text-sm font-semibold text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition flex items-center gap-1 group">
                    Lihat Detail
                    <i class="fas fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                </a>

                @if(($room['status'] ?? '') == 'Tersedia')
                    <a href="{{ route('bookings.create', ['room_id' => $room['id']]) }}"
                       class="bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white text-xs font-semibold px-4 py-2.5 rounded-xl transition shadow-sm hover:shadow-md flex items-center gap-1.5">
                        <i class="fas fa-plus-circle"></i> Booking
                    </a>
                @else
                    <span class="text-xs font-semibold text-red-500 dark:text-red-400 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-500 inline-block animate-pulse"></span>
                        Sedang digunakan
                    </span>
                @endif
            </div>

        </div> {{-- End Room Card --}}
        @empty
        <div class="col-span-full">
            <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700 p-16 text-center">
                <div class="flex flex-col items-center">
                    <div class="w-24 h-24 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-5xl mb-4">
                        <i class="fas fa-building text-slate-400 dark:text-slate-500"></i>
                    </div>
                    <p class="text-xl font-bold text-slate-600 dark:text-slate-400">Belum ada ruangan</p>
                    <p class="text-sm text-slate-400 dark:text-slate-500 mt-1">Belum ada ruangan yang terdaftar di sistem</p>
                </div>
            </div>
        </div>
        @endforelse

    </div>

    {{-- Empty State (Filter) --}}
    <div id="emptyState" class="hidden text-center py-16">
        <div class="flex flex-col items-center">
            <div class="w-20 h-20 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-4xl mb-4">
                <i class="fas fa-search text-slate-400 dark:text-slate-500"></i>
            </div>
            <p class="text-lg font-semibold text-slate-600 dark:text-slate-400">Ruangan tidak ditemukan</p>
            <p class="text-sm text-slate-400 dark:text-slate-500 mt-1">Coba kata kunci atau filter lain</p>
            <button onclick="resetFilters()" class="mt-4 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition shadow-sm">
                <i class="fas fa-undo mr-1.5"></i> Reset Filter
            </button>
        </div>
    </div>

</div>

@push('scripts')
<script>
$(document).ready(function () {
    'use strict';

    // ================================================================
    // VARIABLES
    // ================================================================
    const $searchInput = $('#searchInput');
    const $filterStatus = $('#filterStatus');
    const $minCapacity = $('#minCapacity');
    const $resetBtn = $('#resetFiltersBtn');
    const $roomGrid = $('#roomGrid');
    const $emptyState = $('#emptyState');
    const $roomCards = $('.room-card');

    let searchTimeout = null;

    // ================================================================
    // FILTER FUNCTION (SATU FUNGSI SAJA)
    // ================================================================
    function filterRooms() {
        const keyword = $searchInput.val().toLowerCase().trim();
        const status = $filterStatus.val();
        const minCapacity = parseInt($minCapacity.val()) || 0;

        let visibleCount = 0;

        $roomCards.each(function () {
            const $card = $(this);
            const nama = $card.data('nama') || '';
            const cardStatus = $card.data('status') || '';
            const capacity = parseInt($card.data('kapasitas')) || 0;

            // Match conditions
            const matchSearch = keyword === '' || nama.includes(keyword);
            const matchStatus = status === 'semua' || cardStatus === status;
            const matchCapacity = capacity >= minCapacity;

            const show = matchSearch && matchStatus && matchCapacity;

            if (show) {
                $card.removeClass('hidden').show();
                visibleCount++;
            } else {
                $card.addClass('hidden').hide();
            }
        });

        // Show/hide empty state
        if (visibleCount === 0 && $roomCards.length > 0) {
            $emptyState.removeClass('hidden').show();
        } else {
            $emptyState.hide();
        }
    }

    // ================================================================
    // RESET FILTERS
    // ================================================================
    window.resetFilters = function() {
        $searchInput.val('');
        $filterStatus.val('semua');
        $minCapacity.val('');
        filterRooms();
        $searchInput.focus();
    };

    // ================================================================
    // EVENT LISTENERS
    // ================================================================

    // Search with debounce
    $searchInput.on('input', function () {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(filterRooms, 200);
    });

    // Status filter
    $filterStatus.on('change', filterRooms);

    // Capacity filter
    $minCapacity.on('input', filterRooms);

    // Reset button
    $resetBtn.on('click', resetFilters);

    // ================================================================
    // KEYBOARD SHORTCUT: ESC to reset
    // ================================================================
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape') {
            if ($searchInput.is(':focus')) {
                resetFilters();
                $searchInput.blur();
            }
        }
    });

    // ================================================================
    // INITIAL FILTER
    // ================================================================
    setTimeout(filterRooms, 100);

});
</script>
@endpush

@endsection
