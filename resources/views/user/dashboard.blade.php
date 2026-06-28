@extends('layouts.app')

@section('title', 'Dashboard - Smart Classroom')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Selamat datang kembali!')

@section('content')

<div class="space-y-6">

    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6">
        <p class="text-sm text-slate-500">{{ now()->translatedFormat('l, d F Y') }}</p>
        <h1 class="text-2xl font-extrabold text-slate-800 mt-1">
            Halo, {{ session('user_name', Auth::user()->name ?? 'Pengunjung') }}
        </h1>
        <p class="text-slate-500 mt-1">
            {{ session('user_role') === 'dosen' ? 'Selamat mengajar hari ini.' : 'Semangat menggunakan sistem booking ruangan hari ini.' }}
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
            <p class="text-sm text-slate-500">Booking Disetujui</p>
            <h3 class="text-3xl font-extrabold text-slate-800 mt-2">{{ $booking_aktif ?? 0 }}</h3>
            <p class="text-xs text-slate-400 mt-1">Status: approved</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
            <p class="text-sm text-slate-500">Booking Selesai</p>
            <h3 class="text-3xl font-extrabold text-slate-800 mt-2">{{ $booking_selesai ?? 0 }}</h3>
            <p class="text-xs text-slate-400 mt-1">Total riwayat</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
            <p class="text-sm text-slate-500">Menunggu Approval</p>
            <h3 class="text-3xl font-extrabold text-slate-800 mt-2">{{ $booking_pending ?? 0 }}</h3>
            <p class="text-xs text-slate-400 mt-1">Perlu diproses validator</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
            <p class="text-sm text-slate-500">No Show</p>
            <h3 class="text-3xl font-extrabold text-slate-800 mt-2">{{ $booking_no_show ?? 0 }}</h3>
            <p class="text-xs text-slate-400 mt-1">Pengaruhi reputasi</p>
        </div>
    </div>

    @php
        $rp = $reputation_point ?? 100;
        $rpLabel = $rp >= 80 ? 'Trusted User' : ($rp >= 50 ? 'Normal' : ($rp >= 30 ? 'Dibatasi' : 'Diblokir'));
    @endphp

    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="text-sm text-slate-500">Reputation Point</p>
                <h3 class="text-xl font-extrabold text-slate-800 mt-1">
                    ⭐ {{ $rpLabel }}
                </h3>
            </div>

            <div class="text-right">
                <div class="text-3xl font-extrabold text-blue-600">{{ $rp }}</div>
                <p class="text-xs text-slate-400">/ 100 poin</p>
            </div>
        </div>

        <div class="mt-4 w-full h-3 bg-slate-100 rounded-full overflow-hidden">
            <div class="h-full bg-blue-600 rounded-full" style="width: {{ min($rp, 100) }}%"></div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        <div class="xl:col-span-2 bg-white rounded-3xl border border-slate-100 shadow-sm p-6">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="text-lg font-bold text-slate-800">Jadwal Booking Saya</h2>
                    <p class="text-sm text-slate-500">Daftar booking hari ini.</p>
                </div>

                <a href="{{ route('bookings.index') }}" class="text-sm font-semibold text-blue-600 hover:underline">
                    Lihat semua →
                </a>
            </div>

            <div class="space-y-4">
                @forelse($jadwal_hari_ini ?? [] as $jadwal)
                    <div class="p-4 rounded-2xl border border-slate-100 hover:bg-slate-50">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <span class="inline-flex px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-bold">
                                    {{ $jadwal['status'] }}
                                </span>

                                <h3 class="font-bold text-slate-800 mt-2">
                                    {{ $jadwal['ruangan'] }}
                                </h3>

                                <p class="text-sm text-slate-500">
                                    {{ $jadwal['kode'] }} · {{ $jadwal['waktu'] }} · {{ $jadwal['durasi'] }}
                                </p>

                                <p class="text-sm text-slate-600 mt-1">
                                    {{ $jadwal['keperluan'] }}
                                </p>
                            </div>

                            <a href="{{ route('rooms.show', $jadwal['id']) }}"
                               class="text-sm font-semibold text-blue-600 hover:underline">
                                Detail →
                            </a>
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
            <a href="{{ route('bookings.create') }}"
               class="block bg-blue-600 hover:bg-blue-700 rounded-3xl shadow-sm p-6 text-white">
                <div class="text-3xl mb-3">➕</div>
                <h3 class="font-extrabold text-lg">Ajukan Booking Baru</h3>
                <p class="text-sm text-blue-100 mt-1">Cek ketersediaan dan pesan ruangan.</p>
            </a>

            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6">
                <h2 class="text-lg font-bold text-slate-800 mb-4">Akses Cepat</h2>

                <div class="space-y-3">
                    <a href="{{ route('rooms.index') }}" class="block px-4 py-3 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-700 font-semibold">
                        Ketersediaan Ruang
                    </a>

                    <a href="{{ route('schedule.index') }}" class="block px-4 py-3 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-700 font-semibold">
                        Jadwal Ruangan
                    </a>

                    <a href="{{ route('bookings.history') }}" class="block px-4 py-3 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-700 font-semibold">
                        Riwayat Booking
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection