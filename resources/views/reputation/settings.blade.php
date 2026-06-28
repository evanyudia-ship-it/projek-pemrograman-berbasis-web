@extends('layouts.app')

@section('title', 'Pengaturan Reputasi')
@section('page_title', 'Pengaturan Reputasi')
@section('page_subtitle', 'Atur poin untuk setiap aksi')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <form method="POST" action="{{ route('admin.reputation.settings.update') }}">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    @foreach($settings as $setting)
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                            <label class="font-semibold text-sm">{{ $setting->name }}</label>
                            <div class="flex items-center gap-3">
                                <input type="hidden" name="settings[{{ $loop->index }}][id]" value="{{ $setting->id }}">
                                <input type="number" name="settings[{{ $loop->index }}][points]" value="{{ $setting->points }}" 
                                    class="w-24 rounded-xl border border-slate-200 px-3 py-2 text-sm text-center outline-none focus:border-blue-400">
                                <span class="text-xs text-slate-400">poin</span>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-6 flex justify-end">
                    <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition shadow-sm">
                        Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection