@extends('layouts.app')

@section('title', 'Detail Fakultas')
@section('page_title', 'Detail Fakultas')
@section('page_subtitle', $faculty->name)

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <div class="mb-6">
            <h3 class="text-lg font-bold text-slate-800">{{ $faculty->name }}</h3>
            <p class="text-sm text-slate-500">Kode: {{ $faculty->code }}</p>
            <p class="text-sm text-slate-500">Status: {{ $faculty->status == 'active' ? 'Aktif' : 'Nonaktif' }}</p>
            @if($faculty->description)
            <p class="text-sm text-slate-600 mt-2">{{ $faculty->description }}</p>
            @endif
        </div>

        <h4 class="font-bold text-slate-800 mb-4">Daftar User Terdaftar ({{ $faculty->users->count() }})</h4>

        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-500">
                <tr>
                    <th class="text-left px-4 py-3">Nama</th>
                    <th class="text-left px-4 py-3">Email</th>
                    <th class="text-left px-4 py-3">Role</th>
                    <th class="text-left px-4 py-3">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($faculty->users as $user)
                <tr>
                    <td class="px-4 py-3">{{ $user->name }}</td>
                    <td class="px-4 py-3">{{ $user->email }}</td>
                    <td class="px-4 py-3">{{ ucfirst($user->role) }}</td>
                    <td class="px-4 py-3">{{ ucfirst($user->status) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-4 py-6 text-center text-slate-400">
                        Belum ada user terdaftar di fakultas ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-6">
            <a href="{{ route('admin.faculties.index') }}" class="px-4 py-2 bg-slate-100 rounded-xl text-sm font-semibold hover:bg-slate-200 transition">
                ← Kembali
            </a>
        </div>
    </div>
</div>
@endsection
