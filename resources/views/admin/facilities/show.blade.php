@extends('layouts.app')

@section('title', 'Detail Fasilitas - Admin')
@section('page_title', 'Detail Fasilitas')
@section('page_subtitle', $facility->nama)

@section('content')

<div class="max-w-6xl mx-auto font-sora space-y-6">

    {{-- ===== NAV BACK ===== --}}
    <div class="fade-up">
        <a href="{{ route('admin.facilities.index') }}"
           class="text-sm text-slate-500 hover:text-slate-700 transition flex items-center gap-2">
            ← Kembali ke Manajemen Fasilitas
        </a>
    </div>

    {{-- ===== HERO ===== --}}
    <div class="fade-up bg-white rounded-3xl border border-slate-100 shadow-sm p-6">
        <div class="flex items-start gap-6">
            <div class="w-20 h-20 bg-slate-100 rounded-2xl flex items-center justify-center text-5xl shrink-0">
                {{ $facility->icon ?? '📦' }}
            </div>
            <div class="flex-1">
                <h1 class="text-2xl font-bold text-slate-900">{{ $facility->nama }}</h1>
                <div class="flex flex-wrap items-center gap-3 mt-1">
                    @if($facility->kategori)
                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600">
                        📂 {{ $facility->kategori }}
                    </span>
                    @endif
                    <span class="text-xs text-slate-400">
                        Digunakan di {{ $facility->rooms_count }} ruangan
                    </span>
                </div>
                @if($facility->deskripsi)
                <p class="text-sm text-slate-600 mt-3 border-l-2 border-slate-200 pl-4">
                    {{ $facility->deskripsi }}
                </p>
                @endif
            </div>
            <div class="flex gap-2 shrink-0">
                <a href="{{ route('admin.facilities.edit', $facility->id) }}"
                   class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-semibold rounded-xl transition">
                    ✏️ Edit
                </a>
                <form action="{{ route('admin.facilities.destroy', $facility->id) }}" method="POST"
                      onsubmit="return confirm('Hapus fasilitas {{ addslashes($facility->nama) }}?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-semibold rounded-xl transition">
                        🗑 Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- ===== DAFTAR RUANGAN ===== --}}
    <div class="fade-up delay-1 bg-white rounded-3xl border border-slate-100 shadow-sm p-6">
        <div class="flex items-center justify-between mb-5">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Ruangan dengan Fasilitas Ini</p>
                <p class="text-sm text-slate-600 mt-1">{{ $rooms->count() }} ruangan terpasang</p>
            </div>
            <a href="{{ route('admin.room-facilities.index') }}?room_id="
               class="text-xs text-blue-600 hover:text-blue-700 font-medium">
                Kelola Fasilitas Ruang →
            </a>
        </div>

        @if($rooms->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($rooms as $room)
            <div class="flex items-center gap-3 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                <div class="w-12 h-12 rounded-xl overflow-hidden bg-slate-200 shrink-0">
                    <img src="{{ $room->foto ? asset('storage/' . $room->foto) : asset('images/default-room.jpg') }}"
                         alt="{{ $room->nama }}"
                         class="w-full h-full object-cover">
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-slate-800 text-sm truncate">{{ $room->nama }}</p>
                    <p class="text-xs text-slate-400 font-mono">{{ $room->kode }}</p>
                </div>
                <span class="px-2 py-1 rounded-lg text-xs font-bold
                    {{ $room->pivot->status == 'tersedia' ? 'bg-emerald-100 text-emerald-700' : '' }}
                    {{ $room->pivot->status == 'rusak' ? 'bg-red-100 text-red-700' : '' }}
                    {{ $room->pivot->status == 'maintenance' ? 'bg-amber-100 text-amber-700' : '' }}">
                    {{ $room->pivot->getStatusLabelAttribute() }}
                </span>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-12">
            <p class="text-4xl mb-3">🏠</p>
            <p class="text-slate-500 font-semibold">Belum ada ruangan yang menggunakan fasilitas ini</p>
            <p class="text-sm text-slate-400 mt-1">Tambahkan fasilitas ke ruangan melalui menu Kelola Fasilitas Ruang</p>
            <a href="{{ route('admin.room-facilities.index') }}"
               class="inline-block mt-4 px-4 py-2 bg-slate-900 hover:bg-slate-700 text-white text-sm font-semibold rounded-xl transition">
                Kelola Fasilitas Ruang →
            </a>
        </div>
        @endif
    </div>

    {{-- ===== STATISTIK ===== --}}
    <div class="grid grid-cols-2 gap-4 fade-up delay-2">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 text-center">
            <p class="text-xs text-slate-400 mb-1">Status Tersedia</p>
            <p class="text-2xl font-bold text-emerald-600">
                {{ $rooms->filter(fn($r) => $r->pivot->status == 'tersedia')->count() }}
            </p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 text-center">
            <p class="text-xs text-slate-400 mb-1">Status Rusak / Maintenance</p>
            <p class="text-2xl font-bold text-red-500">
                {{ $rooms->filter(fn($r) => $r->pivot->status != 'tersedia')->count() }}
            </p>
        </div>
    </div>

</div>

@endsection
