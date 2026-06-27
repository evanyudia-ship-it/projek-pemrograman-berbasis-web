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
    $roomParam      = $room ?? '';
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

{{-- ===== FILTER ===== --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6">
    <form method="GET" action="{{ route('schedule.index') }}">

        <input type="hidden" name="bulan" value="{{ $bulanParam }}">
        <input type="hidden" name="tahun" value="{{ $tahunParam }}">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            <div>
                <label class="text-sm font-semibold text-slate-700">Pilih Ruang</label>
                <select name="room"
                    class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm bg-white text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    <option value="">Semua Ruang</option>
                    @foreach($daftarRuang as $r)
                        <option value="{{ e($r->id) }}" {{ $roomParam === $r->id ? 'selected' : '' }}>
                            {{ e($r->nama) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-sm font-semibold text-slate-700">Tanggal</label>
                <select name="date"
                        class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm bg-white text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    <option value="">Semua Tanggal</option>
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

            <div class="flex items-end">
                <button type="submit"
                        class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 rounded-xl transition">
                    Filter
                </button>
            </div>

        </div>
    </form>
</div>

{{-- ===== KALENDER ===== --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">

    {{-- Header kalender + navigasi bulan --}}
    <div class="flex justify-between items-center mb-6">
        <h3 class="font-bold text-lg">Kalender Booking</h3>
        <div class="flex items-center gap-3">
            <a href="{{ route('schedule.index', array_merge(request()->except(['bulan','tahun']), ['bulan' => $prevBulan->month, 'tahun' => $prevBulan->year])) }}"
            class="w-9 h-9 flex items-center justify-center rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-600 transition font-bold">
                ‹
            </a>
            <span class="text-sm font-semibold text-slate-700 min-w-32 text-center">
                {{ $bulanIni->translatedFormat('F Y') }}
            </span>
            <a href="{{ route('schedule.index', array_merge(request()->except(['bulan','tahun']), ['bulan' => $nextBulan->month, 'tahun' => $nextBulan->year])) }}"
            class="w-9 h-9 flex items-center justify-center rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-600 transition font-bold">
                ›
            </a>
        </div>
    </div>

    {{-- Nama hari --}}
    <div class="grid grid-cols-7 gap-2 text-center text-sm font-semibold text-slate-500 mb-2">
        @foreach(['Sen','Sel','Rab','Kam','Jum','Sab','Min'] as $h)
        <div>{{ $h }}</div>
        @endforeach
    </div>

    {{-- Grid tanggal --}}
    <div class="grid grid-cols-7 gap-2">

        {{-- Padding hari kosong --}}
        @for ($pad = 0; $pad < $hariAwal; $pad++)
        <div></div>
        @endfor

        {{-- Hari-hari dalam bulan --}}
        @for ($hari = 1; $hari <= $totalHari; $hari++)
        @php
            $tglStr  = $bulanIni->format('Y-m') . '-' . str_pad($hari, 2, '0', STR_PAD_LEFT);
            $status  = $dateStatuses[$tglStr] ?? ['isPast' => false, 'isToday' => false];
            $isPast  = $status['isPast'];
            $isToday = $status['isToday'];
            $events  = $bookings[$tglStr] ?? [];

            $ringClass = $isToday ? 'ring-2 ring-blue-500' : 'border border-slate-100';
            $bgClass   = $isPast ? 'bg-slate-50' : 'bg-white hover:bg-slate-50';

            $warnaMap = [
                'emerald' => 'bg-emerald-100 text-emerald-700',
                'yellow'  => 'bg-yellow-100 text-yellow-700',
                'blue'    => 'bg-blue-100 text-blue-700',
                'red'     => 'bg-red-100 text-red-700',
                'purple'  => 'bg-purple-100 text-purple-700',
            ];
        @endphp

        <div class="h-24 rounded-xl p-2 text-xs relative {{ $bgClass }} {{ $ringClass }} transition overflow-hidden">

            {{-- Nomor hari --}}
            <div class="font-bold {{ $isPast ? 'text-slate-300' : ($isToday ? 'text-blue-600' : 'text-slate-600') }}">
                {{ $hari }}
            </div>

            {{-- Event booking --}}
            @if(!empty($events))
                @foreach(array_slice($events, 0, 2) as $ev)
                <div class="mt-1 p-1 rounded text-[10px] truncate {{ $warnaMap[$ev['warna'] ?? 'blue'] ?? 'bg-slate-100 text-slate-600' }}">
                    {{ $ev['ruang'] ?? '-' }} • {{ $ev['jam'] ?? '' }}
                    @if(!empty($ev['title']))
                        <span class="block text-[9px] opacity-75">{{ $ev['title'] }}</span>
                    @endif
                </div>
                @endforeach
                @if(count($events) > 2)
                <div class="mt-0.5 text-[10px] text-slate-400 font-semibold">
                    +{{ count($events) - 2 }} lainnya
                </div>
                @endif
            @endif

        </div>
        @endfor

    </div>

    {{-- Pesan ketika tidak ada booking --}}
    @php
        $hasAnyBookingInCalendar = !empty(array_filter($bookings));
    @endphp

    @if(!$hasAnyBookingInCalendar)
    <div class="text-center py-12 bg-slate-50 rounded-2xl mt-6">
        @if($hasActiveFilter)
            <p class="text-4xl mb-3">🔍</p>
            <p class="font-semibold text-slate-600">Tidak ada jadwal</p>
            <p class="text-sm text-slate-400 mt-1">
                @if(!empty($roomParam))
                    untuk ruang <span class="font-mono bg-slate-200 px-2 py-0.5 rounded">{{ e($roomParam) }}</span>
                @endif
                @if(!empty($dateParam))
                    pada tanggal {{ Carbon::parse($dateParam)->translatedFormat('d F Y') }}
                @endif
            </p>
            <a href="{{ route('schedule.index') }}"
            class="inline-block mt-4 text-sm text-blue-500 hover:text-blue-600 transition">
                Reset filter →
            </a>
        @else
            <p class="text-4xl mb-3">📭</p>
            <p class="font-semibold text-slate-500">Belum ada jadwal</p>
            <p class="text-sm text-slate-400 mt-1">Belum ada booking untuk bulan {{ $bulanIni->translatedFormat('F Y') }}</p>
        @endif
    </div>
    @endif

</div>

@endsection
