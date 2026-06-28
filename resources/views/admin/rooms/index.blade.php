@extends('layouts.app')

@section('title', 'Manajemen Ruang - Admin')
@section('page_title', 'Manajemen Ruang')
@section('page_subtitle', 'Tambah, edit, hapus, dan atur status ruang kampus')

@section('content')

{{-- ===== FLASH MESSAGES ===== --}}
@if(session('success'))
<div id="flashMsg"
     class="mb-5 flex items-center gap-3 px-5 py-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-semibold">
    <span class="text-lg">✅</span>
    {{ session('success') }}
    <button onclick="document.getElementById('flashMsg').remove()"
            class="ml-auto text-emerald-500 hover:text-emerald-700 font-bold">✕</button>
</div>
@endif

@if(session('error'))
<div id="flashMsg"
     class="mb-5 flex items-center gap-3 px-5 py-4 rounded-2xl bg-red-50 border border-red-200 text-red-700 text-sm font-semibold">
    <span class="text-lg">❌</span>
    {{ session('error') }}
    <button onclick="document.getElementById('flashMsg').remove()"
            class="ml-auto text-red-500 hover:text-red-700 font-bold">✕</button>
</div>
@endif

{{-- ===== STAT CARDS ===== --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
        <p class="text-xs text-slate-500 mb-1">Total Ruang</p>
        <p class="text-3xl font-extrabold text-slate-900">{{ $totalRooms }}</p>
        <p class="text-xs text-slate-400 mt-1">ruang terdaftar</p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
        <p class="text-xs text-slate-500 mb-1">Tersedia</p>
        <p class="text-3xl font-extrabold text-emerald-600">{{ $totalTersedia }}</p>
        <p class="text-xs text-slate-400 mt-1">siap digunakan</p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
        <p class="text-xs text-slate-500 mb-1">Maintenance</p>
        <p class="text-3xl font-extrabold text-amber-500">{{ $totalRooms - $totalTersedia }}</p>
        <p class="text-xs text-slate-400 mt-1">dalam perbaikan</p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
        <p class="text-xs text-slate-500 mb-1">Total Kapasitas</p>
        <p class="text-3xl font-extrabold text-blue-600">{{ number_format($totalKapasitas) }}</p>
        <p class="text-xs text-slate-400 mt-1">orang (seluruh ruang)</p>
    </div>

</div>

{{-- ===== TABEL RUANG ===== --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

    {{-- Header --}}
    <div class="p-6 border-b border-slate-200">
        <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">
            <div>
                <h3 class="font-bold text-lg">Daftar Ruang Kampus</h3>
                <p class="text-sm text-slate-500">Kelola semua ruang, kapasitas, fasilitas, dan statusnya</p>
            </div>
            <div class="flex gap-3 flex-wrap">
                {{-- Search --}}
                <input type="text" id="searchRoom"
                       placeholder="Cari nama, kode, gedung..."
                       class="rounded-xl text-sm">

                {{-- Filter status --}}
                <select id="filterStatus" class="rounded-xl text-sm">
                    <option value="">Semua Status</option>
                    <option value="Tersedia">Tersedia</option>
                    <option value="Maintenance">Maintenance</option>
                </select>

                {{-- Tambah --}}
                <a href="{{ route('admin.rooms.create') }}"
                   class="px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-700 text-white font-semibold text-sm transition flex items-center gap-2">
                    <span>➕</span> Tambah Ruang
                </a>

                {{-- Reset --}}
                <form action="{{ route('admin.rooms.reset') }}" method="POST"
                      onsubmit="return confirm('Reset semua data ruang ke default?')">
                    @csrf
                    <button type="submit"
                            class="px-4 py-2.5 rounded-xl border border-slate-200 hover:border-slate-300 text-slate-500 hover:text-slate-700 font-semibold text-sm transition">
                        🔄 Reset
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wide">
                <tr>
                    <th class="text-left px-6 py-4">Ruang</th>
                    <th class="text-left px-6 py-4">Lokasi</th>
                    <th class="text-left px-6 py-4">Kapasitas</th>
                    <th class="text-left px-6 py-4">Jam Operasional</th>
                    <th class="text-left px-6 py-4">Fasilitas</th>
                    <th class="text-left px-6 py-4">Status</th>
                    <th class="text-center px-6 py-4">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100" id="roomTable">

                @forelse($rooms as $room)
                <tr class="room-row hover:bg-slate-50 transition"
                    data-search="{{ strtolower($room->nama) }} {{ strtolower($room->kode) }} {{ strtolower($room->gedung) }}"
                    data-status="{{ $room->status }}">

                    {{-- Foto + Nama --}}
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-14 h-14 rounded-xl overflow-hidden shrink-0 bg-slate-100">
                                <img src="{{ $room->foto ? asset('storage/' . $room->foto) : asset('images/default-room.jpg') }}"
                                     alt="{{ $room->nama }}"
                                     class="w-full h-full object-cover">
                            </div>
                            <div>
                                <p class="font-bold text-slate-800">{{ $room->nama }}</p>
                                <p class="text-xs text-slate-400 font-mono mt-0.5">{{ $room->kode }}</p>
                                @if($room->faculty)
                                <p class="text-xs text-slate-400">{{ $room->faculty->name }}</p>
                                @endif
                            </div>
                        </div>
                    </td>

                    {{-- Lokasi --}}
                    <td class="px-6 py-4">
                        <p class="font-semibold text-slate-700">{{ $room->gedung }}</p>
                        <p class="text-xs text-slate-400">Lantai {{ $room->lantai }}</p>
                    </td>

                    {{-- Kapasitas --}}
                    <td class="px-6 py-4">
                        <p class="font-bold text-slate-800">{{ $room->kapasitas }}</p>
                        <p class="text-xs text-slate-400">orang</p>
                    </td>

                    {{-- Jam --}}
                    <td class="px-6 py-4">
                        <p class="font-semibold text-slate-700 text-xs">
                            {{ $room->formatted_jam_buka }} – {{ $room->formatted_jam_tutup }}
                        </p>
                        <p class="text-xs text-slate-400 mt-0.5">Maks. {{ $room->max_durasi_jam }} jam</p>
                    </td>

                    {{-- Fasilitas --}}
                    <td class="px-6 py-4">
                        <div class="flex flex-wrap gap-1 max-w-50">
                            @foreach($room->facilities->take(3) as $f)
                            <span class="bg-slate-100 text-slate-600 text-[10px] px-2 py-0.5 rounded-lg font-medium">
                                {{ $f->nama }}
                            </span>
                            @endforeach
                            @if($room->facilities->count() > 3)
                            <span class="bg-blue-50 text-blue-600 text-[10px] px-2 py-0.5 rounded-lg font-medium">
                                +{{ $room->facilities->count() - 3 }}
                            </span>
                            @endif
                            @if($room->facilities->isEmpty())
                            <span class="text-xs text-slate-400">-</span>
                            @endif
                        </div>
                    </td>

                    {{-- Status --}}
                    <td class="px-6 py-4">
                        <form action="{{ route('admin.rooms.toggleStatus', $room->id) }}" method="POST">
                            @csrf
                            <button type="submit"
                                    title="Klik untuk ubah status"
                                    class="px-3 py-1.5 rounded-full text-xs font-bold transition hover:opacity-80
                                        {{ $room->status === 'Tersedia'
                                            ? 'bg-emerald-100 text-emerald-700'
                                            : 'bg-amber-100 text-amber-700' }}">
                                {{ $room->status_label }}
                            </button>
                        </form>
                    </td>

                    {{-- Aksi --}}
                    <td class="px-6 py-4">
                        <div class="flex justify-center gap-2">
                            <a href="{{ route('admin.rooms.show', $room->id) }}"
                               class="px-3 py-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold text-xs transition">
                                👁 Lihat
                            </a>
                            <a href="{{ route('admin.rooms.edit', $room->id) }}"
                               class="px-3 py-2 rounded-lg bg-blue-100 hover:bg-blue-200 text-blue-700 font-semibold text-xs transition">
                                ✏️ Edit
                            </a>
                            <form action="{{ route('admin.rooms.destroy', $room->id) }}" method="POST"
                                  onsubmit="return confirm('Hapus ruang {{ addslashes($room->nama) }}? Tindakan ini tidak dapat dibatalkan.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="px-3 py-2 rounded-lg bg-red-100 hover:bg-red-200 text-red-700 font-semibold text-xs transition">
                                    🗑 Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-16 text-center text-slate-400">
                        <p class="text-4xl mb-3">🏫</p>
                        <p class="font-semibold">Belum ada ruang terdaftar</p>
                        <p class="text-xs mt-1">Klik "Tambah Ruang" untuk menambahkan</p>
                    </td>
                </tr>
                @endforelse

            </tbody>
        </table>
    </div>

</div>

@push('scripts')
<script>
$(document).ready(function () {
    // ── Search & Filter ──────────────────────────────────────────────────
    function filterRooms() {
        const keyword = $('#searchRoom').val().toLowerCase();
        const status  = $('#filterStatus').val();

        $('.room-row').each(function () {
            const matchSearch = $(this).data('search').includes(keyword);
            const matchStatus = status === '' || $(this).data('status') === status;
            $(this).toggle(matchSearch && matchStatus);
        });
    }

    $('#searchRoom').on('input', filterRooms);
    $('#filterStatus').on('change', filterRooms);

    // ── Auto-dismiss flash message ───────────────────────────────────────
    const flashMsg = $('#flashMsg');
    if (flashMsg.length) {
        setTimeout(() => {
            flashMsg.fadeOut(400, function () {
                $(this).remove();
            });
        }, 4000);
    }
});
</script>
@endpush

@endsection
