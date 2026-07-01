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
    </style>
</head>
<body class="min-h-screen flex items-center justify-center px-4 py-10">

    <div class="w-full max-w-4xl bg-white rounded-3xl shadow-xl overflow-hidden grid md:grid-cols-2">

        {{-- ============= LEFT: DETAIL APLIKASI ============= --}}
        <div class="panel-blue text-white p-10 flex flex-col">
            <div class="flex flex-col items-center text-center mb-6">
                <div class="w-20 h-20 rounded-full bg-white/10 border border-white/25 flex items-center justify-center mb-4">
                    <div class="w-14 h-14 rounded-full bg-white text-[#1D4ED8] flex items-center justify-center font-heading font-bold text-2xl">
                        B
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
                    B
                </div>
                <h1 class="font-heading text-xl font-bold text-slate-800">Login Smart Classroom</h1>
                <p class="text-sm text-slate-500 mt-1">Silakan masukkan kredensial Anda</p>
            </div>

            {{-- FLASH MESSAGES --}}
            @if(session('success'))
                <div class="mb-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('warning'))
                <div class="mb-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-700 px-4 py-3 text-sm">
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
            <form method="POST" action="{{ route('login.process') }}" class="space-y-4" id="loginForm">
                @csrf

                <div>
                    <input type="email"
                           name="email"
                           id="emailInput"
                           value="{{ old('email') }}"
                           placeholder="Email"
                           class="field-input"
                           required>
                </div>

                <div>
                    <input type="password"
                           name="password"
                           id="passwordInput"
                           placeholder="Password"
                           class="field-input"
                           required>
                </div>

                <div class="text-right">
                    <a href="#" class="text-sm text-[#1D4ED8] hover:underline">Lupa Password?</a>
                </div>

                <button type="submit"
                        id="loginButton"
                        class="btn-login w-full px-5 py-3 rounded-xl text-white font-semibold">
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
                    &#128081; Super Admin
                </button>
                <button type="button"
                        class="demo-login badge px-3 py-2.5 text-sm font-medium text-slate-700"
                        data-email="admin@undiksha.ac.id"
                        data-password="password123">
                    &#9881;&#65039; Admin
                </button>
                <button type="button"
                        class="demo-login badge px-3 py-2.5 text-sm font-medium text-slate-700"
                        data-email="dosen@undiksha.ac.id"
                        data-password="password123">
                    &#128104;&#8205;&#127979;&#65039; Dosen
                </button>
                <button type="button"
                        class="demo-login badge px-3 py-2.5 text-sm font-medium text-slate-700"
                        data-email="mahasiswa1@undiksha.ac.id"
                        data-password="password123">
                    &#127891; Mahasiswa
                </button>
            </div>

            <p class="text-center text-sm text-slate-500 mt-6">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-[#1D4ED8] font-semibold hover:underline">
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

            function submitLoginForm(email, password) {
                emailInput.value = email;
                passwordInput.value = password;

                loginButton.innerHTML = '<span class="spinner"></span> Memproses...';
                loginButton.classList.add('btn-loading');

                setTimeout(() => {
                    loginForm.submit();
                }, 300);

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

                    demoButtons.forEach(btn => btn.classList.remove('badge-ring'));
                    this.classList.add('badge-ring');

                    submitLoginForm(email, password);
                });
            });

            [emailInput, passwordInput].forEach(input => {
                input.addEventListener('input', function() {
                    demoButtons.forEach(btn => btn.classList.remove('badge-ring'));
                });
            });

            loginForm.addEventListener('submit', function() {
                this.classList.add('submitted');
            });
        });
    </script>

</body>
</html>