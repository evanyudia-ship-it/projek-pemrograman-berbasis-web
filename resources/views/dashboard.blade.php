@extends('layouts.app')  

@section('title', 'Dashboard - Smart Classroom')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Selamat datang kembali')

@section('content')

<div class="max-w-7xl mx-auto">

    <!-- Greeting -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-800">Halo, I Made Putu Subali 👋</h1>
        <p class="text-slate-600 mt-1">Berikut ringkasan aktivitas hari ini</p>
    </div>

    <!-- Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-500">Ruang Tersedia</p>
                <p class="text-4xl font-bold text-emerald-600 mt-2">{{ $ruang_tersedia }}</p>
            </div>
            <div class="w-12 h-12 bg-emerald-100 rounded-2xl flex items-center justify-center text-3xl">
                🏠
            </div>
        </div>
        <p class="text-xs text-emerald-600 mt-4">dari {{ $total_ruang }} ruang total</p>
    </div>

    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-500">Booking Aktif</p>
                <p class="text-4xl font-bold text-blue-600 mt-2">{{ $booking_aktif }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center text-3xl">
                📅
            </div>
        </div>
        <p class="text-xs text-slate-500 mt-4">Hari ini</p>
    </div>

    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-500">Menunggu Approval</p>
                <p class="text-4xl font-bold text-amber-600 mt-2">{{ $menunggu_approval }}</p>
            </div>
            <div class="w-12 h-12 bg-amber-100 rounded-2xl flex items-center justify-center text-3xl">
                ⏳
            </div>
        </div>
        <p class="text-xs text-amber-600 mt-4">Perlu dicek</p>
    </div>

    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-500">Reputation Point</p>
                <p class="text-4xl font-bold text-purple-600 mt-2">{{ $reputation_point }}</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-2xl flex items-center justify-center text-3xl">
                ⭐
            </div>
        </div>
        <p class="text-xs text-purple-600 mt-4">Good Standing</p>
    </div>
</div>

        <!-- Jadwal Hari Ini -->
        <div class="space-y-4">
            @foreach($jadwal_hari_ini as $jadwal)
            <div class="flex gap-4 bg-slate-50 rounded-2xl p-4 hover:bg-slate-100 transition">
                <div class="w-20 h-20 bg-blue-100 rounded-2xl flex-shrink-0 flex items-center justify-center text-4xl">
                    🏛️
                </div>
                <div class="flex-1">
                    <div class="flex justify-between">
                        <h3 class="font-semibold">{{ $jadwal['ruangan'] }}</h3>
                        <span class="text-xs px-3 py-1 rounded-full font-medium
                            {{ $jadwal['status'] == 'Confirmed' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                            {{ $jadwal['status'] }}
                        </span>
                    </div>
                    <p class="text-slate-600 text-sm mt-1">{{ $jadwal['waktu'] }} ({{ $jadwal['durasi'] }})</p>
                    <p class="text-slate-500 text-sm">{{ $jadwal['keperluan'] }}</p>
                </div>
            </div>
            @endforeach
        </div>

            <div class="space-y-4">
                <!-- Booking Item -->
                <div class="flex gap-4 bg-slate-50 rounded-2xl p-4 hover:bg-slate-100 transition">
                    <div class="w-20 h-20 bg-blue-100 rounded-2xl flex-shrink-0 flex items-center justify-center text-4xl">
                        🏛️
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between">
                            <h3 class="font-semibold">Ruang Seminar A - Lt. 3</h3>
                            <span class="text-xs bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full font-medium">Confirmed</span>
                        </div>
                        <p class="text-slate-600 text-sm mt-1">10:00 - 12:00 (2 jam)</p>
                        <p class="text-slate-500 text-sm">Mata Kuliah: Pemrograman Web Lanjutan</p>
                    </div>
                </div>

                <div class="flex gap-4 bg-slate-50 rounded-2xl p-4 hover:bg-slate-100 transition">
                    <div class="w-20 h-20 bg-amber-100 rounded-2xl flex-shrink-0 flex items-center justify-center text-4xl">
                        🏛️
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between">
                            <h3 class="font-semibold">Ruang Rapat 205</h3>
                            <span class="text-xs bg-amber-100 text-amber-700 px-3 py-1 rounded-full font-medium">Pending</span>
                        </div>
                        <p class="text-slate-600 text-sm mt-1">13:30 - 15:00 (1.5 jam)</p>
                        <p class="text-slate-500 text-sm">Rapat Organisasi Mahasiswa</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notifikasi & Booking Aktif -->
        <div class="lg:col-span-5 space-y-6">

            <!-- Notifikasi Approval -->
            <div class="bg-white rounded-3xl shadow-sm p-6">
                <h2 class="text-lg font-semibold mb-4 flex items-center gap-2">
                    <span>🔔</span> Notifikasi Approval
                </h2>
                <div class="space-y-3">
                    <div class="p-4 bg-amber-50 border border-amber-200 rounded-2xl">
                        <p class="text-sm font-medium">Booking Ruang Seminar A</p>
                        <p class="text-xs text-slate-500">Menunggu persetujuan Validator • 2 jam yang lalu</p>
                        <button onclick="alert('Fitur approval akan dibuat nanti')"
                                class="mt-3 text-xs bg-amber-600 text-white px-4 py-2 rounded-xl hover:bg-amber-700 transition">
                            Lihat Detail
                        </button>
                    </div>
                </div>
            </div>

            <!-- Quick Booking -->
            <a href="{{ route('bookings.create') }}" 
               class="block bg-gradient-to-r from-indigo-600 to-blue-600 text-white rounded-3xl p-8 text-center hover:from-indigo-700 hover:to-blue-700 transition shadow-lg">
                <span class="text-4xl block mb-3">➕</span>
                <span class="font-semibold text-lg">Ajukan Booking Baru</span>
            </a>

        </div>

    </div>

</div>

@endsection