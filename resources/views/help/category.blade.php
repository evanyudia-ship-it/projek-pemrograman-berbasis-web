@extends('layouts.app')

@section('title', $category->name . ' - Bantuan Smart Classroom')
@section('page_title', $category->name)
@section('page_subtitle', $category->description ?? 'Kumpulan artikel bantuan')

@section('content')

<div class="max-w-5xl mx-auto font-sora space-y-6">

    {{-- ===== BACK ===== --}}
    <div class="fade-up">
        <a href="{{ route('help.index') }}"
           class="text-sm text-slate-500 hover:text-slate-700 transition flex items-center gap-2">
            ← Kembali ke Pusat Bantuan
        </a>
    </div>

    {{-- ===== HEADER KATEGORI ===== --}}
    <div class="fade-up bg-white rounded-3xl border border-slate-100 shadow-sm p-6">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center text-4xl">
                {{ $category->icon ?? '📂' }}
            </div>
            <div>
                <h1 class="text-2xl font-bold text-slate-900">{{ $category->name }}</h1>
                @if($category->description)
                <p class="text-sm text-slate-500 mt-1">{{ $category->description }}</p>
                @endif
                <p class="text-xs text-slate-400 mt-2">
                    {{ $articles->total() }} artikel ditemukan
                </p>
            </div>
        </div>
    </div>

    {{-- ===== DAFTAR ARTIKEL ===== --}}
    <div class="fade-up delay-1">
        @if($articles->count() > 0)
        <div class="grid grid-cols-1 gap-4">
            @foreach($articles as $article)
            <a href="{{ route('help.article', $article->slug) }}"
               class="block bg-white rounded-2xl border border-slate-100 hover:border-blue-200 hover:shadow-md transition group p-5">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-slate-50 rounded-xl flex items-center justify-center text-2xl shrink-0">
                        {{ $article->icon ?? '📄' }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-bold text-slate-800 group-hover:text-blue-600 transition">
                            {{ $article->title }}
                        </h3>
                        @if($article->excerpt)
                        <p class="text-sm text-slate-500 mt-1 line-clamp-2">{{ $article->excerpt }}</p>
                        @endif
                        <div class="flex items-center gap-4 mt-2 text-xs text-slate-400">
                            <span class="flex items-center gap-1">
                                <span>🕐</span> {{ $article->read_time }} menit
                            </span>
                            <span class="flex items-center gap-1">
                                <span>👁️</span> {{ $article->views }} dilihat
                            </span>
                            <span class="flex items-center gap-1">
                                <span>📅</span> {{ $article->created_at->translatedFormat('d M Y') }}
                            </span>
                        </div>
                    </div>
                    <span class="text-blue-600 group-hover:translate-x-1 transition">→</span>
                </div>
            </a>
            @endforeach
        </div>

        {{-- ===== PAGINATION ===== --}}
        <div class="mt-6">
            {{ $articles->links() }}
        </div>

        @else
        <div class="bg-white rounded-3xl border border-slate-100 p-12 text-center">
            <p class="text-5xl mb-4">📭</p>
            <p class="text-lg font-semibold text-slate-600">Belum ada artikel di kategori ini</p>
            <p class="text-sm text-slate-400 mt-1">Kategori ini sedang dalam pengembangan</p>
            <a href="{{ route('help.index') }}"
               class="inline-block mt-4 text-blue-600 hover:text-blue-700 text-sm font-medium">
                Lihat kategori lain →
            </a>
        </div>
        @endif
    </div>

</div>

@endsection
