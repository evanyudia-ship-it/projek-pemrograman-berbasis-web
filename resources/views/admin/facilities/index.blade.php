@extends('layouts.app')

@section('title', 'Manajemen Fasilitas - Admin')
@section('page_title', 'Manajemen Fasilitas')
@section('page_subtitle', 'Kelola fasilitas ruangan kampus')

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
        <p class="text-xs text-slate-500 mb-1">Total Fasilitas</p>
        <p class="text-3xl font-extrabold text-slate-900">{{ $totalFacilities }}</p>
        <p class="text-xs text-slate-400 mt-1">jenis fasilitas</p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
        <p class="text-xs text-slate-500 mb-1">Digunakan di Ruang</p>
        <p class="text-3xl font-extrabold text-blue-600">{{ $totalRoomsWithFacilities }}</p>
        <p class="text-xs text-slate-400 mt-1">ruang memiliki fasilitas</p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
        <p class="text-xs text-slate-500 mb-1">Kategori</p>
        <p class="text-3xl font-extrabold text-purple-600">
            {{ collect($facilities)->pluck('kategori')->filter()->unique()->count() }}
        </p>
        <p class="text-xs text-slate-400 mt-1">kategori berbeda</p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
        <p class="text-xs text-slate-500 mb-1">Aksi Cepat</p>
        <a href="{{ route('admin.facilities.create') }}"
           class="inline-block mt-2 px-4 py-2 bg-slate-900 hover:bg-slate-700 text-white text-xs font-bold rounded-xl transition">
            ➕ Tambah Fasilitas
        </a>
    </div>

</div>

{{-- ===== TABEL FASILITAS ===== --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

    {{-- Header --}}
    <div class="p-6 border-b border-slate-200">
        <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">
            <div>
                <h3 class="font-bold text-lg">Daftar Fasilitas</h3>
                <p class="text-sm text-slate-500">Kelola semua fasilitas yang tersedia di kampus</p>
            </div>
            <div class="flex gap-3 flex-wrap">
                {{-- Search --}}
                <input type="text" id="searchFacility"
                       placeholder="Cari fasilitas..."
                       class="rounded-xl text-sm">

                {{-- Filter kategori --}}
                <select id="filterCategory" class="rounded-xl text-sm">
                    <option value="">Semua Kategori</option>
                    @php
                        $categories = collect($facilities)->pluck('kategori')->filter()->unique();
                    @endphp
                    @foreach($categories as $cat)
                    <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>

                {{-- Tambah --}}
                <a href="{{ route('admin.facilities.create') }}"
                   class="px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-700 text-white font-semibold text-sm transition flex items-center gap-2">
                    <span>➕</span> Tambah Fasilitas
                </a>
            </div>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wide">
                <tr>
                    <th class="text-left px-6 py-4">Fasilitas</th>
                    <th class="text-left px-6 py-4">Kategori</th>
                    <th class="text-left px-6 py-4">Digunakan di</th>
                    <th class="text-left px-6 py-4">Status</th>
                    <th class="text-center px-6 py-4">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100" id="facilityTable">

                @forelse($facilities as $facility)
                <tr class="facility-row hover:bg-slate-50 transition"
                    data-search="{{ strtolower($facility->nama) }} {{ strtolower($facility->kategori ?? '') }}"
                    data-category="{{ $facility->kategori }}">

                    {{-- Nama + Icon --}}
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-2xl">
                                {{ $facility->icon ?? '📦' }}
                            </div>
                            <div>
                                <p class="font-bold text-slate-800">{{ $facility->nama }}</p>
                                @if($facility->deskripsi)
                                <p class="text-xs text-slate-400 mt-0.5 line-clamp-1">{{ $facility->deskripsi }}</p>
                                @endif
                            </div>
                        </div>
                    </td>

                    {{-- Kategori --}}
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600">
                            {{ $facility->kategori ?? 'Tanpa Kategori' }}
                        </span>
                    </td>

                    {{-- Digunakan di --}}
                    <td class="px-6 py-4">
                        <span class="font-semibold text-slate-800">{{ $facility->rooms_count }}</span>
                        <span class="text-xs text-slate-400">ruang</span>
                    </td>

                    {{-- Status (aktif otomatis) --}}
                    <td class="px-6 py-4">
                        <span class="px-3 py-1.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">
                            ✅ Aktif
                        </span>
                    </td>

                    {{-- Aksi --}}
                    <td class="px-6 py-4">
                        <div class="flex justify-center gap-2">
                            <a href="{{ route('admin.facilities.show', $facility->id) }}"
                               class="px-3 py-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold text-xs transition">
                                👁 Lihat
                            </a>
                            <a href="{{ route('admin.facilities.edit', $facility->id) }}"
                               class="px-3 py-2 rounded-lg bg-blue-100 hover:bg-blue-200 text-blue-700 font-semibold text-xs transition">
                                ✏️ Edit
                            </a>
                            <form action="{{ route('admin.facilities.destroy', $facility->id) }}" method="POST"
                                  onsubmit="return confirm('Hapus fasilitas {{ addslashes($facility->nama) }}? Tindakan ini akan menghapus semua relasi dengan ruangan.')">
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
                    <td colspan="5" class="px-6 py-16 text-center text-slate-400">
                        <p class="text-4xl mb-3">📦</p>
                        <p class="font-semibold">Belum ada fasilitas terdaftar</p>
                        <p class="text-xs mt-1">Klik "Tambah Fasilitas" untuk menambahkan</p>
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
    function filterFacilities() {
        const keyword = $('#searchFacility').val().toLowerCase();
        const category = $('#filterCategory').val();

        $('.facility-row').each(function () {
            const matchSearch = $(this).data('search').includes(keyword);
            const matchCategory = category === '' || $(this).data('category') === category;
            $(this).toggle(matchSearch && matchCategory);
        });
    }

    $('#searchFacility').on('input', filterFacilities);
    $('#filterCategory').on('change', filterFacilities);

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
