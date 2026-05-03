@extends('layouts.app')

@section('title', 'Approval Booking')
@section('page_title', 'Approval Booking')
@section('page_subtitle', 'Validasi pengajuan booking ruang oleh admin atau sekretaris jurusan')

@section('content')

<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
        <p class="text-sm text-slate-500">Menunggu Approval</p>
        <h3 class="text-3xl font-extrabold mt-2">7</h3>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
        <p class="text-sm text-slate-500">Disetujui Hari Ini</p>
        <h3 class="text-3xl font-extrabold mt-2">5</h3>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
        <p class="text-sm text-slate-500">Expired</p>
        <h3 class="text-3xl font-extrabold mt-2">1</h3>
    </div>

</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

    <div class="p-6 border-b border-slate-200">
        <h3 class="font-bold text-lg">Daftar Pengajuan Pending</h3>
        <p class="text-sm text-slate-500">Booking harus diproses maksimal 1 x 24 jam</p>
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
            <tr>
                <td class="px-6 py-4 font-bold">BK-002</td>
                <td class="px-6 py-4">
                    <p class="font-semibold">BEM Kampus</p>
                    <p class="text-xs text-slate-500">Organisasi</p>
                </td>
                <td class="px-6 py-4">LAB-01</td>
                <td class="px-6 py-4">Rapat Organisasi</td>
                <td class="px-6 py-4">03 Mei 2026, 13.00 - 16.00</td>
                <td class="px-6 py-4">
                    <span class="px-3 py-1 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold">Medium</span>
                </td>
                <td class="px-6 py-4">
                    <div class="flex justify-center gap-2">
                        <button class="btn-approve px-3 py-2 rounded-lg bg-emerald-100 text-emerald-700 font-semibold">
                            Approve
                        </button>
                        <button class="btn-reject px-3 py-2 rounded-lg bg-red-100 text-red-700 font-semibold">
                            Reject
                        </button>
                    </div>
                </td>
            </tr>

            <tr>
                <td class="px-6 py-4 font-bold">BK-004</td>
                <td class="px-6 py-4">
                    <p class="font-semibold">Pak Budi</p>
                    <p class="text-xs text-slate-500">Dosen</p>
                </td>
                <td class="px-6 py-4">R-301</td>
                <td class="px-6 py-4">Kelas Pengganti</td>
                <td class="px-6 py-4">04 Mei 2026, 08.00 - 11.00</td>
                <td class="px-6 py-4">
                    <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-bold">High</span>
                </td>
                <td class="px-6 py-4">
                    <div class="flex justify-center gap-2">
                        <button class="btn-approve px-3 py-2 rounded-lg bg-emerald-100 text-emerald-700 font-semibold">
                            Approve
                        </button>
                        <button class="btn-reject px-3 py-2 rounded-lg bg-red-100 text-red-700 font-semibold">
                            Reject
                        </button>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>

</div>

@push('scripts')
<script>
    $('.btn-approve').on('click', function () {
        if (confirm('Setujui booking ini?')) {
            alert('GUI approve berhasil. Nanti disambungkan ke controller.');
        }
    });

    $('.btn-reject').on('click', function () {
        let reason = prompt('Masukkan alasan penolakan:');

        if (reason) {
            alert('GUI reject berhasil dengan alasan: ' + reason);
        }
    });
</script>
@endpush

@endsection
