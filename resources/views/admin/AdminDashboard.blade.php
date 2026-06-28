@extends('layouts.app')

@section('title', 'Admin Dashboard - Smart Classroom')
@section('page_title', 'Admin Dashboard')
@section('page_subtitle', 'Panel validator dan approval booking')

@section('content')

<div class="space-y-6">

    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6">
        <p class="text-sm text-slate-500">{{ now()->translatedFormat('l, d F Y') }}</p>
        <h1 class="text-2xl font-extrabold text-slate-800 mt-1">
            Halo, {{ session('user_name', Auth::user()->name ?? 'Admin') }}
        </h1>
        <p class="text-slate-500 mt-1">
            Berikut ringkasan tugas approval hari ini.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
            <p class="text-sm text-slate-500">Menunggu Approval</p>
            <h3 class="text-3xl font-extrabold text-slate-800 mt-2">{{ $menunggu_approval ?? 0 }}</h3>
            <a href="{{ route('admin.approvals.index') }}" class="text-xs text-blue-600 font-semibold mt-2 inline-block">
                Proses sekarang →
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
            <p class="text-sm text-slate-500">Disetujui Hari Ini</p>
            <h3 class="text-3xl font-extrabold text-slate-800 mt-2">{{ $disetujui_hari_ini ?? 0 }}</h3>
            <p class="text-xs text-slate-400 mt-1">Booking confirmed</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
            <p class="text-sm text-slate-500">Ditolak Hari Ini</p>
            <h3 class="text-3xl font-extrabold text-slate-800 mt-2">{{ $ditolak_hari_ini ?? 0 }}</h3>
            <p class="text-xs text-slate-400 mt-1">Booking rejected</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
            <p class="text-sm text-slate-500">Total Booking Aktif</p>
            <h3 class="text-3xl font-extrabold text-slate-800 mt-2">{{ $total_booking_aktif ?? 0 }}</h3>
            <p class="text-xs text-slate-400 mt-1">Seluruh ruangan</p>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        <div class="xl:col-span-2 bg-white rounded-3xl border border-slate-100 shadow-sm p-6">
            <h2 class="text-lg font-bold text-slate-800 mb-4">Jadwal Hari Ini</h2>

            <div class="space-y-4">
                @forelse($jadwal_hari_ini ?? [] as $jadwal)
                    <div class="p-4 rounded-2xl border border-slate-100 hover:bg-slate-50">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <h3 class="font-bold text-slate-800">
                                    {{ $jadwal['ruangan'] }}
                                </h3>

                                <p class="text-sm text-slate-500 mt-1">
                                    {{ $jadwal['waktu'] }} · {{ $jadwal['keperluan'] }}
                                </p>
                            </div>

                            <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-bold">
                                {{ $jadwal['status'] }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-slate-500 py-10">
                        Tidak ada jadwal hari ini.
                    </div>
                @endforelse
            </div>
        </div>

        <div class="space-y-6">
            <a href="{{ route('admin.approvals.index') }}"
               class="block bg-blue-600 hover:bg-blue-700 rounded-3xl shadow-sm p-6 text-white">
                <div class="text-3xl mb-3">✅</div>
                <h3 class="font-extrabold text-lg">Proses Approval</h3>
                <p class="text-sm text-blue-100 mt-1">
                    {{ $menunggu_approval ?? 0 }} booking menunggu.
                </p>
            </a>

            <a href="{{ route('schedule.index') }}"
               class="block bg-white rounded-3xl border border-slate-100 shadow-sm p-6 hover:bg-slate-50">
                <div class="text-3xl mb-3">📅</div>
                <h3 class="font-extrabold text-lg text-slate-800">Lihat Jadwal Ruangan</h3>
                <p class="text-sm text-slate-500 mt-1">
                    Pantau penggunaan ruangan hari ini.
                </p>
            </a>
        </div>

    </div>
</div>

@endsection