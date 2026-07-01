@extends('layouts.app')

@section('title', $article->title . ' - Bantuan Smart Classroom')
@section('page_title', $article->title)
@section('page_subtitle', $article->category->name ?? 'Artikel Bantuan')

@section('content')

<div class="max-w-4xl mx-auto font-sora space-y-6">

    {{-- ===== BACK ===== --}}
    <div class="fade-up">
        <a href="{{ route('help.category', $article->category->slug ?? '') }}"
           class="text-sm text-slate-500 hover:text-slate-700 transition flex items-center gap-2">
            ← Kembali ke {{ $article->category->name ?? 'Kategori' }}
        </a>
    </div>

    {{-- ===== ARTICLE HEADER ===== --}}
    <div class="fade-up bg-white rounded-3xl border border-slate-100 shadow-sm p-6">
        <div class="flex items-start gap-4">
            <div class="w-14 h-14 bg-slate-100 rounded-2xl flex items-center justify-center text-3xl shrink-0">
                {{ $article->icon ?? '📄' }}
            </div>
            <div class="flex-1">
                <h1 class="text-2xl font-bold text-slate-900">{{ $article->title }}</h1>
                <div class="flex flex-wrap items-center gap-4 mt-2 text-xs text-slate-400">
                    <span class="flex items-center gap-1">
                        <span>📂</span> {{ $article->category->name ?? 'Umum' }}
                    </span>
                    <span class="flex items-center gap-1">
                        <span>🕐</span> {{ $article->read_time }} menit membaca
                    </span>
                    <span class="flex items-center gap-1">
                        <span>👁️</span> {{ $article->views }} dilihat
                    </span>
                    <span class="flex items-center gap-1">
                        <span>📅</span> {{ $article->created_at->translatedFormat('d M Y') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== ARTICLE CONTENT ===== --}}
    <div class="fade-up delay-1 bg-white rounded-3xl border border-slate-100 shadow-sm p-8">
        <div class="prose prose-slate max-w-none">
            {!! $article->content !!}
        </div>

        {{-- ===== SHARE / ACTION ===== --}}
        <div class="mt-8 pt-6 border-t border-slate-100 flex flex-wrap items-center justify-between gap-4">
            <p class="text-xs text-slate-400">
                Terakhir diperbarui: {{ $article->updated_at->translatedFormat('d M Y, H:i') }}
            </p>
            <div class="flex items-center gap-3">
                <span class="text-xs text-slate-400">Bagikan:</span>
                <a href="https://twitter.com/intent/tweet?text={{ urlencode($article->title) }}&url={{ urlencode(route('help.article', $article->slug)) }}"
                   target="_blank"
                   class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 rounded-xl text-xs font-semibold transition">
                    🐦 Twitter
                </a>
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('help.article', $article->slug)) }}"
                   target="_blank"
                   class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 rounded-xl text-xs font-semibold transition">
                    📘 Facebook
                </a>
            </div>
        </div>
    </div>

    {{-- ===== RELATED ARTICLES ===== --}}
    @if($relatedArticles->count() > 0)
    <div class="fade-up delay-2">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-4">📚 Artikel Terkait</p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($relatedArticles as $related)
            <a href="{{ route('help.article', $related->slug) }}"
               class="block p-4 bg-white rounded-2xl border border-slate-100 hover:border-blue-200 hover:shadow-md transition group">
                <div class="flex items-start gap-3">
                    <span class="text-2xl">{{ $related->icon ?? '📄' }}</span>
                    <div>
                        <h4 class="font-semibold text-slate-800 group-hover:text-blue-600 transition text-sm">
                            {{ $related->title }}
                        </h4>
                        <p class="text-xs text-slate-400 mt-1">{{ $related->read_time }} menit</p>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ===== KEMBALI ===== --}}
    <div class="fade-up delay-3">
        <a href="{{ route('help.index') }}"
           class="text-sm text-blue-600 hover:text-blue-700 font-medium transition flex items-center gap-2">
            ← Kembali ke Pusat Bantuan
        </a>
    </div>

</div>

@endsection
