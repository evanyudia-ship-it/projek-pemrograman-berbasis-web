<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - Sistem Booking Ruangan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-100 flex items-center justify-center px-4">

    <div class="w-full max-w-md bg-white rounded-3xl shadow-xl border border-slate-200 p-8">
        <div class="text-center mb-8">
            <div class="w-16 h-16 rounded-2xl bg-blue-600 text-white flex items-center justify-center text-3xl font-bold mx-auto mb-4">
                ✉
            </div>

            <h1 class="text-2xl font-extrabold text-slate-800">
                Verifikasi Email
            </h1>

            <p class="text-sm text-slate-500 mt-1">
                Masukkan kode OTP 6 digit untuk mengaktifkan akun.
            </p>
        </div>

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

        @if(session('otp_demo'))
            <div class="mb-4 rounded-xl bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 text-sm">
                <p class="font-semibold">Kode OTP Demo:</p>
                <p class="text-2xl font-extrabold tracking-widest mt-1">
                    {{ session('otp_demo') }}
                </p>
                <p class="text-xs mt-1">
                    Catatan: ini hanya untuk demo karena email asli belum dikirim.
                </p>
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

        <form method="POST" action="{{ route('verify.process') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Kode OTP
                </label>
                <input type="text"
                       name="otp"
                       maxlength="6"
                       placeholder="Contoh: 123456"
                       class="w-full px-4 py-3 rounded-xl border border-slate-200 text-center text-2xl tracking-widest font-bold focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       required>
            </div>

            <button type="submit"
                    class="w-full px-5 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold">
                Verifikasi Akun
            </button>
        </form>

        <form method="POST" action="{{ route('verify.resend') }}" class="mt-4">
            @csrf

            <button type="submit"
                    class="w-full px-5 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold">
                Kirim Ulang OTP
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="mt-4">
            @csrf

            <button type="submit"
                    class="w-full text-sm text-slate-500 hover:text-red-600">
                Logout
            </button>
        </form>
    </div>

</body>
</html>