@extends('layouts.app')

@section('title', 'Admin Dashboard - Smart Classroom')
@section('page_title', 'Admin Dashboard')
@section('page_subtitle', 'Panel validator & approval booking')

@section('content')

<div class="max-w-7xl mx-auto space-y-8 font-sora">

    {{-- Greeting --}}
    <div class="fade-up">
        <p class="text-sm text-slate-500 mb-1">{{ now()->translatedFormat('l, d F Y') }}</p>
        <h1 class="text-3xl font-bold text-slate-900">Halo, {{ session('user_name') }} 👋</h1>
        <p class="text-slate-500 mt-1 text-sm">Berikut ringkasan tugas approval hari ini.</p>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 fade-up delay-1">

        <div class="stat-card bg-white rounded-3xl p-5 border border-slate-100 shadow-sm">
            <div class="w-11 h-11 bg-amber-50 rounded-2xl flex items-center justify-center text-xl mb-4">⏳</div>
            <p class="text-3xl font-bold text-slate-900">{{ $menunggu_approval }}</p>
            <p class="text-xs text-slate-500 mt-1">Menunggu Approval</p>
            <a href="{{ route('admin.approvals') }}" class="text-xs text-amber-600 mt-3 font-medium block hover:underline">Proses sekarang →</a>
        </div>

        <div class="stat-card bg-white rounded-3xl p-5 border border-slate-100 shadow-sm">
            <div class="w-11 h-11 bg-emerald-50 rounded-2xl flex items-center justify-center text-xl mb-4">✅</div>
            <p class="text-3xl font-bold text-slate-900">{{ $disetujui_hari_ini }}</p>
            <p class="text-xs text-slate-500 mt-1">Disetujui Hari Ini</p>
            <p class="text-xs text-emerald-600 mt-3 font-medium">Booking confirmed</p>
        </div>

        <div class="stat-card bg-white rounded-3xl p-5 border border-slate-100 shadow-sm">
            <div class="w-11 h-11 bg-red-50 rounded-2xl flex items-center justify-center text-xl mb-4">❌</div>
            <p class="text-3xl font-bold text-slate-900">{{ $ditolak_hari_ini }}</p>
            <p class="text-xs text-slate-500 mt-1">Ditolak Hari Ini</p>
            <p class="text-xs text-red-500 mt-3 font-medium">Booking rejected</p>
        </div>

        <div class="stat-card bg-white rounded-3xl p-5 border border-slate-100 shadow-sm">
            <div class="w-11 h-11 bg-blue-50 rounded-2xl flex items-center justify-center text-xl mb-4">📅</div>
            <p class="text-3xl font-bold text-slate-900">{{ $total_booking_aktif }}</p>
            <p class="text-xs text-slate-500 mt-1">Total Booking Aktif</p>
            <p class="text-xs text-blue-600 mt-3 font-medium">Seluruh ruangan</p>
        </div>

    </div>

    {{-- Main Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 fade-up delay-2">

        {{-- Jadwal Hari Ini --}}
        <div class="lg:col-span-2 space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-base font-bold text-slate-800">Jadwal Hari Ini</h2>
            </div>
            @foreach($jadwal_hari_ini as $jadwal)
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-5 flex items-center justify-between">
                <div>
                    <p class="font-bold text-slate-800">{{ $jadwal['ruangan'] }}</p>
                    <p class="text-xs text-slate-500 mt-0.5">{{ $jadwal['waktu'] }} · {{ $jadwal['keperluan'] }}</p>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-bold
                    {{ $jadwal['status'] === 'Confirmed' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                    {{ $jadwal['status'] }}
                </span>
            </div>
            @endforeach
        </div>

        {{-- Sidebar --}}
        <div class="space-y-4">

            {{-- Notifikasi --}}
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-5">
                <h2 class="text-sm font-bold text-slate-800 mb-3">Notifikasi</h2>
                <div class="space-y-3">
                    @foreach($notifikasi as $notif)
                    <div class="flex gap-3">
                        <span class="text-lg shrink-0">{{ $notif['icon'] }}</span>
                        <div>
                            <p class="text-xs text-slate-700 font-medium">{{ $notif['pesan'] }}</p>
                            <p class="text-xs text-slate-400 mt-0.5">{{ $notif['waktu'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Quick Action --}}
            <a href="{{ route('admin.approvals') }}"
               class="block bg-amber-500 hover:bg-amber-600 transition text-white rounded-3xl p-6 text-center group">
                <span class="text-3xl block mb-2">✅</span>
                <span class="font-bold text-sm">Proses Approval</span>
                <p class="text-xs text-amber-100 mt-1">{{ $menunggu_approval }} booking menunggu</p>
            </a>

            <a href="{{ route('schedule.index') }}"
               class="block bg-white hover:bg-slate-50 transition border border-slate-100 rounded-3xl p-5 text-center">
                <span class="text-2xl block mb-1">🕒</span>
                <span class="font-bold text-sm text-slate-700">Lihat Jadwal Ruangan</span>
            </a>

        </div>
    </div>
</div>
@endsection