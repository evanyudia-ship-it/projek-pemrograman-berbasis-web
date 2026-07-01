@extends('layouts.app')

@section('title', 'Admin Fakultas - Smart Classroom')
@section('page_title', 'Admin Fakultas')
@section('page_subtitle', 'Kelola admin atau validator pada setiap fakultas')

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

    @if($errors->any())
    <div class="p-4 rounded-2xl bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 text-sm font-semibold shadow-sm">
        <div class="flex items-start gap-3">
            <span class="text-lg">❌</span>
            <div>
                <p class="font-semibold">Terjadi kesalahan:</p>
                <ul class="list-disc ml-5 mt-1 text-sm font-normal">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    {{-- ============================================================ --}}
    {{-- STATISTICS CARDS --}}
    {{-- ============================================================ --}}
    @php
        $totalActive = $adminFaculties->filter(fn($item) => $item->status === 'active')->count();
        $totalInactive = $adminFaculties->filter(fn($item) => $item->status === 'inactive')->count();
        $totalFacultiesWithAdmin = $adminFaculties->pluck('faculty_id')->unique()->count();
    @endphp

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- Total --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-5 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Total Admin</p>
                    <p class="text-2xl font-extrabold text-slate-800 dark:text-white mt-1">{{ $adminFaculties->total() }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400 group-hover:scale-110 transition">
                    <i class="fas fa-user-shield text-lg"></i>
                </div>
            </div>
            <div class="mt-2">
                <span class="text-xs text-slate-400 dark:text-slate-500">admin fakultas terdaftar</span>
            </div>
        </div>

        {{-- Active --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-5 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Aktif</p>
                    <p class="text-2xl font-extrabold text-emerald-600 dark:text-emerald-400 mt-1">{{ $totalActive }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center text-emerald-600 dark:text-emerald-400 group-hover:scale-110 transition">
                    <i class="fas fa-check-circle text-lg"></i>
                </div>
            </div>
            <div class="mt-2">
                <span class="text-xs text-emerald-600 dark:text-emerald-400 font-medium">admin aktif</span>
            </div>
        </div>

        {{-- Inactive --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-5 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Nonaktif</p>
                    <p class="text-2xl font-extrabold text-slate-400 dark:text-slate-500 mt-1">{{ $totalInactive }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-slate-400 dark:text-slate-500 group-hover:scale-110 transition">
                    <i class="fas fa-user-slash text-lg"></i>
                </div>
            </div>
            <div class="mt-2">
                <span class="text-xs text-slate-400 dark:text-slate-500">admin nonaktif</span>
            </div>
        </div>

        {{-- Faculties Covered --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-5 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Fakultas Tercover</p>
                    <p class="text-2xl font-extrabold text-blue-600 dark:text-blue-400 mt-1">{{ $totalFacultiesWithAdmin }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center text-blue-600 dark:text-blue-400 group-hover:scale-110 transition">
                    <i class="fas fa-building-columns text-lg"></i>
                </div>
            </div>
            <div class="mt-2">
                <span class="text-xs text-blue-600 dark:text-blue-400 font-medium">fakultas memiliki admin</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- FORM --}}
        <div class="xl:col-span-1">
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden sticky top-6">
                <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700 bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-indigo-950/30 dark:to-blue-950/30">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                            <i class="fas fa-user-plus text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 dark:text-white">Tambah Admin Fakultas</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Pilih admin dan fakultas yang dikelola</p>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.admin-faculties.store') }}" class="p-6 space-y-4">
                    @csrf

                    {{-- Admin --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            <i class="fas fa-user text-indigo-500 mr-1.5"></i> Admin / Validator <span class="text-red-500">*</span>
                        </label>
                        <select name="user_id"
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition appearance-none"
                                required>
                            <option value="">Pilih Admin</option>
                            @foreach($admins as $admin)
                                <option value="{{ $admin->id }}" {{ old('user_id') == $admin->id ? 'selected' : '' }}>
                                    {{ $admin->name }} ({{ ucfirst($admin->role) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Faculty --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            <i class="fas fa-building-columns text-indigo-500 mr-1.5"></i> Fakultas <span class="text-red-500">*</span>
                        </label>
                        <select name="faculty_id"
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition appearance-none"
                                required>
                            <option value="">Pilih Fakultas</option>
                            @foreach($faculties as $faculty)
                                <option value="{{ $faculty->id }}" {{ old('faculty_id') == $faculty->id ? 'selected' : '' }}>
                                    {{ $faculty->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Position --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            <i class="fas fa-briefcase text-indigo-500 mr-1.5"></i> Jabatan
                        </label>
                        <input type="text"
                               name="position"
                               value="{{ old('position') }}"
                               placeholder="Contoh: Validator Fakultas"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            <i class="fas fa-circle text-indigo-500 mr-1.5 text-[6px]"></i> Status
                        </label>
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2 text-sm text-slate-700 dark:text-slate-300 cursor-pointer">
                                <input type="radio" name="status" value="active" {{ old('status', 'active') == 'active' ? 'checked' : '' }}
                                       class="w-4 h-4 text-emerald-600 border-slate-300 focus:ring-emerald-500">
                                <span class="inline-flex items-center gap-1">
                                    <i class="fas fa-check-circle text-emerald-500"></i> Aktif
                                </span>
                            </label>
                            <label class="flex items-center gap-2 text-sm text-slate-700 dark:text-slate-300 cursor-pointer">
                                <input type="radio" name="status" value="inactive" {{ old('status') == 'inactive' ? 'checked' : '' }}
                                       class="w-4 h-4 text-slate-400 border-slate-300 focus:ring-slate-500">
                                <span class="inline-flex items-center gap-1">
                                    <i class="fas fa-user-slash text-slate-400"></i> Nonaktif
                                </span>
                            </label>
                        </div>
                    </div>

                    <button type="submit"
                            class="w-full px-5 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold transition shadow-sm shadow-indigo-200 dark:shadow-indigo-900/30 flex items-center justify-center gap-2">
                        <i class="fas fa-save"></i> Simpan Admin Fakultas
                    </button>
                </form>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="xl:col-span-2">
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">

                {{-- Header --}}
                <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700 bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-indigo-950/30 dark:to-blue-950/30">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                            <i class="fas fa-list-ul text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 dark:text-white">Daftar Admin Fakultas</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Data admin yang memiliki akses validasi pada fakultas tertentu</p>
                        </div>
                    </div>
                </div>

                {{-- Filters --}}
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-700/30">
                    <form method="GET" action="{{ route('admin.admin-faculties.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        {{-- Faculty Filter --}}
                        <div class="relative">
                            <i class="fas fa-building-columns absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <select name="faculty_id"
                                    class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition appearance-none">
                                <option value="">Semua Fakultas</option>
                                @foreach($faculties as $faculty)
                                    <option value="{{ $faculty->id }}" {{ request('faculty_id') == $faculty->id ? 'selected' : '' }}>
                                        {{ $faculty->name }}
                                    </option>
                                @endforeach
                            </select>
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

                        {{-- Actions --}}
                        <div class="flex gap-2">
                            <button type="submit"
                                    class="flex-1 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold transition flex items-center justify-center gap-2">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                            <a href="{{ route('admin.admin-faculties.index') }}"
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
                                <th class="text-left px-6 py-4 font-semibold">Admin</th>
                                <th class="text-left px-6 py-4 font-semibold">Fakultas</th>
                                <th class="text-left px-6 py-4 font-semibold">Jabatan</th>
                                <th class="text-left px-6 py-4 font-semibold">Status</th>
                                <th class="text-center px-6 py-4 font-semibold">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                            @forelse($adminFaculties as $item)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition">
                                {{-- Admin --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full {{ $item->status === 'active' ? 'bg-indigo-100 dark:bg-indigo-900/50' : 'bg-slate-100 dark:bg-slate-700' }} flex items-center justify-center {{ $item->status === 'active' ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500' }} font-bold text-sm">
                                            {{ strtoupper(substr($item->user->name ?? '-', 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-800 dark:text-white {{ $item->status === 'inactive' ? 'opacity-60' : '' }}">
                                                {{ $item->user->name ?? '-' }}
                                            </p>
                                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ $item->user->email ?? '-' }}</p>
                                            <span class="text-[10px] text-slate-400 dark:text-slate-500 inline-flex items-center gap-1">
                                                <i class="fas fa-user-tag"></i>
                                                {{ ucfirst($item->user->role ?? '-') }}
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                {{-- Faculty --}}
                                <td class="px-6 py-4">
                                    <p class="font-bold text-slate-800 dark:text-white {{ $item->status === 'inactive' ? 'opacity-60' : '' }}">
                                        {{ $item->faculty->name ?? '-' }}
                                    </p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ $item->faculty->code ?? '-' }}</p>
                                </td>

                                {{-- Position --}}
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300 text-xs font-medium inline-flex items-center gap-1.5">
                                        <i class="fas fa-briefcase text-[10px]"></i>
                                        {{ $item->position ?? 'Admin Fakultas' }}
                                    </span>
                                </td>

                                {{-- Status --}}
                                <td class="px-6 py-4">
                                    @if($item->status === 'active')
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
                                        <a href="{{ route('admin.admin-faculties.edit', $item) }}"
                                           class="px-3 py-1.5 rounded-lg bg-indigo-100 dark:bg-indigo-900/50 hover:bg-indigo-200 dark:hover:bg-indigo-800 text-indigo-700 dark:text-indigo-300 text-xs font-semibold transition flex items-center gap-1"
                                           title="Edit Admin Fakultas">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <form method="POST"
                                              action="{{ route('admin.admin-faculties.destroy', $item) }}"
                                              onsubmit="return confirm('Yakin ingin menghapus admin {{ $item->user->name ?? '' }} dari fakultas {{ $item->faculty->name ?? '' }}? Tindakan ini tidak dapat dibatalkan.')"
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="px-3 py-1.5 rounded-lg bg-red-100 dark:bg-red-900/50 hover:bg-red-200 dark:hover:bg-red-800 text-red-700 dark:text-red-300 text-xs font-semibold transition flex items-center gap-1"
                                                    title="Hapus Admin Fakultas">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center text-slate-400 dark:text-slate-500">
                                    <div class="flex flex-col items-center">
                                        <div class="w-20 h-20 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-4xl mb-4">
                                            <i class="fas fa-user-shield text-slate-400 dark:text-slate-500"></i>
                                        </div>
                                        <p class="font-semibold text-slate-600 dark:text-slate-400">Belum ada admin fakultas</p>
                                        <p class="text-sm text-slate-400 dark:text-slate-500 mt-1">Tambahkan admin untuk mengelola fakultas</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($adminFaculties->hasPages())
                <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-700/30">
                    {{ $adminFaculties->appends(request()->query())->links() }}
                </div>
                @endif

            </div>
        </div>

    </div>
</div>

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

/* Sticky form */
.sticky {
    position: sticky;
    top: 24px;
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

});
</script>
@endpush

@endsection
