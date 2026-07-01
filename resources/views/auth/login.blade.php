<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Smart Classroom</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        body{ font-family:'Inter',sans-serif; background:#EEF1F6; }
        .font-heading{ font-family:'Poppins',sans-serif; }

        .panel-blue{
            background: linear-gradient(160deg, #1D4ED8 0%, #1E3A8A 100%);
        }

        .field-input{
            width:100%;
            padding:0.8rem 1rem;
            border-radius:0.6rem;
            border:1px solid #E2E8F0;
            background:#F8FAFC;
            outline:none;
            transition:border-color .15s ease, box-shadow .15s ease, background .15s ease;
        }
        .field-input:focus{
            border-color:#1D4ED8;
            background:#fff;
            box-shadow:0 0 0 3px rgba(29,78,216,0.15);
        }
        .field-input.error{
            border-color:#EF4444;
            background:#FEF2F2;
        }
        .field-input.error:focus{
            border-color:#EF4444;
            box-shadow:0 0 0 3px rgba(239,68,68,0.15);
        }

        .btn-login{
            background:#1D4ED8;
            transition: background .15s ease, transform .1s ease;
        }
        .btn-login:hover{ background:#1739AD; }
        .btn-login:active{ transform:scale(0.98); }
        .btn-login.btn-loading{ position:relative; pointer-events:none; opacity:.8; }
        .btn-login.btn-loading::after{
            content:'';
            position:absolute; inset:0;
            border-radius:0.6rem;
            background:linear-gradient(90deg, transparent, rgba(255,255,255,0.25), transparent);
            animation: shimmer .8s infinite;
        }
        .spinner{
            display:inline-block; width:16px; height:16px;
            border:2px solid rgba(255,255,255,0.3);
            border-radius:50%;
            border-top-color:#fff;
            animation: spin .6s linear infinite;
            vertical-align:middle; margin-right:8px;
        }
        @keyframes spin{ to{ transform:rotate(360deg); } }
        @keyframes shimmer{
            0%{ transform:translateX(-100%); }
            100%{ transform:translateX(100%); }
        }

        .badge{
            border-radius:0.6rem;
            border:1px solid #E2E8F0;
            background:#fff;
            transition:all .15s ease;
            position:relative;
            overflow:hidden;
            cursor:pointer;
        }
        .badge:hover{ border-color:#1D4ED8; box-shadow:0 6px 14px -8px rgba(29,78,216,0.35); }
        .badge:active{ transform:scale(0.98); }
        .badge.loading{ opacity:.6; pointer-events:none; }
        .badge.loading::after{
            content:'';
            position:absolute; inset:0;
            background:linear-gradient(90deg, transparent, rgba(29,78,216,0.08), transparent);
            animation: shimmer .8s infinite;
        }
        .badge-ring{ outline:2px solid #1D4ED8; outline-offset:1px; }

        .feature-item{
            display:flex;
            align-items:flex-start;
            gap:0.75rem;
            padding:0.5rem 0;
            border-bottom:1px solid rgba(255,255,255,0.08);
        }
        .feature-item:last-child{ border-bottom:none; }
        .feature-icon{
            width:28px;
            height:28px;
            border-radius:8px;
            background:rgba(255,255,255,0.12);
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:14px;
            flex-shrink:0;
        }
        .feature-text{ font-size:13px; line-height:1.5; color:rgba(255,255,255,0.9); }
        .feature-text strong{ color:#fff; font-weight:600; }

        /* Error message animation */
        .error-message{
            animation: slideDown .3s ease-out forwards;
        }
        @keyframes slideDown{
            from{ opacity:0; transform:translateY(-10px); }
            to{ opacity:1; transform:translateY(0); }
        }

        /* Banned alert styling */
        .banned-alert{
            animation: pulse-border 2s ease-in-out infinite;
        }
        @keyframes pulse-border{
            0%,100%{ border-color:#EF4444; }
            50%{ border-color:#F87171; }
        }

        /* Responsive tweaks */
        @media (max-width: 768px) {
            .panel-blue{ display:none; }
            .max-w-4xl{ max-width:100%; border-radius:1.5rem; }
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center px-4 py-10">

    <div class="w-full max-w-4xl bg-white rounded-3xl shadow-xl overflow-hidden grid md:grid-cols-2">

        {{-- ============= LEFT: DETAIL APLIKASI ============= --}}
        <div class="panel-blue text-white p-10 flex flex-col">
            <div class="flex flex-col items-center text-center mb-6">
                <div class="w-20 h-20 rounded-full bg-white/10 border border-white/25 flex items-center justify-center mb-4">
                    <div class="w-14 h-14 rounded-full bg-white text-[#1D4ED8] flex items-center justify-center font-heading font-bold text-2xl">
                        <img src="{{ asset('images/logo-undiksha-white.png') }}" alt="Undiksha" class="w-10 h-10 object-contain">
                    </div>
                </div>
                <p class="font-heading text-xs tracking-widest text-blue-100 uppercase">Smart Classroom</p>
                <p class="text-[11px] text-blue-200">Sistem Booking Ruangan &middot; Undiksha</p>
            </div>

            <h2 class="font-heading text-lg font-bold text-center mb-4">Tentang Aplikasi</h2>

            <div class="space-y-1 text-sm text-blue-100 leading-relaxed text-center mb-5">
                <p>Smart Classroom adalah sistem informasi manajemen peminjaman ruangan berbasis web yang dirancang untuk memudahkan sivitas akademika dalam mengelola jadwal dan ketersediaan ruang.</p>
            </div>

            <h3 class="font-heading text-md font-semibold text-center mb-3 text-blue-100">Fitur Unggulan</h3>

            <div class="space-y-1">
                <div class="feature-item">
                    <span class="feature-icon">📅</span>
                    <span class="feature-text"><strong>Booking Online</strong> – Ajukan peminjaman ruang kapan saja dengan status real-time.</span>
                </div>
                <div class="feature-item">
                    <span class="feature-icon">✅</span>
                    <span class="feature-text"><strong>Approval Terpadu</strong> – Admin dapat menyetujui atau menolak booking dengan cepat.</span>
                </div>
                <div class="feature-item">
                    <span class="feature-icon">⭐</span>
                    <span class="feature-text"><strong>Sistem Reputasi</strong> – Poin dan level pengguna untuk mendorong kedisiplinan.</span>
                </div>
                <div class="feature-item">
                    <span class="feature-icon">🕒</span>
                    <span class="feature-text"><strong>Check-in Mandiri</strong> – Konfirmasi kehadiran otomatis dengan batas waktu 30 menit.</span>
                </div>
                <div class="feature-item">
                    <span class="feature-icon">📊</span>
                    <span class="feature-text"><strong>Dashboard &amp; Laporan</strong> – Pantau statistik dan riwayat peminjaman.</span>
                </div>
            </div>

            <div class="mt-auto pt-6 text-center border-t border-white/10 text-[11px] text-blue-200">
                Versi 1.0 &bull; Dikembangkan untuk Undiksha
            </div>
        </div>

        {{-- ============= RIGHT: LOGIN FORM ============= --}}
        <div class="p-10 flex flex-col justify-center">

            <div class="text-center mb-7">
                <div class="w-14 h-14 rounded-2xl bg-[#1D4ED8] text-white flex items-center justify-center text-2xl font-bold font-heading mx-auto mb-3">
                    <img src="{{ asset('images/logo-undiksha.png') }}" alt="Undiksha" class="w-8 h-8 object-contain">
                </div>
                <h1 class="font-heading text-xl font-bold text-slate-800">Login Smart Classroom</h1>
                <p class="text-sm text-slate-500 mt-1">Silakan masukkan kredensial Anda</p>
            </div>

            {{-- ===== FLASH MESSAGES ===== --}}
            @if(session('success'))
                <div class="mb-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 text-sm flex items-center gap-2">
                    <span>✅</span>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('warning'))
                <div class="mb-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-700 px-4 py-3 text-sm flex items-center gap-2">
                    <span>⚠️</span>
                    {{ session('warning') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 rounded-xl bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm flex items-center gap-2 error-message">
                    <span>❌</span>
                    {{ session('error') }}
                </div>
            @endif

            {{-- ===== BANNED ALERT (jika user mencoba login dan kena banned) ===== --}}
            @if(session('banned'))
                <div class="mb-4 rounded-xl bg-red-50 border-2 border-red-400 text-red-700 px-4 py-3 text-sm banned-alert">
                    <div class="flex items-start gap-3">
                        <span class="text-2xl">🚫</span>
                        <div>
                            <p class="font-bold">Akun Anda Dibanned!</p>
                            <p class="text-red-600">{{ session('banned') }}</p>
                            <a href="{{ route('banned.index') }}" class="text-sm text-blue-600 hover:underline font-semibold mt-1 inline-block">
                                Kirim Banding →
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 rounded-xl bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm error-message">
                    <ul class="list-disc ml-5 space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- ===== FORM LOGIN ===== --}}
            <form method="POST" action="{{ route('login.process') }}" class="space-y-4" id="loginForm">
                @csrf

                <div>
                    <label class="text-sm font-medium text-slate-600 mb-1 block">Email</label>
                    <input type="email"
                           name="email"
                           id="emailInput"
                           value="{{ old('email') }}"
                           placeholder="Masukkan email Anda"
                           class="field-input @error('email') error @enderror"
                           required
                           autofocus>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1 error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="text-sm font-medium text-slate-600 mb-1 block">Password</label>
                    <input type="password"
                           name="password"
                           id="passwordInput"
                           placeholder="Masukkan password Anda"
                           class="field-input @error('password') error @enderror"
                           required>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1 error-message">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Forgot Password - Dihapus karena tidak ada fitur --}}

                <button type="submit"
                        id="loginButton"
                        class="btn-login w-full px-5 py-3 rounded-xl text-white font-semibold text-base">
                    Login
                </button>
            </form>

            {{-- ===== DIVIDER ===== --}}
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-slate-200"></div>
                </div>
                <div class="relative flex justify-center text-xs">
                    <span class="px-3 bg-white text-slate-400 font-medium uppercase tracking-wide">Akun demo</span>
                </div>
            </div>

            {{-- ===== AKUN DEMO ===== --}}
            <div class="grid grid-cols-2 gap-2.5">
                <button type="button"
                        class="demo-login badge px-3 py-2.5 text-sm font-medium text-slate-700"
                        data-email="superadmin@undiksha.ac.id"
                        data-password="password123">
                    👑 Super Admin
                </button>
                <button type="button"
                        class="demo-login badge px-3 py-2.5 text-sm font-medium text-slate-700"
                        data-email="admin@undiksha.ac.id"
                        data-password="password123">
                    ⚙️ Admin
                </button>
                <button type="button"
                        class="demo-login badge px-3 py-2.5 text-sm font-medium text-slate-700"
                        data-email="dosen@undiksha.ac.id"
                        data-password="password123">
                    👨‍🏫 Dosen
                </button>
                <button type="button"
                        class="demo-login badge px-3 py-2.5 text-sm font-medium text-slate-700"
                        data-email="mahasiswa1@undiksha.ac.id"
                        data-password="password123">
                    🎓 Mahasiswa
                </button>
                <button type="button"
                        class="demo-login badge px-3 py-2.5 text-sm font-medium text-slate-700 col-span-2"
                        data-email="hmtifo@undiksha.ac.id"
                        data-password="password123">
                    🏢 Organisasi (HMTI)
                </button>
                <button type="button"
                        class="demo-login badge px-3 py-2.5 text-sm font-medium col-span-2
                            bg-red-50 hover:bg-red-100 border-red-300 text-red-700 hover:border-red-400"
                        data-email="banned@undiksha.ac.id"
                        data-password="password123">
                    🚫 Demo Banned - Mahasiswa
                </button>
                <button type="button"
                        class="demo-login badge px-3 py-2.5 text-sm font-medium col-span-2
                            bg-red-50 hover:bg-red-100 border-red-300 text-red-700 hover:border-red-400"
                        data-email="banned-org@undiksha.ac.id"
                        data-password="password123">
                    🚫 Demo Banned - Organisasi
                </button>
            </div>

            <p class="text-center text-sm text-slate-500 mt-6">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-[#1D4ED8] font-semibold hover:underline transition">
                    Daftar sekarang
                </a>
            </p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const demoButtons = document.querySelectorAll('.demo-login');
            const emailInput = document.getElementById('emailInput');
            const passwordInput = document.getElementById('passwordInput');
            const loginForm = document.getElementById('loginForm');
            const loginButton = document.getElementById('loginButton');
            const errorMessages = document.querySelectorAll('.error-message');

            // Auto dismiss error messages after 5 seconds
            errorMessages.forEach(function(msg) {
                setTimeout(function() {
                    msg.style.transition = 'opacity 0.5s ease';
                    msg.style.opacity = '0';
                    setTimeout(function() {
                        msg.remove();
                    }, 500);
                }, 5000);
            });

            function submitLoginForm(email, password) {
                emailInput.value = email;
                passwordInput.value = password;

                // Trigger input event untuk validasi
                emailInput.dispatchEvent(new Event('input'));
                passwordInput.dispatchEvent(new Event('input'));

                loginButton.innerHTML = '<span class="spinner"></span> Memproses...';
                loginButton.classList.add('btn-loading');

                // Tambahkan loading state ke semua demo buttons
                demoButtons.forEach(btn => btn.classList.add('loading'));

                setTimeout(() => {
                    loginForm.submit();
                }, 300);

                // Fallback: jika submit gagal, reset button setelah 10 detik
                setTimeout(() => {
                    if (!loginForm.classList.contains('submitted')) {
                        loginButton.innerHTML = 'Login';
                        loginButton.classList.remove('btn-loading');
                        demoButtons.forEach(btn => btn.classList.remove('loading'));
                    }
                }, 10000);
            }

            demoButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();

                    const email = this.dataset.email;
                    const password = this.dataset.password;

                    // Hapus efek dari semua tombol
                    demoButtons.forEach(btn => btn.classList.remove('badge-ring'));

                    // Tambahkan efek pada tombol yang diklik
                    this.classList.add('badge-ring');

                    submitLoginForm(email, password);
                });
            });

            // Hapus ring saat user mulai mengetik
            [emailInput, passwordInput].forEach(input => {
                input.addEventListener('input', function() {
                    demoButtons.forEach(btn => btn.classList.remove('badge-ring'));

                    // Hapus class error jika ada
                    this.classList.remove('error');
                    const errorText = this.parentElement.querySelector('.text-red-500');
                    if (errorText) {
                        errorText.remove();
                    }
                });
            });

            loginForm.addEventListener('submit', function() {
                this.classList.add('submitted');
            });

            // Keyboard shortcut: Enter untuk submit
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && document.activeElement === emailInput) {
                    passwordInput.focus();
                    e.preventDefault();
                }
            });
        });
    </script>

</body>
</html>
