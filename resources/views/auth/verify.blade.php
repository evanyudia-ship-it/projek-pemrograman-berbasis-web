<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Verifikasi OTP - Smart Classroom</title>
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
        /* Additional dark mode fixes untuk verify page */
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

        html.dark .bg-blue-50 {
            background-color: rgba(59, 130, 246, 0.15) !important;
        }

        html.dark .bg-amber-50 {
            background-color: rgba(245, 158, 11, 0.15) !important;
            border-color: rgba(245, 158, 11, 0.2) !important;
        }

        html.dark .text-amber-600,
        html.dark .text-amber-700 {
            color: #fbbf24 !important;
        }

        html.dark .bg-emerald-50 {
            background-color: rgba(16, 185, 129, 0.15) !important;
            border-color: rgba(16, 185, 129, 0.2) !important;
        }

        html.dark .text-emerald-700 {
            color: #34d399 !important;
        }

        html.dark .bg-red-50 {
            background-color: var(--danger-bg) !important;
            border-color: rgba(239, 68, 68, 0.2) !important;
        }

        html.dark .text-red-700 {
            color: var(--danger-text) !important;
        }

        /* OTP Input Styling */
        html.dark .otp-digit {
            background-color: var(--bg-input) !important;
            border-color: var(--border) !important;
            color: var(--text-primary) !important;
        }

        html.dark .otp-digit:focus {
            border-color: var(--brand) !important;
            box-shadow: 0 0 0 3px var(--brand-focus-shadow) !important;
        }

        /* Button dark mode */
        html.dark .bg-slate-800 {
            background-color: var(--brand) !important;
        }

        html.dark .bg-slate-800:hover {
            background-color: var(--brand-dark) !important;
        }

        /* Link styling */
        html.dark a.text-blue-600 {
            color: var(--brand) !important;
        }

        html.dark a.text-slate-400 {
            color: var(--text-muted) !important;
        }

        html.dark a.text-slate-400:hover {
            color: var(--text-secondary) !important;
        }

        /* Countdown text */
        html.dark .text-red-600 {
            color: #f87171 !important;
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
    </style>
</head>

<body class="min-h-screen bg-linear-to-br from-blue-50 via-white to-indigo-100 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900"
      style="font-family: 'Inter', sans-serif;">

{{-- Dark Mode Toggle Button (floating) --}}
<button id="darkModeToggle" class="dark-mode-toggle" aria-label="Toggle dark mode">
    <span id="themeIcon">🌙</span>
</button>

<div class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white dark:bg-surface rounded-3xl shadow-2xl border border-slate-200 dark:border-border p-8 md:p-10">

        {{-- Icon --}}
        <div class="flex flex-col items-center text-center mb-8">
            <div class="w-20 h-20 rounded-3xl bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center text-4xl mb-4">
                ✉️
            </div>
            <h2 class="text-2xl font-extrabold text-slate-900 dark:text-text-primary">Verifikasi Email</h2>
            <p class="text-slate-500 dark:text-text-secondary mt-2 text-sm leading-relaxed">
                Kode OTP telah dikirim ke<br>
                <span class="font-semibold text-slate-700 dark:text-text-primary">
                    {{ session('reg_email', 'email Anda') }}
                </span>
            </p>
        </div>

        {{-- OTP Demo Banner (development only) --}}
        @if(session('otp_demo') && app()->environment('local', 'development'))
        <div class="mb-5 p-4 rounded-2xl bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 text-center">
            <p class="text-xs text-amber-600 dark:text-amber-400 font-semibold mb-1">🔧 Mode Development — Kode OTP Anda:</p>
            <p class="text-3xl font-extrabold text-amber-700 dark:text-amber-400 tracking-widest">
                {{ session('otp_demo') }}
            </p>
            <p class="text-xs text-amber-500 dark:text-amber-500/75 mt-1">Banner ini tidak tampil di production</p>
        </div>
        @endif

        {{-- Resent notice --}}
        @if(session('resent'))
        <div class="mb-4 p-3 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 text-sm text-center font-medium">
            ✅ Kode OTP baru telah dikirim!
        </div>
        @endif

        {{-- Error --}}
        @if ($errors->any())
        <div class="mb-5 p-3 rounded-xl bg-red-50 dark:bg-danger-bg border border-red-200 dark:border-red-800 text-red-700 dark:text-danger-text text-sm text-center">
            {{ $errors->first('otp') }}
        </div>
        @endif

        {{-- OTP Form --}}
        <form action="{{ route('verify.process') }}" method="POST">
            @csrf

            <div class="mb-6">
                <label class="block text-sm font-semibold text-slate-700 dark:text-text-secondary mb-3 text-center">
                    Masukkan 6 Digit Kode OTP
                </label>

                {{-- 6 digit OTP boxes --}}
                <div class="flex gap-2 justify-center" id="otp-boxes">
                    @for($i = 0; $i < 6; $i++)
                    <input type="text"
                           class="otp-digit w-12 h-14 text-center text-xl font-extrabold rounded-xl border-2 border-slate-200 dark:border-border focus:border-blue-500 dark:focus:border-brand outline-none transition-all dark:bg-bg-input dark:text-text-primary"
                           inputmode="numeric"
                           pattern="[0-9]">
                    @endfor
                </div>

                {{-- Hidden input yang dikumpulkan --}}
                <input type="hidden" name="otp" id="otp-combined">
            </div>

            {{-- Countdown --}}
            <div class="text-center text-sm text-slate-500 dark:text-text-secondary mb-5"
                data-expires="{{ session('otp_expires_at') ? \Carbon\Carbon::parse(session('otp_expires_at'))->diffInSeconds(now()) : 600 }}">
                Kode berlaku selama
                <span id="countdown" class="font-bold text-red-600 dark:text-red-400">10:00</span>
            </div>

            <button type="submit" id="btn-verify"
                    class="w-full bg-slate-800 dark:bg-brand hover:bg-blue-500 dark:hover:bg-brand-dark text-white py-3 rounded-xl font-bold transition-all duration-200 hover:shadow-lg active:scale-95">
                Verifikasi Sekarang
            </button>

        </form>

        {{-- Resend --}}
        <div class="mt-5 text-center">
            <p class="text-sm text-slate-500 dark:text-text-muted">Tidak menerima kode?</p>
            <form action="{{ route('verify.resend') }}" method="POST" class="inline">
                @csrf
                <button type="submit"
                        class="text-blue-600 dark:text-brand font-semibold text-sm hover:underline mt-1">
                    Kirim ulang OTP
                </button>
            </form>
        </div>

        {{-- Back to register --}}
        <div class="mt-4 text-center">
            <a href="{{ route('register') }}" class="text-xs text-slate-400 dark:text-text-muted hover:text-slate-600 dark:hover:text-text-secondary transition">
                ← Kembali ke halaman daftar
            </a>
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
        const allowed = ['Backspace','Tab','ArrowLeft','ArrowRight'];
        if (!allowed.includes(e.key) && !/^\d$/.test(e.key)) {
            e.preventDefault();
        }
        if (e.key === 'Backspace' && $(this).val() === '') {
            $(this).prev('.otp-digit').focus();
        }
    });

    $('#otp-boxes').on('paste', function(e) {
        let pastedData = (e.originalEvent.clipboardData || window.clipboardData).getData('text');
        let cleaned = pastedData.replace(/\D/g, '').slice(0, 6);

        if (cleaned.length === 0) return;

        digits.each(function(idx) {
            if (idx < cleaned.length) {
                $(this).val(cleaned[idx]);
            } else {
                $(this).val('');
            }
        });

        let lastFilledIndex = Math.min(cleaned.length, digits.length) - 1;
        if (lastFilledIndex >= 0) {
            digits.eq(lastFilledIndex).focus();
        } else {
            digits.first().focus();
        }

        collectOtp();
        e.preventDefault();
        return false;
    });

    function collectOtp() {
        let combined = '';
        digits.each(function () { combined += $(this).val(); });
        $('#otp-combined').val(combined);
    }

    $('form').first().on('submit', function (e) {
        if ($('#otp-combined').val().length < 6) {
            e.preventDefault();
            alert('Harap isi semua 6 digit kode OTP.');
        }
    });

    // ── Countdown Timer ──
    let seconds = parseInt($('[data-expires]').data('expires')) || 600;
    const $countdown = $('#countdown');
    const $btn = $('#btn-verify');

    if (seconds <= 0) {
        $countdown.text('Kadaluarsa').addClass('text-red-500');
        $btn.prop('disabled', true).addClass('opacity-50 cursor-not-allowed');
    } else {
        let timer = setInterval(function () {
            seconds--;

            let m = Math.floor(seconds / 60).toString().padStart(2, '0');
            let s = (seconds % 60).toString().padStart(2, '0');
            $countdown.text(m + ':' + s);

            if (seconds <= 0) {
                clearInterval(timer);
                $countdown.text('Kadaluarsa').addClass('text-red-500');
                $btn.prop('disabled', true).addClass('opacity-50 cursor-not-allowed');
            }
        }, 1000);
    }
});
</script>

</body>
</html>
