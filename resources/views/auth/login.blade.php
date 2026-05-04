<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Smart Classroom</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Tailwind CDN --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- jQuery --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

</head>

<body class="min-h-screen bg-linear-to-br from-blue-50 via-white to-indigo-100"
      style="font-family: 'Inter', sans-serif;">

<div class="min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-6xl grid grid-cols-1 lg:grid-cols-2 bg-white rounded-3xl shadow-2xl overflow-hidden border border-slate-200">

        {{-- LEFT SECTION --}}
        <div class="hidden lg:flex relative bg-linear-to-br from-blue-700 to-indigo-900 text-white p-12 flex-col justify-between">

            <div>
                <div class="w-14 h-14 rounded-2xl bg-white/20 flex items-center justify-center text-3xl mb-6">
                    🏫
                </div>

                <h1 class="text-4xl font-extrabold leading-tight">
                    Smart Classroom Booking System
                </h1>

                <p class="text-blue-100 mt-5 text-lg leading-relaxed">
                    Sistem pemesanan ruang kelas berbasis web untuk mahasiswa, dosen, dan organisasi kampus secara transparan dan real-time.
                </p>
            </div>

            <div class="grid grid-cols-2 gap-4 mt-10">
                <div class="bg-white/10 rounded-2xl p-4 backdrop-blur">
                    <p class="text-3xl font-extrabold">24</p>
                    <p class="text-sm text-blue-100">Ruang tersedia</p>
                </div>

                <div class="bg-white/10 rounded-2xl p-4 backdrop-blur">
                    <p class="text-3xl font-extrabold">Real-time</p>
                    <p class="text-sm text-blue-100">Cek jadwal ruang</p>
                </div>

                <div class="bg-white/10 rounded-2xl p-4 backdrop-blur">
                    <p class="text-3xl font-extrabold">Role</p>
                    <p class="text-sm text-blue-100">Admin, dosen, mahasiswa</p>
                </div>

                <div class="bg-white/10 rounded-2xl p-4 backdrop-blur">
                    <p class="text-3xl font-extrabold">Point</p>
                    <p class="text-sm text-blue-100">Reputation system</p>
                </div>
            </div>

            <div class="absolute -bottom-20 -right-20 w-64 h-64 rounded-full bg-white/10"></div>
            <div class="absolute -top-20 -left-20 w-64 h-64 rounded-full bg-white/10"></div>
        </div>

        {{-- RIGHT LOGIN FORM --}}
        <div class="p-8 md:p-12">

            <div class="mb-8">
                <div class="lg:hidden w-14 h-14 rounded-2xl bg-blue-600 text-white flex items-center justify-center text-3xl mb-5">
                    🏫
                </div>

                <h2 class="text-3xl font-extrabold text-slate-900">
                    Masuk ke Sistem
                </h2>
                <p class="text-slate-500 mt-2">
                    Gunakan email dan password terdaftar. Role akan terbaca otomatis setelah login.
                </p>
            </div>

            <form action="{{ route('login.process') }}" method="POST" id="loginForm">
                @csrf

                <div class="space-y-5">

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Email
                        </label>
                        <input type="email"
                               name="email"
                               id="email"
                               class="w-full rounded-xl login-input"
                               placeholder="contoh: user@kampus.ac.id"
                               required>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Password
                        </label>
                        <div class="relative">
                            <input type="password"
                                   name="password"
                                   id="password"
                                   class="w-full rounded-xl pr-12 login-input"
                                   placeholder="Masukkan password"
                                   required>

                            <button type="button"
                                    id="togglePassword"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 text-sm">
                                Lihat
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between text-sm">
                        <label class="flex items-center gap-2 text-slate-600">
                            <input type="checkbox" class="rounded border-slate-300">
                            Ingat saya
                        </label>

                        <a href="#" class="text-blue-600 font-semibold hover:underline">
                            Lupa password?
                        </a>
                    </div>

                    <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl font-bold transition">
                        Login
                    </button>

                </div>
            </form>

            <div class="my-8 flex items-center gap-4">
                <div class="h-px bg-slate-200 flex-1"></div>
                <span class="text-xs text-slate-400">Demo Role</span>
                <div class="h-px bg-slate-200 flex-1"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-sm">
                <button class="demo-login px-4 py-3 rounded-xl bg-slate-100 hover:bg-blue-50 text-slate-700 font-semibold"
                        data-email="admin@kampus.ac.id">
                    Admin
                </button>

                <button class="demo-login px-4 py-3 rounded-xl bg-slate-100 hover:bg-blue-50 text-slate-700 font-semibold"
                        data-email="dosen@kampus.ac.id">
                    Dosen
                </button>

                <button class="demo-login px-4 py-3 rounded-xl bg-slate-100 hover:bg-blue-50 text-slate-700 font-semibold"
                        data-email="mahasiswa@kampus.ac.id">
                    Mahasiswa
                </button>
            </div>

            <p class="text-center text-sm text-slate-500 mt-8">
                Belum punya akun?
                <a href="#" class="text-blue-600 font-semibold hover:underline">
                    Hubungi admin kampus
                </a>
            </p>

        </div>

    </div>

</div>

<script>
    $('#togglePassword').on('click', function () {
        let input = $('#password');

        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            $(this).text('Sembunyi');
        } else {
            input.attr('type', 'password');
            $(this).text('Lihat');
        }
    });

    $('.demo-login').on('click', function () {
        let email = $(this).data('email');

        $('#email').val(email);
        $('#password').val('password');
    });
</script>

</body>
</html>
