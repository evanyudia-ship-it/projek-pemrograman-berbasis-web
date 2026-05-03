@extends('layouts.app')

@section('title', 'Daftar Ruang - Smart Classroom')
@section('page_title', 'Daftar Ruang')
@section('page_subtitle', 'Ketersediaan ruangan kampus saat ini')

@section('content')

<div class="max-w-7xl mx-auto">

    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-2xl font-bold text-slate-800">Semua Ruangan</h2>
        <a href="{{ route('bookings.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-2xl flex items-center gap-2 transition">
            <span>➕</span> Ajukan Booking
        </a>
    </div>

    <!-- Grid Ruangan -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($rooms as $room)
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-md transition">
            
            <!-- Header Warna -->
            <div class="h-2 bg-gradient-to-r from-blue-500 to-indigo-600"></div>
            
            <div class="p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <span class="text-xs font-mono bg-slate-100 px-2.5 py-1 rounded-lg">{{ $room['kode'] }}</span>
                        <h3 class="font-semibold text-lg mt-3">{{ $room['nama'] }}</h3>
                    </div>
                    <span class="px-4 py-1.5 text-sm font-medium rounded-2xl
                        {{ $room['status'] == 'Tersedia' 
                            ? 'bg-emerald-100 text-emerald-700' 
                            : 'bg-amber-100 text-amber-700' }}">
                        {{ $room['status'] }}
                    </span>
                </div>

                <div class="mt-6 space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Kapasitas</span>
                        <span class="font-semibold">{{ $room['kapasitas'] }} Orang</span>
                    </div>
                    <div>
                        <span class="text-slate-500 block mb-1">Fasilitas</span>
                        <p class="text-slate-600 text-sm leading-relaxed">
                            {{ is_array($room['fasilitas']) ? implode(', ', $room['fasilitas']) : $room['fasilitas'] }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- footer card --}}
            <div class="border-t border-slate-100 px-6 py-4 bg-slate-50 flex justify-between items-center">
                {{-- Tombol detail selalu tampil --}}
                <a href="{{ route('rooms.show', $room['id']) }}"
                class="text-slate-500 hover:text-slate-700 text-sm font-medium">
                    Lihat Detail →
                </a>

                @if($room['status'] == 'Tersedia')
                <a href="{{ route('bookings.create', ['room_id' => $room['id']]) }}"
                class="text-blue-600 hover:text-blue-700 font-medium text-sm">
                    Ajukan Booking →
                </a>
                @else
                <span class="text-amber-600 text-sm font-medium">⏳ Sedang digunakan</span>
                @endif
            </div>

        </div>
        @endforeach
    </div>

</div>

@endsection