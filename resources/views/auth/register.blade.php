<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar - Smart Classroom</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Dark mode initialization (sama seperti layout) --}}
    <script>
    (function () {
        try {
            var theme = localStorage.getItem('theme');
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            } else if (theme === 'light') {
                document.documentElement.classList.remove('dark');
            } else if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
        } catch (e) {}
    })();

    window.toggleDarkMode = function() {
        const html = document.documentElement;
        if (html.classList.contains('dark')) {
            html.classList.remove('dark');
            localStorage.setItem('theme', 'light');
        } else {
            html.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        }
        window.updateDarkModeIcon();
    };

    window.updateDarkModeIcon = function() {
        const icon = document.getElementById('themeIcon');
        if (icon) {
            icon.textContent = document.documentElement.classList.contains('dark') ? '☀️' : '🌙';
        }
    };
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <style>
        /* Additional dark mode fixes untuk register page */
        html.dark body {
            background: var(--bg-page) !important;
        }

        html.dark .bg-white {
            background-color: var(--bg-surface) !important;
        }

        html.dark .border-slate-200 {
            border-color: var(--border) !important;
        }

        html.dark .text-slate-900 {
            color: var(--text-primary) !important;
        }

        html.dark .text-slate-500,
        html.dark .text-slate-400,
        html.dark .text-slate-600,
        html.dark .text-slate-700 {
            color: var(--text-secondary) !important;
        }

        html.dark .bg-slate-100 {
            background-color: var(--bg-muted) !important;
        }

        html.dark input,
        html.dark select {
            background-color: var(--bg-input) !important;
            border-color: var(--border) !important;
            color: var(--text-primary) !important;
        }

        html.dark input::placeholder {
            color: var(--text-muted) !important;
        }

        html.dark input:focus,
        html.dark select:focus {
            border-color: var(--brand) !important;
            box-shadow: 0 0 0 3px var(--brand-focus-shadow) !important;
        }

        html.dark .bg-red-50 {
            background-color: var(--danger-bg) !important;
            border-color: rgba(239, 68, 68, 0.2) !important;
        }

        html.dark .text-red-700 {
            color: var(--danger-text) !important;
        }

        /* Dark mode toggle button styling */
        .dark-mode-toggle {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 50;
            width: 44px;
            height: 44px;
            border-radius: 999px;
            background: var(--bg-surface);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-default);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            transition: all 0.2s ease;
        }

        .dark-mode-toggle:hover {
            transform: scale(1.1);
            box-shadow: var(--shadow-lg);
        }

        html.dark .dark-mode-toggle {
            background: var(--bg-surface-alt);
            border-color: var(--border);
        }

        /* Select option dark mode */
        html.dark select option {
            background-color: var(--bg-input);
            color: var(--text-primary);
        }

        /* Link styling */
        html.dark a.text-blue-600 {
            color: var(--brand) !important;
        }

        html.dark a.text-blue-600:hover {
            color: var(--brand-dark) !important;
        }

        /* Button dark mode */
        html.dark .bg-blue-600 {
            background-color: var(--brand) !important;
        }

        html.dark .bg-blue-600:hover {
            background-color: var(--brand-dark) !important;
        }

        /* Error border */
        html.dark .border-red-500 {
            border-color: #ef4444 !important;
        }
    </style>
</head>

<body class="min-h-screen bg-linear-to-br from-blue-50 via-white to-indigo-100 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900"
      style="font-family: 'Inter', sans-serif;">

{{-- Dark Mode Toggle Button (floating) --}}
<button id="darkModeToggle" class="dark-mode-toggle" aria-label="Toggle dark mode">
    <span id="themeIcon">🌙</span>
</button>

<div class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-6xl grid grid-cols-1 lg:grid-cols-2 bg-white dark:bg-surface rounded-3xl shadow-2xl overflow-hidden border border-slate-200 dark:border-border">

        {{-- LEFT PANEL --}}
        <div class="hidden lg:flex relative bg-linear-to-br from-slate-800 to-indigo-900 text-white p-12 flex-col justify-between">
            <div>
                <div class="w-14 h-14 rounded-2xl bg-white/20 flex items-center justify-center mb-6 overflow-hidden">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/0/09/Logo_undiksha.png"
                         alt="Logo Undiksha"
                         class="w-10 h-10 object-contain">
                </div>
                <h1 class="text-4xl font-extrabold leading-tight">Buat Akun Baru</h1>
                <p class="text-blue-100 mt-5 text-lg leading-relaxed">
                    Silakan daftarkan diri Anda untuk mendapatkan akses ke sistem Smart Classroom Booking.
                </p>
            </div>

            <div class="space-y-4 mt-10">
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
                <div class="lg:hidden w-14 h-14 rounded-2xl bg-blue-600 dark:bg-brand text-white flex items-center justify-center text-3xl mb-5">🏫</div>
                <h2 class="text-3xl font-extrabold text-slate-900 dark:text-text-primary">Daftar Akun</h2>
                <p class="text-slate-500 dark:text-text-secondary mt-1 text-sm">Isi data diri Anda dengan lengkap dan benar.</p>
            </div>

            {{-- Error global --}}
            @if ($errors->any())
            <div class="mb-5 p-4 rounded-xl bg-red-50 dark:bg-danger-bg border border-red-200 dark:border-red-800 text-red-700 dark:text-danger-text text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <p>• {{ $error }}</p>
                @endforeach
            </div>
            @endif

            @php
                $borderClass = function ($field) {
                    return $errors->has($field) ? 'border-red-500' : 'border-slate-200 dark:border-border';
                };
            @endphp
            <form id="registerForm" action="{{ route('register.process') }}" method="POST" class="space-y-5">
                @csrf

                {{-- Nama Lengkap --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-text-secondary mb-1.5">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                            class="w-full rounded-xl border px-4 py-3 text-sm outline-none transition-all
                                focus:border-blue-500 dark:focus:border-brand focus:ring-1 focus:ring-blue-500 dark:focus:ring-brand/20
                                {{ $errors->has('name') ? 'border-red-500' : 'border-slate-200 dark:border-border' }}"
                           placeholder="contoh: I Made Syaeful Gahar" required>
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-text-secondary mb-1.5">Email Kampus</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                            class="w-full rounded-xl border px-4 py-3 text-sm outline-none transition-all
                                focus:border-blue-500 dark:focus:border-brand focus:ring-1 focus:ring-blue-500 dark:focus:ring-brand/20
                                {{ $errors->has('email') ? 'border-red-500' : 'border-slate-200 dark:border-border' }}"
                           placeholder="contoh: nama@student.undiksha.ac.id" required>
                </div>

                {{-- Role --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-text-secondary mb-1.5">Role / Jabatan</label>
                    <select name="role" id="role"
                            class="w-full rounded-xl border px-4 py-3 text-sm outline-none transition-all
                                focus:border-blue-500 dark:focus:border-brand focus:ring-1 focus:ring-blue-500 dark:focus:ring-brand/20
                                {{ $errors->has('role') ? 'border-red-500' : 'border-slate-200 dark:border-border' }}">
                        <option value="" disabled {{ !old('role') ? 'selected' : '' }}>-- Pilih Role --</option>
                        <option value="mahasiswa" {{ old('role') === 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                        <option value="dosen"     {{ old('role') === 'dosen' ? 'selected' : '' }}>Dosen</option>
                    </select>
                </div>

                {{-- NIM / NIP --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-text-secondary mb-1.5" id="nim-label">NIM / NIP</label>
                    <input type="text" name="nim_nip" value="{{ old('nim_nip') }}"
                           class="w-full rounded-xl border px-4 py-3 text-sm outline-none transition-all
                                focus:border-blue-500 dark:focus:border-brand focus:ring-1 focus:ring-blue-500 dark:focus:ring-brand/20
                                {{ $errors->has('nim_nip') ? 'border-red-500' : 'border-slate-200 dark:border-border' }}"
                           placeholder="Nomor Induk Mahasiswa / Pegawai" required>
                </div>

                {{-- Password --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-text-secondary mb-1.5">Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="password"
                               class="w-full rounded-xl border px-4 py-3 text-sm outline-none transition-all
                                    focus:border-blue-500 dark:focus:border-brand focus:ring-1 focus:ring-blue-500 dark:focus:ring-brand/20
                                    {{ $errors->has('password') ? 'border-red-500' : 'border-slate-200 dark:border-border' }}"
                               placeholder="Minimal 6 karakter" required>
                        <button type="button" id="togglePass"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 dark:text-text-muted hover:text-slate-700 dark:hover:text-text-secondary text-sm font-medium transition">
                            Lihat
                        </button>
                    </div>
                </div>

                {{-- Konfirmasi Password --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-text-secondary mb-1.5">Konfirmasi Password</label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" id="passwordConf"
                               class="w-full rounded-xl border px-4 py-3 text-sm outline-none transition-all
                                    focus:border-blue-500 dark:focus:border-brand focus:ring-1 focus:ring-blue-500 dark:focus:ring-brand/20
                                    {{ $errors->has('password_confirmation') ? 'border-red-500' : 'border-slate-200 dark:border-border' }}"
                               placeholder="Ulangi password" required>
                        <button type="button" id="togglePassConf"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 dark:text-text-muted hover:text-slate-700 dark:hover:text-text-secondary text-sm font-medium transition">
                            Lihat
                        </button>
                    </div>
                </div>

                {{-- Submit --}}
                <button type="submit"
                        class="w-full bg-blue-600 dark:bg-brand hover:bg-blue-700 dark:hover:bg-brand-dark text-white py-3.5 rounded-xl font-semibold text-base transition-all duration-200 hover:shadow-lg active:scale-95">
                    Daftar Sekarang
                </button>

            </form>

            <p class="text-center text-sm text-slate-500 dark:text-text-muted mt-8">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-blue-600 dark:text-brand font-semibold hover:underline">Masuk di sini</a>
            </p>

        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    // Update dark mode icon on load
    window.updateDarkModeIcon();

    // Dark mode toggle
    $('#darkModeToggle').on('click', function() {
        window.toggleDarkMode();
    });

    // Toggle Password
    $('#togglePass').on('click', function () {
        let input = $('#password');
        let isPassword = input.attr('type') === 'password';
        input.attr('type', isPassword ? 'text' : 'password');
        $(this).text(isPassword ? 'Sembunyikan' : 'Lihat');
    });

    $('#togglePassConf').on('click', function () {
        let input = $('#passwordConf');
        let isPassword = input.attr('type') === 'password';
        input.attr('type', isPassword ? 'text' : 'password');
        $(this).text(isPassword ? 'Sembunyikan' : 'Lihat');
    });

    // Update Label NIM/NIP
    function updateNimLabel(val) {
        let label = (val === 'dosen')
            ? 'NIP (Nomor Induk Pegawai)'
            : 'NIM (Nomor Induk Mahasiswa)';
        $('#nim-label').text(label);
    }

    $('#role').on('change', function () {
        updateNimLabel($(this).val());
    });

    // Trigger on page load (untuk old input)
    let initialRole = $('#role').val();
    if (initialRole) updateNimLabel(initialRole);

    // Validasi Password Match sebelum submit
    $('#registerForm').on('submit', function (e) {
        const pass = $('#password').val();
        const passConf = $('#passwordConf').val();

        if (pass !== passConf) {
            e.preventDefault();
            alert('❌ Password dan Konfirmasi Password tidak cocok!');
            $('#passwordConf').focus().addClass('!border-red-500');

            // Remove the error class after user starts typing
            $('#passwordConf').on('input', function() {
                $(this).removeClass('!border-red-500');
            });
        }
    });
});
</script>

</body>
</html>
