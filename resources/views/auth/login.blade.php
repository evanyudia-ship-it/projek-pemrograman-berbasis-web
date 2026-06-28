<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Smart Classroom</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        .demo-login {
            position: relative;
            overflow: hidden;
            transition: all 0.2s ease;
        }

        .demo-login:active {
            transform: scale(0.97);
        }

        .demo-login.loading {
            opacity: 0.7;
            pointer-events: none;
        }

        .demo-login.loading::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            animation: shimmer 0.8s infinite;
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        .btn-loading {
            position: relative;
            pointer-events: none;
            opacity: 0.7;
        }

        .btn-loading::after {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 0.75rem;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            animation: shimmer 0.8s infinite;
        }

        .spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 0.6s linear infinite;
            vertical-align: middle;
            margin-right: 8px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="min-h-screen bg-slate-100 flex items-center justify-center px-4">

    <div class="w-full max-w-md bg-white rounded-3xl shadow-xl border border-slate-200 p-8">
        {{-- LOGO --}}
        <div class="text-center mb-8">
            <div class="w-16 h-16 rounded-2xl bg-blue-600 text-white flex items-center justify-center text-3xl font-bold mx-auto mb-4">
                B
            </div>

            <h1 class="text-2xl font-extrabold text-slate-800">
                Login
            </h1>

            <p class="text-sm text-slate-500 mt-1">
                Masuk ke sistem booking ruangan
            </p>
        </div>

        {{-- FLASH MESSAGES --}}
        @if(session('success'))
            <div class="mb-4 rounded-xl bg-green-50 border border-green-200 text-green-700 px-4 py-3 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(session('warning'))
            <div class="mb-4 rounded-xl bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 text-sm">
                {{ session('warning') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 rounded-xl bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 rounded-xl bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm">
                <ul class="list-disc ml-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- ===== FORM LOGIN ===== --}}
        <form method="POST" action="{{ route('login.process') }}" class="space-y-5" id="loginForm">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Email
                </label>
                <input type="email"
                       name="email"
                       id="emailInput"
                       value="{{ old('email') }}"
                       placeholder="Masukkan email"
                       class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                       required>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Password
                </label>
                <input type="password"
                       name="password"
                       id="passwordInput"
                       placeholder="Masukkan password"
                       class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                       required>
            </div>

            <button type="submit"
                    id="loginButton"
                    class="w-full px-5 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold transition">
                Login
            </button>
        </form>

        {{-- ===== DIVIDER ===== --}}
        <div class="relative my-6">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-slate-200"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-4 bg-white text-slate-400 font-medium">atau</span>
            </div>
        </div>

        {{-- ===== AKUN DEMO ===== --}}
        <div class="space-y-3">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider text-center">
                Login Dengan Akun Demo
            </p>

            <div class="grid grid-cols-2 gap-2">
                {{-- Super Admin --}}
                <button type="button"
                        class="demo-login px-4 py-3 rounded-xl bg-purple-50 hover:bg-purple-100 border border-purple-200 text-purple-700 text-sm font-semibold transition text-center"
                        data-email="superadmin@undiksha.ac.id"
                        data-password="password123">
                    👑 Super Admin
                </button>

                {{-- Admin --}}
                <button type="button"
                        class="demo-login px-4 py-3 rounded-xl bg-blue-50 hover:bg-blue-100 border border-blue-200 text-blue-700 text-sm font-semibold transition text-center"
                        data-email="admin@undiksha.ac.id"
                        data-password="password123">
                    ⚙️ Admin
                </button>

                {{-- Dosen --}}
                <button type="button"
                        class="demo-login px-4 py-3 rounded-xl bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 text-emerald-700 text-sm font-semibold transition text-center"
                        data-email="dosen@undiksha.ac.id"
                        data-password="password123">
                    👨‍🏫 Dosen
                </button>

                {{-- Mahasiswa --}}
                <button type="button"
                        class="demo-login px-4 py-3 rounded-xl bg-amber-50 hover:bg-amber-100 border border-amber-200 text-amber-700 text-sm font-semibold transition text-center"
                        data-email="mahasiswa1@undiksha.ac.id"
                        data-password="password123">
                    🎓 Mahasiswa
                </button>
            </div>

            <p class="text-[10px] text-slate-400 text-center mt-2">
                💡 Klik salah satu akun demo di atas untuk login otomatis
            </p>
        </div>

        <p class="text-center text-sm text-slate-500 mt-6">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-blue-600 font-bold hover:underline">
                Daftar sekarang
            </a>
        </p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ── Demo Login Buttons ──
            const demoButtons = document.querySelectorAll('.demo-login');
            const emailInput = document.getElementById('emailInput');
            const passwordInput = document.getElementById('passwordInput');
            const loginForm = document.getElementById('loginForm');
            const loginButton = document.getElementById('loginButton');

            // ── Fungsi untuk submit form dengan loading state ──
            function submitLoginForm(email, password) {
                // Isi form
                emailInput.value = email;
                passwordInput.value = password;

                // Tambahkan loading state ke button
                loginButton.innerHTML = '<span class="spinner"></span> Memproses...';
                loginButton.classList.add('btn-loading');

                // Submit form
                setTimeout(() => {
                    loginForm.submit();
                }, 300);

                // Fallback: jika submit gagal, reset button setelah 10 detik
                setTimeout(() => {
                    if (!loginForm.classList.contains('submitted')) {
                        loginButton.innerHTML = 'Login';
                        loginButton.classList.remove('btn-loading');
                    }
                }, 10000);
            }

            demoButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();

                    const email = this.dataset.email;
                    const password = this.dataset.password;

                    // Hapus efek dari semua tombol
                    demoButtons.forEach(btn => {
                        btn.classList.remove('ring-2', 'ring-offset-2', 'ring-blue-500');
                    });

                    // Tambahkan efek pada tombol yang diklik
                    this.classList.add('ring-2', 'ring-offset-2', 'ring-blue-500');

                    // Submit form
                    submitLoginForm(email, password);
                });
            });

            // ── Hapus efek ring saat user mulai mengetik ──
            emailInput.addEventListener('input', function() {
                demoButtons.forEach(btn => {
                    btn.classList.remove('ring-2', 'ring-offset-2', 'ring-blue-500');
                });
            });

            passwordInput.addEventListener('input', function() {
                demoButtons.forEach(btn => {
                    btn.classList.remove('ring-2', 'ring-offset-2', 'ring-blue-500');
                });
            });

            // ── Tandai form sudah di-submit ──
            loginForm.addEventListener('submit', function() {
                this.classList.add('submitted');
            });
        });
    </script>

</body>
</html>
