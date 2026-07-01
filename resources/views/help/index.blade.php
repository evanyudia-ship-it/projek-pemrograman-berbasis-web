@extends('layouts.app')

@section('title', 'Bantuan & Panduan - Smart Classroom')
@section('page_title', 'Bantuan & Panduan')
@section('page_subtitle', 'Petunjuk resmi penggunaan sistem Smart Classroom')

@section('content')

<div class="max-w-6xl mx-auto font-sora space-y-8">

    <div class="relative bg-gradient-to-br from-indigo-700 via-purple-700 to-blue-700 dark:from-indigo-900 dark:via-purple-900 dark:to-blue-900 rounded-3xl p-8 md:p-10 text-white overflow-hidden shadow-xl">
        {{-- Background Decoration --}}
        <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/3"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-white/5 rounded-full translate-y-1/2 -translate-x-1/4"></div>
        <div class="absolute top-1/2 left-1/2 w-48 h-48 bg-white/5 rounded-full -translate-x-1/2 -translate-y-1/2"></div>

        <div class="relative z-10">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div class="flex items-start gap-5">
                    <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center text-3xl shrink-0">
                        📚
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-indigo-200 uppercase tracking-widest">Pusat Bantuan Resmi</p>
                        <h1 class="text-3xl md:text-4xl font-bold mt-1 leading-tight">Panduan & Aturan</h1>
                        <p class="text-indigo-200 mt-2 text-sm max-w-xl leading-relaxed">
                            Halaman ini merupakan acuan resmi yang wajib dipatuhi oleh seluruh pengguna sistem Smart Classroom.
                        </p>
                    </div>
                </div>
                <div class="flex flex-wrap gap-2 shrink-0">
                    <a href="#faq" class="px-4 py-2 bg-white/20 hover:bg-white/30 backdrop-blur text-white text-sm font-semibold rounded-xl transition flex items-center gap-1.5">
                        <i class="fas fa-question-circle"></i> FAQ
                    </a>
                    <a href="#reputasi" class="px-4 py-2 bg-white/20 hover:bg-white/30 backdrop-blur text-white text-sm font-semibold rounded-xl transition flex items-center gap-1.5">
                        <i class="fas fa-star"></i> Reputasi
                    </a>
                    <a href="#panduan" class="px-4 py-2 bg-white/20 hover:bg-white/30 backdrop-blur text-white text-sm font-semibold rounded-xl transition flex items-center gap-1.5">
                        <i class="fas fa-book"></i> Panduan
                    </a>
                    <a href="#kontak" class="px-4 py-2 bg-white/20 hover:bg-white/30 backdrop-blur text-white text-sm font-semibold rounded-xl transition flex items-center gap-1.5">
                        <i class="fas fa-headset"></i> Kontak
                    </a>
                </div>
            </div>

            {{-- Quick Stats --}}
            <div class="mt-6 flex flex-wrap gap-6 text-sm text-indigo-200 border-t border-white/10 pt-5">
                <span class="flex items-center gap-1.5">
                    <i class="fas fa-file-alt text-indigo-300"></i>
                    {{ $helpCategories->sum('articles_count') }} Artikel
                </span>
                <span class="flex items-center gap-1.5">
                    <i class="fas fa-folder text-indigo-300"></i>
                    {{ $helpCategories->count() }} Kategori
                </span>
                <span class="flex items-center gap-1.5">
                    <i class="fas fa-question-circle text-indigo-300"></i>
                    {{ $faqs->count() }} FAQ
                </span>
            </div>
        </div>
    </div>

    <div class="relative max-w-3xl mx-auto w-full">
        <form action="{{ route('help.search') }}" method="GET" class="relative">
            <div class="relative">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 dark:text-slate-500 text-sm"></i>
                <input type="text" name="q" placeholder="Cari di pusat bantuan..."
                       class="w-full pl-11 pr-36 py-4 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm placeholder:text-slate-400 dark:placeholder:text-slate-500 shadow-sm transition">
                <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition flex items-center gap-2 shadow-sm shadow-indigo-200 dark:shadow-indigo-900/30">
                    <i class="fas fa-search text-xs"></i> Cari
                </button>
            </div>
        </form>
    </div>

    <div id="kategori" class="scroll-mt-24">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-8 h-8 rounded-xl bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                <i class="fas fa-folder-open text-sm"></i>
            </div>
            <p class="text-sm font-bold text-slate-700 dark:text-slate-300">Kategori Bantuan</p>
            <span class="text-xs text-slate-400 dark:text-slate-500">| {{ $helpCategories->count() }} kategori</span>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
            @forelse($helpCategories as $category)
            <a href="{{ route('help.category', $category->slug) }}"
               class="group p-5 bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 hover:border-indigo-200 dark:hover:border-indigo-800 hover:shadow-lg transition text-center">
                <div class="text-4xl mb-3 group-hover:scale-110 transition-transform duration-200">{{ $category->icon ?? '📂' }}</div>
                <h3 class="font-bold text-slate-800 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition text-sm line-clamp-1">{{ $category->name }}</h3>
                <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">{{ $category->articles_count }} artikel</p>
            </a>
            @empty
            <div class="col-span-full text-center text-slate-400 dark:text-slate-500 py-8">
                <div class="flex flex-col items-center">
                    <span class="text-4xl mb-3">📭</span>
                    <p class="font-semibold">Belum ada kategori bantuan</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>

    @if($featuredArticles->count() > 0)
    <div id="artikel" class="scroll-mt-24">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-8 h-8 rounded-xl bg-amber-100 dark:bg-amber-900/50 flex items-center justify-center text-amber-600 dark:text-amber-400">
                <i class="fas fa-star text-sm"></i>
            </div>
            <p class="text-sm font-bold text-slate-700 dark:text-slate-300">Artikel Unggulan</p>
            <span class="text-xs text-slate-400 dark:text-slate-500">| {{ $featuredArticles->count() }} artikel</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($featuredArticles as $article)
            <a href="{{ route('help.article', $article->slug) }}"
               class="group p-5 bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 hover:border-indigo-200 dark:hover:border-indigo-800 hover:shadow-lg transition">
                <div class="flex items-start gap-4">
                    <span class="text-3xl group-hover:scale-110 transition-transform duration-200">{{ $article->icon ?? '📄' }}</span>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-bold text-slate-800 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition text-sm line-clamp-2">
                            {{ $article->title }}
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 line-clamp-2">{{ $article->excerpt }}</p>
                        <div class="flex items-center gap-2 mt-2 text-xs text-slate-400 dark:text-slate-500">
                            <span class="px-2 py-0.5 rounded-full bg-slate-100 dark:bg-slate-700">{{ $article->category->name ?? 'Umum' }}</span>
                            <span>•</span>
                            <span><i class="far fa-clock mr-1"></i>{{ $article->read_time }} menit</span>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    <div id="durasi" class="scroll-mt-24">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-8 h-8 rounded-xl bg-cyan-100 dark:bg-cyan-900/50 flex items-center justify-center text-cyan-600 dark:text-cyan-400">
                <i class="fas fa-clock text-sm"></i>
            </div>
            <p class="text-sm font-bold text-slate-700 dark:text-slate-300">Batas Durasi Booking</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            @foreach($durasiBooking as $durasi)
            <div class="p-5 rounded-2xl bg-{{ $durasi['color'] }}-50 dark:bg-{{ $durasi['color'] }}-950/30 border border-{{ $durasi['color'] }}-200 dark:border-{{ $durasi['color'] }}-800 group hover:shadow-md transition">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-11 h-11 rounded-xl bg-{{ $durasi['color'] }}-100 dark:bg-{{ $durasi['color'] }}-900/50 flex items-center justify-center text-2xl group-hover:scale-110 transition">
                        {{ $durasi['icon'] }}
                    </div>
                    <div>
                        <p class="font-bold text-{{ $durasi['color'] }}-700 dark:text-{{ $durasi['color'] }}-400">{{ $durasi['role'] }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ $durasi['keterangan'] }}</p>
                    </div>
                </div>
                <p class="text-3xl font-extrabold text-slate-900 dark:text-white">{{ $durasi['durasi'] }}</p>
            </div>
            @endforeach
        </div>
    </div>

    <div id="panduan" class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden scroll-mt-24">
        <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700 bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-indigo-950/30 dark:to-blue-950/30">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                    <i class="fas fa-book-open text-lg"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800 dark:text-white">Panduan Lengkap Booking</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Langkah-langkah dan ketentuan booking ruangan</p>
                </div>
            </div>
        </div>

        <div class="p-6 space-y-6">
            {{-- Steps --}}
            <div>
                <h4 class="font-semibold text-slate-700 dark:text-slate-300 text-sm mb-3 flex items-center gap-2">
                    <i class="fas fa-list-ol text-indigo-500"></i> Langkah Booking
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach($panduanLangkah as $step)
                    <div class="flex gap-4 p-4 bg-slate-50 dark:bg-slate-700/30 rounded-xl border border-slate-100 dark:border-slate-700 hover:border-indigo-200 dark:hover:border-indigo-800 transition">
                        <div class="shrink-0 w-8 h-8 bg-indigo-600 text-white rounded-xl flex items-center justify-center font-bold text-xs">
                            {{ $step['nomor'] }}
                        </div>
                        <div>
                            <p class="font-semibold text-slate-800 dark:text-white text-sm">{{ $step['judul'] }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $step['detail'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Rules Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Ketentuan --}}
                <div>
                    <h4 class="font-semibold text-slate-700 dark:text-slate-300 text-sm mb-3 flex items-center gap-2">
                        <i class="fas fa-check-circle text-emerald-500"></i> Ketentuan Booking
                    </h4>
                    <ul class="space-y-2 text-sm text-slate-600 dark:text-slate-400">
                        <li class="flex items-start gap-2">
                            <i class="fas fa-circle text-[6px] text-emerald-500 mt-1.5"></i>
                            Booking <strong>harus</strong> dilakukan melalui sistem
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fas fa-circle text-[6px] text-emerald-500 mt-1.5"></i>
                            Tidak diperbolehkan menggunakan ruang tanpa booking resmi
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fas fa-circle text-[6px] text-emerald-500 mt-1.5"></i>
                            Semua booking berstatus <strong>Pending</strong> hingga disetujui admin
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fas fa-circle text-[6px] text-emerald-500 mt-1.5"></i>
                            Maksimal <strong>3 booking aktif</strong> per hari
                        </li>
                    </ul>
                </div>

                {{-- Larangan --}}
                <div>
                    <h4 class="font-semibold text-slate-700 dark:text-slate-300 text-sm mb-3 flex items-center gap-2">
                        <i class="fas fa-ban text-red-500"></i> Larangan Booking
                    </h4>
                    <ul class="space-y-2 text-sm text-red-600 dark:text-red-400">
                        <li class="flex items-start gap-2">
                            <i class="fas fa-circle text-[6px] text-red-500 mt-1.5"></i>
                            Booking untuk kepentingan fiktif / palsu
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fas fa-circle text-[6px] text-red-500 mt-1.5"></i>
                            Booking berulang tanpa penggunaan yang jelas (spam)
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fas fa-circle text-[6px] text-red-500 mt-1.5"></i>
                            Menggunakan akun orang lain
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fas fa-circle text-[6px] text-red-500 mt-1.5"></i>
                            Menggunakan ruang tanpa booking resmi
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div id="reputasi" class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden scroll-mt-24">
        <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700 bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-950/30 dark:to-orange-950/30">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900/50 flex items-center justify-center text-amber-600 dark:text-amber-400">
                    <i class="fas fa-star text-lg"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800 dark:text-white">Sistem Reputation Point</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Point mencerminkan tingkat keandalan dan kepatuhan pengguna</p>
                </div>
            </div>
        </div>

        <div class="p-6 space-y-6">
            {{-- Level Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($aturanReputation as $rule)
                <div class="p-4 rounded-xl bg-{{ $rule['warna'] }}-50 dark:bg-{{ $rule['warna'] }}-950/30 border border-{{ $rule['warna'] }}-200 dark:border-{{ $rule['warna'] }}-800 text-center">
                    <div class="text-3xl mb-2">{{ $rule['emoji'] }}</div>
                    <p class="text-xl font-extrabold text-{{ $rule['warna'] }}-600 dark:text-{{ $rule['warna'] }}-400">{{ $rule['range'] }}</p>
                    <p class="text-sm font-bold text-slate-800 dark:text-white">{{ $rule['label'] }}</p>
                    <p class="text-xs text-{{ $rule['warna'] }}-600 dark:text-{{ $rule['warna'] }}-400 mt-1">{{ $rule['detail'] }}</p>
                </div>
                @endforeach
            </div>

            {{-- Reward & Penalty --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-semibold text-emerald-700 dark:text-emerald-400 text-sm mb-3 flex items-center gap-2">
                        <i class="fas fa-plus-circle"></i> Penambahan Point
                    </h4>
                    <div class="space-y-2">
                        <div class="flex justify-between items-center px-4 py-3 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-800 rounded-xl">
                            <span class="text-sm text-slate-700 dark:text-slate-300">Menggunakan ruang sesuai jadwal</span>
                            <span class="font-bold text-emerald-600 dark:text-emerald-400">+10</span>
                        </div>
                        <div class="flex justify-between items-center px-4 py-3 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-800 rounded-xl">
                            <span class="text-sm text-slate-700 dark:text-slate-300">Check-in tepat waktu</span>
                            <span class="font-bold text-emerald-600 dark:text-emerald-400">+5</span>
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="font-semibold text-red-700 dark:text-red-400 text-sm mb-3 flex items-center gap-2">
                        <i class="fas fa-minus-circle"></i> Pengurangan Point
                    </h4>
                    <div class="space-y-2">
                        <div class="flex justify-between items-center px-4 py-3 bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-800 rounded-xl">
                            <span class="text-sm text-slate-700 dark:text-slate-300">No Show (tidak check-in)</span>
                            <span class="font-bold text-red-600 dark:text-red-400">-15</span>
                        </div>
                        <div class="flex justify-between items-center px-4 py-3 bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-800 rounded-xl">
                            <span class="text-sm text-slate-700 dark:text-slate-300">Membatalkan booking mendadak (&lt; 1 jam)</span>
                            <span class="font-bold text-red-600 dark:text-red-400">-10</span>
                        </div>
                        <div class="flex justify-between items-center px-4 py-3 bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-800 rounded-xl">
                            <span class="text-sm text-slate-700 dark:text-slate-300">Check-in terlambat</span>
                            <span class="font-bold text-red-600 dark:text-red-400">-5</span>
                        </div>
                        <div class="flex justify-between items-center px-4 py-3 bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-800 rounded-xl">
                            <span class="text-sm text-slate-700 dark:text-slate-300">Booking fiktif / penyalahgunaan</span>
                            <span class="font-bold text-red-600 dark:text-red-400">-20</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="sanksi" class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden scroll-mt-24">
        <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700 bg-gradient-to-r from-red-50 to-rose-50 dark:from-red-950/30 dark:to-rose-950/30">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-red-100 dark:bg-red-900/50 flex items-center justify-center text-red-600 dark:text-red-400">
                    <i class="fas fa-gavel text-lg"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800 dark:text-white">Aturan Sanksi & Pelanggaran</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Konsekuensi dari pelanggaran aturan sistem</p>
                </div>
            </div>
        </div>

        <div class="p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-semibold text-slate-700 dark:text-slate-300 text-sm mb-3 flex items-center gap-2">
                        <i class="fas fa-exclamation-triangle text-amber-500"></i> Jenis Pelanggaran
                    </h4>
                    <ul class="space-y-2 text-sm text-slate-600 dark:text-slate-400">
                        <li class="flex items-start gap-2">
                            <i class="fas fa-circle text-[6px] text-red-500 mt-1.5"></i>
                            Tidak melakukan check-in (No Show)
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fas fa-circle text-[6px] text-red-500 mt-1.5"></i>
                            Membatalkan booking mendadak (&lt; 1 jam sebelum)
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fas fa-circle text-[6px] text-red-500 mt-1.5"></i>
                            Menggunakan ruang tanpa izin / booking
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fas fa-circle text-[6px] text-red-500 mt-1.5"></i>
                            Booking palsu atau manipulatif
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fas fa-circle text-[6px] text-red-500 mt-1.5"></i>
                            Merusak fasilitas ruangan
                        </li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-semibold text-slate-700 dark:text-slate-300 text-sm mb-3 flex items-center gap-2">
                        <i class="fas fa-clock text-cyan-500"></i> Aturan Check-in
                    </h4>
                    <div class="p-4 bg-cyan-50 dark:bg-cyan-950/20 border border-cyan-200 dark:border-cyan-800 rounded-xl text-sm text-slate-700 dark:text-slate-300">
                        <p>Wajib check-in <strong>maksimal 15 menit</strong> setelah waktu booking dimulai.</p>
                        <p class="mt-1 text-red-600 dark:text-red-400">Jika tidak, booking otomatis dibatalkan dan point dikurangi.</p>
                    </div>

                    <h4 class="font-semibold text-slate-700 dark:text-slate-300 text-sm mt-4 mb-3 flex items-center gap-2">
                        <i class="fas fa-ban text-red-500"></i> Aturan Pembatalan
                    </h4>
                    <ul class="space-y-1.5 text-sm text-slate-600 dark:text-slate-400">
                        <li class="flex items-start gap-2">
                            <i class="fas fa-circle text-[6px] text-emerald-500 mt-1.5"></i>
                            <strong>≥ 1 jam sebelum</strong> → Tidak ada penalti
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fas fa-circle text-[6px] text-red-500 mt-1.5"></i>
                            <strong>&lt; 1 jam</strong> → Terkena pengurangan point (-10)
                        </li>
                    </ul>
                </div>
            </div>

            <div>
                <h4 class="font-semibold text-slate-700 dark:text-slate-300 text-sm mb-3 flex items-center gap-2">
                    <i class="fas fa-layer-group text-purple-500"></i> Sanksi Berdasarkan Level
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                    <div class="p-3 rounded-xl bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-800 text-center">
                        <p class="text-xs text-emerald-600 dark:text-emerald-400 font-bold">🌟 Trusted User</p>
                        <p class="text-sm font-bold text-slate-800 dark:text-white">80-100</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Prioritas approval</p>
                    </div>
                    <div class="p-3 rounded-xl bg-blue-50 dark:bg-blue-950/20 border border-blue-200 dark:border-blue-800 text-center">
                        <p class="text-xs text-blue-600 dark:text-blue-400 font-bold">⭐ Normal</p>
                        <p class="text-sm font-bold text-slate-800 dark:text-white">50-79</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Booking normal</p>
                    </div>
                    <div class="p-3 rounded-xl bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-800 text-center">
                        <p class="text-xs text-amber-600 dark:text-amber-400 font-bold">⚠️ Dibatasi</p>
                        <p class="text-sm font-bold text-slate-800 dark:text-white">30-49</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Review manual</p>
                    </div>
                    <div class="p-3 rounded-xl bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-800 text-center">
                        <p class="text-xs text-red-600 dark:text-red-400 font-bold">🚫 Diblokir</p>
                        <p class="text-sm font-bold text-slate-800 dark:text-white">&lt; 30</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Tidak bisa booking</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="faq" class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden scroll-mt-24">
        <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700 bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-indigo-950/30 dark:to-blue-950/30">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                    <i class="fas fa-question-circle text-lg"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800 dark:text-white">Pertanyaan yang Sering Diajukan</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ $faqs->count() }} pertanyaan</p>
                </div>
            </div>
        </div>

        <div class="p-4 space-y-2">
            @forelse($faqs as $faq)
            <div class="faq-item border border-slate-200 dark:border-slate-700 rounded-xl overflow-hidden hover:border-indigo-200 dark:hover:border-indigo-800 transition">
                <button class="faq-toggle w-full flex items-center justify-between px-5 py-4 text-left hover:bg-slate-50 dark:hover:bg-slate-700/50 transition group" type="button">
                    <span class="flex items-center gap-3 text-sm font-semibold text-slate-800 dark:text-white pr-4">
                        <span class="text-lg">{{ $faq->icon ?? '❓' }}</span>
                        {{ $faq->question }}
                    </span>
                    <span class="faq-icon shrink-0 w-8 h-8 rounded-xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-slate-500 dark:text-slate-400 font-bold text-sm group-hover:bg-indigo-100 dark:group-hover:bg-indigo-900/50 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition">+</span>
                </button>
                <div class="faq-answer hidden px-5 pb-4 pt-1">
                    <div class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed border-t border-slate-100 dark:border-slate-700 pt-3">
                        {!! nl2br(e($faq->answer)) !!}
                    </div>
                    @if($faq->category)
                    <span class="inline-block mt-2 text-xs px-2 py-0.5 rounded-full bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 font-medium">
                        {{ $faq->category }}
                    </span>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-slate-400 dark:text-slate-500">
                <div class="flex flex-col items-center">
                    <span class="text-4xl mb-3">📭</span>
                    <p class="font-semibold">Belum ada FAQ</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>

    <div id="kontak" class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden scroll-mt-24">
        <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700 bg-gradient-to-r from-emerald-50 to-teal-50 dark:from-emerald-950/30 dark:to-teal-950/30">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                    <i class="fas fa-headset text-lg"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800 dark:text-white">Butuh Bantuan Lebih Lanjut?</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Hubungi tim admin kami melalui kontak di bawah ini</p>
                </div>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                {{-- Email --}}
                <a href="mailto:{{ $adminEmail ?? 'admin@smartclassroom.com' }}"
                   target="_blank"
                   class="group p-4 rounded-xl bg-slate-50 dark:bg-slate-700/30 border border-slate-200 dark:border-slate-700 hover:border-indigo-200 dark:hover:border-indigo-800 hover:bg-indigo-50 dark:hover:bg-indigo-950/20 transition text-center">
                    <div class="text-3xl mb-2 group-hover:scale-110 transition-transform">📧</div>
                    <p class="text-sm font-bold text-slate-800 dark:text-white">Email Admin</p>
                    <p class="text-xs text-indigo-600 dark:text-indigo-400 mt-1 font-medium break-all">{{ $adminEmail ?? 'admin@smartclassroom.com' }}</p>
                </a>

                {{-- Location --}}
                <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($campusAddress ?? '') }}"
                   target="_blank"
                   class="group p-4 rounded-xl bg-slate-50 dark:bg-slate-700/30 border border-slate-200 dark:border-slate-700 hover:border-indigo-200 dark:hover:border-indigo-800 hover:bg-indigo-50 dark:hover:bg-indigo-950/20 transition text-center">
                    <div class="text-3xl mb-2 group-hover:scale-110 transition-transform">📍</div>
                    <p class="text-sm font-bold text-slate-800 dark:text-white">Datang Langsung</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ $adminRoomLocation ?? 'Ruangan Tata Usaha' }}</p>
                    <p class="text-xs text-slate-600 dark:text-slate-300 mt-0.5">{{ $campusAddress ?? 'Kampus' }}</p>
                </a>

                {{-- WhatsApp --}}
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $adminPhone ?? '') }}"
                   target="_blank"
                   class="group p-4 rounded-xl bg-slate-50 dark:bg-slate-700/30 border border-slate-200 dark:border-slate-700 hover:border-emerald-200 dark:hover:border-emerald-800 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 transition text-center">
                    <div class="text-3xl mb-2 group-hover:scale-110 transition-transform">💬</div>
                    <p class="text-sm font-bold text-slate-800 dark:text-white">WhatsApp</p>
                    <p class="text-xs text-emerald-600 dark:text-emerald-400 mt-1 font-medium">{{ $adminPhone ?? '+62 812-3456-7890' }}</p>
                </a>
            </div>
        </div>
    </div>

    <button id="backToTop"
            class="fixed bottom-8 right-8 w-12 h-12 bg-indigo-600 hover:bg-indigo-700 text-white rounded-full shadow-lg shadow-indigo-200 dark:shadow-indigo-900/30 opacity-0 invisible z-30 flex items-center justify-center transition-all duration-300 group"
            type="button">
        <svg class="w-5 h-5 group-hover:-translate-y-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
        </svg>
    </button>

</div>

@push('scripts')
<script>
$(document).ready(function() {
    'use strict';

    // ================================================================
    // FAQ - TOGGLE
    // ================================================================
    $('.faq-toggle').on('click', function(e) {
        e.preventDefault();

        const $item = $(this).closest('.faq-item');
        const $answer = $item.find('.faq-answer');
        const $icon = $(this).find('.faq-icon');

        // Close all other FAQs
        $('.faq-item').not($item).each(function() {
            $(this).find('.faq-answer').slideUp(200);
            $(this).find('.faq-icon').text('+').removeClass('bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400');
        });

        // Toggle current FAQ
        if ($answer.is(':visible')) {
            $answer.slideUp(200);
            $icon.text('+').removeClass('bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400');
        } else {
            $answer.slideDown(200);
            $icon.text('−').addClass('bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400');
        }
    });

    // ================================================================
    // BACK TO TOP
    // ================================================================
    const $backToTop = $('#backToTop');

    $(window).on('scroll', function() {
        if ($(window).scrollTop() > 300) {
            $backToTop.removeClass('opacity-0 invisible').addClass('opacity-100 visible');
        } else {
            $backToTop.removeClass('opacity-100 visible').addClass('opacity-0 invisible');
        }
    });

    $backToTop.on('click', function(e) {
        e.preventDefault();
        $('html, body').animate({ scrollTop: 0 }, 500, 'swing');
    });

    // ================================================================
    // URL HASH NAVIGATION
    // ================================================================
    if (window.location.hash) {
        const targetId = window.location.hash;
        const $target = $(targetId);
        if ($target.length) {
            setTimeout(function() {
                const offset = 100;
                const targetPosition = $target.offset().top - offset;
                $('html, body').animate({ scrollTop: targetPosition }, 400);
            }, 100);
        }
    }

    // ================================================================
    // AUTO OPEN FAQ
    // ================================================================
    if (window.location.hash === '#faq') {
        setTimeout(function() {
            $('.faq-toggle:first').click();
        }, 300);
    }

    // ================================================================
    // SMOOTH SCROLL FOR NAV LINKS
    // ================================================================
    $('a[href^="#"]').on('click', function(e) {
        const href = $(this).attr('href');
        if (href !== '#') {
            e.preventDefault();
            const $target = $(href);
            if ($target.length) {
                const offset = 100;
                const targetPosition = $target.offset().top - offset;
                $('html, body').animate({ scrollTop: targetPosition }, 500, 'swing');
                window.history.pushState(null, null, href);
            }
        }
    });

});
</script>
@endpush

@endsection
