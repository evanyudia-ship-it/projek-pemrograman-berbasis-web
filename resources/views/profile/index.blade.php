@extends('layouts.app')

@section('title', 'Profil - ' . $user->name)
@section('page_title', 'Profil & Reputation')
@section('page_subtitle', 'Informasi akun, status, dan aktivitas pengguna')

@section('content')

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    {{-- ══ KARTU PROFIL ══ --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">

        <div class="flex flex-col items-center text-center">

            {{-- Avatar --}}
            <div class="w-24 h-24 rounded-full bg-blue-600 text-white flex items-center justify-center text-3xl font-bold">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>

            <h2 class="mt-4 text-xl font-bold text-slate-900">{{ $user->name }}</h2>
            <p class="text-sm text-slate-500">{{ $user->email }}</p>

            {{-- Badge role --}}
            @php
                $badgeClass = match(session('user_role')) {
                    'superadmin' => 'bg-purple-100 text-purple-700',
                    'admin' => 'bg-blue-200 text-blue-700',
                    'dosen' => 'bg-teal-300 text-teal-700',
                    default => 'bg-emerald-100 text-emerald-700',
                };
            @endphp

            <span class="mt-3 px-3 py-1 rounded-full text-xs font-bold {{ $badgeClass }}">
                {{ $profileData->badge }} {{ $user->role }}
            </span>

        </div>

        {{-- Info rows --}}
        <div class="mt-6 space-y-3 text-sm">

            <div class="flex justify-between">
                <span class="text-slate-500">Role</span>
                <span class="font-semibold">{{ $user->role }}</span>
            </div>

            <div class="flex justify-between">
                <span class="text-slate-500">Bergabung</span>
                <span class="font-semibold">Jan 2026</span>
            </div>

            <div class="flex justify-between">
                <span class="text-slate-500">Status Akun</span>
                <span class="font-semibold text-emerald-600">Aktif</span>
            </div>

            <div class="flex justify-between items-center">
                <span class="text-slate-500">Verifikasi Email</span>
                @if(session('is_verified', false))
                    <span class="font-semibold text-emerald-600">Terverifikasi ✓</span>
                @else
                    <span class="font-semibold text-amber-600">Belum Terverifikasi</span>
                @endif
            </div>


        </div>

        {{-- Status Verifikasi Box --}}
        @if(!session('is_verified', false))
        <div class="mt-6 p-4 rounded-2xl bg-amber-50 border border-amber-200">
            <p class="text-amber-700 font-semibold">Akun belum diverifikasi</p>
            <p class="text-sm text-amber-600 mt-1 mb-3">
                Verifikasi akun untuk dapat melakukan booking ruangan.
            </p>
            <a href="{{ route('verify.show') }}" 
            class="block w-full py-2.5 text-center bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-sm font-semibold transition">
                Verifikasi Akun Sekarang →
            </a>
        </div>
        @else
        <div class="mt-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-center">
            <p class="text-emerald-700 font-semibold">✓ Akun sudah terverifikasi</p>
        </div>
        @endif

        {{-- Reputation hanya untuk mahasiswa & dosen --}}
        @if(in_array(session('user_role'), ['mahasiswa', 'dosen']))
        <div class="flex justify-between mt-4">
            <span class="text-slate-500">Reputation</span>
            <span class="font-semibold text-blue-600">{{ $profileData->point }} Point</span>
        </div>
        @endif

        {{-- Deskripsi role --}}
        <div class="mt-5 p-3 rounded-xl bg-slate-50 border border-slate-100 text-xs text-slate-500 leading-relaxed">
            {{ $profileData->description }}
        </div>

    </div>

    {{-- ══ KOLOM KANAN ══ --}}
    <div class="xl:col-span-2 space-y-6">

        {{-- STAT CARDS (berbeda per role) --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($profileData->stats as $stat)
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 text-center">
                <p class="text-2xl mb-1">{{ $stat['icon'] }}</p>
                <p class="text-2xl font-bold text-slate-900">{{ $stat['value'] }}</p>
                <p class="text-xs text-slate-500 mt-0.5">{{ $stat['label'] }}</p>
            </div>
            @endforeach
        </div>

        {{-- REPUTATION (hanya mahasiswa & dosen) --}}
        @if(in_array(session('user_role'), ['mahasiswa', 'dosen']))
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">

            <h3 class="font-bold text-lg">Reputation Point</h3>
            <p class="text-sm text-slate-500 mb-6">Sistem penilaian perilaku pengguna</p>

            <div class="flex items-center justify-center mb-6">
                @php
                    $rp = $profileData->point;
                    $rpColor = $rp >= 80 ? '#10b981' : ($rp >= 50 ? '#3b82f6' : ($rp >= 30 ? '#f59e0b' : '#ef4444'));
                @endphp
                <div class="w-44 h-44 rounded-full flex items-center justify-center"
                     style="border: 10px solid {{ $rpColor }}">
                    <div class="text-center">
                        <p class="text-5xl font-extrabold" style="color: {{ $rpColor }}">{{ $rp }}</p>
                        <p class="text-xs text-slate-500">Point</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-100">
                    <p class="text-emerald-700 font-bold">Status</p>
                    <p class="mt-1 font-semibold">{{ $profileData->status }}</p>
                </div>
                <div class="p-4 rounded-xl bg-yellow-50 border border-yellow-100">
                    <p class="text-yellow-700 font-bold">Booking Aktif</p>
                    <p class="mt-1 font-semibold">{{ $profileData->booking_active }}</p>
                </div>
                <div class="p-4 rounded-xl bg-red-50 border border-red-100">
                    <p class="text-red-700 font-bold">Pelanggaran</p>
                    <p class="mt-1 font-semibold">{{ $profileData->violation }}</p>
                </div>
            </div>

            <div class="mt-4 p-4 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-600">
                <p class="font-semibold mb-1">Keterangan:</p>
                <p>{{ $profileData->description }}</p>
            </div>

        </div>
        @endif

        {{-- PANEL KHUSUS ADMIN & SUPERADMIN --}}
        @if(in_array(session('user_role'), ['admin', 'superadmin']))
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h3 class="font-bold text-lg mb-1">Status Validator</h3>
            <p class="text-sm text-slate-500 mb-5">Ringkasan aktivitas sebagai {{ $user->role }}</p>

            @php
                $panelClass = session('user_role') === 'superadmin'
                    ? 'bg-purple-50 border-purple-100 text-purple-700'
                    : 'bg-blue-50 border-blue-100 text-blue-700';
            @endphp

            <div class="p-4 rounded-xl border text-sm {{ $panelClass }}">
        </div>
        @endif

        {{-- RIWAYAT AKTIVITAS --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">

            <h3 class="font-bold text-lg mb-5">Riwayat Aktivitas</h3>

            <div class="space-y-3">
                @foreach($activities as $act)
                <div class="flex items-start gap-3 p-3 rounded-xl hover:bg-slate-50 transition">
                    <span class="text-xl shrink-0 mt-0.5">{{ $act['icon'] }}</span>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-slate-700">{{ $act['text'] }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">{{ $act['waktu'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>

        </div>

        {{-- FORM DETAIL INFORMASI --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">

            <h3 class="font-bold text-lg mb-5">Detail Informasi</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <div>
                    <label class="text-sm font-semibold text-slate-700">Nama Lengkap</label>
                    <input type="text" value="{{ $user->name }}" class="mt-2 w-full">
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-700">Email</label>
                    <input type="email" value="{{ $user->email }}" class="mt-2 w-full">
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-700">
                        {{ in_array(session('user_role'), ['mahasiswa']) ? 'NIM' : 'NIP' }}
                    </label>
                    <input type="text"
                           value="{{ session('user_role') === 'mahasiswa' ? '20260001' : '199001012020121001' }}"
                           class="mt-2 w-full">
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-700">No. HP</label>
                    <input type="text" value="08123456789" class="mt-2 w-full">
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-700">Role</label>
                    <input type="text" value="{{ $user->role }}" class="mt-2 w-full" readonly>
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-700">
                        {{ session('user_role') === 'mahasiswa' ? 'Organisasi' : 'Departemen' }}
                    </label>
                    <input type="text"
                           value="{{ session('user_role') === 'mahasiswa' ? 'Belum mewakili organisasi' : 'Teknik Informatika' }}"
                           class="mt-2 w-full"
                           {{ in_array(session('user_role'), ['admin', 'superadmin']) ? 'readonly' : '' }}>
                </div>

            </div>

            <div class="mt-6 flex justify-end">
                <button class="px-5 py-3 rounded-xl bg-blue-500 hover:bg-slate-800 text-white font-semibold transition">
                    Simpan Perubahan
                </button>
            </div>

        </div>

    </div>

</div>

@endsection