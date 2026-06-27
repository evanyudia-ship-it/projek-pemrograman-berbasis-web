@extends('layouts.app')

@section('title', ($room['nama'] ?? 'Ruangan') . ' - Smart Classroom')
@section('page_title', 'Detail Ruangan')
@section('page_subtitle', ($room['kode'] ?? '-') . ' · ' . ($room['gedung'] ?? '-'))

@section('content')

@php
    use Carbon\Carbon;

    $today         = Carbon::today();
    $kalender      = Carbon::createFromDate($tahun, $bulan, 1);
    $prevBulan     = $kalender->copy()->subMonth();
    $nextBulan     = $kalender->copy()->addMonth();
    $hariAwal      = (int) $kalender->copy()->startOfMonth()->dayOfWeek;
    $totalHari     = (int) $kalender->daysInMonth;

    $jadwalBulanIni = collect($room['jadwal'] ?? [])->filter(
        fn($v, $tgl) => str_starts_with($tgl, $kalender->format('Y-m'))
    );
@endphp

<div class="max-w-6xl mx-auto font-sora space-y-6">

    {{-- ===== BREADCRUMB ===== --}}
    <nav class="flex items-center gap-2 text-sm text-slate-500 fade-up">
        <a href="{{ route('rooms.index') }}"
           class="hover:text-blue-600 transition font-medium">Ketersediaan Ruang</a>
        <span class="text-slate-300">/</span>
        <span class="text-slate-800 font-semibold">{{ $room['kode'] }}</span>
    </nav>

    {{-- ===== HERO FOTO ===== --}}
    <div class="fade-up relative h-64 md:h-80 rounded-3xl overflow-hidden bg-slate-200 shadow-sm">
        <img src="{{ $room['foto'] }}"
             alt="{{ $room['nama'] }}"
             class="hero-img w-full h-full object-cover">

        {{-- Overlay --}}
        <div class="absolute inset-0 bg-linear-to-t from-black/70 via-black/20 to-transparent"></div>

        {{-- Badge status --}}
        <span class="absolute top-5 right-5 flex items-center gap-2 px-4 py-2 rounded-full text-sm font-bold
            {{ $room['status'] == 'Tersedia'
                ? 'bg-emerald-400/90 text-emerald-900'
                : 'bg-red-400/90 text-red-900' }}">
            <span class="w-2 h-2 rounded-full
                {{ $room['status'] == 'Tersedia' ? 'bg-emerald-800 animate-pulse' : 'bg-red-800' }}"></span>
            {{ $room['status'] }}
            <span class="text-xs font-normal opacity-75">· saat ini</span>
        </span>

        {{-- Kode ruangan --}}
        <span class="absolute top-5 left-5 bg-white/20 backdrop-blur-sm text-white font-mono text-xs px-3 py-1.5 rounded-xl">
            {{ $room['kode'] }}
        </span>

        {{-- Nama & lokasi di bawah hero --}}
        <div class="absolute bottom-5 left-6 right-6">
            <h1 class="text-white text-2xl md:text-3xl font-bold leading-tight">
                {{ $room['nama'] }}
            </h1>
            <p class="text-white/70 text-sm mt-1">
                {{ $room['gedung'] }} · Lantai {{ $room['lantai'] }}
            </p>
        </div>
    </div>

    {{-- ===== ROW 1: Info + Sidebar Aksi ===== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 fade-up delay-1">

        {{-- ===== KIRI: Info Lengkap (2/3) ===== --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Stat cards --}}
            <div class="grid grid-cols-3 gap-4">
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 text-center">
                    <p class="text-xs text-slate-400 mb-1">Kapasitas</p>
                    <p class="text-3xl font-bold text-slate-900">{{ $room['kapasitas'] }}</p>
                    <p class="text-xs text-slate-400 mt-0.5">orang</p>
                </div>
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 text-center">
                    <p class="text-xs text-slate-400 mb-1">Lantai</p>
                    <p class="text-3xl font-bold text-slate-900">{{ $room['lantai'] }}</p>
                    <p class="text-xs text-slate-400 mt-0.5">{{ $room['gedung'] }}</p>
                </div>
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 text-center">
                    <p class="text-xs text-slate-400 mb-1">Booking Bulan Ini</p>
                    <p class="text-3xl font-bold text-slate-900">{{ $totalBooking ?? 0 }}</p>
                    <p class="text-xs text-slate-400 mt-0.5">sesi</p>
                </div>
            </div>

            {{-- Deskripsi --}}
            @if(!empty($room['deskripsi']))
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">Tentang Ruangan</p>
                <p class="text-sm text-slate-600 leading-relaxed border-l-2 border-slate-200 pl-4 italic">
                    {{ $room['deskripsi'] }}
                </p>
            </div>
            @endif

            {{-- Fasilitas --}}
            <div class="bg-white dark:bg-surface rounded-2xl border border-slate-100 dark:border-default shadow-sm p-5">
                <p class="text-xs font-semibold text-slate-400 dark:text-text-muted uppercase tracking-wider mb-4">Fasilitas</p>
                <div class="flex flex-wrap gap-2">
                    @foreach(($room['fasilitas'] ?? []) as $f)
                    <span class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs px-3 py-1.5 rounded-xl font-medium">
                        {{ $f }}
                    </span>
                    @endforeach
                </div>
            </div>

            {{-- Meta info --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-4">Informasi Operasional</p>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div class="flex items-center gap-3">
                        <span class="w-9 h-9 bg-blue-50 rounded-xl flex items-center justify-center text-base">🕐</span>
                        <div>
                            <p class="text-xs text-slate-400">Jam Operasional</p>
                            <p class="font-semibold text-slate-800">{{ $room['jam_buka'] }} – {{ $room['jam_tutup'] }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="w-9 h-9 bg-purple-50 rounded-xl flex items-center justify-center text-base">⏱</span>
                        <div>
                            <p class="text-xs text-slate-400">Maks. Durasi</p>
                            <p class="font-semibold text-slate-800">{{ $room['max_durasi'] }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="w-9 h-9 bg-slate-100 rounded-xl flex items-center justify-center text-base">📶</span>
                        <div>
                            <p class="text-xs text-slate-400">Koneksi</p>
                            {{-- TODO: cek dari $room['fasilitas'] saat DB aktif --}}
                            <p class="font-semibold text-slate-800">WiFi Tersedia</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="w-9 h-9 bg-amber-50 rounded-xl flex items-center justify-center text-base">⚡</span>
                        <div>
                            <p class="text-xs text-slate-400">Listrik</p>
                            <p class="font-semibold text-slate-800">Stop kontak tersedia</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- ===== KANAN: Panel Aksi (1/3) ===== --}}
        <div class="flex flex-col gap-4">

            {{-- Booking action --}}
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 flex flex-col gap-3">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Tindakan</p>

                @if($room['status'] == 'Tersedia')
                <a href="{{ route('bookings.create', ['room_id' => $room['id']]) }}"
                   class="action-btn bg-slate-900 hover:bg-slate-700 text-white text-center py-3.5 rounded-2xl font-semibold text-sm transition flex items-center justify-center gap-2">
                    <span>➕</span> Ajukan Booking
                </a>
                @else
                <div class="bg-red-50 border border-red-100 text-red-600 text-center py-3.5 rounded-2xl font-semibold text-sm flex items-center justify-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-red-500 inline-block"></span>
                    Ruangan Sedang Dipakai
                </div>
                <p class="text-xs text-slate-400 text-center">Cek kalender untuk waktu lain</p>
                @endif

                <a href="{{ route('rooms.index') }}"
                   class="action-btn border border-slate-200 hover:border-slate-300 text-slate-600 text-center py-3 rounded-2xl font-semibold text-sm transition">
                    ← Kembali ke Daftar
                </a>
            </div>

            {{-- Legenda kalender --}}
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-4">Keterangan Kalender</p>
                <div class="space-y-2.5">
                    <div class="flex items-center gap-3 text-sm">
                        <span class="w-6 h-6 rounded-lg bg-emerald-100 shrink-0"></span>
                        <span class="text-slate-600">Tersedia</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm">
                        <span class="w-6 h-6 rounded-lg bg-amber-100 shrink-0"></span>
                        <span class="text-slate-600">Sebagian terpakai</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm">
                        <span class="w-6 h-6 rounded-lg bg-red-100 shrink-0"></span>
                        <span class="text-slate-600">Penuh</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm">
                        <span class="w-6 h-6 rounded-lg bg-slate-100 shrink-0"></span>
                        <span class="text-slate-600">Sudah lewat</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm">
                        <span class="w-6 h-6 rounded-lg border-2 border-blue-500 shrink-0"></span>
                        <span class="text-slate-600">Hari ini</span>
                    </div>
                </div>
            </div>

            {{-- Quick stats ruang --}}
            <div class="bg-slate-950 rounded-3xl p-6 text-white">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-4">Utilisasi Bulan Ini</p>
                @php
                    $pctBooking = ($totalBooking ?? 0) > 0 ? min(($totalBooking ?? 0) / 10 * 100, 100) : 0;
                @endphp
                <div class="text-3xl font-bold mb-1">{{ $totalBooking ?? 0 }}
                    <span class="text-base font-normal text-slate-400">sesi</span>
                </div>
                <div class="mt-3 h-2 bg-white/10 rounded-full overflow-hidden">
                    <div class="h-full bg-linear-to-r from-blue-400 to-violet-400 rounded-full transition-all duration-700"
                         style="width: {{ $pctBooking }}%"></div>
                </div>
                <p class="text-xs text-slate-500 mt-2">dari estimasi 10 sesi/bulan</p>
            </div>

        </div>
    </div>

    {{-- ===== ROW 2: KALENDER ===== --}}
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 fade-up delay-2">

        {{-- Header kalender --}}
        <div class="flex items-center justify-between mb-6">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Kalender Ketersediaan</p>
            <div class="flex items-center gap-3">
                <a href="{{ route('rooms.show', ['id' => $room['id'], 'tahun' => $prevBulan->year, 'bulan' => $prevBulan->month]) }}"
                   class="w-9 h-9 flex items-center justify-center rounded-xl border border-slate-200 hover:border-slate-300 hover:bg-slate-50 text-slate-600 transition font-bold">‹</a>
                <span class="font-bold text-slate-800 text-sm min-w-35 text-center">
                    {{ $kalender->translatedFormat('F Y') }}
                </span>
                <a href="{{ route('rooms.show', ['id' => $room['id'], 'tahun' => $nextBulan->year, 'bulan' => $nextBulan->month]) }}"
                   class="w-9 h-9 flex items-center justify-center rounded-xl border border-slate-200 hover:border-slate-300 hover:bg-slate-50 text-slate-600 transition font-bold">›</a>
            </div>
        </div>

        {{-- Nama hari --}}
        <div class="grid grid-cols-7 mb-1">
            @foreach(['Min','Sen','Sel','Rab','Kam','Jum','Sab'] as $h)
            <div class="text-center text-xs font-bold text-slate-400 py-2 uppercase tracking-wide">{{ $h }}</div>
            @endforeach
        </div>

        {{-- Grid tanggal --}}
        <div class="grid grid-cols-7 gap-1.5">

            @for($i = 0; $i < $hariAwal; $i++)
            <div></div>
            @endfor

            @for($hari = 1; $hari <= $totalHari; $hari++)
                @php
                    $tglStr  = $kalender->format('Y-m') . '-' . str_pad($hari, 2, '0', STR_PAD_LEFT);
                    $jadwal  = $room['jadwal'][$tglStr] ?? null;
                    $isToday = $tglStr === $today->format('Y-m-d');
                    $isPast  = Carbon::parse($tglStr)->lt($today);

                    if ($jadwal && ($jadwal['tipe'] ?? '') === 'penuh') {
                        $bg = 'bg-red-100 text-red-700 hover:bg-red-200';
                    } elseif ($jadwal && ($jadwal['tipe'] ?? '') === 'sebagian') {
                        $bg = 'bg-amber-100 text-amber-700 hover:bg-amber-200';
                    } elseif ($isPast) {
                        $bg = 'bg-slate-50 text-slate-300 cursor-default';
                    } else {
                        $bg = 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200';
                    }

                    $ring    = $isToday ? 'ring-2 ring-blue-500 ring-offset-1' : '';
                    $tooltip = $jadwal
                        ? ($jadwal['label'] ?? '-') . ' · ' . ($jadwal['waktu'] ?? '-')
                        : ($isPast ? 'Sudah lewat' : 'Tersedia');
                @endphp

                <div class="flex justify-center py-0.5">
                    <span title="{{ $tooltip }}"
                          class="cal-cell w-10 h-10 flex items-center justify-center rounded-xl text-sm font-semibold select-none cursor-default {{ $bg }} {{ $ring }}">
                        {{ $hari }}
                    </span>
                </div>
            @endfor

        </div>
    </div>

    {{-- ===== ROW 3: JADWAL LIST ===== --}}
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 fade-up delay-3">

        <div class="flex items-center justify-between mb-5">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">
                Jadwal Penggunaan – {{ $kalender->translatedFormat('F Y') }}
            </p>
            <span class="text-xs bg-slate-100 text-slate-500 px-3 py-1 rounded-full font-semibold">
                {{ $jadwalBulanIni->count() }} acara
            </span>
        </div>

        @if($jadwalBulanIni->isEmpty())
        <div class="text-center py-12">
            <p class="text-5xl mb-4">📭</p>
            <p class="text-slate-500 font-semibold">Belum ada jadwal bulan ini</p>
            <p class="text-sm text-slate-400 mt-1">Ruangan ini tersedia untuk dibooking</p>
            @if($room['status'] == 'Tersedia')
            <a href="{{ route('bookings.create', ['room_id' => $room['id']]) }}"
               class="inline-block mt-5 bg-slate-900 text-white text-sm font-semibold px-6 py-3 rounded-2xl hover:bg-slate-700 transition">
                ➕ Ajukan Booking Sekarang
            </a>
            @endif
        </div>

        @else
        <div class="divide-y divide-slate-100">
            @foreach($jadwalBulanIni as $tgl => $j)
            @php
                $tanggal    = Carbon::parse($tgl);
                $isPastItem = $tanggal->lt($today);
                $badgeCls   = ($j['tipe'] ?? '') === 'penuh'
                    ? 'bg-red-100 text-red-700'
                    : 'bg-amber-100 text-amber-700';
                $badgeLabel = ($j['tipe'] ?? '') === 'penuh' ? 'Penuh' : 'Sebagian';
            @endphp
            <div class="py-4 flex items-center gap-4 {{ $isPastItem ? 'opacity-50' : '' }}">

                {{-- Tanggal --}}
                <div class="bg-slate-50 border border-slate-100 rounded-2xl px-3 py-2.5 text-center min-w-35 shrink-0">
                    <p class="text-xs text-slate-400 uppercase font-semibold leading-none">
                        {{ $tanggal->translatedFormat('D') }}
                    </p>
                    <p class="text-2xl font-bold text-slate-800 leading-tight">{{ $tanggal->day }}</p>
                </div>

                {{-- Garis vertikal --}}
                <div class="w-px h-10 bg-slate-200 shrink-0"></div>

                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-slate-800 text-sm truncate">{{ $j['label'] ?? '-' }}</p>
                    <p class="text-xs text-slate-400 mt-0.5 flex items-center gap-1">
                        <span>🕐</span> {{ $j['waktu'] ?? '-' }}
                        @if($isPastItem)
                        <span class="ml-2 text-slate-300">· Sudah selesai</span>
                        @endif
                    </p>
                </div>

                {{-- Badge --}}
                <span class="px-3 py-1.5 rounded-2xl text-xs font-bold shrink-0 {{ $badgeCls }}">
                    {{ $badgeLabel }}
                </span>

            </div>
            @endforeach
        </div>
        @endif

    </div>

</div>

@endsection
