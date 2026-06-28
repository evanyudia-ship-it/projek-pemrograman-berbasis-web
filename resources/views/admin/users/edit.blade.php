@extends('layouts.app')

@section('title', 'Edit User')
@section('page_title', 'Edit User')
@section('page_subtitle', 'Perbarui data akun user')

@section('content')

<div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-sm border border-slate-200 p-6">

    <div class="mb-6">
        <h3 class="text-xl font-bold text-slate-800">Form Edit User</h3>
        <p class="text-sm text-slate-500">Kosongkan password jika tidak ingin mengganti password.</p>
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

    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="grid grid-cols-1 md:grid-cols-2 gap-5">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-semibold mb-2">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                   class="w-full px-4 py-3 rounded-xl border border-slate-200" required>
        </div>

        <div>
            <label class="block text-sm font-semibold mb-2">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                   class="w-full px-4 py-3 rounded-xl border border-slate-200" required>
        </div>

        <div>
            <label class="block text-sm font-semibold mb-2">Password Baru</label>
            <input type="password" name="password"
                   class="w-full px-4 py-3 rounded-xl border border-slate-200">
        </div>

        <div>
            <label class="block text-sm font-semibold mb-2">Konfirmasi Password Baru</label>
            <input type="password" name="password_confirmation"
                   class="w-full px-4 py-3 rounded-xl border border-slate-200">
        </div>

        <div>
            <label class="block text-sm font-semibold mb-2">Role</label>
            <select name="role" class="w-full px-4 py-3 rounded-xl border border-slate-200" required>
                <option value="superadmin" {{ old('role', $user->role) == 'superadmin' ? 'selected' : '' }}>Super Admin</option>
                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="dosen" {{ old('role', $user->role) == 'dosen' ? 'selected' : '' }}>Dosen</option>
                <option value="mahasiswa" {{ old('role', $user->role) == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-semibold mb-2">Status</label>
            <select name="status" class="w-full px-4 py-3 rounded-xl border border-slate-200" required>
                <option value="pending" {{ old('status', $user->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                <option value="banned" {{ old('status', $user->status) == 'banned' ? 'selected' : '' }}>Banned</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-semibold mb-2">NIM</label>
            <input type="text" name="nim" value="{{ old('nim', $user->nim) }}"
                   class="w-full px-4 py-3 rounded-xl border border-slate-200">
        </div>

        <div>
            <label class="block text-sm font-semibold mb-2">NIDN</label>
            <input type="text" name="nidn" value="{{ old('nidn', $user->nidn) }}"
                   class="w-full px-4 py-3 rounded-xl border border-slate-200">
        </div>

        <div>
            <label class="block text-sm font-semibold mb-2">No. HP</label>
            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                   class="w-full px-4 py-3 rounded-xl border border-slate-200">
        </div>

        <div>
            <label class="block text-sm font-semibold mb-2">Fakultas</label>
            <select name="faculty_id" class="w-full px-4 py-3 rounded-xl border border-slate-200">
                <option value="">Tidak ada</option>
                @foreach($faculties as $faculty)
                    <option value="{{ $faculty->id }}" {{ old('faculty_id', $user->faculty_id) == $faculty->id ? 'selected' : '' }}>
                        {{ $faculty->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-semibold mb-2">Poin Reputasi</label>
            <input type="number" name="reputation_points" value="{{ old('reputation_points', $user->reputation_points) }}"
                   class="w-full px-4 py-3 rounded-xl border border-slate-200">
        </div>

        <div class="md:col-span-2 flex justify-end gap-3 pt-4">
            <a href="{{ route('admin.users.index') }}"
               class="px-5 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold">
                Batal
            </a>

            <button type="submit"
                    class="px-5 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold">
                Update User
            </button>
        </div>
    </form>
</div>

@endsection