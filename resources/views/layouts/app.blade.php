<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Smart Classroom')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Font Optimization -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Font Awesome 6 (Free) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

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
            if (localStorage.getItem('sidebar_minimized') === '1') {
                document.documentElement.classList.add('sidebar-will-minimize');
            }
        } catch (e) {}
    })();

    // Global Dark Mode
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

    document.addEventListener('DOMContentLoaded', function() {
        window.updateDarkModeIcon();
    });
    </script>

    @vite(['resources/css/app.css'])

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        function lazyLoadScript(src) {
            var script = document.createElement('script');
            script.src = src;
            script.async = true;
            document.head.appendChild(script);
        }

        var sweetAlertNeeded = document.querySelector('[data-swal]') !== null ||
                            document.querySelector('.swal-trigger') !== null;

        if (sweetAlertNeeded) {
            lazyLoadScript('https://cdn.jsdelivr.net/npm/sweetalert2@11');
        } else {
            document.addEventListener('click', function(e) {
                var target = e.target.closest('[data-swal], .swal-trigger, .delete-btn, .confirm-action');
                if (target && typeof Swal === 'undefined') {
                    lazyLoadScript('https://cdn.jsdelivr.net/npm/sweetalert2@11');
                }
            }, { once: true });
        }
    </script>

    @php
        $isLoggedIn = session('logged_in', false) || Auth::check();

        if ($isLoggedIn) {
            $role = session('user_role', 'mahasiswa');
            $allowedRoles = ['superadmin', 'admin', 'dosen', 'mahasiswa'];
            $role = in_array($role, $allowedRoles) ? $role : 'mahasiswa';
            $userName = session('user_name', 'Guest');
        } else {
            $role = 'guest';
            $userName = 'Guest';
        }

        $userId = session('user_id');
        $unreadCount = 0;
        if ($userId && $isLoggedIn) {
            try {
                $unreadCount = App\Models\Notifikasi::where('user_id', $userId)
                    ->where('status', 'belum_dibaca')
                    ->count();
            } catch (\Exception $e) {
                $unreadCount = 0;
            }
        }
    @endphp

    <style>
        /* ============================================================
           SIDEBAR STYLES
           ============================================================ */
        #sidebar {
            width: 260px;
            background: var(--tw-bg-opacity, 1) #ffffff;
            border-right: 1px solid #e2e8f0;
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1),
                        transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 40;
            display: flex;
            flex-direction: column;
            height: 100vh;
            position: sticky;
            top: 0;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .dark #sidebar {
            background: #1e293b;
            border-color: #334155;
        }

        #sidebar.minimized {
            width: 72px;
        }

        #sidebar.minimized .sidebar-brand-text,
        #sidebar.minimized .sidebar-subtitle,
        #sidebar.minimized .nav-label,
        #sidebar.minimized .sidebar-section-label,
        #sidebar.minimized .user-info,
        #sidebar.minimized .logout-label,
        #sidebar.minimized .nav-badge {
            display: none !important;
        }

        #sidebar.minimized .nav-item {
            justify-content: center;
            padding: 0.75rem;
        }

        #sidebar.minimized .nav-icon {
            margin-right: 0 !important;
            font-size: 1.25rem;
        }

        #sidebar.minimized .user-box {
            justify-content: center;
        }

        #sidebar.minimized .logout-btn {
            justify-content: center;
            padding: 0.75rem;
        }

        #sidebar.minimized .logout-btn span:not(.shrink-0) {
            display: none;
        }

        /* ============================================================
           MOBILE SIDEBAR
           ============================================================ */
        @media (max-width: 767px) {
            #sidebar {
                position: fixed;
                transform: translateX(-100%);
                width: 280px;
                height: 100vh;
                top: 0;
                left: 0;
                z-index: 50;
                border-radius: 0;
                box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            }

            #sidebar:not(.minimized) {
                transform: translateX(0);
            }

            #sidebar.minimized {
                width: 280px;
                transform: translateX(-100%);
            }

            #sidebar.minimized .sidebar-brand-text,
            #sidebar.minimized .sidebar-subtitle,
            #sidebar.minimized .nav-label,
            #sidebar.minimized .sidebar-section-label,
            #sidebar.minimized .user-info,
            #sidebar.minimized .logout-label,
            #sidebar.minimized .nav-badge {
                display: flex !important;
            }

            #sidebar.minimized .nav-item {
                justify-content: flex-start;
                padding: 0.625rem 0.875rem;
            }

            #sidebar.minimized .nav-icon {
                margin-right: 0.75rem !important;
                font-size: 1.125rem;
            }

            #sidebar.minimized .user-box {
                justify-content: flex-start;
            }

            #sidebar.minimized .logout-btn {
                justify-content: flex-start;
                padding: 0.625rem 0.875rem;
            }

            #sidebar.minimized .logout-btn span:not(.shrink-0) {
                display: inline;
            }
        }

        /* ============================================================
           NAVIGATION ITEMS
           ============================================================ */
        .nav-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.625rem 0.875rem;
            border-radius: 0.75rem;
            color: #64748b;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.15s ease;
            text-decoration: none;
            position: relative;
            cursor: pointer;
        }

        .nav-item:hover {
            background: #f1f5f9;
            color: #0f172a;
        }

        .nav-item.active {
            background: #eef2ff;
            color: #4f46e5;
            font-weight: 600;
        }

        .nav-item.active .nav-icon {
            color: #4f46e5;
        }

        .dark .nav-item {
            color: #94a3b8;
        }

        .dark .nav-item:hover {
            background: #334155;
            color: #f1f5f9;
        }

        .dark .nav-item.active {
            background: #312e81;
            color: #818cf8;
        }

        .dark .nav-item.active .nav-icon {
            color: #818cf8;
        }

        .nav-icon {
            font-size: 1.125rem;
            width: 1.5rem;
            text-align: center;
            flex-shrink: 0;
        }

        .nav-label {
            flex: 1;
            white-space: nowrap;
        }

        .nav-badge {
            background: #ef4444;
            color: white;
            font-size: 0.625rem;
            font-weight: 700;
            padding: 0.125rem 0.5rem;
            border-radius: 9999px;
            flex-shrink: 0;
        }

        /* ============================================================
           SIDEBAR SECTION LABEL
           ============================================================ */
        .sidebar-section-label {
            font-size: 0.625rem;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            padding: 0.5rem 0.875rem 0.25rem;
        }

        .dark .sidebar-section-label {
            color: #64748b;
        }

        /* ============================================================
           SCROLLBAR
           ============================================================ */
        ::-webkit-scrollbar {
            width: 4px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 999px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .dark ::-webkit-scrollbar-thumb {
            background: #475569;
        }

        .dark ::-webkit-scrollbar-thumb:hover {
            background: #64748b;
        }

        /* ============================================================
           ANIMATIONS
           ============================================================ */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-up {
            animation: fadeInUp 0.4s ease-out forwards;
        }

        .fade-up.delay-1 {
            animation-delay: 0.1s;
            opacity: 0;
        }

        .fade-up.delay-2 {
            animation-delay: 0.2s;
            opacity: 0;
        }

        .fade-up.delay-3 {
            animation-delay: 0.3s;
            opacity: 0;
        }

        /* ============================================================
           USER AVATAR
           ============================================================ */
        .user-avatar {
            width: 2.25rem;
            height: 2.25rem;
            border-radius: 9999px;
            background: #1e293b;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.75rem;
            flex-shrink: 0;
            transition: transform 0.2s ease;
        }

        .user-avatar:hover {
            transform: scale(1.05);
        }

        .dark .user-avatar {
            background: #334155;
        }

        /* ============================================================
           LOGOUT BUTTON
           ============================================================ */
        .logout-btn {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            width: 100%;
            padding: 0.625rem 0.75rem;
            border-radius: 0.75rem;
            background: #f1f5f9;
            color: #64748b;
            font-size: 0.875rem;
            font-weight: 600;
            transition: all 0.15s ease;
            border: none;
            cursor: pointer;
        }

        .logout-btn:hover {
            background: #ef4444;
            color: white;
            box-shadow: 0 4px 6px -1px rgba(239, 68, 68, 0.2);
        }

        .dark .logout-btn {
            background: #1e293b;
            color: #94a3b8;
            border: 1px solid #334155;
        }

        .dark .logout-btn:hover {
            background: #dc2626;
            color: white;
        }

        /* ============================================================
           OVERLAY
           ============================================================ */
        #overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.3);
            z-index: 30;
            display: none;
        }

        #overlay.show {
            display: block;
        }

        @media (min-width: 768px) {
            #overlay {
                display: none !important;
            }
        }

        /* ============================================================
           MAIN CONTENT
           ============================================================ */
        .main-content {
            background: #f8fafc;
        }

        .dark .main-content {
            background: #0f172a;
        }

        /* ============================================================
           RESPONSIVE UTILITIES
           ============================================================ */
        @media (max-width: 767px) {
            .sidebar-brand-text {
                display: flex !important;
            }
        }
    </style>

</head>
<body class="font-['Inter'] antialiased bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100">

<div class="min-h-screen flex overflow-x-hidden">

    {{-- ============================================================ --}}
    {{-- SIDEBAR --}}
    {{-- ============================================================ --}}
    @if($isLoggedIn && $role !== 'guest')
    <aside id="sidebar" class="bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800">

        {{-- LOGO --}}
        <div class="h-16 flex items-center px-4 border-b border-slate-200 dark:border-slate-800 shrink-0">
            <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center shrink-0 overflow-hidden">
                <img src="https://upload.wikimedia.org/wikipedia/commons/0/09/Logo_undiksha.png"
                    alt="Logo Undiksha"
                    class="w-8 h-8 object-contain block">
            </div>
            <div class="ml-3 overflow-hidden sidebar-brand-text">
                <h1 class="text-base font-extrabold tracking-tight text-slate-900 dark:text-white whitespace-nowrap">
                    Smart Classroom
                </h1>
                <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5 truncate sidebar-subtitle">
                    Booking & Availability
                </p>
            </div>
        </div>

        {{-- NAVIGATION --}}
        <nav class="flex-1 overflow-y-auto p-3 space-y-0.5">

            {{-- ===== HOME ===== --}}
            @php
                $dashRoute = match($role) {
                    'superadmin' => route('dashboard'),
                    'admin' => route('admin.dashboard'),
                    default => route('user.dashboard'),
                };
                $dashActive = request()->routeIs('dashboard', 'admin.dashboard', 'user.dashboard');
            @endphp

            <p class="sidebar-section-label">Home</p>
            <a href="{{ $dashRoute }}"
               class="nav-item {{ $dashActive ? 'active' : '' }}"
               @if($dashActive) aria-current="page" @endif>
                <span class="nav-icon"><i class="fas fa-home"></i></span>
                <span class="nav-label">Dashboard</span>
            </a>

            {{-- ===== RUANGAN ===== --}}
            @if(in_array($role, ['mahasiswa', 'dosen', 'superadmin']))
                <p class="sidebar-section-label pt-3">Ruangan</p>

                <a href="{{ route('rooms.index') }}"
                   class="nav-item {{ request()->is('rooms*') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fas fa-building"></i></span>
                    <span class="nav-label">Daftar Ruangan</span>
                </a>

                <a href="{{ route('schedule.index') }}"
                   class="nav-item {{ request()->is('schedule*') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fas fa-calendar-alt"></i></span>
                    <span class="nav-label">Jadwal Ruangan</span>
                </a>
            @endif

            {{-- ===== BOOKING ===== --}}
            @if(in_array($role, ['mahasiswa', 'dosen', 'organisasi', 'superadmin']))  {{-- ← TAMBAHKAN organisasi --}}
                <p class="sidebar-section-label pt-3">Booking</p>

                <a href="{{ route('bookings.create') }}"
                class="nav-item {{ request()->routeIs('bookings.create') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fas fa-plus-circle"></i></span>
                    <span class="nav-label">Ajukan Booking</span>
                </a>

                <a href="{{ route('bookings.index') }}"
                class="nav-item {{ request()->routeIs('bookings.index') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fas fa-calendar-check"></i></span>
                    <span class="nav-label">Data Booking</span>
                </a>
            @endif

            {{-- ===== AKUN ===== --}}
            <p class="sidebar-section-label pt-3">Akun</p>

            <a href="{{ route('profile.index') }}"
               class="nav-item {{ request()->is('profile*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-user"></i></span>
                <span class="nav-label">Profil Saya</span>
            </a>

            <a href="{{ route('reputation.index') }}"
               class="nav-item {{ request()->is('reputation*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-star"></i></span>
                <span class="nav-label">Reputation Point</span>
            </a>

            <a href="{{ route('notifications.index') }}"
               class="nav-item {{ request()->is('notifications*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-bell"></i></span>
                <span class="nav-label">Notifikasi</span>
                @php
                    $unreadCountSidebar = 0;
                    if (session('user_id')) {
                        try {
                            $unreadCountSidebar = App\Models\Notifikasi::where('user_id', session('user_id'))
                                ->where('status', 'belum_dibaca')
                                ->count();
                        } catch (\Exception $e) {
                            $unreadCountSidebar = 0;
                        }
                    }
                @endphp
                @if($unreadCountSidebar > 0)
                <span class="nav-badge">{{ $unreadCountSidebar > 99 ? '99+' : $unreadCountSidebar }}</span>
                @endif
            </a>

            <a href="{{ route('help.index') }}"
               class="nav-item {{ request()->is('help*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-question-circle"></i></span>
                <span class="nav-label">Bantuan</span>
            </a>

            {{-- ===== ADMINISTRASI ===== --}}
            @if(in_array($role, ['admin', 'superadmin']))
                <p class="sidebar-section-label pt-3">Administrasi</p>

                <a href="{{ route('admin.approvals.index') }}"
                   class="nav-item {{ request()->is('admin/approvals*') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fas fa-check-double"></i></span>
                    <span class="nav-label">Approval Booking</span>
                    @php
                        $pendingCount = App\Models\Booking::where('status', 'pending')->count();
                    @endphp
                    @if($pendingCount > 0)
                    <span class="nav-badge">{{ $pendingCount }}</span>
                    @endif
                </a>
            @endif

            {{-- ===== MANAJEMEN (Super Admin Only) ===== --}}
            @if($role === 'superadmin')
                <p class="sidebar-section-label pt-3">Manajemen</p>

                <a href="{{ route('admin.rooms.index') }}"
                   class="nav-item {{ request()->is('admin/rooms*') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fas fa-door-open"></i></span>
                    <span class="nav-label">Manajemen Ruang</span>
                </a>

                <a href="{{ route('admin.facilities.index') }}"
                   class="nav-item {{ request()->is('admin/facilities*') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fas fa-tools"></i></span>
                    <span class="nav-label">Manajemen Fasilitas</span>
                </a>

                <a href="{{ route('admin.room-facilities.index') }}"
                   class="nav-item {{ request()->is('admin/room-facilities*') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fas fa-link"></i></span>
                    <span class="nav-label">Fasilitas Ruang</span>
                </a>

                <a href="{{ route('admin.users.index') }}"
                   class="nav-item {{ request()->is('admin/users*') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fas fa-users"></i></span>
                    <span class="nav-label">Kelola User</span>
                </a>

                <a href="{{ route('admin.faculties.index') }}"
                   class="nav-item {{ request()->is('admin/faculties*') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fas fa-university"></i></span>
                    <span class="nav-label">Kelola Fakultas</span>
                </a>

                <a href="{{ route('admin.admin-faculties.index') }}"
                   class="nav-item {{ request()->is('admin/admin-faculties*') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fas fa-user-tie"></i></span>
                    <span class="nav-label">Admin Fakultas</span>
                </a>

                <a href="{{ route('admin.organization-approvals.index') }}"
                   class="nav-item {{ request()->is('admin/organization-approvals*') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fas fa-users-cog"></i></span>
                    <span class="nav-label">Approval Organisasi</span>
                </a>

                <p class="sidebar-section-label pt-3">Reputasi</p>

                <a href="{{ route('admin.reputation.settings') }}"
                   class="nav-item {{ request()->is('admin/reputation/settings*') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fas fa-sliders-h"></i></span>
                    <span class="nav-label">Pengaturan Reputasi</span>
                </a>

                <a href="{{ route('admin.reputation.logs') }}"
                   class="nav-item {{ request()->is('admin/reputation/logs*') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fas fa-history"></i></span>
                    <span class="nav-label">Log Reputasi</span>
                </a>

                <a href="{{ route('admin.reputation.levels') }}"
                   class="nav-item {{ request()->is('admin/reputation/levels*') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fas fa-layer-group"></i></span>
                    <span class="nav-label">Level Reputasi</span>
                </a>
            @endif

        </nav>

        {{-- USER BOX + LOGOUT --}}
        <div class="p-3 border-t border-slate-200 dark:border-slate-800 shrink-0">
            <div class="user-box flex items-center gap-3 px-2 py-2 mb-2 overflow-hidden">
                <div class="user-avatar">
                    {{ strtoupper(substr(session('user_name') ?? 'G', 0, 1)) }}
                </div>
                <div class="user-info overflow-hidden">
                    <p class="text-sm font-semibold text-slate-800 dark:text-white leading-tight truncate max-w-[150px]">
                        {{ e(session('user_name', 'Guest')) }}
                    </p>
                    <p class="text-xs text-slate-400 dark:text-slate-500 truncate">
                        {{ ucfirst(e(session('user_role', '-'))) }}
                    </p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    <span class="shrink-0"><i class="fas fa-sign-out-alt"></i></span>
                    <span class="logout-label whitespace-nowrap">Logout</span>
                </button>
            </form>
        </div>
    </aside>
    @endif
    {{-- ============================================================ --}}
    {{-- END SIDEBAR --}}
    {{-- ============================================================ --}}

    {{-- MOBILE OVERLAY --}}
    <div id="overlay"></div>

    {{-- ============================================================ --}}
    {{-- MAIN CONTENT --}}
    {{-- ============================================================ --}}
    <main class="flex-1 min-w-0 flex flex-col h-screen overflow-hidden bg-slate-50 dark:bg-slate-950">

        {{-- TOPBAR --}}
        @if($isLoggedIn && $role !== 'guest')
        <header class="h-16 md:h-[72px] bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between px-4 md:px-8 sticky top-0 z-20 shrink-0 shadow-sm">
            <div class="flex items-center gap-3 min-w-0">
                <button id="btnToggleSidebar"
                        aria-label="Toggle navigation menu"
                        aria-expanded="false"
                        aria-controls="sidebar"
                        class="flex items-center justify-center w-10 h-10 rounded-xl transition-all active:scale-95 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 bg-indigo-600 hover:bg-indigo-700 text-white shadow-md md:bg-transparent md:hover:bg-slate-100 md:text-slate-600 md:hover:text-indigo-600 md:shadow-none dark:md:bg-transparent dark:md:text-slate-400 dark:md:hover:bg-slate-800 dark:md:hover:text-white">
                    <i class="fas fa-bars text-lg"></i>
                </button>

                <div class="min-w-0">
                    <h2 class="text-base md:text-xl font-extrabold text-slate-900 dark:text-white truncate leading-tight">
                        @yield('page_title', 'Dashboard')
                    </h2>
                    <p class="text-xs text-slate-400 dark:text-slate-500 truncate">
                        @yield('page_subtitle', 'Manajemen booking ruang kelas')
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2 md:gap-3">
                <!-- Dark Mode Toggle -->
                <button id="darkModeToggle"
                        aria-label="Toggle dark mode"
                        title="Ganti tema"
                        class="w-9 h-9 flex items-center justify-center rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 transition-all focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-slate-700 dark:hover:text-white">
                    <span class="text-base" id="themeIcon">🌙</span>
                </button>

                <!-- Notifikasi Dropdown -->
                @include('layouts.notifikasi')

                <!-- User Profile -->
                <div class="hidden md:flex items-center gap-3 pl-3 border-l border-slate-200 dark:border-slate-800">
                    <div class="text-right">
                        <p class="text-sm font-semibold text-slate-800 dark:text-white leading-tight">
                            {{ e(session('user_name', 'Guest')) }}
                        </p>
                        <p class="text-xs text-slate-400 dark:text-slate-500">
                            <i class="fas fa-circle text-emerald-500 text-[8px] align-middle mr-1"></i> Online
                        </p>
                    </div>
                    <div class="w-9 h-9 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-sm shadow-sm">
                        {{ strtoupper(substr(session('user_name', 'G'), 0, 1)) }}
                    </div>
                </div>
            </div>
        </header>
        @endif

        <!-- CONTENT -->
        <section id="mainContent" class="flex-1 p-4 md:p-8 overflow-y-auto min-h-0 {{ $isLoggedIn ? '' : 'flex items-center justify-center' }}">
            @yield('content')
        </section>
    </main>

    <!-- Loading Overlay -->
    <div id="pageLoading" class="fixed inset-0 z-50 items-center justify-center hidden backdrop-blur-sm bg-black/30">
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl p-6 flex flex-col items-center gap-3">
            <div class="w-10 h-10 border-4 border-slate-200 dark:border-slate-700 border-t-indigo-600 rounded-full animate-spin"></div>
            <p class="text-sm text-slate-600 dark:text-slate-300">Memuat halaman...</p>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    'use strict';

    // ============================================================
    // VARIABLES
    // ============================================================
    const $sidebar = $('#sidebar');
    const $overlay = $('#overlay');
    const $btnToggle = $('#btnToggleSidebar');
    const $mainContent = $('#mainContent');
    const $darkModeToggle = $('#darkModeToggle');
    const $pageLoading = $('#pageLoading');

    let resizeTimer = null;
    let saveScrollTimeout = null;
    let isNavigating = false;
    let loadingTimeout = null;

    // ============================================================
    // UTILITY FUNCTIONS
    // ============================================================
    function isMobile() {
        return window.innerWidth < 768;
    }

    // ============================================================
    // SIDEBAR TOGGLE
    // ============================================================
    function toggleSidebar() {
        if (isMobile()) {
            // Mobile: slide in/out
            if ($sidebar.hasClass('minimized')) {
                $sidebar.removeClass('minimized');
            }
            $sidebar.toggleClass('-translate-x-full');
            $overlay.toggleClass('show');
            document.body.style.overflow = $sidebar.hasClass('-translate-x-full') ? '' : 'hidden';
            $btnToggle.attr('aria-expanded', !$sidebar.hasClass('-translate-x-full'));
        } else {
            // Desktop: toggle minimized
            if ($sidebar.hasClass('-translate-x-full')) {
                $sidebar.removeClass('-translate-x-full');
            }
            $sidebar.toggleClass('minimized');
            const nowMinimized = $sidebar.hasClass('minimized');
            localStorage.setItem('sidebar_minimized', nowMinimized ? '1' : '0');
            $btnToggle.attr('aria-expanded', String(!nowMinimized));
        }
    }

    function closeSidebar() {
        if (isMobile()) {
            $sidebar.addClass('-translate-x-full');
            $overlay.removeClass('show');
            document.body.style.overflow = '';
            $btnToggle.attr('aria-expanded', 'false');
        }
    }

    // ============================================================
    // EVENT HANDLERS
    // ============================================================
    $btnToggle.on('click', toggleSidebar);

    $overlay.on('click', closeSidebar);

    // ============================================================
    // DARK MODE TOGGLE
    // ============================================================
    $darkModeToggle.on('click', function() {
        window.toggleDarkMode();
    });

    // ============================================================
    // SCROLL POSITION MANAGEMENT
    // ============================================================
    function saveScrollPosition() {
        if (isNavigating) return;
        clearTimeout(saveScrollTimeout);
        saveScrollTimeout = setTimeout(() => {
            if ($mainContent.length) {
                const scrollTop = $mainContent.scrollTop();
                if (scrollTop > 10) {
                    sessionStorage.setItem('scroll_position_' + window.location.pathname, scrollTop);
                }
            }
        }, 100);
    }

    function restoreScrollPosition() {
        const currentPath = window.location.pathname;
        const savedPosition = sessionStorage.getItem('scroll_position_' + currentPath);
        if (savedPosition && $mainContent.length) {
            requestAnimationFrame(() => {
                $mainContent.scrollTop(parseInt(savedPosition));
                sessionStorage.removeItem('scroll_position_' + currentPath);
            });
        }
    }

    $mainContent.on('scroll', saveScrollPosition);
    setTimeout(restoreScrollPosition, 100);
    $(window).on('load', restoreScrollPosition);

    // ============================================================
    // RESIZE HANDLER
    // ============================================================
    function handleResize() {
        if (resizeTimer) clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
            if (!isMobile()) {
                // Desktop: restore minimized state
                const savedMinimized = localStorage.getItem('sidebar_minimized');
                if (savedMinimized === '1') {
                    $sidebar.addClass('minimized');
                    $btnToggle.attr('aria-expanded', 'false');
                } else {
                    $sidebar.removeClass('minimized');
                    $btnToggle.attr('aria-expanded', 'true');
                }
                $sidebar.removeClass('-translate-x-full');
                $overlay.removeClass('show');
                document.body.style.overflow = '';
            } else {
                // Mobile: ensure sidebar is closed
                $sidebar.removeClass('minimized');
            }
        }, 150);
    }

    $(window).off('resize', handleResize).on('resize', handleResize);

    // ============================================================
    // LOADING OVERLAY
    // ============================================================
    function showLoadingOverlay() {
        if (loadingTimeout) clearTimeout(loadingTimeout);
        $pageLoading.removeClass('hidden').fadeIn(200);
        loadingTimeout = setTimeout(() => {
            $pageLoading.fadeOut(200);
        }, 5000);
    }

    function hideLoadingOverlay() {
        if (loadingTimeout) clearTimeout(loadingTimeout);
        $pageLoading.hide().addClass('hidden');
    }

    $(document).on('click', 'a:not([href^="#"]):not([href^="javascript:"]):not([href^="mailto:"]):not([href^="tel:"])', function(e) {
        const href = $(this).attr('href');
        if (href && href !== '#' && !href.startsWith('javascript:') && !href.startsWith('http') && !href.startsWith('mailto:') && !href.startsWith('tel:')) {
            isNavigating = true;
            // Save scroll before navigation
            const scrollTop = $mainContent.scrollTop();
            if (scrollTop > 10) {
                sessionStorage.setItem('scroll_position_' + window.location.pathname, scrollTop);
            }
            setTimeout(() => { isNavigating = false; }, 500);
        }
    });

    $(window).on('load pageshow', function() {
        hideLoadingOverlay();
    });

    // ============================================================
    // INITIALIZE SIDEBAR STATE (Desktop)
    // ============================================================
    if (!isMobile()) {
        const savedMinimized = localStorage.getItem('sidebar_minimized');
        if (savedMinimized === '1') {
            $sidebar.addClass('minimized');
            $btnToggle.attr('aria-expanded', 'false');
        } else {
            $sidebar.removeClass('minimized');
            $btnToggle.attr('aria-expanded', 'true');
        }
        document.documentElement.classList.remove('sidebar-will-minimize');
    }

    // ============================================================
    // KEYBOARD SHORTCUT: ESC to close sidebar
    // ============================================================
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape' && isMobile()) {
            closeSidebar();
        }
    });
});
</script>

@stack('scripts')
</body>
</html>
