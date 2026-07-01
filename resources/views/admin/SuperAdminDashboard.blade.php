@extends('layouts.app')

@section('title', 'Super Admin Dashboard - Smart Classroom')
@section('page_title', 'Super Admin Dashboard')
@section('page_subtitle', 'Panel kontrol penuh sistem Smart Classroom')

@php
    use App\Models\Booking;
@endphp

@section('content')

<div class="max-w-7xl mx-auto font-sora space-y-6">

    {{-- ============================================================ --}}
    {{-- GREETING SECTION --}}
    {{-- ============================================================ --}}
    <div class="bg-gradient-to-br from-indigo-700 to-purple-700 dark:from-indigo-900 dark:to-purple-900 rounded-2xl p-6 md:p-8 text-white shadow-lg">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center text-3xl">
                    👑
                </div>
                <div>
                    <p class="text-xs font-semibold text-white/70 uppercase tracking-widest">
                        {{ now()->translatedFormat('l, d F Y') }}
                    </p>
                    <h1 class="text-2xl md:text-3xl font-bold mt-0.5">
                        Halo, {{ session('user_name', Auth::user()->name ?? 'Super Admin') }}
                    </h1>
                    <p class="text-sm text-white/80 mt-1 flex items-center gap-2">
                        <span>🛡️ Panel kontrol penuh sistem Smart Classroom</span>
                        @if($menunggu_approval > 0)
                        <span class="px-3 py-1 rounded-full bg-amber-500/30 text-amber-200 text-xs font-bold animate-pulse border border-amber-400/30">
                            {{ $menunggu_approval }} approval pending
                        </span>
                        @endif
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <span class="px-4 py-2 rounded-xl bg-white/20 backdrop-blur text-sm font-semibold border border-white/10 flex items-center gap-2">
                    <i class="fas fa-crown"></i> Super Admin
                </span>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- STATISTICS CARDS --}}
    {{-- ============================================================ --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- Available Rooms --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-5 shadow-sm hover:shadow-md transition group">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Ruang Tersedia</p>
                    <p class="text-2xl font-extrabold text-emerald-600 dark:text-emerald-400 mt-1">{{ $ruang_tersedia }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center text-emerald-600 dark:text-emerald-400 group-hover:scale-110 transition">
                    <i class="fas fa-door-open text-lg"></i>
                </div>
            </div>
            <div class="mt-2 flex items-center justify-between">
                <span class="text-xs text-slate-400 dark:text-slate-500">dari {{ $total_ruang }} ruang</span>
                <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400">
                    {{ $total_ruang > 0 ? round($ruang_tersedia / $total_ruang * 100) : 0 }}%
                </span>
            </div>
            <div class="mt-2 h-1.5 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                <div class="h-full bg-emerald-500 rounded-full transition-all duration-700" style="width: {{ $total_ruang > 0 ? round($ruang_tersedia / $total_ruang * 100) : 0 }}%"></div>
            </div>
        </div>

        {{-- Active Bookings --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-5 shadow-sm hover:shadow-md transition group">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Booking Aktif</p>
                    <p class="text-2xl font-extrabold text-blue-600 dark:text-blue-400 mt-1">{{ $booking_aktif }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center text-blue-600 dark:text-blue-400 group-hover:scale-110 transition">
                    <i class="fas fa-calendar-check text-lg"></i>
                </div>
            </div>
            <div class="mt-2">
                <span class="text-xs text-blue-600 dark:text-blue-400 font-medium">Hari ini</span>
            </div>
        </div>

        {{-- Pending Approval --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-5 shadow-sm hover:shadow-md transition group">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Menunggu Approval</p>
                    <p class="text-2xl font-extrabold text-amber-600 dark:text-amber-400 mt-1">{{ $menunggu_approval }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900/50 flex items-center justify-center text-amber-600 dark:text-amber-400 group-hover:scale-110 transition">
                    <i class="fas fa-clock text-lg"></i>
                </div>
            </div>
            @if($menunggu_approval > 0)
            <div class="mt-2">
                <span class="text-xs text-amber-600 dark:text-amber-400 animate-pulse inline-flex items-center gap-1">
                    <i class="fas fa-circle text-[6px]"></i> Perlu ditindaklanjuti
                </span>
            </div>
            @endif
            <div class="mt-3">
                <a href="{{ route('admin.approvals.index') }}" class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:underline inline-flex items-center gap-1">
                    Proses sekarang <i class="fas fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>

        {{-- Reputation Point --}}
        @php
            $rp = $reputation_point ?? 100;
            $rpLabel = $rp >= 80 ? 'Trusted User' : ($rp >= 50 ? 'Normal' : ($rp >= 30 ? 'Dibatasi' : 'Diblokir'));
            $rpColor = $rp >= 80 ? 'text-emerald-600 dark:text-emerald-400' : ($rp >= 50 ? 'text-blue-600 dark:text-blue-400' : ($rp >= 30 ? 'text-amber-600 dark:text-amber-400' : 'text-red-600 dark:text-red-400'));
            $barColor = $rp >= 80 ? 'bg-emerald-500' : ($rp >= 50 ? 'bg-blue-500' : ($rp >= 30 ? 'bg-amber-500' : 'bg-red-500'));
            $rpBg = $rp >= 80 ? 'bg-emerald-100 dark:bg-emerald-900/50' : ($rp >= 50 ? 'bg-blue-100 dark:bg-blue-900/50' : ($rp >= 30 ? 'bg-amber-100 dark:bg-amber-900/50' : 'bg-red-100 dark:bg-red-900/50'));
        @endphp

        <a href="{{ route('reputation.index') }}" class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-5 shadow-sm hover:shadow-md transition group block">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Reputation Points</p>
                    <p class="text-2xl font-extrabold {{ $rpColor }} mt-1">{{ $rp }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl {{ $rpBg }} flex items-center justify-center {{ $rpColor }} group-hover:scale-110 transition">
                    <i class="fas fa-star text-lg"></i>
                </div>
            </div>
            <div class="mt-2 flex items-center justify-between">
                <span class="text-xs text-slate-400 dark:text-slate-500">{{ $rpLabel }}</span>
                <span class="text-xs font-bold {{ $rpColor }}">{{ $rp }}/100</span>
            </div>
            <div class="mt-2 h-1.5 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                <div class="h-full {{ $barColor }} rounded-full transition-all duration-700" style="width: {{ min($rp, 100) }}%"></div>
            </div>
        </a>

    </div>

    {{-- ============================================================ --}}
    {{-- SECONDARY STATISTICS --}}
    {{-- ============================================================ --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-4 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                    <i class="fas fa-users text-sm"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-400 dark:text-slate-500">Total User</p>
                    <p class="text-xl font-bold text-slate-800 dark:text-white">{{ $total_users ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-4 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center text-blue-600 dark:text-blue-400">
                    <i class="fas fa-chalkboard-user text-sm"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-400 dark:text-slate-500">Dosen</p>
                    <p class="text-xl font-bold text-blue-600 dark:text-blue-400">{{ $total_dosen ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-4 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                    <i class="fas fa-graduation-cap text-sm"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-400 dark:text-slate-500">Mahasiswa</p>
                    <p class="text-xl font-bold text-emerald-600 dark:text-emerald-400">{{ $total_mahasiswa ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-4 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-purple-100 dark:bg-purple-900/50 flex items-center justify-center text-purple-600 dark:text-purple-400">
                    <i class="fas fa-book text-sm"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-400 dark:text-slate-500">Total Booking</p>
                    <p class="text-xl font-bold text-purple-600 dark:text-purple-400">{{ Booking::count() }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- QUICK ACTION BAR --}}
    {{-- ============================================================ --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <a href="{{ route('admin.approvals.index') }}"
           class="bg-gradient-to-br from-amber-500 to-orange-500 dark:from-amber-600 dark:to-orange-600 rounded-2xl p-4 text-white shadow-md hover:shadow-lg transition group">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center text-xl group-hover:scale-110 transition">
                    ✅
                </div>
                <div>
                    <h3 class="font-bold text-sm">Approval</h3>
                    <p class="text-xs text-white/80">{{ $menunggu_approval }} pending</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.rooms.index') }}"
           class="bg-gradient-to-br from-indigo-500 to-purple-500 dark:from-indigo-600 dark:to-purple-600 rounded-2xl p-4 text-white shadow-md hover:shadow-lg transition group">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center text-xl group-hover:scale-110 transition">
                    🏗️
                </div>
                <div>
                    <h3 class="font-bold text-sm">Manajemen Ruang</h3>
                    <p class="text-xs text-white/80">Kelola ruangan</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.facilities.index') }}"
           class="bg-gradient-to-br from-emerald-500 to-teal-500 dark:from-emerald-600 dark:to-teal-600 rounded-2xl p-4 text-white shadow-md hover:shadow-lg transition group">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center text-xl group-hover:scale-110 transition">
                    📦
                </div>
                <div>
                    <h3 class="font-bold text-sm">Fasilitas</h3>
                    <p class="text-xs text-white/80">Kelola fasilitas</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.faculties.index') }}"
           class="bg-gradient-to-br from-rose-500 to-pink-500 dark:from-rose-600 dark:to-pink-600 rounded-2xl p-4 text-white shadow-md hover:shadow-lg transition group">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center text-xl group-hover:scale-110 transition">
                    🏛️
                </div>
                <div>
                    <h3 class="font-bold text-sm">Fakultas</h3>
                    <p class="text-xs text-white/80">Kelola fakultas</p>
                </div>
            </div>
        </a>
    </div>

    {{-- ============================================================ --}}
    {{-- MAIN CONTENT GRID --}}
    {{-- ============================================================ --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- TODAY'S SCHEDULE --}}
        <div class="xl:col-span-2 bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700 bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-indigo-950/30 dark:to-blue-950/30">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                            <i class="fas fa-calendar-day text-lg"></i>
                        </div>
                        <div>
                            <h2 class="font-bold text-slate-800 dark:text-white">Jadwal Hari Ini</h2>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Daftar booking yang berlangsung hari ini</p>
                        </div>
                    </div>
                    <a href="{{ route('bookings.index') }}" class="text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:underline flex items-center gap-1">
                        Lihat semua <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                </div>
            </div>

            <div class="p-6 space-y-4 max-h-[600px] overflow-y-auto">
                @forelse($jadwal_hari_ini ?? [] as $jadwal)
                    <div class="group p-4 rounded-xl border border-slate-200 dark:border-slate-700 hover:border-indigo-200 dark:hover:border-indigo-800 hover:shadow-md transition bg-white dark:bg-slate-800/50">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-bold
                                        {{ ($jadwal['status'] ?? '') === 'Disetujui' || ($jadwal['status'] ?? '') === 'approved' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300' :
                                           (($jadwal['status'] ?? '') === 'pending' ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-300' :
                                           'bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300') }}">
                                        {{ ucfirst($jadwal['status'] ?? 'Active') }}
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
                                        <i class="far fa-clock text-xs"></i> {{ $jadwal['waktu'] ?? '-' }}
                                    </span>
                                    <span class="w-px h-3 bg-slate-300 dark:bg-slate-600"></span>
                                    <span class="flex items-center gap-1">
                                        <i class="fas fa-user text-xs"></i> {{ $jadwal['pemohon'] ?? '-' }}
                                    </span>
                                    <span class="w-px h-3 bg-slate-300 dark:bg-slate-600"></span>
                                    <span class="flex items-center gap-1">
                                        <i class="fas fa-hashtag text-xs"></i> {{ $jadwal['kode'] ?? '-' }}
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

                            <a href="{{ route('rooms.show', $jadwal['id'] ?? 0) }}"
                               class="px-3.5 py-2 rounded-xl bg-indigo-50 dark:bg-indigo-950/30 hover:bg-indigo-100 dark:hover:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 text-xs font-semibold transition flex items-center gap-1.5 whitespace-nowrap">
                                Detail <i class="fas fa-arrow-right text-[10px]"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-16 text-center">
                        <div class="w-24 h-24 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-5xl mb-4">
                            📅
                        </div>
                        <p class="font-semibold text-slate-600 dark:text-slate-400 text-lg">Tidak ada jadwal hari ini</p>
                        <p class="text-sm text-slate-400 dark:text-slate-500 mt-1">Semua ruangan kosong hari ini</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- SIDEBAR --}}
        <div class="space-y-6">

            {{-- Notifications --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-700/30">
                    <div class="flex items-center justify-between">
                        <h2 class="font-bold text-slate-800 dark:text-white flex items-center gap-2">
                            <i class="fas fa-bell text-indigo-500"></i> Notifikasi
                        </h2>
                        @if(count($notifikasi ?? []) > 0)
                        <span class="text-xs bg-red-500 text-white font-bold px-2.5 py-0.5 rounded-full animate-pulse">
                            {{ count($notifikasi) }}
                        </span>
                        @endif
                    </div>
                </div>
                <div class="p-4 space-y-3 max-h-[300px] overflow-y-auto">
                    @forelse($notifikasi ?? [] as $notif)
                        <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-700/30 border border-slate-100 dark:border-slate-700 flex gap-3 hover:bg-slate-100 dark:hover:bg-slate-700/50 transition">
                            <span class="text-xl shrink-0 mt-0.5">{{ $notif['icon'] ?? '📌' }}</span>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed font-medium">
                                    {{ $notif['pesan'] ?? '-' }}
                                </p>
                                <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-1">
                                    {{ $notif['waktu'] ?? '-' }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6">
                            <p class="text-4xl mb-2">🔕</p>
                            <p class="text-xs text-slate-400 dark:text-slate-500">Tidak ada notifikasi baru</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Room Capacity Overview --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-5">
                <h2 class="font-bold text-slate-800 dark:text-white flex items-center gap-2 mb-4">
                    <i class="fas fa-chart-pie text-indigo-500"></i> Kapasitas Ruang
                </h2>
                <div class="space-y-3">
                    <div>
                        <div class="flex justify-between text-xs text-slate-500 dark:text-slate-400 mb-1">
                            <span>Tersedia</span>
                            <span class="font-semibold text-emerald-600 dark:text-emerald-400">{{ $ruang_tersedia }} ruang</span>
                        </div>
                        <div class="h-2 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                            <div class="h-full bg-emerald-500 rounded-full transition-all duration-700"
                                 style="width: {{ $total_ruang > 0 ? round($ruang_tersedia / $total_ruang * 100) : 0 }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-xs text-slate-500 dark:text-slate-400 mb-1">
                            <span>Dipakai / Maintenance</span>
                            <span class="font-semibold text-red-500 dark:text-red-400">{{ $total_ruang - $ruang_tersedia }} ruang</span>
                        </div>
                        <div class="h-2 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                            <div class="h-full bg-red-500 rounded-full transition-all duration-700"
                                 style="width: {{ $total_ruang > 0 ? round(($total_ruang - $ruang_tersedia) / $total_ruang * 100) : 0 }}%"></div>
                        </div>
                    </div>
                    <p class="text-xs text-slate-400 dark:text-slate-500 pt-1 text-right">Total: {{ $total_ruang }} ruang kampus</p>
                </div>
            </div>

            {{-- Quick Booking --}}
            <a href="{{ route('bookings.create') }}"
               class="block bg-gradient-to-br from-indigo-600 to-blue-600 dark:from-indigo-800 dark:to-blue-800 rounded-2xl p-5 text-white shadow-md hover:shadow-lg transition group">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center text-2xl group-hover:scale-110 transition">
                        ➕
                    </div>
                    <div>
                        <h3 class="font-extrabold text-lg">Ajukan Booking</h3>
                        <p class="text-sm text-white/80">Cek ketersediaan & pesan sekarang</p>
                    </div>
                </div>
                <div class="mt-3 flex items-center justify-end text-sm text-white/70 group-hover:text-white transition">
                    <span class="flex items-center gap-1">
                        Mulai <i class="fas fa-arrow-right text-xs"></i>
                    </span>
                </div>
            </a>

            {{-- Quick Links --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-700/30">
                    <h2 class="font-bold text-slate-800 dark:text-white flex items-center gap-2">
                        <i class="fas fa-link text-indigo-500"></i> Navigasi Cepat
                    </h2>
                </div>
                <div class="p-3 space-y-1.5">
                    <a href="{{ route('rooms.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700/50 text-slate-700 dark:text-slate-300 font-semibold text-sm transition group">
                        <span class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center text-blue-600 dark:text-blue-400 group-hover:scale-110 transition">
                            <i class="fas fa-door-open"></i>
                        </span>
                        Ketersediaan Ruang
                        <span class="ml-auto text-slate-400 text-xs"><i class="fas fa-chevron-right"></i></span>
                    </a>

                    <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700/50 text-slate-700 dark:text-slate-300 font-semibold text-sm transition group">
                        <span class="w-8 h-8 rounded-lg bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400 group-hover:scale-110 transition">
                            <i class="fas fa-users"></i>
                        </span>
                        Manajemen User
                        <span class="ml-auto text-slate-400 text-xs"><i class="fas fa-chevron-right"></i></span>
                    </a>

                    <a href="{{ route('admin.approvals.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700/50 text-slate-700 dark:text-slate-300 font-semibold text-sm transition group">
                        <span class="w-8 h-8 rounded-lg bg-amber-100 dark:bg-amber-900/50 flex items-center justify-center text-amber-600 dark:text-amber-400 group-hover:scale-110 transition">
                            <i class="fas fa-check-double"></i>
                        </span>
                        Approval Booking
                        @if($menunggu_approval > 0)
                        <span class="ml-auto text-xs bg-amber-100 dark:bg-amber-900/50 text-amber-700 dark:text-amber-300 font-bold px-2 py-0.5 rounded-full">
                            {{ $menunggu_approval }}
                        </span>
                        @endif
                    </a>

                    <a href="{{ route('admin.rooms.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700/50 text-slate-700 dark:text-slate-300 font-semibold text-sm transition group">
                        <span class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-900/50 flex items-center justify-center text-purple-600 dark:text-purple-400 group-hover:scale-110 transition">
                            <i class="fas fa-building"></i>
                        </span>
                        Kelola Ruangan
                        <span class="ml-auto text-slate-400 text-xs"><i class="fas fa-chevron-right"></i></span>
                    </a>

                    <a href="{{ route('profile.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700/50 text-slate-700 dark:text-slate-300 font-semibold text-sm transition group">
                        <span class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center text-emerald-600 dark:text-emerald-400 group-hover:scale-110 transition">
                            <i class="fas fa-user-cog"></i>
                        </span>
                        Pengaturan Profil
                        <span class="ml-auto text-slate-400 text-xs"><i class="fas fa-chevron-right"></i></span>
                    </a>
                </div>
            </div>

        </div>

    </div>
</div>

{{-- ============================================================ --}}
{{-- STYLES --}}
{{-- ============================================================ --}}
<style>
/* Dark mode scrollbar */
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

/* Line clamp */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

@endsection
