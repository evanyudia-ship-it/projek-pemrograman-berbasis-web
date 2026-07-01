@extends('layouts.app')

@section('title', 'Manajemen Fasilitas - Smart Classroom')
@section('page_title', 'Manajemen Fasilitas')
@section('page_subtitle', 'Kelola fasilitas ruangan kampus')

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

        {{-- Total Facilities --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-5 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Total Fasilitas</p>
                    <p class="text-2xl font-extrabold text-slate-800 dark:text-white mt-1">{{ $totalFacilities }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400 group-hover:scale-110 transition">
                    <i class="fas fa-cubes text-lg"></i>
                </div>
            </div>
            <div class="mt-2">
                <span class="text-xs text-slate-400 dark:text-slate-500">jenis fasilitas</span>
            </div>
        </div>

        {{-- Used in Rooms --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-5 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Digunakan di Ruang</p>
                    <p class="text-2xl font-extrabold text-blue-600 dark:text-blue-400 mt-1">{{ $totalRoomsWithFacilities }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center text-blue-600 dark:text-blue-400 group-hover:scale-110 transition">
                    <i class="fas fa-door-open text-lg"></i>
                </div>
            </div>
            <div class="mt-2">
                <span class="text-xs text-blue-600 dark:text-blue-400 font-medium">ruang memiliki fasilitas</span>
            </div>
        </div>

        {{-- Categories --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-5 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Kategori</p>
                    <p class="text-2xl font-extrabold text-purple-600 dark:text-purple-400 mt-1">
                        {{ collect($facilities)->pluck('kategori')->filter()->unique()->count() }}
                    </p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-purple-100 dark:bg-purple-900/50 flex items-center justify-center text-purple-600 dark:text-purple-400 group-hover:scale-110 transition">
                    <i class="fas fa-tags text-lg"></i>
                </div>
            </div>
            <div class="mt-2">
                <span class="text-xs text-slate-400 dark:text-slate-500">kategori berbeda</span>
            </div>
        </div>

        {{-- Quick Action --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-5 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Aksi Cepat</p>
                    <a href="{{ route('admin.facilities.create') }}"
                       class="inline-flex items-center gap-2 mt-1 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl transition">
                        <i class="fas fa-plus-circle"></i> Tambah Fasilitas
                    </a>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center text-emerald-600 dark:text-emerald-400 group-hover:scale-110 transition">
                    <i class="fas fa-plus-circle text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- FACILITY TABLE --}}
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
                        <h3 class="font-bold text-slate-800 dark:text-white">Daftar Fasilitas</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Kelola semua fasilitas yang tersedia di kampus</p>
                    </div>
                </div>
                <a href="{{ route('admin.facilities.create') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold transition shadow-sm shadow-indigo-200 dark:shadow-indigo-900/30">
                    <i class="fas fa-plus-circle"></i> Tambah Fasilitas
                </a>
            </div>
        </div>

        {{-- Filters --}}
        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-700/30">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                {{-- Search --}}
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" id="searchFacility"
                           placeholder="Cari fasilitas, kategori..."
                           class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                </div>

                {{-- Category Filter --}}
                <div class="relative">
                    <i class="fas fa-tags absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <select id="filterCategory" class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition appearance-none">
                        <option value="">Semua Kategori</option>
                        @php
                            $categories = collect($facilities)->pluck('kategori')->filter()->unique();
                        @endphp
                        @foreach($categories as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Reset --}}
                <div class="flex gap-2">
                    <button id="resetFilters"
                            class="flex-1 px-4 py-2.5 rounded-xl bg-slate-200 dark:bg-slate-600 hover:bg-slate-300 dark:hover:bg-slate-500 text-slate-700 dark:text-slate-300 text-sm font-semibold transition flex items-center justify-center gap-2">
                        <i class="fas fa-undo"></i> Reset Filter
                    </button>
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 dark:bg-slate-700/50 text-slate-500 dark:text-slate-400 text-xs uppercase tracking-wide">
                    <tr>
                        <th class="text-left px-6 py-4 font-semibold">Fasilitas</th>
                        <th class="text-left px-6 py-4 font-semibold">Kategori</th>
                        <th class="text-left px-6 py-4 font-semibold text-center">Digunakan di</th>
                        <th class="text-left px-6 py-4 font-semibold">Status</th>
                        <th class="text-center px-6 py-4 font-semibold">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 dark:divide-slate-700" id="facilityTable">
                    @forelse($facilities as $facility)
                    <tr class="facility-row hover:bg-slate-50 dark:hover:bg-slate-700/50 transition"
                        data-search="{{ strtolower($facility->nama) }} {{ strtolower($facility->kategori ?? '') }}"
                        data-category="{{ $facility->kategori }}">

                        {{-- Facility Name + Icon --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-11 h-11 rounded-xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-2xl border border-slate-200 dark:border-slate-600">
                                    {{ $facility->icon ?? '📦' }}
                                </div>
                                <div>
                                    <p class="font-bold text-slate-800 dark:text-white">{{ $facility->nama }}</p>
                                    @if($facility->deskripsi)
                                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5 line-clamp-1">{{ $facility->deskripsi }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>

                        {{-- Category --}}
                        <td class="px-6 py-4">
                            @if($facility->kategori)
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300">
                                {{ $facility->kategori }}
                            </span>
                            @else
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400">
                                Tanpa Kategori
                            </span>
                            @endif
                        </td>

                        {{-- Used In --}}
                        <td class="px-6 py-4 text-center">
                            <span class="font-bold text-slate-800 dark:text-white text-lg">{{ $facility->rooms_count }}</span>
                            <span class="text-xs text-slate-400 dark:text-slate-500"> ruang</span>
                        </td>

                        {{-- Status --}}
                        <td class="px-6 py-4">
                            <span class="px-3 py-1.5 rounded-full text-xs font-bold bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300 inline-flex items-center gap-1.5">
                                <i class="fas fa-check-circle"></i> Aktif
                            </span>
                        </td>

                        {{-- Actions --}}
                        <td class="px-6 py-4">
                            <div class="flex justify-center gap-1.5">
                                <a href="{{ route('admin.facilities.show', $facility->id) }}"
                                   class="px-3 py-1.5 rounded-lg bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-600 dark:text-slate-300 text-xs font-semibold transition flex items-center gap-1"
                                   title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.facilities.edit', $facility->id) }}"
                                   class="px-3 py-1.5 rounded-lg bg-indigo-100 dark:bg-indigo-900/50 hover:bg-indigo-200 dark:hover:bg-indigo-800 text-indigo-700 dark:text-indigo-300 text-xs font-semibold transition flex items-center gap-1"
                                   title="Edit Fasilitas">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button"
                                        class="btn-delete-facility px-3 py-1.5 rounded-lg bg-red-100 dark:bg-red-900/50 hover:bg-red-200 dark:hover:bg-red-800 text-red-700 dark:text-red-300 text-xs font-semibold transition flex items-center gap-1"
                                        data-name="{{ $facility->nama }}"
                                        data-url="{{ route('admin.facilities.destroy', $facility->id) }}"
                                        title="Hapus Fasilitas">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center text-slate-400 dark:text-slate-500">
                            <div class="flex flex-col items-center">
                                <div class="w-20 h-20 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-4xl mb-4">
                                    <i class="fas fa-cubes text-slate-400 dark:text-slate-500"></i>
                                </div>
                                <p class="font-semibold text-slate-600 dark:text-slate-400">Belum ada fasilitas terdaftar</p>
                                <p class="text-sm text-slate-400 dark:text-slate-500 mt-1">Mulai dengan menambahkan fasilitas baru</p>
                                <a href="{{ route('admin.facilities.create') }}" class="mt-4 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition shadow-sm shadow-indigo-200 dark:shadow-indigo-900/30 inline-flex items-center gap-2">
                                    <i class="fas fa-plus-circle"></i> Tambah Fasilitas
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
    const $searchFacility = $('#searchFacility');
    const $filterCategory = $('#filterCategory');
    const $resetBtn = $('#resetFilters');
    const $facilityRows = $('.facility-row');

    // ================================================================
    // FILTER FUNCTION
    // ================================================================
    function filterFacilities() {
        const keyword = $searchFacility.val().toLowerCase().trim();
        const category = $filterCategory.val();

        let visibleCount = 0;

        $facilityRows.each(function() {
            const $row = $(this);
            const searchText = $row.data('search') || '';
            const rowCategory = $row.data('category') || '';

            const matchSearch = keyword === '' || searchText.includes(keyword);
            const matchCategory = category === '' || rowCategory === category;

            const show = matchSearch && matchCategory;

            if (show) {
                $row.show();
                visibleCount++;
            } else {
                $row.hide();
            }
        });

        // Show/hide empty state
        if (visibleCount === 0 && $facilityRows.length > 0) {
            if ($('#emptyRow').length === 0) {
                const emptyRow = `
                    <tr id="emptyRow">
                        <td colspan="5" class="px-6 py-16 text-center text-slate-400 dark:text-slate-500">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-3xl mb-3">
                                    🔍
                                </div>
                                <p class="font-semibold text-slate-600 dark:text-slate-400">Tidak ada fasilitas yang sesuai</p>
                                <p class="text-sm text-slate-400 dark:text-slate-500 mt-1">Coba ubah filter pencarian</p>
                                <button id="resetFromEmpty" class="mt-3 px-4 py-2 text-sm bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-semibold transition flex items-center gap-2">
                                    <i class="fas fa-undo"></i> Reset Filter
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
                $('#facilityTable').append(emptyRow);
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
        $searchFacility.val('');
        $filterCategory.val('');
        filterFacilities();
    }

    // ================================================================
    // EVENT LISTENERS
    // ================================================================
    $searchFacility.on('input', filterFacilities);
    $filterCategory.on('change', filterFacilities);
    $resetBtn.on('click', resetFilters);

    // ================================================================
    // DELETE FACILITY - SweetAlert
    // ================================================================
    $(document).on('click', '.btn-delete-facility', function(e) {
        e.preventDefault();
        const $btn = $(this);
        const name = $btn.data('name');
        const url = $btn.data('url');

        Swal.fire({
            title: 'Hapus Fasilitas?',
            html: `
                <p>Anda yakin ingin menghapus fasilitas <strong>"${name}"</strong>?</p>
                <p class="text-sm text-red-500 mt-2">⚠️ Tindakan ini akan menghapus semua relasi dengan ruangan dan tidak dapat dibatalkan!</p>
            `,
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
                            text: 'Fasilitas berhasil dihapus.',
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
    setTimeout(filterFacilities, 100);

});
</script>
@endpush

@endsection
