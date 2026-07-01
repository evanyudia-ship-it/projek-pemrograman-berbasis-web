@extends('layouts.app')

@section('title', 'Log Reputasi - Smart Classroom')
@section('page_title', 'Log Reputasi')
@section('page_subtitle', 'Riwayat semua perubahan poin reputasi user')

@section('content')

<div class="max-w-7xl mx-auto font-sora space-y-6">

    {{-- ============================================================ --}}
    {{-- FLASH MESSAGES --}}
    -- ============================================================ --}}
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
    {{-- HEADER --}}
    -- ============================================================ --}}
    <div class="bg-gradient-to-br from-indigo-600 to-purple-600 dark:from-indigo-800 dark:to-purple-800 rounded-2xl p-6 md:p-8 text-white shadow-lg">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center text-3xl">
                    📜
                </div>
                <div>
                    <p class="text-xs font-semibold text-white/70 uppercase tracking-widest">Riwayat Reputasi</p>
                    <h1 class="text-2xl md:text-3xl font-bold mt-0.5">Log Reputasi</h1>
                    <p class="text-sm text-white/80 mt-1 flex items-center gap-2">
                        <span>📊 Total {{ $logs->total() }} perubahan</span>
                        <span class="w-px h-4 bg-white/30"></span>
                        <span>✅ {{ $logs->where('type', 'reward')->count() }} Reward</span>
                        <span class="w-px h-4 bg-white/30"></span>
                        <span>❌ {{ $logs->where('type', 'penalty')->count() }} Penalty</span>
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <span class="px-4 py-2 rounded-xl bg-white/20 backdrop-blur text-sm font-semibold border border-white/10 flex items-center gap-2">
                    <i class="fas fa-history"></i> {{ $logs->first()?->created_at?->translatedFormat('d M Y') ?? 'Belum ada' }}
                </span>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- STATISTICS CARDS --}}
    -- ============================================================ --}}
    @php
        $totalReward = $logs->where('type', 'reward')->sum('point_change');
        $totalPenalty = $logs->where('type', 'penalty')->sum('point_change');
        $totalPoints = $totalReward + $totalPenalty;
        $uniqueUsers = $logs->pluck('user_id')->unique()->count();
    @endphp

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- Total Changes --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-5 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Total Perubahan</p>
                    <p class="text-2xl font-extrabold text-slate-800 dark:text-white mt-1">{{ $logs->total() }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400 group-hover:scale-110 transition">
                    <i class="fas fa-exchange-alt text-lg"></i>
                </div>
            </div>
            <div class="mt-2">
                <span class="text-xs text-slate-400 dark:text-slate-500">riwayat perubahan</span>
            </div>
        </div>

        {{-- Total Users Affected --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-5 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">User Terpengaruh</p>
                    <p class="text-2xl font-extrabold text-blue-600 dark:text-blue-400 mt-1">{{ $uniqueUsers }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center text-blue-600 dark:text-blue-400 group-hover:scale-110 transition">
                    <i class="fas fa-users text-lg"></i>
                </div>
            </div>
            <div class="mt-2">
                <span class="text-xs text-blue-600 dark:text-blue-400 font-medium">user mendapatkan perubahan</span>
            </div>
        </div>

        {{-- Total Reward --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-5 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Total Reward</p>
                    <p class="text-2xl font-extrabold text-emerald-600 dark:text-emerald-400 mt-1">+{{ number_format(abs($totalReward)) }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center text-emerald-600 dark:text-emerald-400 group-hover:scale-110 transition">
                    <i class="fas fa-arrow-up text-lg"></i>
                </div>
            </div>
            <div class="mt-2">
                <span class="text-xs text-emerald-600 dark:text-emerald-400 font-medium">total poin ditambahkan</span>
            </div>
        </div>

        {{-- Total Penalty --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-5 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Total Penalty</p>
                    <p class="text-2xl font-extrabold text-red-600 dark:text-red-400 mt-1">{{ number_format(abs($totalPenalty)) }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-red-100 dark:bg-red-900/50 flex items-center justify-center text-red-600 dark:text-red-400 group-hover:scale-110 transition">
                    <i class="fas fa-arrow-down text-lg"></i>
                </div>
            </div>
            <div class="mt-2">
                <span class="text-xs text-red-600 dark:text-red-400 font-medium">total poin dikurangi</span>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- LOG TABLE --}}
    -- ============================================================ --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">

        {{-- Header --}}
        <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700 bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-indigo-950/30 dark:to-blue-950/30">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                    <i class="fas fa-list-ul text-lg"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800 dark:text-white">Riwayat Perubahan Reputasi</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Semua log perubahan poin reputasi user</p>
                </div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-700/30">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3">
                {{-- User Filter --}}
                <div class="relative">
                    <i class="fas fa-user absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <select name="user_id"
                            class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition appearance-none">
                        <option value="">Semua User</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Type Filter --}}
                <div class="relative">
                    <i class="fas fa-tag absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <select name="type"
                            class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition appearance-none">
                        <option value="">Semua Tipe</option>
                        <option value="reward" {{ request('type') == 'reward' ? 'selected' : '' }}>✅ Reward</option>
                        <option value="penalty" {{ request('type') == 'penalty' ? 'selected' : '' }}>❌ Penalty</option>
                    </select>
                </div>

                {{-- Date Range --}}
                <div class="relative">
                    <i class="fas fa-calendar-alt absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                           placeholder="Dari"
                           class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                </div>

                {{-- Actions --}}
                <div class="flex gap-2">
                    <button type="submit"
                            class="flex-1 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold transition flex items-center justify-center gap-2">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('admin.reputation.logs') }}"
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
                        <th class="text-left px-6 py-4 font-semibold">User</th>
                        <th class="text-left px-6 py-4 font-semibold text-center">Perubahan</th>
                        <th class="text-left px-6 py-4 font-semibold text-center">Total</th>
                        <th class="text-left px-6 py-4 font-semibold">Tipe</th>
                        <th class="text-left px-6 py-4 font-semibold">Keterangan</th>
                        <th class="text-left px-6 py-4 font-semibold">Tanggal</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($logs as $log)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition">
                        {{-- User --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full {{ $log->point_change > 0 ? 'bg-emerald-100 dark:bg-emerald-900/50' : 'bg-red-100 dark:bg-red-900/50' }} flex items-center justify-center font-bold text-sm {{ $log->point_change > 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                                    {{ strtoupper(substr($log->user->name ?? '-', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-bold text-slate-800 dark:text-white">{{ $log->user->name ?? 'User Dihapus' }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ $log->user->email ?? '-' }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- Perubahan --}}
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1.5 rounded-full text-xs font-bold inline-flex items-center gap-1.5
                                {{ $log->point_change > 0 ? 'bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300' : 'bg-red-100 dark:bg-red-900/50 text-red-700 dark:text-red-300' }}">
                                <i class="fas {{ $log->point_change > 0 ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
                                {{ $log->point_change > 0 ? '+' : '' }}{{ $log->point_change }}
                            </span>
                        </td>

                        {{-- Total Setelah --}}
                        <td class="px-6 py-4 text-center">
                            <span class="font-bold text-slate-800 dark:text-white">{{ $log->point_after }}</span>
                        </td>

                        {{-- Tipe --}}
                        <td class="px-6 py-4">
                            @if($log->type === 'reward')
                                <span class="px-3 py-1 rounded-full bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300 text-xs font-bold inline-flex items-center gap-1.5">
                                    <i class="fas fa-star"></i> Reward
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full bg-red-100 dark:bg-red-900/50 text-red-700 dark:text-red-300 text-xs font-bold inline-flex items-center gap-1.5">
                                    <i class="fas fa-exclamation-triangle"></i> Penalty
                                </span>
                            @endif
                        </td>

                        {{-- Keterangan --}}
                        <td class="px-6 py-4">
                            <p class="text-slate-600 dark:text-slate-300 max-w-xs">
                                {{ $log->reason ?? '-' }}
                            </p>
                        </td>

                        {{-- Tanggal --}}
                        <td class="px-6 py-4">
                            <p class="text-slate-600 dark:text-slate-400 text-xs font-medium">
                                {{ $log->created_at->translatedFormat('d M Y') }}
                            </p>
                            <p class="text-xs text-slate-400 dark:text-slate-500">
                                {{ $log->created_at->translatedFormat('H:i') }}
                            </p>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center text-slate-400 dark:text-slate-500">
                            <div class="flex flex-col items-center">
                                <div class="w-20 h-20 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-4xl mb-4">
                                    <i class="fas fa-history text-slate-400 dark:text-slate-500"></i>
                                </div>
                                <p class="font-semibold text-slate-600 dark:text-slate-400">Belum ada log reputasi</p>
                                <p class="text-sm text-slate-400 dark:text-slate-500 mt-1">Belum ada perubahan poin reputasi</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($logs->hasPages())
        <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-700/30">
            {{ $logs->appends(request()->query())->links() }}
        </div>
        @endif

    </div>

</div>

{{-- ============================================================ --}}
{{-- STYLES --}}
-- ============================================================ --}}
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
    // DATE FILTER - Auto submit on change
    // ================================================================
    $('input[type="date"]').on('change', function() {
        $(this).closest('form').submit();
    });

    // ================================================================
    // SELECT - Auto submit on change
    // ================================================================
    $('select').on('change', function() {
        $(this).closest('form').submit();
    });

});
</script>
@endpush

@endsection
