@extends('layouts.app')

@section('title', 'Approval Booking')
@section('page_title', 'Approval Booking')
@section('page_subtitle', 'Validasi pengajuan booking ruang oleh admin atau sekretaris jurusan')

@section('content')

{{-- STAT CARDS --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-5 mb-6">
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200">
        <p class="text-sm text-slate-500">Menunggu Approval</p>
        <h3 class="text-3xl font-extrabold mt-1 text-amber-500">{{ $stats['pending'] }}</h3>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200">
        <p class="text-sm text-slate-500">Disetujui</p>
        <h3 class="text-3xl font-extrabold mt-1 text-emerald-600">{{ $stats['approved'] }}</h3>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200">
        <p class="text-sm text-slate-500">Ditolak</p>
        <h3 class="text-3xl font-extrabold mt-1 text-red-500">{{ $stats['rejected'] }}</h3>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200">
        <p class="text-sm text-slate-500">Expired</p>
        <h3 class="text-3xl font-extrabold mt-1 text-slate-400">{{ $stats['expired'] }}</h3>
    </div>
</div>

{{-- SUCCESS FLASH --}}
@if(session('success'))
<div class="mb-5 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 font-semibold text-sm">
    ✅ {{ session('success') }}
</div>
@endif

{{-- ══ PENDING BOOKINGS ══ --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-6">
    <div class="p-6 border-b border-slate-200 flex items-center justify-between">
        <div>
            <h3 class="font-bold text-lg">Daftar Pengajuan Pending</h3>
            <p class="text-sm text-slate-500">Booking harus diproses maksimal 1 × 24 jam</p>
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
                    <th class="text-left px-6 py-4">Pemohon</th>
                    <th class="text-left px-6 py-4">Ruang</th>
                    <th class="text-left px-6 py-4">Kegiatan</th>
                    <th class="text-left px-6 py-4">Waktu</th>
                    <th class="text-left px-6 py-4">Prioritas</th>
                    <th class="text-center px-6 py-4">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($pending as $bk)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-6 py-4 font-bold text-slate-700">{{ $bk['id'] }}</td>
                    <td class="px-6 py-4">
                        <p class="font-semibold">{{ $bk['pemohon'] }}</p>
                        <p class="text-xs text-slate-500">{{ $bk['tipe'] }}</p>
                    </td>
                    <td class="px-6 py-4">{{ $bk['ruang'] }}</td>
                    <td class="px-6 py-4">{{ $bk['kegiatan'] }}</td>
                    <td class="px-6 py-4 text-slate-600">{{ $bk['waktu'] }}</td>
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
                        <div class="flex justify-center gap-2">
                            <button
                                class="btn-approve px-3 py-2 rounded-lg bg-emerald-100 hover:bg-emerald-200 text-emerald-700 font-semibold transition"
                                data-id="{{ $bk['id'] }}"
                                data-action="{{ route('admin.approvals.approve', $bk['id']) }}">
                                ✓ Approve
                            </button>
                            <button
                                class="btn-reject px-3 py-2 rounded-lg bg-red-100 hover:bg-red-200 text-red-700 font-semibold transition"
                                data-id="{{ $bk['id'] }}"
                                data-action="{{ route('admin.approvals.reject', $bk['id']) }}">
                                ✕ Reject
                            </button>
                            <button class="btn-detail px-3 py-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition"
                                    data-id="{{ $bk['id'] }}"
                                    data-pemohon="{{ $bk['pemohon'] }}"
                                    data-tipe="{{ $bk['tipe'] }}"
                                    data-ruang="{{ $bk['ruang'] }}"
                                    data-kegiatan="{{ $bk['kegiatan'] }}"
                                    data-tanggal="{{ isset($bk['tanggal']) ? \Carbon\Carbon::parse($bk['tanggal'])->translatedFormat('d M Y') : '-' }}"
                                    data-jam="{{ $bk['jam_mulai'] }} - {{ $bk['jam_selesai'] }}"
                                    data-status="pending"
                                    data-tujuan="{{ $bk['tujuan'] }}">
                                Detail
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                        <p class="text-3xl mb-2">🎉</p>
                        <p class="font-semibold">Semua booking sudah diproses!</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ══ RIWAYAT APPROVAL ══ --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="p-6 border-b border-slate-200 flex items-center justify-between">
        <div>
            <h3 class="font-bold text-lg">Riwayat Approval</h3>
            <p class="text-sm text-slate-500">Semua booking yang sudah diproses</p>
        </div>

        {{-- Filter Status --}}
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
            <button class="filter-btn px-3 py-1.5 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 transition" data-filter="expired">
                ⏰Expired
            </button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-500">
                <tr>
                    <th class="text-left px-6 py-4">Kode</th>
                    <th class="text-left px-6 py-4">Pemohon</th>
                    <th class="text-left px-6 py-4">Ruang</th>
                    <th class="text-left px-6 py-4">Kegiatan</th>
                    <th class="text-left px-6 py-4">Waktu Booking</th>
                    <th class="text-left px-6 py-4">Diproses</th>
                    <th class="text-left px-6 py-4">Status</th>
                    <th class="text-left px-6 py-4">Catatan</th>
                    <th class="text-center px-6 py-4">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100" id="history-table">
                @forelse($history as $bk)
                <tr class="hover:bg-slate-50 transition history-row" data-status="{{ $bk['status'] }}">
                    <td class="px-6 py-4 font-bold text-slate-700">{{ $bk['id'] }}</td>
                    <td class="px-6 py-4">
                        <p class="font-semibold">{{ $bk['pemohon'] }}</p>
                        <p class="text-xs text-slate-500">{{ $bk['tipe'] }}</p>
                    </td>
                    <td class="px-6 py-4">{{ $bk['ruang'] }}</td>
                    <td class="px-6 py-4">{{ $bk['kegiatan'] }}</td>
                    <td class="px-6 py-4 text-slate-600">{{ $bk['waktu'] }}</td>
                    <td class="px-6 py-4 text-slate-500 text-xs">{{ $bk['diproses'] }}</td>
                    <td class="px-6 py-4">
                        @if($bk['status'] === 'approved')
                            <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">
                                ✓ Disetujui
                            </span>
                        @elseif($bk['status'] === 'rejected')
                            <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-bold">
                                ✕ Ditolak
                            </span>
                        @elseif($bk['status'] === 'expired')
                            <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-500 text-xs font-bold">
                                ⏰ Expired
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-slate-500 text-xs max-w-40">
                        {{ $bk['catatan'] }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        <button class="btn-detail-history px-3 py-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition"
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
                            Detail
                        </button>
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

{{-- Modal Reject --}}
<div id="modal-reject" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/40 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md mx-4">
        <h3 class="font-bold text-lg mb-1">Tolak Booking</h3>
        <p class="text-sm text-slate-500 mb-5">Masukkan alasan penolakan yang jelas untuk pemohon.</p>

        <form id="form-reject" method="POST">
            @csrf
            <input type="hidden" id="booking-id-input" name="booking_id">

            <textarea name="reason" id="reject-reason" rows="3"
                class="w-full rounded-xl border border-slate-200 p-3 text-sm focus:border-blue-400 outline-none resize-none"
                placeholder="contoh: Konflik jadwal dengan kelas reguler..."></textarea>
            <p class="text-xs text-red-500 mt-1 hidden" id="reason-error">Alasan wajib diisi.</p>

            <div class="flex gap-3 mt-5">
                <button type="button" id="btn-cancel-reject"
                    class="flex-1 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold hover:bg-slate-50 transition">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white font-semibold transition">
                    Tolak Booking
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Detail --}}
<div id="bookingModal" class="hidden fixed inset-0 bg-black/50 z-50 items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-xl overflow-hidden">

        <div class="p-6 border-b border-slate-200 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-lg">Detail Booking</h3>
                <p class="text-sm text-slate-500">Informasi lengkap pengajuan booking</p>
            </div>
            <button id="btnCloseModal"
                    class="w-10 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 font-bold transition">
                ✕
            </button>
        </div>

        <div class="p-6 space-y-3 text-sm" id="modalContent">
            {{-- diisi JS --}}
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function () {

    // ── Approve ──
    $('.btn-approve').on('click', function () {
        const id     = $(this).data('id');
        const action = $(this).data('action');

        if (confirm(`Setujui booking ${id}?`)) {
            $.post(action, { _token: '{{ csrf_token() }}' })
                .done(() => location.reload())
                .fail(() => alert('Gagal menyetujui. Coba lagi.'));
        }
    });

    // ── Reject: buka modal ──
    $('.btn-reject').on('click', function () {
        const id     = $(this).data('id');
        const action = $(this).data('action');

        $('#form-reject').attr('action', action);
        $('#booking-id-input').val(id);
        $('#reject-reason').val('');
        $('#reason-error').addClass('hidden');
        $('#modal-reject').removeClass('hidden').addClass('flex');
    });

    // ── Reject: tutup modal ──
    $('#btn-cancel-reject').on('click', function () {
        $('#modal-reject').removeClass('flex').addClass('hidden');
    });

    $('#modal-reject').on('click', function (e) {
        if ($(e.target).is('#modal-reject')) {
            $(this).removeClass('flex').addClass('hidden');
        }
    });

    // ── Reject: validasi sebelum submit ──
    $('#form-reject').on('submit', function (e) {
        const reason = $('#reject-reason').val().trim();
        if (!reason) {
            e.preventDefault();
            $('#reason-error').removeClass('hidden');
        }
    });

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

        let statusBadge = '';
        if (data.status === 'approved') {
            statusBadge = '<span class="px-2 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">✓ Disetujui</span>';
        } else if (data.status === 'rejected') {
            statusBadge = '<span class="px-2 py-1 rounded-full bg-red-100 text-red-700 text-xs font-bold">✕ Ditolak</span>';
        } else if (data.status === 'expired') {
            statusBadge = '<span class="px-2 py-1 rounded-full bg-slate-100 text-slate-500 text-xs font-bold">⏰ Expired</span>';
        } else {
            statusBadge = '<span class="px-2 py-1 rounded-full bg-amber-100 text-amber-700 text-xs font-bold">⏳ Pending</span>';
        }

        $('#modalContent').html(`
            <div class="grid grid-cols-1 gap-3">
                <div class="border-b pb-2 flex justify-between">
                    <strong>Kode Booking:</strong>
                    <span class="font-mono">${data.id}</span>
                </div>
                <div class="border-b pb-2 flex justify-between">
                    <strong>Pemohon:</strong>
                    <span>${data.pemohon} <span class="text-xs text-slate-500">(${data.tipe})</span></span>
                </div>
                <div class="border-b pb-2 flex justify-between">
                    <strong>Ruang:</strong>
                    <span>${data.ruang}</span>
                </div>
                <div class="border-b pb-2 flex justify-between">
                    <strong>Kegiatan:</strong>
                    <span>${data.kegiatan}</span>
                </div>
                <div class="border-b pb-2 flex justify-between">
                    <strong>Tanggal:</strong>
                    <span>${data.tanggal}</span>
                </div>
                <div class="border-b pb-2 flex justify-between">
                    <strong>Jam:</strong>
                    <span>${data.jam}</span>
                </div>
                <div class="border-b pb-2 flex justify-between">
                    <strong>Status:</strong>
                    ${statusBadge}
                </div>
                <div>
                    <strong>Tujuan / Deskripsi:</strong><br>
                    <p class="text-slate-600 mt-1 text-sm leading-relaxed">${data.tujuan}</p>
                </div>
            </div>
        `);

        $('#bookingModal').removeClass('hidden').addClass('flex');
    });

    // ── Detail History Booking ──
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

        // Mapping status ke badge yang lebih informatif
        let statusBadge = '';
        if (data.status === 'approved') {
            statusBadge = '<span class="px-2 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">✓ Disetujui</span>';
        } else if (data.status === 'rejected') {
            statusBadge = '<span class="px-2 py-1 rounded-full bg-red-100 text-red-700 text-xs font-bold">✕ Ditolak</span>';
        } else if (data.status === 'expired') {
            statusBadge = '<span class="px-2 py-1 rounded-full bg-slate-100 text-slate-500 text-xs font-bold">⏰ Expired</span>';
        } else {
            statusBadge = '<span class="px-2 py-1 rounded-full bg-amber-100 text-amber-700 text-xs font-bold">⏳ Pending</span>';
        }

        $('#modalContent').html(`
            <div class="grid grid-cols-1 gap-3">
                <div class="border-b pb-2 flex justify-between">
                    <strong>Kode Booking:</strong>
                    <span class="font-mono">${data.id}</span>
                </div>
                <div class="border-b pb-2 flex justify-between">
                    <strong>Pemohon:</strong>
                    <span>${data.pemohon} <span class="text-xs text-slate-500">(${data.tipe})</span></span>
                </div>
                <div class="border-b pb-2 flex justify-between">
                    <strong>Ruang:</strong>
                    <span>${data.ruang}</span>
                </div>
                <div class="border-b pb-2 flex justify-between">
                    <strong>Kegiatan:</strong>
                    <span>${data.kegiatan}</span>
                </div>
                <div class="border-b pb-2 flex justify-between">
                    <strong>Tanggal:</strong>
                    <span>${data.tanggal}</span>
                </div>
                <div class="border-b pb-2 flex justify-between">
                    <strong>Jam:</strong>
                    <span>${data.jam}</span>
                </div>
                <div class="border-b pb-2 flex justify-between">
                    <strong>Status:</strong>
                    ${statusBadge}
                </div>
                ${data.diproses !== '-' ? `
                <div class="border-b pb-2 flex justify-between">
                    <strong>Diproses Pada:</strong>
                    <span>${data.diproses}</span>
                </div>
                ` : ''}
                ${data.catatan && data.catatan !== '-' ? `
                <div class="border-b pb-2">
                    <strong>Catatan:</strong><br>
                    <p class="text-slate-600 mt-1 text-sm">${data.catatan}</p>
                </div>
                ` : ''}
                <div>
                    <strong>Tujuan / Deskripsi:</strong><br>
                    <p class="text-slate-600 mt-1 text-sm leading-relaxed">${data.tujuan}</p>
                </div>
            </div>
        `);

        $('#bookingModal').removeClass('hidden').addClass('flex');
    });

    // ── Tutup Modal ──
    $('#btnCloseModal').on('click', function () {
        $('#bookingModal').removeClass('flex').addClass('hidden');
    });

    $('#bookingModal').on('click', function (e) {
        if ($(e.target).is('#bookingModal')) {
            $(this).removeClass('flex').addClass('hidden');
        }
    });

    // ── Filter riwayat ──
    $('.filter-btn').on('click', function () {
        const filter = $(this).data('filter');

        // Update tombol aktif
        $('.filter-btn').removeClass('bg-slate-800 text-white')
                        .addClass('bg-slate-100 text-slate-600');
        $(this).removeClass('bg-slate-100 text-slate-600')
               .addClass('bg-slate-800 text-white');

        // Filter baris
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
