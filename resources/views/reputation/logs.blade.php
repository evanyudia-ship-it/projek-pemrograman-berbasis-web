@extends('layouts.app')

@section('title', 'Log Reputasi')
@section('page_title', 'Log Reputasi')
@section('page_subtitle', 'Riwayat semua perubahan poin reputasi')

@section('content')
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <form method="GET" class="flex flex-wrap gap-4 items-end mb-4">
            <div>
                <label class="text-sm font-semibold text-slate-700">User</label>
                <select name="user_id" class="rounded-xl border border-slate-200 px-4 py-2 text-sm outline-none focus:border-blue-400">
                    <option value="">Semua</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-sm font-semibold text-slate-700">Tipe</label>
                <select name="type" class="rounded-xl border border-slate-200 px-4 py-2 text-sm outline-none focus:border-blue-400">
                    <option value="">Semua</option>
                    <option value="reward" {{ request('type') == 'reward' ? 'selected' : '' }}>Reward</option>
                    <option value="penalty" {{ request('type') == 'penalty' ? 'selected' : '' }}>Penalty</option>
                </select>
            </div>
            <div>
                <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700 transition">Filter</button>
                <a href="{{ route('admin.reputation.logs') }}" class="px-5 py-2 bg-slate-200 text-slate-700 rounded-xl font-semibold hover:bg-slate-300 transition ml-2">Reset</a>
            </div>
        </form>

        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-500">
                <tr>
                    <th class="text-left px-4 py-3">User</th>
                    <th class="text-left px-4 py-3">Perubahan</th>
                    <th class="text-left px-4 py-3">Total</th>
                    <th class="text-left px-4 py-3">Tipe</th>
                    <th class="text-left px-4 py-3">Keterangan</th>
                    <th class="text-left px-4 py-3">Tanggal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($logs as $log)
                    <tr>
                        <td class="px-4 py-3">{{ $log->user->name }}</td>
                        <td class="px-4 py-3 font-bold {{ $log->point_change > 0 ? 'text-emerald-600' : 'text-red-600' }}">
                            {{ $log->point_change > 0 ? '+' : '' }}{{ $log->point_change }}
                        </td>
                        <td class="px-4 py-3">{{ $log->point_after }}</td>
                        <td class="px-4 py-3">{{ ucfirst($log->type) }}</td>
                        <td class="px-4 py-3">{{ $log->reason }}</td>
                        <td class="px-4 py-3">{{ $log->created_at->translatedFormat('d M Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">{{ $logs->links() }}</div>
    </div>
@endsection