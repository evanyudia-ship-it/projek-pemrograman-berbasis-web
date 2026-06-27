@extends('layouts.app')

@section('title', 'Bantuan & Panduan')
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
        </div>
        <div class="absolute -top-16 -right-16 w-64 h-64 rounded-full bg-white/5 pointer-events-none"></div>
        <div class="absolute -bottom-12 -right-4 w-40 h-40 rounded-full bg-white/5 pointer-events-none"></div>
    </div>

    {{-- ===== ATURAN DURASI BOOKING ===== --}}
    <div id="durasi" class="grid grid-cols-1 sm:grid-cols-3 gap-4 scroll-mt-28">
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

    {{-- ===== PANDUAN BOOKING ===== --}}
    <div id="panduan" class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 scroll-mt-28">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-6">1. Aturan Cara Booking</p>

        <div class="space-y-8">
            <div>
                <h3 class="font-semibold text-slate-800 mb-3">🔹 Langkah Booking</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex gap-4 p-4 bg-slate-50 rounded-2xl">
                        <div class="shrink-0 w-8 h-8 bg-slate-900 text-white rounded-xl flex items-center justify-center font-bold">1</div>
                        <div>Pilih ruang melalui halaman <strong>Daftar Ruang</strong> atau <strong>Detail Ruang</strong></div>
                    </div>
                    <div class="flex gap-4 p-4 bg-slate-50 rounded-2xl">
                        <div class="shrink-0 w-8 h-8 bg-slate-900 text-white rounded-xl flex items-center justify-center font-bold">2</div>
                        <div>Klik tombol <strong>Booking</strong></div>
                    </div>
                    <div class="flex gap-4 p-4 bg-slate-50 rounded-2xl">
                        <div class="shrink-0 w-8 h-8 bg-slate-900 text-white rounded-xl flex items-center justify-center font-bold">3</div>
                        <div>Isi: Tanggal & Waktu, Durasi, Tujuan Penggunaan</div>
                    </div>
                    <div class="flex gap-4 p-4 bg-slate-50 rounded-2xl">
                        <div class="shrink-0 w-8 h-8 bg-slate-900 text-white rounded-xl flex items-center justify-center font-bold">4</div>
                        <div>Klik <strong>Submit Booking</strong></div>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="font-semibold text-slate-800 mb-3">🔹 Ketentuan Booking</h3>
                <ul class="list-disc pl-5 space-y-2 text-slate-600">
                    <li>Booking <strong>harus</strong> dilakukan melalui sistem</li>
                    <li>Tidak diperbolehkan menggunakan ruang tanpa booking resmi</li>
                    <li>Semua booking berstatus <strong>Pending</strong> hingga disetujui</li>
                </ul>
            </div>

            <div>
                <h3 class="font-semibold text-slate-800 mb-3">🔹 Larangan Booking</h3>
                <ul class="list-disc pl-5 space-y-2 text-red-600">
                    <li>Booking untuk kepentingan fiktif / palsu</li>
                    <li>Booking berulang tanpa penggunaan yang jelas (spam)</li>
                    <li>Menggunakan akun orang lain</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- ===== REPUTATION POINT ===== --}}
    <div id="reputation" class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 scroll-mt-28">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">2. Sistem Reputation Point</p>
        <p class="text-sm text-slate-600 mb-6">
            Reputation Point mencerminkan tingkat keandalan dan kepatuhan pengguna. Point ini memengaruhi kemampuan booking kamu.
        </p>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="p-5 rounded-2xl bg-red-50 border border-red-100">
                <div class="text-3xl mb-2">❌</div>
                <p class="font-bold text-red-700">0 – 29</p>
                <p class="text-xs text-red-600">Suspended — Tidak dapat melakukan booking</p>
            </div>
            <div class="p-5 rounded-2xl bg-amber-50 border border-amber-100">
                <div class="text-3xl mb-2">⚠️</div>
                <p class="font-bold text-amber-700">30 – 50</p>
                <p class="text-xs text-amber-600">Booking dibatasi</p>
            </div>
            <div class="p-5 rounded-2xl bg-emerald-50 border border-emerald-100">
                <div class="text-3xl mb-2">✅</div>
                <p class="font-bold text-emerald-700">51 – 80</p>
                <p class="text-xs text-emerald-600">Normal</p>
            </div>
            <div class="p-5 rounded-2xl bg-violet-50 border border-violet-100">
                <div class="text-3xl mb-2">⭐</div>
                <p class="font-bold text-violet-700">81 – 100</p>
                <p class="text-xs text-violet-600">Prioritas Tinggi</p>
            </div>
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
                        <span>Membatalkan booking mendadak</span>
                        <span class="font-bold text-red-600">-10</span>
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
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-6">3. Aturan Sanksi & Pelanggaran</p>

        <div class="space-y-6">
            <div>
                <h3 class="font-semibold mb-3">Jenis Pelanggaran</h3>
                <ul class="list-disc pl-5 space-y-1.5 text-slate-600">
                    <li>Tidak melakukan check-in</li>
                    <li>Membatalkan booking mendadak</li>
                    <li>Menggunakan ruang tanpa izin / booking</li>
                    <li>Booking palsu atau manipulatif</li>
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
                    <li><strong>< 1 jam</strong> → Terkena pengurangan point</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- ===== ATURAN TAMBAHAN ===== --}}
    <div id="aturan-tambahan" class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 scroll-mt-28">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-6">4. Aturan Tambahan</p>
        <div class="space-y-4">
            <div class="flex gap-4">
                <span class="shrink-0 text-xl">📋</span>
                <div>
                    <p class="font-semibold">Approval Booking</p>
                    <p class="text-sm text-slate-600">Semua booking harus disetujui dalam <strong>1×24 jam</strong>. Jika tidak disetujui, status otomatis menjadi <strong>Expired</strong>.</p>
                </div>
            </div>
            <div class="flex gap-4">
                <span class="shrink-0 text-xl">🔀</span>
                <div>
                    <p class="font-semibold">Prioritas Booking</p>
                    <p class="text-sm text-slate-600">Dalam kasus konflik jadwal: <strong>Dosen > Organisasi > Mahasiswa</strong></p>
                </div>
            </div>
            <div class="flex gap-4">
                <span class="shrink-0 text-xl">📜</span>
                <div>
                    <p class="font-semibold">Kepatuhan Pengguna</p>
                    <p class="text-sm text-slate-600">Dengan menggunakan sistem ini, Anda dianggap <strong>menyetujui seluruh aturan</strong> dan bersedia menerima sanksi jika melanggar.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== FAQ ===== --}}
    <div id="faq" class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 scroll-mt-28">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-6">Pertanyaan yang Sering Diajukan</p>
        <div class="space-y-3">
            @foreach(($faqs ?? []) as $faq)
            <div class="faq-item border border-slate-100 rounded-2xl overflow-hidden">
                <button class="faq-toggle w-full flex items-center justify-between px-5 py-4 text-left hover:bg-slate-50" type="button">
                    <span class="text-sm font-semibold text-slate-800 pr-4">{{ e($faq['pertanyaan'] ?? '-') }}</span>
                    <span class="faq-icon shrink-0 w-7 h-7 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500 text-sm font-bold">+</span>
                </button>
                <div class="faq-answer hidden px-5 pb-4">
                    <p class="text-sm text-slate-500 leading-relaxed">{{ e($faq['jawaban'] ?? '-') }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- ===== KONTAK BANTUAN ===== --}}
    <div id="kontak" class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 scroll-mt-28">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-5">Butuh Bantuan Lebih Lanjut?</p>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <a href="https://mail.google.com/mail/?view=cm&to=syaefuldarmawan02@gmail.com" target="_blank" class="flex items-start gap-3 p-4 rounded-2xl bg-slate-50 border border-slate-100 hover:border-blue-200 hover:bg-blue-50">
                <span class="text-2xl shrink-0">📧</span>
                <div>
                    <p class="text-sm font-bold text-slate-800">Email Admin</p>
                    <p class="text-xs text-blue-600 mt-0.5 font-medium">syaefuldarmawan02@gmail.com</p>
                </div>
            </a>
            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($campusAddress ?? '') }}" class="flex items-start gap-3 p-4 rounded-2xl bg-slate-50 border border-slate-100 hover:bg-slate-100">
                <span class="text-2xl shrink-0">📍</span>
                <div>
                    <p class="text-sm font-bold text-slate-800">Datang Langsung</p>
                    <p class="text-xs text-slate-500">{{ $adminRoomLocation ?? 'Ruangan Tata Usaha' }}</p>
                    <p class="text-xs text-slate-600 mt-1">{{ $campusAddress ?? 'Kampus' }}</p>
                </div>
            </a>
            <a href="https://wa.me/6285797279169" target="_blank" class="flex items-start gap-3 p-4 rounded-2xl bg-slate-50 border border-slate-100 hover:border-emerald-200 hover:bg-emerald-50">
                <span class="text-2xl shrink-0">💬</span>
                <div>
                    <p class="text-sm font-bold text-slate-800">WhatsApp</p>
                    <p class="text-xs text-emerald-600 mt-0.5 font-medium">+62 857-9727-9169</p>
                </div>
            </a>
        </div>
    </div>

    {{-- ===== BACK TO TOP BUTTON ===== --}}
    <button id="backToTop"
            class="fixed bottom-8 right-8 w-11 h-11 bg-slate-900 text-white rounded-full shadow-lg hover:bg-slate-700 opacity-0 invisible z-30 flex items-center justify-center"
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

    // ===== FAQ - Toggle dengan jQuery (tanpa animasi) =====
    $('.faq-toggle').on('click', function(e) {
        e.preventDefault();

        var $item = $(this).closest('.faq-item');
        var $answer = $item.find('.faq-answer');
        var $icon = $(this).find('.faq-icon');

        // Tutup semua FAQ lainnya
        $('.faq-item').not($item).each(function() {
            $(this).find('.faq-answer').hide();
            $(this).find('.faq-icon').text('+');
        });

        // Toggle FAQ yang diklik
        if ($answer.is(':visible')) {
            $answer.hide();
            $icon.text('+');
        } else {
            $answer.show();
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
        $('html, body').animate({ scrollTop: 0 }, 0);
    });

    // ===== Handle URL Hash (scroll ke section yang dituju) =====
    if (window.location.hash) {
        var targetId = window.location.hash;
        var $target = $(targetId);
        if ($target.length) {
            setTimeout(function() {
                var offset = 90;
                var targetPosition = $target.offset().top - offset;
                $('html, body').animate({ scrollTop: targetPosition }, 0);
            }, 50);
        }
    }
});
</script>
@endpush

@endsection
