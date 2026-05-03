@extends('layouts.app')

@section('title', $room['nama'] . ' - Smart Classroom')
@section('page_title', 'Detail Ruangan')
@section('page_subtitle', $room['kode'] . ' · ' . $room['gedung'])

@section('content')

@php
    use Carbon\Carbon;

    $today      = Carbon::today();
    $kalender   = Carbon::createFromDate($tahun, $bulan, 1);
    $prevBulan  = $kalender->copy()->subMonth();
    $nextBulan  = $kalender->copy()->addMonth();
    $hariAwal   = (int) $kalender->copy()->startOfMonth()->dayOfWeek; // 0=Min
    $totalHari  = (int) $kalender->daysInMonth;

    // Jadwal dalam bulan ini
    $jadwalBulanIni = collect($room['jadwal'])->filter(
        fn($v, $tgl) => str_starts_with($tgl, $kalender->format('Y-m'))
    );
@endphp

<div class="max-w-6xl mx-auto space-y-6">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-slate-500">
        <a href="{{ route('rooms.index') }}" class="hover:text-blue-600 transition">Ketersediaan Ruang</a>
        <span>/</span>
        <span class="text-slate-700 font-medium">{{ $room['kode'] }}</span>
    </nav>

    {{-- === ROW 1: Info + Aksi === --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Info Ruangan (2/3) --}}
        <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">

            {{-- Accent bar warna sesuai status --}}
            <div class="h-1.5 {{ $room['status'] == 'Tersedia' ? 'bg-gradient-to-r from-emerald-400 to-teal-500' : 'bg-gradient-to-r from-amber-400 to-orange-500' }}"></div>

            <div class="p-6">
                {{-- Header --}}
                <div class="flex items-start justify-between mb-6">
                    <div>
                        <span class="text-xs font-mono bg-slate-100 px-2.5 py-1 rounded-lg text-slate-500">
                            {{ $room['kode'] }}
                        </span>
                        <h2 class="text-2xl font-bold mt-3 text-slate-900">{{ $room['nama'] }}</h2>
                        <p class="text-slate-500 text-sm mt-1">{{ $room['gedung'] }} · Lantai {{ $room['lantai'] }}</p>
                    </div>
                    <span class="px-4 py-1.5 text-sm font-semibold rounded-2xl flex items-center gap-1.5
                        {{ $room['status'] == 'Tersedia' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                        <span class="w-2 h-2 rounded-full {{ $room['status'] == 'Tersedia' ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                        {{ $room['status'] }}
                    </span>
                </div>

                {{-- Statistik --}}
                <div class="grid grid-cols-3 gap-4 mb-6">
                    <div class="bg-slate-50 rounded-2xl p-4 text-center">
                        <p class="text-xs text-slate-500 mb-1">Kapasitas</p>
                        <p class="text-2xl font-bold text-slate-800">{{ $room['kapasitas'] }}</p>
                        <p class="text-xs text-slate-400">orang</p>
                    </div>
                    <div class="bg-slate-50 rounded-2xl p-4 text-center">
                        <p class="text-xs text-slate-500 mb-1">Lantai</p>
                        <p class="text-2xl font-bold text-slate-800">{{ $room['lantai'] }}</p>
                        <p class="text-xs text-slate-400">{{ $room['gedung'] }}</p>
                    </div>
                    <div class="bg-slate-50 rounded-2xl p-4 text-center">
                        <p class="text-xs text-slate-500 mb-1">Booking Bulan Ini</p>
                        <p class="text-2xl font-bold text-slate-800">{{ $totalBooking }}</p>
                        <p class="text-xs text-slate-400">sesi</p>
                    </div>
                </div>

                {{-- Fasilitas --}}
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">Fasilitas</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($room['fasilitas'] as $f)
                        <span class="bg-slate-100 text-slate-600 text-xs px-3 py-1.5 rounded-xl font-medium">
                            {{ $f }}
                        </span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Panel Aksi (1/3) --}}
        <div class="flex flex-col gap-4">

            {{-- Tombol Booking --}}
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 flex flex-col gap-3">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Tindakan</p>

                @if($room['status'] == 'Tersedia')
                <a href="{{ route('bookings.create', ['room_id' => $room['id']]) }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white text-center py-3 rounded-2xl font-semibold text-sm transition">
                    ➕ Ajukan Booking
                </a>
                @else
                <button disabled
                    class="bg-slate-200 text-slate-400 text-center py-3 rounded-2xl font-semibold text-sm cursor-not-allowed">
                    Ruangan Sedang Dipakai
                </button>
                @endif

                <a href="{{ route('rooms.index') }}"
                   class="border border-slate-200 hover:border-slate-300 text-slate-600 text-center py-3 rounded-2xl font-semibold text-sm transition">
                    ← Kembali ke Daftar
                </a>

                <hr class="border-slate-100">

                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Jam Operasional</span>
                        <span class="font-semibold text-slate-700">{{ $room['jam_buka'] }} – {{ $room['jam_tutup'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Maks. Durasi</span>
                        <span class="font-semibold text-slate-700">{{ $room['max_durasi'] }}</span>
                    </div>
                </div>
            </div>

            {{-- Legenda Kalender --}}
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-4">Keterangan Kalender</p>
                <div class="space-y-2 text-sm">
                    <div class="flex items-center gap-3">
                        <span class="w-5 h-5 rounded-lg bg-emerald-100 inline-block"></span>
                        <span class="text-slate-600">Tersedia</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="w-5 h-5 rounded-lg bg-amber-100 inline-block"></span>
                        <span class="text-slate-600">Sebagian terpakai</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="w-5 h-5 rounded-lg bg-red-100 inline-block"></span>
                        <span class="text-slate-600">Penuh / tidak tersedia</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="w-5 h-5 rounded-lg border-2 border-blue-500 inline-block"></span>
                        <span class="text-slate-600">Hari ini</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- === ROW 2: Kalender === --}}
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6">

        {{-- Header Kalender --}}
        <div class="flex items-center justify-between mb-6">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Kalender Ketersediaan</p>
            <div class="flex items-center gap-3">
                <a href="{{ route('rooms.show', ['id' => $room['id'], 'tahun' => $prevBulan->year, 'bulan' => $prevBulan->month]) }}"
                   class="w-8 h-8 flex items-center justify-center rounded-xl border border-slate-200 hover:border-slate-300 text-slate-600 transition">‹</a>
                <span class="font-semibold text-slate-800 text-sm min-w-[130px] text-center">
                    {{ $kalender->translatedFormat('F Y') }}
                </span>
                <a href="{{ route('rooms.show', ['id' => $room['id'], 'tahun' => $nextBulan->year, 'bulan' => $nextBulan->month]) }}"
                   class="w-8 h-8 flex items-center justify-center rounded-xl border border-slate-200 hover:border-slate-300 text-slate-600 transition">›</a>
            </div>
        </div>

        {{-- Nama Hari --}}
        <div class="grid grid-cols-7 mb-2">
            @foreach(['Min','Sen','Sel','Rab','Kam','Jum','Sab'] as $hari)
            <div class="text-center text-xs font-semibold text-slate-400 py-2">{{ $hari }}</div>
            @endforeach
        </div>

        {{-- Grid Tanggal --}}
        <div class="grid grid-cols-7 gap-1">

            {{-- Offset hari pertama --}}
            @for($i = 0; $i < $hariAwal; $i++)
            <div></div>
            @endfor

            {{-- Looping tanggal --}}
            @for($hari = 1; $hari <= $totalHari; $hari++)
                @php
                    $tglStr   = $kalender->format('Y-m') . '-' . str_pad($hari, 2, '0', STR_PAD_LEFT);
                    $jadwal   = $room['jadwal'][$tglStr] ?? null;
                    $isToday  = $tglStr === $today->format('Y-m-d');
                    $isPast   = Carbon::parse($tglStr)->lt($today);

                    if ($jadwal && $jadwal['tipe'] === 'penuh') {
                        $bg   = 'bg-red-100 text-red-700';
                    } elseif ($jadwal && $jadwal['tipe'] === 'sebagian') {
                        $bg   = 'bg-amber-100 text-amber-700';
                    } elseif ($isPast) {
                        $bg   = 'bg-slate-50 text-slate-300';
                    } else {
                        $bg   = 'bg-emerald-100 text-emerald-700';
                    }

                    $ring = $isToday ? 'ring-2 ring-blue-500 ring-offset-1' : '';

                    // Tooltip teks
                    $tooltip = $jadwal ? $jadwal['label'] . ' · ' . $jadwal['waktu'] : 'Tersedia';
                @endphp

                <div class="flex justify-center py-1" title="{{ $tooltip }}">
                    <span class="w-9 h-9 flex items-center justify-center rounded-xl text-sm font-medium cursor-default select-none {{ $bg }} {{ $ring }}">
                        {{ $hari }}
                    </span>
                </div>
            @endfor

        </div>
    </div>

    {{-- === ROW 3: Daftar Jadwal === --}}
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-4">
            Jadwal Penggunaan – {{ $kalender->translatedFormat('F Y') }}
        </p>

        @if($jadwalBulanIni->isEmpty())
        <div class="text-center py-10 text-slate-400">
            <p class="text-4xl mb-3">📭</p>
            <p class="text-sm">Belum ada jadwal penggunaan bulan ini.</p>
        </div>
        @else
        <div class="divide-y divide-slate-100">
            @foreach($jadwalBulanIni as $tgl => $j)
            @php
                $tanggal  = Carbon::parse($tgl);
                $badgeCls = $j['tipe'] === 'penuh'
                    ? 'bg-red-100 text-red-700'
                    : 'bg-amber-100 text-amber-700';
                $badgeLabel = $j['tipe'] === 'penuh' ? 'Penuh' : 'Sebagian';
            @endphp
            <div class="py-4 flex items-center justify-between gap-4">
                {{-- Tanggal badge --}}
                <div class="bg-slate-50 rounded-2xl px-3 py-2 text-center min-w-[52px]">
                    <p class="text-xs text-slate-400 leading-none">{{ $tanggal->translatedFormat('D') }}</p>
                    <p class="text-xl font-bold text-slate-800 leading-tight">{{ $tanggal->day }}</p>
                </div>

                {{-- Info --}}
                <div class="flex-1">
                    <p class="font-semibold text-slate-800 text-sm">{{ $j['label'] }}</p>
                    <p class="text-xs text-slate-400 mt-0.5">🕐 {{ $j['waktu'] }}</p>
                </div>

                <span class="px-3 py-1.5 rounded-2xl text-xs font-semibold {{ $badgeCls }}">
                    {{ $badgeLabel }}
                </span>
            </div>
            @endforeach
        </div>
        @endif
    </div>

</div>

@endsection