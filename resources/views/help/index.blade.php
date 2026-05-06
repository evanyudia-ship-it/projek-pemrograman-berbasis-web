@extends('layouts.app')

@section('title', 'Bantuan & Panduan')
@section('page_title', 'Bantuan & Panduan')
@section('page_subtitle', 'Petunjuk resmi penggunaan sistem Smart Classroom')

@section('content')

<div class="max-w-5xl mx-auto space-y-8 font-sora fade-up">

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
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6">
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
                <p class="text-2xl font-extrabold text-slate-900 mt-1">Fleksibel</p>
                <p class="text-xs text-slate-500 mt-1">Butuh persetujuan penanggung jawab</p>
            </div>
        </div>
    </div>

    {{-- ===== PANDUAN BOOKING ===== --}}
    <div id="panduan" class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6">
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
    <div id="reputation" class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6">
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
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6">
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
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6">
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

    {{-- ===== FAQ & KONTAK ===== --}}
    <div id="faq" class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-6">Pertanyaan yang Sering Diajukan</p>
        <!-- FAQ tetap sama atau bisa ditambah -->
        <div class="space-y-3" id="faqList">
            @foreach($faqs as $i => $faq)
            <div class="faq-item border border-slate-100 rounded-2xl overflow-hidden">
                <button class="faq-toggle w-full flex items-center justify-between px-5 py-4 text-left hover:bg-slate-50 transition" data-index="{{ $i }}">
                    <span class="text-sm font-semibold text-slate-800 pr-4">{{ $faq['pertanyaan'] }}</span>
                    <span class="faq-icon shrink-0 w-7 h-7 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500 text-sm font-bold transition-transform">+</span>
                </button>
                <div class="faq-answer hidden px-5 pb-4">
                    <p class="text-sm text-slate-500 leading-relaxed border-l-2 border-slate-200 pl-4">{{ $faq['jawaban'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- ===== KONTAK BANTUAN ===== --}}
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-5">Butuh Bantuan Lebih Lanjut?</p>
        <!-- Kontak tetap sama -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <a href="https://mail.google.com/mail/?view=cm&to=syaefuldarmawan02@gmail.com" target="_blank" class="flex items-start gap-3 p-4 rounded-2xl bg-slate-50 border border-slate-100 hover:border-blue-200 hover:bg-blue-50 transition">
                <span class="text-2xl shrink-0">📧</span>
                <div>
                    <p class="text-sm font-bold text-slate-800">Email Admin</p>
                    <p class="text-xs text-blue-600 mt-0.5 font-medium">syaefuldarmawan02@gmail.com</p>
                </div>
            </a>
            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($room['alamat'] ?? '') }}"
            class="flex items-start gap-3 p-4 rounded-2xl bg-slate-50 border border-slate-100 hover:bg-slate-100 transition-colors">
                <span class="text-2xl shrink-0">📍</span>
                <div>
                    <p class="text-sm font-bold text-slate-800">Datang Langsung</p>
                    <p class="text-xs text-slate-500">Ruang Admin Gedung A Lt. 1</p>
                    @if(!empty($room['alamat']))
                        <p class="text-xs text-slate-600 mt-1 line-clamp-2">{{ $room['alamat'] }}</p>
                    @endif
                </div>
            </a>
            <a href="https://wa.me/6285797279169" target="_blank" class="flex items-start gap-3 p-4 rounded-2xl bg-slate-50 border border-slate-100 hover:border-emerald-200 hover:bg-emerald-50 transition">
                <span class="text-2xl shrink-0">💬</span>
                <div>
                    <p class="text-sm font-bold text-slate-800">WhatsApp</p>
                    <p class="text-xs text-emerald-600 mt-0.5 font-medium">+62 857-9727-9169</p>
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

        $('.faq-answer').addClass('hidden');
        $('.faq-icon').text('+');

        if (!isOpen) {
            $answer.removeClass('hidden');
            $icon.text('−');
        }
    });
});
</script>
@endpush

@endsection