@extends('layouts.app')

@section('title', 'Profil Saya')
@section('page_title', 'Profil Saya')
@section('page_subtitle', 'Informasi akun dan status pengguna')

@section('content')

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <div class="flex flex-col items-center text-center">
            <div class="w-28 h-28 rounded-full bg-blue-600 text-white flex items-center justify-center text-4xl font-extrabold">
                M
            </div>

            <h3 class="text-xl font-extrabold mt-4">Mahasiswa Demo</h3>
            <p class="text-slate-500 text-sm">mahasiswa@kampus.ac.id</p>

            <span class="mt-4 px-4 py-2 rounded-full bg-blue-100 text-blue-700 text-xs font-bold">
                Role: Mahasiswa
            </span>
        </div>

        <div class="mt-6 space-y-3 text-sm">
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
            </div>

        </div>

        <div class="mt-6 flex justify-end">
            <button class="px-5 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold">
                Simpan Perubahan
            </button>
        </div>
    </div>

</div>

@endsection
