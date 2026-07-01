@extends('layouts.app')

@section('title', 'Level Reputasi - Admin')
@section('page_title', 'Level Reputasi')
@section('page_subtitle', 'Kelola level dan batas poin reputasi')

@section('content')

<div class="max-w-4xl mx-auto">

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="mb-5 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 font-semibold text-sm flex items-center gap-3">
        <span class="text-lg">✅</span>
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="mb-5 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 font-semibold text-sm flex items-center gap-3">
        <span class="text-lg">❌</span>
        {{ session('error') }}
    </div>
    @endif

    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">

        <div class="mb-6">
            <h3 class="text-lg font-bold text-slate-800 dark:text-white">Level Reputasi</h3>
            <p class="text-sm text-slate-500 dark:text-slate-400">Atur batas poin untuk setiap level reputasi</p>
        </div>

        <div class="space-y-4">
            @foreach($levels as $level)
            <form method="POST"
                  action="{{ route('admin.reputation.levels.update', $level->id) }}"
                  class="p-4 rounded-xl bg-slate-50 dark:bg-slate-700/50 border border-slate-100 dark:border-slate-600">

                @csrf
                @method('PUT')

                <div class="flex flex-wrap items-end gap-4">

                    {{-- Nama Level --}}
                    <div>
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Nama Level</label>
                        <input type="text"
                               name="name"
                               value="{{ $level->name }}"
                               class="mt-1 rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white px-4 py-2 text-sm outline-none focus:ring-2 focus:ring-indigo-500"
                               required>
                    </div>

                    {{-- Min Poin --}}
                    <div>
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Min Poin</label>
                        <input type="number"
                               name="min_points"
                               value="{{ $level->min_points }}"
                               class="mt-1 rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white px-4 py-2 text-sm w-24 text-center outline-none focus:ring-2 focus:ring-indigo-500"
                               required>
                    </div>

                    {{-- Max Poin --}}
                    <div>
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Max Poin</label>
                        <input type="number"
                               name="max_points"
                               value="{{ $level->max_points }}"
                               placeholder="∞"
                               class="mt-1 rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white px-4 py-2 text-sm w-24 text-center outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>

                    {{-- Warna --}}
                    <div>
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Warna</label>
                        <input type="color"
                               name="color"
                               value="{{ $level->color ?? '#000000' }}"
                               class="mt-1 h-10 w-16 rounded border border-slate-200 dark:border-slate-600 cursor-pointer">
                    </div>

                    {{-- Is Banned --}}
                    <div class="flex items-center gap-2 pt-2">
                        <input type="hidden" name="is_banned" value="0">
                        <input type="checkbox"
                               name="is_banned"
                               id="is_banned_{{ $level->id }}"
                               value="1"
                               {{ $level->is_banned ? 'checked' : '' }}
                               class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                        <label for="is_banned_{{ $level->id }}" class="text-sm font-medium text-slate-700 dark:text-slate-300">
                            Diblokir
                        </label>
                    </div>

                    {{-- Status --}}
                    <div>
                        <select name="status"
                                class="mt-1 rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white px-4 py-2 text-sm outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="active" {{ $level->status == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ $level->status == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>

                    {{-- Submit --}}
                    <div>
                        <button type="submit"
                                class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-semibold text-sm transition">
                            <i class="fas fa-save mr-1"></i> Update
                        </button>
                    </div>

                </div>

                {{-- Deskripsi --}}
                <div class="mt-3">
                    <input type="text"
                           name="description"
                           value="{{ $level->description }}"
                           placeholder="Deskripsi level..."
                           class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white px-4 py-2 text-sm outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

            </form>
            @endforeach
        </div>

        <div class="mt-6 p-4 rounded-xl bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800">
            <p class="text-xs text-blue-700 dark:text-blue-300">
                <i class="fas fa-info-circle mr-1"></i>
                <strong>Catatan:</strong> Level "Diblokir" (is_banned = true) akan mencegah user melakukan booking.
                Kosongkan Max Poin (∞) untuk level tertinggi.
            </p>
        </div>

    </div>
</div>

@endsection
