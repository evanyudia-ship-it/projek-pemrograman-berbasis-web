@extends('layouts.app')

@section('title', 'Admin Dashboard - Smart Classroom')
@section('page_title', 'Admin Dashboard')
@section('page_subtitle', 'Panel validator dan approval booking')

@section('content')

<div class="max-w-7xl mx-auto font-sora space-y-6">

    {{-- ============================================================ --}}
    {{-- GREETING SECTION --}}
    {{-- ============================================================ --}}
    <div class="bg-gradient-to-br from-indigo-600 to-blue-600 dark:from-indigo-800 dark:to-blue-800 rounded-2xl p-6 md:p-8 text-white shadow-lg">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center text-3xl">
                    🛡️
                </div>
                <div>
                    <p class="text-xs font-semibold text-white/70 uppercase tracking-widest">
                        {{ now()->translatedFormat('l, d F Y') }}
                    </p>
                    <h1 class="text-2xl md:text-3xl font-bold mt-0.5">
                        Halo, {{ session('user_name', Auth::user()->name ?? 'Admin') }}
                    </h1>
                    <p class="text-sm text-white/80 mt-1 flex items-center gap-2">
                        <span>📋 Berikut ringkasan tugas approval hari ini</span>
                        @if(($menunggu_approval ?? 0) > 0)
                        <span class="px-3 py-1 rounded-full bg-amber-500/30 text-amber-200 text-xs font-bold animate-pulse border border-amber-400/30">
                            {{ $menunggu_approval }} perlu diproses
                        </span>
                        @endif
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <span class="px-4 py-2 rounded-xl bg-white/20 backdrop-blur text-sm font-semibold border border-white/10 flex items-center gap-2">
                    <i class="fas fa-shield-alt"></i> Validator
                </span>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- STATISTICS CARDS --}}
    {{-- ============================================================ --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Pending --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-5 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Menunggu Approval</p>
                    <p class="text-2xl font-extrabold text-amber-600 dark:text-amber-400 mt-1">{{ $menunggu_approval ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900/50 flex items-center justify-center text-amber-600 dark:text-amber-400 group-hover:scale-110 transition">
                    <i class="fas fa-clock text-lg"></i>
                </div>
            </div>
            @if(($menunggu_approval ?? 0) > 0)
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

        {{-- Approved Today --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-5 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Disetujui Hari Ini</p>
                    <p class="text-2xl font-extrabold text-emerald-600 dark:text-emerald-400 mt-1">{{ $disetujui_hari_ini ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center text-emerald-600 dark:text-emerald-400 group-hover:scale-110 transition">
                    <i class="fas fa-check-circle text-lg"></i>
                </div>
            </div>
            <div class="mt-2">
                <span class="text-xs text-slate-400 dark:text-slate-500">Booking confirmed</span>
            </div>
        </div>

        {{-- Rejected Today --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-5 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Ditolak Hari Ini</p>
                    <p class="text-2xl font-extrabold text-red-600 dark:text-red-400 mt-1">{{ $ditolak_hari_ini ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-red-100 dark:bg-red-900/50 flex items-center justify-center text-red-600 dark:text-red-400 group-hover:scale-110 transition">
                    <i class="fas fa-times-circle text-lg"></i>
                </div>
            </div>
            <div class="mt-2">
                <span class="text-xs text-slate-400 dark:text-slate-500">Booking rejected</span>
            </div>
        </div>

        {{-- Total Active --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-5 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Total Booking Aktif</p>
                    <p class="text-2xl font-extrabold text-blue-600 dark:text-blue-400 mt-1">{{ $total_booking_aktif ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center text-blue-600 dark:text-blue-400 group-hover:scale-110 transition">
                    <i class="fas fa-building text-lg"></i>
                </div>
            </div>
            <div class="mt-2">
                <span class="text-xs text-slate-400 dark:text-slate-500">Seluruh ruangan</span>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- QUICK ACTION BAR --}}
    {{-- ============================================================ --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('admin.approvals.index') }}"
           class="bg-gradient-to-br from-amber-500 to-orange-500 dark:from-amber-600 dark:to-orange-600 rounded-2xl p-5 text-white shadow-md hover:shadow-lg transition group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center text-2xl group-hover:scale-110 transition">
                    ✅
                </div>
                <div>
                    <h3 class="font-extrabold text-lg">Proses Approval</h3>
                    <p class="text-sm text-white/80">
                        {{ $menunggu_approval ?? 0 }} booking menunggu persetujuan
                    </p>
                </div>
            </div>
            <div class="mt-3 flex items-center justify-end text-sm text-white/70 group-hover:text-white transition">
                <span class="flex items-center gap-1">
                    Buka panel <i class="fas fa-arrow-right text-xs"></i>
                </span>
            </div>
        </a>

        <a href="{{ route('admin.bookings.index') }}"
           class="bg-gradient-to-br from-indigo-500 to-purple-500 dark:from-indigo-600 dark:to-purple-600 rounded-2xl p-5 text-white shadow-md hover:shadow-lg transition group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center text-2xl group-hover:scale-110 transition">
                    📋
                </div>
                <div>
                    <h3 class="font-extrabold text-lg">Manajemen Booking</h3>
                    <p class="text-sm text-white/80">
                        Lihat semua booking yang berlangsung
                    </p>
                </div>
            </div>
            <div class="mt-3 flex items-center justify-end text-sm text-white/70 group-hover:text-white transition">
                <span class="flex items-center gap-1">
                    Kelola <i class="fas fa-arrow-right text-xs"></i>
                </span>
            </div>
        </a>

        <a href="{{ route('schedule.index') }}"
           class="bg-gradient-to-br from-emerald-500 to-teal-500 dark:from-emerald-600 dark:to-teal-600 rounded-2xl p-5 text-white shadow-md hover:shadow-lg transition group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center text-2xl group-hover:scale-110 transition">
                    📅
                </div>
                <div>
                    <h3 class="font-extrabold text-lg">Jadwal Ruangan</h3>
                    <p class="text-sm text-white/80">
                        Pantau penggunaan ruangan hari ini
                    </p>
                </div>
            </div>
            <div class="mt-3 flex items-center justify-end text-sm text-white/70 group-hover:text-white transition">
                <span class="flex items-center gap-1">
                    Lihat jadwal <i class="fas fa-arrow-right text-xs"></i>
                </span>
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
                    <span class="px-3 py-1.5 rounded-full bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 text-xs font-bold">
                        {{ count($jadwal_hari_ini ?? []) }} jadwal
                    </span>
                </div>
            </div>

            <div class="p-6 space-y-4 max-h-[450px] overflow-y-auto">
                @forelse($jadwal_hari_ini ?? [] as $jadwal)
                    <div class="group p-4 rounded-xl border border-slate-200 dark:border-slate-700 hover:border-indigo-200 dark:hover:border-indigo-800 hover:shadow-md transition bg-white dark:bg-slate-800/50">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-bold
                                        {{ ($jadwal['status'] ?? '') === 'approved' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300' :
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
                                </div>

                                <p class="text-sm text-slate-600 dark:text-slate-300 mt-2 line-clamp-2">
                                    {{ $jadwal['keperluan'] ?? '-' }}
                                </p>
                            </div>

                            <div class="flex flex-col items-end gap-2">
                                <a href="{{ route('bookings.show', $jadwal['id'] ?? 0) }}"
                                   class="px-3.5 py-2 rounded-xl bg-indigo-50 dark:bg-indigo-950/30 hover:bg-indigo-100 dark:hover:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 text-xs font-semibold transition flex items-center gap-1.5 whitespace-nowrap">
                                    Detail <i class="fas fa-arrow-right text-[10px]"></i>
                                </a>
                                @if(($jadwal['status'] ?? '') === 'pending')
                                <a href="{{ route('admin.approvals.index') }}#pending-{{ $jadwal['id'] ?? 0 }}"
                                   class="px-3.5 py-1.5 rounded-xl bg-amber-50 dark:bg-amber-950/30 hover:bg-amber-100 dark:hover:bg-amber-900/50 text-amber-600 dark:text-amber-400 text-xs font-semibold transition flex items-center gap-1.5 whitespace-nowrap">
                                    <i class="fas fa-check"></i> Proses
                                </a>
                                @endif
                            </div>
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

            {{-- Quick Approval --}}
            @if(($menunggu_approval ?? 0) > 0)
            <div class="bg-gradient-to-br from-amber-50 to-orange-50 dark:from-amber-950/30 dark:to-orange-950/30 rounded-2xl border border-amber-200 dark:border-amber-800 p-5">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900/50 flex items-center justify-center text-amber-600 dark:text-amber-400">
                        <i class="fas fa-bell text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 dark:text-white">Perlu Tindakan</h3>
                        <p class="text-xs text-amber-600 dark:text-amber-400 font-semibold">{{ $menunggu_approval }} booking pending</p>
                    </div>
                </div>
                <p class="text-sm text-slate-600 dark:text-slate-400 mb-3">
                    Booking yang belum diproses akan mempengaruhi reputasi pemohon.
                </p>
                <a href="{{ route('admin.approvals.index') }}"
                   class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold transition shadow-sm shadow-amber-200 dark:shadow-amber-900/30">
                    <i class="fas fa-check-double"></i> Proses Sekarang
                </a>
            </div>
            @endif

            {{-- Quick Stats --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-5">
                <h2 class="font-bold text-slate-800 dark:text-white flex items-center gap-2 mb-3">
                    <i class="fas fa-chart-simple text-indigo-500"></i> Ringkasan Hari Ini
                </h2>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between py-2 border-b border-slate-100 dark:border-slate-700">
                        <span class="text-slate-500 dark:text-slate-400">Pending</span>
                        <span class="font-semibold text-amber-600 dark:text-amber-400">{{ $menunggu_approval ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-slate-100 dark:border-slate-700">
                        <span class="text-slate-500 dark:text-slate-400">Disetujui</span>
                        <span class="font-semibold text-emerald-600 dark:text-emerald-400">{{ $disetujui_hari_ini ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-slate-100 dark:border-slate-700">
                        <span class="text-slate-500 dark:text-slate-400">Ditolak</span>
                        <span class="font-semibold text-red-600 dark:text-red-400">{{ $ditolak_hari_ini ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-slate-500 dark:text-slate-400">Booking Aktif</span>
                        <span class="font-semibold text-blue-600 dark:text-blue-400">{{ $total_booking_aktif ?? 0 }}</span>
                    </div>
                </div>
            </div>

            {{-- Recent Activity / Quick Links --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-700/30">
                    <h2 class="font-bold text-slate-800 dark:text-white flex items-center gap-2">
                        <i class="fas fa-link text-indigo-500"></i> Navigasi Cepat
                    </h2>
                </div>
                <div class="p-3 space-y-1.5">
                    <a href="{{ route('admin.bookings.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700/50 text-slate-700 dark:text-slate-300 font-semibold text-sm transition group">
                        <span class="w-8 h-8 rounded-lg bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400 group-hover:scale-110 transition">
                            <i class="fas fa-list"></i>
                        </span>
                        Semua Booking
                        <span class="ml-auto text-slate-400 text-xs"><i class="fas fa-chevron-right"></i></span>
                    </a>

                    @if(Auth::user()->role === 'superadmin')
                    <a href="{{ route('admin.rooms.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700/50 text-slate-700 dark:text-slate-300 font-semibold text-sm transition group">
                        <span class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center text-emerald-600 dark:text-emerald-400 group-hover:scale-110 transition">
                            <i class="fas fa-door-open"></i>
                        </span>
                        Kelola Ruangan
                        <span class="ml-auto text-slate-400 text-xs"><i class="fas fa-chevron-right"></i></span>
                    </a>

                    <a href="{{ route('admin.faculties.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700/50 text-slate-700 dark:text-slate-300 font-semibold text-sm transition group">
                        <span class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-900/50 flex items-center justify-center text-purple-600 dark:text-purple-400 group-hover:scale-110 transition">
                            <i class="fas fa-building-columns"></i>
                        </span>
                        Kelola Fakultas
                        <span class="ml-auto text-slate-400 text-xs"><i class="fas fa-chevron-right"></i></span>
                    </a>
                    @endif

                    <a href="{{ route('profile.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700/50 text-slate-700 dark:text-slate-300 font-semibold text-sm transition group">
                        <span class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center text-blue-600 dark:text-blue-400 group-hover:scale-110 transition">
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
