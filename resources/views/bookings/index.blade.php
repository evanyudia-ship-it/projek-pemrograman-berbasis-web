@extends('layouts.app')

@section('title', 'Riwayat Booking')
@section('page_title', 'Riwayat Booking')
@section('page_subtitle', 'Daftar pengajuan dan penggunaan ruang')

@section('content')

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

    <div class="p-6 border-b border-slate-200 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h3 class="font-bold text-lg">Data Booking</h3>
            <p class="text-sm text-slate-500">Pantau status booking ruang kelas</p>
        </div>

        <a href="{{ route('bookings.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl text-sm font-semibold text-center">
            + Ajukan Booking
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-500">
            <tr>
                <th class="text-left px-6 py-4">Kode</th>
                <th class="text-left px-6 py-4">Ruang</th>
                <th class="text-left px-6 py-4">Kegiatan</th>
                <th class="text-left px-6 py-4">Waktu</th>
                <th class="text-left px-6 py-4">Status</th>
                <th class="text-center px-6 py-4">Aksi</th>
            </tr>
            </thead>

            <tbody class="divide-y divide-slate-100">
            <tr>
                <td class="px-6 py-4 font-bold">BK-001</td>
                <td class="px-6 py-4">R-201</td>
                <td class="px-6 py-4">Kelas Pengganti</td>
                <td class="px-6 py-4">03 Mei 2026, 08.00 - 10.00</td>
                <td class="px-6 py-4">
                    <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">Approved</span>
                </td>
                <td class="px-6 py-4 text-center">
                    <button class="px-3 py-2 rounded-lg bg-blue-100 text-blue-700 font-semibold">Check-in</button>
                </td>
            </tr>

            <tr>
                <td class="px-6 py-4 font-bold">BK-002</td>
                <td class="px-6 py-4">LAB-01</td>
                <td class="px-6 py-4">Rapat Organisasi</td>
                <td class="px-6 py-4">03 Mei 2026, 13.00 - 16.00</td>
                <td class="px-6 py-4">
                    <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-bold">Pending</span>
                </td>
                <td class="px-6 py-4 text-center">
                    <button class="px-3 py-2 rounded-lg bg-red-100 text-red-700 font-semibold">Cancel</button>
                </td>
            </tr>

            <tr>
                <td class="px-6 py-4 font-bold">BK-003</td>
                <td class="px-6 py-4">R-105</td>
                <td class="px-6 py-4">Belajar Kelompok</td>
                <td class="px-6 py-4">02 Mei 2026, 18.00 - 20.00</td>
                <td class="px-6 py-4">
                    <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-bold">Completed</span>
                </td>
                <td class="px-6 py-4 text-center">
                    <button class="px-3 py-2 rounded-lg bg-slate-100 text-slate-600 font-semibold">Detail</button>
                </td>
            </tr>
            </tbody>
        </table>
    </div>

</div>

@endsection
