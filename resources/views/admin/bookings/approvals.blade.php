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
                {{-- TAMBAHKAN CLASS table-pending --}}
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700 table-pending">
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
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $bk['priority_color'] }}">
                                {{ $bk['priority_label'] }}
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

                                {{-- TAMBAHKAN DATA LENGKAP UNTUK PENDING --}}
                                <button class="btn-detail-pending px-3 py-1.5 rounded-lg bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-600 dark:text-slate-300 font-semibold text-xs transition flex items-center gap-1"
                                        data-id="{{ $bk['id'] }}"
                                        data-pemohon="{{ $bk['pemohon'] }}"
                                        data-email="{{ $bk['email'] ?? '-' }}"
                                        data-fakultas="{{ $bk['fakultas'] ?? '-' }}"
                                        data-tipe="{{ $bk['tipe'] }}"
                                        data-ruang="{{ $bk['ruang'] }}"
                                        data-gedung="{{ $bk['gedung'] ?? '-' }}"
                                        data-lantai="{{ $bk['lantai'] ?? '-' }}"
                                        data-kapasitas="{{ $bk['kapasitas'] ?? '-' }}"
                                        data-kegiatan="{{ $bk['kegiatan'] }}"
                                        data-jenis_kegiatan="{{ $bk['jenis_kegiatan'] ?? '-' }}"
                                        data-prioritas="{{ $bk['prioritas'] ?? '-' }}"
                                        data-tanggal="{{ isset($bk['tanggal']) ? \Carbon\Carbon::parse($bk['tanggal'])->translatedFormat('d M Y') : '-' }}"
                                        data-jam="{{ $bk['jam_mulai'] ?? '-' }} - {{ $bk['jam_selesai'] ?? '-' }}"
                                        data-durasi="{{ $bk['durasi'] ?? '-' }}"
                                        data-check_in_status="{{ $bk['check_in_status'] ?? '-' }}"
                                        data-check_in_at="{{ $bk['check_in_at'] ?? '-' }}"
                                        data-checkin_deadline="{{ $bk['checkin_deadline'] ?? '-' }}"
                                        data-disetujui_oleh="{{ $bk['disetujui_oleh'] ?? '-' }}"
                                        data-disetujui_at="{{ $bk['disetujui_at'] ?? '-' }}"
                                        data-status="pending"
                                        data-tujuan="{{ $bk['tujuan'] ?? '-' }}"
                                        data-catatan="-"
                                        data-diproses="-">
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
                    <button class="filter-btn {{ !request('status') ? 'active' : '' }} px-3 py-1.5 rounded-lg {{ !request('status') ? 'bg-slate-800 text-white' : 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-600' }} transition" data-filter="all">
                        <i class="fas fa-list mr-1"></i> Semua
                    </button>
                    <button class="filter-btn {{ request('status') === 'approved' ? 'active' : '' }} px-3 py-1.5 rounded-lg {{ request('status') === 'approved' ? 'bg-slate-800 text-white' : 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-600' }} transition" data-filter="approved">
                        <i class="fas fa-check-circle text-emerald-500 mr-1"></i> Disetujui
                    </button>
                    <button class="filter-btn {{ request('status') === 'rejected' ? 'active' : '' }} px-3 py-1.5 rounded-lg {{ request('status') === 'rejected' ? 'bg-slate-800 text-white' : 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-600' }} transition" data-filter="rejected">
                        <i class="fas fa-times-circle text-red-500 mr-1"></i> Ditolak
                    </button>
                    <button class="filter-btn {{ request('status') === 'no_show' ? 'active' : '' }} px-3 py-1.5 rounded-lg {{ request('status') === 'no_show' ? 'bg-slate-800 text-white' : 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-600' }} transition" data-filter="no_show">
                        <i class="fas fa-hourglass-end text-slate-400 mr-1"></i> No Show
                    </button>
                    <button class="filter-btn {{ request('status') === 'cancelled' ? 'active' : '' }} px-3 py-1.5 rounded-lg {{ request('status') === 'cancelled' ? 'bg-slate-800 text-white' : 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-600' }} transition" data-filter="cancelled">
                        <i class="fas fa-ban text-slate-400 mr-1"></i> Dibatalkan
                    </button>
                    <button class="filter-btn {{ request('status') === 'completed' ? 'active' : '' }} px-3 py-1.5 rounded-lg {{ request('status') === 'completed' ? 'bg-slate-800 text-white' : 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-600' }} transition" data-filter="completed">
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
                {{-- TAMBAHKAN CLASS table-history --}}
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700 table-history" id="history-table">
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
                            {{-- UBAH MENJADI btn-detail-history --}}
                            <button class="btn-detail-history px-3 py-1.5 rounded-lg bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-600 dark:text-slate-300 font-semibold text-xs transition flex items-center gap-1"
                                    data-id="{{ $bk['id'] }}"
                                    data-pemohon="{{ $bk['pemohon'] }}"
                                    data-email="{{ $bk['email'] ?? '-' }}"
                                    data-fakultas="{{ $bk['fakultas'] ?? '-' }}"
                                    data-tipe="{{ $bk['tipe'] }}"
                                    data-ruang="{{ $bk['ruang'] }}"
                                    data-gedung="{{ $bk['gedung'] ?? '-' }}"
                                    data-lantai="{{ $bk['lantai'] ?? '-' }}"
                                    data-kapasitas="{{ $bk['kapasitas'] ?? '-' }}"
                                    data-kegiatan="{{ $bk['kegiatan'] }}"
                                    data-jenis_kegiatan="{{ $bk['jenis_kegiatan'] ?? '-' }}"
                                    data-prioritas="{{ $bk['prioritas'] ?? '-' }}"
                                    data-tanggal="{{ isset($bk['tanggal']) ? \Carbon\Carbon::parse($bk['tanggal'])->translatedFormat('d M Y') : '-' }}"
                                    data-jam="{{ $bk['jam_mulai'] ?? '-' }} - {{ $bk['jam_selesai'] ?? '-' }}"
                                    data-durasi="{{ $bk['durasi'] ?? '-' }}"
                                    data-check_in_status="{{ $bk['check_in_status'] ?? '-' }}"
                                    data-check_in_at="{{ $bk['check_in_at'] ?? '-' }}"
                                    data-checkin_deadline="{{ $bk['checkin_deadline'] ?? '-' }}"
                                    data-disetujui_oleh="{{ $bk['disetujui_oleh'] ?? '-' }}"
                                    data-disetujui_at="{{ $bk['disetujui_at'] ?? '-' }}"
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
    <div id="bookingModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 items-center justify-center p-4">
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden animate-fade-in-up max-h-[90vh] flex flex-col">
            {{-- Header --}}
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between bg-gradient-to-r from-indigo-600 to-blue-600 dark:from-indigo-800 dark:to-blue-800 flex-shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center text-white">
                        <i class="fas fa-file-invoice text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-white text-lg">Detail Booking</h3>
                        <p class="text-xs text-white/70">Informasi lengkap pengajuan booking</p>
                    </div>
                </div>
                <button id="btnCloseModal"
                        class="w-10 h-10 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold transition flex items-center justify-center">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            {{-- Content --}}
            <div class="p-6 overflow-y-auto flex-1" id="modalContent">
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
    $(document).on('click', '.btn-approve', function () {
        const $btn = $(this);
        const id = $btn.data('id');
        const action = $btn.data('action');
        const originalHtml = $btn.html();

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
                $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Memproses...');
                Swal.fire({
                    title: 'Memproses...',
                    text: 'Mohon tunggu',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: action,
                    type: 'POST',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        Swal.fire('Disetujui!', 'Booking berhasil disetujui.', 'success');
                        setTimeout(() => location.reload(), 1000);
                    },
                    error: function(xhr) {
                        $btn.prop('disabled', false).html(originalHtml);
                        Swal.fire('Gagal!', xhr.responseJSON?.message || 'Terjadi kesalahan, coba lagi.', 'error');
                    }
                });
            }
        });
    });

    // ================================================================
    // REJECT - BUKA MODAL
    // ================================================================
    $(document).on('click', '.btn-reject', function () {
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
    // DETAIL - PENDING (btn-detail-pending)
    // ================================================================
    $(document).on('click', '.btn-detail-pending', function () {
        const data = {
            id: $(this).data('id') || '-',
            pemohon: $(this).data('pemohon') || '-',
            email: $(this).data('email') || '-',
            fakultas: $(this).data('fakultas') || '-',
            tipe: $(this).data('tipe') || '-',
            ruang: $(this).data('ruang') || '-',
            gedung: $(this).data('gedung') || '-',
            lantai: $(this).data('lantai') || '-',
            kapasitas: $(this).data('kapasitas') || '-',
            kegiatan: $(this).data('kegiatan') || '-',
            jenis_kegiatan: $(this).data('jenis_kegiatan') || '-',
            prioritas: $(this).data('prioritas') || '-',
            tanggal: $(this).data('tanggal') || '-',
            jam: $(this).data('jam') || '-',
            durasi: $(this).data('durasi') || '-',
            status: $(this).data('status') || 'pending',
            tujuan: $(this).data('tujuan') || 'Tidak ada keterangan',
            check_in_status: $(this).data('check_in_status') || '-',
            check_in_at: $(this).data('check_in_at') || '-',
            checkin_deadline: $(this).data('checkin_deadline') || '-',
            disetujui_oleh: $(this).data('disetujui_oleh') || '-',
            disetujui_at: $(this).data('disetujui_at') || '-',
            catatan: $(this).data('catatan') || '-',
            diproses: $(this).data('diproses') || '-'
        };
        showDetailModal(data);
    });

    // ================================================================
    // DETAIL - HISTORY (btn-detail-history)
    // ================================================================
    $(document).on('click', '.btn-detail-history', function () {
        const data = {
            id: $(this).data('id') || '-',
            pemohon: $(this).data('pemohon') || '-',
            email: $(this).data('email') || '-',
            fakultas: $(this).data('fakultas') || '-',
            tipe: $(this).data('tipe') || '-',
            ruang: $(this).data('ruang') || '-',
            gedung: $(this).data('gedung') || '-',
            lantai: $(this).data('lantai') || '-',
            kapasitas: $(this).data('kapasitas') || '-',
            kegiatan: $(this).data('kegiatan') || '-',
            jenis_kegiatan: $(this).data('jenis_kegiatan') || '-',
            prioritas: $(this).data('prioritas') || '-',
            tanggal: $(this).data('tanggal') || '-',
            jam: $(this).data('jam') || '-',
            durasi: $(this).data('durasi') || '-',
            status: $(this).data('status') || '-',
            tujuan: $(this).data('tujuan') || 'Tidak ada keterangan',
            check_in_status: $(this).data('check_in_status') || '-',
            check_in_at: $(this).data('check_in_at') || '-',
            checkin_deadline: $(this).data('checkin_deadline') || '-',
            disetujui_oleh: $(this).data('disetujui_oleh') || '-',
            disetujui_at: $(this).data('disetujui_at') || '-',
            catatan: $(this).data('catatan') || '-',
            diproses: $(this).data('diproses') || '-'
        };
        showDetailModal(data);
    });

    // ================================================================
    // SHOW DETAIL MODAL
    // ================================================================
    function showDetailModal(data) {
        // Status Badge dengan icon dan warna
        let statusBadge = '';
        let statusIcon = '';
        let statusColor = '';

        if (data.status === 'approved') {
            statusBadge = 'Disetujui';
            statusIcon = 'fa-check-circle';
            statusColor = 'bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300';
        } else if (data.status === 'rejected') {
            statusBadge = 'Ditolak';
            statusIcon = 'fa-times-circle';
            statusColor = 'bg-red-100 dark:bg-red-900/50 text-red-700 dark:text-red-300';
        } else if (data.status === 'no_show') {
            statusBadge = 'No Show';
            statusIcon = 'fa-hourglass-end';
            statusColor = 'bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400';
        } else if (data.status === 'cancelled') {
            statusBadge = 'Dibatalkan';
            statusIcon = 'fa-ban';
            statusColor = 'bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400';
        } else if (data.status === 'completed') {
            statusBadge = 'Selesai';
            statusIcon = 'fa-check-double';
            statusColor = 'bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300';
        } else if (data.status === 'pending') {
            statusBadge = 'Menunggu Approval';
            statusIcon = 'fa-clock';
            statusColor = 'bg-amber-100 dark:bg-amber-900/50 text-amber-700 dark:text-amber-300';
        } else {
            statusBadge = data.status || 'Unknown';
            statusIcon = 'fa-question-circle';
            statusColor = 'bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400';
        }

        // Check-in Status dengan icon
        let checkinHtml = '';
        if (data.check_in_status && data.check_in_status !== '-' && data.check_in_status !== 'undefined') {
            let checkinLabel = '';
            let checkinIcon = '';
            let checkinColor = '';

            if (data.check_in_status === 'belum_checkin') {
                checkinLabel = 'Belum Check-in';
                checkinIcon = 'fa-hourglass-half';
                checkinColor = 'text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-950/30';
            } else if (data.check_in_status === 'checkin_tepat_waktu') {
                checkinLabel = 'Tepat Waktu';
                checkinIcon = 'fa-check-circle';
                checkinColor = 'text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/30';
            } else if (data.check_in_status === 'checkin_terlambat') {
                checkinLabel = 'Terlambat';
                checkinIcon = 'fa-exclamation-triangle';
                checkinColor = 'text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-950/30';
            } else if (data.check_in_status === 'no_show') {
                checkinLabel = 'No Show';
                checkinIcon = 'fa-times-circle';
                checkinColor = 'text-red-700 dark:text-red-500 bg-red-50 dark:bg-red-950/30';
            } else {
                checkinLabel = data.check_in_status;
                checkinIcon = 'fa-info-circle';
                checkinColor = 'text-slate-600 dark:text-slate-400 bg-slate-50 dark:bg-slate-700/30';
            }

            checkinHtml = `
            <div class="flex items-center justify-between p-3 rounded-xl ${checkinColor}">
                <span class="text-sm font-medium text-slate-700 dark:text-slate-300">
                    <i class="fas ${checkinIcon} mr-2"></i>Check-in Status
                </span>
                <span class="text-sm font-semibold">${checkinLabel}</span>
            </div>
            `;
        }

        // Check-in Time
        let checkinTimeHtml = '';
        if (data.check_in_at && data.check_in_at !== '-' && data.check_in_at !== 'undefined') {
            checkinTimeHtml = `
            <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 dark:bg-slate-700/30">
                <span class="text-sm font-medium text-slate-700 dark:text-slate-300">
                    <i class="fas fa-clock mr-2 text-indigo-500"></i>Waktu Check-in
                </span>
                <span class="text-sm font-semibold text-slate-800 dark:text-white">${data.check_in_at}</span>
            </div>
            `;
        }

        // Check-in Deadline dengan warning jika lewat
        let checkinDeadlineHtml = '';
        if (data.checkin_deadline && data.checkin_deadline !== '-' && data.checkin_deadline !== 'undefined') {
            const isExpired = data.check_in_status === 'belum_checkin';
            const bgColor = isExpired ? 'bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-800' : 'bg-slate-50 dark:bg-slate-700/30';
            const textColor = isExpired ? 'text-red-600 dark:text-red-400' : 'text-slate-800 dark:text-white';
            const icon = isExpired ? 'fa-exclamation-circle text-red-500' : 'fa-hourglass-end text-indigo-500';
            const warningText = isExpired ? '<span class="ml-2 text-xs text-red-500 font-bold">⚠️ Lewat Deadline</span>' : '';

            checkinDeadlineHtml = `
            <div class="flex items-center justify-between p-3 rounded-xl ${bgColor}">
                <span class="text-sm font-medium text-slate-700 dark:text-slate-300">
                    <i class="fas ${icon} mr-2"></i>Check-in Deadline
                </span>
                <span class="text-sm font-semibold ${textColor}">${data.checkin_deadline} ${warningText}</span>
            </div>
            `;
        }

        // Approval Info
        let approvalHtml = '';
        if (data.disetujui_oleh && data.disetujui_oleh !== '-' && data.disetujui_oleh !== 'undefined') {
            approvalHtml = `
            <div class="flex items-center justify-between p-3 rounded-xl bg-emerald-50 dark:bg-emerald-950/20">
                <span class="text-sm font-medium text-slate-700 dark:text-slate-300">
                    <i class="fas fa-user-check mr-2 text-emerald-500"></i>Disetujui Oleh
                </span>
                <span class="text-sm font-semibold text-slate-800 dark:text-white">${data.disetujui_oleh}</span>
            </div>
            `;
        }

        let approvalTimeHtml = '';
        if (data.disetujui_at && data.disetujui_at !== '-' && data.disetujui_at !== 'undefined') {
            approvalTimeHtml = `
            <div class="flex items-center justify-between p-3 rounded-xl bg-emerald-50 dark:bg-emerald-950/20">
                <span class="text-sm font-medium text-slate-700 dark:text-slate-300">
                    <i class="fas fa-calendar-check mr-2 text-emerald-500"></i>Waktu Disetujui
                </span>
                <span class="text-sm font-semibold text-slate-800 dark:text-white">${data.disetujui_at}</span>
            </div>
            `;
        }

        // Diproses Pada
        let diprosesHtml = '';
        if (data.diproses && data.diproses !== '-' && data.diproses !== 'undefined') {
            diprosesHtml = `
            <div class="flex items-center justify-between p-3 rounded-xl bg-indigo-50 dark:bg-indigo-950/20">
                <span class="text-sm font-medium text-slate-700 dark:text-slate-300">
                    <i class="fas fa-history mr-2 text-indigo-500"></i>Diproses Pada
                </span>
                <span class="text-sm font-semibold text-slate-800 dark:text-white">${data.diproses}</span>
            </div>
            `;
        }

        // Catatan Admin
        let catatanHtml = '';
        if (data.catatan && data.catatan !== '-' && data.catatan !== 'undefined' && data.catatan !== 'Tidak ada keterangan') {
            const isRejected = data.status === 'rejected';
            const bgColor = isRejected ? 'bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-800' : 'bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-800';
            const textColor = isRejected ? 'text-red-600 dark:text-red-400' : 'text-amber-600 dark:text-amber-400';
            const icon = isRejected ? 'fa-times-circle' : 'fa-info-circle';
            const iconColor = isRejected ? 'text-red-500' : 'text-amber-500';

            catatanHtml = `
            <div class="p-4 rounded-xl ${bgColor} mt-2">
                <div class="flex items-start gap-3">
                    <i class="fas ${icon} ${iconColor} mt-0.5"></i>
                    <div>
                        <span class="text-xs font-semibold uppercase tracking-wider ${textColor}">Catatan Admin</span>
                        <p class="text-sm text-slate-700 dark:text-slate-300 mt-1 leading-relaxed">${data.catatan}</p>
                    </div>
                </div>
            </div>
            `;
        }

        // Prioritas dengan visual indicator
        let prioritasHtml = '';
        if (data.prioritas && data.prioritas !== '-' && data.prioritas !== 'undefined') {
            let prioritasColor = '';
            let prioritasBg = '';
            let prioritasIcon = '';

            if (data.prioritas === 'High') {
                prioritasColor = 'text-red-600 dark:text-red-400';
                prioritasBg = 'bg-red-50 dark:bg-red-950/30';
                prioritasIcon = 'fa-arrow-up';
            } else if (data.prioritas === 'Medium-High') {
                prioritasColor = 'text-orange-600 dark:text-orange-400';
                prioritasBg = 'bg-orange-50 dark:bg-orange-950/30';
                prioritasIcon = 'fa-arrow-up';
            } else if (data.prioritas === 'Medium') {
                prioritasColor = 'text-yellow-600 dark:text-yellow-400';
                prioritasBg = 'bg-yellow-50 dark:bg-yellow-950/30';
                prioritasIcon = 'fa-minus';
            } else if (data.prioritas === 'Low') {
                prioritasColor = 'text-blue-600 dark:text-blue-400';
                prioritasBg = 'bg-blue-50 dark:bg-blue-950/30';
                prioritasIcon = 'fa-arrow-down';
            } else {
                prioritasColor = 'text-slate-600 dark:text-slate-400';
                prioritasBg = 'bg-slate-50 dark:bg-slate-700/30';
                prioritasIcon = 'fa-circle';
            }

            prioritasHtml = `
            <div class="flex items-center justify-between p-3 rounded-xl ${prioritasBg}">
                <span class="text-sm font-medium text-slate-700 dark:text-slate-300">
                    <i class="fas ${prioritasIcon} mr-2 ${prioritasColor}"></i>Prioritas
                </span>
                <span class="text-sm font-bold ${prioritasColor}">${data.prioritas}</span>
            </div>
            `;
        }

        // Jenis Kegiatan
        let jenisKegiatanHtml = '';
        if (data.jenis_kegiatan && data.jenis_kegiatan !== '-' && data.jenis_kegiatan !== 'undefined') {
            jenisKegiatanHtml = `
            <div class="flex items-center justify-between p-3 rounded-xl bg-purple-50 dark:bg-purple-950/20">
                <span class="text-sm font-medium text-slate-700 dark:text-slate-300">
                    <i class="fas fa-tag mr-2 text-purple-500"></i>Jenis Kegiatan
                </span>
                <span class="text-sm font-semibold text-slate-800 dark:text-white">${data.jenis_kegiatan}</span>
            </div>
            `;
        }

        // Durasi
        let durasiHtml = '';
        if (data.durasi && data.durasi !== '-' && data.durasi !== 'undefined') {
            durasiHtml = `
            <div class="flex items-center justify-between p-3 rounded-xl bg-cyan-50 dark:bg-cyan-950/20">
                <span class="text-sm font-medium text-slate-700 dark:text-slate-300">
                    <i class="fas fa-hourglass-half mr-2 text-cyan-500"></i>Durasi
                </span>
                <span class="text-sm font-semibold text-slate-800 dark:text-white">${data.durasi}</span>
            </div>
            `;
        }

        // Ruang Detail (Gedung, Lantai)
        let ruangDetailHtml = '';
        if ((data.gedung && data.gedung !== '-' && data.gedung !== 'undefined') ||
            (data.lantai && data.lantai !== '-' && data.lantai !== 'undefined')) {
            const gedungText = data.gedung && data.gedung !== '-' && data.gedung !== 'undefined' ? data.gedung : '-';
            const lantaiText = data.lantai && data.lantai !== '-' && data.lantai !== 'undefined' ? data.lantai : '-';
            ruangDetailHtml = `
            <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 dark:bg-slate-700/30">
                <span class="text-sm font-medium text-slate-700 dark:text-slate-300">
                    <i class="fas fa-location-dot mr-2 text-indigo-500"></i>Lokasi Ruang
                </span>
                <span class="text-sm font-semibold text-slate-800 dark:text-white">${gedungText}, Lantai ${lantaiText}</span>
            </div>
            `;
        }

        // Kapasitas
        let kapasitasHtml = '';
        if (data.kapasitas && data.kapasitas !== '-' && data.kapasitas !== 'undefined') {
            kapasitasHtml = `
            <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 dark:bg-slate-700/30">
                <span class="text-sm font-medium text-slate-700 dark:text-slate-300">
                    <i class="fas fa-users mr-2 text-indigo-500"></i>Kapasitas Ruang
                </span>
                <span class="text-sm font-semibold text-slate-800 dark:text-white">${data.kapasitas} orang</span>
            </div>
            `;
        }

        // Pemohon Info (Email & Fakultas)
        let pemohonInfoHtml = '';
        if (data.email && data.email !== '-' && data.email !== 'undefined') {
            pemohonInfoHtml += `
            <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 dark:bg-slate-700/30">
                <span class="text-sm font-medium text-slate-700 dark:text-slate-300">
                    <i class="fas fa-envelope mr-2 text-indigo-500"></i>Email Pemohon
                </span>
                <span class="text-sm font-semibold text-slate-800 dark:text-white">${data.email}</span>
            </div>
            `;
        }
        if (data.fakultas && data.fakultas !== '-' && data.fakultas !== 'undefined') {
            pemohonInfoHtml += `
            <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 dark:bg-slate-700/30">
                <span class="text-sm font-medium text-slate-700 dark:text-slate-300">
                    <i class="fas fa-building-columns mr-2 text-indigo-500"></i>Fakultas
                </span>
                <span class="text-sm font-semibold text-slate-800 dark:text-white">${data.fakultas}</span>
            </div>
            `;
        }

        // Badge Status untuk header
        const statusBadgeHtml = `<span class="px-3 py-1 rounded-full text-xs font-bold ${statusColor} inline-flex items-center gap-1.5"><i class="fas ${statusIcon}"></i> ${statusBadge}</span>`;

        // Kode Booking
        const bookingIdDisplay = data.id || '-';
        const isHistory = data.status !== 'pending';

        // Tujuan / Deskripsi
        const tujuanText = data.tujuan || 'Tidak ada keterangan';

        // Build HTML
        $('#modalContent').html(`
            <div class="space-y-4">
                {{-- Header Card --}}
                <div class="flex items-center justify-between p-4 rounded-xl ${isHistory ? 'bg-indigo-50 dark:bg-indigo-950/30' : 'bg-amber-50 dark:bg-amber-950/30'} border ${isHistory ? 'border-indigo-200 dark:border-indigo-800' : 'border-amber-200 dark:border-amber-800'}">
                    <div>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Kode Booking</p>
                        <p class="text-lg font-bold font-mono text-slate-800 dark:text-white">#${bookingIdDisplay}</p>
                    </div>
                    <div>
                        ${statusBadgeHtml}
                    </div>
                </div>

                {{-- Pemohon --}}
                <div class="p-4 rounded-xl bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-indigo-950/30 dark:to-blue-950/30 border border-indigo-100 dark:border-indigo-800">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                            <i class="fas fa-user text-xl"></i>
                        </div>
                        <div>
                            <p class="font-bold text-slate-800 dark:text-white text-lg">${data.pemohon || '-'}</p>
                            <p class="text-sm text-slate-500 dark:text-slate-400">
                                <span class="px-2 py-0.5 rounded-full bg-slate-200 dark:bg-slate-600 text-xs font-medium">${data.tipe || '-'}</span>
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Informasi Pemohon --}}
                ${pemohonInfoHtml}

                {{-- Informasi Ruang --}}
                <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-3">
                        <i class="fas fa-door-open mr-2"></i>Informasi Ruang
                    </p>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600 dark:text-slate-400">Nama Ruang</span>
                            <span class="text-sm font-semibold text-slate-800 dark:text-white">${data.ruang || '-'}</span>
                        </div>
                        ${ruangDetailHtml ? ruangDetailHtml.replace(/^<div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 dark:bg-slate-700\/30">/, '<div class="flex items-center justify-between">') : ''}
                        ${kapasitasHtml ? kapasitasHtml.replace(/^<div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 dark:bg-slate-700\/30">/, '<div class="flex items-center justify-between">') : ''}
                    </div>
                </div>

                {{-- Informasi Kegiatan --}}
                <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-3">
                        <i class="fas fa-calendar-day mr-2"></i>Informasi Kegiatan
                    </p>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600 dark:text-slate-400">Kegiatan</span>
                            <span class="text-sm font-semibold text-slate-800 dark:text-white">${data.kegiatan || '-'}</span>
                        </div>
                        ${jenisKegiatanHtml ? jenisKegiatanHtml.replace(/^<div class="flex items-center justify-between p-3 rounded-xl bg-purple-50 dark:bg-purple-950\/20">/, '<div class="flex items-center justify-between">') : ''}
                        ${prioritasHtml ? prioritasHtml.replace(/^<div class="flex items-center justify-between p-3 rounded-xl .*?">/, '<div class="flex items-center justify-between">') : ''}
                    </div>
                </div>

                {{-- Jadwal --}}
                <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-3">
                        <i class="fas fa-clock mr-2"></i>Jadwal
                    </p>
                    <div class="grid grid-cols-2 gap-2">
                        <div class="p-2 rounded-lg bg-white dark:bg-slate-700/50">
                            <p class="text-xs text-slate-400 dark:text-slate-500">Tanggal</p>
                            <p class="text-sm font-semibold text-slate-800 dark:text-white">${data.tanggal || '-'}</p>
                        </div>
                        <div class="p-2 rounded-lg bg-white dark:bg-slate-700/50">
                            <p class="text-xs text-slate-400 dark:text-slate-500">Jam</p>
                            <p class="text-sm font-semibold text-slate-800 dark:text-white">${data.jam || '-'}</p>
                        </div>
                        ${durasiHtml ? `<div class="p-2 rounded-lg bg-white dark:bg-slate-700/50 col-span-2">${durasiHtml.replace(/^<div class="flex items-center justify-between p-3 rounded-xl bg-cyan-50 dark:bg-cyan-950\/20">/, '<div class="flex items-center justify-between">').replace(/<\/div>$/, '</div>')}</div>` : ''}
                    </div>
                </div>

                {{-- Check-in Status --}}
                ${checkinHtml || checkinTimeHtml || checkinDeadlineHtml ? `
                <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-3">
                        <i class="fas fa-fingerprint mr-2"></i>Check-in
                    </p>
                    <div class="space-y-2">
                        ${checkinHtml ? checkinHtml.replace(/^<div class="flex items-center justify-between p-3 rounded-xl .*?">/, '<div class="flex items-center justify-between">') : ''}
                        ${checkinTimeHtml ? checkinTimeHtml.replace(/^<div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 dark:bg-slate-700\/30">/, '<div class="flex items-center justify-between">') : ''}
                        ${checkinDeadlineHtml ? checkinDeadlineHtml.replace(/^<div class="flex items-center justify-between p-3 rounded-xl .*?">/, '<div class="flex items-center justify-between">') : ''}
                    </div>
                </div>
                ` : ''}

                {{-- Approval --}}
                ${approvalHtml || approvalTimeHtml || diprosesHtml ? `
                <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-3">
                        <i class="fas fa-check-double mr-2"></i>Approval
                    </p>
                    <div class="space-y-2">
                        ${approvalHtml ? approvalHtml.replace(/^<div class="flex items-center justify-between p-3 rounded-xl bg-emerald-50 dark:bg-emerald-950\/20">/, '<div class="flex items-center justify-between">') : ''}
                        ${approvalTimeHtml ? approvalTimeHtml.replace(/^<div class="flex items-center justify-between p-3 rounded-xl bg-emerald-50 dark:bg-emerald-950\/20">/, '<div class="flex items-center justify-between">') : ''}
                        ${diprosesHtml ? diprosesHtml.replace(/^<div class="flex items-center justify-between p-3 rounded-xl bg-indigo-50 dark:bg-indigo-950\/20">/, '<div class="flex items-center justify-between">') : ''}
                    </div>
                </div>
                ` : ''}

                {{-- Catatan Admin --}}
                ${catatanHtml}

                {{-- Tujuan / Deskripsi --}}
                <div class="p-4 rounded-xl bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-800/50 dark:to-slate-700/30 border border-slate-200 dark:border-slate-700">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2">
                        <i class="fas fa-align-left mr-2"></i>Tujuan / Deskripsi
                    </p>
                    <p class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed">${tujuanText}</p>
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
    $(document).on('click', '.filter-btn', function () {
        const filter = $(this).data('filter');
        const currentUrl = new URL(window.location.href);

        if (filter === 'all') {
            currentUrl.searchParams.delete('status');
        } else {
            currentUrl.searchParams.set('status', filter);
        }

        window.location.href = currentUrl.toString();
    });

});
</script>
@endpush

@endsection
