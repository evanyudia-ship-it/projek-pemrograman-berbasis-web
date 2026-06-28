@extends('layouts.app')

@section('title', 'Admin Fakultas')
@section('page_title', 'Admin Fakultas')
@section('page_subtitle', 'Kelola admin atau validator pada setiap fakultas')

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
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-lg font-bold text-slate-800">Tambah Admin Fakultas</h3>
            <p class="text-sm text-slate-500 mb-5">
                Pilih user admin dan fakultas yang akan dikelola.
            </p>

            <form method="POST" action="{{ route('admin.admin-faculties.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-semibold mb-2">Admin / Validator</label>
                    <select name="user_id"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required>
                        <option value="">Pilih Admin</option>
                        @foreach($admins as $admin)
                            <option value="{{ $admin->id }}" {{ old('user_id') == $admin->id ? 'selected' : '' }}>
                                {{ $admin->name }} - {{ ucfirst($admin->role) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-2">Fakultas</label>
                    <select name="faculty_id"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required>
                        <option value="">Pilih Fakultas</option>
                        @foreach($faculties as $faculty)
                            <option value="{{ $faculty->id }}" {{ old('faculty_id') == $faculty->id ? 'selected' : '' }}>
                                {{ $faculty->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-2">Jabatan</label>
                    <input type="text"
                           name="position"
                           value="{{ old('position') }}"
                           placeholder="Contoh: Validator Fakultas"
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-2">Status</label>
                    <select name="status"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required>
                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>

                <button type="submit"
                        class="w-full px-5 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold">
                    Simpan Admin Fakultas
                </button>
            </form>
        </div>
    </div>

    <div class="xl:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

            <div class="p-6 border-b border-slate-200">
                <h3 class="text-lg font-bold text-slate-800">Daftar Admin Fakultas</h3>
                <p class="text-sm text-slate-500">
                    Data admin yang memiliki akses validasi pada fakultas tertentu.
                </p>

                <form method="GET" action="{{ route('admin.admin-faculties.index') }}" class="mt-5 grid grid-cols-1 md:grid-cols-3 gap-3">
                    <select name="faculty_id"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Fakultas</option>
                        @foreach($faculties as $faculty)
                            <option value="{{ $faculty->id }}" {{ request('faculty_id') == $faculty->id ? 'selected' : '' }}>
                                {{ $faculty->name }}
                            </option>
                        @endforeach
                    </select>

                    <select name="status"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>

                    <div class="flex gap-2">
                        <button type="submit"
                                class="flex-1 px-4 py-3 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold">
                            Filter
                        </button>

                        <a href="{{ route('admin.admin-faculties.index') }}"
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
                            <th class="text-left px-6 py-4">Admin</th>
                            <th class="text-left px-6 py-4">Fakultas</th>
                            <th class="text-left px-6 py-4">Jabatan</th>
                            <th class="text-left px-6 py-4">Status</th>
                            <th class="text-center px-6 py-4">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse($adminFaculties as $item)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4">
                                    <p class="font-bold text-slate-800">{{ $item->user->name ?? '-' }}</p>
                                    <p class="text-xs text-slate-500">{{ $item->user->email ?? '-' }}</p>
                                </td>

                                <td class="px-6 py-4">
                                    <p class="font-bold text-slate-800">{{ $item->faculty->name ?? '-' }}</p>
                                    <p class="text-xs text-slate-500">{{ $item->faculty->code ?? '-' }}</p>
                                </td>

                                <td class="px-6 py-4">
                                    {{ $item->position ?? 'Admin Fakultas' }}
                                </td>

                                <td class="px-6 py-4">
                                    @if($item->status === 'active')
                                        <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-bold">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-bold">
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex justify-center">
                                        <form method="POST"
                                              action="{{ route('admin.admin-faculties.destroy', $item) }}"
                                              onsubmit="return confirm('Yakin ingin menghapus admin fakultas ini?')">
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
                                <td colspan="5" class="px-6 py-10 text-center text-slate-500">
                                    Belum ada data admin fakultas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-6 border-t border-slate-200">
                {{-- ===== PERBAIKAN: $faculties -> $adminFaculties ===== --}}
                {{ $adminFaculties->links() }}
            </div>

        </div>
    </div>

</div>

@endsection
