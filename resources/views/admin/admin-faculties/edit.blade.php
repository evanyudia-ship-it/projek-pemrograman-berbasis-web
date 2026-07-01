@extends('layouts.app')

@section('title', 'Edit Admin Fakultas')
@section('page_title', 'Edit Admin Fakultas')
@section('page_subtitle', 'Perbarui data admin fakultas')

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

<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">

        <div class="mb-6">
            <h3 class="text-xl font-bold text-slate-800">Edit Admin Fakultas</h3>
            <p class="text-sm text-slate-500">Perbarui data admin yang mengelola fakultas.</p>
        </div>

        <form method="POST" action="{{ route('admin.admin-faculties.update', $adminFaculty) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-semibold mb-2">Admin / Validator</label>
                <select name="user_id"
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        required>
                    <option value="">Pilih Admin</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id', $adminFaculty->user_id) == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} - {{ ucfirst($user->role) }}
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
                        <option value="{{ $faculty->id }}" {{ old('faculty_id', $adminFaculty->faculty_id) == $faculty->id ? 'selected' : '' }}>
                            {{ $faculty->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Jabatan</label>
                <input type="text"
                       name="position"
                       value="{{ old('position', $adminFaculty->position) }}"
                       placeholder="Contoh: Validator Fakultas"
                       class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Status</label>
                <select name="status"
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        required>
                    <option value="active" {{ old('status', $adminFaculty->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ old('status', $adminFaculty->status) == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('admin.admin-faculties.index') }}"
                   class="px-5 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold">
                    Batal
                </a>

                <button type="submit"
                        class="px-5 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold">
                    Update Admin Fakultas
                </button>
            </div>
        </form>

    </div>
</div>

@endsection
