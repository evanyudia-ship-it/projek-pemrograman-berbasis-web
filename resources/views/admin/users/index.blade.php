@extends('layouts.app')

@section('title', 'Manajemen User')
@section('page_title', 'Manajemen User')
@section('page_subtitle', 'Kelola akun super admin, admin, dosen, dan mahasiswa')

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

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5 mb-6">
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
        <p class="text-sm text-slate-500">Total User</p>
        <h3 class="text-3xl font-extrabold mt-2">{{ $totalUsers ?? 0 }}</h3>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
        <p class="text-sm text-slate-500">Admin</p>
        <h3 class="text-3xl font-extrabold mt-2">{{ $totalAdmin ?? 0 }}</h3>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
        <p class="text-sm text-slate-500">Dosen</p>
        <h3 class="text-3xl font-extrabold mt-2">{{ $totalDosen ?? 0 }}</h3>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
        <p class="text-sm text-slate-500">Mahasiswa</p>
        <h3 class="text-3xl font-extrabold mt-2">{{ $totalMahasiswa ?? 0 }}</h3>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

    <div class="p-6 border-b border-slate-200">
        <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">
            <div>
                <h3 class="font-bold text-lg text-slate-800">Daftar User</h3>
                <p class="text-sm text-slate-500">
                    Filter berdasarkan nama, email, role, dan status akun.
                </p>
            </div>

            <a href="{{ route('admin.users.create') }}"
               class="inline-flex items-center justify-center px-5 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold">
                + Tambah User
            </a>
        </div>

        <form method="GET" action="{{ route('admin.users.index') }}" class="mt-5 grid grid-cols-1 md:grid-cols-4 gap-3">
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Cari nama/email/NIM/NIDN..."
                   class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

            <select name="role"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Semua Role</option>
                <option value="superadmin" {{ request('role') == 'superadmin' ? 'selected' : '' }}>Super Admin</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="dosen" {{ request('role') == 'dosen' ? 'selected' : '' }}>Dosen</option>
                <option value="mahasiswa" {{ request('role') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
            </select>

            <select name="status"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                <option value="banned" {{ request('status') == 'banned' ? 'selected' : '' }}>Banned</option>
            </select>

            <div class="flex gap-2">
                <button type="submit"
                        class="flex-1 px-4 py-3 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold">
                    Filter
                </button>

                <a href="{{ route('admin.users.index') }}"
                   class="px-4 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-500">
                <tr>
                    <th class="text-left px-6 py-4">User</th>
                    <th class="text-left px-6 py-4">NIM/NIDN</th>
                    <th class="text-left px-6 py-4">Fakultas</th>
                    <th class="text-left px-6 py-4">Role</th>
                    <th class="text-left px-6 py-4">Status</th>
                    <th class="text-left px-6 py-4">Reputasi</th>
                    <th class="text-center px-6 py-4">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100">
                @forelse($users as $user)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-11 h-11 rounded-full bg-slate-900 text-white flex items-center justify-center font-bold">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-bold text-slate-800">{{ $user->name }}</p>
                                    <p class="text-xs text-slate-500">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            {{ $user->nim ?? $user->nidn ?? '-' }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $user->faculty->name ?? '-' }}
                        </td>

                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-bold">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>

                        <td class="px-6 py-4">
                            @php
                                $statusClass = match($user->status) {
                                    'active' => 'bg-green-100 text-green-700',
                                    'pending' => 'bg-yellow-100 text-yellow-700',
                                    'banned' => 'bg-red-100 text-red-700',
                                    default => 'bg-slate-100 text-slate-700',
                                };
                            @endphp

                            <span class="px-3 py-1 rounded-full {{ $statusClass }} text-xs font-bold">
                                {{ ucfirst($user->status) }}
                            </span>
                        </td>

                        <td class="px-6 py-4">
                            <span class="font-bold text-blue-600">
                                {{ $user->reputation_points ?? 0 }}
                            </span>
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('admin.users.show', $user) }}"
                                   class="px-3 py-2 rounded-lg bg-slate-100 text-slate-700 font-semibold">
                                    Detail
                                </a>

                                <a href="{{ route('admin.users.edit', $user) }}"
                                   class="px-3 py-2 rounded-lg bg-blue-100 text-blue-700 font-semibold">
                                    Edit
                                </a>

                                <form method="POST"
                                      action="{{ route('admin.users.destroy', $user) }}"
                                      onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="px-3 py-2 rounded-lg bg-red-100 text-red-700 font-semibold">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-slate-500">
                            Belum ada data user.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-6 border-t border-slate-200">
        {{ $users->links() }}
    </div>
</div>

@endsection