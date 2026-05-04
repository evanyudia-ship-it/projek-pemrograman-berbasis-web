<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Smart Classroom')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    {{-- Tailwind CDN --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- jQuery CDN --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

</head>

<body class="bg-slate-100 text-slate-800">

<div class="min-h-screen flex">

    {{-- ══════════════════════════════
         SIDEBAR — Light Theme
    ══════════════════════════════ --}}
    <aside id="sidebar"
           class="fixed md:static inset-y-0 left-0 z-40 bg-white border-r border-slate-200 flex flex-col
                  transform -translate-x-full md:translate-x-0 transition-transform duration-300 h-screen">

        {{-- LOGO --}}
        <div class="h-17 flex items-center px-4 border-b border-slate-100 logo-wrap shrink-0">
            <div class="logo-icon w-9 h-9 rounded-xl bg-blue-600 flex items-center justify-center text-lg shrink-0 mr-3">
                🏫
            </div>
            <div class="sidebar-brand-text overflow-hidden">
                <h1 class="text-base font-extrabold tracking-tight text-slate-900 whitespace-nowrap">Smart Classroom</h1>
                <p class="sidebar-subtitle text-[11px] text-slate-400 mt-0.5 whitespace-nowrap">Booking & Availability</p>
            </div>
        </div>

        {{-- NAVIGATION --}}
        <nav class="flex-1 overflow-y-auto p-3 space-y-0.5">

            {{-- Main Menu --}}
            <p class="admin-section-label px-2 pt-2 pb-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Menu</p>

            <a href="{{ route('dashboard') }}"
               class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
                <span class="nav-icon">🏠</span>
                <span class="nav-label">Dashboard</span>
            </a>

            <a href="{{ route('rooms.index') }}"
               class="nav-item {{ request()->is('rooms') ? 'active' : '' }}">
                <span class="nav-icon">🏫</span>
                <span class="nav-label">Ketersediaan Ruang</span>
            </a>

            <a href="{{ route('bookings.create') }}"
               class="nav-item {{ request()->is('bookings/create') ? 'active' : '' }}">
                <span class="nav-icon">➕</span>
                <span class="nav-label">Ajukan Booking</span>
            </a>

            <a href="{{ route('bookings.index') }}"
               class="nav-item {{ request()->is('bookings') ? 'active' : '' }}">
                <span class="nav-icon">📅</span>
                <span class="nav-label">Data Booking</span>
            </a>

            <a href="{{ route('schedule.index') }}"
               class="nav-item {{ request()->is('schedule') ? 'active' : '' }}">
                <span class="nav-icon">🕒</span>
                <span class="nav-label">Jadwal Ruangan</span>
            </a>

            <a href="{{ route('profile.index') }}"
               class="nav-item {{ request()->is('profile') ? 'active' : '' }}">
                <span class="nav-icon">👤</span>
                <span class="nav-label">Profil Saya</span>
            </a>

            <a href="{{ route('reputation.index') }}"
               class="nav-item {{ request()->is('reputation') ? 'active' : '' }}">
                <span class="nav-icon">⭐</span>
                <span class="nav-label">Reputation Point</span>
            </a>

            <a href="{{ route('help.index') }}"
                class="nav-item {{ request()->is('help') ? 'active' : '' }}">
                <span class="nav-icon">❓</span>
                <span class="nav-label">Bantuan</span>
            </a>

            {{-- Admin Section --}}
            <div class="pt-3">
                <p class="admin-section-label px-2 pb-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest overflow-hidden">Admin</p>

                <a href="{{ route('admin.approvals') }}"
                   class="nav-item {{ request()->is('admin/approvals') ? 'active' : '' }}">
                    <span class="nav-icon">✅</span>
                    <span class="nav-label">Approval Admin</span>
                </a>

                <a href="{{ route('admin.rooms.index') }}"
                    class="nav-item {{ request()->is('admin/rooms') ? 'active' : '' }}">
                    <span class="nav-icon">🏗️</span>
                    <span class="nav-label">Manajemen Ruang</span>
                </a>

                <a href="{{ route('admin.users.index') }}"
                   class="nav-item {{ request()->is('admin/users') ? 'active' : '' }}">
                    <span class="nav-icon">👥</span>
                    <span class="nav-label">Kelola User</span>
                </a>
            </div>


        </nav>

        {{-- USER BOX + LOGOUT --}}
        <div class="p-3 border-t border-slate-100 shrink-0">
            <div class="user-box flex items-center gap-3 px-2 py-2 mb-2 overflow-hidden">
                <div class="user-avatar w-9 h-9 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-sm shrink-0">
                    A
                </div>
                <div class="user-info overflow-hidden">
                    <p class="text-sm font-semibold text-slate-800 whitespace-nowrap">Admin Kampus</p>
                    <p class="text-[11px] text-slate-400 whitespace-nowrap">Role: Admin</p>
                </div>
            </div>

            <a href="{{ route('login') }}"
               class="logout-btn flex items-center gap-2.5 w-full px-3 py-2.5 rounded-xl
                      bg-slate-100 hover:bg-red-50 hover:text-red-600 text-slate-500
                      text-sm font-semibold transition">
                <span class="shrink-0">🚪</span>
                <span class="logout-label whitespace-nowrap">Logout</span>
            </a>
        </div>

    </aside>

    {{-- OVERLAY MOBILE --}}
    <div id="overlay" class="fixed inset-0 bg-black/30 z-30 md:hidden"></div>

    {{-- ══════════════════════════════
         MAIN CONTENT
    ══════════════════════════════ --}}
    <main class="flex-1 w-full min-w-0 flex flex-col">

        {{-- TOPBAR --}}
        <header class="h-17 bg-white border-b border-slate-200 flex items-center justify-between px-4 md:px-6 sticky top-0 z-20 shrink-0">

            <div class="flex items-center gap-3 min-w-0">

                {{-- Toggle Sidebar (Desktop: minimize/expand | Mobile: open) --}}
                <button id="btnToggleSidebar"
                        class="w-9 h-9 flex items-center justify-center rounded-xl bg-slate-100 hover:bg-blue-50 hover:text-blue-600 text-slate-600 transition shrink-0"
                        title="Toggle Sidebar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" y1="6" x2="21" y2="6"/>
                        <line x1="3" y1="12" x2="21" y2="12"/>
                        <line x1="3" y1="18" x2="21" y2="18"/>
                    </svg>
                </button>

                <div class="min-w-0">
                    <h2 class="text-base md:text-xl font-extrabold text-slate-900 truncate leading-tight">
                        @yield('page_title', 'Dashboard')
                    </h2>
                    <p class="text-[11px] md:text-xs text-slate-400 truncate">
                        @yield('page_subtitle', 'Manajemen booking ruang kelas')
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                {{-- Notif bell --}}
                <button class="hidden md:flex w-9 h-9 items-center justify-center rounded-xl bg-slate-100 hover:bg-blue-50 text-slate-500 hover:text-blue-600 transition relative">
                    🔔
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-blue-600 rounded-full"></span>
                </button>

                {{-- Avatar --}}
                <div class="hidden md:flex items-center gap-2.5 pl-3 border-l border-slate-200">
                    <div class="text-right">
                        <p class="text-sm font-semibold text-slate-800 leading-tight">Admin Kampus</p>
                        <p class="text-[11px] text-slate-400">Online</p>
                    </div>
                    <div class="w-9 h-9 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-sm">
                        A
                    </div>
                </div>
            </div>

        </header>

        {{-- PAGE CONTENT --}}
        <section class="flex-1 p-4 md:p-8 overflow-auto">
            @yield('content')
        </section>

    </main>
</div>

<script>
$(document).ready(function () {
    const $sidebar = $('#sidebar');
    const $overlay = $('#overlay');
    const isMobile = () => window.innerWidth < 768;

    // ── Restore state TANPA animasi (fix: tidak ada transisi saat load halaman) ──
    if (!isMobile() && localStorage.getItem('sidebar_minimized') === '1') {
        $sidebar.addClass('sidebar-no-transition minimized');
        // Aktifkan kembali transisi setelah 1 frame render selesai
        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                $sidebar.removeClass('sidebar-no-transition');
            });
        });
    }

    // ── TOGGLE (Desktop: minimize/expand | Mobile: open drawer) ──
    $('#btnToggleSidebar').on('click', function (e) {
        e.stopPropagation();
        if (isMobile()) {
            $sidebar.removeClass('-translate-x-full');
            $overlay.addClass('show');
        } else {
            $sidebar.toggleClass('minimized');
            localStorage.setItem('sidebar_minimized', $sidebar.hasClass('minimized') ? '1' : '0');
        }
    });

    // ── CLOSE on overlay click (mobile) ──
    $overlay.on('click', function () {
        $sidebar.addClass('-translate-x-full');
        $overlay.removeClass('show');
    });
});
</script>

@stack('scripts')

</body>
</html>