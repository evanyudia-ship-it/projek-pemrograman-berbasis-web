@extends('layouts.app')

@section('title', 'Reputation Point - Smart Classroom')
@section('page_title', 'Reputation Point')
@section('page_subtitle', 'Riwayat penilaian perilaku penggunaan ruang')

@section('content')

<div class="max-w-7xl mx-auto font-sora space-y-6">

    {{-- ============================================================ --}}
    {{-- MAIN GRID: KIRI (Profil Reputasi) + KANAN (Riwayat Log) --}}
    {{-- ============================================================ --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- ===== KIRI: Profil Reputasi ===== --}}
        <div class="xl:col-span-1">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 sticky top-24">

                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                        <i class="fas fa-star text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 dark:text-white">Point Saya</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Status kepercayaan pengguna</p>
                    </div>
                </div>

                @php
                    $rp = $user->reputation_points ?? 100;

                    if ($rp >= 80) {
                        $rpColor = '#10b981';
                        $rpLabel = 'Trusted User';
                        $rpBg = 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300';
                        $rpBorder = 'border-emerald-500';
                        $rpGradient = 'from-emerald-400 to-teal-400';
                    } elseif ($rp >= 50) {
                        $rpColor = '#3b82f6';
                        $rpLabel = 'Normal';
                        $rpBg = 'bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300';
                        $rpBorder = 'border-blue-500';
                        $rpGradient = 'from-blue-400 to-indigo-400';
                    } elseif ($rp >= 30) {
                        $rpColor = '#f59e0b';
                        $rpLabel = 'Dibatasi';
                        $rpBg = 'bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-300';
                        $rpBorder = 'border-amber-500';
                        $rpGradient = 'from-amber-400 to-orange-400';
                    } else {
                        $rpColor = '#ef4444';
                        $rpLabel = 'Diblokir';
                        $rpBg = 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300';
                        $rpBorder = 'border-red-500';
                        $rpGradient = 'from-red-400 to-rose-400';
                    }
                @endphp

                {{-- Circle Reputasi --}}
                <div class="flex items-center justify-center py-4">
                    <div class="relative">
                        <div class="w-48 h-48 rounded-full flex items-center justify-center relative
                                    border-8 {{ $rpBorder }} shadow-lg"
                             style="border-color: {{ $rpColor }}">

                            <div class="text-center">
                                <p class="text-5xl font-extrabold" style="color: {{ $rpColor }}">
                                    {{ $rp }}
                                </p>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">dari 100</p>
                            </div>

                            @if($rp >= 100)
                                <span class="absolute -top-2 -right-2 text-3xl animate-bounce">⭐</span>
                            @endif

                            @if($rp <= 0)
                                <span class="absolute -top-2 -right-2 text-3xl">🚫</span>
                            @endif
                        </div>

                        {{-- Progress Ring (SVG) --}}
                        <svg class="absolute inset-0 w-48 h-48 -rotate-90">
                            <circle cx="96" cy="96" r="88"
                                    fill="none"
                                    stroke="#e2e8f0"
                                    stroke-width="8"
                                    class="dark:stroke-slate-700"/>
                            <circle cx="96" cy="96" r="88"
                                    fill="none"
                                    stroke="{{ $rpColor }}"
                                    stroke-width="8"
                                    stroke-dasharray="553"
                                    stroke-dashoffset="{{ 553 - (553 * ($rp / 100)) }}"
                                    stroke-linecap="round"
                                    class="transition-all duration-1000 ease-out"/>
                        </svg>
                    </div>
                </div>

                {{-- Label Level --}}
                <div class="text-center mt-2">
                    <span class="px-5 py-2 rounded-full text-sm font-bold {{ $rpBg }} inline-flex items-center gap-2">
                        @if($rp >= 80)
                            <i class="fas fa-crown text-amber-400"></i>
                        @elseif($rp >= 50)
                            <i class="fas fa-user-check text-emerald-400"></i>
                        @elseif($rp >= 30)
                            <i class="fas fa-exclamation-triangle text-amber-400"></i>
                        @else
                            <i class="fas fa-ban text-red-400"></i>
                        @endif
                        {{ $level->name ?? $rpLabel }}
                    </span>
                </div>

                {{-- Info Maksimal / Minimal --}}
                @if($rp >= 100)
                <div class="mt-4 text-center">
                    <span class="px-4 py-2 rounded-full text-sm font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300 inline-flex items-center gap-2">
                        <i class="fas fa-trophy"></i> Reputasi Maksimal!
                    </span>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1.5">
                        Anda telah mencapai reputasi tertinggi. Pertahankan!
                    </p>
                </div>
                @endif

                @if($rp <= 0)
                <div class="mt-4 text-center">
                    <span class="px-4 py-2 rounded-full text-sm font-bold bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300 inline-flex items-center gap-2">
                        <i class="fas fa-exclamation-circle"></i> Reputasi Minimal!
                    </span>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1.5">
                        Hubungi admin untuk pemulihan akun.
                    </p>
                </div>
                @endif

                {{-- Info Tambahan --}}
                <div class="mt-6 space-y-3 text-sm bg-slate-50 dark:bg-slate-700/50 p-4 rounded-xl">
                    <div class="flex justify-between">
                        <span class="text-slate-500 dark:text-slate-400">Hak Booking</span>
                        <span class="font-bold {{ $rp >= 30 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                            {{ $rp >= 30 ? '✅ Aktif' : '🚫 Dibatasi' }}
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-slate-500 dark:text-slate-400">Level Saat Ini</span>
                        <span class="font-bold text-slate-800 dark:text-white">{{ $level->name ?? 'Belum ada level' }}</span>
                    </div>

                    @if($nextLevel)
                    <div class="flex justify-between">
                        <span class="text-slate-500 dark:text-slate-400">Level Selanjutnya</span>
                        <span class="font-bold text-indigo-600 dark:text-indigo-400">
                            {{ $nextLevel->name }} <span class="text-xs font-normal text-slate-400 dark:text-slate-500">({{ $nextLevel->min_points }} poin)</span>
                        </span>
                    </div>
                    @endif

                    <div class="flex justify-between pt-2 border-t border-slate-200 dark:border-slate-600">
                        <span class="text-slate-500 dark:text-slate-400">Total Log</span>
                        <span class="font-bold text-slate-800 dark:text-white">{{ $logs->total() }}</span>
                    </div>
                </div>

                {{-- Progress Bar Level --}}
                @if($level && $nextLevel)
                @php
                    $current = $rp - $level->min_points;
                    $max = $nextLevel->min_points - $level->min_points;
                    $progress = $max > 0 ? min(($current / $max) * 100, 100) : 0;
                @endphp
                <div class="mt-4">
                    <div class="flex justify-between text-xs text-slate-500 dark:text-slate-400">
                        <span>{{ $level->name }}</span>
                        <span>{{ $nextLevel->name }}</span>
                    </div>
                    <div class="mt-1.5 h-2.5 bg-slate-200 dark:bg-slate-600 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r {{ $rpGradient }} rounded-full transition-all duration-1000"
                             style="width: {{ $progress }}%"></div>
                    </div>
                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-1.5 text-center">
                        {{ round($progress) }}% menuju {{ $nextLevel->name }}
                    </p>
                </div>
                @endif

                {{-- Tombol Aksi --}}
                <div class="mt-6 space-y-2">
                    <a href="{{ route('help.index') }}#reputasi"
                       class="block w-full text-center px-4 py-2.5 rounded-xl bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 text-sm font-semibold transition">
                        <i class="fas fa-info-circle mr-1.5"></i> Pelajari Sistem Reputasi
                    </a>
                </div>

            </div>
        </div>

        {{-- ===== KANAN: Riwayat Log ===== --}}
        <div class="xl:col-span-2 bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">

            {{-- Header --}}
            <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700 bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-indigo-950/30 dark:to-blue-950/30">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                            <i class="fas fa-history text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 dark:text-white">Riwayat Point</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Catatan penambahan dan pengurangan point</p>
                        </div>
                    </div>
                    <span class="text-xs bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 px-3 py-1.5 rounded-full font-medium inline-flex items-center gap-1.5">
                        <i class="fas fa-list"></i> {{ $logs->total() }} log
                    </span>
                </div>
            </div>

            {{-- Tabel --}}
            <div class="overflow-x-auto">
                @if($logs->count() > 0)
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 dark:bg-slate-700/50 text-slate-500 dark:text-slate-400 text-xs uppercase tracking-wide">
                        <tr>
                            <th class="text-left px-6 py-4 font-semibold">Tanggal</th>
                            <th class="text-left px-6 py-4 font-semibold">Aktivitas</th>
                            <th class="text-left px-6 py-4 font-semibold">Tipe</th>
                            <th class="text-right px-6 py-4 font-semibold">Point</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @foreach($logs as $log)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition">
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-400 text-xs whitespace-nowrap">
                                {{ $log->created_at->translatedFormat('d M Y H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-medium text-slate-800 dark:text-white">{{ $log->reason ?? $log->description ?? '-' }}</p>
                                @if($log->booking)
                                <p class="text-xs text-slate-400 dark:text-slate-500 flex items-center gap-1 mt-0.5">
                                    <i class="fas fa-receipt"></i> Booking: <span class="font-mono">{{ $log->booking->booking_code ?? '-' }}</span>
                                </p>
                                @endif
                                @if($log->created_by)
                                <p class="text-xs text-slate-400 dark:text-slate-500 flex items-center gap-1 mt-0.5">
                                    <i class="fas fa-user-cog"></i> Admin: {{ $log->creator->name ?? '-' }}
                                </p>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1.5 rounded-full text-xs font-bold inline-flex items-center gap-1.5
                                    {{ $log->type == 'reward' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300' : 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300' }}">
                                    <i class="fas {{ $log->type == 'reward' ? 'fa-arrow-up' : 'fa-arrow-down' }} text-[10px]"></i>
                                    {{ $log->type == 'reward' ? 'Reward' : 'Penalty' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right font-bold
                                {{ $log->point_change > 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ $log->point_change > 0 ? '+' : '' }}{{ $log->point_change }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="text-center py-16 text-slate-400 dark:text-slate-500">
                    <div class="flex flex-col items-center">
                        <div class="w-20 h-20 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-4xl mb-4">
                            <i class="fas fa-inbox text-slate-400 dark:text-slate-500"></i>
                        </div>
                        <p class="font-semibold text-slate-600 dark:text-slate-400">Belum ada riwayat reputasi</p>
                        <p class="text-sm text-slate-400 dark:text-slate-500 mt-1">Point reputasi akan tercatat di sini</p>
                    </div>
                </div>
                @endif
            </div>

            {{-- Pagination --}}
            @if($logs->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700">
                {{ $logs->links() }}
            </div>
            @endif

        </div>

    </div>

    {{-- ============================================================ --}}
    {{-- ATURAN REPUTASI --}}
    {{-- ============================================================ --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">

        <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700 bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-950/30 dark:to-orange-950/30">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900/50 flex items-center justify-center text-amber-600 dark:text-amber-400">
                    <i class="fas fa-gavel text-lg"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800 dark:text-white">📋 Aturan Reputation</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Ketentuan level dan perolehan point reputasi</p>
                </div>
            </div>
        </div>

        <div class="p-6">

            {{-- Level Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-100 dark:border-emerald-800">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-2xl">⭐</span>
                        <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-200 dark:bg-emerald-800 px-2 py-0.5 rounded-full">Tertinggi</span>
                    </div>
                    <p class="font-bold text-emerald-700 dark:text-emerald-300 text-lg">80 - 100</p>
                    <p class="text-emerald-600 dark:text-emerald-400 font-medium">Trusted User</p>
                    <p class="text-slate-600 dark:text-slate-400 text-xs mt-1">Proses approval lebih cepat, booking prioritas</p>
                </div>

                <div class="p-4 rounded-xl bg-blue-50 dark:bg-blue-950/30 border border-blue-100 dark:border-blue-800">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-2xl">👤</span>
                        <span class="text-xs font-bold text-blue-600 dark:text-blue-400 bg-blue-200 dark:bg-blue-800 px-2 py-0.5 rounded-full">Normal</span>
                    </div>
                    <p class="font-bold text-blue-700 dark:text-blue-300 text-lg">50 - 79</p>
                    <p class="text-blue-600 dark:text-blue-400 font-medium">Normal</p>
                    <p class="text-slate-600 dark:text-slate-400 text-xs mt-1">Dapat booking seperti biasa</p>
                </div>

                <div class="p-4 rounded-xl bg-amber-50 dark:bg-amber-950/30 border border-amber-100 dark:border-amber-800">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-2xl">⚠️</span>
                        <span class="text-xs font-bold text-amber-600 dark:text-amber-400 bg-amber-200 dark:bg-amber-800 px-2 py-0.5 rounded-full">Dibatasi</span>
                    </div>
                    <p class="font-bold text-amber-700 dark:text-amber-300 text-lg">30 - 49</p>
                    <p class="text-amber-600 dark:text-amber-400 font-medium">Dibatasi</p>
                    <p class="text-slate-600 dark:text-slate-400 text-xs mt-1">Booking dibatasi sementara</p>
                </div>

                <div class="p-4 rounded-xl bg-red-50 dark:bg-red-950/30 border border-red-100 dark:border-red-800">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-2xl">🚫</span>
                        <span class="text-xs font-bold text-red-600 dark:text-red-400 bg-red-200 dark:bg-red-800 px-2 py-0.5 rounded-full">Diblokir</span>
                    </div>
                    <p class="font-bold text-red-700 dark:text-red-300 text-lg">&lt; 30</p>
                    <p class="text-red-600 dark:text-red-400 font-medium">Diblokir</p>
                    <p class="text-slate-600 dark:text-slate-400 text-xs mt-1">Tidak bisa booking sementara</p>
                </div>
            </div>

            {{-- Reward & Penalty --}}
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-5 rounded-xl bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-100 dark:border-emerald-800">
                    <p class="font-bold text-emerald-700 dark:text-emerald-300 flex items-center gap-2">
                        <i class="fas fa-plus-circle"></i> Penambahan Point
                    </p>
                    <ul class="mt-3 space-y-2 text-sm text-slate-600 dark:text-slate-400">
                        <li class="flex items-center justify-between p-2 bg-white dark:bg-slate-700/50 rounded-lg">
                            <span>Check-in tepat waktu</span>
                            <span class="font-bold text-emerald-600 dark:text-emerald-400">+5</span>
                        </li>
                        <li class="flex items-center justify-between p-2 bg-white dark:bg-slate-700/50 rounded-lg">
                            <span>Menggunakan ruang sesuai jadwal</span>
                            <span class="font-bold text-emerald-600 dark:text-emerald-400">+10</span>
                        </li>
                        <li class="flex items-center justify-between p-2 bg-white dark:bg-slate-700/50 rounded-lg">
                            <span>Menjaga kondisi ruang</span>
                            <span class="font-bold text-emerald-600 dark:text-emerald-400">+2</span>
                        </li>
                        <li class="flex items-center justify-between p-2 bg-white dark:bg-slate-700/50 rounded-lg">
                            <span>Booking disetujui</span>
                            <span class="font-bold text-emerald-600 dark:text-emerald-400">+5</span>
                        </li>
                    </ul>
                </div>

                <div class="p-5 rounded-xl bg-red-50 dark:bg-red-950/30 border border-red-100 dark:border-red-800">
                    <p class="font-bold text-red-700 dark:text-red-300 flex items-center gap-2">
                        <i class="fas fa-minus-circle"></i> Pengurangan Point
                    </p>
                    <ul class="mt-3 space-y-2 text-sm text-slate-600 dark:text-slate-400">
                        <li class="flex items-center justify-between p-2 bg-white dark:bg-slate-700/50 rounded-lg">
                            <span>No Show (tidak check-in)</span>
                            <span class="font-bold text-red-600 dark:text-red-400">-15</span>
                        </li>
                        <li class="flex items-center justify-between p-2 bg-white dark:bg-slate-700/50 rounded-lg">
                            <span>Pembatalan mendadak (&lt; 1 jam)</span>
                            <span class="font-bold text-red-600 dark:text-red-400">-10</span>
                        </li>
                        <li class="flex items-center justify-between p-2 bg-white dark:bg-slate-700/50 rounded-lg">
                            <span>Check-in terlambat</span>
                            <span class="font-bold text-red-600 dark:text-red-400">-5</span>
                        </li>
                        <li class="flex items-center justify-between p-2 bg-white dark:bg-slate-700/50 rounded-lg">
                            <span>Booking fiktif / penyalahgunaan</span>
                            <span class="font-bold text-red-600 dark:text-red-400">-20</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="mt-4 text-center">
                <a href="{{ route('help.index') }}#reputasi"
                   class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 font-medium inline-flex items-center gap-1.5 transition">
                    <i class="fas fa-book-open"></i> Lihat penjelasan lengkap di Pusat Bantuan
                </a>
            </div>
        </div>

    </div>

</div>

@endsection
