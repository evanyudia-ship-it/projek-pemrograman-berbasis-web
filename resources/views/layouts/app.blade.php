<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Smart Classroom')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Font Optimization -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

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

    <!-- Lazy load untuk semua library non-kritis -->
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
</head>
<body>
<div class="min-h-screen flex overflow-x-hidden">
    {{-- SIDEBAR --}}
    <aside id="sidebar"
        class="fixed md:sticky md:top-0 inset-y-0 left-0 z-40
            bg-white border-r border-slate-200 flex flex-col
            transform -translate-x-full md:translate-x-0 transition-transform duration-300
            h-full md:h-screen md:overflow-y-auto self-start
            dark:bg-bg-surface dark:border-border">

        {{-- LOGO --}}
        <div class="h-16 flex items-center px-5 border-b border-slate-100 shrink-0 dark:border-border">
            <div class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center shrink-0 overflow-hidden dark:bg-bg-muted dark:border-border">
                <img src="https://upload.wikimedia.org/wikipedia/commons/0/09/Logo_undiksha.png"
                    alt="Logo Undiksha"
                    class="w-8 h-8 object-contain block">
            </div>
            <div class="ml-2.5 overflow-hidden sidebar-brand-text">
                <h1 class="text-base font-extrabold tracking-tight text-slate-900 whitespace-nowrap dark:text-text-primary">
                    Smart Classroom
                </h1>
                <p class="text-xs text-slate-400 mt-0.5 truncate sidebar-subtitle dark:text-text-muted">
                    Booking & Availability
                </p>
            </div>
        </div>

        {{-- NAVIGATION --}}
        <nav class="flex-1 overflow-y-auto p-3 space-y-0.5">
            @php
                $role = session('user_role', 'mahasiswa');
                // Whitelist role (Security - Temuan 4)
                $allowedRoles = ['superadmin', 'admin', 'dosen', 'mahasiswa'];
                $role = in_array($role, $allowedRoles) ? $role : 'mahasiswa';
            @endphp

            @php
                $dashRoute = match($role) {
                    'superadmin' => route('dashboard'),
                    'admin' => route('admin.dashboard'),
                    default => route('user.dashboard'),
                };
                $dashActive = request()->routeIs('dashboard', 'admin.dashboard', 'user.dashboard');
            @endphp

            <p class="sidebar-section-label px-2 pt-2 pb-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest dark:text-text-muted">Home</p>
            <a href="{{ $dashRoute }}" class="nav-item focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 rounded-lg {{ $dashActive ? 'active' : '' }}" @if($dashActive) aria-current="page" @endif>
                <span class="nav-icon">🏠</span>
                <span class="nav-label">Dashboard</span>
            </a>

            @if(in_array($role, ['mahasiswa', 'dosen', 'superadmin']))
                <p class="sidebar-section-label px-2 pt-3 pb-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest dark:text-text-muted">Kelola Ruangan</p>
                <a href="{{ route('rooms.index') }}" class="nav-item focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 rounded-lg {{ request()->is('rooms*') ? 'active' : '' }}">
                    <span class="nav-icon">🏫</span>
                    <span class="nav-label">Daftar Ruangan</span>
                </a>
                <a href="{{ route('schedule.index') }}" class="nav-item focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 rounded-lg {{ request()->is('schedule*') ? 'active' : '' }}">
                    <span class="nav-icon">🕒</span>
                    <span class="nav-label">Jadwal Ruangan</span>
                </a>
            @endif

            @if(in_array($role, ['mahasiswa', 'dosen', 'superadmin']))
                <p class="sidebar-section-label px-2 pt-3 pb-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest dark:text-text-muted">Booking</p>
                <a href="{{ route('bookings.create') }}" class="nav-item focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 rounded-lg {{ request()->routeIs('bookings.create') ? 'active' : '' }}">
                    <span class="nav-icon">➕</span>
                    <span class="nav-label">Ajukan Booking</span>
                </a>
                <a href="{{ route('bookings.index') }}" class="nav-item focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 rounded-lg {{ request()->routeIs('bookings.index') ? 'active' : '' }}">
                    <span class="nav-icon">📅</span>
                    <span class="nav-label">Data Booking</span>
                </a>
            @endif

            @if(in_array($role, ['mahasiswa', 'dosen', 'superadmin']))
                <p class="sidebar-section-label px-2 pt-3 pb-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest dark:text-text-muted">Lainnya</p>
                <a href="{{ route('organization.index') }}" class="nav-item focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 rounded-lg {{ request()->is('organization*') ? 'active' : '' }}">
                    <span class="nav-icon">🏢</span>
                    <span class="nav-label">Perwakilan Organisasi</span>
                </a>
                <a href="{{ route('reputation.index') }}" class="nav-item focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 rounded-lg {{ request()->is('reputation*') ? 'active' : '' }}">
                    <span class="nav-icon">⭐</span>
                    <span class="nav-label">Reputation Point</span>
                </a>
            @endif

            <p class="sidebar-section-label px-2 pt-3 pb-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest dark:text-text-muted">Akun</p>
            <a href="{{ route('profile.index') }}" class="nav-item focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 rounded-lg {{ request()->is('profile*') ? 'active' : '' }}">
                <span class="nav-icon">👤</span>
                <span class="nav-label">Profil Saya</span>
            </a>
            <a href="{{ route('help.index') }}" class="nav-item focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 rounded-lg {{ request()->is('help*') ? 'active' : '' }}">
                <span class="nav-icon">❓</span>
                <span class="nav-label">Bantuan</span>
            </a>

            @if(in_array($role, ['admin', 'superadmin']))
                <p class="sidebar-section-label px-2 pt-3 pb-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest dark:text-text-muted">Administrasi</p>
                <a href="{{ route('admin.approvals') }}" class="nav-item focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 rounded-lg {{ request()->is('admin/approvals*') ? 'active' : '' }}">
                    <span class="nav-icon">✅</span>
                    <span class="nav-label">Approval Booking</span>
                </a>
                @if($role === 'superadmin')
                    <a href="{{ route('admin.organization-approvals.index') }}" class="nav-item focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 rounded-lg {{ request()->is('admin/organization-approvals*') ? 'active' : '' }}">
                        <span class="nav-icon">🏢</span>
                        <span class="nav-label">Approval Organisasi</span>
                    </a>
                    <a href="{{ route('admin.rooms.index') }}" class="nav-item focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 rounded-lg {{ request()->is('admin/rooms*') ? 'active' : '' }}">
                        <span class="nav-icon">🏗️</span>
                        <span class="nav-label">Manajemen Ruang</span>
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="nav-item focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 rounded-lg {{ request()->is('admin/users*') ? 'active' : '' }}">
                        <span class="nav-icon">👥</span>
                        <span class="nav-label">Kelola User</span>
                    </a>
                @endif
            @endif
        </nav>

        {{-- USER BOX + LOGOUT --}}
        <div class="p-3 border-t border-slate-100 shrink-0 dark:border-border">
            <div class="user-box flex items-center gap-3 px-2 py-2 mb-2 overflow-hidden">
                <div class="user-avatar w-9 h-9 rounded-full bg-slate-800 text-white flex items-center justify-center font-bold text-sm shrink-0 transition-transform hover:scale-105 dark:bg-bg-slate-800">
                    {{ strtoupper(substr(session('user_name') ?? 'G', 0, 1)) }}
                </div>
                <div class="user-info overflow-hidden">
                    <p class="text-sm font-semibold text-slate-800 leading-tight truncate max-w-[150px] dark:text-text-primary">
                        {{ e(session('user_name', 'Guest')) }}
                    </p>
                    <p class="text-xs text-slate-400 truncate dark:text-text-muted">
                        Role: {{ e(ucfirst(session('user_role', '-'))) }}
                    </p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn flex items-center gap-2.5 w-full px-3 py-2.5 rounded-xl bg-slate-100 hover:bg-red-500 hover:text-white text-slate-500 text-sm font-semibold transition-all duration-150 hover:shadow-md active:scale-95 dark:bg-bg-muted dark:text-text-secondary dark:border dark:border-border dark:hover:bg-danger-hover-bg dark:hover:text-danger-hover-text">
                    <span class="shrink-0">🚪</span>
                    <span class="logout-label whitespace-nowrap">Logout</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- MOBILE OVERLAY --}}
    <div id="overlay" class="fixed inset-0 bg-black/30 z-30 md:hidden hidden"></div>

    {{-- MAIN CONTENT --}}
    <main class="flex-1 min-w-0 flex flex-col md:shadow-lg md:rounded-l-2xl h-screen overflow-hidden">
        {{-- TOPBAR --}}
        <header class="h-16 md:h-20 bg-white border-b border-slate-200 flex items-center justify-between px-4 md:px-6 sticky top-0 z-20 shrink-0 dark:bg-bg-surface dark:border-border">
            <div class="flex items-center gap-3 min-w-0">
                <!-- Desktop Toggle (hidden on mobile) -->
                <button id="btnToggleSidebar"
                        aria-label="Toggle navigation menu"
                        aria-expanded="false"
                        aria-controls="sidebar"
                        class="flex items-center justify-center w-11 h-11 rounded-xl transition-all
                            active:scale-95 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2
                          bg-green-500 hover:bg-green-600 text-white shadow-md
                            md:bg-slate-100 md:hover:bg-blue-50 md:text-slate-600 md:hover:text-blue-600 md:shadow-none
                            dark:md:bg-bg-muted dark:md:text-text-secondary">
                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" y1="6" x2="21" y2="6"/>
                        <line x1="3" y1="12" x2="21" y2="12"/>
                        <line x1="3" y1="18" x2="21" y2="18"/>
                    </svg>
                </button>

                <div class="min-w-0">
                    <h2 class="text-base md:text-xl font-extrabold text-slate-900 truncate leading-tight dark:text-text-primary">
                        @yield('page_title', 'Dashboard')
                    </h2>
                    <p class="text-xs text-slate-400 truncate dark:text-text-muted">
                        @yield('page_subtitle', 'Manajemen booking ruang kelas')
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button id="darkModeToggle" aria-label="Toggle dark mode" title="Ganti tema"
                        class="w-9 h-9 flex items-center justify-center rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 transition-all focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:bg-bg-muted dark:text-text-secondary dark:hover:bg-slate-700">
                    <span class="toggle-icon text-base" id="themeIcon">🌙</span>
                </button>

                @include('layouts.notifikasi')

                <div class="hidden md:flex items-center gap-2.5 pl-3 border-l border-slate-200 dark:border-border">
                    <div class="text-right">
                        <p class="text-sm font-semibold text-slate-800 leading-tight dark:text-text-primary">
                            {{ e(session('user_name', 'Guest')) }}
                        </p>
                        <p class="text-xs text-slate-400 dark:text-text-muted">Online</p>
                    </div>
                    <div class="w-9 h-9 rounded-full bg-slate-800 text-white flex items-center justify-center font-bold text-sm dark:bg-bg-slate-800">
                        {{ strtoupper(substr(session('user_name', 'G'), 0, 1)) }}
                    </div>
                </div>
            </div>
        </header>

        <section id="mainContent" class="flex-1 p-4 md:p-8 overflow-y-auto min-h-0">
            @yield('content')
        </section>
    </main>

    {{-- Loading Overlay --}}
    <div id="pageLoading" class="fixed inset-0 z-50 items-center justify-center hidden backdrop-blur-sm"
        style="background-color: color-mix(in srgb, var(--bg-page) 85%, transparent); display:none!important">
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl p-6 flex flex-col items-center gap-3">
            <div class="w-10 h-10 border-4 border-slate-200 dark:border-slate-700 border-t-green-500 rounded-full animate-spin"></div>
            <p class="text-sm text-slate-600 dark:text-slate-300">Memuat halaman...</p>
        </div>
    </div>
</div>

<script>
// ============================================================
// FIX T4: SINGLE SOURCE OF TRUTH - No duplicate handlers!
// ============================================================

// === Utility Functions (defined FIRST - hoisting safe) ===
function isMobile() {
    return window.innerWidth < 768;
}

let resizeTimer = null;
let saveScrollTimeout;
let isNavigating = false;
let loadingTimeout = null;

// === Scroll Position Management ===
function saveScrollPosition() {
    if (isNavigating) return;
    clearTimeout(saveScrollTimeout);
    saveScrollTimeout = setTimeout(() => {
        const contentSection = document.getElementById('mainContent');
        if (contentSection) {
            const scrollTop = contentSection.scrollTop;
            if (scrollTop > 10) {
                sessionStorage.setItem('scroll_position_' + window.location.pathname, scrollTop);
            }
        }
    }, 100);
}

function restoreScrollPosition() {
    const currentPath = window.location.pathname;
    const savedPosition = sessionStorage.getItem('scroll_position_' + currentPath);
    if (savedPosition) {
        const contentSection = document.getElementById('mainContent');
        if (contentSection) {
            requestAnimationFrame(() => {
                contentSection.scrollTop = parseInt(savedPosition);
                sessionStorage.removeItem('scroll_position_' + currentPath);
            });
        }
    }
}

function saveCurrentScrollBeforeUnload() {
    const contentSection = document.getElementById('mainContent');
    if (contentSection && contentSection.scrollTop > 10) {
        sessionStorage.setItem('scroll_position_' + window.location.pathname, contentSection.scrollTop);
    }
}

// === SINGLE Resize Handler (not duplicate!) ===
function handleResize() {
    if (resizeTimer) clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => {
        const isMobileView = isMobile();
        const $sidebar = $('#sidebar');
        const $overlay = $('#overlay');
        const $btnToggle = $('#btnToggleSidebar');

        if (!isMobileView) {
            // Desktop mode
            $sidebar.removeClass('-translate-x-full');
            $overlay.addClass('hidden').removeClass('show');
            document.body.style.overflow = '';

            // Restore minimized state from localStorage
            const savedMinimized = localStorage.getItem('sidebar_minimized');
            if (savedMinimized === '1') {
                $sidebar.addClass('minimized');
                $btnToggle.attr('aria-expanded', 'false');
            } else {
                $sidebar.removeClass('minimized');
                $btnToggle.attr('aria-expanded', 'true');
            }
        } else {
            // Mobile mode - force remove minimized
            $sidebar.removeClass('minimized');
            // Don't auto-close sidebar on resize — let user control
        }
    }, 150);
}

// === Loading Overlay Handler ===
function showLoadingOverlay() {
    const $loading = $('#pageLoading');
    if (loadingTimeout) clearTimeout(loadingTimeout);
    $loading.removeClass('hidden').fadeIn(200);
    loadingTimeout = setTimeout(() => {
        $loading.fadeOut(200);
    }, 3000);
}

function hideLoadingOverlay() {
    if (loadingTimeout) clearTimeout(loadingTimeout);
    // Hapus inline style dulu, lalu hide
    $('#pageLoading').removeAttr('style').hide();
}

// === MAIN DOCUMENT READY ===
$(document).ready(function () {
    const $loading = $('#pageLoading');
    const $mainContent = $('#mainContent');
    const $sidebar = $('#sidebar');
    const $overlay = $('#overlay');
    const $btnToggle = $('#btnToggleSidebar');
    const $darkModeToggle = $('#darkModeToggle');

    // === Initialize Sidebar State (Desktop only) ===
    if (!isMobile()) {
        const savedMinimized = localStorage.getItem('sidebar_minimized');
        if (savedMinimized === '1') {
            $sidebar.addClass('minimized');
            $btnToggle.attr('aria-expanded', 'false');
        } else {
            $sidebar.removeClass('minimized');
            $btnToggle.attr('aria-expanded', 'true');
        }
        // Hapus class sidebar-will-minimize dari html setelah sidebar terinisialisasi
        // Class ini hanya untuk pre-render (mencegah flash), tidak boleh aktif saat JS jalan
        document.documentElement.classList.remove('sidebar-will-minimize');
    }

    // === SINGLE Resize Handler Attachment ===
    $(window).off('resize', handleResize).on('resize', handleResize);

    // === Dark Mode Toggle ===
    $darkModeToggle.on('click', function() {
        window.toggleDarkMode();
    });

    // === Scroll Handling ===
    $mainContent.on('scroll', saveScrollPosition);
    setTimeout(restoreScrollPosition, 100);
    $(window).on('load', restoreScrollPosition);

    // === Intercept Navigation ===
    $(document).on('click', 'a[href^="/"]', function(e) {
        const href = $(this).attr('href');
        if (href && href !== '#' && !href.startsWith('javascript:') && !href.startsWith('http') && !href.startsWith('mailto:') && !href.startsWith('tel:')) {
            isNavigating = true;
            saveCurrentScrollBeforeUnload();
            setTimeout(() => { isNavigating = false; }, 500);
        }
    });
    window.addEventListener('beforeunload', saveCurrentScrollBeforeUnload);

    $btnToggle.on('click', function (e) {
        e.stopPropagation();

        if (isMobile()) {
            // Mobile: toggle sidebar visibility (show/hide)
            if ($sidebar.hasClass('-translate-x-full')) {
                // Buka sidebar
                $sidebar.removeClass('-translate-x-full');
                $overlay.removeClass('hidden').addClass('show');
                document.body.style.overflow = 'hidden';
                $btnToggle.attr('aria-expanded', 'true');
            } else {
                // Tutup sidebar
                $sidebar.addClass('-translate-x-full');
                $overlay.removeClass('show').addClass('hidden');
                document.body.style.overflow = '';
                $btnToggle.attr('aria-expanded', 'false');
            }
        } else {
            // Desktop: toggle minimized state (collapse/expand)
            if ($sidebar.hasClass('-translate-x-full')) {
                $sidebar.removeClass('-translate-x-full');
            }

            $sidebar.toggleClass('minimized');
            const nowMinimized = $sidebar.hasClass('minimized');
            localStorage.setItem('sidebar_minimized', nowMinimized ? '1' : '0');
            $btnToggle.attr('aria-expanded', String(!nowMinimized));
            $(window).trigger('sidebar:toggle', { minimized: nowMinimized });
        }
    });

    $overlay.on('click', function () {
        $sidebar.addClass('-translate-x-full');
        $overlay.removeClass('show').addClass('hidden');
        document.body.style.overflow = '';
        $btnToggle.attr('aria-expanded', 'false');
    });

    // === Loading Overlay ===
    $(document).on('click', 'a', function(e) {
        const href = $(this).attr('href');
        if (href && href !== '#' && !href.startsWith('javascript:') && !href.startsWith('http') && !href.startsWith('mailto:') && !href.startsWith('tel:')) {
            showLoadingOverlay();
        }
    });

    $(window).on('load pageshow', function() {
        hideLoadingOverlay();
    });
});
</script>
    <script>
document.addEventListener('DOMContentLoaded', function () {
    const notificationButton = document.getElementById('notificationButton');
    const notificationDropdown = document.getElementById('notificationDropdown');

    if (notificationButton && notificationDropdown) {
        notificationButton.addEventListener('click', function (event) {
            event.stopPropagation();
            notificationDropdown.classList.toggle('hidden');
        });

        notificationDropdown.addEventListener('click', function (event) {
            event.stopPropagation();
        });

        document.addEventListener('click', function () {
            notificationDropdown.classList.add('hidden');
        });
    }
});
</script>

@stack('scripts')
</body>
</html>
