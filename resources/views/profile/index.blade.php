@extends('layouts.app')

<<<<<<< HEAD
@section('title', 'Profil User')
@section('page_title', 'Profil & Reputation')
@section('page_subtitle', 'Informasi akun, status, dan point pengguna')
=======
@section('title', 'Profil Saya')
@section('page_title', 'Profil Saya')
@section('page_subtitle', 'Informasi akun dan status pengguna')
>>>>>>> 476107a9d746a5dfbaf61610547d77df3a893d89

@section('content')

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

<<<<<<< HEAD
    {{-- PROFIL --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">

        <div class="flex flex-col items-center text-center">
            <div class="w-24 h-24 rounded-full bg-blue-600 text-white flex items-center justify-center text-3xl font-bold">
                A
            </div>

            <h2 class="mt-4 text-xl font-bold">Admin Kampus</h2>
            <p class="text-sm text-slate-500">admin@kampus.ac.id</p>

            <span class="mt-3 px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">
                Aktif
=======
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <div class="flex flex-col items-center text-center">
            <div class="w-28 h-28 rounded-full bg-blue-600 text-white flex items-center justify-center text-4xl font-extrabold">
                M
            </div>

            <h3 class="text-xl font-extrabold mt-4">Mahasiswa Demo</h3>
            <p class="text-slate-500 text-sm">mahasiswa@kampus.ac.id</p>

            <span class="mt-4 px-4 py-2 rounded-full bg-blue-100 text-blue-700 text-xs font-bold">
                Role: Mahasiswa
>>>>>>> 476107a9d746a5dfbaf61610547d77df3a893d89
            </span>
        </div>

        <div class="mt-6 space-y-3 text-sm">
<<<<<<< HEAD

            <div class="flex justify-between">
                <span class="text-slate-500">Role</span>
                <span class="font-semibold">Admin</span>
            </div>

            <div class="flex justify-between">
                <span class="text-slate-500">Bergabung</span>
                <span class="font-semibold">Jan 2026</span>
            </div>

            <div class="flex justify-between">
                <span class="text-slate-500">Status Akun</span>
                <span class="font-semibold text-emerald-600">Aktif</span>
            </div>

        </div>

    </div>

    {{-- POINT --}}
    <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-200 p-6">

        <h3 class="font-bold text-lg">Reputation Point</h3>
        <p class="text-sm text-slate-500 mb-6">Sistem penilaian perilaku pengguna</p>

        <div class="flex items-center justify-center mb-6">
            <div class="w-44 h-44 rounded-full border-[14px] border-blue-600 flex items-center justify-center">
                <div class="text-center">
                    <p class="text-5xl font-extrabold">85</p>
                    <p class="text-xs text-slate-500">Point</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">

            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-100">
                <p class="text-emerald-700 font-bold">Status</p>
                <p class="mt-1 font-semibold">Trusted User</p>
            </div>

            <div class="p-4 rounded-xl bg-yellow-50 border border-yellow-100">
                <p class="text-yellow-700 font-bold">Booking Aktif</p>
                <p class="mt-1 font-semibold">3</p>
            </div>

            <div class="p-4 rounded-xl bg-red-50 border border-red-100">
                <p class="text-red-700 font-bold">Pelanggaran</p>
                <p class="mt-1 font-semibold">0</p>
=======
            <div class="flex justify-between">
                <span class="text-slate-500">Status Akun</span>
                <span class="font-bold text-emerald-600">Aktif</span>
            </div>

            <div class="flex justify-between">
                <span class="text-slate-500">Verifikasi</span>
                <span class="font-bold text-emerald-600">Terverifikasi</span>
            </div>

            <div class="flex justify-between">
                <span class="text-slate-500">Reputation</span>
                <span class="font-bold text-blue-600">85 Point</span>
            </div>
        </div>
    </div>

    <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <h3 class="font-bold text-lg mb-5">Detail Informasi</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            <div>
                <label class="text-sm font-semibold text-slate-700">Nama Lengkap</label>
                <input type="text" value="Mahasiswa Demo" class="mt-2 w-full rounded-xl">
            </div>

            <div>
                <label class="text-sm font-semibold text-slate-700">Email</label>
                <input type="email" value="mahasiswa@kampus.ac.id" class="mt-2 w-full rounded-xl">
            </div>

            <div>
                <label class="text-sm font-semibold text-slate-700">NIM / NIP</label>
                <input type="text" value="20260001" class="mt-2 w-full rounded-xl">
            </div>

            <div>
                <label class="text-sm font-semibold text-slate-700">No. HP</label>
                <input type="text" value="08123456789" class="mt-2 w-full rounded-xl">
            </div>

            <div>
                <label class="text-sm font-semibold text-slate-700">Role</label>
                <input type="text" value="Mahasiswa" class="mt-2 w-full rounded-xl bg-slate-100" readonly>
            </div>

            <div>
                <label class="text-sm font-semibold text-slate-700">Organisasi</label>
                <input type="text" value="Belum mewakili organisasi" class="mt-2 w-full rounded-xl bg-slate-100" readonly>
>>>>>>> 476107a9d746a5dfbaf61610547d77df3a893d89
            </div>

        </div>

<<<<<<< HEAD
        <div class="mt-6 p-4 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-600">
            <p class="font-semibold mb-1">Status Akun:</p>
            <p>Akun aktif dan dapat melakukan booking tanpa batasan.</p>
        </div>

=======
        <div class="mt-6 flex justify-end">
            <button class="px-5 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold">
                Simpan Perubahan
            </button>
        </div>
>>>>>>> 476107a9d746a5dfbaf61610547d77df3a893d89
    </div>

</div>

<<<<<<< HEAD
@endsection
=======
@endsection
>>>>>>> 476107a9d746a5dfbaf61610547d77df3a893d89
