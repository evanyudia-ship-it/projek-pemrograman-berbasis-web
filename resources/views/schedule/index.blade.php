@extends('layouts.app')

@section('title', 'Jadwal Ruangan')
@section('page_title', 'Jadwal Ruangan')
@section('page_subtitle', 'Kalender penggunaan semua ruang kelas')

@section('content')

@php
    use Carbon\Carbon;

    // Ambil dari query string jika ada, fallback ke bulan/tahun sekarang
    $bulanParam = request('bulan', now()->month);
    $tahunParam = request('tahun', now()->year);
    $ruangParam = request('ruang', '');
    $tglParam   = request('tanggal', '');

    $bulanIni  = Carbon::createFromDate($tahunParam, $bulanParam, 1);
    $prevBulan = $bulanIni->copy()->subMonth();
    $nextBulan = $bulanIni->copy()->addMonth();
    $totalHari = (int) $bulanIni->daysInMonth;

    // Offset hari awal (Senin = 0, Minggu = 6)
    $hariAwal = (int) $bulanIni->copy()->startOfMonth()->dayOfWeek;
    $hariAwal = $hariAwal === 0 ? 6 : $hariAwal - 1;

    // TODO: ganti dengan data dari DB saat DB aktif
    // Contoh struktur: $bookings['2026-05-03'] = [['ruang'=>'R-201','jam'=>'08:00','warna'=>'emerald'], ...]
    $bookings = [
        $bulanIni->format('Y-m') . '-03' => [
            ['ruang' => 'R-201',  'jam' => '08:00', 'warna' => 'emerald'],
        ],
        $bulanIni->format('Y-m') . '-05' => [
            ['ruang' => 'LAB-01', 'jam' => '13:00', 'warna' => 'yellow'],
        ],
        $bulanIni->format('Y-m') . '-08' => [
            ['ruang' => 'R-105',  'jam' => '18:00', 'warna' => 'blue'],
        ],
    ];

    // Daftar ruang dummy — TODO: ganti dengan $rooms dari controller
    $daftarRuang = ['R-201', 'R-105', 'LAB-01', 'AULA'];
@endphp

{{-- ===== FILTER ===== --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6">
    <form method="GET" action="{{ route('schedule.index') }}">

        {{-- Pertahankan parameter bulan & tahun saat filter --}}
        <input type="hidden" name="bulan" value="{{ $bulanParam }}">
        <input type="hidden" name="tahun" value="{{ $tahunParam }}">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            <div>
                <label class="text-sm font-semibold text-slate-700">Pilih Ruang</label>
                <select name="ruang" class="mt-2 w-full">
                    <option value="">Semua Ruang</option>
                    {{-- TODO: ganti dengan @foreach($rooms as $r) saat DB aktif --}}
                    @foreach($daftarRuang as $r)
                    <option value="{{ $r }}" {{ $ruangParam === $r ? 'selected' : '' }}>
                        {{ $r }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-sm font-semibold text-slate-700">Tanggal</label>
                <input type="date"
                       name="tanggal"
                       value="{{ $tglParam }}"
                       class="mt-2 w-full">
            </div>

            <div class="flex items-end">
                <button type="submit"
                        class="w-full bg-blue-500 hover:bg-slate-800 text-white font-semibold py-3 rounded-xl transition">
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

    {{-- Nama hari — dimulai Senin --}}
    <div class="grid grid-cols-7 gap-2 text-center text-sm font-semibold text-slate-500 mb-2">
        @foreach(['Sen','Sel','Rab','Kam','Jum','Sab','Min'] as $h)
        <div>{{ $h }}</div>
        @endforeach
    </div>

    {{-- Grid tanggal --}}
    <div class="grid grid-cols-7 gap-2">

        {{-- Padding hari kosong di awal bulan --}}
        @for ($pad = 0; $pad < $hariAwal; $pad++)
        <div></div>
        @endfor

        {{-- Hari-hari dalam bulan --}}
        @for ($hari = 1; $hari <= $totalHari; $hari++)
        @php
            $tglStr  = $bulanIni->format('Y-m') . '-' . str_pad($hari, 2, '0', STR_PAD_LEFT);
            $isToday = $tglStr === now()->format('Y-m-d');
            $isPast  = Carbon::parse($tglStr)->lt(Carbon::today());
            $events  = $bookings[$tglStr] ?? [];

            $ringClass = $isToday ? 'ring-2 ring-blue-500' : 'border border-slate-100';
            $bgClass   = $isPast ? 'bg-slate-50' : 'bg-white hover:bg-slate-50';

            // Warna badge per booking
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
                    {{ $ev['ruang'] ?? '-' }} {{ $ev['jam'] ?? '' }}
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

    {{-- Empty state jika tidak ada booking sama sekali --}}
    @if(empty(array_filter($bookings)))
    <div class="text-center py-8 text-slate-400 text-sm mt-4">
        <p class="text-3xl mb-2">📭</p>
        <p>Tidak ada jadwal bulan ini</p>
    </div>
    @endif

</div>

@endsection