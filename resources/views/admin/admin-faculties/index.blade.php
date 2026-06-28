@extends('layouts.app')

@section('title', 'Manajemen Fakultas')
@section('page_title', 'Manajemen Fakultas')
@section('page_subtitle', 'Kelola data fakultas pada sistem')

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

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

    <div class="p-6 border-b border-slate-200">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Daftar Fakultas</h3>
                <p class="text-sm text-slate-500">
                    Kelola nama fakultas, kode fakultas, deskripsi, dan status.
                </p>
            </div>

            <a href="{{ route('admin.faculties.create') }}"
               class="inline-flex items-center justify-center px-5 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold">
                + Tambah Fakultas
            </a>
        </div>

        <form method="GET" action="{{ route('admin.faculties.index') }}" class="mt-5 grid grid-cols-1 md:grid-cols-3 gap-3">
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Cari nama atau kode fakultas..."
                   class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

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

                <a href="{{ route('admin.faculties.index') }}"
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
                    <th class="text-left px-6 py-4">Nama Fakultas</th>
                    <th class="text-left px-6 py-4">Kode</th>
                    <th class="text-left px-6 py-4">Deskripsi</th>
                    <th class="text-left px-6 py-4">Jumlah User</th>
                    <th class="text-left px-6 py-4">Status</th>
                    <th class="text-center px-6 py-4">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100">
                @forelse($faculties as $faculty)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4">
                            <p class="font-bold text-slate-800">{{ $faculty->name }}</p>
                        </td>

                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-bold">
                                {{ $faculty->code }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-slate-600">
                            {{ Str::limit($faculty->description ?? '-', 60) }}
                        </td>

                        <td class="px-6 py-4">
                            <span class="font-bold text-slate-800">
                                {{ $faculty->users_count ?? 0 }}
                            </span>
                        </td>

                        <td class="px-6 py-4">
                            @if($faculty->status === 'active')
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
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('admin.faculties.edit', $faculty) }}"
                                   class="px-3 py-2 rounded-lg bg-blue-100 text-blue-700 font-semibold">
                                    Edit
                                </a>

                                <form method="POST"
                                      action="{{ route('admin.faculties.destroy', $faculty) }}"
                                      onsubmit="return confirm('Yakin ingin menghapus fakultas ini?')">
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
                        <td colspan="6" class="px-6 py-10 text-center text-slate-500">
                            Belum ada data fakultas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-6 border-t border-slate-200">
        {{ $faculties->links() }}
    </div>

</div>

@endsection