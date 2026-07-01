@extends('layouts.app')

@section('title', 'Pengaturan Reputasi - Admin')
@section('page_title', 'Pengaturan Reputasi')
@section('page_subtitle', 'Atur poin untuk setiap aksi reputasi')

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
            <h3 class="text-lg font-bold text-slate-800 dark:text-white">Aturan Reputasi</h3>
            <p class="text-sm text-slate-500 dark:text-slate-400">Atur jumlah poin untuk setiap aksi reward dan penalty</p>
        </div>

        <form method="POST" action="{{ route('admin.reputation.settings.update') }}">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                @foreach($settings as $index => $setting)
                <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50 dark:bg-slate-700/50 border border-slate-100 dark:border-slate-600">
                    <div>
                        <p class="font-semibold text-slate-800 dark:text-white text-sm">{{ $setting->name }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ $setting->code }}</p>
                        <span class="inline-block mt-1 px-2 py-0.5 rounded-full text-xs font-medium
                            {{ $setting->type == 'reward' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300' : 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300' }}">
                            {{ ucfirst($setting->type) }}
                        </span>
                    </div>
                    <div class="flex items-center gap-3">
                        <input type="hidden" name="settings[{{ $index }}][id]" value="{{ $setting->id }}">
                        <input type="number"
                               name="settings[{{ $index }}][points]"
                               value="{{ $setting->point }}"
                               class="w-24 rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white px-3 py-2 text-sm text-center outline-none focus:ring-2 focus:ring-indigo-500"
                               {{ $setting->code == 'FAKE_BOOKING' ? 'readonly' : '' }}>
                        <span class="text-xs text-slate-400">poin</span>
                        @if($setting->code == 'FAKE_BOOKING')
                        <span class="text-xs text-amber-500">(fixed)</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit"
                        class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl transition shadow-sm">
                    💾 Simpan Pengaturan
                </button>
            </div>
        </form>

        <div class="mt-6 p-4 rounded-xl bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800">
            <p class="text-xs text-amber-700 dark:text-amber-300">
                <i class="fas fa-info-circle mr-1"></i>
                <strong>Catatan:</strong> Poin untuk "Booking Fiktif" (FAKE_BOOKING) bersifat tetap (-20) dan tidak dapat diubah.
            </p>
        </div>

    </div>
</div>

@endsection
