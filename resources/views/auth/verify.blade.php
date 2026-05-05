<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Verifikasi OTP - Smart Classroom</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>

<body class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-100"
      style="font-family: 'Inter', sans-serif;">

<div class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white rounded-3xl shadow-2xl border border-slate-200 p-8 md:p-10">

        {{-- Icon --}}
        <div class="flex flex-col items-center text-center mb-8">
            <div class="w-20 h-20 rounded-3xl bg-blue-50 flex items-center justify-center text-4xl mb-4">
                ✉️
            </div>
            <h2 class="text-2xl font-extrabold text-slate-900">Verifikasi Email</h2>
            <p class="text-slate-500 mt-2 text-sm leading-relaxed">
                Kode OTP telah dikirim ke<br>
                <span class="font-semibold text-slate-700">{{ session('reg_email') }}</span>
            </p>
        </div>

        {{-- OTP Demo Banner (development only) --}}
        @if(session('otp_demo'))
        <div class="mb-5 p-4 rounded-2xl bg-amber-50 border border-amber-200 text-center">
            <p class="text-xs text-amber-600 font-semibold mb-1">🔧 Mode Development — Kode OTP Anda:</p>
            <p class="text-3xl font-extrabold text-amber-700 tracking-widest">
                {{ session('otp_demo') }}
            </p>
            <p class="text-xs text-amber-500 mt-1">Banner ini tidak tampil di production</p>
        </div>
        @endif

        {{-- Resent notice --}}
        @if(session('resent'))
        <div class="mb-4 p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm text-center font-medium">
            ✅ Kode OTP baru telah dikirim!
        </div>
        @endif

        {{-- Error --}}
        @if ($errors->any())
        <div class="mb-5 p-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm text-center">
            {{ $errors->first('otp') }}
        </div>
        @endif

        {{-- OTP Form --}}
        <form action="{{ route('verify.process') }}" method="POST">
            @csrf

            <div class="mb-6">
                <label class="block text-sm font-semibold text-slate-700 mb-3 text-center">
                    Masukkan 6 Digit Kode OTP
                </label>

                {{-- 6 digit OTP boxes --}}
                <div class="flex gap-2 justify-center" id="otp-boxes">
                    @for($i = 0; $i < 6; $i++)
                    <input type="text"
                           maxlength="1"
                           class="otp-digit w-12 h-14 text-center text-xl font-extrabold rounded-xl border-2 border-slate-200 focus:border-blue-500 outline-none transition"
                           inputmode="numeric"
                           pattern="[0-9]">
                    @endfor
                </div>

                {{-- Hidden input yang dikumpulkan --}}
                <input type="hidden" name="otp" id="otp-combined">
            </div>

            {{-- Countdown --}}
            <div class="text-center text-sm text-slate-500 mb-5">
                Kode berlaku selama
                <span id="countdown" class="font-bold text-blue-600">10:00</span>
            </div>

            <button type="submit" id="btn-verify"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl font-bold transition">
                Verifikasi Sekarang
            </button>

        </form>

        {{-- Resend --}}
        <div class="mt-5 text-center">
            <p class="text-sm text-slate-500">Tidak menerima kode?</p>
            <form action="{{ route('verify.resend') }}" method="POST" class="inline">
                @csrf
                <button type="submit"
                        class="text-blue-600 font-semibold text-sm hover:underline mt-1">
                    Kirim ulang OTP
                </button>
            </form>
        </div>

        {{-- Back to register --}}
        <div class="mt-4 text-center">
            <a href="{{ route('register') }}" class="text-xs text-slate-400 hover:text-slate-600">
                ← Kembali ke halaman daftar
            </a>
        </div>

    </div>
</div>

<script>
$(document).ready(function () {

    // ── OTP input: auto-focus next box ──
    const digits = $('.otp-digit');

    digits.on('input', function () {
        let val = $(this).val().replace(/\D/g, '');
        $(this).val(val);

        if (val.length === 1) {
            let next = $(this).next('.otp-digit');
            if (next.length) next.focus();
        }
        collectOtp();
    });

    digits.on('keydown', function (e) {
        if (e.key === 'Backspace' && $(this).val() === '') {
            $(this).prev('.otp-digit').focus();
        }
    });

    // Paste: sebar ke semua kotak
    digits.first().on('paste', function (e) {
        let pasted = (e.originalEvent.clipboardData || window.clipboardData)
                        .getData('text').replace(/\D/g, '').slice(0, 6);
        digits.each(function (i) {
            $(this).val(pasted[i] || '');
        });
        collectOtp();
        digits.last().focus();
        e.preventDefault();
    });

    function collectOtp() {
        let combined = '';
        digits.each(function () { combined += $(this).val(); });
        $('#otp-combined').val(combined);
    }

    // ── Countdown 10 menit ──
    let seconds = 600;
    const $countdown = $('#countdown');
    const $btn = $('#btn-verify');

    let timer = setInterval(function () {
        seconds--;
        let m = Math.floor(seconds / 60).toString().padStart(2, '0');
        let s = (seconds % 60).toString().padStart(2, '0');
        $countdown.text(m + ':' + s);

        if (seconds <= 0) {
            clearInterval(timer);
            $countdown.text('Kadaluarsa').addClass('text-red-500').removeClass('text-blue-600');
            $btn.prop('disabled', true).addClass('opacity-50 cursor-not-allowed');
        }
    }, 1000);

});
</script>

</body>
</html>