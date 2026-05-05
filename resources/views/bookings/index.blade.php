@extends('layouts.app')

@section('title', 'Data Booking')
@section('page_title', 'Booking Saya')
@section('page_subtitle', 'Daftar pengajuan booking ruang yang pernah Anda lakukan')

@section('content')

{{-- STAT CARDS --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-5 mb-6">
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200">
        <p class="text-sm text-slate-500">Total Booking</p>
        <h3 class="text-3xl font-extrabold mt-1">{{ $stats['total'] }}</h3>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200">
        <p class="text-sm text-slate-500">Pending</p>
        <h3 class="text-3xl font-extrabold mt-1 text-amber-500">{{ $stats['pending'] }}</h3>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200">
        <p class="text-sm text-slate-500">Approved</p>
        <h3 class="text-3xl font-extrabold mt-1 text-emerald-600">{{ $stats['approved'] }}</h3>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200">
        <p class="text-sm text-slate-500">No Show</p>
        <h3 class="text-3xl font-extrabold mt-1 text-red-500">{{ $stats['no_show'] }}</h3>
    </div>
</div>

{{-- FLASH SUCCESS --}}
@if(session('success'))
<div class="mb-5 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 font-semibold text-sm">
    ✅ {{ session('success') }}
</div>
@endif

{{-- TABEL BOOKING --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

    <div class="p-6 border-b border-slate-200">
        <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">
            <div>
                <h3 class="font-bold text-lg">Daftar Booking Saya</h3>
                <p class="text-sm text-slate-500">Hanya menampilkan booking atas nama Anda</p>
            </div>

            <div class="flex flex-col md:flex-row gap-3">
                <input type="text" id="searchBooking" class="rounded-xl"
                       placeholder="Cari kode, ruang, kegiatan...">

                <select id="filterStatus" class="rounded-xl">
                    <option value="">Semua Status</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="checked_in">Checked In</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                    <option value="no_show">No Show</option>
                </select>

                <input type="date" id="filterDate" class="rounded-xl">

                <a href="{{ route('bookings.create') }}"
                   class="px-5 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-center whitespace-nowrap">
                    + Ajukan Booking
                </a>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-500">
                <tr>
                    <th class="text-left px-6 py-4">Kode</th>
                    <th class="text-left px-6 py-4">Ruang</th>
                    <th class="text-left px-6 py-4">Kegiatan</th>
                    <th class="text-left px-6 py-4">Tanggal</th>
                    <th class="text-left px-6 py-4">Jam</th>
                    <th class="text-left px-6 py-4">Status</th>
                    <th class="text-center px-6 py-4">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100" id="bookingTable">
                @forelse($bookings as $bk)
                @php
                    $tglFormat = \Carbon\Carbon::parse($bk['tanggal'])->translatedFormat('d M Y');
                    $searchStr = strtolower($bk['id'] . ' ' . $bk['ruang'] . ' ' . $bk['kegiatan'] . ' ' . $bk['pemohon']);
                @endphp
                <tr class="booking-row hover:bg-slate-50 transition"
                    data-search="{{ $searchStr }}"
                    data-status="{{ $bk['status'] }}"
                    data-date="{{ $bk['tanggal'] }}">

                    <td class="px-6 py-4 font-bold text-slate-700">{{ $bk['id'] }}</td>

                    <td class="px-6 py-4">
                        <p class="font-semibold">{{ $bk['ruang'] }}</p>
                        <p class="text-xs text-slate-500">{{ $bk['ruang_nama'] }}</p>
                    </td>

                    <td class="px-6 py-4">{{ $bk['kegiatan'] }}</td>

                    <td class="px-6 py-4">{{ $tglFormat }}</td>

                    <td class="px-6 py-4">
                        <p class="font-semibold">{{ $bk['jam_mulai'] }} - {{ $bk['jam_selesai'] }}</p>
                        <p class="text-xs text-slate-500">{{ $bk['durasi'] }}</p>
                    </td>

                    <td class="px-6 py-4">
                        @php
                            $badge = match($bk['status']) {
                                'approved'   => 'bg-emerald-100 text-emerald-700',
                                'pending'    => 'bg-amber-100 text-amber-700',
                                'completed'  => 'bg-slate-100 text-slate-700',
                                'cancelled'  => 'bg-slate-100 text-slate-400',
                                'no_show'    => 'bg-red-100 text-red-700',
                                'checked_in' => 'bg-blue-100 text-blue-700',
                                default      => 'bg-slate-100 text-slate-500',
                            };
                            $label = match($bk['status']) {
                                'approved'   => 'Approved',
                                'pending'    => 'Pending',
                                'completed'  => 'Completed',
                                'cancelled'  => 'Cancelled',
                                'no_show'    => 'No Show',
                                'checked_in' => 'Checked In',
                                default      => ucfirst($bk['status']),
                            };
                        @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $badge }}">
                            {{ $label }}
                        </span>
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex justify-center gap-2">

                            {{-- Check-in hanya untuk approved --}}
                            @if($bk['status'] === 'approved')
                            <button class="btn-checkin px-3 py-2 rounded-lg bg-blue-100 hover:bg-blue-200 text-blue-700 font-semibold text-xs transition"
                                    data-id="{{ $bk['id'] }}">
                                Check-in
                            </button>
                            @endif

                            {{-- Cancel hanya untuk pending --}}
                            @if($bk['status'] === 'pending')
                            <form method="POST" action="{{ route('bookings.cancel', $bk['id']) }}" class="inline">
                                @csrf
                                <button type="submit"
                                        onclick="return confirm('Batalkan booking {{ $bk['id'] }}?')"
                                        class="px-3 py-2 rounded-lg bg-red-100 hover:bg-red-200 text-red-700 font-semibold text-xs transition">
                                    Cancel
                                </button>
                            </form>
                            @endif

                            {{-- Detail selalu ada --}}
                            <button class="btn-detail px-3 py-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition"
                                    data-id="{{ $bk['id'] }}"
                                    data-pemohon="{{ $bk['pemohon'] }}"
                                    data-tipe="{{ $bk['tipe'] }}"
                                    data-ruang="{{ $bk['ruang'] }}"
                                    data-kegiatan="{{ $bk['kegiatan'] }}"
                                    data-tanggal="{{ $tglFormat }}"
                                    data-jam="{{ $bk['jam_mulai'] }} - {{ $bk['jam_selesai'] }}"
                                    data-status="{{ $label }}"
                                    data-tujuan="{{ $bk['tujuan'] }}">
                                Detail
                            </button>

                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-16 text-center text-slate-400">
                        <p class="text-4xl mb-3">📭</p>
                        <p class="font-semibold text-slate-500">Belum ada booking</p>
                        <p class="text-xs mt-1">Klik "Ajukan Booking" untuk membuat pengajuan baru</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- MODAL DETAIL --}}
<div id="bookingModal"
     class="hidden fixed inset-0 bg-black/50 z-50 items-center justify-center p-4">
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

    // ── Filter ──
    function filterBookings() {
        let keyword = $('#searchBooking').val().toLowerCase();
        let status  = $('#filterStatus').val();
        let date    = $('#filterDate').val();

        $('.booking-row').each(function () {
            let matchKeyword = $(this).data('search').includes(keyword);
            let matchStatus  = status === '' || $(this).data('status') === status;
            let matchDate    = date === '' || $(this).data('date') === date;

            $(this).toggle(matchKeyword && matchStatus && matchDate);
        });
    }

    $('#searchBooking').on('keyup', filterBookings);
    $('#filterStatus').on('change', filterBookings);
    $('#filterDate').on('change', filterBookings);

    // ── Detail Modal ──
    $('.btn-detail').on('click', function () {
        const d = $(this).data();

        const statusColor = {
            'Approved'  : 'text-emerald-600',
            'Pending'   : 'text-amber-600',
            'Completed' : 'text-slate-600',
            'Cancelled' : 'text-slate-400',
            'No Show'   : 'text-red-600',
            'Checked In': 'text-blue-600',
        };

        const color = statusColor[d.status] || 'text-slate-700';

        $('#modalContent').html(`
            <div class="flex justify-between border-b border-slate-100 pb-3">
                <span class="text-slate-500">Kode Booking</span>
                <span class="font-bold">${d.id}</span>
            </div>
            <div class="flex justify-between border-b border-slate-100 pb-3">
                <span class="text-slate-500">Pemohon</span>
                <span class="font-bold">${d.pemohon}</span>
            </div>
            <div class="flex justify-between border-b border-slate-100 pb-3">
                <span class="text-slate-500">Role</span>
                <span class="font-bold">${d.tipe}</span>
            </div>
            <div class="flex justify-between border-b border-slate-100 pb-3">
                <span class="text-slate-500">Ruang</span>
                <span class="font-bold">${d.ruang}</span>
            </div>
            <div class="flex justify-between border-b border-slate-100 pb-3">
                <span class="text-slate-500">Kegiatan</span>
                <span class="font-bold">${d.kegiatan}</span>
            </div>
            <div class="flex justify-between border-b border-slate-100 pb-3">
                <span class="text-slate-500">Tanggal</span>
                <span class="font-bold">${d.tanggal}</span>
            </div>
            <div class="flex justify-between border-b border-slate-100 pb-3">
                <span class="text-slate-500">Jam</span>
                <span class="font-bold">${d.jam}</span>
            </div>
            <div class="flex justify-between border-b border-slate-100 pb-3">
                <span class="text-slate-500">Status</span>
                <span class="font-bold ${color}">${d.status}</span>
            </div>
            <div>
                <p class="text-slate-500 mb-2">Tujuan Penggunaan</p>
                <div class="bg-slate-50 rounded-xl p-4 text-slate-700">${d.tujuan}</div>
            </div>
        `);

        $('#bookingModal').removeClass('hidden');
    });

    // ── Tutup Modal ──
    $('#btnCloseModal, #bookingModal').on('click', function (e) {
        if (e.target.id === 'btnCloseModal' || e.target.id === 'bookingModal') {
            $('#bookingModal').addClass('hidden');
        }
    });

    // ── Check-in ──
    $('.btn-checkin').on('click', function () {
        const id = $(this).data('id');
        if (confirm(`Lakukan check-in untuk booking ${id}?`)) {
            alert('Check-in berhasil! (Belum terhubung database)');
        }
    });

});
</script>
@endpush

@endsection