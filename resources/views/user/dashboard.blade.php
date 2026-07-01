{{-- ============================================================ --}}
{{-- USER DASHBOARD (user/dashboard.blade.php) --}}
{{-- ============================================================ --}}

@extends('layouts.app')

@section('title', 'Dashboard - Smart Classroom')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Selamat datang kembali!')

@section('content')

<div class="max-w-7xl mx-auto font-sora space-y-6">

    {{-- ============================================================ --}}
    {{-- GREETING SECTION --}}
    {{-- ============================================================ --}}
    <div class="bg-gradient-to-br from-indigo-600 to-blue-600 dark:from-indigo-800 dark:to-blue-800 rounded-2xl p-6 md:p-8 text-white shadow-lg">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center text-3xl">
                    👋
                </div>
                <div>
                    <p class="text-xs font-semibold text-white/70 uppercase tracking-widest">
                        {{ now()->translatedFormat('l, d F Y') }}
                    </p>
                    <h1 class="text-2xl md:text-3xl font-bold mt-0.5">
                        Halo, {{ session('user_name', Auth::user()->name ?? 'Pengunjung') }}
                    </h1>
                    <p class="text-sm text-white/80 mt-1">
                        {{ session('user_role') === 'dosen' ? '📚 Selamat mengajar hari ini.' : '💪 Semangat menggunakan sistem booking ruangan hari ini.' }}
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <span class="px-4 py-2 rounded-xl bg-white/20 backdrop-blur text-sm font-semibold border border-white/10">
                    🏷️ {{ ucfirst(session('user_role', 'user')) }}
                </span>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- STATISTICS CARDS --}}
    {{-- ============================================================ --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Approved --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-5 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Booking Disetujui</p>
                    <p class="text-2xl font-extrabold text-emerald-600 dark:text-emerald-400 mt-1">{{ $booking_aktif ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center text-emerald-600 dark:text-emerald-400 group-hover:scale-110 transition">
                    <i class="fas fa-check-circle text-lg"></i>
                </div>
            </div>
            <div class="mt-2">
                <span class="text-xs text-slate-400 dark:text-slate-500">Status: approved</span>
            </div>
        </div>

        {{-- Completed --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-5 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Booking Selesai</p>
                    <p class="text-2xl font-extrabold text-blue-600 dark:text-blue-400 mt-1">{{ $booking_selesai ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center text-blue-600 dark:text-blue-400 group-hover:scale-110 transition">
                    <i class="fas fa-flag-checkered text-lg"></i>
                </div>
            </div>
            <div class="mt-2">
                <span class="text-xs text-slate-400 dark:text-slate-500">Total riwayat</span>
            </div>
        </div>

        {{-- Pending --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-5 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Menunggu Approval</p>
                    <p class="text-2xl font-extrabold text-amber-600 dark:text-amber-400 mt-1">{{ $booking_pending ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900/50 flex items-center justify-center text-amber-600 dark:text-amber-400 group-hover:scale-110 transition">
                    <i class="fas fa-clock text-lg"></i>
                </div>
            </div>
            <div class="mt-2">
                <span class="text-xs text-amber-600 dark:text-amber-400 animate-pulse inline-flex items-center gap-1">
                    <i class="fas fa-circle text-[6px]"></i> Perlu diproses
                </span>
            </div>
        </div>

        {{-- No Show --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-5 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">No Show</p>
                    <p class="text-2xl font-extrabold text-red-600 dark:text-red-400 mt-1">{{ $booking_no_show ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-red-100 dark:bg-red-900/50 flex items-center justify-center text-red-600 dark:text-red-400 group-hover:scale-110 transition">
                    <i class="fas fa-hourglass-end text-lg"></i>
                </div>
            </div>
            <div class="mt-2">
                <span class="text-xs text-red-500 dark:text-red-400">⚠️ Pengaruhi reputasi</span>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- REPUTATION POINT --}}
    {{-- ============================================================ --}}
    @php
        $rp = $reputation_point ?? 100;
        $rpLabel = $rp >= 80 ? '🌟 Trusted User' : ($rp >= 50 ? '⭐ Normal' : ($rp >= 30 ? '⚠️ Dibatasi' : '🚫 Diblokir'));
        $rpColor = $rp >= 80 ? 'text-emerald-600 dark:text-emerald-400' : ($rp >= 50 ? 'text-blue-600 dark:text-blue-400' : ($rp >= 30 ? 'text-amber-600 dark:text-amber-400' : 'text-red-600 dark:text-red-400'));
        $barColor = $rp >= 80 ? 'bg-emerald-500' : ($rp >= 50 ? 'bg-blue-500' : ($rp >= 30 ? 'bg-amber-500' : 'bg-red-500'));
    @endphp

    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-2xl text-indigo-600 dark:text-indigo-400">
                    🏆
                </div>
                <div>
                    <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Reputation Point</p>
                    <h3 class="text-xl font-extrabold {{ $rpColor }} mt-0.5">
                        {{ $rpLabel }}
                    </h3>
                </div>
            </div>

            <div class="text-right">
                <div class="text-3xl font-extrabold {{ $rpColor }}">{{ $rp }}</div>
                <p class="text-xs text-slate-400 dark:text-slate-500">/ 100 poin</p>
            </div>
        </div>

        <div class="mt-4">
            <div class="flex justify-between text-xs text-slate-400 dark:text-slate-500 mb-1">
                <span>0</span>
                <span>50</span>
                <span>100</span>
            </div>
            <div class="w-full h-3 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden relative">
                <div class="h-full {{ $barColor }} rounded-full transition-all duration-1000" style="width: {{ min($rp, 100) }}%"></div>
                <div class="absolute top-1/2 -translate-y-1/2 w-5 h-5 rounded-full bg-white dark:bg-slate-800 border-2 {{ $rp >= 80 ? 'border-emerald-500' : ($rp >= 50 ? 'border-blue-500' : ($rp >= 30 ? 'border-amber-500' : 'border-red-500')) }} shadow-md"
                     style="left: calc({{ min($rp, 100) }}% - 10px);">
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- MAIN CONTENT GRID --}}
    {{-- ============================================================ --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- SCHEDULE --}}
        <div class="xl:col-span-2 bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700 bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-indigo-950/30 dark:to-blue-950/30">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                            <i class="fas fa-calendar-day text-lg"></i>
                        </div>
                        <div>
                            <h2 class="font-bold text-slate-800 dark:text-white">Jadwal Booking Saya</h2>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Daftar booking hari ini</p>
                        </div>
                    </div>
                    <a href="{{ route('bookings.index') }}" class="text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:underline flex items-center gap-1">
                        Lihat semua <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                </div>
            </div>

            <div class="p-6 space-y-4 max-h-[500px] overflow-y-auto">
                @forelse($jadwal_hari_ini ?? [] as $jadwal)
                    <div class="group p-4 rounded-xl border border-slate-200 dark:border-slate-700 hover:border-indigo-200 dark:hover:border-indigo-800 hover:shadow-md transition bg-white dark:bg-slate-800/50">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-bold {{ $jadwal['status'] === 'approved' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300' : ($jadwal['status'] === 'pending' ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-300' : 'bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300') }}">
                                        {{ ucfirst($jadwal['status'] ?? 'Unknown') }}
                                    </span>
                                    @if(isset($jadwal['prioritas']) && $jadwal['prioritas'] === 'High')
                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300">
                                        <i class="fas fa-arrow-up mr-1"></i> Prioritas
                                    </span>
                                    @endif
                                </div>

                                <h3 class="font-bold text-slate-800 dark:text-white mt-2 text-lg">
                                    {{ $jadwal['ruangan'] ?? '-' }}
                                </h3>

                                <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-sm text-slate-500 dark:text-slate-400 mt-1">
                                    <span class="flex items-center gap-1">
                                        <i class="fas fa-hashtag text-xs"></i> {{ $jadwal['kode'] ?? '-' }}
                                    </span>
                                    <span class="w-px h-3 bg-slate-300 dark:bg-slate-600"></span>
                                    <span class="flex items-center gap-1">
                                        <i class="far fa-clock text-xs"></i> {{ $jadwal['waktu'] ?? '-' }}
                                    </span>
                                    <span class="w-px h-3 bg-slate-300 dark:bg-slate-600"></span>
                                    <span class="flex items-center gap-1">
                                        <i class="fas fa-hourglass-half text-xs"></i> {{ $jadwal['durasi'] ?? '-' }}
                                    </span>
                                </div>

                                <p class="text-sm text-slate-600 dark:text-slate-300 mt-2 line-clamp-2">
                                    {{ $jadwal['keperluan'] ?? '-' }}
                                </p>
                            </div>

                            <a href="{{ route('bookings.show', $jadwal['id'] ?? 0) }}"
                               class="px-3.5 py-2 rounded-xl bg-indigo-50 dark:bg-indigo-950/30 hover:bg-indigo-100 dark:hover:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 text-xs font-semibold transition flex items-center gap-1.5 whitespace-nowrap">
                                Detail <i class="fas fa-arrow-right text-[10px]"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-12 text-center">
                        <div class="w-20 h-20 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-4xl mb-4">
                            📅
                        </div>
                        <p class="font-semibold text-slate-600 dark:text-slate-400">Tidak ada jadwal hari ini</p>
                        <p class="text-sm text-slate-400 dark:text-slate-500 mt-1">Santai dulu, atau ajukan booking baru</p>
                        <a href="{{ route('bookings.create') }}" class="mt-4 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition shadow-sm shadow-indigo-200 dark:shadow-indigo-900/30">
                            <i class="fas fa-plus mr-1.5"></i> Ajukan Booking
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- SIDEBAR --}}
        <div class="space-y-6">

            {{-- Quick Booking --}}
            <a href="{{ route('bookings.create') }}"
               class="block bg-gradient-to-br from-indigo-600 to-blue-600 dark:from-indigo-800 dark:to-blue-800 rounded-2xl p-6 text-white shadow-lg hover:shadow-xl transition group">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center text-3xl group-hover:scale-110 transition">
                        ➕
                    </div>
                    <div>
                        <h3 class="font-extrabold text-lg">Ajukan Booking Baru</h3>
                        <p class="text-sm text-white/80 mt-0.5">Cek ketersediaan dan pesan ruangan</p>
                    </div>
                </div>
                <div class="mt-4 flex items-center justify-end text-sm text-white/70 group-hover:text-white transition">
                    <span class="flex items-center gap-1">
                        Mulai <i class="fas fa-arrow-right text-xs"></i>
                    </span>
                </div>
            </a>

            {{-- Quick Access --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-700/30">
                    <h2 class="font-bold text-slate-800 dark:text-white flex items-center gap-2">
                        <i class="fas fa-bolt text-indigo-500"></i> Akses Cepat
                    </h2>
                </div>
                <div class="p-3 space-y-1.5">
                    <a href="{{ route('rooms.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700/50 text-slate-700 dark:text-slate-300 font-semibold text-sm transition group">
                        <span class="w-8 h-8 rounded-lg bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400 group-hover:scale-110 transition">
                            <i class="fas fa-door-open"></i>
                        </span>
                        Ketersediaan Ruang
                        <span class="ml-auto text-slate-400 text-xs"><i class="fas fa-chevron-right"></i></span>
                    </a>

                    <a href="{{ route('schedule.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700/50 text-slate-700 dark:text-slate-300 font-semibold text-sm transition group">
                        <span class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center text-blue-600 dark:text-blue-400 group-hover:scale-110 transition">
                            <i class="fas fa-calendar-alt"></i>
                        </span>
                        Jadwal Ruangan
                        <span class="ml-auto text-slate-400 text-xs"><i class="fas fa-chevron-right"></i></span>
                    </a>

                    <a href="{{ route('bookings.history') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700/50 text-slate-700 dark:text-slate-300 font-semibold text-sm transition group">
                        <span class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center text-emerald-600 dark:text-emerald-400 group-hover:scale-110 transition">
                            <i class="fas fa-history"></i>
                        </span>
                        Riwayat Booking
                        <span class="ml-auto text-slate-400 text-xs"><i class="fas fa-chevron-right"></i></span>
                    </a>

                    {{-- ✅ PERBAIKAN: Gunakan route('profile.index') --}}
                    <a href="{{ route('profile.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700/50 text-slate-700 dark:text-slate-300 font-semibold text-sm transition group">
                        <span class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-900/50 flex items-center justify-center text-purple-600 dark:text-purple-400 group-hover:scale-110 transition">
                            <i class="fas fa-user-cog"></i>
                        </span>
                        Pengaturan Profil
                        <span class="ml-auto text-slate-400 text-xs"><i class="fas fa-chevron-right"></i></span>
                    </a>
                </div>
            </div>

            {{-- Quick Stats --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-5">
                <h2 class="font-bold text-slate-800 dark:text-white flex items-center gap-2 mb-3">
                    <i class="fas fa-chart-simple text-indigo-500"></i> Ringkasan
                </h2>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between py-2 border-b border-slate-100 dark:border-slate-700">
                        <span class="text-slate-500 dark:text-slate-400">Total Booking</span>
                        <span class="font-semibold text-slate-800 dark:text-white">{{ ($booking_aktif ?? 0) + ($booking_selesai ?? 0) + ($booking_pending ?? 0) + ($booking_no_show ?? 0) }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-slate-100 dark:border-slate-700">
                        <span class="text-slate-500 dark:text-slate-400">Aktif Hari Ini</span>
                        <span class="font-semibold text-emerald-600 dark:text-emerald-400">{{ count($jadwal_hari_ini ?? []) }}</span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-slate-500 dark:text-slate-400">Reputation</span>
                        <span class="font-semibold {{ $rpColor }}">{{ $rp }} / 100</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- ============================================================ --}}
{{-- STYLES --}}
{{-- ============================================================ --}}
<style>
/* Dark mode scrollbar untuk jadwal */
.dark .overflow-y-auto::-webkit-scrollbar {
    width: 5px;
}
.dark .overflow-y-auto::-webkit-scrollbar-track {
    background: #1e293b;
    border-radius: 9999px;
}
.dark .overflow-y-auto::-webkit-scrollbar-thumb {
    background: #475569;
    border-radius: 9999px;
}

/* Line clamp untuk teks panjang */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Animasi progress bar */
@keyframes progressPulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.6; }
}
.h-3 .h-full {
    animation: progressPulse 2s ease-in-out infinite;
}
</style>

@endsection
