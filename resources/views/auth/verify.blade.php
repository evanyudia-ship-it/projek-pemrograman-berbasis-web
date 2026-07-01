<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verifikasi Email - Smart Classroom</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body{ font-family:'Inter',sans-serif; background:#EEF1F6; }
        .font-heading{ font-family:'Poppins',sans-serif; }

        .panel-blue{
            background: linear-gradient(160deg, #1D4ED8 0%, #1E3A8A 100%);
        }

        .field-input{
            width:100%;
            padding:0.85rem 1.1rem;
            border-radius:0.75rem;
            border:1px solid #E2E8F0;
            background:#F8FAFC;
            outline:none;
            transition:all .15s ease;
        }

        .otp-input {
            width: 3.2rem;
            height: 3.8rem;
            text-align: center;
            font-size: 1.6rem;
            font-weight: 700;
            border-radius: 0.75rem;
            border: 2px solid #E2E8F0;
            background: #F8FAFC;
            outline: none;
            transition: all .2s ease;
        }
        .otp-input:focus {
            border-color: #1D4ED8;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(29,78,216,0.15);
            transform: scale(1.06);
        }
        .otp-input.filled {
            border-color: #10B981;
            background: #ECFDF5;
        }
        .otp-input.error {
            border-color: #EF4444;
            background: #FEF2F2;
        }

        .btn-verify{
            background:#1D4ED8;
            transition: all .15s ease;
            padding: 0.9rem 1.5rem;
        }
        .btn-verify:hover{ background:#1739AD; }

        .btn-resend{
            background:#F1F5F9;
            color:#475569;
            transition: all .15s ease;
        }
        .btn-resend:hover{
            background:#E2E8F0;
            color:#1E293B;
        }

        .spinner{
            display:inline-block; width:18px; height:18px;
            border:2px solid rgba(255,255,255,0.3);
            border-radius:50%;
            border-top-color:#fff;
            animation: spin .6s linear infinite;
        }
        @keyframes spin{ to{ transform:rotate(360deg); } }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center px-4 py-12">

    <div class="w-full max-w-6xl bg-white rounded-3xl shadow-2xl overflow-hidden grid md:grid-cols-[2fr_3fr]">

        <!-- LEFT PANEL -->
        <div class="panel-blue text-white p-10 flex flex-col">
            <div class="flex flex-col items-center text-center mb-8">
                <div class="w-24 h-24 rounded-full bg-white/10 border border-white/30 flex items-center justify-center mb-5">
                    <div class="w-16 h-16 rounded-full bg-white flex items-center justify-center">
                        <img src="{{ asset('images/logo-undiksha-white.png') }}" alt="Logo Undiksha" class="w-12 h-12 object-contain">
                    </div>
                </div>
                <p class="font-heading text-sm tracking-widest text-blue-100 uppercase">SMART CLASSROOM</p>
                <p class="text-blue-200 text-sm">Sistem Booking Ruangan • Undiksha</p>
            </div>

            <div class="flex-1 flex flex-col items-center justify-center text-center">
                <div class="text-7xl mb-6">✉️</div>
                <h2 class="font-heading text-2xl font-bold mb-3">Verifikasi Akun</h2>
                <p class="text-blue-100 leading-relaxed max-w-xs mx-auto">
                    Kami telah mengirimkan kode verifikasi 6 digit ke email Anda. Masukkan kode untuk mengaktifkan akun.
                </p>

                <div class="mt-8 w-full max-w-xs mx-auto bg-white/10 rounded-2xl p-5 backdrop-blur-sm border border-white/10">
                    <p class="text-xs text-blue-200 font-medium uppercase tracking-wider">Keamanan</p>
                    <p class="text-sm text-blue-100 mt-1">Kode OTP berlaku selama 5 menit</p>
                </div>
            </div>

            <div class="mt-auto pt-8 text-center text-blue-200 text-sm">
                Versi 1.0 • Dikembangkan untuk Undiksha
            </div>
        </div>

        <!-- VERIFICATION FORM -->
        <div class="p-12 lg:p-16 flex flex-col justify-center">
            <div class="max-w-md mx-auto w-full">
                <div class="text-center mb-8">
                    <div class="w-16 h-16 mx-auto mb-4 bg-[#1D4ED8] rounded-2xl flex items-center justify-center text-white">
                        <i class="fas fa-envelope text-3xl"></i>
                    </div>
                    <h1 class="font-heading text-2xl font-bold text-slate-800">Verifikasi Email</h1>
                    <p class="text-slate-500 mt-1">Masukkan kode OTP 6 digit</p>
                </div>

                @if(session('success'))
                    <div class="mb-6 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 text-sm flex items-center gap-3">
                        ✅ {{ session('success') }}
                    </div>
                @endif

                @if(session('warning'))
                    <div class="mb-6 rounded-2xl bg-amber-50 border border-amber-200 text-amber-700 px-5 py-4 text-sm flex items-center gap-3">
                        ⚠️ {{ session('warning') }}
                    </div>
                @endif

                @if(session('otp_demo'))
                    <div class="mb-6 rounded-2xl bg-blue-50 border border-blue-200 text-blue-700 px-5 py-4 text-sm">
                        <p class="font-semibold">Kode OTP Demo:</p>
                        <p class="text-3xl font-mono font-bold tracking-widest mt-2">{{ session('otp_demo') }}</p>
                        <p class="text-xs text-blue-600 mt-2">Hanya untuk keperluan demo.</p>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 rounded-2xl bg-red-50 border border-red-200 text-red-700 px-5 py-4 text-sm">
                        <ul class="list-disc ml-5 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('verify.process') }}" class="space-y-6" id="verifyForm">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-3 text-center">Kode OTP</label>
                        <div class="otp-container flex gap-3 justify-center" id="otpContainer">
                            <input type="text" id="otp1" maxlength="1" class="otp-input" required>
                            <input type="text" id="otp2" maxlength="1" class="otp-input" required>
                            <input type="text" id="otp3" maxlength="1" class="otp-input" required>
                            <input type="text" id="otp4" maxlength="1" class="otp-input" required>
                            <input type="text" id="otp5" maxlength="1" class="otp-input" required>
                            <input type="text" id="otp6" maxlength="1" class="otp-input" required>
                        </div>
                        <input type="hidden" name="otp" id="otpHidden">
                    </div>

                    <button type="submit" id="verifyButton"
                            class="btn-verify w-full rounded-2xl text-white font-semibold text-lg flex items-center justify-center gap-2">
                        <i class="fas fa-check-circle"></i> Verifikasi Akun
                    </button>
                </form>

                <form method="POST" action="{{ route('verify.resend') }}" class="mt-4" id="resendForm">
                    @csrf
                    <button type="submit" id="resendButton"
                            class="btn-resend w-full rounded-2xl py-3.5 text-sm font-semibold flex items-center justify-center gap-2">
                        <i class="fas fa-redo"></i> Kirim Ulang OTP
                    </button>
                </form>

                <div class="text-center mt-8">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="text-slate-500 hover:text-red-600 transition text-sm flex items-center gap-2 mx-auto">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </div>

                <div class="mt-6 text-center">
                    <p id="timerDisplay" class="text-xs font-medium text-slate-400 timer-text">⏱️ 5:00</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const otpInputs = document.querySelectorAll('.otp-input');
            const otpHidden = document.getElementById('otpHidden');
            const verifyForm = document.getElementById('verifyForm');
            const verifyButton = document.getElementById('verifyButton');
            const resendButton = document.getElementById('resendButton');

            otpInputs[0].focus();

            otpInputs.forEach((input, index) => {
                input.addEventListener('input', function() {
                    this.value = this.value.replace(/[^0-9]/g, '');

                    if (this.value.length === 1 && index < otpInputs.length - 1) {
                        otpInputs[index + 1].focus();
                    }
                    updateOtpHidden();

                    this.classList.toggle('filled', this.value.length === 1);
                });

                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace' && !this.value && index > 0) {
                        otpInputs[index - 1].focus();
                    }
                });

                input.addEventListener('paste', function(e) {
                    e.preventDefault();
                    const text = e.clipboardData.getData('text').replace(/[^0-9]/g, '').slice(0,6);
                    text.split('').forEach((char, i) => {
                        if (otpInputs[i]) {
                            otpInputs[i].value = char;
                            otpInputs[i].classList.add('filled');
                        }
                    });
                    updateOtpHidden();
                    if (text.length < 6) otpInputs[text.length].focus();
                });
            });

            function updateOtpHidden() {
                let otp = '';
                otpInputs.forEach(i => otp += i.value);
                otpHidden.value = otp;
            }

            // Submit Verification
            verifyForm.addEventListener('submit', function(e) {
                if (otpHidden.value.length !== 6) {
                    e.preventDefault();
                    otpInputs.forEach(input => input.classList.add('error'));
                    setTimeout(() => otpInputs.forEach(input => input.classList.remove('error')), 2500);
                    return;
                }

                verifyButton.innerHTML = `<span class="spinner"></span> Memverifikasi...`;
                verifyButton.disabled = true;
            });

            // Resend OTP
            document.getElementById('resendForm').addEventListener('submit', function() {
                resendButton.innerHTML = `<span class="spinner spinner-dark"></span> Mengirim...`;
                resendButton.disabled = true;
            });

            // Timer 5 Menit
            let timeLeft = 300;
            const timerDisplay = document.getElementById('timerDisplay');

            const timerInterval = setInterval(() => {
                const min = Math.floor(timeLeft / 60);
                const sec = timeLeft % 60;
                timerDisplay.textContent = `⏱️ ${min}:${sec.toString().padStart(2, '0')}`;

                if (timeLeft <= 60) timerDisplay.style.color = '#EF4444';
                if (timeLeft <= 0) {
                    clearInterval(timerInterval);
                    timerDisplay.textContent = 'Kode telah kadaluarsa';
                }
                timeLeft--;
            }, 1000);
        });
    </script>
</body>
</html>
