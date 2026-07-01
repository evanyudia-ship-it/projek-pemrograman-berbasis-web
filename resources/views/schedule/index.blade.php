@extends('layouts.app')

@section('title', 'Jadwal Ruangan')
@section('page_title', 'Jadwal Ruangan')
@section('page_subtitle', 'Kalender penggunaan semua ruang kelas')

@section('content')

@php
    use Carbon\Carbon;

    // Gunakan data dari Controller
    $bookings       = $bookingsGrouped ?? [];
    $daftarRuang    = $rooms ?? collect([]);
    $roomParam      = $roomId ?? '';
    $dateParam      = $date ?? '';
    $hasActiveFilter = $hasActiveFilter ?? false;
    $totalBookingsAfterFilter = $totalBookingsAfterFilter ?? 0;

    // Parameter bulan & tahun untuk kalender
    $bulanParam = request('bulan', now()->month);
    $tahunParam = request('tahun', now()->year);

    $bulanIni  = Carbon::createFromDate($tahunParam, $bulanParam, 1);
    $prevBulan = $bulanIni->copy()->subMonth();
    $nextBulan = $bulanIni->copy()->addMonth();
    $totalHari = (int) $bulanIni->daysInMonth;

    // Offset hari awal (Senin = 0)
    $hariAwal = (int) $bulanIni->copy()->startOfMonth()->dayOfWeek;
    $hariAwal = $hariAwal === 0 ? 6 : $hariAwal - 1;
@endphp

<div class="max-w-7xl mx-auto font-sora space-y-6">

    {{-- ============================================================ --}}
    {{-- FILTER --}}
    {{-- ============================================================ --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                <i class="fas fa-sliders-h text-lg"></i>
            </div>
            <div>
                <h3 class="font-bold text-slate-800 dark:text-white">Filter Jadwal</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">Filter berdasarkan ruang atau tanggal tertentu</p>
            </div>
            @if($hasActiveFilter)
            <span class="ml-auto text-xs px-3 py-1.5 rounded-full bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 font-medium flex items-center gap-1">
                <i class="fas fa-filter"></i> {{ $totalBookingsAfterFilter }} hasil
            </span>
            @endif
        </div>

        <form method="GET" action="{{ route('schedule.index') }}">
            <input type="hidden" name="bulan" value="{{ $bulanParam }}">
            <input type="hidden" name="tahun" value="{{ $tahunParam }}">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                <div>
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-300 flex items-center gap-2">
                        <i class="fas fa-door-open text-indigo-400 text-xs"></i>
                        Pilih Ruang
                    </label>
                    <select name="room"
                        class="mt-2 w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        <option value="">— Semua Ruang —</option>
                        @foreach($daftarRuang as $r)
                            <option value="{{ e($r->id) }}" {{ $roomParam == $r->id ? 'selected' : '' }}>
                                {{ e($r->nama) }} ({{ e($r->kode) }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-300 flex items-center gap-2">
                        <i class="fas fa-calendar-day text-indigo-400 text-xs"></i>
                        Tanggal
                    </label>
                    <select name="date"
                        class="mt-2 w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        <option value="">— Semua Tanggal —</option>
                        @for($i = 0; $i <= 30; $i++)
                            @php
                                $tanggalOption = now()->addDays($i)->format('Y-m-d');
                            @endphp
                            <option value="{{ $tanggalOption }}"
                                    {{ $dateParam === $tanggalOption ? 'selected' : '' }}>
                                {{ now()->addDays($i)->translatedFormat('l, d F Y') }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit"
                            class="flex-1 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white font-semibold py-3 rounded-xl transition shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                        <i class="fas fa-search"></i> Filter
                    </button>
                    <a href="{{ route('schedule.index') }}"
                       class="px-5 py-3 rounded-xl bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 font-semibold transition flex items-center gap-2">
                        <i class="fas fa-undo"></i>
                    </a>
                </div>

            </div>
        </form>
    </div>

    {{-- ============================================================ --}}
    {{-- KALENDER --}}
    {{-- ============================================================ --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">

        {{-- Header Kalender --}}
        <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700 bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-indigo-950/30 dark:to-blue-950/30">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                        <i class="fas fa-calendar-alt text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 dark:text-white">Kalender Booking</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">
                            {{ $bulanIni->translatedFormat('F Y') }} · {{ array_sum(array_map('count', $bookings)) }} booking
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('schedule.index', array_merge(request()->except(['bulan','tahun']), ['bulan' => $prevBulan->month, 'tahun' => $prevBulan->year])) }}"
                       class="w-9 h-9 flex items-center justify-center rounded-xl border border-slate-200 dark:border-slate-600 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-400 transition font-bold text-lg">
                        ‹
                    </a>
                    <span class="text-sm font-bold text-slate-800 dark:text-white min-w-32 text-center">
                        {{ $bulanIni->translatedFormat('F Y') }}
                    </span>
                    <a href="{{ route('schedule.index', array_merge(request()->except(['bulan','tahun']), ['bulan' => $nextBulan->month, 'tahun' => $nextBulan->year])) }}"
                       class="w-9 h-9 flex items-center justify-center rounded-xl border border-slate-200 dark:border-slate-600 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-400 transition font-bold text-lg">
                        ›
                    </a>
                    <a href="{{ route('schedule.index') }}"
                       class="ml-2 px-3 py-1.5 rounded-lg bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-600 dark:text-slate-400 text-xs font-medium transition">
                        <i class="fas fa-calendar-day mr-1"></i> Hari Ini
                    </a>
                </div>
            </div>
        </div>

        {{-- Body Kalender --}}
        <div class="p-6">

            {{-- Legend --}}
            <div class="flex flex-wrap items-center gap-4 mb-5 pb-4 border-b border-slate-200 dark:border-slate-700">
                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 flex items-center gap-2">
                    <i class="fas fa-info-circle"></i> Keterangan:
                </span>
                <span class="flex items-center gap-1.5 text-xs text-slate-600 dark:text-slate-400">
                    <span class="w-3 h-3 rounded-full bg-emerald-500"></span> Tersedia
                </span>
                <span class="flex items-center gap-1.5 text-xs text-slate-600 dark:text-slate-400">
                    <span class="w-3 h-3 rounded-full bg-yellow-500"></span> Sebagian
                </span>
                <span class="flex items-center gap-1.5 text-xs text-slate-600 dark:text-slate-400">
                    <span class="w-3 h-3 rounded-full bg-red-500"></span> Penuh
                </span>
                <span class="flex items-center gap-1.5 text-xs text-slate-400 dark:text-slate-500">
                    <span class="w-3 h-3 rounded-full bg-slate-300 dark:bg-slate-600"></span> Lewat
                </span>
                <span class="flex items-center gap-1.5 text-xs text-slate-600 dark:text-slate-400">
                    <span class="w-3 h-3 rounded-full ring-2 ring-blue-500 ring-offset-1"></span> Hari Ini
                </span>
            </div>

            {{-- Nama Hari --}}
            <div class="grid grid-cols-7 gap-2 text-center text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">
                @foreach(['Sen','Sel','Rab','Kam','Jum','Sab','Min'] as $h)
                <div class="py-2 bg-slate-50 dark:bg-slate-700/50 rounded-lg">{{ $h }}</div>
                @endforeach
            </div>

            {{-- Grid Tanggal --}}
            <div class="grid grid-cols-7 gap-2">

                {{-- Padding hari kosong --}}
                @for ($pad = 0; $pad < $hariAwal; $pad++)
                <div class="h-24 rounded-xl bg-slate-50/50 dark:bg-slate-700/30"></div>
                @endfor

                {{-- Hari-hari dalam bulan --}}
                @for ($hari = 1; $hari <= $totalHari; $hari++)
                @php
                    $tglStr  = $bulanIni->format('Y-m') . '-' . str_pad($hari, 2, '0', STR_PAD_LEFT);
                    $status  = $dateStatuses[$tglStr] ?? ['isPast' => false, 'isToday' => false];
                    $isPast  = $status['isPast'];
                    $isToday = $status['isToday'];
                    $events  = $bookings[$tglStr] ?? [];

                    $warnaMap = [
                        'emerald' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300',
                        'yellow'  => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/50 dark:text-yellow-300',
                        'blue'    => 'bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300',
                        'red'     => 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300',
                        'purple'  => 'bg-purple-100 text-purple-700 dark:bg-purple-900/50 dark:text-purple-300',
                    ];
                @endphp

                <div class="h-24 rounded-xl p-2 text-xs relative transition-all duration-200
                    {{ $isPast ? 'bg-slate-50 dark:bg-slate-700/30' : 'bg-white dark:bg-slate-700/50 hover:shadow-md' }}
                    {{ $isToday ? 'ring-2 ring-indigo-500 ring-offset-2 dark:ring-offset-slate-800' : 'border border-slate-200 dark:border-slate-600' }}
                    {{ !$isPast ? 'cursor-pointer hover:border-indigo-300 dark:hover:border-indigo-500' : '' }}">

                    {{-- Nomor hari --}}
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-sm
                            {{ $isPast ? 'text-slate-400 dark:text-slate-500' : ($isToday ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-700 dark:text-slate-300') }}">
                            {{ $hari }}
                        </span>
                        @if($isToday)
                        <span class="text-[8px] font-bold bg-indigo-500 text-white px-1.5 py-0.5 rounded-full">HARI INI</span>
                        @endif
                    </div>

                    {{-- Event booking --}}
                    @if(!empty($events))
                        @foreach(array_slice($events, 0, 2) as $ev)
                        <div class="mt-1 p-1 rounded text-[10px] truncate {{ $warnaMap[$ev['warna'] ?? 'blue'] ?? 'bg-slate-100 text-slate-600 dark:bg-slate-600 dark:text-slate-300' }}">
                            <span class="font-medium">{{ $ev['ruang'] ?? '-' }}</span>
                            <span class="opacity-75">• {{ $ev['jam'] ?? '' }}</span>
                            @if(!empty($ev['title']))
                                <span class="block text-[9px] opacity-75 truncate">{{ $ev['title'] }}</span>
                            @endif
                        </div>
                        @endforeach
                        @if(count($events) > 2)
                        <div class="mt-0.5 text-[10px] text-indigo-500 dark:text-indigo-400 font-semibold">
                            +{{ count($events) - 2 }} lainnya
                        </div>
                        @endif
                    @else
                        @if(!$isPast)
                        <div class="mt-2 text-[10px] text-emerald-400 dark:text-emerald-500 flex items-center gap-1">
                            <i class="fas fa-check-circle"></i> Tersedia
                        </div>
                        @endif
                    @endif

                </div>
                @endfor

            </div>

            {{-- Empty State --}}
            @php
                $hasAnyBookingInCalendar = !empty(array_filter($bookings));
            @endphp

            @if(!$hasAnyBookingInCalendar)
            <div class="text-center py-12 bg-slate-50 dark:bg-slate-700/30 rounded-2xl mt-6">
                <div class="flex flex-col items-center">
                    <div class="w-20 h-20 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-4xl mb-4">
                        <i class="fas fa-calendar-times text-slate-400 dark:text-slate-500"></i>
                    </div>
                    @if($hasActiveFilter)
                        <p class="font-semibold text-slate-600 dark:text-slate-400">Tidak ada jadwal</p>
                        <p class="text-sm text-slate-400 dark:text-slate-500 mt-1">
                            @if(!empty($roomParam))
                                untuk ruang <span class="font-mono bg-slate-200 dark:bg-slate-600 px-2 py-0.5 rounded">{{ e($roomParam) }}</span>
                            @endif
                            @if(!empty($dateParam))
                                pada tanggal {{ Carbon::parse($dateParam)->translatedFormat('d F Y') }}
                            @endif
                        </p>
                        <a href="{{ route('schedule.index') }}"
                           class="mt-4 inline-flex items-center gap-2 text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 font-medium transition">
                            <i class="fas fa-undo"></i> Reset filter
                        </a>
                    @else
                        <p class="font-semibold text-slate-600 dark:text-slate-400">Belum ada jadwal</p>
                        <p class="text-sm text-slate-400 dark:text-slate-500 mt-1">
                            Belum ada booking untuk bulan {{ $bulanIni->translatedFormat('F Y') }}
                        </p>
                        <a href="{{ route('bookings.create') }}"
                           class="mt-4 inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white text-sm font-semibold transition shadow-md hover:shadow-lg">
                            <i class="fas fa-plus-circle"></i> Ajukan Booking
                        </a>
                    @endif
                </div>
            </div>
            @endif

        </div>
    </div>

</div>

@endsection
