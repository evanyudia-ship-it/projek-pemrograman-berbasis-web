@extends('layouts.app')

@section('title', 'Hasil Pencarian Bantuan - Smart Classroom')
@section('page_title', 'Hasil Pencarian')
@section('page_subtitle', "Pencarian: " . ($keyword ?? '-'))

@section('content')

<div class="max-w-5xl mx-auto font-sora space-y-6">

    {{-- ===== BACK ===== --}}
    <div class="fade-up">
        <a href="{{ route('help.index') }}" class="text-sm text-slate-500 hover:text-slate-700 transition flex items-center gap-2">
            ← Kembali ke Pusat Bantuan
        </a>
    </div>

    {{-- ===== RESULT ===== --}}
    <div class="fade-up delay-1">
        @if(empty($keyword))
        <div class="bg-white rounded-3xl border border-slate-100 p-12 text-center">
            <p class="text-5xl mb-4">🔍</p>
            <p class="text-lg font-semibold text-slate-600">Masukkan kata kunci pencarian</p>
            <p class="text-sm text-slate-400 mt-1">Cari artikel, FAQ, atau panduan di pusat bantuan</p>
        </div>
        @elseif(empty($articles) && empty($faqs))
        <div class="bg-white rounded-3xl border border-slate-100 p-12 text-center">
            <p class="text-5xl mb-4">🔎</p>
            <p class="text-lg font-semibold text-slate-600">Tidak ditemukan hasil untuk "{{ e($keyword) }}"</p>
            <p class="text-sm text-slate-400 mt-1">Coba gunakan kata kunci lain atau lihat FAQ di bawah</p>
            <a href="{{ route('help.index') }}#faq" class="inline-block mt-4 text-blue-600 hover:text-blue-700 text-sm font-medium">
                Lihat FAQ →
            </a>
        </div>
        @else

        {{-- Articles --}}
        @if(!empty($articles))
        <div class="mb-6">
            <h2 class="text-lg font-bold text-slate-800 mb-4">📄 Artikel Terkait</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($articles as $article)
                <a href="{{ route('help.article', $article['slug']) }}"
                   class="block p-5 bg-white rounded-2xl border border-slate-100 hover:border-blue-200 hover:shadow-md transition group">
                    <div class="flex items-start gap-3">
                        <span class="text-2xl">{{ $article['icon'] ?? '📄' }}</span>
                        <div>
                            <h3 class="font-bold text-slate-800 group-hover:text-blue-600 transition text-sm">
                                {{ $article['judul'] }}
                            </h3>
                            <p class="text-xs text-slate-500 mt-1">{{ $article['excerpt'] ?? '' }}</p>
                            <div class="flex items-center gap-3 mt-2 text-xs text-slate-400">
                                <span>{{ $article['category'] ?? '' }}</span>
                                <span>•</span>
                                <span>{{ $article['read_time'] ?? '2 menit' }}</span>
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- FAQs --}}
        @if(!empty($faqs))
        <div>
            <h2 class="text-lg font-bold text-slate-800 mb-4">❓ FAQ Terkait</h2>
            <div class="space-y-3">
                @foreach($faqs as $faq)
                <div class="faq-item border border-slate-100 rounded-2xl overflow-hidden">
                    <button class="faq-toggle w-full flex items-center justify-between px-5 py-4 text-left hover:bg-slate-50 transition" type="button">
                        <span class="flex items-center gap-3">
                            <span class="text-lg">{{ $faq['icon'] ?? '❓' }}</span>
                            <span class="text-sm font-semibold text-slate-800">{{ e($faq['pertanyaan'] ?? '-') }}</span>
                        </span>
                        <span class="faq-icon shrink-0 w-7 h-7 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500 text-sm font-bold">+</span>
                    </button>
                    <div class="faq-answer hidden px-5 pb-4">
                        <p class="text-sm text-slate-600 leading-relaxed">{{ e($faq['jawaban'] ?? '-') }}</p>
                        @if(isset($faq['kategori']))
                        <span class="inline-block mt-2 text-xs px-2 py-0.5 rounded-full bg-slate-100 text-slate-500 font-medium">
                            {{ $faq['kategori'] }}
                        </span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @endif
    </div>

</div>

@push('scripts')
<script>
$(document).ready(function() {
    // FAQ Toggle
    $('.faq-toggle').on('click', function(e) {
        e.preventDefault();

        var $item = $(this).closest('.faq-item');
        var $answer = $item.find('.faq-answer');
        var $icon = $(this).find('.faq-icon');

        $('.faq-item').not($item).each(function() {
            $(this).find('.faq-answer').slideUp(200);
            $(this).find('.faq-icon').text('+');
        });

        if ($answer.is(':visible')) {
            $answer.slideUp(200);
            $icon.text('+');
        } else {
            $answer.slideDown(200);
            $icon.text('−');
        }
    });
});
</script>
@endpush

@endsection
