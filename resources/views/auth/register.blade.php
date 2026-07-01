<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Sistem Booking Ruangan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-100 flex items-center justify-center px-4 py-10">

    <div class="w-full max-w-3xl bg-white rounded-3xl shadow-xl border border-slate-200 p-8">
        <div class="text-center mb-8">
            <div class="w-16 h-16 rounded-2xl bg-blue-600 text-white flex items-center justify-center text-3xl font-bold mx-auto mb-4">
                R
            </div>

            <h1 class="text-2xl font-extrabold text-slate-800">
                Registrasi Akun
            </h1>

            <p class="text-sm text-slate-500 mt-1">
                Daftar sebagai mahasiswa, dosen, atau organisasi
            </p>
        </div>

        @if($errors->any())
            <div class="mb-5 rounded-xl bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm">
                <ul class="list-disc ml-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register.process') }}" class="grid grid-cols-1 md:grid-cols-2 gap-5">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Nama Lengkap
                </label>
                <input type="text"
                       name="name"
                       value="{{ old('name') }}"
                       class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       required>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Email
                </label>
                <input type="email"
                       name="email"
                       value="{{ old('email') }}"
                       class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       required>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Role
                </label>
                <select name="role" id="role"  {{-- PERBAIKAN: tambahkan id="role" --}}
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        required>
                    <option value="">Pilih Role</option>
                    <option value="mahasiswa" {{ old('role') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                    <option value="dosen" {{ old('role') == 'dosen' ? 'selected' : '' }}>Dosen</option>
                    <option value="organisasi" {{ old('role') == 'organisasi' ? 'selected' : '' }}>Organisasi</option>
                </select>
            </div>

            <div>
                <label id="nimLabel" class="block text-sm font-semibold text-slate-700 mb-2">
                    NIM / NIDN
                </label>
                <input type="text"
                       id="nim_nip"  {{-- PERBAIKAN: tambahkan id="nim_nip" --}}
                       name="nim_nip"
                       value="{{ old('nim_nip') }}"
                       placeholder="NIM untuk mahasiswa / NIDN untuk dosen"
                       class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                {{-- PERBAIKAN: tidak ada required, karena organisasi tidak wajib --}}
                <p id="nimHelp" class="text-xs text-slate-400 mt-1">
                    Diisi sesuai role Anda
                </p>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    No. HP
                </label>
                <input type="text"
                       name="phone"
                       value="{{ old('phone') }}"
                       class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Fakultas
                </label>
                <select name="faculty_id"
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Pilih Fakultas</option>

                    @isset($faculties)
                        @foreach($faculties as $faculty)
                            <option value="{{ $faculty->id }}" {{ old('faculty_id') == $faculty->id ? 'selected' : '' }}>
                                {{ $faculty->name }}
                            </option>
                        @endforeach
                    @endisset
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Password
                </label>
                <input type="password"
                       name="password"
                       class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       required>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Konfirmasi Password
                </label>
                <input type="password"
                       name="password_confirmation"
                       class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       required>
            </div>

            <div class="md:col-span-2">
                <button type="submit"
                        class="w-full px-5 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold">
                    Daftar Akun
                </button>
            </div>
        </form>

        <p class="text-center text-sm text-slate-500 mt-6">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-blue-600 font-bold hover:underline">
                Login
            </a>
        </p>
    </div>

    {{-- PERBAIKAN: JavaScript untuk dynamic label --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const role = document.getElementById('role');
            const label = document.getElementById('nimLabel');
            const input = document.getElementById('nim_nip');
            const help = document.getElementById('nimHelp');

            function updateLabel() {
                if (role.value === 'mahasiswa') {
                    label.innerHTML = 'NIM <span class="text-red-500">*</span>';
                    input.placeholder = 'Masukkan NIM';
                    input.required = true;
                    help.textContent = 'Masukkan NIM mahasiswa Anda';
                } else if (role.value === 'dosen') {
                    label.innerHTML = 'NIDN <span class="text-red-500">*</span>';
                    input.placeholder = 'Masukkan NIDN';
                    input.required = true;
                    help.textContent = 'Masukkan NIDN dosen Anda';
                } else if (role.value === 'organisasi') {
                    label.innerHTML = 'Nama Organisasi';
                    input.placeholder = 'Masukkan Nama Organisasi (opsional)';
                    input.required = false;
                    help.textContent = 'Nama organisasi tidak wajib diisi';
                } else {
                    label.innerHTML = 'NIM / NIDN';
                    input.placeholder = 'NIM untuk mahasiswa / NIDN untuk dosen';
                    input.required = false;
                    help.textContent = 'Diisi sesuai role Anda';
                }
            }

            role.addEventListener('change', updateLabel);
            updateLabel();
        });
    </script>

</body>
</html>
