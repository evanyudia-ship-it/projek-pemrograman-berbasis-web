@extends('layouts.app')

@section('title', 'Edit Fakultas')
@section('page_title', 'Edit Fakultas')
@section('page_subtitle', 'Perbarui data fakultas')

@section('content')

<div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-sm border border-slate-200 p-6">

    <div class="mb-6">
        <h3 class="text-xl font-bold text-slate-800">Form Edit Fakultas</h3>
        <p class="text-sm text-slate-500">Perbarui nama, kode, deskripsi, dan status fakultas.</p>
    </div>

    @if($errors->any())
        <div class="mb-5 rounded-xl bg-red-50 border border-red-200 text-red-700 px-4 py-3">
            <ul class="list-disc ml-5 text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.faculties.update', $faculty) }}" class="space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-semibold mb-2">Nama Fakultas</label>
            <input type="text"
                   name="name"
                   value="{{ old('name', $faculty->name) }}"
                   class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                   required>
        </div>

        <div>
            <label class="block text-sm font-semibold mb-2">Kode Fakultas</label>
            <input type="text"
                   name="code"
                   value="{{ old('code', $faculty->code) }}"
                   class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                   required>
        </div>

        <div>
            <label class="block text-sm font-semibold mb-2">Deskripsi</label>
            <textarea name="description"
                      rows="4"
                      class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('description', $faculty->description) }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-semibold mb-2">Status</label>
            <select name="status"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    required>
                <option value="active" {{ old('status', $faculty->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="inactive" {{ old('status', $faculty->status) == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>

        <div class="flex justify-end gap-3 pt-4">
            <a href="{{ route('admin.faculties.index') }}"
               class="px-5 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold">
                Batal
            </a>

            <button type="submit"
                    class="px-5 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold">
                Update Fakultas
            </button>
        </div>
    </form>
</div>

@endsection