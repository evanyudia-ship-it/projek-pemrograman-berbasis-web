@extends('layouts.app')

@section('title', 'Detail User')
@section('page_title', 'Detail User')
@section('page_subtitle', 'Informasi lengkap akun user')

@section('content')

<div class="max-w-4xl mx-auto space-y-6">

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-full bg-slate-900 text-white flex items-center justify-center text-2xl font-bold">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>

                <div>
                    <h3 class="text-2xl font-extrabold text-slate-800">{{ $user->name }}</h3>
                    <p class="text-sm text-slate-500">{{ $user->email }}</p>
                </div>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('admin.users.edit', $user) }}"
                   class="px-5 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold">
                    Edit User
                </a>

                <a href="{{ route('admin.users.index') }}"
                   class="px-5 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold">
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <h4 class="text-lg font-bold text-slate-800 mb-4">Data Akun</h4>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-sm">
            <div>
                <p class="text-slate-500">Role</p>
                <p class="font-bold text-slate-800">{{ ucfirst($user->role) }}</p>
            </div>

            <div>
                <p class="text-slate-500">Status</p>
                <p class="font-bold text-slate-800">{{ ucfirst($user->status) }}</p>
            </div>

            <div>
                <p class="text-slate-500">NIM</p>
                <p class="font-bold text-slate-800">{{ $user->nim ?? '-' }}</p>
            </div>

            <div>
                <p class="text-slate-500">NIDN</p>
                <p class="font-bold text-slate-800">{{ $user->nidn ?? '-' }}</p>
            </div>

            <div>
                <p class="text-slate-500">No. HP</p>
                <p class="font-bold text-slate-800">{{ $user->phone ?? '-' }}</p>
            </div>

            <div>
                <p class="text-slate-500">Fakultas</p>
                <p class="font-bold text-slate-800">{{ $user->faculty->name ?? '-' }}</p>
            </div>

            <div>
                <p class="text-slate-500">Poin Reputasi</p>
                <p class="font-bold text-blue-600">{{ $user->reputation_points ?? 0 }}</p>
            </div>

            <div>
                <p class="text-slate-500">Tanggal Daftar</p>
                <p class="font-bold text-slate-800">
                    {{ $user->created_at ? $user->created_at->format('d M Y H:i') : '-' }}
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <h4 class="text-lg font-bold text-slate-800 mb-4">Fakultas yang Dikelola</h4>

        @if($user->managedFaculties && $user->managedFaculties->count())
            <div class="space-y-3">
                @foreach($user->managedFaculties as $faculty)
                    <div class="p-4 rounded-xl border border-slate-200 flex justify-between">
                        <div>
                            <p class="font-bold text-slate-800">{{ $faculty->name }}</p>
                            <p class="text-sm text-slate-500">{{ $faculty->code }}</p>
                        </div>
                        <span class="text-sm font-semibold text-blue-600">
                            {{ $faculty->pivot->position ?? 'Admin Fakultas' }}
                        </span>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-slate-500">User ini belum menjadi admin fakultas.</p>
        @endif
    </div>

</div>

@endsection