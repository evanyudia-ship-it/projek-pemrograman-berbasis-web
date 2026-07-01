@extends('layouts.app')

@section('title', 'Manajemen Fakultas - Smart Classroom')
@section('page_title', 'Manajemen Fakultas')
@section('page_subtitle', 'Kelola data fakultas pada sistem')

@section('content')

<div class="max-w-7xl mx-auto font-sora space-y-6">
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
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- Total Faculties --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-5 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Total Fakultas</p>
                    <p class="text-2xl font-extrabold text-slate-800 dark:text-white mt-1">{{ $faculties->total() }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400 group-hover:scale-110 transition">
                    <i class="fas fa-building-columns text-lg"></i>
                </div>
            </div>
            <div class="mt-2">
                <span class="text-xs text-slate-400 dark:text-slate-500">fakultas terdaftar</span>
            </div>
        </div>

        {{-- Active --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-5 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Aktif</p>
                    <p class="text-2xl font-extrabold text-emerald-600 dark:text-emerald-400 mt-1">
                        {{ $faculties->filter(fn($f) => $f->status === 'active')->count() }}
                    </p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center text-emerald-600 dark:text-emerald-400 group-hover:scale-110 transition">
                    <i class="fas fa-check-circle text-lg"></i>
                </div>
            </div>
            <div class="mt-2">
                <span class="text-xs text-emerald-600 dark:text-emerald-400 font-medium">fakultas aktif</span>
            </div>
        </div>

        {{-- Inactive --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-5 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Nonaktif</p>
                    <p class="text-2xl font-extrabold text-slate-400 dark:text-slate-500 mt-1">
                        {{ $faculties->filter(fn($f) => $f->status === 'inactive')->count() }}
                    </p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-slate-400 dark:text-slate-500 group-hover:scale-110 transition">
                    <i class="fas fa-user-slash text-lg"></i>
                </div>
            </div>
            <div class="mt-2">
                <span class="text-xs text-slate-400 dark:text-slate-500">fakultas nonaktif</span>
            </div>
        </div>

        {{-- Total Users --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-5 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Total User</p>
                    <p class="text-2xl font-extrabold text-blue-600 dark:text-blue-400 mt-1">
                        {{ $faculties->sum('users_count') }}
                    </p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center text-blue-600 dark:text-blue-400 group-hover:scale-110 transition">
                    <i class="fas fa-users text-lg"></i>
                </div>
            </div>
            <div class="mt-2">
                <span class="text-xs text-blue-600 dark:text-blue-400 font-medium">terdaftar di fakultas</span>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- FACULTY TABLE --}}
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
                        <h3 class="font-bold text-slate-800 dark:text-white">Daftar Fakultas</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Kelola nama, kode, deskripsi, dan status fakultas</p>
                    </div>
                </div>
                <a href="{{ route('admin.faculties.create') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold transition shadow-sm shadow-indigo-200 dark:shadow-indigo-900/30">
                    <i class="fas fa-plus-circle"></i> Tambah Fakultas
                </a>
            </div>
        </div>

        {{-- Filters --}}
        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-700/30">
            <form method="GET" action="{{ route('admin.faculties.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-3">
                {{-- Search --}}
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Cari nama atau kode..."
                           class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                </div>

                {{-- Status Filter --}}
                <div class="relative">
                    <i class="fas fa-circle absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[6px]"></i>
                    <select name="status"
                            class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition appearance-none">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>✅ Aktif</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>⛔ Nonaktif</option>
                    </select>
                </div>

                {{-- Sort --}}
                <div class="relative">
                    <i class="fas fa-sort absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <select name="sort" id="sortFilter"
                            class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition appearance-none">
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Sortir Nama</option>
                        <option value="code" {{ request('sort') == 'code' ? 'selected' : '' }}>Sortir Kode</option>
                        <option value="users_count" {{ request('sort') == 'users_count' ? 'selected' : '' }}>Sortir Jumlah User</option>
                        <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Sortir Terbaru</option>
                    </select>
                </div>

                {{-- Actions --}}
                <div class="flex gap-2">
                    <button type="submit"
                            class="flex-1 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold transition flex items-center justify-center gap-2">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('admin.faculties.index') }}"
                       class="px-4 py-2.5 rounded-xl bg-slate-200 dark:bg-slate-600 hover:bg-slate-300 dark:hover:bg-slate-500 text-slate-700 dark:text-slate-300 text-sm font-semibold transition flex items-center justify-center gap-2">
                        <i class="fas fa-undo"></i>
                    </a>
                </div>
            </form>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 dark:bg-slate-700/50 text-slate-500 dark:text-slate-400 text-xs uppercase tracking-wide">
                    <tr>
                        <th class="text-left px-6 py-4 font-semibold">Fakultas</th>
                        <th class="text-left px-6 py-4 font-semibold">Kode</th>
                        <th class="text-left px-6 py-4 font-semibold">Deskripsi</th>
                        <th class="text-left px-6 py-4 font-semibold text-center">Jumlah User</th>
                        <th class="text-left px-6 py-4 font-semibold">Status</th>
                        <th class="text-center px-6 py-4 font-semibold">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($faculties as $faculty)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition">
                        {{-- Faculty Name --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl {{ $faculty->status === 'active' ? 'bg-indigo-100 dark:bg-indigo-900/50' : 'bg-slate-100 dark:bg-slate-700' }} flex items-center justify-center {{ $faculty->status === 'active' ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500' }} font-bold text-sm">
                                    {{ strtoupper(substr($faculty->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-bold text-slate-800 dark:text-white {{ $faculty->status === 'inactive' ? 'opacity-60' : '' }}">
                                        {{ $faculty->name }}
                                    </p>
                                    <p class="text-xs text-slate-400 dark:text-slate-500">
                                        Dibuat {{ $faculty->created_at->translatedFormat('d M Y') }}
                                    </p>
                                </div>
                            </div>
                        </td>

                        {{-- Code --}}
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full {{ $faculty->status === 'active' ? 'bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400' }} text-xs font-bold inline-flex items-center gap-1.5">
                                <i class="fas fa-tag text-[10px]"></i>
                                {{ $faculty->code }}
                            </span>
                        </td>

                        {{-- Description --}}
                        <td class="px-6 py-4">
                            <p class="text-slate-600 dark:text-slate-300 text-sm {{ $faculty->status === 'inactive' ? 'opacity-60' : '' }}">
                                {{ Str::limit($faculty->description ?? '-', 60) }}
                            </p>
                            @if($faculty->description && strlen($faculty->description) > 60)
                            <span class="text-xs text-slate-400 dark:text-slate-500">...</span>
                            @endif
                        </td>

                        {{-- Users Count --}}
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <span class="font-bold text-slate-800 dark:text-white text-lg {{ $faculty->status === 'inactive' ? 'opacity-60' : '' }}">
                                    {{ $faculty->users_count ?? 0 }}
                                </span>
                                <span class="text-xs text-slate-400 dark:text-slate-500">user</span>
                            </div>
                        </td>

                        {{-- Status --}}
                        <td class="px-6 py-4">
                            @if($faculty->status === 'active')
                                <span class="px-3 py-1.5 rounded-full bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300 text-xs font-bold inline-flex items-center gap-1.5">
                                    <i class="fas fa-check-circle"></i> Aktif
                                </span>
                            @else
                                <span class="px-3 py-1.5 rounded-full bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 text-xs font-bold inline-flex items-center gap-1.5">
                                    <i class="fas fa-user-slash"></i> Nonaktif
                                </span>
                            @endif
                        </td>

                        {{-- Actions --}}
                        <td class="px-6 py-4">
                            <div class="flex justify-center gap-1.5">
                                <a href="{{ route('admin.faculties.show', $faculty) }}"
                                   class="px-3 py-1.5 rounded-lg bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-600 dark:text-slate-300 text-xs font-semibold transition flex items-center gap-1"
                                   title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <a href="{{ route('admin.faculties.edit', $faculty) }}"
                                   class="px-3 py-1.5 rounded-lg bg-indigo-100 dark:bg-indigo-900/50 hover:bg-indigo-200 dark:hover:bg-indigo-800 text-indigo-700 dark:text-indigo-300 text-xs font-semibold transition flex items-center gap-1"
                                   title="Edit Fakultas">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form method="POST"
                                      action="{{ route('admin.faculties.destroy', $faculty) }}"
                                      onsubmit="return confirm('Yakin ingin menghapus fakultas {{ $faculty->name }}? Tindakan ini tidak dapat dibatalkan.')"
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="px-3 py-1.5 rounded-lg bg-red-100 dark:bg-red-900/50 hover:bg-red-200 dark:hover:bg-red-800 text-red-700 dark:text-red-300 text-xs font-semibold transition flex items-center gap-1"
                                            title="Hapus Fakultas">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center text-slate-400 dark:text-slate-500">
                            <div class="flex flex-col items-center">
                                <div class="w-20 h-20 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-4xl mb-4">
                                    <i class="fas fa-building-columns text-slate-400 dark:text-slate-500"></i>
                                </div>
                                <p class="font-semibold text-slate-600 dark:text-slate-400">Belum ada data fakultas</p>
                                <p class="text-sm text-slate-400 dark:text-slate-500 mt-1">Mulai dengan menambahkan fakultas baru</p>
                                <a href="{{ route('admin.faculties.create') }}" class="mt-4 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition shadow-sm shadow-indigo-200 dark:shadow-indigo-900/30 inline-flex items-center gap-2">
                                    <i class="fas fa-plus-circle"></i> Tambah Fakultas
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($faculties->hasPages())
        <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-700/30">
            {{ $faculties->appends(request()->query())->links() }}
        </div>
        @endif
    </div>

</div>

{{-- ============================================================ --}}
{{-- STYLES --}}
{{-- ============================================================ --}}
<style>
/* Dark mode scrollbar */
.dark .overflow-x-auto::-webkit-scrollbar {
    height: 5px;
}
.dark .overflow-x-auto::-webkit-scrollbar-track {
    background: #1e293b;
    border-radius: 9999px;
}
.dark .overflow-x-auto::-webkit-scrollbar-thumb {
    background: #475569;
    border-radius: 9999px;
}
</style>

@push('scripts')
<script>
$(document).ready(function() {

    // ================================================================
    // AUTO-DISMISS FLASH MESSAGE
    // ================================================================
    const flashMsg = document.getElementById('flashMsg');
    if (flashMsg) {
        setTimeout(() => {
            flashMsg.style.transition = 'opacity 0.5s';
            flashMsg.style.opacity = '0';
            setTimeout(() => flashMsg.remove(), 500);
        }, 5000);
    }

    // ================================================================
    // SORT FILTER - Auto submit on change
    // ================================================================
    $('#sortFilter').on('change', function() {
        $(this).closest('form').submit();
    });

    // ================================================================
    // SEARCH - Debounced auto search (optional)
    // ================================================================
    let searchTimeout;
    $('input[name="search"]').on('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            $(this).closest('form').submit();
        }, 500);
    });

});
</script>
@endpush

@endsection
