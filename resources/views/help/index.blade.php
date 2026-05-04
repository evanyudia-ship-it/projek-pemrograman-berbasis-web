@extends('layouts.app')

@section('title', 'Bantuan & Panduan')
@section('page_title', 'Bantuan & Panduan')
@section('page_subtitle', 'Petunjuk penggunaan sistem, aturan reputation, dan FAQ')

@section('content')

<div class="max-w-5xl mx-auto space-y-8 font-sora fade-up">

    {{-- ===== HERO ===== --}}
    <div class="bg-slate-950 rounded-3xl p-8 text-white relative overflow-hidden">
        <div class="relative z-10">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest mb-3">Pusat Bantuan</p>
            <h1 class="text-3xl font-bold leading-tight">Panduan Smart Classroom</h1>
            <p class="text-slate-400 mt-2 text-sm max-w-xl">
                Temukan petunjuk penggunaan, aturan booking, sistem reputation, dan jawaban atas pertanyaan umum seputar sistem ini.
            </p>
            <div class="flex flex-wrap gap-3 mt-6">
                <a href="#panduan"
                   class="px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white text-sm font-semibold transition">
                    📖 Panduan Langkah
                </a>
                <a href="#reputation"
                   class="px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white text-sm font-semibold transition">
                    ⭐ Aturan Reputation
                </a>
                <a href="#faq"
                   class="px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white text-sm font-semibold transition">
                    ❓ FAQ
                </a>
            </div>
        </div>
        {{-- Decorative circles --}}
        <div class="absolute -top-16 -right-16 w-64 h-64 rounded-full bg-white/5 pointer-events-none"></div>
        <div class="absolute -bottom-12 -right-4 w-40 h-40 rounded-full bg-white/5 pointer-events-none"></div>
    </div>

    {{-- ===== ATURAN DURASI BOOKING ===== --}}
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 fade-up delay-1">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-5">Batas Durasi Booking per Role</p>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="p-5 rounded-2xl bg-blue-50 border border-blue-100">
                <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center text-xl mb-3">🎓</div>
                <p class="font-bold text-blue-700">Mahasiswa</p>
                <p class="text-2xl font-extrabold text-slate-900 mt-1">2 Jam</p>
                <p class="text-xs text-slate-500 mt-1">Maksimal per sesi booking</p>
            </div>
            <div class="p-5 rounded-2xl bg-indigo-50 border border-indigo-100">
                <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center text-xl mb-3">👨‍🏫</div>
                <p class="font-bold text-indigo-700">Dosen</p>
                <p class="text-2xl font-extrabold text-slate-900 mt-1">6 Jam</p>
                <p class="text-xs text-slate-500 mt-1">Maksimal per sesi booking</p>
            </div>
            <div class="p-5 rounded-2xl bg-emerald-50 border border-emerald-100">
                <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center text-xl mb-3">🏢</div>
                <p class="font-bold text-emerald-700">Organisasi</p>
                <p class="text-2xl font-extrabold text-slate-900 mt-1">4 Jam</p>
                <p class="text-xs text-slate-500 mt-1">Wajib validasi penanggung jawab</p>
            </div>
        </div>
    </div>

    {{-- ===== PANDUAN LANGKAH ===== --}}
    <div id="panduan" class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 fade-up delay-2">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-6">Panduan Penggunaan Sistem</p>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($panduanLangkah as $langkah)
            <div class="flex gap-4 p-4 rounded-2xl bg-slate-50 border border-slate-100 hover:border-slate-200 transition">
                <div class="shrink-0">
                    <div class="w-12 h-12 rounded-2xl bg-slate-900 text-white flex items-center justify-center text-xl">
                        {{ $langkah['icon'] }}
                    </div>
                </div>
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-xs font-bold text-slate-400 font-mono">{{ $langkah['nomor'] }}</span>
                        <p class="font-bold text-slate-800 text-sm">{{ $langkah['judul'] }}</p>
                    </div>
                    <p class="text-xs text-slate-500 leading-relaxed">{{ $langkah['detail'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- ===== ATURAN REPUTATION ===== --}}
    <div id="reputation" class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 fade-up delay-3">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Sistem Reputation Point</p>
        <p class="text-sm text-slate-500 mb-6">
            Reputation point mencerminkan keandalan pengguna dalam menggunakan ruang. Point bertambah saat kamu tertib, dan berkurang saat melanggar aturan.
        </p>

        {{-- Tier cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            @foreach($aturanReputation as $tier)
            @php
                $colorMap = [
                    'emerald' => ['bg' => 'bg-emerald-50', 'border' => 'border-emerald-100', 'title' => 'text-emerald-700', 'badge' => 'bg-emerald-100 text-emerald-700'],
                    'blue'    => ['bg' => 'bg-blue-50',    'border' => 'border-blue-100',    'title' => 'text-blue-700',    'badge' => 'bg-blue-100 text-blue-700'],
                    'amber'   => ['bg' => 'bg-amber-50',   'border' => 'border-amber-100',   'title' => 'text-amber-700',   'badge' => 'bg-amber-100 text-amber-700'],
                    'red'     => ['bg' => 'bg-red-50',     'border' => 'border-red-100',     'title' => 'text-red-700',     'badge' => 'bg-red-100 text-red-700'],
                ];
                $c = $colorMap[$tier['warna']];
            @endphp
            <div class="p-4 rounded-2xl {{ $c['bg'] }} border {{ $c['border'] }}">
                <div class="text-2xl mb-2">{{ $tier['emoji'] }}</div>
                <p class="font-bold {{ $c['title'] }} text-sm">{{ $tier['label'] }}</p>
                <p class="text-xs font-mono font-semibold text-slate-600 mt-0.5">{{ $tier['range'] }} poin</p>
                <p class="text-xs text-slate-500 mt-2 leading-relaxed">{{ $tier['detail'] }}</p>
            </div>
            @endforeach
        </div>

        {{-- Tabel tambah/kurang point --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wider mb-3">➕ Penambahan Point</p>
                <div class="space-y-2">
                    @foreach([
                        ['aksi' => 'Check-in tepat waktu', 'poin' => '+3'],
                        ['aksi' => 'Menggunakan ruang sesuai jadwal', 'poin' => '+5'],
                        ['aksi' => 'Mengembalikan kondisi ruang dengan baik', 'poin' => '+2'],
                        ['aksi' => 'Booking selesai tanpa pelanggaran', 'poin' => '+2'],
                    ] as $item)
                    <div class="flex items-center justify-between px-4 py-2.5 rounded-xl bg-emerald-50 border border-emerald-100">
                        <span class="text-xs text-slate-600">{{ $item['aksi'] }}</span>
                        <span class="text-xs font-bold text-emerald-600 ml-3 shrink-0">{{ $item['poin'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            <div>
                <p class="text-xs font-semibold text-red-500 uppercase tracking-wider mb-3">➖ Pengurangan Point</p>
                <div class="space-y-2">
                    @foreach([
                        ['aksi' => 'No Show (tidak check-in)', 'poin' => '-10'],
                        ['aksi' => 'Pembatalan mendadak (< 2 jam)', 'poin' => '-5'],
                        ['aksi' => 'Penggunaan melebihi waktu booking', 'poin' => '-5'],
                        ['aksi' => 'Kondisi ruang tidak dikembalikan', 'poin' => '-3'],
                    ] as $item)
                    <div class="flex items-center justify-between px-4 py-2.5 rounded-xl bg-red-50 border border-red-100">
                        <span class="text-xs text-slate-600">{{ $item['aksi'] }}</span>
                        <span class="text-xs font-bold text-red-600 ml-3 shrink-0">{{ $item['poin'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- ===== FAQ ===== --}}
    <div id="faq" class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 fade-up delay-4">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-6">Pertanyaan yang Sering Diajukan</p>

        <div class="space-y-3" id="faqList">
            @foreach($faqs as $i => $faq)
            <div class="faq-item border border-slate-100 rounded-2xl overflow-hidden">
                <button
                    class="faq-toggle w-full flex items-center justify-between px-5 py-4 text-left hover:bg-slate-50 transition"
                    data-index="{{ $i }}">
                    <span class="text-sm font-semibold text-slate-800 pr-4">{{ $faq['pertanyaan'] }}</span>
                    <span class="faq-icon shrink-0 w-7 h-7 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500 text-sm font-bold transition-transform">
                        +
                    </span>
                </button>
                <div class="faq-answer hidden px-5 pb-4">
                    <p class="text-sm text-slate-500 leading-relaxed border-l-2 border-slate-200 pl-4">
                        {{ $faq['jawaban'] }}
                    </p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- ===== KONTAK BANTUAN ===== --}}
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 fade-up">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-5">Butuh Bantuan Lebih Lanjut?</p>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            {{-- Email Admin — klik langsung buka Gmail compose --}}
            <a href="https://mail.google.com/mail/?view=cm&to=syaefuldarmawan02@gmail.com"
                target="_blank"
                class="flex items-start gap-3 p-4 rounded-2xl bg-slate-50 border border-slate-100 hover:border-blue-200 hover:bg-blue-50 transition">
                <span class="text-2xl shrink-0">📧</span>
                <div>
                    <p class="text-sm font-bold text-slate-800">Email Admin</p>
                    <p class="text-xs text-blue-600 mt-0.5 font-medium">syaefuldarmawan02@gmail.com</p>
                    <p class="text-xs text-slate-400 mt-1">Respon dalam 1×24 jam</p>
                </div>
            </a>
            <div class="flex items-start gap-3 p-4 rounded-2xl bg-slate-50 border border-slate-100">
                <span class="text-2xl shrink-0">📍</span>
                <div>
                    <p class="text-sm font-bold text-slate-800">Datang Langsung</p>
                    <p class="text-xs text-slate-500 mt-0.5">Ruang Admin Gedung A Lt. 1</p>
                    <p class="text-xs text-slate-400 mt-1">Senin – Jumat, 08.00 – 16.00</p>
                </div>
            </div>
            {{-- WhatsApp — klik langsung buka chat WA --}}
            <a href="https://wa.me/6285797279169"
                target="_blank"
                class="flex items-start gap-3 p-4 rounded-2xl bg-slate-50 border border-slate-100 hover:border-emerald-200 hover:bg-emerald-50 transition">
                <span class="text-2xl shrink-0">💬</span>
                <div>
                    <p class="text-sm font-bold text-slate-800">WhatsApp</p>
                    <p class="text-xs text-emerald-600 mt-0.5 font-medium">+62 857-9727-9169</p>
                    <p class="text-xs text-slate-400 mt-1">Jam kerja kampus</p>
                </div>
            </a>
        </div>
    </div>

</div>

@push('scripts')
<script>
$(document).ready(function () {
    $('.faq-toggle').on('click', function () {
        const $item   = $(this).closest('.faq-item');
        const $answer = $item.find('.faq-answer');
        const $icon   = $(this).find('.faq-icon');
        const isOpen  = !$answer.hasClass('hidden');

        // Tutup semua
        $('.faq-answer').addClass('hidden');
        $('.faq-icon').text('+').css('transform', 'rotate(0deg)');

        // Buka yang diklik (kecuali kalau sudah terbuka)
        if (!isOpen) {
            $answer.removeClass('hidden');
            $icon.text('×').css('transform', 'rotate(0deg)');
        }
    });
});
</script>
@endpush

@endsection