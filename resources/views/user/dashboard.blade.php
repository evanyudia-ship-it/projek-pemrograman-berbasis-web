@extends('layouts.app')

@section('title', 'Dashboard - Smart Classroom')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Selamat datang kembali!')

@section('content')

<div class="max-w-7xl mx-auto space-y-8 font-sora">

    {{-- Greeting --}}
    <div class="fade-up">
        <p class="text-sm text-slate-500 mb-1">{{ now()->translatedFormat('l, d F Y') }}</p>
        <h1 class="text-3xl font-bold text-slate-900">Halo, {{ session('user_name', 'Pengunjung') }} 👋</h1>
        <p class="text-slate-500 mt-1 text-sm">
            {{ session('user_role') === 'dosen' ? 'Selamat mengajar hari ini.' : 'Semangat kuliah hari ini!' }}
        </p>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 fade-up delay-1">

        <div class="stat-card bg-white rounded-3xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="w-11 h-11 bg-blue-50 rounded-2xl flex items-center justify-center text-xl mb-4">📅</div>
            <p class="text-3xl font-bold text-slate-900">{{ $booking_aktif }}</p>
            <p class="text-xs text-slate-500 mt-1">Booking Disetujui</p>
            <p class="text-xs text-blue-600 mt-3 font-medium">Status: approved</p>
        </div>

        <div class="stat-card bg-white rounded-3xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="w-11 h-11 bg-emerald-50 rounded-2xl flex items-center justify-center text-xl mb-4">✅</div>
            <p class="text-3xl font-bold text-slate-900">{{ $booking_selesai }}</p>
            <p class="text-xs text-slate-500 mt-1">Booking Selesai</p>
            <p class="text-xs text-emerald-600 mt-3 font-medium">Total riwayat</p>
        </div>

        <div class="stat-card bg-white rounded-3xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="w-11 h-11 bg-amber-50 rounded-2xl flex items-center justify-center text-xl mb-4">⏳</div>
            <p class="text-3xl font-bold text-slate-900">{{ $booking_pending }}</p>
            <p class="text-xs text-slate-500 mt-1">Menunggu Approval</p>
            <p class="text-xs text-amber-600 mt-3 font-medium">Perlu diproses</p>
        </div>

        <div class="stat-card bg-white rounded-3xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="w-11 h-11 bg-red-50 rounded-2xl flex items-center justify-center text-xl mb-4">⚠️</div>
            <p class="text-3xl font-bold text-slate-900">{{ $booking_no_show ?? 0 }}</p>
            <p class="text-xs text-slate-500 mt-1">No Show (Perlu Validasi Check-in)</p>
            <p class="text-xs text-red-500 mt-3 font-medium">Pengaruhi reputasi</p>
        </div>

    </div>

    {{-- Reputation --}}
    @php
        $rp = $reputation_point;
        $rpLabel = $rp >= 80 ? 'Trusted User' : ($rp >= 50 ? 'Normal' : ($rp >= 30 ? 'Dibatasi' : 'Diblokir'));
        $rpColor = $rp >= 80 ? 'from-emerald-400 to-teal-400' : ($rp >= 50 ? 'from-blue-400 to-indigo-400' : 'from-amber-400 to-orange-400');
    @endphp
    <a href="{{ route('reputation.index') }}"
        class="stat-card bg-slate-950 rounded-3xl p-5 border border-slate-800 shadow-sm hover:shadow-lg transition-all block max-h-48 overflow-hidden">
        <div class="flex items-center justify-between">
            <div class="w-11 h-11 bg-white/10 rounded-2xl flex items-center justify-center text-xl">⭐</div>
            <span class="text-2xs text-slate-500">{{ $rpLabel }}</span>
        </div>
        <p class="text-4xl font-bold text-white mt-3">{{ $rp }}</p>
        <p class="text-xs text-slate-400 mt-1">Reputation Points</p>
        <div class="mt-3 h-1.5 bg-white/10 rounded-full overflow-hidden">
            <div class="h-full bg-linear-to-r {{ $rpColor }} rounded-full" style="width:{{ min(100, $rp) }}%"></div>
        </div>
        <p class="text-xs text-slate-400 mt-2">{{ $rp }}/100</p>
    </a>

    {{-- Verification Alert --}}
    @if(!session('is_verified', false))
    <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 flex items-center justify-between fade-up">
        <div class="flex items-center gap-3">
            <span class="text-xl">⚠️</span>
            <p class="text-amber-700 text-sm font-medium">Email Anda belum diverifikasi. Tolong verifikasi akun terlebih dahulu</p>
        </div>
        <a href="{{ route('verify.show') }}" 
           class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold rounded-xl transition-colors shrink-0">
            Verifikasi Sekarang
        </a>
    </div>
    @endif

    {{-- Main Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 fade-up delay-2 items-start">

        {{-- Jadwal Section --}}
        <div class="lg:col-span-2 space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-base font-bold text-slate-800">Jadwal Booking Saya</h2>
                <a href="{{ route('bookings.index') }}" class="text-xs text-blue-600 hover:text-blue-700 font-medium transition-colors">Lihat semua →</a>
            </div>

            @forelse($jadwal_hari_ini as $jadwal)
            <div class="room-card bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition-all overflow-hidden">
                <div class="relative h-36 overflow-hidden">
                    <img src="{{ $jadwal['foto'] }}" alt="{{ $jadwal['ruangan'] }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-linear-to-t from-black/60 via-black/10 to-transparent"></div>
                    <span class="absolute top-3 right-3 px-3 py-1 rounded-full text-xs font-bold shadow-sm
                        {{ $jadwal['status'] === 'Confirmed' ? 'bg-emerald-400/90 text-emerald-900' : 'bg-amber-400/90 text-amber-900' }}">
                        {{ $jadwal['status'] }}
                    </span>
                    <div class="absolute bottom-3 left-4 right-4">
                        <p class="text-white font-bold text-sm truncate">{{ $jadwal['ruangan'] }}</p>
                        <p class="text-white/70 text-xs font-mono">{{ $jadwal['kode'] }}</p>
                    </div>
                </div>
                <div class="p-4 flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-slate-700">
                            🕐 {{ $jadwal['waktu'] }} 
                            <span class="text-slate-400 font-normal text-xs">({{ $jadwal['durasi'] }})</span>
                        </p>
                        <p class="text-xs text-slate-500 mt-0.5 line-clamp-1">{{ $jadwal['keperluan'] }}</p>
                    </div>
                    <a href="{{ route('rooms.show', $jadwal['id']) }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700 ml-3 shrink-0">Detail →</a>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-3xl border border-slate-100 p-10 text-center">
                <p class="text-5xl mb-3">📭</p>
                <p class="text-sm text-slate-400">Tidak ada jadwal hari ini.</p>
            </div>
            @endforelse
        </div>

        {{-- Sidebar --}}
        <div class="space-y-4">

            {{-- Notifications --}}
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-5">
                <h2 class="text-sm font-bold text-slate-800 mb-4">Notifikasi</h2>
                <div class="space-y-3">
                    @forelse($notifikasi as $notif)
                    <div class="flex gap-3 items-start">
                        <span class="text-lg shrink-0">{{ $notif['icon'] }}</span>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs text-slate-700 font-medium leading-relaxed">{{ $notif['pesan'] }}</p>
                            <p class="text-2xs text-slate-400 mt-0.5">{{ $notif['waktu'] }}</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-xs text-slate-400 text-center py-4">Tidak ada notifikasi</p>
                    @endforelse
                </div>
            </div>

            {{-- Quick Create Booking --}}
            <a href="{{ route('bookings.create') }}"
               class="block bg-slate-950 hover:bg-slate-800 transition-all rounded-3xl p-6 text-center group">
                <span class="text-3xl block mb-2 group-hover:scale-110 transition-transform">➕</span>
                <span class="font-bold text-sm text-white">Ajukan Booking Baru</span>
                <p class="text-xs text-slate-400 mt-1">Cek ketersediaan & pesan sekarang</p>
            </a>

            {{-- Quick Access --}}
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-5">
                <h2 class="text-sm font-bold text-slate-800 mb-3">Akses Cepat</h2>
                <div class="space-y-1">
                    <a href="{{ route('rooms.index') }}" 
                       class="flex items-center gap-3 p-3 rounded-2xl hover:bg-slate-50 transition-colors text-sm text-slate-700 font-medium group">
                        <span class="w-8 h-8 bg-blue-50 rounded-xl flex items-center justify-center group-hover:bg-blue-100 transition-colors">🏫</span>
                        Ketersediaan Ruang
                    </a>
                    <a href="{{ route('schedule.index') }}" 
                       class="flex items-center gap-3 p-3 rounded-2xl hover:bg-slate-50 transition-colors text-sm text-slate-700 font-medium group">
                        <span class="w-8 h-8 bg-purple-50 rounded-xl flex items-center justify-center group-hover:bg-purple-100 transition-colors">🕒</span>
                        Jadwal Ruangan
                    </a>
                    <a href="{{ route('bookings.index') }}" 
                       class="flex items-center gap-3 p-3 rounded-2xl hover:bg-slate-50 transition-colors text-sm text-slate-700 font-medium group">
                        <span class="w-8 h-8 bg-slate-100 rounded-xl flex items-center justify-center group-hover:bg-slate-200 transition-colors">📋</span>
                        Riwayat Booking
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection