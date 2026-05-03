@extends('layouts.app')

@section('title', 'Dashboard - Smart Classroom')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Ringkasan ketersediaan dan penggunaan ruang kelas')

@section('content')

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm text-slate-500">Total Ruang</p>
                <h3 class="text-3xl font-extrabold mt-2">24</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center text-xl">
                🏫
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm text-slate-500">Ruang Tersedia</p>
                <h3 class="text-3xl font-extrabold mt-2">16</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl">
                ✅
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm text-slate-500">Booking Pending</p>
                <h3 class="text-3xl font-extrabold mt-2">7</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-yellow-100 text-yellow-600 flex items-center justify-center text-xl">
                ⏳
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm text-slate-500">No Show</p>
                <h3 class="text-3xl font-extrabold mt-2">2</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-red-100 text-red-600 flex items-center justify-center text-xl">
                ⚠️
            </div>
        </div>
    </div>

</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-200 flex justify-between items-center">
            <div>
                <h3 class="font-bold text-lg">Jadwal Booking Hari Ini</h3>
                <p class="text-sm text-slate-500">Daftar penggunaan ruang terbaru</p>
            </div>
            <a href="{{ route('bookings.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-sm font-semibold">
                + Booking
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-slate-500">
                <tr>
                    <th class="text-left px-6 py-4">Ruang</th>
                    <th class="text-left px-6 py-4">Pengguna</th>
                    <th class="text-left px-6 py-4">Waktu</th>
                    <th class="text-left px-6 py-4">Status</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                <tr>
                    <td class="px-6 py-4 font-semibold">R-201</td>
                    <td class="px-6 py-4">Dosen - Pak Andi</td>
                    <td class="px-6 py-4">08.00 - 10.00</td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">Approved</span>
                    </td>
                </tr>
                <tr>
                    <td class="px-6 py-4 font-semibold">Lab Komputer</td>
                    <td class="px-6 py-4">Organisasi BEM</td>
                    <td class="px-6 py-4">13.00 - 16.00</td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-bold">Pending</span>
                    </td>
                </tr>
                <tr>
                    <td class="px-6 py-4 font-semibold">R-105</td>
                    <td class="px-6 py-4">Mahasiswa</td>
                    <td class="px-6 py-4">18.00 - 20.00</td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-bold">Checked In</span>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <h3 class="font-bold text-lg mb-1">Reputation Point</h3>
        <p class="text-sm text-slate-500 mb-6">Kontrol perilaku pengguna</p>

        <div class="flex items-center justify-center">
            <div class="w-40 h-40 rounded-full border-[12px] border-blue-600 flex items-center justify-center">
                <div class="text-center">
                    <p class="text-4xl font-extrabold">85</p>
                    <p class="text-xs text-slate-500">Point</p>
                </div>
            </div>
        </div>

        <div class="mt-6 space-y-3 text-sm">
            <div class="flex justify-between">
                <span class="text-slate-500">Status</span>
                <span class="font-bold text-emerald-600">Trusted</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Booking aktif</span>
                <span class="font-bold">3</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Pelanggaran</span>
                <span class="font-bold text-red-600">0</span>
            </div>
        </div>
    </div>

</div>

@endsection
