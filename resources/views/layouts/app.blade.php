<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Smart Classroom')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- jQuery CDN --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    {{-- Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        inter: ['Inter', 'sans-serif']
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-800">

<div class="min-h-screen flex">

    {{-- Sidebar --}}
    <aside id="sidebar"
           class="fixed md:static inset-y-0 left-0 z-40 w-72 bg-slate-950 text-white transform -translate-x-full md:translate-x-0 transition-transform duration-300">

        <div class="h-20 flex items-center px-6 border-b border-slate-800">
            <div>
                <h1 class="text-xl font-extrabold tracking-tight">Smart Classroom</h1>
                <p class="text-xs text-slate-400 mt-1">Booking & Availability</p>
            </div>
        </div>

        <nav class="p-4 space-y-2">

            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-blue-600 transition {{ request()->is('dashboard') ? 'bg-blue-600' : 'text-slate-300' }}">
                <span>🏠</span>
                <span class="font-medium">Dashboard</span>
            </a>

            <a href="{{ route('rooms.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-blue-600 transition {{ request()->is('rooms') ? 'bg-blue-600' : 'text-slate-300' }}">
                <span>🏫</span>
                <span class="font-medium">Ketersediaan Ruang</span>
            </a>

            <a href="{{ route('bookings.create') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-blue-600 transition {{ request()->is('bookings/create') ? 'bg-blue-600' : 'text-slate-300' }}">
                <span>➕</span>
                <span class="font-medium">Ajukan Booking</span>
            </a>

            <a href="{{ route('bookings.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-blue-600 transition {{ request()->is('bookings') ? 'bg-blue-600' : 'text-slate-300' }}">
                <span>📅</span>
                <span class="font-medium">Riwayat Booking</span>
            </a>

            <a href="{{ route('admin.approvals') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-blue-600 transition {{ request()->is('admin/approvals') ? 'bg-blue-600' : 'text-slate-300' }}">
                <span>✅</span>
                <span class="font-medium">Approval Admin</span>
            </a>

        </nav>

        <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-slate-800">
            <div class="bg-slate-900 rounded-2xl p-4">
                <p class="text-sm font-semibold">I Made Putu Subali</p>
                <p class="text-xs text-slate-400 mt-1">Role: Admin</p>
            </div>
        </div>
    </aside>

    {{-- Overlay Mobile --}}
    <div id="overlay" class="hidden fixed inset-0 bg-black/40 z-30 md:hidden"></div>

    {{-- Main Content --}}
    <main class="flex-1 md:ml-0 w-full">

        {{-- Topbar --}}
        <header class="h-20 bg-white border-b border-slate-200 flex items-center justify-between px-4 md:px-8 sticky top-0 z-20">

            <div class="flex items-center gap-3">
                <button id="btnSidebar" class="md:hidden bg-slate-100 px-3 py-2 rounded-lg">
                    ☰
                </button>

                <div>
                    <h2 class="text-lg md:text-2xl font-extrabold text-slate-900">
                        @yield('page_title', 'Dashboard')
                    </h2>
                    <p class="text-xs md:text-sm text-slate-500">
                        @yield('page_subtitle', 'Manajemen booking ruang kelas')
                    </p>
                </div>
            </div>

            <div class="hidden md:flex items-center gap-4">
                <div class="text-right">
                    <p class="text-sm font-semibold">Admin Kampus</p>
                    <p class="text-xs text-slate-500">Online</p>
                </div>
                <div class="w-11 h-11 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold">
                    A
                </div>
            </div>
        </header>

        <section class="p-4 md:p-8">
            @yield('content')
        </section>

    </main>
</div>

<script>
    $(document).ready(function () {
        $('#btnSidebar').on('click', function () {
            $('#sidebar').removeClass('-translate-x-full');
            $('#overlay').removeClass('hidden');
        });

        $('#overlay').on('click', function () {
            $('#sidebar').addClass('-translate-x-full');
            $('#overlay').addClass('hidden');
        });
    });
</script>

@stack('scripts')

</body>
</html>
