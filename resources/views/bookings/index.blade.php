@extends('layouts.app')

@section('title', 'Data Booking')
@section('page_title', 'Data Booking Ruang')
@section('page_subtitle', 'Pantau pengajuan, jadwal, status, dan check-in booking ruang')

@section('content')

<div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-6">

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
        <p class="text-sm text-slate-500">Total Booking</p>
        <h3 class="text-3xl font-extrabold mt-2">18</h3>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
        <p class="text-sm text-slate-500">Pending</p>
        <h3 class="text-3xl font-extrabold mt-2 text-yellow-600">5</h3>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
        <p class="text-sm text-slate-500">Approved</p>
        <h3 class="text-3xl font-extrabold mt-2 text-emerald-600">10</h3>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
        <p class="text-sm text-slate-500">No Show</p>
        <h3 class="text-3xl font-extrabold mt-2 text-red-600">3</h3>
    </div>

</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

    <div class="p-6 border-b border-slate-200">
        <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">

            <div>
                <h3 class="font-bold text-lg">Daftar Booking Ruang</h3>
                <p class="text-sm text-slate-500">
                    Kelola pengajuan booking dan jadwal penggunaan ruang
                </p>
            </div>

            <div class="flex flex-col md:flex-row gap-3">

                <input type="text"
                       id="searchBooking"
                       class="rounded-xl"
                       placeholder="Cari kode, ruang, pemohon...">

                <select id="filterStatus" class="rounded-xl">
                    <option value="">Semua Status</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="checked_in">Checked In</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                    <option value="no_show">No Show</option>
                </select>

                <input type="date"
                       id="filterDate"
                       class="rounded-xl">

                <a href="{{ route('bookings.create') }}"
                   class="px-5 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-center">
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
                    <th class="text-left px-6 py-4">Pemohon</th>
                    <th class="text-left px-6 py-4">Ruang</th>
                    <th class="text-left px-6 py-4">Kegiatan</th>
                    <th class="text-left px-6 py-4">Tanggal</th>
                    <th class="text-left px-6 py-4">Jam</th>
                    <th class="text-left px-6 py-4">Status</th>
                    <th class="text-center px-6 py-4">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100" id="bookingTable">

                <tr class="booking-row"
                    data-search="bk-001 pak budi dosen r-201 kelas pengganti"
                    data-status="approved"
                    data-date="2026-05-03">

                    <td class="px-6 py-4 font-bold">BK-001</td>

                    <td class="px-6 py-4">
                        <p class="font-semibold">Pak Budi</p>
                        <p class="text-xs text-slate-500">Dosen</p>
                    </td>

                    <td class="px-6 py-4">
                        <p class="font-semibold">R-201</p>
                        <p class="text-xs text-slate-500">Ruang Kelas 201</p>
                    </td>

                    <td class="px-6 py-4">Kelas Pengganti</td>

                    <td class="px-6 py-4">03 Mei 2026</td>

                    <td class="px-6 py-4">
                        <p class="font-semibold">08.00 - 10.00</p>
                        <p class="text-xs text-slate-500">2 jam</p>
                    </td>

                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">
                            Approved
                        </span>
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex justify-center gap-2">
                            <button class="btn-checkin px-3 py-2 rounded-lg bg-blue-100 text-blue-700 font-semibold">
                                Check-in
                            </button>
                            <button class="btn-detail px-3 py-2 rounded-lg bg-slate-100 text-slate-700 font-semibold">
                                Detail
                            </button>
                        </div>
                    </td>
                </tr>

                <tr class="booking-row"
                    data-search="bk-002 bem kampus organisasi lab-01 rapat organisasi"
                    data-status="pending"
                    data-date="2026-05-03">

                    <td class="px-6 py-4 font-bold">BK-002</td>

                    <td class="px-6 py-4">
                        <p class="font-semibold">BEM Kampus</p>
                        <p class="text-xs text-slate-500">Organisasi</p>
                    </td>

                    <td class="px-6 py-4">
                        <p class="font-semibold">LAB-01</p>
                        <p class="text-xs text-slate-500">Lab Komputer</p>
                    </td>

                    <td class="px-6 py-4">Rapat Organisasi</td>

                    <td class="px-6 py-4">03 Mei 2026</td>

                    <td class="px-6 py-4">
                        <p class="font-semibold">13.00 - 16.00</p>
                        <p class="text-xs text-slate-500">3 jam</p>
                    </td>

                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-bold">
                            Pending
                        </span>
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex justify-center gap-2">
                            <button class="btn-cancel px-3 py-2 rounded-lg bg-red-100 text-red-700 font-semibold">
                                Cancel
                            </button>
                            <button class="btn-detail px-3 py-2 rounded-lg bg-slate-100 text-slate-700 font-semibold">
                                Detail
                            </button>
                        </div>
                    </td>
                </tr>

                <tr class="booking-row"
                    data-search="bk-003 mahasiswa demo mahasiswa r-105 belajar kelompok"
                    data-status="completed"
                    data-date="2026-05-02">

                    <td class="px-6 py-4 font-bold">BK-003</td>

                    <td class="px-6 py-4">
                        <p class="font-semibold">Mahasiswa Demo</p>
                        <p class="text-xs text-slate-500">Mahasiswa</p>
                    </td>

                    <td class="px-6 py-4">
                        <p class="font-semibold">R-105</p>
                        <p class="text-xs text-slate-500">Ruang Diskusi 105</p>
                    </td>

                    <td class="px-6 py-4">Belajar Kelompok</td>

                    <td class="px-6 py-4">02 Mei 2026</td>

                    <td class="px-6 py-4">
                        <p class="font-semibold">18.00 - 20.00</p>
                        <p class="text-xs text-slate-500">2 jam</p>
                    </td>

                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-bold">
                            Completed
                        </span>
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex justify-center gap-2">
                            <button class="btn-detail px-3 py-2 rounded-lg bg-slate-100 text-slate-700 font-semibold">
                                Detail
                            </button>
                        </div>
                    </td>
                </tr>

                <tr class="booking-row"
                    data-search="bk-004 mahasiswa tono r-302 diskusi tugas"
                    data-status="no_show"
                    data-date="2026-05-01">

                    <td class="px-6 py-4 font-bold">BK-004</td>

                    <td class="px-6 py-4">
                        <p class="font-semibold">Tono Mahasiswa</p>
                        <p class="text-xs text-slate-500">Mahasiswa</p>
                    </td>

                    <td class="px-6 py-4">
                        <p class="font-semibold">R-302</p>
                        <p class="text-xs text-slate-500">Ruang Kelas 302</p>
                    </td>

                    <td class="px-6 py-4">Diskusi Tugas</td>

                    <td class="px-6 py-4">01 Mei 2026</td>

                    <td class="px-6 py-4">
                        <p class="font-semibold">09.00 - 11.00</p>
                        <p class="text-xs text-slate-500">2 jam</p>
                    </td>

                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-bold">
                            No Show
                        </span>
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex justify-center gap-2">
                            <button class="btn-detail px-3 py-2 rounded-lg bg-slate-100 text-slate-700 font-semibold">
                                Detail
                            </button>
                        </div>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>

</div>

{{-- MODAL DETAIL BOOKING --}}
<div id="bookingModal"
     class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">

    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-xl overflow-hidden">

        <div class="p-6 border-b border-slate-200 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-lg">Detail Booking</h3>
                <p class="text-sm text-slate-500">Informasi detail pengajuan booking ruang</p>
            </div>

            <button id="btnCloseModal"
                    class="w-10 h-10 rounded-xl bg-slate-100 hover:bg-slate-200">
                ✕
            </button>
        </div>

        <div class="p-6 space-y-4 text-sm">

            <div class="flex justify-between border-b border-slate-100 pb-3">
                <span class="text-slate-500">Kode Booking</span>
                <span class="font-bold">BK-001</span>
            </div>

            <div class="flex justify-between border-b border-slate-100 pb-3">
                <span class="text-slate-500">Pemohon</span>
                <span class="font-bold">Pak Budi</span>
            </div>

            <div class="flex justify-between border-b border-slate-100 pb-3">
                <span class="text-slate-500">Role</span>
                <span class="font-bold">Dosen</span>
            </div>

            <div class="flex justify-between border-b border-slate-100 pb-3">
                <span class="text-slate-500">Ruang</span>
                <span class="font-bold">R-201</span>
            </div>

            <div class="flex justify-between border-b border-slate-100 pb-3">
                <span class="text-slate-500">Kegiatan</span>
                <span class="font-bold">Kelas Pengganti</span>
            </div>

            <div class="flex justify-between border-b border-slate-100 pb-3">
                <span class="text-slate-500">Waktu</span>
                <span class="font-bold">03 Mei 2026, 08.00 - 10.00</span>
            </div>

            <div class="flex justify-between border-b border-slate-100 pb-3">
                <span class="text-slate-500">Status</span>
                <span class="font-bold text-emerald-600">Approved</span>
            </div>

            <div>
                <p class="text-slate-500 mb-2">Tujuan Penggunaan</p>
                <div class="bg-slate-50 rounded-xl p-4 text-slate-700">
                    Digunakan untuk kelas pengganti karena jadwal sebelumnya bentrok dengan kegiatan jurusan.
                </div>
            </div>

        </div>

    </div>
</div>

@push('scripts')
<script>
    function filterBookings() {
        let keyword = $('#searchBooking').val().toLowerCase();
        let status = $('#filterStatus').val();
        let date = $('#filterDate').val();

        $('.booking-row').each(function () {
            let searchData = $(this).data('search');
            let rowStatus = $(this).data('status');
            let rowDate = $(this).data('date');

            let matchKeyword = searchData.includes(keyword);
            let matchStatus = status === '' || rowStatus === status;
            let matchDate = date === '' || rowDate === date;

            if (matchKeyword && matchStatus && matchDate) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }

    $('#searchBooking').on('keyup', filterBookings);
    $('#filterStatus').on('change', filterBookings);
    $('#filterDate').on('change', filterBookings);

    $('.btn-detail').on('click', function () {
        $('#bookingModal').removeClass('hidden');
    });

    $('#btnCloseModal').on('click', function () {
        $('#bookingModal').addClass('hidden');
    });

    $('#bookingModal').on('click', function (e) {
        if (e.target.id === 'bookingModal') {
            $('#bookingModal').addClass('hidden');
        }
    });

    $('.btn-checkin').on('click', function () {
        if (confirm('Lakukan check-in untuk booking ini?')) {
            alert('Check-in berhasil. Ini masih GUI, belum terhubung database.');
        }
    });

    $('.btn-cancel').on('click', function () {
        let reason = prompt('Masukkan alasan pembatalan booking:');

        if (reason) {
            alert('Booking dibatalkan dengan alasan: ' + reason);
        }
    });
</script>
@endpush

@endsection
