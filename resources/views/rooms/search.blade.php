@extends('layouts.app')

@section('title', 'Cari Ruangan - Smart Classroom')
@section('page_title', 'Cari Ruangan')
@section('page_subtitle', 'Temukan ruangan yang tersedia sesuai kebutuhan Anda')

@section('content')

<div class="max-w-7xl mx-auto font-sora space-y-6">

    {{-- ===== FORM PENCARIAN ===== --}}
    <div class="fade-up">
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6">
            <form action="{{ route('rooms.search') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    {{-- Keyword --}}
                    <div>
                        <label class="text-sm font-semibold text-slate-700">Cari Ruangan</label>
                        <input type="text"
                               name="q"
                               value="{{ request('q') }}"
                               placeholder="Nama, kode, gedung..."
                               class="mt-1 w-full rounded-xl border-slate-200 focus:border-green-500 focus:ring-green-500">
                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="text-sm font-semibold text-slate-700">Status</label>
                        <select name="status" class="mt-1 w-full rounded-xl border-slate-200 focus:border-green-500 focus:ring-green-500">
                            <option value="">Semua Status</option>
                            <option value="Tersedia" {{ request('status') == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                            <option value="Maintenance" {{ request('status') == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                        </select>
                    </div>

                    {{-- Gedung --}}
                    <div>
                        <label class="text-sm font-semibold text-slate-700">Gedung</label>
                        <select name="gedung" class="mt-1 w-full rounded-xl border-slate-200 focus:border-green-500 focus:ring-green-500">
                            <option value="">Semua Gedung</option>
                            @foreach($buildings ?? [] as $building)
                            <option value="{{ $building }}" {{ request('gedung') == $building ? 'selected' : '' }}>
                                {{ $building }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Kapasitas Minimal --}}
                    <div>
                        <label class="text-sm font-semibold text-slate-700">Kapasitas Minimal</label>
                        <input type="number"
                               name="min_capacity"
                               value="{{ request('min_capacity') }}"
                               placeholder="Minimal orang"
                               min="1"
                               class="mt-1 w-full rounded-xl border-slate-200 focus:border-green-500 focus:ring-green-500">
                    </div>
                </div>

                {{-- Pencarian berdasarkan ketersediaan --}}
                <div class="border-t border-slate-100 pt-4">
                    <p class="text-sm font-semibold text-slate-700 mb-3">Cek Ketersediaan Waktu</p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="text-sm text-slate-600">Tanggal</label>
                            <input type="date"
                                   name="tanggal"
                                   value="{{ request('tanggal') }}"
                                   min="{{ now()->format('Y-m-d') }}"
                                   class="mt-1 w-full rounded-xl border-slate-200 focus:border-green-500 focus:ring-green-500">
                        </div>
                        <div>
                            <label class="text-sm text-slate-600">Jam Mulai</label>
                            <input type="time"
                                   name="jam_mulai"
                                   value="{{ request('jam_mulai') }}"
                                   class="mt-1 w-full rounded-xl border-slate-200 focus:border-green-500 focus:ring-green-500">
                        </div>
                        <div>
                            <label class="text-sm text-slate-600">Jam Selesai</label>
                            <input type="time"
                                   name="jam_selesai"
                                   value="{{ request('jam_selesai') }}"
                                   class="mt-1 w-full rounded-xl border-slate-200 focus:border-green-500 focus:ring-green-500">
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit"
                            class="px-6 py-2.5 bg-slate-900 hover:bg-slate-700 text-white rounded-xl font-semibold text-sm transition">
                        🔍 Cari Ruangan
                    </button>
                    <a href="{{ route('rooms.search') }}"
                       class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-semibold text-sm transition">
                        Reset Filter
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- ===== HASIL PENCARIAN ===== --}}
    <div class="fade-up delay-1">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-slate-800">
                Hasil Pencarian
                @if(request()->hasAny(['q', 'status', 'gedung', 'min_capacity']))
                    <span class="text-sm font-normal text-slate-400">
                        ({{ count($rooms ?? []) }} ruangan ditemukan)
                    </span>
                @endif
            </h2>
            <a href="{{ route('rooms.index') }}"
               class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                Lihat semua ruangan →
            </a>
        </div>

        @if(isset($rooms) && count($rooms) > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($rooms as $room)
            <div class="room-card bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden flex flex-col">
                {{-- Foto --}}
                <div class="relative h-48 overflow-hidden bg-slate-200 flex-shrink-0">
                    <img src="{{ $room['foto'] ?? asset('images/default-room.jpg') }}"
                         alt="{{ $room['nama'] }}"
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/55 via-black/10 to-transparent"></div>

                    <span class="absolute top-3 right-3 px-3 py-1.5 rounded-full text-xs font-bold
                        {{ $room['status'] == 'Tersedia' ? 'bg-emerald-400/90 text-emerald-900' : 'bg-red-400/90 text-red-900' }}">
                        {{ $room['status'] }}
                    </span>

                    <div class="absolute bottom-3 left-4 right-4">
                        <h3 class="text-white font-bold text-base">{{ $room['nama'] }}</h3>
                        <p class="text-white/65 text-xs font-mono">{{ $room['kode'] }} · {{ $room['gedung'] }}, Lt. {{ $room['lantai'] }}</p>
                    </div>
                </div>

                {{-- Body --}}
                <div class="p-5 flex-grow">
                    <div class="flex items-center gap-4 mb-3">
                        <span class="text-sm font-semibold">👥 {{ $room['kapasitas'] }} orang</span>
                        <span class="text-sm text-slate-500">🕐 {{ $room['jam_buka'] }} - {{ $room['jam_tutup'] }}</span>
                    </div>

                    <div class="flex flex-wrap gap-1.5 mb-3">
                        @foreach(array_slice($room['fasilitas'] ?? [], 0, 3) as $f)
                        <span class="bg-slate-100 text-slate-600 text-xs px-2.5 py-1 rounded-xl">{{ $f }}</span>
                        @endforeach
                        @if(count($room['fasilitas'] ?? []) > 3)
                        <span class="bg-blue-50 text-blue-600 text-xs px-2.5 py-1 rounded-xl">
                            +{{ count($room['fasilitas']) - 3 }}
                        </span>
                        @endif
                    </div>

                    <p class="text-xs text-slate-500 line-clamp-2">{{ $room['deskripsi'] ?? 'Tidak ada deskripsi.' }}</p>
                </div>

                {{-- Footer --}}
                <div class="border-t border-slate-100 px-5 py-3 flex items-center justify-between">
                    <a href="{{ route('rooms.show', $room['id']) }}"
                       class="text-sm font-semibold text-slate-600 hover:text-slate-900 transition">
                        Lihat Detail →
                    </a>
                    @if($room['status'] == 'Tersedia')
                    <a href="{{ route('bookings.create', ['room_id' => $room['id']]) }}"
                       class="bg-slate-900 hover:bg-slate-700 text-white text-xs font-semibold px-4 py-2 rounded-xl transition">
                        ➕ Booking
                    </a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-white rounded-3xl border border-slate-100 p-12 text-center">
            <p class="text-5xl mb-4">🔍</p>
            <p class="text-lg font-semibold text-slate-600">Tidak ada ruangan ditemukan</p>
            <p class="text-sm text-slate-400 mt-1">Coba ubah kata kunci atau filter pencarian Anda</p>
        </div>
        @endif
    </div>

</div>

@endsection
