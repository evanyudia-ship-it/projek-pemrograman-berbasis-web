@extends('layouts.app')

@section('title', 'Reputation Point')
@section('page_title', 'Reputation Point')
@section('page_subtitle', 'Riwayat penilaian perilaku penggunaan ruang')

@section('content')

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <h3 class="font-bold text-lg mb-1">Point Saya</h3>
        <p class="text-sm text-slate-500 mb-6">Status kepercayaan pengguna</p>

        @php
        // TODO: hapus ini setelah database reputation terisi
        $rp = $profileData->point ?? 85; // dummy 85 untuk test


        // Tentukan warna berdasarkan range
        if ($rp >= 80) {
            $rpColor = '#10b981'; // hijau
        } elseif ($rp >= 50) {
            $rpColor = '#3b82f6'; // biru
        } elseif ($rp >= 30) {
            $rpColor = '#f59e0b'; // kuning
        } else {
            $rpColor = '#ef4444'; // merah
        }
    @endphp

    <div class="flex items-center justify-center">
        <div class="w-44 h-44 rounded-full flex items-center justify-center"
             style="border: 8px solid {{ $rpColor }}">
            <div class="text-center">
                <p class="text-5xl font-extrabold" style="color: {{ $rpColor }}">{{ $rp }}</p>
                <p class="text-xs text-slate-500">Point</p>
            </div>
        </div>
    </div>

        <div class="mt-6 text-center">
            <span class="px-4 py-2 rounded-full bg-emerald-100 text-emerald-700 text-sm font-bold">
                Trusted User
            </span> {{-- TODO: ganti $reputation->status --}}
        </div>

        <div class="mt-6 space-y-3 text-sm">
            <div class="flex justify-between">
                <span class="text-slate-500">Hak Booking</span>
                <span class="font-bold text-emerald-600">Aktif</span>
            </div>

            <div class="flex justify-between">
                <span class="text-slate-500">Batasan</span>
                <span class="font-bold">Normal</span>
            </div>

            <div class="flex justify-between">
                <span class="text-slate-500">Pelanggaran</span>
                <span class="font-bold text-red-600">0</span> {{-- TODO: ganti $reputation->violation --}}
            </div>
        </div>
    </div>

    <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

        <div class="p-6 border-b border-slate-200">
            <h3 class="font-bold text-lg">Riwayat Point</h3>
            <p class="text-sm text-slate-500">Catatan penambahan dan pengurangan point</p>
        </div>

        <div class="overflow-x-auto">
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
                    <tr>
                        <td class="px-6 py-4">03 Mei 2026</td>
                        <td class="px-6 py-4">Menggunakan ruang sesuai jadwal</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">
                                Reward
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right font-bold text-emerald-600">+5</td>
                    </tr>

                    <tr>
                        <td class="px-6 py-4">02 Mei 2026</td>
                        <td class="px-6 py-4">Check-in tepat waktu</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">
                                Reward
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right font-bold text-emerald-600">+3</td>
                    </tr>

                    <tr>
                        <td class="px-6 py-4">29 April 2026</td>
                        <td class="px-6 py-4">Membatalkan booking mendadak</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-bold">
                                Penalty
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right font-bold text-red-600">-5</td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>

</div>

<div class="mt-6 bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
    <h3 class="font-bold text-lg mb-4">Aturan Reputation</h3>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-100">
            <p class="font-bold text-emerald-700">80 - 100</p>
            <p class="text-slate-600 mt-1">Trusted user, proses approval lebih mudah.</p>
        </div>

        <div class="p-4 rounded-xl bg-blue-50 border border-blue-100">
            <p class="font-bold text-blue-700">50 - 79</p>
            <p class="text-slate-600 mt-1">Normal, dapat booking seperti biasa.</p>
        </div>

        <div class="p-4 rounded-xl bg-yellow-50 border border-yellow-100">
            <p class="font-bold text-yellow-700">30 - 49</p>
            <p class="text-slate-600 mt-1">Booking dibatasi sementara.</p>
        </div>

        <div class="p-4 rounded-xl bg-red-50 border border-red-200">
            <p class="font-bold text-red-700">&lt; 30</p>
            <p class="text-yellow-600 mt-1">Tidak bisa booking sementara.</p>
        </div>
    </div>
</div>

@endsection
