@extends('layouts.app')

@section('title', 'Log Reputasi - Admin')
@section('page_title', 'Log Reputasi')
@section('page_subtitle', 'Riwayat semua perubahan poin reputasi user')

@section('content')

<div class="max-w-6xl mx-auto">

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="mb-5 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 font-semibold text-sm flex items-center gap-3">
        <span class="text-lg">✅</span>
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">

        {{-- Filter --}}
        <div class="p-6 border-b border-slate-200 dark:border-slate-700">
            <form method="GET" class="flex flex-wrap gap-4 items-end">
                <div>
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">User</label>
                    <select name="user_id"
                            class="mt-1 rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white px-4 py-2 text-sm outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Semua User</option>
                        @foreach($users as $u)
                        <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>
                            {{ $u->name }} ({{ $u->role }})
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Tipe</label>
                    <select name="type"
                            class="mt-1 rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white px-4 py-2 text-sm outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Semua Tipe</option>
                        <option value="reward" {{ request('type') == 'reward' ? 'selected' : '' }}>✅ Reward</option>
                        <option value="penalty" {{ request('type') == 'penalty' ? 'selected' : '' }}>❌ Penalty</option>
                    </select>
                </div>

                <div class="flex gap-2">
                    <button type="submit"
                            class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-semibold text-sm transition">
                        <i class="fas fa-filter mr-1"></i> Filter
                    </button>
                    <a href="{{ route('admin.reputation.logs') }}"
                       class="px-5 py-2 bg-slate-200 dark:bg-slate-600 hover:bg-slate-300 dark:hover:bg-slate-500 text-slate-700 dark:text-white rounded-xl font-semibold text-sm transition">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- Tabel --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 dark:bg-slate-700/50 text-slate-500 dark:text-slate-400">
                    <tr>
                        <th class="text-left px-6 py-4">User</th>
                        <th class="text-left px-6 py-4">Perubahan</th>
                        <th class="text-left px-6 py-4">Total</th>
                        <th class="text-left px-6 py-4">Tipe</th>
                        <th class="text-left px-6 py-4">Keterangan</th>
                        <th class="text-left px-6 py-4">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($logs as $log)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition">
                        <td class="px-6 py-4">
                            <p class="font-semibold text-slate-800 dark:text-white">{{ $log->user->name ?? '-' }}</p>
                            <p class="text-xs text-slate-400">{{ $log->user->email ?? '-' }}</p>
                        </td>
                        <td class="px-6 py-4 font-bold
                            {{ $log->point_change > 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                            {{ $log->point_change > 0 ? '+' : '' }}{{ $log->point_change }}
                        </td>
                        <td class="px-6 py-4 font-semibold text-slate-800 dark:text-white">
                            {{ $log->point_after }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-bold
                                {{ $log->type == 'reward' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300' : 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300' }}">
                                {{ $log->type == 'reward' ? '✅ Reward' : '❌ Penalty' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-600 dark:text-slate-300 max-w-xs">
                            <p class="text-sm">{{ $log->reason ?? $log->description ?? '-' }}</p>
                            @if($log->booking)
                            <p class="text-xs text-slate-400 mt-0.5">Booking: {{ $log->booking->booking_code ?? '-' }}</p>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-500 dark:text-slate-400">
                            {{ $log->created_at->translatedFormat('d M Y H:i') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-400 dark:text-slate-500">
                            <i class="fas fa-inbox text-3xl block mb-3"></i>
                            <p class="font-semibold">Belum ada log reputasi</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($logs->hasPages())
        <div class="p-6 border-t border-slate-200 dark:border-slate-700">
            {{ $logs->links() }}
        </div>
        @endif

    </div>
</div>

@endsection
