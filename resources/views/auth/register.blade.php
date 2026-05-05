<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar - Smart Classroom</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>

<body class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-100"
      style="font-family: 'Inter', sans-serif;">

<div class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-6xl grid grid-cols-1 lg:grid-cols-2 bg-white rounded-3xl shadow-2xl overflow-hidden border border-slate-200">

        {{-- LEFT PANEL --}}
        <div class="hidden lg:flex relative bg-gradient-to-br from-blue-700 to-indigo-900 text-white p-12 flex-col justify-between">
            <div>
                <div class="w-14 h-14 rounded-2xl bg-white/20 flex items-center justify-center text-3xl mb-6">🏫</div>
                <h1 class="text-4xl font-extrabold leading-tight">Buat Akun Baru</h1>
                <p class="text-blue-100 mt-5 text-lg leading-relaxed">
                    Daftarkan diri Anda untuk mengakses sistem booking ruang kelas Smart Classroom.
                </p>
            </div>

            <div class="space-y-4 mt-10">
                <div class="flex items-center gap-3 bg-white/10 rounded-2xl p-4 backdrop-blur">
                    <span class="text-2xl">✉️</span>
                    <div>
                        <p class="font-bold text-sm">Verifikasi Email</p>
                        <p class="text-blue-100 text-xs">Kode OTP dikirim ke email Anda</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 bg-white/10 rounded-2xl p-4 backdrop-blur">
                    <span class="text-2xl">🔒</span>
                    <div>
                        <p class="font-bold text-sm">Akun Aman</p>
                        <p class="text-blue-100 text-xs">Data terlindungi & terenkripsi</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 bg-white/10 rounded-2xl p-4 backdrop-blur">
                    <span class="text-2xl">⭐</span>
                    <div>
                        <p class="font-bold text-sm">Sistem Reputasi</p>
                        <p class="text-blue-100 text-xs">Mulai dengan 100 reputation point</p>
                    </div>
                </div>
            </div>

            <div class="absolute -bottom-20 -right-20 w-64 h-64 rounded-full bg-white/10"></div>
            <div class="absolute -top-20 -left-20 w-64 h-64 rounded-full bg-white/10"></div>
        </div>

        {{-- RIGHT: FORM --}}
        <div class="p-8 md:p-10 overflow-y-auto max-h-screen">

            <div class="mb-7">
                <div class="lg:hidden w-14 h-14 rounded-2xl bg-blue-600 text-white flex items-center justify-center text-3xl mb-5">🏫</div>
                <h2 class="text-3xl font-extrabold text-slate-900">Daftar Akun</h2>
                <p class="text-slate-500 mt-1 text-sm">Isi data diri Anda dengan lengkap dan benar.</p>
            </div>

            {{-- Error global --}}
            @if ($errors->any())
            <div class="mb-5 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <p>• {{ $error }}</p>
                @endforeach
            </div>
            @endif

            <form action="{{ route('register.process') }}" method="POST" class="space-y-4">
                @csrf

                {{-- Nama --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           class="w-full rounded-xl @error('name') border-red-400 @enderror"
                           placeholder="contoh: I Made Syaeful Gahar" required>
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Email Kampus</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="w-full rounded-xl @error('email') border-red-400 @enderror"
                           placeholder="contoh: nama@kampus.ac.id" required>
                </div>

                {{-- Role --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Role / Jabatan</label>
                    <select name="role" class="w-full rounded-xl @error('role') border-red-400 @enderror" required>
                        <option value="" disabled {{ old('role') ? '' : 'selected' }}>-- Pilih Role --</option>
                        <option value="mahasiswa" {{ old('role') === 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                        <option value="dosen"     {{ old('role') === 'dosen'     ? 'selected' : '' }}>Dosen</option>
                    </select>
                </div>

                {{-- NIM / NIP --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5" id="nim-label">NIM / NIP</label>
                    <input type="text" name="nim_nip" value="{{ old('nim_nip') }}"
                           class="w-full rounded-xl @error('nim_nip') border-red-400 @enderror"
                           placeholder="Nomor Induk Mahasiswa / Pegawai" required>
                </div>

                {{-- Password --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="password"
                               class="w-full rounded-xl pr-12 @error('password') border-red-400 @enderror"
                               placeholder="Minimal 6 karakter" required>
                        <button type="button" id="togglePass"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 text-xs font-semibold">
                            Lihat
                        </button>
                    </div>
                </div>

                {{-- Konfirmasi Password --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Konfirmasi Password</label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" id="passwordConf"
                               class="w-full rounded-xl pr-12 @error('password_confirmation') border-red-400 @enderror"
                               placeholder="Ulangi password" required>
                        <button type="button" id="togglePassConf"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 text-xs font-semibold">
                            Lihat
                        </button>
                    </div>
                </div>

                {{-- Submit --}}
                <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl font-bold transition mt-2">
                    Daftar
                </button>

            </form>

            <p class="text-center text-sm text-slate-500 mt-6">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-blue-600 font-semibold hover:underline">Masuk di sini</a>
            </p>

        </div>
    </div>
</div>

<script>
    // Toggle password
    $('#togglePass').on('click', function () {
        let input = $('#password');
        let show = input.attr('type') === 'password';
        input.attr('type', show ? 'text' : 'password');
        $(this).text(show ? 'Sembunyikan' : 'Lihat');
    });
    $('#togglePassConf').on('click', function () {
        let input = $('#passwordConf');
        let show = input.attr('type') === 'password';
        input.attr('type', show ? 'text' : 'password');
        $(this).text(show ? 'Sembunyikan' : 'Lihat');
    });

    // Update label NIM/NIP sesuai role
    $('select[name="role"]').on('change', function () {
        let label = $(this).val() === 'dosen' ? 'NIP (Nomor Induk Pegawai)' : 'NIM (Nomor Induk Mahasiswa)';
        $('#nim-label').text(label);
    });
</script>

</body>
</html>