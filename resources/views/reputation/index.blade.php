@extends('layouts.app')

@section('title', 'Reputation Point - Smart Classroom')
@section('page_title', 'Reputation Point')
@section('page_subtitle', 'Riwayat penilaian perilaku penggunaan ruang')

@section('content')

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    {{-- ===== KIRI: Profile Reputasi ===== --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <h3 class="font-bold text-lg mb-1">Point Saya</h3>
        <p class="text-sm text-slate-500 mb-6">Status kepercayaan pengguna</p>

        @php
            $rp = $user->reputation_points ?? 100;

            // Tentukan warna berdasarkan range
            if ($rp >= 80) {
                $rpColor = '#10b981'; // hijau
                $rpLabel = 'Trusted User';
                $rpBg = 'bg-emerald-100 text-emerald-700';
            } elseif ($rp >= 50) {
                $rpColor = '#3b82f6'; // biru
                $rpLabel = 'Normal';
                $rpBg = 'bg-blue-100 text-blue-700';
            } elseif ($rp >= 30) {
                $rpColor = '#f59e0b'; // kuning
                $rpLabel = 'Dibatasi';
                $rpBg = 'bg-amber-100 text-amber-700';
            } else {
                $rpColor = '#ef4444'; // merah
                $rpLabel = 'Diblokir';
                $rpBg = 'bg-red-100 text-red-700';
            }
        @endphp

        {{-- Circle Reputasi --}}
        <div class="flex items-center justify-center">
            <div class="w-44 h-44 rounded-full flex items-center justify-center"
                 style="border: 8px solid {{ $rpColor }}">
                <div class="text-center">
                    <p class="text-5xl font-extrabold" style="color: {{ $rpColor }}">{{ $rp }}</p>
                    <p class="text-xs text-slate-500">Point</p>
                </div>
            </div>
        </div>

        {{-- Label Level --}}
        <div class="mt-6 text-center">
            <span class="px-4 py-2 rounded-full text-sm font-bold {{ $rpBg }}">
                {{ $level->name ?? $rpLabel }}
            </span>
        </div>

        {{-- Info Tambahan --}}
        <div class="mt-6 space-y-3 text-sm">
            <div class="flex justify-between">
                <span class="text-slate-500">Hak Booking</span>
                <span class="font-bold {{ $rp >= 30 ? 'text-emerald-600' : 'text-red-600' }}">
                    {{ $rp >= 30 ? 'Aktif' : 'Dibatasi' }}
                </span>
            </div>

            <div class="flex justify-between">
                <span class="text-slate-500">Level Saat Ini</span>
                <span class="font-bold">{{ $level->name ?? 'Belum ada level' }}</span>
            </div>

            @if($nextLevel)
            <div class="flex justify-between">
                <span class="text-slate-500">Level Selanjutnya</span>
                <span class="font-bold text-blue-600">
                    {{ $nextLevel->name }} ({{ $nextLevel->min_points }} poin)
                </span>
            </div>
            @endif

            <div class="flex justify-between">
                <span class="text-slate-500">Total Log</span>
                <span class="font-bold">{{ $logs->total() }}</span>
            </div>
        </div>

        {{-- Progress Bar --}}
        @if($level && $nextLevel)
        @php
            $current = $rp - $level->min_points;
            $max = $nextLevel->min_points - $level->min_points;
            $progress = $max > 0 ? min(($current / $max) * 100, 100) : 0;
        @endphp
        <div class="mt-4">
            <div class="flex justify-between text-xs text-slate-500">
                <span>{{ $level->name }}</span>
                <span>{{ $nextLevel->name }}</span>
            </div>
            <div class="mt-1 h-2 bg-slate-100 rounded-full overflow-hidden">
                <div class="h-full bg-blue-500 rounded-full transition-all duration-700"
                     style="width: {{ $progress }}%"></div>
            </div>
            <p class="text-xs text-slate-400 mt-1 text-center">
                {{ round($progress) }}% menuju {{ $nextLevel->name }}
            </p>
        </div>
        @endif
    </div>

    {{-- ===== KANAN: Riwayat Log ===== --}}
    <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

        <div class="p-6 border-b border-slate-200">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-lg">Riwayat Point</h3>
                    <p class="text-sm text-slate-500">Catatan penambahan dan pengurangan point</p>
                </div>
                <span class="text-xs bg-slate-100 text-slate-500 px-3 py-1 rounded-full">
                    Total {{ $logs->total() }} log
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            @if($logs->count() > 0)
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-slate-500">
                    <tr>
                        <th class="text-left px-6 py-4">Tanggal</th>
                        <th class="text-left px-6 py-4">Aktivitas</th>
                        <th class="text-left px-6 py-4">Tipe</th>
                        <th class="text-right px-6 py-4">Point</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @foreach($logs as $log)
                    <tr>
                        <td class="px-6 py-4 text-slate-600">
                            {{ $log->created_at->translatedFormat('d M Y H:i') }}
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-medium text-slate-800">{{ $log->reason ?? $log->description ?? '-' }}</p>
                            @if($log->booking)
                            <p class="text-xs text-slate-400">Booking: {{ $log->booking->booking_code ?? '-' }}</p>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-bold
                                {{ $log->type == 'reward' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                {{ $log->type == 'reward' ? '✅ Reward' : '❌ Penalty' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right font-bold
                            {{ $log->point_change > 0 ? 'text-emerald-600' : 'text-red-600' }}">
                            {{ $log->point_change > 0 ? '+' : '' }}{{ $log->point_change }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="text-center py-12 text-slate-400">
                <p class="text-4xl mb-3">📭</p>
                <p class="font-semibold">Belum ada riwayat reputasi</p>
                <p class="text-xs mt-1">Point reputasi akan tercatat di sini</p>
            </div>
            @endif
        </div>

        {{-- Pagination --}}
        @if($logs->hasPages())
        <div class="p-4 border-t border-slate-200">
            {{ $logs->links() }}
        </div>
        @endif

    </div>

</div>

{{-- ===== ATURAN REPUTASI ===== --}}
<div class="mt-6 bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
    <h3 class="font-bold text-lg mb-4">📋 Aturan Reputation</h3>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-100">
            <p class="font-bold text-emerald-700">80 - 100</p>
            <p class="text-emerald-600 mt-1 font-medium">Trusted User</p>
            <p class="text-slate-600 text-xs mt-1">Proses approval lebih mudah.</p>
        </div>

        <div class="p-4 rounded-xl bg-blue-50 border border-blue-100">
            <p class="font-bold text-blue-700">50 - 79</p>
            <p class="text-blue-600 mt-1 font-medium">Normal</p>
            <p class="text-slate-600 text-xs mt-1">Dapat booking seperti biasa.</p>
        </div>

        <div class="p-4 rounded-xl bg-amber-50 border border-amber-100">
            <p class="font-bold text-amber-700">30 - 49</p>
            <p class="text-amber-600 mt-1 font-medium">Dibatasi</p>
            <p class="text-slate-600 text-xs mt-1">Booking dibatasi sementara.</p>
        </div>

        <div class="p-4 rounded-xl bg-red-50 border border-red-200">
            <p class="font-bold text-red-700">&lt; 30</p>
            <p class="text-red-600 mt-1 font-medium">Diblokir</p>
            <p class="text-slate-600 text-xs mt-1">Tidak bisa booking sementara.</p>
        </div>
    </div>

    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-100">
            <p class="font-bold text-emerald-700">➕ Penambahan Point</p>
            <ul class="mt-2 space-y-1 text-slate-600">
                <li>• Check-in tepat waktu: <span class="font-bold text-emerald-600">+5</span></li>
                <li>• Menggunakan ruang sesuai jadwal: <span class="font-bold text-emerald-600">+10</span></li>
                <li>• Menjaga kondisi ruang: <span class="font-bold text-emerald-600">+2</span></li>
            </ul>
        </div>
        <div class="p-4 rounded-xl bg-red-50 border border-red-100">
            <p class="font-bold text-red-700">➖ Pengurangan Point</p>
            <ul class="mt-2 space-y-1 text-slate-600">
                <li>• No Show: <span class="font-bold text-red-600">-15</span></li>
                <li>• Pembatalan mendadak: <span class="font-bold text-red-600">-10</span></li>
                <li>• Check-in terlambat: <span class="font-bold text-red-600">-5</span></li>
            </ul>
        </div>
    </div>
</div>

@endsection
