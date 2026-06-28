<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Booking Ruangan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-100 flex items-center justify-center px-4">

    <div class="w-full max-w-md bg-white rounded-3xl shadow-xl border border-slate-200 p-8">
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

        @if($errors->any())
            <div class="mb-4 rounded-xl bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm">
                <ul class="list-disc ml-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login.process') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Email
                </label>
                <input type="email"
                       name="email"
                       value="{{ old('email') }}"
                       placeholder="Masukkan email"
                       class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       required>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Password
                </label>
                <input type="password"
                       name="password"
                       placeholder="Masukkan password"
                       class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       required>
            </div>

            <button type="submit"
                    class="w-full px-5 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold">
                Login
            </button>
        </form>

        <p class="text-center text-sm text-slate-500 mt-6">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-blue-600 font-bold hover:underline">
                Daftar sekarang
            </a>
        </p>
    </div>

</body>
</html>