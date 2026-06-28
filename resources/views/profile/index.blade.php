@extends('layouts.app')

@section('title', 'Profil Saya')
@section('page_title', 'Profil Saya')
@section('page_subtitle', 'Kelola informasi akun dan password')

@section('content')

@if(session('success'))
    <div class="mb-4 rounded-xl bg-green-50 border border-green-200 text-green-700 px-4 py-3">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-4 rounded-xl bg-red-50 border border-red-200 text-red-700 px-4 py-3">
        {{ session('error') }}
    </div>
@endif

@if($errors->any())
    <div class="mb-4 rounded-xl bg-red-50 border border-red-200 text-red-700 px-4 py-3">
        <ul class="list-disc ml-5 text-sm">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    <div class="xl:col-span-1">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 text-center">
            <div class="w-24 h-24 rounded-full bg-slate-900 text-white flex items-center justify-center text-4xl font-bold mx-auto mb-4">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>

            <h3 class="text-xl font-extrabold text-slate-800">
                {{ $user->name }}
            </h3>

            <p class="text-sm text-slate-500">
                {{ $user->email }}
            </p>

            <div class="mt-4 flex justify-center gap-2 flex-wrap">
                <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-bold">
                    {{ ucfirst($user->role) }}
                </span>

                <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-bold">
                    {{ ucfirst($user->status ?? 'active') }}
                </span>
            </div>

            <div class="mt-6 text-left space-y-3 text-sm">
                <div class="flex justify-between gap-3">
                    <span class="text-slate-500">NIM</span>
                    <span class="font-semibold text-slate-800">{{ $user->nim ?? '-' }}</span>
                </div>

                <div class="flex justify-between gap-3">
                    <span class="text-slate-500">NIDN</span>
                    <span class="font-semibold text-slate-800">{{ $user->nidn ?? '-' }}</span>
                </div>

                <div class="flex justify-between gap-3">
                    <span class="text-slate-500">No. HP</span>
                    <span class="font-semibold text-slate-800">{{ $user->phone ?? '-' }}</span>
                </div>

                <div class="flex justify-between gap-3">
                    <span class="text-slate-500">Fakultas</span>
                    <span class="font-semibold text-slate-800 text-right">
                        {{ $user->faculty->name ?? '-' }}
                    </span>
                </div>

                <div class="flex justify-between gap-3">
                    <span class="text-slate-500">Reputasi</span>
                    <span class="font-semibold text-blue-600">
                        {{ $user->reputation_points ?? 0 }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="xl:col-span-2 space-y-6">

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-lg font-bold text-slate-800 mb-1">
                Edit Profil
            </h3>

            <p class="text-sm text-slate-500 mb-5">
                Email akun tidak dapat diubah setelah akun dibuat.
            </p>

            <form method="POST" action="{{ route('profile.update') }}" class="grid grid-cols-1 md:grid-cols-2 gap-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-semibold mb-2">
                        Nama Lengkap
                    </label>
                    <input type="text"
                           name="name"
                           value="{{ old('name', $user->name) }}"
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           required>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-2">
                        Email / Gmail
                    </label>
                    <input type="email"
                           value="{{ $user->email }}"
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-100 text-slate-500 cursor-not-allowed"
                           readonly>
                    <p class="text-xs text-slate-400 mt-1">
                        Email tidak bisa diubah.
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-2">
                        NIM
                    </label>
                    <input type="text"
                           name="nim"
                           value="{{ old('nim', $user->nim) }}"
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-2">
                        NIDN
                    </label>
                    <input type="text"
                           name="nidn"
                           value="{{ old('nidn', $user->nidn) }}"
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-2">
                        No. HP
                    </label>
                    <input type="text"
                           name="phone"
                           value="{{ old('phone', $user->phone) }}"
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-2">
                        Fakultas
                    </label>
                    <select name="faculty_id"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Tidak ada</option>

                        @foreach($faculties as $faculty)
                            <option value="{{ $faculty->id }}"
                                {{ old('faculty_id', $user->faculty_id) == $faculty->id ? 'selected' : '' }}>
                                {{ $faculty->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2 flex justify-end">
                    <button type="submit"
                            class="px-5 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold">
                        Simpan Perubahan Profil
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-lg font-bold text-slate-800 mb-1">
                Ganti Password
            </h3>

            <p class="text-sm text-slate-500 mb-5">
                Masukkan password lama dan password baru.
            </p>

            <form method="POST" action="{{ route('profile.password') }}" class="grid grid-cols-1 md:grid-cols-2 gap-5">
                @csrf
                @method('PUT')

                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold mb-2">
                        Password Lama
                    </label>
                    <input type="password"
                           name="current_password"
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           required>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-2">
                        Password Baru
                    </label>
                    <input type="password"
                           name="password"
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           required>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-2">
                        Konfirmasi Password Baru
                    </label>
                    <input type="password"
                           name="password_confirmation"
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           required>
                </div>

                <div class="md:col-span-2 flex justify-end">
                    <button type="submit"
                            class="px-5 py-3 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold">
                        Update Password
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

@endsection