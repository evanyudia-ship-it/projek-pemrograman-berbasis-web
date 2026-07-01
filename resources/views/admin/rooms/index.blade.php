@extends('layouts.app')

@section('title', 'Manajemen Ruang - Smart Classroom')
@section('page_title', 'Manajemen Ruang')
@section('page_subtitle', 'Tambah, edit, hapus, dan atur status ruang kampus')

@section('content')

<div class="max-w-7xl mx-auto font-sora space-y-6">

    {{-- ============================================================ --}}
    {{-- FLASH MESSAGES --}}
    {{-- ============================================================ --}}
    @if(session('success'))
    <div id="flashMsg" class="flex items-center gap-3 px-5 py-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 text-sm font-semibold shadow-sm">
        <span class="text-lg">✅</span>
        {{ session('success') }}
        <button onclick="document.getElementById('flashMsg').remove()" class="ml-auto text-emerald-500 hover:text-emerald-700 dark:text-emerald-400 dark:hover:text-emerald-200 font-bold">✕</button>
    </div>
    @endif

    @if(session('error'))
    <div id="flashMsg" class="flex items-center gap-3 px-5 py-4 rounded-2xl bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 text-sm font-semibold shadow-sm">
        <span class="text-lg">❌</span>
        {{ session('error') }}
        <button onclick="document.getElementById('flashMsg').remove()" class="ml-auto text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-200 font-bold">✕</button>
    </div>
    @endif

    {{-- ============================================================ --}}
    {{-- STATISTICS CARDS --}}
    {{-- ============================================================ --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- Total Rooms --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-5 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Total Ruang</p>
                    <p class="text-2xl font-extrabold text-slate-800 dark:text-white mt-1">{{ $totalRooms }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400 group-hover:scale-110 transition">
                    <i class="fas fa-building text-lg"></i>
                </div>
            </div>
            <div class="mt-2">
                <span class="text-xs text-slate-400 dark:text-slate-500">ruang terdaftar</span>
            </div>
        </div>

        {{-- Available --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-5 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Tersedia</p>
                    <p class="text-2xl font-extrabold text-emerald-600 dark:text-emerald-400 mt-1">{{ $totalTersedia }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center text-emerald-600 dark:text-emerald-400 group-hover:scale-110 transition">
                    <i class="fas fa-check-circle text-lg"></i>
                </div>
            </div>
            <div class="mt-2">
                <span class="text-xs text-emerald-600 dark:text-emerald-400 font-medium">siap digunakan</span>
            </div>
        </div>

        {{-- Maintenance --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-5 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Maintenance</p>
                    <p class="text-2xl font-extrabold text-amber-600 dark:text-amber-400 mt-1">{{ $totalRooms - $totalTersedia }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900/50 flex items-center justify-center text-amber-600 dark:text-amber-400 group-hover:scale-110 transition">
                    <i class="fas fa-tools text-lg"></i>
                </div>
            </div>
            <div class="mt-2">
                <span class="text-xs text-amber-600 dark:text-amber-400 font-medium">dalam perbaikan</span>
            </div>
        </div>

        {{-- Total Capacity --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-5 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Total Kapasitas</p>
                    <p class="text-2xl font-extrabold text-blue-600 dark:text-blue-400 mt-1">{{ number_format($totalKapasitas) }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center text-blue-600 dark:text-blue-400 group-hover:scale-110 transition">
                    <i class="fas fa-users text-lg"></i>
                </div>
            </div>
            <div class="mt-2">
                <span class="text-xs text-slate-400 dark:text-slate-500">seluruh ruang</span>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- ROOM TABLE --}}
    {{-- ============================================================ --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">

        {{-- Header --}}
        <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700 bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-indigo-950/30 dark:to-blue-950/30">
            <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                        <i class="fas fa-list-ul text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 dark:text-white">Daftar Ruang Kampus</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Kelola semua ruang, kapasitas, fasilitas, dan statusnya</p>
                    </div>
                </div>
                <a href="{{ route('admin.rooms.create') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold transition shadow-sm shadow-indigo-200 dark:shadow-indigo-900/30">
                    <i class="fas fa-plus-circle"></i> Tambah Ruang
                </a>
            </div>
        </div>

        {{-- Filters --}}
        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-700/30">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-3">
                {{-- Search --}}
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" id="searchRoom"
                           placeholder="Cari nama, kode, gedung..."
                           class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                </div>

                {{-- Status --}}
                <div class="relative">
                    <i class="fas fa-circle absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[6px]"></i>
                    <select id="filterStatus" class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition appearance-none">
                        <option value="">Semua Status</option>
                        <option value="Tersedia">✅ Tersedia</option>
                        <option value="Maintenance">🔧 Maintenance</option>
                    </select>
                </div>

                {{-- Capacity --}}
                <div class="relative">
                    <i class="fas fa-users absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="number" id="filterCapacity"
                           placeholder="Kapasitas minimal"
                           min="0"
                           class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                </div>

                {{-- Faculty --}}
                <div class="relative">
                    <i class="fas fa-building-columns absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <select id="filterFaculty" class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition appearance-none">
                        <option value="">Semua Fakultas</option>
                        @foreach($faculties ?? [] as $faculty)
                        <option value="{{ $faculty->id }}">{{ $faculty->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Action --}}
                <div class="flex gap-2">
                    <button id="resetFilters"
                            class="flex-1 px-4 py-2.5 rounded-xl bg-slate-200 dark:bg-slate-600 hover:bg-slate-300 dark:hover:bg-slate-500 text-slate-700 dark:text-slate-300 text-sm font-semibold transition flex items-center justify-center gap-2">
                        <i class="fas fa-undo"></i> Reset
                    </button>
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 dark:bg-slate-700/50 text-slate-500 dark:text-slate-400 text-xs uppercase tracking-wide">
                    <tr>
                        <th class="text-left px-6 py-4 font-semibold">Ruang</th>
                        <th class="text-left px-6 py-4 font-semibold">Lokasi</th>
                        <th class="text-left px-6 py-4 font-semibold text-center">Kapasitas</th>
                        <th class="text-left px-6 py-4 font-semibold">Jam Operasional</th>
                        <th class="text-left px-6 py-4 font-semibold">Fasilitas</th>
                        <th class="text-left px-6 py-4 font-semibold">Status</th>
                        <th class="text-center px-6 py-4 font-semibold">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 dark:divide-slate-700" id="roomTableBody">
                    @forelse($rooms as $room)
                    <tr class="room-row hover:bg-slate-50 dark:hover:bg-slate-700/50 transition"
                        data-search="{{ strtolower($room->nama) }} {{ strtolower($room->kode) }} {{ strtolower($room->gedung) }}"
                        data-status="{{ $room->status }}"
                        data-capacity="{{ $room->kapasitas }}"
                        data-faculty="{{ $room->faculty_id ?? '' }}"
                        data-name="{{ strtolower($room->nama) }}">

                        {{-- Room Info --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-14 h-14 rounded-xl overflow-hidden shrink-0 bg-slate-100 dark:bg-slate-700 border border-slate-200 dark:border-slate-600">
                                    <img src="{{ $room->foto ? asset('storage/' . $room->foto) : asset('images/default-room.jpg') }}"
                                         alt="{{ $room->nama }}"
                                         class="w-full h-full object-cover"
                                         loading="lazy"
                                         onerror="this.src='{{ asset('images/default-room.jpg') }}'">
                                </div>
                                <div>
                                    <p class="font-bold text-slate-800 dark:text-white">{{ $room->nama }}</p>
                                    <p class="text-xs text-slate-400 dark:text-slate-500 font-mono mt-0.5">{{ $room->kode }}</p>
                                    @if($room->faculty)
                                    <span class="text-xs text-slate-400 dark:text-slate-500 bg-slate-100 dark:bg-slate-700 px-2 py-0.5 rounded-full inline-block mt-0.5">
                                        {{ $room->faculty->name }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </td>

                        {{-- Location --}}
                        <td class="px-6 py-4">
                            <p class="font-semibold text-slate-700 dark:text-slate-300">{{ $room->gedung }}</p>
                            <p class="text-xs text-slate-400 dark:text-slate-500">Lantai {{ $room->lantai }}</p>
                        </td>

                        {{-- Capacity --}}
                        <td class="px-6 py-4 text-center">
                            <span class="font-bold text-slate-800 dark:text-white text-lg">{{ $room->kapasitas }}</span>
                            <p class="text-[10px] text-slate-400 dark:text-slate-500">orang</p>
                        </td>

                        {{-- Hours --}}
                        <td class="px-6 py-4">
                            <p class="font-semibold text-slate-700 dark:text-slate-300 text-xs">
                                <i class="far fa-clock text-slate-400 mr-1"></i>
                                {{ $room->formatted_jam_buka }} – {{ $room->formatted_jam_tutup }}
                            </p>
                            <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">
                                <i class="fas fa-hourglass-half text-[10px] mr-1"></i>
                                Maks. {{ $room->max_durasi_jam }} jam
                            </p>
                        </td>

                        {{-- Facilities --}}
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1 max-w-52">
                                @foreach($room->facilities->take(3) as $f)
                                <span class="bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 text-[10px] px-2 py-0.5 rounded-full font-medium"
                                      title="{{ $f->nama }}">
                                    {{ Str::limit($f->nama, 12) }}
                                </span>
                                @endforeach
                                @if($room->facilities->count() > 3)
                                <span class="bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 text-[10px] px-2 py-0.5 rounded-full font-medium">
                                    +{{ $room->facilities->count() - 3 }}
                                </span>
                                @endif
                                @if($room->facilities->isEmpty())
                                <span class="text-xs text-slate-400 dark:text-slate-500">-</span>
                                @endif
                            </div>
                        </td>

                        {{-- Status --}}
                        <td class="px-6 py-4">
                            <form action="{{ route('admin.rooms.toggle-status', $room->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit"
                                        title="Klik untuk ubah status"
                                        class="px-3 py-1.5 rounded-full text-xs font-bold transition hover:opacity-80
                                            {{ $room->status === 'Tersedia'
                                                ? 'bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300'
                                                : 'bg-amber-100 dark:bg-amber-900/50 text-amber-700 dark:text-amber-300' }}">
                                    <i class="fas {{ $room->status === 'Tersedia' ? 'fa-check-circle' : 'fa-tools' }} mr-1"></i>
                                    {{ $room->status_label }}
                                </button>
                            </form>
                        </td>

                        {{-- Actions --}}
                        <td class="px-6 py-4">
                            <div class="flex justify-center gap-1.5">
                                <a href="{{ route('admin.rooms.show', $room->id) }}"
                                   class="px-3 py-1.5 rounded-lg bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-600 dark:text-slate-300 text-xs font-semibold transition flex items-center gap-1"
                                   title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.rooms.edit', $room->id) }}"
                                   class="px-3 py-1.5 rounded-lg bg-indigo-100 dark:bg-indigo-900/50 hover:bg-indigo-200 dark:hover:bg-indigo-800 text-indigo-700 dark:text-indigo-300 text-xs font-semibold transition flex items-center gap-1"
                                   title="Edit Ruang">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button"
                                        class="btn-delete-room px-3 py-1.5 rounded-lg bg-red-100 dark:bg-red-900/50 hover:bg-red-200 dark:hover:bg-red-800 text-red-700 dark:text-red-300 text-xs font-semibold transition flex items-center gap-1"
                                        data-name="{{ $room->nama }}"
                                        data-url="{{ route('admin.rooms.destroy', $room->id) }}"
                                        title="Hapus Ruang">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center text-slate-400 dark:text-slate-500">
                            <div class="flex flex-col items-center">
                                <div class="w-20 h-20 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-4xl mb-4">
                                    <i class="fas fa-building text-slate-400 dark:text-slate-500"></i>
                                </div>
                                <p class="font-semibold text-slate-600 dark:text-slate-400">Belum ada ruang terdaftar</p>
                                <p class="text-sm text-slate-400 dark:text-slate-500 mt-1">Mulai dengan menambahkan ruang baru</p>
                                <a href="{{ route('admin.rooms.create') }}" class="mt-4 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition shadow-sm shadow-indigo-200 dark:shadow-indigo-900/30 inline-flex items-center gap-2">
                                    <i class="fas fa-plus-circle"></i> Tambah Ruang
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@push('scripts')
<script>
$(document).ready(function() {
    'use strict';

    // ================================================================
    // VARIABLES
    // ================================================================
    const $searchRoom = $('#searchRoom');
    const $filterStatus = $('#filterStatus');
    const $filterCapacity = $('#filterCapacity');
    const $filterFaculty = $('#filterFaculty');
    const $resetBtn = $('#resetFilters');
    const $roomRows = $('.room-row');

    // ================================================================
    // FILTER FUNCTION
    // ================================================================
    function filterRooms() {
        const keyword = $searchRoom.val().toLowerCase().trim();
        const status = $filterStatus.val();
        const minCapacity = parseInt($filterCapacity.val()) || 0;
        const facultyId = $filterFaculty.val();

        let visibleCount = 0;

        $roomRows.each(function() {
            const $row = $(this);
            const searchText = $row.data('search') || '';
            const rowStatus = $row.data('status') || '';
            const capacity = parseInt($row.data('capacity')) || 0;
            const rowFaculty = String($row.data('faculty') || '');

            const matchSearch = keyword === '' || searchText.includes(keyword);
            const matchStatus = status === '' || rowStatus === status;
            const matchCapacity = capacity >= minCapacity;
            const matchFaculty = facultyId === '' || rowFaculty === facultyId;

            const show = matchSearch && matchStatus && matchCapacity && matchFaculty;

            if (show) {
                $row.show();
                visibleCount++;
            } else {
                $row.hide();
            }
        });

        // Show/hide empty state
        if (visibleCount === 0 && $roomRows.length > 0) {
            if ($('#emptyRow').length === 0) {
                const emptyRow = `
                    <tr id="emptyRow">
                        <td colspan="7" class="px-6 py-16 text-center text-slate-400 dark:text-slate-500">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-3xl mb-3">
                                    🔍
                                </div>
                                <p class="font-semibold text-slate-600 dark:text-slate-400">Tidak ada ruang yang sesuai</p>
                                <p class="text-sm text-slate-400 dark:text-slate-500 mt-1">Coba ubah filter pencarian</p>
                                <button id="resetFromEmpty" class="mt-3 px-4 py-2 text-sm bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-semibold transition flex items-center gap-2">
                                    <i class="fas fa-undo"></i> Reset Filter
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
                $('#roomTableBody').append(emptyRow);
                $('#resetFromEmpty').on('click', resetFilters);
            }
        } else {
            $('#emptyRow').remove();
        }
    }

    // ================================================================
    // RESET FILTERS
    // ================================================================
    function resetFilters() {
        $searchRoom.val('');
        $filterStatus.val('');
        $filterCapacity.val('');
        $filterFaculty.val('');
        filterRooms();
    }

    // ================================================================
    // EVENT LISTENERS
    // ================================================================
    $searchRoom.on('keyup', filterRooms);
    $filterStatus.on('change', filterRooms);
    $filterCapacity.on('input', filterRooms);
    $filterFaculty.on('change', filterRooms);
    $resetBtn.on('click', resetFilters);

    // ================================================================
    // DELETE ROOM - SweetAlert
    // ================================================================
    $(document).on('click', '.btn-delete-room', function(e) {
        e.preventDefault();
        const $btn = $(this);
        const name = $btn.data('name');
        const url = $btn.data('url');

        Swal.fire({
            title: 'Hapus Ruangan?',
            html: `Anda yakin ingin menghapus <strong>"${name}"</strong>?<br><span class="text-sm text-slate-500">Tindakan ini tidak dapat dibatalkan!</span>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#64748b',
            confirmButtonText: '<i class="fas fa-trash-alt mr-2"></i> Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $btn.html('<i class="fas fa-spinner fa-spin"></i>');
                $btn.prop('disabled', true);

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Terhapus!',
                            text: 'Data ruangan berhasil dihapus.',
                            timer: 1500,
                            showConfirmButton: false
                        });
                        setTimeout(() => location.reload(), 1500);
                    },
                    error: function(xhr) {
                        const message = xhr.responseJSON?.message || 'Terjadi kesalahan, coba lagi.';
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: message
                        });
                        $btn.html('<i class="fas fa-trash-alt"></i>');
                        $btn.prop('disabled', false);
                    }
                });
            }
        });
    });

    // ================================================================
    // AUTO-DISMISS FLASH MESSAGE
    // ================================================================
    const flashMsg = $('#flashMsg');
    if (flashMsg.length) {
        setTimeout(() => {
            flashMsg.fadeOut(500, function() {
                $(this).remove();
            });
        }, 5000);
    }

    // ================================================================
    // INITIAL FILTER
    // ================================================================
    setTimeout(filterRooms, 100);

});
</script>
@endpush

@endsection
