@extends('layouts.app')

@section('title', 'Bantuan & Panduan - Smart Classroom')
@section('page_title', 'Bantuan & Panduan')
@section('page_subtitle', 'Petunjuk resmi penggunaan sistem Smart Classroom')

@section('content')

<div class="max-w-5xl mx-auto space-y-8 font-sora">

    {{-- ===== HERO ===== --}}
    <div class="bg-slate-950 rounded-3xl p-8 text-white relative overflow-hidden">
        <div class="relative z-10">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest mb-3">Pusat Bantuan Resmi</p>
            <h1 class="text-3xl font-bold leading-tight">Panduan & Aturan Smart Classroom</h1>
            <p class="text-slate-400 mt-2 text-sm max-w-xl">
                Halaman ini merupakan acuan resmi yang wajib dipatuhi oleh seluruh pengguna sistem.
            </p>
            <div class="mt-4 flex flex-wrap gap-2">
                <a href="#faq" class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white text-sm font-semibold rounded-xl transition">FAQ</a>
                <a href="#reputasi" class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white text-sm font-semibold rounded-xl transition">Sistem Reputasi</a>
                <a href="#panduan" class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white text-sm font-semibold rounded-xl transition">Panduan</a>
                <a href="#kontak" class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white text-sm font-semibold rounded-xl transition">Kontak</a>
            </div>
        </div>
        <div class="absolute -top-16 -right-16 w-64 h-64 rounded-full bg-white/5 pointer-events-none"></div>
        <div class="absolute -bottom-12 -right-4 w-40 h-40 rounded-full bg-white/5 pointer-events-none"></div>
    </div>

    {{-- ===== SEARCH ===== --}}
    <div class="fade-up">
        <form action="{{ route('help.search') }}" method="GET" class="relative max-w-2xl mx-auto">
            <input type="text" name="q" placeholder="🔍 Cari di pusat bantuan..."
                   class="w-full px-5 py-4 bg-white border border-slate-200 rounded-2xl focus:border-green-500 focus:ring-1 focus:ring-green-500 outline-none text-sm placeholder:text-slate-400 shadow-sm">
            <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 px-4 py-2 bg-slate-900 hover:bg-slate-700 text-white text-xs font-semibold rounded-xl transition">
                Cari
            </button>
        </form>
    </div>

    {{-- ===== KATEGORI BANTUAN ===== --}}
    <div id="kategori" class="scroll-mt-28">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-4">📂 Kategori Bantuan</p>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3">
            @forelse($helpCategories as $category)
            <a href="{{ route('help.category', $category->slug) }}"
               class="p-4 bg-white rounded-2xl border border-slate-100 hover:border-blue-200 hover:shadow-md transition group text-center">
                <div class="text-3xl mb-2">{{ $category->icon ?? '📂' }}</div>
                <h3 class="font-bold text-slate-800 group-hover:text-blue-600 transition text-sm">{{ $category->name }}</h3>
                <p class="text-xs text-slate-400 mt-1">{{ $category->articles_count }} artikel</p>
            </a>
            @empty
            <div class="col-span-full text-center text-slate-400 py-4">
                Belum ada kategori bantuan.
            </div>
            @endforelse
        </div>
    </div>

    {{-- ===== ARTIKEL UNGGULAN ===== --}}
    @if($featuredArticles->count() > 0)
    <div id="artikel" class="scroll-mt-28">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-4">⭐ Artikel Unggulan</p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($featuredArticles as $article)
            <a href="{{ route('help.article', $article->slug) }}"
               class="block p-5 bg-white rounded-2xl border border-slate-100 hover:border-blue-200 hover:shadow-md transition group">
                <div class="flex items-start gap-3">
                    <span class="text-2xl">{{ $article->icon ?? '📄' }}</span>
                    <div>
                        <h3 class="font-bold text-slate-800 group-hover:text-blue-600 transition text-sm">
                            {{ $article->title }}
                        </h3>
                        <p class="text-xs text-slate-500 mt-1 line-clamp-2">{{ $article->excerpt }}</p>
                        <div class="flex items-center gap-3 mt-2 text-xs text-slate-400">
                            <span>{{ $article->category->name ?? 'Umum' }}</span>
                            <span>•</span>
                            <span>{{ $article->read_time }} menit</span>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ===== ATURAN DURASI BOOKING ===== --}}
    <div id="durasi" class="scroll-mt-28">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-4">⏱️ Batas Durasi Booking</p>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            @foreach($durasiBooking as $durasi)
            <div class="p-5 rounded-2xl bg-{{ $durasi['color'] }}-50 border border-{{ $durasi['color'] }}-100">
                <div class="w-10 h-10 bg-{{ $durasi['color'] }}-100 rounded-xl flex items-center justify-center text-xl mb-3">
                    {{ $durasi['icon'] }}
                </div>
                <p class="font-bold text-{{ $durasi['color'] }}-700">{{ $durasi['role'] }}</p>
                <p class="text-2xl font-extrabold text-slate-900 mt-1">{{ $durasi['durasi'] }}</p>
                <p class="text-xs text-slate-500 mt-1">{{ $durasi['keterangan'] }}</p>
            </div>
            @endforeach
        </div>
    </div>

    {{-- ===== PANDUAN BOOKING ===== --}}
    <div id="panduan" class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 scroll-mt-28">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-6">📖 1. Panduan Lengkap</p>

        <div class="space-y-8">
            {{-- Langkah-langkah --}}
            <div>
                <h3 class="font-semibold text-slate-800 mb-3">🔹 Langkah Booking</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($panduanLangkah as $step)
                    <div class="flex gap-4 p-4 bg-slate-50 rounded-2xl">
                        <div class="shrink-0 w-8 h-8 bg-slate-900 text-white rounded-xl flex items-center justify-center font-bold text-xs">
                            {{ $step['nomor'] }}
                        </div>
                        <div>
                            <p class="font-semibold text-slate-800 text-sm">{{ $step['judul'] }}</p>
                            <p class="text-xs text-slate-500 mt-0.5">{{ $step['detail'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Ketentuan Booking --}}
            <div>
                <h3 class="font-semibold text-slate-800 mb-3">🔹 Ketentuan Booking</h3>
                <ul class="list-disc pl-5 space-y-2 text-slate-600">
                    <li>Booking <strong>harus</strong> dilakukan melalui sistem</li>
                    <li>Tidak diperbolehkan menggunakan ruang tanpa booking resmi</li>
                    <li>Semua booking berstatus <strong>Pending</strong> hingga disetujui admin</li>
                    <li>Maksimal <strong>3 booking aktif</strong> per hari</li>
                </ul>
            </div>

            {{-- Larangan Booking --}}
            <div>
                <h3 class="font-semibold text-slate-800 mb-3">🔹 Larangan Booking</h3>
                <ul class="list-disc pl-5 space-y-2 text-red-600">
                    <li>Booking untuk kepentingan fiktif / palsu</li>
                    <li>Booking berulang tanpa penggunaan yang jelas (spam)</li>
                    <li>Menggunakan akun orang lain</li>
                    <li>Menggunakan ruang tanpa booking resmi</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- ===== REPUTATION POINT ===== --}}
    <div id="reputasi" class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 scroll-mt-28">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">⭐ 2. Sistem Reputation Point</p>
        <p class="text-sm text-slate-600 mb-6">
            Reputation Point mencerminkan tingkat keandalan dan kepatuhan pengguna. Point ini memengaruhi kemampuan booking kamu.
        </p>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            @foreach($aturanReputation as $rule)
            <div class="p-5 rounded-2xl bg-{{ $rule['warna'] }}-50 border border-{{ $rule['warna'] }}-100">
                <div class="text-3xl mb-2">{{ $rule['emoji'] }}</div>
                <p class="font-bold text-{{ $rule['warna'] }}-700">{{ $rule['range'] }}</p>
                <p class="text-sm font-semibold text-slate-800">{{ $rule['label'] }}</p>
                <p class="text-xs text-{{ $rule['warna'] }}-600 mt-1">{{ $rule['detail'] }}</p>
            </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wider mb-3">➕ Penambahan Point</p>
                <div class="space-y-2">
                    <div class="flex justify-between px-4 py-3 bg-emerald-50 border border-emerald-100 rounded-xl">
                        <span>Menggunakan ruang sesuai jadwal</span>
                        <span class="font-bold text-emerald-600">+10</span>
                    </div>
                    <div class="flex justify-between px-4 py-3 bg-emerald-50 border border-emerald-100 rounded-xl">
                        <span>Check-in tepat waktu</span>
                        <span class="font-bold text-emerald-600">+5</span>
                    </div>
                </div>
            </div>
            <div>
                <p class="text-xs font-semibold text-red-600 uppercase tracking-wider mb-3">➖ Pengurangan Point</p>
                <div class="space-y-2">
                    <div class="flex justify-between px-4 py-3 bg-red-50 border border-red-100 rounded-xl">
                        <span>No Show (tidak check-in)</span>
                        <span class="font-bold text-red-600">-15</span>
                    </div>
                    <div class="flex justify-between px-4 py-3 bg-red-50 border border-red-100 rounded-xl">
                        <span>Membatalkan booking mendadak (&lt; 1 jam)</span>
                        <span class="font-bold text-red-600">-10</span>
                    </div>
                    <div class="flex justify-between px-4 py-3 bg-red-50 border border-red-100 rounded-xl">
                        <span>Check-in terlambat</span>
                        <span class="font-bold text-red-600">-5</span>
                    </div>
                    <div class="flex justify-between px-4 py-3 bg-red-50 border border-red-100 rounded-xl">
                        <span>Booking fiktif / penyalahgunaan</span>
                        <span class="font-bold text-red-600">-20</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== SANKSI & PELANGGARAN ===== --}}
    <div id="sanksi" class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 scroll-mt-28">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-6">🚫 3. Aturan Sanksi & Pelanggaran</p>

        <div class="space-y-6">
            <div>
                <h3 class="font-semibold mb-3">Jenis Pelanggaran</h3>
                <ul class="list-disc pl-5 space-y-1.5 text-slate-600">
                    <li>Tidak melakukan check-in (No Show)</li>
                    <li>Membatalkan booking mendadak (&lt; 1 jam sebelum)</li>
                    <li>Menggunakan ruang tanpa izin / booking</li>
                    <li>Booking palsu atau manipulatif</li>
                    <li>Merusak fasilitas ruangan</li>
                </ul>
            </div>

            <div>
                <h3 class="font-semibold mb-3">Aturan Check-in</h3>
                <p class="text-slate-600">Wajib check-in <strong>maksimal 15 menit</strong> setelah waktu booking dimulai.<br>
                Jika tidak, booking otomatis dibatalkan dan point dikurangi.</p>
            </div>

            <div>
                <h3 class="font-semibold mb-3">Aturan Pembatalan</h3>
                <ul class="list-disc pl-5 space-y-1.5 text-slate-600">
                    <li><strong>≥ 1 jam sebelum</strong> → Tidak ada penalti</li>
                    <li><strong>&lt; 1 jam</strong> → Terkena pengurangan point (-10)</li>
                </ul>
            </div>

            <div>
                <h3 class="font-semibold mb-3">Sanksi Berdasarkan Level</h3>
                <ul class="list-disc pl-5 space-y-1.5 text-slate-600">
                    <li><strong>Trusted User (80-100):</strong> Prioritas approval</li>
                    <li><strong>Normal (50-79):</strong> Booking normal</li>
                    <li><strong>Dibatasi (30-49):</strong> Booking wajib review manual</li>
                    <li><strong>Diblokir (&lt; 30):</strong> Tidak bisa booking</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- ===== FAQ ===== --}}
    <div id="faq" class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 scroll-mt-28">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-6">❓ 4. Pertanyaan yang Sering Diajukan</p>
        <div class="space-y-3">
            @forelse($faqs as $faq)
            <div class="faq-item border border-slate-100 rounded-2xl overflow-hidden">
                <button class="faq-toggle w-full flex items-center justify-between px-5 py-4 text-left hover:bg-slate-50 transition" type="button">
                    <span class="flex items-center gap-3">
                        <span class="text-lg">{{ $faq->icon ?? '❓' }}</span>
                        <span class="text-sm font-semibold text-slate-800">{{ $faq->question }}</span>
                    </span>
                    <span class="faq-icon shrink-0 w-7 h-7 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500 text-sm font-bold">+</span>
                </button>
                <div class="faq-answer hidden px-5 pb-4">
                    <p class="text-sm text-slate-600 leading-relaxed">{!! nl2br(e($faq->answer)) !!}</p>
                    @if($faq->category)
                    <span class="inline-block mt-2 text-xs px-2 py-0.5 rounded-full bg-slate-100 text-slate-500 font-medium">
                        {{ $faq->category }}
                    </span>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-slate-400">
                <p class="text-4xl mb-2">📭</p>
                <p>Belum ada FAQ.</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- ===== KONTAK BANTUAN ===== --}}
    <div id="kontak" class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 scroll-mt-28">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-5">📞 5. Butuh Bantuan Lebih Lanjut?</p>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <a href="mailto:{{ $adminEmail ?? 'admin@smartclassroom.com' }}"
               target="_blank"
               class="flex items-start gap-3 p-4 rounded-2xl bg-slate-50 border border-slate-100 hover:border-blue-200 hover:bg-blue-50 transition">
                <span class="text-2xl shrink-0">📧</span>
                <div>
                    <p class="text-sm font-bold text-slate-800">Email Admin</p>
                    <p class="text-xs text-blue-600 mt-0.5 font-medium">{{ $adminEmail ?? 'admin@smartclassroom.com' }}</p>
                </div>
            </a>
            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($campusAddress ?? '') }}"
               target="_blank"
               class="flex items-start gap-3 p-4 rounded-2xl bg-slate-50 border border-slate-100 hover:bg-slate-100 transition">
                <span class="text-2xl shrink-0">📍</span>
                <div>
                    <p class="text-sm font-bold text-slate-800">Datang Langsung</p>
                    <p class="text-xs text-slate-500">{{ $adminRoomLocation ?? 'Ruangan Tata Usaha' }}</p>
                    <p class="text-xs text-slate-600 mt-1">{{ $campusAddress ?? 'Kampus' }}</p>
                </div>
            </a>
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $adminPhone ?? '') }}"
               target="_blank"
               class="flex items-start gap-3 p-4 rounded-2xl bg-slate-50 border border-slate-100 hover:border-emerald-200 hover:bg-emerald-50 transition">
                <span class="text-2xl shrink-0">💬</span>
                <div>
                    <p class="text-sm font-bold text-slate-800">WhatsApp</p>
                    <p class="text-xs text-emerald-600 mt-0.5 font-medium">{{ $adminPhone ?? '+62 812-3456-7890' }}</p>
                </div>
            </a>
        </div>
    </div>

    {{-- ===== BACK TO TOP BUTTON ===== --}}
    <button id="backToTop"
            class="fixed bottom-8 right-8 w-11 h-11 bg-slate-900 text-white rounded-full shadow-lg hover:bg-slate-700 opacity-0 invisible z-30 flex items-center justify-center transition-all"
            type="button">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
        </svg>
    </button>

</div>

@push('scripts')
<script>
$(document).ready(function() {
    'use strict';

    // ===== FAQ - Toggle =====
    $('.faq-toggle').on('click', function(e) {
        e.preventDefault();

        var $item = $(this).closest('.faq-item');
        var $answer = $item.find('.faq-answer');
        var $icon = $(this).find('.faq-icon');

        // Tutup semua FAQ lainnya
        $('.faq-item').not($item).each(function() {
            $(this).find('.faq-answer').slideUp(200);
            $(this).find('.faq-icon').text('+');
        });

        // Toggle FAQ yang diklik
        if ($answer.is(':visible')) {
            $answer.slideUp(200);
            $icon.text('+');
        } else {
            $answer.slideDown(200);
            $icon.text('−');
        }
    });

    // ===== Back to Top Button =====
    var $backToTop = $('#backToTop');

    $(window).on('scroll', function() {
        if ($(window).scrollTop() > 300) {
            $backToTop.removeClass('opacity-0 invisible').addClass('opacity-100 visible');
        } else {
            $backToTop.removeClass('opacity-100 visible').addClass('opacity-0 invisible');
        }
    });

    $backToTop.on('click', function(e) {
        e.preventDefault();
        $('html, body').animate({ scrollTop: 0 }, 400);
    });

    // ===== Handle URL Hash =====
    if (window.location.hash) {
        var targetId = window.location.hash;
        var $target = $(targetId);
        if ($target.length) {
            setTimeout(function() {
                var offset = 90;
                var targetPosition = $target.offset().top - offset;
                $('html, body').animate({ scrollTop: targetPosition }, 400);
            }, 100);
        }
    }

    // ===== Auto-open FAQ jika ada hash #faq =====
    if (window.location.hash === '#faq') {
        setTimeout(function() {
            $('.faq-toggle:first').click();
        }, 300);
    }
});
</script>
@endpush

@endsection
