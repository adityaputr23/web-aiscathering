<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Console') | AISH Management</title>

    <!-- FONTS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Outfit:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --primary: #3d8c75; /* New Medium Green / Teal */
            --primary-glow: rgba(61, 140, 117, 0.15);
            --navy-deep: #08100d; /* Deep Forest Midnight Black */
            --navy-card: #0d1a15; /* Slate Green Card Background */
            --navy-card-border: rgba(162, 223, 204, 0.06);
            --sidebar-width: 280px;
            
            /* Light Mode (Refined Slate / Landing Page Sync) */
            --bg-main: #f8fafc; /* slate-50 */
            --bg-sidebar: #ffffff;
            --bg-card: #ffffff;
            --text-main: #0f172a; /* slate-900 */
            --text-muted: #64748b; /* slate-500 */
            --border: #e2e8f0; /* slate-200 */
            --glass-bg: rgba(255, 255, 255, 0.85);
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
        }

        html.dark {
            --bg-main: #08100d; /* Midnight Deep Forest Black */
            --bg-sidebar: #050a08; /* Slightly darker for sidebars */
            --bg-card: #0d1a15; /* Slate Green Card Background */
            --text-main: #f0fbf7;
            --text-muted: #a2dfcc;
            --border: rgba(162, 223, 204, 0.08);
            --glass-bg: rgba(8, 16, 13, 0.8);
            --card-shadow: 0 10px 40px -10px rgba(0, 0, 0, 0.50);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg-main);
            color: var(--text-main);
            overflow: hidden;
        }

        h1,
        h2,
        h3,
        h4,
        .font-outfit {
            font-family: 'Outfit', sans-serif;
        }

        /* CUSTOM SCROLLBAR */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--border);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary);
        }

        /* LAYOUT COMPONENTS */
        .premium-sidebar {
            width: var(--sidebar-width);
            background: var(--bg-sidebar);
            border-right: 1px solid var(--border);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 20px;
            margin: 4px 16px;
            border-radius: 16px;
            font-size: 14px;
            font-weight: 500;
            color: var(--text-muted);
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .nav-link:hover {
            color: var(--primary);
            background: var(--primary-glow);
            transform: translateX(4px);
        }

        .nav-link.active {
            color: white;
            background: linear-gradient(135deg, var(--primary), #059669);
            box-shadow: 0 10px 20px -5px rgba(16, 185, 129, 0.3);
        }

        .nav-link.active .link-icon {
            color: white;
        }

        /* GLOW OVERLAY */
        .glow-overlay {
            position: fixed;
            top: 0;
            right: 0;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, var(--primary-glow) 0%, transparent 70%);
            z-index: -1;
            pointer-events: none;
            opacity: 0.5;
        }

        /* ANIMATIONS */
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-in {
            animation: slideInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }

        /* BLUR HEAD */
        .glass-header {
            backdrop-filter: blur(20px);
            background: var(--glass-bg);
            border-bottom: 1px solid var(--border);
        }
    </style>

    <script>
        // Theme Manager (Separate from landing page)
        (function () {
            const theme = localStorage.getItem('admin_theme') || 'dark';
            document.documentElement.classList.toggle('dark', theme === 'dark');
        })();

        function toggleAdminTheme() {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('admin_theme', isDark ? 'dark' : 'light');
            updateThemeIcons();
        }

        function updateThemeIcons() {
            const isDark = document.documentElement.classList.contains('dark');
            const sun = document.getElementById('theme-icon-sun');
            const moon = document.getElementById('theme-icon-moon');
            if (sun && moon) {
                sun.classList.toggle('hidden', isDark);
                moon.classList.toggle('hidden', !isDark);
            }
        }
        
        window.addEventListener('DOMContentLoaded', updateThemeIcons);

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        function toggleUserDropdown() {
            const menu = document.getElementById('user-dropdown');
            menu.classList.toggle('hidden');
            menu.classList.toggle('animate-in');
        }

        document.addEventListener('click', (e) => {
            const dropdown = document.getElementById('user-dropdown');
            const btn = document.getElementById('user-btn');
            if (dropdown && !dropdown.contains(e.target) && !btn.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });
    </script>
</head>

<body class="bg-[var(--bg-main)] text-[var(--text-main)] selection:bg-emerald-500/30 transition-colors duration-500">

    <div class="glow-overlay"></div>

    <div class="flex h-screen overflow-hidden">

        <!-- SIDEBAR OVERLAY (Mobile) -->
        <div id="sidebar-overlay" onclick="toggleSidebar()"
            class="fixed inset-0 bg-black/60 backdrop-blur-sm z-40 hidden lg:hidden transition-opacity duration-300">
        </div>

        <!-- SIDEBAR -->
        <aside id="sidebar"
            class="premium-sidebar fixed lg:static z-50 h-full flex flex-col -translate-x-full lg:translate-x-0 overflow-y-auto scroll">

            <!-- BRAND -->
            <div class="px-8 py-8 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo.jpg') }}" alt="Aish Management" class="w-10 h-10 sm:w-12 sm:h-12 rounded-full object-contain bg-white shadow-lg p-0.5">
                    <div>
                        <h1 class="text-lg font-bold tracking-tight leading-none font-outfit">AISH</h1>
                        <p class="text-[10px] text-emerald-500 font-bold tracking-widest uppercase">Management</p>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4">
                <p class="text-[10px] font-black text-[var(--text-muted)] uppercase tracking-[0.2em] mb-4 ml-4">Main Menu</p>
                <nav class="space-y-1">
                    <a href="{{ route('admin.dashboard') }}"
                        onclick="if(window.innerWidth < 1024) toggleSidebar()"
                        class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <svg class="w-5 h-5 link-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('admin.menus.index') }}"
                        onclick="if(window.innerWidth < 1024) toggleSidebar()"
                        class="nav-link {{ request()->routeIs('admin.menus.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 link-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <span>Kelola Menu</span>
                    </a>
                    <a href="{{ route('admin.hours.index') }}"
                        onclick="if(window.innerWidth < 1024) toggleSidebar()"
                        class="nav-link {{ request()->routeIs('admin.hours.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 link-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Jam Operasional</span>
                    </a>
                    <a href="{{ route('admin.content.index') }}"
                        onclick="if(window.innerWidth < 1024) toggleSidebar()"
                        class="nav-link {{ request()->routeIs('admin.content.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 link-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <span>Konten Web</span>
                    </a>
                    <a href="{{ route('admin.gallery.index') }}"
                        onclick="if(window.innerWidth < 1024) toggleSidebar()"
                        class="nav-link {{ request()->routeIs('admin.gallery.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 link-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>Kelola Galeri</span>
                    </a>
                    <a href="{{ route('admin.special_packages.index') }}"
                        onclick="if(window.innerWidth < 1024) toggleSidebar()"
                        class="nav-link {{ request()->routeIs('admin.special_packages.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 link-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        <span>Kelola Paket</span>
                    </a>
                    <a href="{{ route('admin.orders.index') }}"
                        onclick="if(window.innerWidth < 1024) toggleSidebar()"
                        class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 link-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <span>Kelola Pesanan</span>
                    </a>
                    <a href="{{ route('admin.users.index') }}"
                        onclick="if(window.innerWidth < 1024) toggleSidebar()"
                        class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 link-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span>Kelola Pengguna</span>
                    </a>
                    <a href="{{ route('admin.chats.index') }}"
                        onclick="if(window.innerWidth < 1024) toggleSidebar()"
                        class="nav-link {{ request()->routeIs('admin.chats.*') ? 'active' : '' }} flex items-center justify-between">
                        <div class="flex items-center gap-3.5">
                            <svg class="w-5 h-5 link-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                            </svg>
                            <span>Pesan Masuk</span>
                        </div>
                        <span id="global-unread-badge" class="hidden px-2 py-0.5 text-[10px] font-black bg-rose-500 text-white rounded-full shadow-lg shadow-rose-500/20 animate-pulse">0</span>
                    </a>
                </nav>

                <p class="text-[10px] font-black text-[var(--text-muted)] uppercase tracking-[0.2em] mt-8 mb-4 ml-4">Account</p>
                <nav class="space-y-1">
                    <a href="{{ route('admin.profile') }}"
                        class="nav-link py-3 {{ request()->routeIs('admin.profile') ? 'active' : '' }}">
                        <svg class="w-5 h-5 link-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>Profil Saya</span>
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="p-0">
                        @csrf
                        <button type="submit"
                            class="w-[calc(100%-40px)] mx-5 flex items-center gap-3 px-5 py-3 text-xs font-bold text-rose-500 hover:bg-rose-500/10 rounded-xl transition group">
                            <svg class="w-5 h-5 group-hover:rotate-12 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span>Keluar Sistem</span>
                        </button>
                    </form>
                </nav>
            </div>

            <!-- FOOTER INFO -->
            <div class="mt-auto p-4 m-6 rounded-3xl bg-[var(--bg-main)] border border-[var(--border)] shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-emerald-500/20 flex items-center justify-center">
                        <div class="w-2 h-2 bg-emerald-500 rounded-full animate-ping"></div>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-[var(--text-main)]">Cloud Server</p>
                        <p class="text-[9px] text-emerald-500 font-medium tracking-wide">Connected & Secure</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- MAIN CONTENT AREA -->
        <div class="flex-1 flex flex-col relative min-w-0">

            <!-- HEADER -->
            <header class="h-16 glass-header flex items-center justify-between px-4 sm:px-6 lg:px-10 shrink-0 z-30">
                <button onclick="toggleSidebar()" class="lg:hidden text-[var(--text-main)] hover:text-emerald-500 transition">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h10M4 18h16" />
                    </svg>
                </button>

                <div class="hidden lg:flex items-center gap-3">
                    <span class="text-xs font-bold text-[var(--text-muted)] uppercase tracking-widest">Aish Dashboard</span>
                    <span class="text-[var(--text-muted)]">/</span>
                    <span class="text-xs font-bold text-emerald-500 uppercase tracking-widest">@yield('title')</span>
                </div>

                <div class="flex items-center gap-4">
                    <!-- SEARCH -->
                    <div
                        class="hidden md:flex items-center bg-[var(--bg-card)] border border-[var(--border)] rounded-2xl px-4 py-2 hover:border-emerald-500/30 transition duration-300">
                        <svg class="w-4 h-4 text-[var(--text-muted)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" id="global-search" placeholder="Cari data..."
                            class="bg-transparent border-none focus:ring-0 text-sm font-medium ml-3 w-48 placeholder:text-[var(--text-muted)] text-[var(--text-main)]">
                    </div>

                    <!-- THEME TOGGLE -->
                    <button id="theme-toggle" onclick="toggleAdminTheme()" class="w-10 h-10 flex items-center justify-center bg-[var(--bg-card)] border border-[var(--border)] rounded-2xl hover:border-emerald-500/30 transition-all duration-300 group shadow-sm">
                        <svg id="theme-icon-sun" class="w-5 h-5 text-amber-500 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 5a7 7 0 100 14 7 7 0 000-14z" />
                        </svg>
                        <svg id="theme-icon-moon" class="w-5 h-5 text-indigo-400 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>

                    <!-- USER -->
                    <div class="relative">
                        <button id="user-btn" onclick="toggleUserDropdown()"
                            class="flex items-center gap-3 hover:bg-black/5 dark:hover:bg-white/5 p-1.5 pr-4 rounded-2xl transition duration-300">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-emerald-500 to-emerald-300 p-[2px]">
                                <div
                                    class="w-full h-full bg-[var(--bg-main)] rounded-[10px] flex items-center justify-center overflow-hidden">
                                    @if(Auth::user()->profile_photo && file_exists(public_path('uploads/profile/' . Auth::user()->profile_photo)))
                                        <img src="{{ asset('uploads/profile/' . Auth::user()->profile_photo) }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-emerald-500 font-black text-xs uppercase">{{ substr(Auth::user()->name ?? 'A', 0, 1) }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="hidden sm:block text-left">
                                <p class="text-xs font-bold text-[var(--text-main)]">{{ Auth::user()->name ?? 'Administrator' }}</p>
                                <p class="text-[9px] text-emerald-500 font-bold uppercase tracking-tighter">Owner Aish
                                </p>
                            </div>
                        </button>

                        <!-- DROPDOWN -->
                        <div id="user-dropdown"
                            class="absolute right-0 mt-3 w-64 bg-[var(--bg-card)] border border-[var(--border)] rounded-3xl shadow-2xl p-4 hidden z-50">
                            <div class="p-3 border-b border-[var(--border)] mb-2">
                                <p class="text-xs font-bold text-[var(--text-main)]">{{ Auth::user()->email ??
                                    'aishcatering2@gmail.com' }}</p>
                                <p class="text-[10px] text-[var(--text-muted)] font-medium">Session Active: 2h 45m</p>
                            </div>
                            <a href="{{ route('admin.profile') }}"
                                class="flex items-center gap-3 p-3 rounded-2xl hover:bg-black/5 dark:hover:bg-white/5 text-sm font-semibold transition">
                                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Profil & Keamanan
                            </a>
                            <div class="h-px bg-black/5 dark:bg-white/5 my-2"></div>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left flex items-center gap-3 p-3 rounded-2xl hover:bg-rose-500/10 text-rose-500 text-xs font-bold transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    Keluar Console
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- VIEWPORT -->
            <main class="flex-1 overflow-y-auto p-0 sm:p-4 lg:p-10 scroll bg-[var(--bg-main)]">
                @yield('content')
            </main>

        </div>
    </div>

    @stack('scripts')

    <script>
        // Search functionality
        const searchInput = document.getElementById('global-search');
        searchInput?.addEventListener('input', function (e) {
            const term = e.target.value.toLowerCase();
            const tableRows = document.querySelectorAll('tbody tr, .searchable-card');

            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(term)) {
                    row.classList.remove('hidden');
                    row.style.opacity = '0';
                    setTimeout(() => row.style.opacity = '1', 50);
                } else {
                    row.classList.add('hidden');
                }
            });
        });

        // Sidebar Toggle for Mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            }
        }

        // User Dropdown Toggle
        function toggleUserDropdown() {
            const dropdown = document.getElementById('user-dropdown');
            dropdown.classList.toggle('hidden');
            
            // Close when clicking outside
            document.addEventListener('click', function closeDropdown(e) {
                if (!document.getElementById('user-btn').contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.classList.add('hidden');
                    document.removeEventListener('click', closeDropdown);
                }
            });
        }
    </script>
    <!-- Firebase Global SDK -->
    <script src="https://www.gstatic.com/firebasejs/9.0.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.0.0/firebase-messaging-compat.js"></script>

    <script>
        const firebaseConfig = {
            apiKey: "AIzaSyDzjDJDugIH8GWpisuAdYBqmKaAAkQQTMk",
            authDomain: "aishcathering-project.firebaseapp.com",
            projectId: "aishcathering-project",
            storageBucket: "aishcathering-project.firebasestorage.app",
            messagingSenderId: "175555114047",
            appId: "1:175555114047:web:c0b9b80267517003eab48a"
        };

        firebase.initializeApp(firebaseConfig);
        const messaging = firebase.messaging();
        const notificationSound = new Audio('/mixkit-bell-notification-933.wav');

        function playGlobalNotification() {
            notificationSound.currentTime = 0;
            notificationSound.play().catch(e => console.log("Audio blocked by browser policy"));
        }

        function showGlobalToast(title, body) {
            if (Notification.permission === 'granted') {
                new Notification(title, {
                    body: body,
                    icon: '/favicon.ico'
                }).onclick = () => {
                    window.focus();
                    window.location.href = '{{ route('admin.chats.index') }}';
                };
            }
        }

        // Handle foreground messages
        messaging.onMessage((payload) => {
            playGlobalNotification();
            showGlobalToast(payload.notification.title, payload.notification.body);
        });

        // Initialize FCM Token registration
        function initGlobalFirebase() {
            messaging.getToken({ vapidKey: 'BIBDUamdRKW9NM_QJYxCYFwNgzqonF8uSgDnXCPitTWG84_lo5oRKHHITfW5iwHZYRXVyG5yqwp59pOr8QVhk0Q' })
                .then((token) => {
                    if (token) {
                        fetch('{{ route('admin.chats.save_token') }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify({ token: token })
                        });
                    }
                }).catch(err => console.log('FCM Token Error:', err));
        }

        // Global Click to unlock audio
        document.addEventListener('click', function unlock() {
            notificationSound.play().then(() => {
                notificationSound.pause();
                document.removeEventListener('click', unlock);
            }).catch(() => {});
        }, { once: true });

        // Show permission modal on any admin page if not granted
        window.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('global-notif-modal');
            if (Notification.permission !== 'granted' && !localStorage.getItem('admin_notif_denied')) {
                setTimeout(() => {
                    modal?.classList.remove('hidden');
                }, 2000);
            }
        });

        // --- REAL-TIME GLOBAL UNREAD CHAT VIA WEBSOCKET ---
        let lastGlobalUnreadCount = null;

        async function checkGlobalUnread() {
            try {
                const response = await fetch('{{ route('admin.chats.unread_count') }}');
                if (response.ok) {
                    const data = await response.json();
                    const count = data.unread_count;
                    const badge = document.getElementById('global-unread-badge');

                    if (badge) {
                        if (count > 0) {
                            badge.innerText = count;
                            badge.classList.remove('hidden');
                        } else {
                            badge.classList.add('hidden');
                        }
                    }

                    // Alert on new incoming message
                    if (lastGlobalUnreadCount !== null && count > lastGlobalUnreadCount) {
                        // Play sound and notification if NOT in active chat room
                        const inChatRoom = document.getElementById('admin-chat-messages') !== null;
                        if (!inChatRoom) {
                            playGlobalNotification();
                            showGlobalToast("Pesan Masuk Baru", "Ada pesan baru dari pelanggan di Aish Catering.");
                        }
                    }

                    lastGlobalUnreadCount = count;
                }
            } catch (e) {
                console.log("Unread Fetch Error: ", e);
            }
        }

        // Initial check and register Echo listener
        window.addEventListener('DOMContentLoaded', () => {
            checkGlobalUnread();
            
            if (window.Echo) {
                window.Echo.channel('chats')
                    .listen('.ChatMessageSent', (e) => {
                        checkGlobalUnread();
                    })
                    .listen('.ChatMessageDeleted', (e) => {
                        checkGlobalUnread();
                    });
            }
        });

        // Long fallback poll (60s) instead of 3s
        setInterval(checkGlobalUnread, 60000);
    </script>

    <!-- Global Notif Permission Modal -->
    <div id="global-notif-modal" class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/80 backdrop-blur-md hidden">
        <div class="bg-[var(--bg-card)] border border-emerald-500/30 p-10 rounded-[3rem] shadow-2xl max-w-sm w-full text-center">
            <div class="w-24 h-24 bg-emerald-500/10 rounded-full flex items-center justify-center mx-auto mb-8 border border-emerald-500/20">
                <svg class="w-12 h-12 text-emerald-500 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
            </div>
            <h3 class="text-2xl font-black text-[var(--text-main)] mb-4 font-outfit">Aktifkan Notifikasi Real-Time?</h3>
            <p class="text-[var(--text-muted)] text-sm mb-10 leading-relaxed">Jangan lewatkan pesanan pelanggan. Aktifkan notifikasi Windows & suara sekarang.</p>
            
            <button onclick="Notification.requestPermission().then(p => { if(p==='granted') { initGlobalFirebase(); playGlobalNotification(); document.getElementById('global-notif-modal').remove(); } })" 
                    class="w-full py-5 bg-emerald-500 hover:bg-emerald-600 text-white font-black rounded-2xl transition-all shadow-xl shadow-emerald-500/30 active:scale-95 mb-4">
                AKTIFKAN SEKARANG
            </button>
            <button onclick="localStorage.setItem('admin_notif_denied', 'true'); document.getElementById('global-notif-modal').remove()" 
                    class="text-[var(--text-muted)] hover:text-[var(--text-main)] text-xs font-bold uppercase tracking-[0.2em] transition-all">
                Nanti Saja
            </button>
        </div>
    </div>
</body>

</html>