@extends('layouts.app')

@section('title', 'Dashboard - Smart Classroom')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Selamat datang kembali!')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&display=swap" rel="stylesheet">
<style>
    .font-sora { font-family: 'Sora', sans-serif; }
    .stat-card { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 12px 32px -8px rgba(0,0,0,0.12); }
    .room-card { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .room-card:hover { transform: translateY(-4px); box-shadow: 0 16px 40px -10px rgba(0,0,0,0.15); }
    .room-img { transition: transform 0.4s ease; }
    .room-card:hover .room-img { transform: scale(1.05); }
    @keyframes fadeUp { from { opacity:0; transform:translateY(16px); } to { opacity:1; transform:translateY(0); } }
    .fade-up { animation: fadeUp 0.4s ease both; }
    .delay-1 { animation-delay: 0.05s; }
    .delay-2 { animation-delay: 0.1s; }
</style>

<div class="max-w-7xl mx-auto space-y-8 font-sora">

    {{-- Greeting --}}
    <div class="fade-up">
        <p class="text-sm text-slate-500 mb-1">{{ now()->translatedFormat('l, d F Y') }}</p>
        <h1 class="text-3xl font-bold text-slate-900">Halo, {{ session('user_name') }} 👋</h1>
        <p class="text-slate-500 mt-1 text-sm">
            {{ session('user_role') === 'dosen' ? 'Selamat mengajar hari ini.' : 'Semangat kuliah hari ini!' }}
        </p>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 fade-up delay-1">

        <div class="stat-card bg-white rounded-3xl p-5 border border-slate-100 shadow-sm">
            <div class="w-11 h-11 bg-blue-50 rounded-2xl flex items-center justify-center text-xl mb-4">📅</div>
            <p class="text-3xl font-bold text-slate-900">{{ $booking_aktif }}</p>
            <p class="text-xs text-slate-500 mt-1">Booking Aktif</p>
            <p class="text-xs text-blue-600 mt-3 font-medium">Hari ini</p>
        </div>

        <div class="stat-card bg-white rounded-3xl p-5 border border-slate-100 shadow-sm">
            <div class="w-11 h-11 bg-emerald-50 rounded-2xl flex items-center justify-center text-xl mb-4">✅</div>
            <p class="text-3xl font-bold text-slate-900">{{ $booking_selesai }}</p>
            <p class="text-xs text-slate-500 mt-1">Booking Selesai</p>
            <p class="text-xs text-emerald-600 mt-3 font-medium">Total riwayat</p>
        </div>

        <div class="stat-card bg-white rounded-3xl p-5 border border-slate-100 shadow-sm">
            <div class="w-11 h-11 bg-amber-50 rounded-2xl flex items-center justify-center text-xl mb-4">⏳</div>
            <p class="text-3xl font-bold text-slate-900">{{ $booking_pending }}</p>
            <p class="text-xs text-slate-500 mt-1">Menunggu Approval</p>
            <p class="text-xs text-amber-600 mt-3 font-medium">Perlu diproses</p>
        </div>

        {{-- Reputation --}}
        @php
            $rp = $reputation_point;
            $rpLabel = $rp >= 80 ? 'Trusted User' : ($rp >= 50 ? 'Normal' : ($rp >= 30 ? 'Dibatasi' : 'Diblokir'));
            $rpColor = $rp >= 80 ? 'from-emerald-400 to-teal-400' : ($rp >= 50 ? 'from-blue-400 to-indigo-400' : 'from-amber-400 to-orange-400');
        @endphp
        <a href="{{ route('reputation.index') }}"
           class="stat-card bg-slate-950 rounded-3xl p-5 border border-slate-800 shadow-sm block">
            <div class="w-11 h-11 bg-white/10 rounded-2xl flex items-center justify-center text-xl mb-4">⭐</div>
            <p class="text-3xl font-bold text-white">{{ $rp }}</p>
            <p class="text-xs text-slate-400 mt-1">Reputation Points</p>
            <div class="mt-3 h-1.5 bg-white/10 rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r {{ $rpColor }} rounded-full" style="width:{{ $rp }}%"></div>
            </div>
            <p class="text-xs text-slate-400 mt-2">{{ $rpLabel }} · {{ $rp }}/100</p>
        </a>

    </div>

    {{-- Main Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 fade-up delay-2">

        {{-- Jadwal --}}
        <div class="lg:col-span-2 space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-base font-bold text-slate-800">Jadwal Booking Saya</h2>
                <a href="{{ route('bookings.index') }}" class="text-xs text-blue-600 hover:underline font-medium">Lihat semua →</a>
            </div>

            @forelse($jadwal_hari_ini as $jadwal)
            <div class="room-card bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="relative h-36 overflow-hidden">
                    <img src="{{ $jadwal['foto'] }}" alt="{{ $jadwal['ruangan'] }}" class="room-img w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent"></div>
                    <span class="absolute top-3 right-3 px-3 py-1 rounded-full text-xs font-bold
                        {{ $jadwal['status'] === 'Confirmed' ? 'bg-emerald-400/90 text-emerald-900' : 'bg-amber-400/90 text-amber-900' }}">
                        {{ $jadwal['status'] }}
                    </span>
                    <div class="absolute bottom-3 left-4">
                        <p class="text-white font-bold text-sm">{{ $jadwal['ruangan'] }}</p>
                        <p class="text-white/70 text-xs font-mono">{{ $jadwal['kode'] }}</p>
                    </div>
                </div>
                <div class="p-4 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-slate-700">🕐 {{ $jadwal['waktu'] }} <span class="text-slate-400 font-normal text-xs">({{ $jadwal['durasi'] }})</span></p>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $jadwal['keperluan'] }}</p>
                    </div>
                    <a href="{{ route('rooms.show', $jadwal['id']) }}" class="text-xs font-semibold text-blue-600">Detail →</a>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-3xl border border-slate-100 p-10 text-center text-slate-400">
                <p class="text-4xl mb-3">📭</p>
                <p class="text-sm">Tidak ada jadwal hari ini.</p>
            </div>
            @endforelse
        </div>

        @if(!session('is_verified', false))
        <div class="mb-4 p-4 rounded-xl bg-amber-50 border border-amber-300 flex items-center justify-between">
            <p class="text-amber-700 text-sm font-semibold">
                ⚠️ Email Anda belum diverifikasi. Tolong Verifikasi Akun terlebih dahulu
            </p>
            <a href="{{ route('verify.show') }}" 
               class="ml-4 px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold rounded-xl transition">
                Verifikasi Sekarang
            </a>
        </div>
        @endif

        {{-- Sidebar --}}
        <div class="space-y-4">

            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-5">
                <h2 class="text-sm font-bold text-slate-800 mb-3">Notifikasi</h2>
                <div class="space-y-3">
                    @foreach($notifikasi as $notif)
                    <div class="flex gap-3">
                        <span class="text-lg flex-shrink-0">{{ $notif['icon'] }}</span>
                        <div>
                            <p class="text-xs text-slate-700 font-medium">{{ $notif['pesan'] }}</p>
                            <p class="text-xs text-slate-400 mt-0.5">{{ $notif['waktu'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <a href="{{ route('bookings.create') }}"
               class="block bg-slate-950 hover:bg-slate-800 transition text-white rounded-3xl p-6 text-center group">
                <span class="text-3xl block mb-2 group-hover:scale-110 transition-transform">➕</span>
                <span class="font-bold text-sm">Ajukan Booking Baru</span>
                <p class="text-xs text-slate-400 mt-1">Cek ketersediaan & pesan sekarang</p>
            </a>

            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-5">
                <h2 class="text-sm font-bold text-slate-800 mb-3">Akses Cepat</h2>
                <div class="space-y-2">
                    <a href="{{ route('rooms.index') }}" class="flex items-center gap-3 p-3 rounded-2xl hover:bg-slate-50 transition text-sm text-slate-700 font-medium">
                        <span class="w-8 h-8 bg-blue-50 rounded-xl flex items-center justify-center">🏫</span>
                        Ketersediaan Ruang
                    </a>
                    <a href="{{ route('schedule.index') }}" class="flex items-center gap-3 p-3 rounded-2xl hover:bg-slate-50 transition text-sm text-slate-700 font-medium">
                        <span class="w-8 h-8 bg-purple-50 rounded-xl flex items-center justify-center">🕒</span>
                        Jadwal Ruangan
                    </a>
                    <a href="{{ route('bookings.index') }}" class="flex items-center gap-3 p-3 rounded-2xl hover:bg-slate-50 transition text-sm text-slate-700 font-medium">
                        <span class="w-8 h-8 bg-slate-100 rounded-xl flex items-center justify-center">📋</span>
                        Riwayat Booking
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection