@extends('layouts.app')

@section('title', 'Level Reputasi')
@section('page_title', 'Level Reputasi')
@section('page_subtitle', 'Kelola level dan batas poin')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            @foreach($levels as $level)
                <form method="POST" action="{{ route('admin.reputation.levels.update', $level->id) }}" 
                    class="border-b border-slate-100 pb-4 mb-4 last:border-0 last:mb-0">
                    @csrf
                    @method('PUT')
                    <div class="flex flex-wrap items-end gap-4">
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Nama Level</label>
                            <input type="text" name="name" value="{{ $level->name }}" 
                                class="mt-1 rounded-xl border border-slate-200 px-4 py-2 text-sm outline-none focus:border-blue-400" required>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Min Poin</label>
                            <input type="number" name="min_points" value="{{ $level->min_points }}" 
                                class="mt-1 rounded-xl border border-slate-200 px-4 py-2 text-sm outline-none focus:border-blue-400" required>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Max Poin</label>
                            <input type="number" name="max_points" value="{{ $level->max_points }}" 
                                class="mt-1 rounded-xl border border-slate-200 px-4 py-2 text-sm outline-none focus:border-blue-400" 
                                placeholder="Kosongkan jika unlimited">
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Warna</label>
                            <input type="color" name="color" value="{{ $level->color ?? '#000000' }}" 
                                class="mt-1 h-10 w-16 rounded border">
                        </div>
                        <div>
                            <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold transition">
                                Update
                            </button>
                        </div>
                    </div>
                </form>
            @endforeach
        </div>
    </div>
@endsection