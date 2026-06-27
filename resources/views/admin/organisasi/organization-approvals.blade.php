@extends('layouts.app')

@section('title', 'Approval Perwakilan Organisasi')
@section('page_title', 'Approval Perwakilan Organisasi')
@section('page_subtitle', 'Validasi pengajuan perwakilan organisasi oleh mahasiswa')

@section('content')

{{-- STAT CARDS --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-5 mb-6">
    <div class="bg-white dark:bg-surface rounded-2xl p-5 shadow-sm border border-slate-200 dark:border-default">
        <p class="text-sm text-slate-500 dark:text-text-muted">Menunggu Approval</p>
        <h3 class="text-3xl font-extrabold mt-1 text-amber-500 dark:text-amber-400">{{ $stats['pending'] }}</h3>
    </div>
    <div class="bg-white dark:bg-surface rounded-2xl p-5 shadow-sm border border-slate-200 dark:border-default">
        <p class="text-sm text-slate-500 dark:text-text-muted">Disetujui</p>
        <h3 class="text-3xl font-extrabold mt-1 text-emerald-600 dark:text-emerald-400">{{ $stats['approved'] }}</h3>
    </div>
    <div class="bg-white dark:bg-surface rounded-2xl p-5 shadow-sm border border-slate-200 dark:border-default">
        <p class="text-sm text-slate-500 dark:text-text-muted">Ditolak</p>
        <h3 class="text-3xl font-extrabold mt-1 text-red-500 dark:text-red-400">{{ $stats['rejected'] }}</h3>
    </div>
    <div class="bg-white dark:bg-surface rounded-2xl p-5 shadow-sm border border-slate-200 dark:border-default">
        <p class="text-sm text-slate-500 dark:text-text-muted">Total Pengajuan</p>
        <h3 class="text-3xl font-extrabold mt-1 text-slate-700 dark:text-text-primary">{{ $stats['total'] }}</h3>
    </div>
</div>

{{-- FLASH MESSAGES --}}
@if(session('success'))
<div class="mb-5 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 font-semibold text-sm flex items-center gap-2">
    <span>✅</span> {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="mb-5 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 font-semibold text-sm flex items-center gap-2">
    <span>❌</span> {{ session('error') }}
</div>
@endif

{{-- ══ PENDING SUBMISSIONS ══ --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-6">
    <div class="p-6 border-b border-slate-200 flex items-center justify-between flex-wrap gap-3">
        <div>
            <h3 class="font-bold text-lg">Daftar Pengajuan Pending</h3>
            <p class="text-sm text-slate-500">Pengajuan perwakilan organisasi yang perlu diverifikasi</p>
        </div>
        @if($stats['pending'] > 0)
        <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-700 text-xs font-bold animate-pulse">
            {{ $stats['pending'] }} Menunggu
        </span>
        @endif
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-500">
                <tr>
                    <th class="text-left px-6 py-4">Kode</th>
                    <th class="text-left px-6 py-4">Mahasiswa</th>
                    <th class="text-left px-6 py-4">NIM</th>
                    <th class="text-left px-6 py-4">Organisasi</th>
                    <th class="text-left px-6 py-4">Jabatan</th>
                    <th class="text-left px-6 py-4">Tgl Pengajuan</th>
                    <th class="text-left px-6 py-4">Status</th>
                    <th class="text-center px-6 py-4">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($pending as $submission)
                <tr class="hover:bg-slate-50 transition group">
                    <td class="px-6 py-4 font-mono text-xs font-bold text-slate-600 dark:text-text-secondary">
                        {{ $submission['id'] }}
                    </td>
                    <td class="px-6 py-4">
                        <p class="font-semibold text-slate-800 dark:text-text-primary">{{ $submission['nama_mahasiswa'] }}</p>
                        <p class="text-xs text-slate-400 dark:text-text-muted">{{ $submission['email'] }}</p>
                    </td>
                    <td class="px-6 py-4 font-mono text-xs text-slate-600 dark:text-text-secondary">{{ $submission['nim'] }}</td>
                    <td class="px-6 py-4">
                        <p class="font-medium text-slate-700 dark:text-text-secondary">{{ $submission['organisasi'] }}</p>
                        <p class="text-xs text-slate-400 dark:text-text-muted">{{ $submission['singkatan'] }}</p>
                    </td>
                    <td class="px-6 py-4 text-slate-600 dark:text-text-secondary">{{ $submission['jabatan'] }}</td>
                    <td class="px-6 py-4 text-slate-500 dark:text-text-muted text-xs">{{ $submission['tgl_ajuan'] }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 dark:bg-amber-950 dark:text-amber-300">
                            ⏳ Pending
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex justify-center gap-2">
                            <a href="{{ route('admin.organization-approvals.show', $submission['id']) }}"
                               class="px-3 py-2 rounded-lg bg-blue-100 hover:bg-blue-200 text-blue-700 font-semibold transition text-xs">
                                📋 Detail
                            </a>
                            <button class="btn-approve px-3 py-2 rounded-lg bg-emerald-100 hover:bg-emerald-200 text-emerald-700 font-semibold transition text-xs"
                                    data-id="{{ $submission['id'] }}"
                                    data-action="{{ route('admin.organization-approvals.approve', $submission['id']) }}">
                                ✓ Approve
                            </button>
                            <button class="btn-reject px-3 py-2 rounded-lg bg-red-100 hover:bg-red-200 text-red-700 font-semibold transition text-xs"
                                    data-id="{{ $submission['id'] }}"
                                    data-action="{{ route('admin.organization-approvals.reject', $submission['id']) }}">
                                ✕ Reject
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center text-slate-400">
                        <p class="text-4xl mb-2">🎉</p>
                        <p class="font-semibold">Tidak ada pengajuan pending!</p>
                        <p class="text-xs mt-1">Semua pengajuan sudah diproses.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ══ RIWAYAT APPROVAL ══ --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="p-6 border-b border-slate-200 flex items-center justify-between flex-wrap gap-3">
        <div>
            <h3 class="font-bold text-lg">Riwayat Approval</h3>
            <p class="text-sm text-slate-500">Semua pengajuan yang sudah diproses</p>
        </div>

        {{-- Filter Status (konsisten dengan approval booking) --}}
        <div class="flex gap-2 text-xs font-semibold">
            <button class="filter-btn active px-3 py-1.5 rounded-lg bg-slate-800 text-white transition" data-filter="all">
                Semua
            </button>
            <button class="filter-btn px-3 py-1.5 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 transition" data-filter="approved">
                Disetujui
            </button>
            <button class="filter-btn px-3 py-1.5 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 transition" data-filter="rejected">
                Ditolak
            </button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-500">
                <tr>
                    <th class="text-left px-6 py-4">Kode</th>
                    <th class="text-left px-6 py-4">Mahasiswa</th>
                    <th class="text-left px-6 py-4">Organisasi</th>
                    <th class="text-left px-6 py-4">Jabatan</th>
                    <th class="text-left px-6 py-4">Tgl Pengajuan</th>
                    <th class="text-left px-6 py-4">Diproses</th>
                    <th class="text-left px-6 py-4">Status</th>
                    <th class="text-left px-6 py-4">Catatan</th>
                    <th class="text-left px-6 py-4">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100" id="history-table">
                @forelse($history as $submission)
                <tr class="hover:bg-slate-50 transition history-row" data-status="{{ $submission['status'] }}">
                    <td class="px-6 py-4 font-mono text-xs font-bold text-slate-600">
                        {{ $submission['id'] }}
                    </td>
                    <td class="px-6 py-4">
                        <p class="font-semibold text-slate-800">{{ $submission['nama_mahasiswa'] }}</p>
                        <p class="text-xs text-slate-400">{{ $submission['nim'] }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="font-medium text-slate-700">{{ $submission['organisasi'] }}</p>
                        <p class="text-xs text-slate-400">{{ $submission['singkatan'] }}</p>
                    </td>
                    <td class="px-6 py-4 text-slate-600">{{ $submission['jabatan'] }}</td>
                    <td class="px-6 py-4 text-slate-500 text-xs">{{ $submission['tgl_ajuan'] }}</td>
                    <td class="px-6 py-4 text-slate-500 text-xs">{{ $submission['diproses'] ?? '-' }}</td>
                    <td class="px-6 py-4">
                        @if($submission['status'] === 'approved')
                            <span class="px-3 py-1 rounded-full bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 text-xs font-bold">
                                ✓ Disetujui
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-full bg-red-100 dark:bg-red-950 text-red-700 dark:text-red-300 text-xs font-bold">
                                ✕ Ditolak
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-slate-500 text-xs max-w-50 truncate" title="{{ $submission['catatan'] }}">
                        {{ $submission['catatan'] }}
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.organization-approvals.show', $submission['id']) }}"
                           class="text-blue-600 hover:text-blue-800 font-semibold text-xs transition">
                            Lihat Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-6 py-10 text-center text-slate-400">
                        Belum ada riwayat approval.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- MODAL REJECT (konsisten dengan approval booking) --}}
<div id="modal-reject" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/40 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md mx-4">
        <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center text-2xl mb-4">⚠️</div>
        <h3 class="font-bold text-lg mb-1">Tolak Pengajuan</h3>
        <p class="text-sm text-slate-500 mb-5">Berikan alasan penolakan yang jelas agar mahasiswa dapat mengajukan ulang dengan perbaikan.</p>

        <form id="form-reject" method="POST">
            @csrf
            <input type="hidden" id="submission-id-input" name="submission_id">

            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Alasan Penolakan</label>
            <textarea name="reason" id="reject-reason" rows="4"
                class="w-full rounded-xl border border-slate-200 p-3 text-sm focus:border-red-400 outline-none resize-none"
                placeholder="Contoh: Bukti kepengurusan tidak lengkap, silakan upload SK organisasi..."></textarea>
            <p class="text-xs text-red-500 mt-1 hidden" id="reason-error">Alasan penolakan wajib diisi (minimal 10 karakter).</p>

            <div class="flex gap-3 mt-6">
                <button type="button" id="btn-cancel-reject"
                    class="flex-1 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold hover:bg-slate-50 transition">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white font-semibold transition">
                    Tolak Pengajuan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function () {
    let pendingCount = {{ $stats['pending'] }};

    // ── Approve dengan konfirmasi ──
    $('.btn-approve').on('click', function () {
        const id = $(this).data('id');
        const action = $(this).data('action');

        Swal.fire({
            title: 'Setujui Pengajuan?',
            text: `Mahasiswa akan menjadi perwakilan resmi organisasi dengan ID ${id}.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Setujui',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: action,
                    type: 'POST',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function() {
                        Swal.fire('Disetujui!', 'Pengajuan berhasil disetujui.', 'success');
                        location.reload();
                    },
                    error: function() {
                        Swal.fire('Gagal!', 'Terjadi kesalahan, coba lagi.', 'error');
                    }
                });
            }
        });
    });

    // ── Reject: buka modal ──
    $('.btn-reject').on('click', function () {
        const id = $(this).data('id');
        const action = $(this).data('action');

        $('#form-reject').attr('action', action);
        $('#submission-id-input').val(id);
        $('#reject-reason').val('');
        $('#reason-error').addClass('hidden');
        $('#modal-reject').removeClass('hidden').addClass('flex');
    });

    // ── Tutup modal ──
    $('#btn-cancel-reject').on('click', function () {
        $('#modal-reject').removeClass('flex').addClass('hidden');
    });

    $('#modal-reject').on('click', function (e) {
        if ($(e.target).is('#modal-reject')) {
            $(this).removeClass('flex').addClass('hidden');
        }
    });

    // ── Validasi reject reason ──
    $('#form-reject').on('submit', function (e) {
        const reason = $('#reject-reason').val().trim();
        if (reason.length < 10) {
            e.preventDefault();
            $('#reason-error').removeClass('hidden');
        }
    });

    // ── Filter riwayat (konsisten dengan approval booking) ──
    $('.filter-btn').on('click', function () {
        const filter = $(this).data('filter');

        $('.filter-btn').removeClass('bg-slate-800 text-white')
                        .addClass('bg-slate-100 text-slate-600');
        $(this).removeClass('bg-slate-100 text-slate-600')
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
