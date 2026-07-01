@extends('layouts.app')

@section('title', 'Approval Booking')
@section('page_title', 'Approval Booking')
@section('page_subtitle', 'Validasi pengajuan booking ruang oleh admin atau sekretaris jurusan')

@section('content')

<div class="max-w-7xl mx-auto font-sora space-y-6">

    {{-- ============================================================ --}}
    {{-- FLASH MESSAGES --}}
    {{-- ============================================================ --}}
    @if(session('success'))
    <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 font-semibold text-sm flex items-center gap-3">
        <span class="text-lg">✅</span>
        {{ session('success') }}
        <button onclick="this.parentElement.remove()" class="ml-auto text-emerald-500 hover:text-emerald-700 dark:text-emerald-400 dark:hover:text-emerald-200">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    @if(session('error'))
    <div class="p-4 rounded-xl bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 font-semibold text-sm flex items-center gap-3">
        <span class="text-lg">❌</span>
        {{ session('error') }}
        <button onclick="this.parentElement.remove()" class="ml-auto text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-200">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    {{-- ============================================================ --}}
    {{-- STAT CARDS --}}
    {{-- ============================================================ --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Menunggu Approval</p>
                    <p class="text-2xl font-extrabold text-amber-600 dark:text-amber-400 mt-1">{{ $stats['pending'] }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900/50 flex items-center justify-center text-amber-600 dark:text-amber-400">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
            @if($stats['pending'] > 0)
            <div class="mt-2">
                <span class="text-xs font-medium text-amber-600 dark:text-amber-400 animate-pulse inline-flex items-center gap-1">
                    <i class="fas fa-circle text-[6px]"></i> {{ $stats['pending'] }} perlu diproses
                </span>
            </div>
            @endif
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Disetujui</p>
                    <p class="text-2xl font-extrabold text-emerald-600 dark:text-emerald-400 mt-1">{{ $stats['approved'] }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Ditolak</p>
                    <p class="text-2xl font-extrabold text-red-600 dark:text-red-400 mt-1">{{ $stats['rejected'] }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-red-100 dark:bg-red-900/50 flex items-center justify-center text-red-600 dark:text-red-400">
                    <i class="fas fa-times-circle"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">No Show</p>
                    <p class="text-2xl font-extrabold text-slate-400 dark:text-slate-500 mt-1">{{ $stats['expired'] }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-slate-400 dark:text-slate-500">
                    <i class="fas fa-hourglass-end"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- PENDING BOOKINGS --}}
    {{-- ============================================================ --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">

        <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700 bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-950/30 dark:to-orange-950/30">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900/50 flex items-center justify-center text-amber-600 dark:text-amber-400">
                        <i class="fas fa-hourglass-half text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 dark:text-white">Daftar Pengajuan Pending</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Booking harus diproses maksimal 1 × 24 jam</p>
                    </div>
                </div>
                @if($stats['pending'] > 0)
                <span class="px-4 py-1.5 rounded-full bg-amber-500 text-white text-xs font-bold animate-pulse inline-flex items-center gap-1.5 shadow-sm">
                    <i class="fas fa-circle text-[6px]"></i> {{ $stats['pending'] }} Menunggu
                </span>
                @endif
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 dark:bg-slate-700/50 text-slate-500 dark:text-slate-400 text-xs uppercase tracking-wide">
                    <tr>
                        <th class="text-left px-6 py-4 font-semibold">Kode</th>
                        <th class="text-left px-6 py-4 font-semibold">Pemohon</th>
                        <th class="text-left px-6 py-4 font-semibold">Ruang</th>
                        <th class="text-left px-6 py-4 font-semibold">Kegiatan</th>
                        <th class="text-left px-6 py-4 font-semibold">Waktu</th>
                        <th class="text-left px-6 py-4 font-semibold">Prioritas</th>
                        <th class="text-center px-6 py-4 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($pending as $bk)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition group">
                        <td class="px-6 py-4">
                            <span class="font-bold text-slate-700 dark:text-slate-300 font-mono">{{ $bk['id'] }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-semibold text-slate-800 dark:text-white">{{ $bk['pemohon'] }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">
                                <span class="px-2 py-0.5 rounded-full bg-slate-100 dark:bg-slate-600 text-[10px]">
                                    {{ $bk['tipe'] }}
                                </span>
                            </p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-medium text-slate-700 dark:text-slate-300">{{ $bk['ruang'] }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-slate-700 dark:text-slate-300">{{ $bk['kegiatan'] }}</p>
                            <p class="text-xs text-slate-400 dark:text-slate-500 line-clamp-1">{{ $bk['tujuan'] ?? '-' }}</p>
                        </td>
                        <td class="px-6 py-4 text-slate-600 dark:text-slate-400">
                            <p class="font-medium">{{ $bk['waktu'] }}</p>
                            <p class="text-xs text-slate-400 dark:text-slate-500">{{ $bk['jam_mulai'] ?? '' }} - {{ $bk['jam_selesai'] ?? '' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $priorityColor = App\Helpers\PriorityHelper::getPriorityColor($bk['prioritas'] ?? 'Medium');
                                $priorityLabel = App\Helpers\PriorityHelper::getPriorityLabel($bk['prioritas'] ?? 'Medium');
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $priorityColor }}">
                                {{ $priorityLabel }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center gap-1.5 flex-wrap">
                                <button class="btn-approve px-3 py-1.5 rounded-lg bg-emerald-100 dark:bg-emerald-900/50 hover:bg-emerald-200 dark:hover:bg-emerald-800 text-emerald-700 dark:text-emerald-300 font-semibold text-xs transition flex items-center gap-1"
                                        data-id="{{ $bk['id'] }}"
                                        data-action="{{ route('admin.approvals.approve', $bk['id']) }}">
                                    <i class="fas fa-check"></i> Approve
                                </button>

                                <button class="btn-reject px-3 py-1.5 rounded-lg bg-red-100 dark:bg-red-900/50 hover:bg-red-200 dark:hover:bg-red-800 text-red-700 dark:text-red-300 font-semibold text-xs transition flex items-center gap-1"
                                        data-id="{{ $bk['id'] }}"
                                        data-action="{{ route('admin.approvals.reject', $bk['id']) }}">
                                    <i class="fas fa-times"></i> Reject
                                </button>

                                <button class="btn-detail px-3 py-1.5 rounded-lg bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-600 dark:text-slate-300 font-semibold text-xs transition flex items-center gap-1"
                                        data-id="{{ $bk['id'] }}"
                                        data-pemohon="{{ $bk['pemohon'] }}"
                                        data-tipe="{{ $bk['tipe'] }}"
                                        data-ruang="{{ $bk['ruang'] }}"
                                        data-kegiatan="{{ $bk['kegiatan'] }}"
                                        data-tanggal="{{ isset($bk['tanggal']) ? \Carbon\Carbon::parse($bk['tanggal'])->translatedFormat('d M Y') : '-' }}"
                                        data-jam="{{ $bk['jam_mulai'] ?? '-' }} - {{ $bk['jam_selesai'] ?? '-' }}"
                                        data-status="pending"
                                        data-tujuan="{{ $bk['tujuan'] ?? '-' }}">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center text-slate-400 dark:text-slate-500">
                            <div class="flex flex-col items-center">
                                <div class="w-20 h-20 rounded-full bg-amber-50 dark:bg-amber-900/20 flex items-center justify-center text-4xl mb-4">
                                    <i class="fas fa-check-double text-amber-400 dark:text-amber-500"></i>
                                </div>
                                <p class="font-semibold text-slate-600 dark:text-slate-400 text-lg">Semua booking sudah diproses!</p>
                                <p class="text-sm text-slate-400 dark:text-slate-500 mt-1">Tidak ada pengajuan yang menunggu approval</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- RIWAYAT APPROVAL --}}
    {{-- ============================================================ --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">

        <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700 bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-indigo-950/30 dark:to-blue-950/30">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                        <i class="fas fa-history text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 dark:text-white">Riwayat Approval</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Semua booking yang sudah diproses</p>
                    </div>
                </div>

                {{-- Filter Status --}}
                <div class="flex gap-1.5 text-xs font-semibold flex-wrap">
                    <button class="filter-btn active px-3 py-1.5 rounded-lg bg-slate-800 text-white transition" data-filter="all">
                        <i class="fas fa-list mr-1"></i> Semua
                    </button>
                    <button class="filter-btn px-3 py-1.5 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-600 transition" data-filter="approved">
                        <i class="fas fa-check-circle text-emerald-500 mr-1"></i> Disetujui
                    </button>
                    <button class="filter-btn px-3 py-1.5 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-600 transition" data-filter="rejected">
                        <i class="fas fa-times-circle text-red-500 mr-1"></i> Ditolak
                    </button>
                    <button class="filter-btn px-3 py-1.5 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-600 transition" data-filter="no_show">
                        <i class="fas fa-hourglass-end text-slate-400 mr-1"></i> No Show
                    </button>
                    <button class="filter-btn px-3 py-1.5 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-600 transition" data-filter="cancelled">
                        <i class="fas fa-ban text-slate-400 mr-1"></i> Dibatalkan
                    </button>
                    <button class="filter-btn px-3 py-1.5 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-600 transition" data-filter="completed">
                        <i class="fas fa-check-double text-blue-500 mr-1"></i> Selesai
                    </button>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 dark:bg-slate-700/50 text-slate-500 dark:text-slate-400 text-xs uppercase tracking-wide">
                    <tr>
                        <th class="text-left px-6 py-4 font-semibold">Kode</th>
                        <th class="text-left px-6 py-4 font-semibold">Pemohon</th>
                        <th class="text-left px-6 py-4 font-semibold">Ruang</th>
                        <th class="text-left px-6 py-4 font-semibold">Kegiatan</th>
                        <th class="text-left px-6 py-4 font-semibold">Waktu Booking</th>
                        <th class="text-left px-6 py-4 font-semibold">Diproses</th>
                        <th class="text-left px-6 py-4 font-semibold">Status</th>
                        <th class="text-left px-6 py-4 font-semibold">Catatan</th>
                        <th class="text-center px-6 py-4 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700" id="history-table">
                    @forelse($history as $bk)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition history-row" data-status="{{ $bk['status'] }}">
                        <td class="px-6 py-4">
                            <span class="font-bold text-slate-700 dark:text-slate-300 font-mono">{{ $bk['id'] }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-semibold text-slate-800 dark:text-white">{{ $bk['pemohon'] }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">
                                <span class="px-2 py-0.5 rounded-full bg-slate-100 dark:bg-slate-600 text-[10px]">
                                    {{ $bk['tipe'] }}
                                </span>
                            </p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-medium text-slate-700 dark:text-slate-300">{{ $bk['ruang'] }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-slate-700 dark:text-slate-300">{{ $bk['kegiatan'] }}</p>
                        </td>
                        <td class="px-6 py-4 text-slate-600 dark:text-slate-400">
                            <p class="font-medium">{{ $bk['waktu'] }}</p>
                        </td>
                        <td class="px-6 py-4 text-slate-500 dark:text-slate-400 text-xs">
                            {{ $bk['diproses'] }}
                        </td>
                        <td class="px-6 py-4">
                            {{-- ============================================================ --}}
                            {{-- ✅ PERBAIKAN STATUS BADGE --}}
                            {{-- ============================================================ --}}
                            @if($bk['status'] === 'approved')
                                <span class="px-3 py-1 rounded-full bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300 text-xs font-bold inline-flex items-center gap-1">
                                    <i class="fas fa-check-circle"></i> Disetujui
                                </span>
                            @elseif($bk['status'] === 'rejected')
                                <span class="px-3 py-1 rounded-full bg-red-100 dark:bg-red-900/50 text-red-700 dark:text-red-300 text-xs font-bold inline-flex items-center gap-1">
                                    <i class="fas fa-times-circle"></i> Ditolak
                                </span>
                            @elseif($bk['status'] === 'no_show')
                                <span class="px-3 py-1 rounded-full bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 text-xs font-bold inline-flex items-center gap-1">
                                    <i class="fas fa-hourglass-end"></i> No Show
                                </span>
                            @elseif($bk['status'] === 'cancelled')
                                <span class="px-3 py-1 rounded-full bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 text-xs font-bold inline-flex items-center gap-1">
                                    <i class="fas fa-ban"></i> Dibatalkan
                                </span>
                            @elseif($bk['status'] === 'completed')
                                <span class="px-3 py-1 rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300 text-xs font-bold inline-flex items-center gap-1">
                                    <i class="fas fa-check-double"></i> Selesai
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 text-xs font-bold">
                                    {{ ucfirst($bk['status']) }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-slate-500 dark:text-slate-400 text-xs max-w-40 truncate" title="{{ $bk['catatan'] }}">
                            {{ $bk['catatan'] }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button class="btn-detail-history px-3 py-1.5 rounded-lg bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-600 dark:text-slate-300 font-semibold text-xs transition flex items-center gap-1 mx-auto"
                                    data-id="{{ $bk['id'] }}"
                                    data-pemohon="{{ $bk['pemohon'] }}"
                                    data-tipe="{{ $bk['tipe'] }}"
                                    data-ruang="{{ $bk['ruang'] }}"
                                    data-kegiatan="{{ $bk['kegiatan'] }}"
                                    data-tanggal="{{ isset($bk['tanggal']) ? \Carbon\Carbon::parse($bk['tanggal'])->translatedFormat('d M Y') : '-' }}"
                                    data-jam="{{ $bk['jam_mulai'] ?? '-' }} - {{ $bk['jam_selesai'] ?? '-' }}"
                                    data-status="{{ $bk['status'] }}"
                                    data-tujuan="{{ $bk['tujuan'] ?? '-' }}"
                                    data-catatan="{{ $bk['catatan'] ?? '-' }}"
                                    data-diproses="{{ $bk['diproses'] ?? '-' }}">
                                <i class="fas fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-16 text-center text-slate-400 dark:text-slate-500">
                            <div class="flex flex-col items-center">
                                <div class="w-20 h-20 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-4xl mb-4">
                                    <i class="fas fa-inbox text-slate-400 dark:text-slate-500"></i>
                                </div>
                                <p class="font-semibold text-slate-600 dark:text-slate-400">Belum ada riwayat approval</p>
                                <p class="text-sm text-slate-400 dark:text-slate-500 mt-1">Belum ada booking yang diproses</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- MODAL REJECT --}}
    {{-- ============================================================ --}}
    <div id="modal-reject" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/50 backdrop-blur-sm">
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl p-8 w-full max-w-md mx-4 animate-fade-in-up">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-red-100 dark:bg-red-900/50 flex items-center justify-center text-red-600 dark:text-red-400">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div>
                    <h3 class="font-bold text-lg text-slate-800 dark:text-white">Tolak Booking</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Masukkan alasan penolakan yang jelas</p>
                </div>
            </div>

            <form id="form-reject" method="POST">
                @csrf
                <input type="hidden" id="booking-id-input" name="booking_id">

                <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Alasan Penolakan <span class="text-red-500">*</span></label>
                <textarea name="reason" id="reject-reason" rows="4"
                    class="mt-2 w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white p-3 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none resize-none transition"
                    placeholder="contoh: Konflik jadwal dengan kelas reguler..."></textarea>
                <p class="text-xs text-red-500 mt-1 hidden" id="reason-error">
                    <i class="fas fa-exclamation-circle mr-1"></i> Alasan wajib diisi.
                </p>

                <div class="flex gap-3 mt-6">
                    <button type="button" id="btn-cancel-reject"
                        class="flex-1 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-400 font-semibold hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white font-semibold transition shadow-sm flex items-center justify-center gap-2">
                        <i class="fas fa-times"></i> Tolak Booking
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- MODAL DETAIL --}}
    {{-- ============================================================ --}}
    <div id="bookingModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 items-center justify-center p-4">
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-xl overflow-hidden animate-fade-in-up">
            <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-indigo-950/30 dark:to-blue-950/30">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                        <i class="fas fa-file-invoice text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 dark:text-white">Detail Booking</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Informasi lengkap pengajuan booking</p>
                    </div>
                </div>
                <button id="btnCloseModal"
                        class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 font-bold transition text-slate-500 dark:text-slate-400">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="p-6 space-y-3 text-sm" id="modalContent">
                {{-- diisi JS --}}
            </div>
        </div>
    </div>

</div>

<style>
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}
.animate-fade-in-up {
    animation: fadeInUp 0.25s ease-out forwards;
}
</style>

@push('scripts')
<script>
$(document).ready(function () {

    // ================================================================
    // APPROVE
    // ================================================================
    $('.btn-approve').on('click', function () {
        const id = $(this).data('id');
        const action = $(this).data('action');

        Swal.fire({
            title: 'Setujui Booking?',
            text: 'Booking akan disetujui dan slot waktu akan terkunci.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#64748b',
            confirmButtonText: '✅ Ya, Setujui',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: action,
                    type: 'POST',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        Swal.fire('Disetujui!', 'Booking berhasil disetujui.', 'success');
                        location.reload();
                    },
                    error: function() {
                        Swal.fire('Gagal!', 'Terjadi kesalahan, coba lagi.', 'error');
                    }
                });
            }
        });
    });

    // ================================================================
    // REJECT - BUKA MODAL
    // ================================================================
    $('.btn-reject').on('click', function () {
        const id = $(this).data('id');
        const action = $(this).data('action');

        $('#form-reject').attr('action', action);
        $('#booking-id-input').val(id);
        $('#reject-reason').val('');
        $('#reason-error').addClass('hidden');
        $('#modal-reject').removeClass('hidden').addClass('flex');
        document.body.style.overflow = 'hidden';
    });

    // ================================================================
    // REJECT - TUTUP MODAL
    // ================================================================
    function closeRejectModal() {
        $('#modal-reject').removeClass('flex').addClass('hidden');
        document.body.style.overflow = '';
    }

    $('#btn-cancel-reject').on('click', closeRejectModal);

    $('#modal-reject').on('click', function (e) {
        if ($(e.target).is('#modal-reject')) {
            closeRejectModal();
        }
    });

    // ESC key
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape') {
            closeRejectModal();
            closeDetailModal();
        }
    });

    // ================================================================
    // REJECT - VALIDASI
    // ================================================================
    $('#form-reject').on('submit', function (e) {
        const reason = $('#reject-reason').val().trim();
        if (!reason) {
            e.preventDefault();
            $('#reason-error').removeClass('hidden');
            $('#reject-reason').focus();
        }
    });

    // ================================================================
    // DETAIL - PENDING
    // ================================================================
    $('.btn-detail').on('click', function () {
        const data = {
            id: $(this).data('id') || '-',
            pemohon: $(this).data('pemohon') || '-',
            tipe: $(this).data('tipe') || '-',
            ruang: $(this).data('ruang') || '-',
            kegiatan: $(this).data('kegiatan') || '-',
            tanggal: $(this).data('tanggal') || '-',
            jam: $(this).data('jam') || '-',
            status: $(this).data('status') || 'pending',
            tujuan: $(this).data('tujuan') || 'Tidak ada keterangan'
        };

        showDetailModal(data);
    });

    // ================================================================
    // DETAIL - HISTORY
    // ================================================================
    $('.btn-detail-history').on('click', function () {
        const data = {
            id: $(this).data('id'),
            pemohon: $(this).data('pemohon'),
            tipe: $(this).data('tipe'),
            ruang: $(this).data('ruang'),
            kegiatan: $(this).data('kegiatan'),
            tanggal: $(this).data('tanggal'),
            jam: $(this).data('jam'),
            status: $(this).data('status'),
            tujuan: $(this).data('tujuan'),
            catatan: $(this).data('catatan'),
            diproses: $(this).data('diproses')
        };

        showDetailModal(data);
    });

    // ================================================================
    // SHOW DETAIL MODAL
    // ================================================================

    function showDetailModal(data) {
        let statusBadge = '';
        if (data.status === 'approved') {
            statusBadge = '<span class="px-3 py-1 rounded-full bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300 text-xs font-bold inline-flex items-center gap-1"><i class="fas fa-check-circle"></i> Disetujui</span>';
        } else if (data.status === 'rejected') {
            statusBadge = '<span class="px-3 py-1 rounded-full bg-red-100 dark:bg-red-900/50 text-red-700 dark:text-red-300 text-xs font-bold inline-flex items-center gap-1"><i class="fas fa-times-circle"></i> Ditolak</span>';
        } else if (data.status === 'no_show') {
            statusBadge = '<span class="px-3 py-1 rounded-full bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 text-xs font-bold inline-flex items-center gap-1"><i class="fas fa-hourglass-end"></i> No Show</span>';
        } else if (data.status === 'cancelled') {
            statusBadge = '<span class="px-3 py-1 rounded-full bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 text-xs font-bold inline-flex items-center gap-1"><i class="fas fa-ban"></i> Dibatalkan</span>';
        } else if (data.status === 'completed') {
            statusBadge = '<span class="px-3 py-1 rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300 text-xs font-bold inline-flex items-center gap-1"><i class="fas fa-check-double"></i> Selesai</span>';
        } else {
            statusBadge = '<span class="px-3 py-1 rounded-full bg-amber-100 dark:bg-amber-900/50 text-amber-700 dark:text-amber-300 text-xs font-bold inline-flex items-center gap-1"><i class="fas fa-clock"></i> Pending</span>';
        }

        let actorHtml = '';
        if (data.aktor && data.aktor !== '-') {
            actorHtml = `
            <div class="border-b border-slate-100 dark:border-slate-700 pb-3 flex justify-between">
                <strong class="text-slate-700 dark:text-slate-300">Dilakukan Oleh:</strong>
                <span class="text-slate-600 dark:text-slate-400">${data.aktor === 'user' ? '👤 User' : (data.aktor === 'admin' ? '👨‍💼 Admin' : '🤖 Sistem')}</span>
            </div>
            `;
        }

        let catatanHtml = '';
        if (data.catatan && data.catatan !== '-' && data.catatan !== 'Tidak ada keterangan') {
            catatanHtml = `
            <div class="border-b border-slate-100 dark:border-slate-700 pb-3">
                <strong class="text-slate-700 dark:text-slate-300">Catatan:</strong><br>
                <p class="text-slate-600 dark:text-slate-400 mt-1 text-sm bg-amber-50 dark:bg-amber-950/20 p-2 rounded-lg">${data.catatan}</p>
            </div>
            `;
        }

        let diprosesHtml = '';
        if (data.diproses && data.diproses !== '-') {
            diprosesHtml = `
            <div class="border-b border-slate-100 dark:border-slate-700 pb-3 flex justify-between">
                <strong class="text-slate-700 dark:text-slate-300">Diproses Pada:</strong>
                <span class="text-slate-600 dark:text-slate-400">${data.diproses}</span>
            </div>
            `;
        }

        $('#modalContent').html(`
            <div class="grid grid-cols-1 gap-3">
                <div class="border-b border-slate-100 dark:border-slate-700 pb-3 flex justify-between">
                    <strong class="text-slate-700 dark:text-slate-300">Kode Booking:</strong>
                    <span class="font-mono text-slate-800 dark:text-white font-bold">${data.id}</span>
                </div>
                <div class="border-b border-slate-100 dark:border-slate-700 pb-3 flex justify-between">
                    <strong class="text-slate-700 dark:text-slate-300">Pemohon:</strong>
                    <span class="text-slate-800 dark:text-white">${data.pemohon} <span class="text-xs text-slate-500 dark:text-slate-400">(${data.tipe})</span></span>
                </div>
                <div class="border-b border-slate-100 dark:border-slate-700 pb-3 flex justify-between">
                    <strong class="text-slate-700 dark:text-slate-300">Ruang:</strong>
                    <span class="text-slate-800 dark:text-white">${data.ruang}</span>
                </div>
                <div class="border-b border-slate-100 dark:border-slate-700 pb-3 flex justify-between">
                    <strong class="text-slate-700 dark:text-slate-300">Kegiatan:</strong>
                    <span class="text-slate-800 dark:text-white">${data.kegiatan}</span>
                </div>
                <div class="border-b border-slate-100 dark:border-slate-700 pb-3 flex justify-between">
                    <strong class="text-slate-700 dark:text-slate-300">Tanggal:</strong>
                    <span class="text-slate-800 dark:text-white">${data.tanggal}</span>
                </div>
                <div class="border-b border-slate-100 dark:border-slate-700 pb-3 flex justify-between">
                    <strong class="text-slate-700 dark:text-slate-300">Jam:</strong>
                    <span class="text-slate-800 dark:text-white">${data.jam}</span>
                </div>
                <div class="border-b border-slate-100 dark:border-slate-700 pb-3 flex justify-between">
                    <strong class="text-slate-700 dark:text-slate-300">Status:</strong>
                    ${statusBadge}
                </div>
                ${diprosesHtml}
                ${actorHtml}
                ${catatanHtml}
                <div>
                    <strong class="text-slate-700 dark:text-slate-300">Tujuan / Deskripsi:</strong><br>
                    <p class="text-slate-600 dark:text-slate-400 mt-1 text-sm leading-relaxed bg-slate-50 dark:bg-slate-700/50 p-3 rounded-lg">${data.tujuan}</p>
                </div>
            </div>
        `);

        $('#bookingModal').removeClass('hidden').addClass('flex');
        document.body.style.overflow = 'hidden';
    }

    // ================================================================
    // TUTUP MODAL DETAIL
    // ================================================================
    function closeDetailModal() {
        $('#bookingModal').removeClass('flex').addClass('hidden');
        document.body.style.overflow = '';
    }

    $('#btnCloseModal').on('click', closeDetailModal);

    $('#bookingModal').on('click', function (e) {
        if ($(e.target).is('#bookingModal')) {
            closeDetailModal();
        }
    });

    // ================================================================
    // FILTER RIWAYAT
    // ================================================================
    $('.filter-btn').on('click', function () {
        const filter = $(this).data('filter');

        $('.filter-btn').removeClass('bg-slate-800 text-white')
                        .addClass('bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400');
        $(this).removeClass('bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400')
               .addClass('bg-slate-800 text-white');

        $('.history-row').each(function () {
            if (filter === 'all' || $(this).data('status') === filter) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

});
</script>
@endpush

@endsection
