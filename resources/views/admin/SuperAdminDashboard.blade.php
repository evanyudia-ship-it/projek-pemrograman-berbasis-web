@extends('layouts.app')

@section('title', 'Dashboard - Smart Classroom')
@section('page_title', 'Dashboard')
@section('page_subtitle')
    Selamat datang kembali, {{ session('user_name', 'Guest') }}
@endsection

@section('content')
<div class="max-w-7xl mx-auto space-y-8 font-sora">

    {{-- ===== STAT CARDS ===== --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 fade-up delay-1">

        {{-- Ruang Tersedia --}}
        <div class="stat-card bg-white rounded-3xl p-5 border border-slate-100 shadow-sm">
            <div class="flex items-start justify-between mb-4">
                <div class="w-11 h-11 bg-emerald-50 rounded-2xl flex items-center justify-center text-xl">🏠</div>
                <span class="text-xs font-semibold bg-emerald-100 text-emerald-700 px-2.5 py-1 rounded-full">
                    {{ $total_ruang > 0 ? round($ruang_tersedia / $total_ruang * 100) : 0 }}%
                </span>
            </div>
            <p class="text-3xl font-bold text-slate-900">{{ $ruang_tersedia }}</p>
            <p class="text-xs text-slate-500 mt-1">Ruang Tersedia</p>
            <p class="text-xs text-emerald-600 mt-3 font-medium">dari {{ $total_ruang }} ruang total</p>
        </div>

        {{-- Booking Aktif --}}
        <div class="stat-card bg-white rounded-3xl p-5 border border-slate-100 shadow-sm">
            <div class="flex items-start justify-between mb-4">
                <div class="w-11 h-11 bg-blue-50 rounded-2xl flex items-center justify-center text-xl">📅</div>
                <span class="text-xs font-semibold bg-blue-100 text-blue-700 px-2.5 py-1 rounded-full">Aktif</span>
            </div>
            <p class="text-3xl font-bold text-slate-900">{{ $booking_aktif }}</p>
            <p class="text-xs text-slate-500 mt-1">Booking Aktif</p>
            <p class="text-xs text-blue-600 mt-3 font-medium">Hari ini</p>
        </div>

        {{-- Menunggu Approval --}}
        <div class="stat-card bg-white rounded-3xl p-5 border border-slate-100 shadow-sm">
            <div class="flex items-start justify-between mb-4">
                <div class="w-11 h-11 bg-amber-50 rounded-2xl flex items-center justify-center text-xl">⏳</div>
                <span class="text-xs font-semibold bg-amber-100 text-amber-700 px-2.5 py-1 rounded-full">Perlu dicek</span>
            </div>
            <p class="text-3xl font-bold text-slate-900">{{ $menunggu_approval }}</p>
            <p class="text-xs text-slate-500 mt-1">Menunggu Approval</p>
            <a href="{{ route('admin.approvals') }}"
               class="text-xs text-amber-600 mt-3 font-medium block hover:underline">
                Lihat semua →
            </a>
        </div>

        {{-- Reputation Point --}}
        @php
            $rp = $reputation_point;
            if ($rp >= 80) {
                $rpLabel = 'Trusted User';
                $rpColor = 'from-emerald-400 to-teal-400';
                $rpBadgeBg = 'bg-emerald-500/20';
                $rpBadgeText = 'text-emerald-300';
            } elseif ($rp >= 50) {
                $rpLabel = 'Normal';
                $rpColor = 'from-blue-400 to-indigo-400';
                $rpBadgeBg = 'bg-blue-500/20';
                $rpBadgeText = 'text-blue-300';
            } elseif ($rp >= 30) {
                $rpLabel = 'Dibatasi';
                $rpColor = 'from-amber-400 to-orange-400';
                $rpBadgeBg = 'bg-amber-500/20';
                $rpBadgeText = 'text-amber-300';
            } else {
                $rpLabel = 'Diblokir';
                $rpColor = 'from-red-400 to-rose-400';
                $rpBadgeBg = 'bg-red-500/20';
                $rpBadgeText = 'text-red-300';
            }
        @endphp

        <a href="{{ route('reputation.index') }}"
           class="stat-card bg-slate-950 rounded-3xl p-5 border border-slate-800 shadow-sm block hover:border-slate-600 transition">
            <div class="flex items-start justify-between mb-4">
                <div class="w-11 h-11 bg-white/10 rounded-2xl flex items-center justify-center text-xl">⭐</div>
                <span class="text-xs font-semibold {{ $rpBadgeBg }} {{ $rpBadgeText }} px-2.5 py-1 rounded-full">
                    {{ $rpLabel }}
                </span>
            </div>
            <p class="text-3xl font-bold text-white">{{ $rp }}</p>
            <p class="text-xs text-slate-400 mt-1">Reputation Points</p>
            <div class="mt-3 h-1.5 bg-white/10 rounded-full overflow-hidden">
                <div class="h-full bg-linear-to-r {{ $rpColor }} rounded-full transition-all duration-700"
                     style="width: {{ $rp }}%"></div>
            </div>
            <div class="mt-2.5 flex justify-between items-center">
                <p class="text-xs text-slate-400">{{ $rp }}/100 poin</p>
                <p class="text-xs text-slate-500 hover:text-slate-300 transition">Lihat detail →</p>
            </div>
        </a>

    </div>

    {{-- ===== MAIN GRID: Jadwal + Sidebar ===== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 fade-up delay-2">

        {{-- KIRI: Jadwal Hari Ini --}}
        <div class="lg:col-span-2 space-y-4">

            <div class="flex items-center justify-between">
                <h2 class="text-base font-bold text-slate-800">Jadwal Hari Ini</h2>
                <a href="{{ route('bookings.index') }}"
                   class="text-xs text-blue-600 hover:underline font-medium">Lihat semua →</a>
            </div>

            @forelse($jadwal_hari_ini as $jadwal)
            <div class="room-card bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">

                <div class="relative h-40 overflow-hidden">
                    <img src="{{ $jadwal['foto'] }}"
                         alt="{{ $jadwal['ruangan'] }}"
                         class="room-img w-full h-full object-cover">
                    <div class="absolute inset-0 bg-linear-to-t from-black/60 via-black/10 to-transparent"></div>

                    <span class="absolute top-3 right-3 px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1.5
                        {{ $jadwal['status'] == 'Confirmed'
                            ? 'bg-emerald-400/90 text-emerald-900'
                            : 'bg-amber-400/90 text-amber-900' }}">
                        <span class="w-1.5 h-1.5 rounded-full
                            {{ $jadwal['status'] == 'Confirmed' ? 'bg-emerald-700' : 'bg-amber-700' }}"></span>
                        {{ $jadwal['status'] }}
                    </span>

                    <span class="absolute top-3 left-3 w-7 h-7 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center text-white font-bold text-xs">
                        #{{ $loop->iteration }}
                    </span>

                    <div class="absolute bottom-3 left-4 right-4">
                        <p class="text-white font-bold text-base leading-tight">{{ $jadwal['ruangan'] }}</p>
                        <p class="text-white/70 text-xs font-mono mt-0.5">{{ $jadwal['kode'] }}</p>
                    </div>
                </div>

                <div class="p-5 flex items-center justify-between">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2 text-sm text-slate-700">
                            <span class="text-base">🕐</span>
                            <span class="font-semibold">{{ $jadwal['waktu'] }}</span>
                            <span class="text-slate-400 text-xs">({{ $jadwal['durasi'] }})</span>
                        </div>
                        <p class="text-xs text-slate-500 pl-7">{{ $jadwal['keperluan'] }}</p>
                    </div>
                    <a href="{{ route('rooms.show', $jadwal['id']) }}"
                       class="text-xs font-semibold text-blue-600 hover:text-blue-700 whitespace-nowrap">
                        Detail →
                    </a>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-10 text-center text-slate-400">
                <p class="text-4xl mb-3">📭</p>
                <p class="text-sm">Tidak ada jadwal hari ini.</p>
            </div>
            @endforelse

        </div>

        {{-- KANAN: Notifikasi + Quick Action --}}
        <div class="space-y-4">

            {{-- Notifikasi --}}
            <div>
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-base font-bold text-slate-800">Notifikasi</h2>
                    @if(count($notifikasi) > 0)
                    <span class="text-xs bg-red-100 text-red-600 font-bold px-2 py-0.5 rounded-full">
                        {{ count($notifikasi) }} baru
                    </span>
                    @endif
                </div>

                <div class="space-y-3">
                    @forelse($notifikasi as $notif)   {{-- Ubah ke @forelse biar konsisten --}}
                        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 flex gap-3">
                            <span class="text-xl shrink-0 mt-0.5">{{ $notif['icon'] }}</span>
                            <div>
                                <p class="text-xs text-slate-700 leading-relaxed font-medium">{{ $notif['pesan'] }}</p>
                                <p class="text-xs text-slate-400 mt-1">{{ $notif['waktu'] }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 text-center py-3">Tidak ada notifikasi baru.</p>
                    @endforelse
                </div>
            </div>

            {{-- Kapasitas Ruang --}}
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-5">
                <h2 class="text-sm font-bold text-slate-800 mb-4">Kapasitas Ruang</h2>
                <div class="space-y-3">
                    <div>
                        <div class="flex justify-between text-xs text-slate-500 mb-1.5">
                            <span>Tersedia</span>
                            <span class="font-semibold text-emerald-600">{{ $ruang_tersedia }} ruang</span>
                        </div>
                        <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-emerald-400 rounded-full"
                                 style="width:{{ $total_ruang > 0 ? round($ruang_tersedia / $total_ruang * 100) : 0 }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-xs text-slate-500 mb-1.5">
                            <span>Dipakai</span>
                            <span class="font-semibold text-red-500">{{ $total_ruang - $ruang_tersedia }} ruang</span>
                        </div>
                        <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-red-400 rounded-full"
                                style="width: {{ $total_ruang > 0 ? round(($total_ruang - $ruang_tersedia) / $total_ruang * 100) : 0 }}%"></div>
                        </div>
                    </div>
                    <p class="text-xs text-slate-400 pt-1 text-right">Total: {{ $total_ruang }} ruang kampus</p>
                </div>
            </div>

            {{-- Quick Booking --}}
            <a href="{{ route('bookings.create') }}"
               class="block bg-slate-950 hover:bg-slate-800 transition text-white rounded-3xl p-6 text-center group">
                <span class="text-3xl block mb-2 group-hover:scale-110 transition-transform duration-200">➕</span>
                <span class="font-bold text-sm">Ajukan Booking Baru</span>
                <p class="text-xs text-slate-400 mt-1">Cek ketersediaan & pesan sekarang</p>
            </a>

            {{-- Akses Cepat --}}
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-5">
                <h2 class="text-sm font-bold text-slate-800 mb-3">Akses Cepat</h2>
                <div class="space-y-2">
                    <a href="{{ route('rooms.index') }}"
                       class="flex items-center gap-3 p-3 rounded-2xl hover:bg-slate-50 transition text-sm text-slate-700 font-medium">
                        <span class="w-8 h-8 bg-blue-50 rounded-xl flex items-center justify-center">🏫</span>
                        Ketersediaan Ruang
                    </a>
                    <a href="{{ route('admin.approvals') }}"
                       class="flex items-center gap-3 p-3 rounded-2xl hover:bg-slate-50 transition text-sm text-slate-700 font-medium">
                        <span class="w-8 h-8 bg-amber-50 rounded-xl flex items-center justify-center">✅</span>
                        Approval Admin
                        @if($menunggu_approval > 0)
                        <span class="ml-auto text-xs bg-amber-100 text-amber-700 font-bold px-2 py-0.5 rounded-full">
                            {{ $menunggu_approval }}
                        </span>
                        @endif
                    </a>
                    <a href="{{ route('bookings.index') }}"
                       class="flex items-center gap-3 p-3 rounded-2xl hover:bg-slate-50 transition text-sm text-slate-700 font-medium">
                        <span class="w-8 h-8 bg-slate-100 rounded-xl flex items-center justify-center">📋</span>
                        Riwayat Booking
                    </a>
                </div>
            </div>

        </div>

    </div>

</div>

@endsection