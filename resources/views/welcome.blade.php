@extends('layouts.app')

@section('content')
    <!-- Leaflet Map CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <style>
        @keyframes zoom-in {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .animate-zoom-in {
            animation: zoom-in 0.3s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }

        /* Hide scrollbar completely for horizontal scroll filters while maintaining scrolling */
        .scrollbar-hide::-webkit-scrollbar {
            display: none !important;
            width: 0 !important;
            height: 0 !important;
        }

        .scrollbar-hide {
            -ms-overflow-style: none !important;
            /* IE and Edge */
            scrollbar-width: none !important;
            /* Firefox */
        }

        /* Premium Modernized Category Filters Style */
        .filter-btn {
            backdrop-filter: blur(12px) !important;
            -webkit-backdrop-filter: blur(12px) !important;
            background: rgba(0, 0, 0, 0.03) !important;
            border: 1px solid rgba(0, 0, 0, 0.08) !important;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.05) !important;
            color: rgba(0, 0, 0, 0.6) !important;
            cursor: pointer;
        }

        html.dark .filter-btn {
            background: rgba(255, 255, 255, 0.03) !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1) !important;
            color: rgba(255, 255, 255, 0.6) !important;
        }

        .filter-btn:hover {
            transform: translateY(-2px) scale(1.02) !important;
            background: rgba(61, 140, 117, 0.12) !important;
            border-color: rgba(61, 140, 117, 0.4) !important;
            color: #3d8c75 !important;
            box-shadow: 0 0 15px rgba(61, 140, 117, 0.1) !important;
        }

        html.dark .filter-btn:hover {
            color: #a2dfcc !important;
            box-shadow: 0 0 15px rgba(61, 140, 117, 0.2) !important;
        }

        .filter-btn.active {
            transform: translateY(-2px) scale(1.05) !important;
            background: linear-gradient(135deg, #3d8c75, #1b4d3e) !important;
            border-color: rgba(61, 140, 117, 0.5) !important;
            color: white !important;
            box-shadow: 0 10px 25px -5px rgba(61, 140, 117, 0.4), 0 0 15px rgba(61, 140, 117, 0.2) !important;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1) !important;
        }

        /* Modal transitions */
        #menu-detail-modal.modal-active {
            opacity: 1 !important;
            pointer-events: auto !important;
        }
        #modal-content.modal-content-active {
            transform: translateY(0) scale(1) !important;
        }
    </style>
    <!-- Navbar -->
    <nav id="main-nav"
        class="fixed top-0 inset-x-0 z-[100] transition-all duration-500 border-b border-transparent bg-transparent py-3">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 sm:h-20 items-center relative">

                <!-- Logo -->
                <div class="flex items-center shrink-0">
                    <img src="{{ asset('images/logo.jpg') }}" alt="Aish Catering Logo"
                        class="h-10 sm:h-14 w-auto object-contain rounded-full bg-white shadow-sm p-0.5">
                </div>

                <!-- Desktop Nav Links (Absolute Center) -->
                <div
                    class="hidden lg:flex absolute left-1/2 -translate-x-1/2 items-center space-x-10 text-[13px] font-black uppercase tracking-widest text-slate-600 dark:text-slate-300">
                    <a href="#home" class="hover:text-emerald-500 transition-colors relative group">
                        Utama
                        <span
                            class="absolute -bottom-1.5 left-0 w-0 h-0.5 bg-emerald-500 transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="#menu" class="hover:text-emerald-500 transition-colors relative group">
                        Menu
                        <span
                            class="absolute -bottom-1.5 left-0 w-0 h-0.5 bg-emerald-500 transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="#about" class="hover:text-emerald-500 transition-colors relative group">
                        Tentang
                        <span
                            class="absolute -bottom-1.5 left-0 w-0 h-0.5 bg-emerald-500 transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="#download" class="hover:text-emerald-500 transition-colors relative group">
                        App
                        <span
                            class="absolute -bottom-1.5 left-0 w-0 h-0.5 bg-emerald-500 transition-all duration-300 group-hover:w-full"></span>
                    </a>
                </div>

                <!-- Desktop Utilities (Right) -->
                <div class="hidden lg:flex items-center space-x-4 shrink-0">
                    <!-- Operational Status -->
                    <a href="#operational" class="group relative cursor-pointer">
                        <div id="op-status-pill"
                            class="flex items-center space-x-2.5 px-4 py-2 rounded-[1rem] bg-slate-100/80 backdrop-blur-md border border-slate-200/50 transition-all duration-500 hover:scale-105 hover:shadow-lg shadow-sm">
                            <span class="relative flex h-2 w-2">
                                <span id="op-status-ping"
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75"></span>
                                <span id="op-status-dot" class="relative inline-flex rounded-full h-2 w-2"></span>
                            </span>
                            <span id="op-status-text"
                                class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-500">Checking...</span>
                        </div>
                    </a>

                    <!-- Theme Toggle -->
                    <button id="theme-toggle"
                        class="w-10 h-10 rounded-[1rem] bg-slate-100/80 dark:bg-slate-800/80 backdrop-blur-md border border-slate-200/50 dark:border-slate-700/50 flex items-center justify-center text-slate-500 dark:text-yellow-400 hover:scale-110 hover:shadow-lg transition-all shadow-sm">
                        <svg id="theme-toggle-dark-icon" class="hidden w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                        </svg>
                        <svg id="theme-toggle-light-icon" class="hidden w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                                fill-rule="evenodd" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>

                <!-- Mobile: right-side actions -->
                <div class="flex items-center gap-3 lg:hidden">
                    <!-- Theme toggle (mobile) -->
                    <button id="theme-toggle-mobile"
                        class="w-9 h-9 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 hover:scale-110 transition-all">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                                fill-rule="evenodd" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <!-- Hamburger button -->
                    <button id="mobile-menu-btn"
                        class="w-9 h-9 rounded-xl flex items-center justify-center text-slate-600 transition-all duration-200 hover:bg-slate-100"
                        onclick="toggleMobileMenu()" aria-label="Toggle menu">
                        <!-- Hamburger icon -->
                        <svg id="icon-hamburger" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M4 6h16M4 12h16M4 18h16" stroke-width="2.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                        <!-- Close icon -->
                        <svg id="icon-close" class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M6 18L18 6M6 6l12 12" stroke-width="2.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Drawer -->
        <div id="mobile-menu" class="lg:hidden overflow-hidden transition-all duration-300" style="max-height:0;opacity:0;">
            <div class="px-4 pb-6 pt-2 space-y-1 border-t border-slate-100 bg-white/95 backdrop-blur-lg">
                <a href="#home" onclick="closeMobileMenu()"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-slate-700 hover:bg-green-50 hover:text-green-600 transition-all">
                    🏠 Utama
                </a>
                <a href="#menu" onclick="closeMobileMenu()"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-slate-700 hover:bg-green-50 hover:text-green-600 transition-all">
                    🍱 Menu Katering
                </a>
                <a href="#about" onclick="closeMobileMenu()"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-slate-700 hover:bg-green-50 hover:text-green-600 transition-all">
                    ℹ️ Tentang Kami
                </a>
                <a href="#calculator" onclick="closeMobileMenu()"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-slate-700 hover:bg-green-50 hover:text-green-600 transition-all">
                    🔢 Kalkulator Porsi
                </a>
                <a href="#download" onclick="closeMobileMenu()"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-slate-700 hover:bg-green-50 hover:text-green-600 transition-all">
                    📱 Download App
                </a>

                <!-- Divider -->
                <div class="my-3" style="height:1px;background:rgba(0,0,0,0.06);"></div>

                <!-- WhatsApp CTA -->
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $contents['whatsapp_number'] ?? '628123456789') }}"
                    class="flex items-center justify-center gap-2 w-full py-3 rounded-2xl font-bold text-white text-sm transition-all"
                    style="background:linear-gradient(135deg,#22c55e,#16a34a);box-shadow:0 6px 20px rgba(34,197,94,0.3);">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                    </svg>
                    Pesan via WhatsApp
                </a>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu Overlay -->
    <div id="mobile-overlay" class="fixed inset-0 z-40 lg:hidden pointer-events-none"
        style="background:rgba(0,0,0,0);transition:background 0.3s;" onclick="closeMobileMenu()">
    </div>

    <script>
        let mobileMenuOpen = false;

        function toggleMobileMenu() {
            mobileMenuOpen ? closeMobileMenu() : openMobileMenu();
        }

        function openMobileMenu() {
            mobileMenuOpen = true;
            const menu = document.getElementById('mobile-menu');
            const overlay = document.getElementById('mobile-overlay');
            const hamburger = document.getElementById('icon-hamburger');
            const close = document.getElementById('icon-close');

            menu.style.maxHeight = menu.scrollHeight + 'px';
            menu.style.opacity = '1';
            overlay.style.background = 'rgba(0,0,0,0.4)';
            overlay.style.pointerEvents = 'auto';
            hamburger.classList.add('hidden');
            close.classList.remove('hidden');
        }

        function closeMobileMenu() {
            mobileMenuOpen = false;
            const menu = document.getElementById('mobile-menu');
            const overlay = document.getElementById('mobile-overlay');
            const hamburger = document.getElementById('icon-hamburger');
            const close = document.getElementById('icon-close');

            menu.style.maxHeight = '0';
            menu.style.opacity = '0';
            overlay.style.background = 'rgba(0,0,0,0)';
            overlay.style.pointerEvents = 'none';
            hamburger.classList.remove('hidden');
            close.classList.add('hidden');
        }
        // Sync mobile theme toggle with desktop
        document.getElementById('theme-toggle-mobile').addEventListener('click', function () {
            document.getElementById('theme-toggle').click();
        });
    </script>


    <!-- Hero Section — Full Screen Cinematic -->
    <header id="home" class="relative overflow-hidden bg-slate-50 dark:bg-[#09090b]" style="min-height:100vh;display:flex;flex-direction:column;">

        <!-- Ambient Decorative Orbs for solid background -->
        <div class="absolute inset-0 pointer-events-none overflow-hidden">
            <div class="absolute -top-40 -right-40 w-[600px] h-[600px] rounded-full opacity-25 dark:opacity-10"
                style="background: radial-gradient(circle, rgba(34,197,94,0.15), transparent 70%); filter: blur(80px);"></div>
            <div class="absolute top-1/2 -left-40 w-[600px] h-[600px] rounded-full opacity-20 dark:opacity-10"
                style="background: radial-gradient(circle, rgba(249,115,22,0.12), transparent 70%); filter: blur(80px);"></div>
        </div>

        <!-- Main Content — Centered -->
        <div class="relative z-10 flex flex-col items-center justify-center flex-1 px-4 sm:px-6 text-center pb-8 lg:pb-[280px]"
            style="padding-top: clamp(4rem, 10vh, 8rem);">

            <!-- Eyebrow badges -->
            <div class="flex flex-col sm:flex-row flex-wrap justify-center gap-1.5 sm:gap-3 mb-4 sm:mb-8">
                <div class="inline-flex items-center justify-center gap-1.5 px-3 sm:px-4 py-1.5 sm:py-2 rounded-full text-[9px] sm:text-xs font-bold uppercase tracking-wider bg-emerald-500/10 dark:bg-emerald-500/15 border border-emerald-500/20 dark:border-emerald-500/40 text-emerald-600 dark:text-emerald-400 backdrop-blur-md">
                    <span class="w-1 h-1 sm:w-1.5 sm:h-1.5 rounded-full animate-pulse bg-emerald-500 dark:bg-emerald-400"></span>
                    🌟 Katering No. 1
                </div>
                <div class="inline-flex items-center justify-center gap-1.5 px-3 sm:px-4 py-1.5 sm:py-2 rounded-full text-[9px] sm:text-xs font-bold uppercase tracking-wider bg-slate-200/50 dark:bg-white/10 border border-slate-300/50 dark:border-white/20 text-slate-700 dark:text-white backdrop-blur-md">
                    🥗 Halal & Higienis
                </div>
            </div>

            <!-- Main heading -->
            <h1 class="font-poppins font-extrabold text-slate-900 dark:text-white reveal-text px-2"
                style="font-size:clamp(2rem,7vw,6.5rem);line-height:1.08;max-width:900px;">
                @if(isset($contents['hero_title_1']))
                    {!! $contents['hero_title_1'] !!} &amp; {!! $contents['hero_title_2'] !!}
                @else
                    Hidangan <span
                        style="background:linear-gradient(135deg,#22c55e,#86efac);-webkit-background-clip:text;-webkit-text-fill-color:transparent;">Sehat</span>
                    &amp; <span
                        style="background:linear-gradient(135deg,#f97316,#fdba74);-webkit-background-clip:text;-webkit-text-fill-color:transparent;">Lezat</span><br>Untuk
                    Anda.
                @endif
            </h1>



            <!-- CTA Buttons -->
            <div
                class="flex flex-col sm:flex-row flex-wrap justify-center gap-2.5 sm:gap-4 mt-6 sm:mt-10 w-full max-w-[240px] sm:max-w-none mx-auto">
                <a href="#menu"
                    class="inline-flex items-center justify-center gap-2 w-full sm:w-auto px-5 sm:px-8 py-3 sm:py-4 rounded-full font-bold text-white text-xs sm:text-sm transition-all duration-300 hover:scale-105"
                    style="background:linear-gradient(135deg,#22c55e,#16a34a);box-shadow:0 8px 32px rgba(34,197,94,0.4);">
                    Lihat Menu
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path d="M9 5l7 7-7 7" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $contents['whatsapp_number'] ?? '628123456789') }}"
                    class="inline-flex items-center justify-center gap-2 w-full sm:w-auto px-5 sm:px-8 py-3 sm:py-4 rounded-full font-bold text-xs sm:text-sm transition-all duration-300 hover:scale-105 bg-slate-200/50 dark:bg-white/10 border border-slate-300/50 dark:border-white/20 text-slate-700 dark:text-white backdrop-blur-md">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                    </svg>
                    WhatsApp
                </a>
                <a href="#download"
                    class="inline-flex items-center justify-center gap-2 w-full sm:w-auto px-5 sm:px-8 py-3 sm:py-4 rounded-full font-bold text-xs transition-all duration-300 hover:scale-105 bg-[#f97316]/10 dark:bg-[#f97316]/15 border border-[#f97316]/20 dark:border-[#f97316]/40 text-[#f97316] dark:text-[#fb923c] backdrop-blur-md">
                    📱 Download App
                </a>
            </div>

            <!-- Scroll Indicator — hidden on mobile -->
            <div class="hidden sm:flex mt-10 flex-col items-center gap-2 text-slate-400 dark:text-white/35">
                <span style="font-size:10px;letter-spacing:0.2em;text-transform:uppercase;">Scroll</span>
                <svg class="w-5 h-5 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>
        </div>

        <!-- Bottom Stats Bar — relative on mobile, absolute on desktop -->
        <div class="relative lg:absolute lg:bottom-0 left-0 right-0 z-20 w-full" id="stats-section">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 py-4 sm:py-5">
                <div class="grid lg:grid-cols-[1.05fr_2fr] rounded-[1.6rem] sm:rounded-[2.5rem] overflow-hidden bg-white/80 dark:bg-[#18181b]/80 border border-slate-200 dark:border-white/10 backdrop-blur-2xl shadow-xl dark:shadow-2xl">

                    <div class="p-5 sm:p-8 lg:p-10 border-b lg:border-b-0 lg:border-r border-slate-200 dark:border-white/5 bg-slate-50/50 dark:bg-black/30">
                        <p class="text-[10px] sm:text-xs font-black uppercase tracking-[0.22em] text-emerald-600 dark:text-[#5ce9c5]">
                            Layanan Acara
                        </p>
                        <h2 class="mt-2 sm:mt-3 font-poppins font-black text-2xl sm:text-4xl leading-tight text-slate-900 dark:text-white">
                            Siap dari brief sampai tersaji.
                        </h2>
                        <p class="mt-3 text-[12px] sm:text-sm leading-relaxed text-slate-600 dark:text-white/60">
                            Tim AISH bantu rapikan menu, porsi, dan jadwal pengantaran agar persiapan acara lebih tenang.
                        </p>
                    </div>

                    <div class="grid grid-cols-2 lg:grid-cols-4">
                        <div class="group min-h-[135px] p-4 sm:p-6 border-r border-b sm:border-r lg:border-b-0 border-slate-200 dark:border-white/5 bg-slate-50/30 dark:bg-black/20">
                            <div
                                class="w-10 h-10 sm:w-11 sm:h-11 rounded-2xl bg-emerald-100 dark:bg-[#5ce9c5]/15 text-emerald-600 dark:text-[#5ce9c5] flex items-center justify-center mb-3 sm:mb-4 group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path d="M8 10h8M8 14h5M7 4h10a3 3 0 013 3v8a3 3 0 01-3 3h-4l-4 3v-3H7a3 3 0 01-3-3V7a3 3 0 013-3z"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <h3 class="font-bold text-[13px] sm:text-base text-slate-800 dark:text-white leading-snug">Konsultasi Menu</h3>
                            <p class="mt-2 text-[10px] sm:text-xs leading-relaxed text-slate-500 dark:text-white/55">
                                Rekomendasi hidangan sesuai tema dan tamu.
                            </p>
                        </div>

                        <div class="group min-h-[135px] p-4 sm:p-6 border-b sm:border-r lg:border-b-0 border-slate-200 dark:border-white/5 bg-slate-50/20 dark:bg-black/16">
                        <p
                            class="hidden">
                            <span data-target="4.9" data-decimals="1">0.0</span>
                            <span class="text-xl sm:text-2xl leading-none">⭐</span>
                        </p>
                        <p
                            style="display:none;font-size:10px;color:rgba(255,255,255,0.5);text-transform:uppercase;letter-spacing:0.15em;margin-top:6px;font-weight:700;">
                            
                        </p>
                            <div
                                class="w-10 h-10 sm:w-11 sm:h-11 rounded-2xl bg-orange-100 dark:bg-[#ffa043]/15 text-orange-600 dark:text-[#ffa043] flex items-center justify-center mb-3 sm:mb-4 group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path d="M4 7h16M7 7v13m10-13v13M8 11h8M8 15h8M6 4h12a2 2 0 012 2v14H4V6a2 2 0 012-2z"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <h3 class="font-bold text-[13px] sm:text-base text-slate-800 dark:text-white leading-snug">Porsi Fleksibel</h3>
                            <p class="mt-2 text-[10px] sm:text-xs leading-relaxed text-slate-500 dark:text-white/55">
                                Bisa disesuaikan untuk acara kecil sampai besar.
                            </p>
                    </div>

                        <div class="group min-h-[135px] p-4 sm:p-6 border-r sm:border-b-0 border-slate-200 dark:border-white/5 bg-slate-50/30 dark:bg-black/20">
                        <p
                            class="hidden">
                            <span data-target="10">0</span>+
                        </p>
                        <p
                            style="display:none;font-size:10px;color:rgba(255,255,255,0.5);text-transform:uppercase;letter-spacing:0.15em;margin-top:6px;font-weight:700;">
                            
                        </p>
                            <div
                                class="w-10 h-10 sm:w-11 sm:h-11 rounded-2xl bg-amber-100 dark:bg-[#ffdc43]/15 text-amber-600 dark:text-[#ffdc43] flex items-center justify-center mb-3 sm:mb-4 group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path d="M20 12v7a2 2 0 01-2 2H6a2 2 0 01-2-2v-7m16 0H4m16 0l-2-6H6l-2 6m5 0v9m6-9v9"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <h3 class="font-bold text-[13px] sm:text-base text-slate-800 dark:text-white leading-snug">Packing Rapi</h3>
                            <p class="mt-2 text-[10px] sm:text-xs leading-relaxed text-slate-500 dark:text-white/55">
                                Kemasan siap dibawa dan tetap nyaman disajikan.
                            </p>
                    </div>
                        <div class="group min-h-[135px] p-4 sm:p-6 bg-slate-50/20 dark:bg-black/16">
                            <div
                                class="w-10 h-10 sm:w-11 sm:h-11 rounded-2xl bg-violet-100 dark:bg-[#c4b5fd]/15 text-violet-600 dark:text-[#c4b5fd] flex items-center justify-center mb-3 sm:mb-4 group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path d="M12 8v5l3 2m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <h3 class="font-bold text-[13px] sm:text-base text-slate-800 dark:text-white leading-snug">Jadwal Terarah</h3>
                            <p class="mt-2 text-[10px] sm:text-xs leading-relaxed text-slate-500 dark:text-white/55">
                                Produksi dan pengantaran dirapikan sejak awal.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Menu Section -->

    <section id="menu" class="py-28 relative overflow-hidden reveal bg-white dark:bg-transparent">
        <!-- Decorative background orbs -->
        <div class="absolute top-0 left-0 w-full h-full pointer-events-none overflow-hidden">
            <div class="absolute -top-32 -left-32 w-[500px] h-[500px] rounded-full opacity-0 dark:opacity-20"
                style="background: radial-gradient(circle, #3d8c75, transparent 70%); filter: blur(60px);"></div>
            <div class="absolute -bottom-32 -right-32 w-[500px] h-[500px] rounded-full opacity-0 dark:opacity-15"
                style="background: radial-gradient(circle, #f97316, transparent 70%); filter: blur(80px);"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[700px] h-[700px] rounded-full opacity-0 dark:opacity-5"
                style="background: radial-gradient(circle, #a2dfcc, transparent 70%); filter: blur(100px);"></div>
        </div>

        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">
            <!-- Section Header -->
            <div class="text-center mb-20 space-y-5 reveal-text">
                <div class="inline-flex items-center gap-2 px-5 py-2 rounded-full text-xs font-bold uppercase tracking-widest"
                    style="background: rgba(249,115,22,0.15); border: 1px solid rgba(249,115,22,0.3); color: #fb923c;">
                    <span class="w-1.5 h-1.5 rounded-full bg-orange-400 animate-pulse"></span>
                    Pilihan Menu
                </div>
                <h2 class="text-4xl lg:text-6xl font-poppins font-extrabold text-slate-900 dark:text-white leading-tight">
                    Menu Katering <span
                        style="background: linear-gradient(90deg, #3d8c75, #1b4d3e); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Favorit</span>
                </h2>
                <p class="text-slate-500 dark:text-slate-400 text-lg max-w-xl mx-auto">Pilih dari ragam menu terbaik kami,
                    segar & lezat untuk
                    setiap momen.</p>
            </div>

            <!-- Search & Filter Controls -->
            <div class="mb-16">
                <div
                    class="flex flex-col lg:flex-row gap-6 items-center justify-between p-6 rounded-3xl bg-slate-50 border border-slate-200 dark:bg-white/5 dark:border-white/10 backdrop-blur-md">
                    <!-- Categories with Horizontal Scroll on Mobile -->
                    <div class="w-full lg:w-auto overflow-x-auto pb-4 lg:pb-0 scrollbar-hide snap-x"
                        id="category-filters-container">
                        <div class="flex items-center gap-2 min-w-max justify-start lg:justify-start px-2 lg:px-0"
                            id="category-filters">
                            <button
                                class="filter-btn active px-5 py-2.5 rounded-full text-sm font-bold transition-all duration-300 snap-start"
                                data-category="all">✦ Semua</button>
                            <button
                                class="filter-btn px-5 py-2.5 rounded-full text-sm font-bold transition-all duration-300 snap-start"
                                data-category="nasi-box">🍱 Nasi Box</button>
                            <button
                                class="filter-btn px-5 py-2.5 rounded-full text-sm font-bold transition-all duration-300 snap-start"
                                data-category="nasi-kotak">🍱 Nasi Kotak</button>
                            <button
                                class="filter-btn px-5 py-2.5 rounded-full text-sm font-bold transition-all duration-300 snap-start"
                                data-category="tumpeng">🎋 Tumpeng</button>
                            <button
                                class="filter-btn px-5 py-2.5 rounded-full text-sm font-bold transition-all duration-300 snap-start"
                                data-category="prasmanan">🍽️ Prasmanan</button>
                            <button
                                class="filter-btn px-5 py-2.5 rounded-full text-sm font-bold transition-all duration-300 snap-start"
                                data-category="snack">☕ Snack</button>
                            <button
                                class="filter-btn px-5 py-2.5 rounded-full text-sm font-bold transition-all duration-300 snap-start"
                                data-category="minuman">🧋 Minuman</button>
                            <button
                                class="filter-btn px-5 py-2.5 rounded-full text-sm font-bold transition-all duration-300 snap-start"
                                data-category="aqiqah">🐑 Aqiqah</button>
                            <button
                                class="filter-btn px-5 py-2.5 rounded-full text-sm font-bold transition-all duration-300 snap-start"
                                data-category="paket-hemat">🏷️ Paket Hemat</button>
                            <button
                                class="filter-btn px-5 py-2.5 rounded-full text-sm font-bold transition-all duration-300 snap-start"
                                data-category="lauk-pauk">🍗 Lauk Pauk</button>
                            <button
                                class="filter-btn px-5 py-2.5 rounded-full text-sm font-bold transition-all duration-300 snap-start"
                                data-category="lain-lain">🎁 Lain-lain</button>
                        </div>
                    </div>

                    <!-- Ultra-Minimalist Search Bar -->
                    <div class="w-full lg:w-[400px] relative group">
                        <div class="absolute inset-y-0 left-0 pl-0 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400 dark:text-white/30 group-focus-within:text-emerald-500 dark:group-focus-within:text-emerald-400 transition-all duration-500 transform group-focus-within:scale-110"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="2.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <input type="text" id="menu-search" placeholder="Cari menu favorit Anda..."
                            class="block w-full bg-transparent border-b border-slate-300 dark:border-white/10 focus:border-emerald-500 pl-10 pr-4 py-3 text-sm font-bold tracking-wide transition-all duration-500 outline-none text-slate-900 dark:text-white"
                            style="caret-color: #3d8c75;">
                        <!-- Animated glowing underline -->
                        <div
                            class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-green-500 to-emerald-400 transition-all duration-700 group-focus-within:w-full shadow-[0_0_15px_rgba(34,197,94,0.5)]">
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-10" id="menu-grid">
                @forelse($allMenus as $menu)
                    @php
                        $predefined = ['Prasmanan', 'Nasi Box', 'Nasi Kotak', 'Tumpeng', 'Snack', 'Minuman', 'Aqiqah', 'Lauk Pauk'];
                        $categorySlug = in_array($menu->category, $predefined) ? Str::slug($menu->category) : 'lain-lain';
                    @endphp
                    <div class="menu-item group relative rounded-2xl sm:rounded-[2.5rem] overflow-hidden transition-all duration-700 hover:-translate-y-3 cursor-pointer bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 shadow-sm hover:shadow-xl dark:shadow-none dark:backdrop-blur-md"
                        onclick="openMenuDetail(this, event)"
                        data-category="{{ $categorySlug }}" 
                        data-title="{{ $menu->name }}"
                        data-menu="{{ json_encode([
                            'name' => $menu->name,
                            'category' => $menu->category,
                            'description' => $menu->description,
                            'price' => number_format($menu->price, 0, ',', '.'),
                            'image_url' => $menu->image_url ? asset($menu->image_url) : 'https://images.unsplash.com/photo-1547592166-23ac45744acd?q=80&w=800',
                            'is_available' => $menu->is_available,
                            'is_featured' => $menu->is_featured,
                            'rating' => (float)$menu->rating,
                            'sold' => (int)$menu->sold
                        ]) }}">

                        <!-- Image Container -->
                        <div class="relative h-40 sm:h-64 overflow-hidden">
                            <img src="{{ $menu->image_url ? asset($menu->image_url) : 'https://images.unsplash.com/photo-1547592166-23ac45744acd?q=80&w=800' }}"
                                alt="{{ $menu->name }}"
                                class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110 group-hover:rotate-1 {{ !$menu->is_available ? 'grayscale opacity-40' : '' }}">

                            <!-- Premium Overlays -->
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-80">
                            </div>

                            <!-- Floating Badges -->
                            <div class="absolute top-2 left-2 sm:top-5 sm:left-5 flex flex-wrap gap-1 sm:gap-2">
                                <div
                                    class="px-2 py-1 sm:px-4 sm:py-1.5 rounded-full text-[7px] sm:text-[10px] font-black uppercase tracking-widest backdrop-blur-md shadow-lg border border-white/20 bg-emerald-500/90 text-white">
                                    {{ $menu->category }}
                                </div>
                                @if($menu->is_featured)
                                    <div
                                        class="px-2 py-1 sm:px-4 sm:py-1.5 rounded-full text-[7px] sm:text-[10px] font-black uppercase tracking-widest backdrop-blur-md shadow-lg border border-white/20 bg-orange-500/90 text-white">
                                        ✨ Recommended
                                    </div>
                                @endif
                                @if(!$menu->is_available)
                                    <div
                                        class="px-2 py-1 sm:px-4 sm:py-1.5 rounded-full text-[7px] sm:text-[10px] font-black uppercase tracking-widest backdrop-blur-md shadow-lg border border-rose-500/20 animate-pulse bg-rose-500/90 text-white">
                                        🚫 Kosong
                                    </div>
                                @endif
                            </div>

                            <!-- Interactive Reveal Info -->
                            <div
                                class="absolute bottom-5 left-5 right-5 translate-y-2 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-500">
                                <div
                                    class="flex items-center gap-2 text-white/90 text-[10px] font-bold uppercase tracking-widest">
                                    <svg class="w-3 h-3 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    Terlaris Bulan Ini
                                </div>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-3 sm:p-8 pt-4 sm:pt-6 space-y-2 sm:space-y-4">
                            <div class="space-y-1">
                                <h4
                                    class="text-sm sm:text-xl font-poppins font-black text-slate-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors duration-300 line-clamp-1">
                                    {{ $menu->name }}
                                </h4>
                                <!-- Rating and Sold (GrabFood Style) -->
                                <div class="flex items-center gap-1.5 text-[10px] sm:text-xs text-slate-500 dark:text-slate-400 font-medium">
                                    <span class="text-amber-500">⭐</span>
                                    <span class="font-bold text-slate-800 dark:text-slate-200">{{ number_format($menu->rating, 1) }}</span>
                                    <span class="text-slate-300 dark:text-slate-600">•</span>
                                    <span>{{ $menu->sold > 0 ? $menu->sold . '+' : '0' }} terjual</span>
                                </div>
                                <p
                                    class="text-[9px] sm:text-xs font-medium leading-relaxed line-clamp-1 sm:line-clamp-2 text-slate-500 dark:text-white/50 mt-1">
                                    {{ $menu->description }}
                                </p>
                            </div>

                            <div
                                class="flex flex-col sm:flex-row items-start sm:items-end justify-between pt-3 sm:pt-5 border-t border-slate-100 dark:border-white/10 gap-3 sm:gap-5">
                                <div class="flex flex-col flex-shrink-0">
                                    <span
                                        class="text-[7px] sm:text-[9px] font-bold uppercase tracking-[0.2em] text-slate-400 dark:text-white/40 mb-0.5 sm:mb-1">Investasi
                                        Rasa</span>
                                    <div class="flex items-baseline gap-1 whitespace-nowrap group/price">
                                        <span
                                            class="text-xs sm:text-base font-bold text-emerald-600 dark:text-emerald-400/80 group-hover/price:text-emerald-500 transition-colors">Rp</span>
                                        <span
                                            class="text-lg sm:text-3xl font-black font-poppins tracking-tight text-emerald-600 dark:text-emerald-300 drop-shadow-sm">
                                            {{ number_format($menu->price, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Modern App Link Button -->
                                @if($menu->is_available)
                                    <a href="#download" onclick="event.stopPropagation();"
                                        class="w-full sm:w-auto flex items-center justify-center gap-1.5 px-3 py-2.5 sm:px-6 sm:py-3 rounded-xl sm:rounded-2xl text-[8px] sm:text-[10px] font-black uppercase tracking-widest transition-all duration-500 hover:scale-105 active:scale-95 group/btn bg-gradient-to-r from-emerald-500 to-emerald-600 dark:from-[#3d8c75] dark:to-[#1b4d3e] text-white shadow-lg shadow-emerald-500/30">
                                        <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 transition-transform group-hover/btn:rotate-12"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" stroke-width="2.5"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <span class="whitespace-nowrap">Pesan</span>
                                    </a>
                                @else
                                    <button disabled onclick="event.stopPropagation();"
                                        class="w-full sm:w-auto flex items-center justify-center gap-1.5 px-3 py-2.5 sm:px-6 sm:py-3 rounded-xl sm:rounded-2xl text-[8px] sm:text-[10px] font-black uppercase tracking-widest opacity-40 cursor-not-allowed bg-slate-100 dark:bg-white/5 text-slate-400 dark:text-white/30 border border-slate-200 dark:border-white/10">
                                        <span class="whitespace-nowrap">Kosong</span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-24 text-center">
                        <div class="w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6 text-4xl"
                            style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);">🍽️</div>
                        <p class="text-lg font-semibold" style="color: rgba(255,255,255,0.4);">Menu belum tersedia.</p>
                        <p class="text-sm mt-2" style="color: rgba(255,255,255,0.25);">Segera hadir menu-menu lezat pilihan
                            kami.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination Controls -->
            <div id="menu-pagination" class="flex justify-center items-center gap-1 sm:gap-2 mt-12 sm:mt-16">
                <!-- Will be dynamically populated by JS -->
            </div>

            <!-- No Results Message -->
            <div id="no-results" class="hidden py-24 text-center animate-reveal">
                <div class="w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6"
                    style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);">
                    <svg class="w-10 h-10" style="color: rgba(255,255,255,0.2);" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </div>
                <h4 class="text-xl font-bold text-white mb-2">Menu tidak ditemukan</h4>
                <p style="color: rgba(255,255,255,0.4);">Coba gunakan kata kunci lain atau pilih kategori yang berbeda.</p>
            </div>
        </div>
    </section>

    <!-- About Section — Redesigned for Premium Look -->
    <section id="about" class="py-24 bg-white overflow-hidden reveal">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-10 lg:gap-20 items-center">
                <!-- Left: Interactive Visuals -->
                <div class="lg:w-1/2 relative">
                    <!-- Main Image with Decorative Frame -->
                    <div
                        class="relative z-10 rounded-[3rem] overflow-hidden shadow-2xl transform transition-transform hover:scale-[1.02] duration-700">
                        @php
                            $aboutImg = $contents['about_image'] ?? 'uploads/contents/about.png';
                            if ($aboutImg === 'assets/img/about.png') {
                                $aboutImg = 'uploads/contents/about.png';
                            }
                            $aboutImgUrl = filter_var($aboutImg, FILTER_VALIDATE_URL) ? $aboutImg : asset($aboutImg);
                        @endphp
                        <img src="{{ $aboutImgUrl }}" alt="Modern Catering Kitchen"
                            class="w-full h-[300px] sm:h-[500px] object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                    </div>

                    <!-- Floating Experience Card -->
                    <div
                        class="absolute -bottom-10 -right-10 z-20 bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] shadow-2xl border border-slate-100 dark:border-slate-800 animate-bounce-subtle hidden sm:block">
                        <div class="flex flex-col items-center text-center space-y-1">
                            <span class="text-5xl font-black text-green-500">8+</span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tahun
                                Berpengalaman</span>
                        </div>
                    </div>

                    <!-- Decorative Background Elements -->
                    <div
                        class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[120%] h-[120%] bg-green-50 rounded-full -z-10 blur-3xl opacity-50">
                    </div>
                </div>

                <!-- Right: Modern Bento Grid Content -->
                <div class="lg:w-1/2 space-y-6 sm:space-y-10">
                    <div class="space-y-4">
                        <div
                            class="inline-flex items-center gap-2 px-4 py-2 bg-green-50 text-green-600 rounded-full text-[10px] font-black uppercase tracking-widest">
                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-ping"></span>
                            Tentang Kami
                        </div>
                        <h3
                            class="text-3xl sm:text-6xl font-poppins font-black text-slate-900 leading-tight sm:leading-[1.1]">
                            {{ $contents['about_title'] ?? 'Kenapa AISH Catering?' }}
                        </h3>
                        <p class="text-sm sm:text-lg text-slate-500 leading-relaxed max-w-xl">
                            {{ $contents['about_description'] ?? 'Kami percaya bahwa makanan lezat berawal dari bahan yang segar dan diolah dengan penuh kasih. Sejak 2018, AISH Catering telah melayani ribuan acara di Singkawang dengan standar kualitas tinggi.' }}
                        </p>
                    </div>

                    <!-- Bento Grid Features -->
                    <div class="grid grid-cols-2 lg:grid-cols-2 gap-3 sm:gap-6">
                        <div
                            class="group p-4 sm:p-8 bg-slate-50 rounded-2xl sm:rounded-[2.5rem] border border-transparent hover:border-green-100 hover:bg-white hover:shadow-xl transition-all duration-500">
                            <div
                                class="w-10 h-10 sm:w-14 sm:h-14 bg-green-500 text-white rounded-xl sm:rounded-2xl flex items-center justify-center shadow-lg shadow-green-200 mb-4 sm:mb-6 group-hover:rotate-12 transition-transform">
                                <svg class="w-5 h-5 sm:w-7 sm:h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <h4 class="text-sm sm:text-lg font-bold text-slate-900 mb-1 sm:mb-2">100% Higienis</h4>
                            <p
                                class="text-[10px] sm:text-sm text-slate-500 leading-relaxed line-clamp-2 sm:line-clamp-none">
                                Proses pengolahan steril dan aman.</p>
                        </div>

                        <div
                            class="group p-4 sm:p-8 bg-slate-50 rounded-2xl sm:rounded-[2.5rem] border border-transparent hover:border-orange-100 hover:bg-white hover:shadow-xl transition-all duration-500">
                            <div
                                class="w-10 h-10 sm:w-14 sm:h-14 bg-orange-500 text-white rounded-xl sm:rounded-2xl flex items-center justify-center shadow-lg shadow-orange-200 mb-4 sm:mb-6 group-hover:-rotate-12 transition-transform">
                                <svg class="w-5 h-5 sm:w-7 sm:h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path d="M13 10V3L4 14h7v7l9-11h-7z" stroke-width="2.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </div>
                            <h4 class="text-sm sm:text-lg font-bold text-slate-900 mb-1 sm:mb-2">Harga Terbaik</h4>
                            <p
                                class="text-[10px] sm:text-sm text-slate-500 leading-relaxed line-clamp-2 sm:line-clamp-none">
                                Hidangan premium harga bersahabat.</p>
                        </div>
                    </div>

                    <!-- Call to Action -->
                    <div class="pt-4">
                        <a href="#menu"
                            class="group inline-flex items-center gap-4 text-sm font-black uppercase tracking-widest text-slate-900 hover:text-green-600 transition-colors">
                            Lihat Menu Pilihan Kami
                            <span
                                class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center group-hover:bg-green-500 group-hover:text-white transition-all duration-500">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path d="M17 8l4 4m0 0l-4 4m4-4H3" stroke-width="2.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Packages by Event Section -->
    <section id="event-packages" class="py-24 bg-white dark:bg-slate-900 reveal transition-colors duration-500">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-16 space-y-4 px-4">
                <h2 class="text-orange-500 font-bold uppercase tracking-widest text-[10px] sm:text-sm reveal-text">Paket
                    Spesial</h2>
                <h3
                    class="text-2xl sm:text-4xl lg:text-5xl font-poppins font-bold text-slate-900 dark:text-white reveal-text leading-tight">
                    Pilihan Paket Berdasarkan Acara</h3>
                <p class="text-slate-500 text-xs sm:text-base max-w-2xl mx-auto reveal-text">Apapun acaranya, AISH Catering
                    siap menyajikan
                    hidangan pilihan terbaik yang disesuaikan dengan kebutuhan Anda.</p>
            </div>

            <div id="special-packages-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                @forelse($specialPackages as $package)
                    <div
                        class="special-package-item group relative bg-white dark:bg-slate-800 rounded-[2rem] overflow-hidden shadow-lg border border-slate-100 dark:border-slate-700 transition-all hover:-translate-y-2 hover:shadow-2xl mx-4 sm:mx-0 animate-reveal">
                        <div class="h-48 sm:h-64 overflow-hidden">
                            @php
                                $pkgImg = $package->image ?? 'https://images.unsplash.com/photo-1547592166-23ac45744acd?q=80&w=800';
                                $pkgImgUrl = filter_var($pkgImg, FILTER_VALIDATE_URL) ? $pkgImg : asset($pkgImg);
                            @endphp
                            <img src="{{ $pkgImgUrl }}"
                                class="w-full h-full object-cover transition-transform group-hover:scale-110"
                                alt="{{ $package->title }}">
                            @if($package->badge)
                                <div
                                    class="absolute top-4 left-4 sm:top-6 sm:left-6 bg-white/90 dark:bg-slate-800/90 backdrop-blur px-3 py-1 sm:px-4 sm:py-1.5 rounded-full text-green-600 dark:text-green-400 font-bold text-[9px] sm:text-xs uppercase shadow-sm">
                                    {{ $package->badge }}
                                </div>
                            @endif
                        </div>
                        <div class="p-6 sm:p-8">
                            <h4 class="text-lg sm:text-2xl font-bold text-slate-900 dark:text-white mb-2 sm:mb-3">
                                {{ $package->title }}</h4>
                            <p class="text-slate-500 dark:text-slate-400 text-xs sm:text-sm leading-relaxed mb-4 sm:mb-6">
                                {{ $package->description }}</p>
                            <ul class="space-y-2 sm:space-y-3 mb-4 sm:mb-8">
                                @foreach($package->features ?? [] as $feature)
                                    <li class="flex items-center text-[11px] sm:text-sm text-slate-600 dark:text-slate-400"><span
                                            class="w-4 h-4 sm:w-5 sm:h-5 bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded-full flex items-center justify-center mr-2 sm:mr-3 text-[8px] sm:text-[10px]">✓</span>
                                        {{ $feature }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center text-slate-400 font-bold">
                        Paket spesial belum tersedia.
                    </div>
                @endforelse
            </div>

            <!-- Pagination Controls for Special Packages -->
            <div id="special-packages-pagination" class="flex justify-center items-center gap-1 sm:gap-2 mt-12 sm:mt-16">
                <!-- Will be dynamically populated by JS -->
            </div>
        </div>
    </section>

    <!-- Portion Calculator Section -->
    <section id="calculator" class="py-16 sm:py-24 relative overflow-hidden reveal bg-white dark:bg-transparent">

        <!-- Background orbs -->
        <div class="absolute inset-0 pointer-events-none overflow-hidden">
            <div class="absolute rounded-full opacity-0 dark:opacity-100"
                style="width:500px;height:500px;top:-100px;left:-100px;background:radial-gradient(circle,rgba(61,140,117,0.08) 0%,transparent 70%);filter:blur(60px);">
            </div>
            <div class="absolute rounded-full opacity-0 dark:opacity-100"
                style="width:400px;height:400px;bottom:-80px;right:-80px;background:radial-gradient(circle,rgba(249,115,22,0.07) 0%,transparent 70%);filter:blur(60px);">
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            <!-- Section header -->
            <div class="text-center mb-8 sm:mb-16">
                <div
                    class="inline-flex items-center gap-2 px-3 py-1.5 sm:px-4 sm:py-2 rounded-full text-[10px] sm:text-xs font-bold uppercase tracking-widest mb-3 sm:mb-4 border border-emerald-500/30 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">
                    <span class="w-1 h-1 sm:w-1.5 sm:h-1.5 rounded-full animate-pulse bg-emerald-500"></span>
                    Catering Tools
                </div>
                <h2 class="font-poppins font-extrabold text-slate-900 dark:text-white"
                    style="font-size:clamp(1.5rem,8vw,3.5rem);line-height:1.1;">
                    Kalkulator <span
                        style="background:linear-gradient(90deg,#3d8c75,#a2dfcc);-webkit-background-clip:text;-webkit-text-fill-color:transparent;">Porsi</span>
                </h2>
                <p
                    class="text-sm sm:text-lg max-w-2xl mx-auto leading-relaxed mt-2 sm:mt-4 text-slate-500 dark:text-slate-300">
                    Bingung pesan berapa? Masukkan jumlah tamu Anda, kami bantu hitung estimasi porsi terbaik.
                </p>
            </div>

            <div class="grid lg:grid-cols-2 gap-6 sm:gap-16 items-start">

                <!-- Left: Form -->
                <div
                    class="rounded-[2rem] sm:rounded-3xl p-5 sm:p-8 space-y-5 sm:space-y-6 bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 shadow-sm dark:shadow-none dark:backdrop-blur-md">

                    <!-- Guest count -->
                    <div class="space-y-2">
                        <label class="block text-xs sm:text-sm font-bold text-slate-500 dark:text-white/70">Jumlah Tamu
                            Undangan</label>
                        <input type="number" id="calc-guests" value="100"
                            class="w-full px-4 py-3 sm:px-5 sm:py-4 rounded-xl sm:rounded-2xl text-lg sm:text-xl font-black outline-none transition-all bg-slate-50 dark:bg-white/5 border-2 border-slate-200 dark:border-white/10 text-slate-900 dark:text-white focus:border-emerald-500 dark:focus:border-emerald-500/50 focus:ring-4 focus:ring-emerald-500/10"
                            placeholder="Contoh: 100">
                    </div>

                    <!-- Service type -->
                    <div class="space-y-3">
                        <label class="block text-xs sm:text-sm font-bold text-slate-500 dark:text-white/70">Tipe
                            Layanan</label>
                        <!-- Calculator Type Buttons -->
                        <div class="grid grid-cols-3 sm:grid-cols-5 gap-2 sm:gap-3 mb-4" id="calc-btn-container">
                            <button onclick="setCalcType('prasmanan')"
                                class="calc-type-btn active py-2.5 sm:py-3 px-1 rounded-xl text-center transition-all duration-200 border-2 border-emerald-500 bg-emerald-50 dark:border-emerald-500/50 dark:bg-emerald-500/10 shadow-sm"
                                data-type="prasmanan">
                                <span class="text-sm sm:text-lg block mb-0.5">🍱</span>
                                <span
                                    class="block text-[8px] sm:text-[10px] font-bold text-emerald-600 dark:text-emerald-400">Prasmanan</span>
                            </button>
                            <button onclick="setCalcType('box')"
                                class="calc-type-btn py-2.5 sm:py-3 px-1 rounded-xl text-center transition-all duration-200 border-2 border-slate-200 bg-white dark:border-white/10 dark:bg-white/5 hover:border-slate-300 dark:hover:border-white/20 shadow-sm dark:shadow-none"
                                data-type="box">
                                <span class="text-sm sm:text-lg block mb-0.5">📦</span>
                                <span class="block text-[8px] sm:text-[10px] font-bold text-slate-500 dark:text-white/50">Nasi
                                    Box</span>
                            </button>
                            <button onclick="setCalcType('snack')"
                                class="calc-type-btn py-2.5 sm:py-3 px-1 rounded-xl text-center transition-all duration-200 border-2 border-slate-200 bg-white dark:border-white/10 dark:bg-white/5 hover:border-slate-300 dark:hover:border-white/20 shadow-sm dark:shadow-none"
                                data-type="snack">
                                <span class="text-sm sm:text-lg block mb-0.5">🧁</span>
                                <span class="block text-[8px] sm:text-[10px] font-bold text-slate-500 dark:text-white/50">Snack
                                    Box</span>
                            </button>
                            <button onclick="setCalcType('tumpeng')"
                                class="calc-type-btn py-2.5 sm:py-3 px-1 rounded-xl text-center transition-all duration-200 border-2 border-slate-200 bg-white dark:border-white/10 dark:bg-white/5 hover:border-slate-300 dark:hover:border-white/20 shadow-sm dark:shadow-none"
                                data-type="tumpeng">
                                <span class="text-sm sm:text-lg block mb-0.5">🎋</span>
                                <span class="block text-[8px] sm:text-[10px] font-bold text-slate-500 dark:text-white/50">Tumpeng</span>
                            </button>
                            <button onclick="setCalcType('lainnya')"
                                class="calc-type-btn py-2.5 sm:py-3 px-1 rounded-xl text-center transition-all duration-200 border-2 border-slate-200 bg-white dark:border-white/10 dark:bg-white/5 hover:border-slate-300 dark:hover:border-white/20 shadow-sm dark:shadow-none"
                                data-type="lainnya">
                                <span class="text-sm sm:text-lg block mb-0.5">🎁</span>
                                <span class="block text-[8px] sm:text-[10px] font-bold text-slate-500 dark:text-white/50">Paket Lain</span>
                            </button>
                        </div>
                    </div>

                    <!-- Package select -->
                    <div class="space-y-2" id="calc-package-container">
                        <label class="block text-xs sm:text-sm font-bold text-slate-500 dark:text-white/70">Pilih Paket / Menu</label>
                        <select id="calc-package" onchange="calculatePortions()"
                            class="w-full px-4 py-3 rounded-xl text-xs sm:text-sm font-bold outline-none transition-all bg-slate-50 dark:bg-white/5 border-2 border-slate-200 dark:border-white/10 text-slate-900 dark:text-white focus:border-emerald-500 dark:focus:border-emerald-500/50 focus:ring-4 focus:ring-emerald-500/10">
                            <!-- Dynamically loaded -->
                        </select>
                    </div>

                    <!-- Calculate button -->
                    <button onclick="calculatePortions()"
                        class="w-full py-3.5 sm:py-5 rounded-xl sm:rounded-2xl font-bold text-white text-sm sm:text-lg transition-all duration-300 hover:scale-[1.02] hover:shadow-xl hover:shadow-emerald-500/30 bg-gradient-to-r from-emerald-500 to-emerald-600 dark:from-emerald-500 dark:to-emerald-600 shadow-lg shadow-emerald-500/20">
                        🔢 Hitung Sekarang
                    </button>

                    <p class="text-[10px] text-center text-slate-400 dark:text-white/30">
                        * Estimasi profesional dengan buffer keamanan 10%
                    </p>
                </div>

                <!-- Right: Result -->
                <div class="relative">
                    <div id="calc-result"
                        class="rounded-[2rem] sm:rounded-3xl p-5 sm:p-10 relative z-10 flex flex-col justify-center bg-white dark:bg-gradient-to-br dark:from-[#0f0f1e] dark:to-[#0f2a1a] border border-slate-200 dark:border-white/10 shadow-xl dark:shadow-none dark:backdrop-blur-md min-h-[300px] sm:min-h-[360px]">

                        <!-- Main number -->
                        <div class="text-center mb-4 sm:mb-8">
                            <p
                                class="text-[9px] sm:text-xs font-bold uppercase tracking-widest mb-1 sm:mb-3 text-slate-500 dark:text-white/30">
                                Hasil Estimasi</p>
                            <div class="font-poppins font-extrabold text-emerald-500 drop-shadow-sm dark:drop-shadow-[0_0_20px_rgba(34,197,94,0.4)]"
                                id="result-total" style="font-size:clamp(2.5rem,12vw,6rem);line-height:1;">
                                150</div>
                            <p class="mt-1 text-[10px] sm:text-base font-semibold text-slate-600 dark:text-white/50" id="result-total-label">Total
                                Porsi Disarankan</p>
                        </div>

                        <!-- Breakdown -->
                        <div class="space-y-2 sm:space-y-3">
                            <div
                                class="flex items-center justify-between px-3 py-2 sm:px-4 sm:py-3 rounded-xl sm:rounded-2xl bg-slate-50 dark:bg-white/5 border border-slate-100 dark:border-white/10">
                                <span class="text-[11px] sm:text-sm text-slate-600 dark:text-white/50" id="result-main-label">🍛 Lauk Utama</span>
                                <span class="font-bold text-[11px] sm:text-sm text-emerald-600 dark:text-emerald-400"
                                    id="result-main">150 Porsi</span>
                            </div>
                            <div
                                class="flex items-center justify-between px-3 py-2 sm:px-4 sm:py-3 rounded-xl sm:rounded-2xl bg-slate-50 dark:bg-white/5 border border-slate-100 dark:border-white/10">
                                <span class="text-[11px] sm:text-sm text-slate-600 dark:text-white/50" id="calc-side-label">🥤 Minuman &
                                    Dessert</span>
                                <span class="font-bold text-[11px] sm:text-sm text-orange-500 dark:text-orange-400"
                                    id="result-side">200 porsi</span>
                            </div>
                            <div
                                class="flex items-center justify-between px-3 py-2 sm:px-4 sm:py-3 rounded-xl sm:rounded-2xl bg-slate-50 dark:bg-white/5 border border-slate-100 dark:border-white/10">
                                <span class="text-[11px] sm:text-sm text-slate-600 dark:text-white/50">🛡️ Buffer
                                    Keamanan</span>
                                <span class="font-bold text-[11px] sm:text-sm text-blue-500 dark:text-blue-400"
                                    id="result-buffer">10%</span>
                            </div>
                        </div>

                        <!-- Description Box -->
                        <div id="calc-package-desc-container" class="mt-4 p-3.5 rounded-xl sm:rounded-2xl bg-slate-50 dark:bg-white/5 border border-slate-100 dark:border-white/10 text-[10px] sm:text-xs text-slate-500 dark:text-white/60 text-left leading-relaxed hidden">
                            <strong class="text-slate-800 dark:text-white">Detail Paket:</strong> <span id="calc-package-desc"></span>
                        </div>

                        <!-- Total cost -->
                        <div class="mt-5 pt-5 sm:mt-6 sm:pt-6 text-center border-t border-slate-200 dark:border-white/10">
                            <span
                                class="text-[9px] sm:text-xs font-bold uppercase tracking-widest block mb-1 sm:mb-2 text-slate-400 dark:text-white/30">Estimasi
                                Total Biaya</span>
                            <div class="font-poppins font-bold text-xl sm:text-3xl text-orange-500 drop-shadow-sm"
                                id="result-price">Rp 0</div>
                        </div>
                    </div>

                    <!-- Glow decorations -->
                    <div
                        class="absolute -top-8 -right-8 w-32 h-32 rounded-full pointer-events-none bg-emerald-500/10 dark:bg-emerald-500/15 blur-[40px]">
                    </div>
                    <div
                        class="absolute -bottom-8 -left-8 w-32 h-32 rounded-full pointer-events-none bg-orange-500/10 dark:bg-orange-500/12 blur-[40px]">
                    </div>
                </div>

            </div>
        </div>
    </section>



    <!-- Statistics Section -->
    <section
        class="py-20 bg-slate-50/50 dark:bg-[#050a08] border-y border-slate-100 dark:border-white/5 transition-colors duration-500"
        id="stats-section-secondary">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div
                class="grid grid-cols-2 lg:grid-cols-4 gap-0 rounded-3xl sm:rounded-[2.5rem] overflow-hidden border border-slate-200/50 dark:border-white/5 shadow-xl transition-all duration-300 hover:shadow-2xl bg-white/75 dark:bg-[#08100d]/75 backdrop-blur-2xl">

                <div class="flex flex-col items-center justify-center py-8 px-4 border-r border-b border-slate-200/50 dark:border-white/5 lg:border-b-0"
                    style="background:rgba(255,255,255,0.03);">
                    <p
                        class="font-outfit font-black text-3xl sm:text-4xl text-[#0ea5e9] drop-shadow-[0_0_12px_rgba(14,165,233,0.3)]">
                        <span class="stat-counter-secondary" data-target="1200" data-thousands="true">0</span>+
                    </p>
                    <p class="text-xs font-bold text-slate-500 dark:text-[#a2dfcc]/70 uppercase tracking-widest mt-3">
                        Pelanggan Puas
                    </p>
                </div>

                <div class="flex flex-col items-center justify-center py-8 px-4 border-b border-slate-200/50 dark:border-white/5 lg:border-r lg:border-b-0"
                    style="background:rgba(255,255,255,0.01);">
                    <p
                        class="font-outfit font-black text-3xl sm:text-4xl text-[#ec4899] drop-shadow-[0_0_12px_rgba(236,72,153,0.3)]">
                        <span class="stat-counter-secondary" data-target="5000" data-thousands="true">0</span>+
                    </p>
                    <p class="text-xs font-bold text-slate-500 dark:text-[#a2dfcc]/70 uppercase tracking-widest mt-3">
                        Pesanan Selesai
                    </p>
                </div>

                <div class="flex flex-col items-center justify-center py-8 px-4 border-r border-slate-200/50 dark:border-white/5"
                    style="background:rgba(255,255,255,0.03);">
                    <p
                        class="font-outfit font-black text-3xl sm:text-4xl text-[#0ea5e9] drop-shadow-[0_0_12px_rgba(14,165,233,0.3)]">
                        <span class="stat-counter-secondary" data-target="4.9" data-decimals="1">0.0</span>/5
                    </p>
                    <p class="text-xs font-bold text-slate-500 dark:text-[#a2dfcc]/70 uppercase tracking-widest mt-3">
                        Rating Layanan
                    </p>
                </div>

                <div class="flex flex-col items-center justify-center py-8 px-4" style="background:rgba(255,255,255,0.01);">
                    <p
                        class="font-outfit font-black text-3xl sm:text-4xl text-[#ec4899] drop-shadow-[0_0_12px_rgba(236,72,153,0.3)]">
                        <span class="stat-counter-secondary" data-target="10">0</span>+
                    </p>
                    <p class="text-xs font-bold text-slate-500 dark:text-[#a2dfcc]/70 uppercase tracking-widest mt-3">
                        Tahun Pengalaman
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section
        class="py-24 bg-white dark:bg-transparent overflow-hidden relative border-y border-slate-100 dark:border-transparent">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10 text-slate-900 dark:text-white">
            <div class="text-center mb-10 sm:mb-16 space-y-2 sm:space-y-4">
                <h2
                    class="font-bold uppercase tracking-widest text-[10px] sm:text-sm opacity-80 text-emerald-600 dark:text-emerald-400">
                    Testimoni</h2>
                <h3 class="text-2xl sm:text-4xl font-poppins font-bold">Apa Kata Mereka?</h3>
            </div>

            <div class="flex space-x-3 sm:space-x-8 animate-marquee whitespace-nowrap">
                <!-- Testi 1 -->
                <div class="w-[220px] sm:w-[400px] flex-shrink-0">
                    <div
                        class="bg-slate-50 dark:bg-white/10 backdrop-blur-lg p-5 sm:p-10 rounded-2xl sm:rounded-[2.5rem] border border-slate-200 dark:border-white/20 whitespace-normal">
                        <div class="flex text-orange-400 mb-4 sm:mb-6 font-serif text-xs sm:text-base">★★★★★</div>
                        <p class="text-sm sm:text-lg italic mb-6 sm:mb-8 leading-relaxed">"Nasi kotak dari AISH Catering
                            rasanya sungguh
                            autentik. Tamu undangan saya sangat puas."</p>
                        <div class="flex items-center space-x-4 text-left">
                            <div
                                class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center font-bold text-sm">
                                BS</div>
                            <div>
                                <h4 class="font-bold text-sm">Bapak Syarif</h4>
                                <p class="text-[10px] opacity-60">Acara Syukuran Kantor</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Testi 2 -->
                <div class="w-[220px] sm:w-[400px] flex-shrink-0">
                    <div
                        class="bg-slate-50 dark:bg-white/10 backdrop-blur-lg p-5 sm:p-10 rounded-2xl sm:rounded-[2.5rem] border border-slate-200 dark:border-white/20 whitespace-normal">
                        <div class="flex text-orange-400 mb-4 sm:mb-6 text-xs sm:text-base">★★★★★</div>
                        <p class="text-sm sm:text-lg italic mb-6 sm:mb-8 leading-relaxed">"Tumpeng ayu-nya juara! Dekorasi
                            makanannya sangat
                            cantik dan rasanya gurih banget."</p>
                        <div class="flex items-center space-x-4 text-left">
                            <div
                                class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center font-bold text-sm">
                                IA</div>
                            <div>
                                <h4 class="font-bold text-sm">Ibu Anisa</h4>
                                <p class="text-[10px] opacity-60">Ulang Tahun Anak</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Testi 3 -->
                <div class="w-[220px] sm:w-[400px] flex-shrink-0">
                    <div
                        class="bg-slate-50 dark:bg-white/10 backdrop-blur-lg p-5 sm:p-10 rounded-2xl sm:rounded-[2.5rem] border border-slate-200 dark:border-white/20 whitespace-normal">
                        <div class="flex text-orange-400 mb-4 sm:mb-6 text-xs sm:text-base">★★★★★</div>
                        <p class="text-sm sm:text-lg italic mb-6 sm:mb-8 leading-relaxed">"Katering pernikahan terbaik di
                            Singkawang.
                            Prasmanannya lengkap dan pelayanannya profesional."</p>
                        <div class="flex items-center space-x-4 text-left">
                            <div
                                class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center font-bold text-sm">
                                DR</div>
                            <div>
                                <h4 class="font-bold text-sm">dr. Rendy</h4>
                                <p class="text-[10px] opacity-60">Resepsi Pernikahan</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Duplicate for Seamless Loop -->
                <!-- Testi 1 (Loop) -->
                <div class="w-[220px] sm:w-[400px] flex-shrink-0">
                    <div
                        class="bg-slate-50 dark:bg-white/10 backdrop-blur-lg p-5 sm:p-10 rounded-2xl sm:rounded-[2.5rem] border border-slate-200 dark:border-white/20 whitespace-normal">
                        <div class="flex text-orange-400 mb-4 sm:mb-6 text-xs sm:text-base">★★★★★</div>
                        <p class="text-sm sm:text-lg italic mb-6 sm:mb-8 leading-relaxed">"Nasi kotak dari AISH Catering
                            rasanya sungguh
                            autentik. Tamu undangan saya sangat puas."</p>
                        <div class="flex items-center space-x-3 sm:space-x-4 text-left">
                            <div
                                class="w-8 h-8 sm:w-10 sm:h-10 bg-white/20 rounded-full flex items-center justify-center font-bold text-xs sm:text-sm">
                                BS</div>
                            <div>
                                <h4 class="font-bold text-xs sm:text-sm">Bapak Syarif</h4>
                                <p class="text-[9px] sm:text-[10px] opacity-60">Acara Syukuran</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Duplicate for Seamless Loop -->
                <!-- Testi 2 (Loop) -->
                <div class="w-[220px] sm:w-[400px] flex-shrink-0">
                    <div
                        class="bg-slate-50 dark:bg-white/10 backdrop-blur-lg p-5 sm:p-10 rounded-2xl sm:rounded-[2.5rem] border border-slate-200 dark:border-white/20 whitespace-normal">
                        <div class="flex text-orange-400 mb-4 sm:mb-6 text-xs sm:text-base">★★★★★</div>
                        <p class="text-sm sm:text-lg italic mb-6 sm:mb-8 leading-relaxed">"Tumpeng ayu-nya juara! Dekorasi
                            makanannya sangat
                            cantik dan rasanya gurih banget."</p>
                        <div class="flex items-center space-x-3 sm:space-x-4 text-left">
                            <div
                                class="w-8 h-8 sm:w-10 sm:h-10 bg-white/20 rounded-full flex items-center justify-center font-bold text-xs sm:text-sm">
                                IA</div>
                            <div>
                                <h4 class="font-bold text-xs sm:text-sm">Ibu Anisa</h4>
                                <p class="text-[9px] sm:text-[10px] opacity-60">Ulang Tahun</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
    </section>

    <!-- Ordering Process Section -->
    <section id="workflow" class="py-24 bg-slate-50">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-20 space-y-4">
                <h2 class="text-orange-500 font-bold uppercase tracking-widest text-sm">Cara Pesan</h2>
                <h3 class="text-4xl lg:text-5xl font-poppins font-bold text-slate-900">Alur Pemesanan AISH Catering</h3>
                <p class="text-slate-500 max-w-2xl mx-auto">Kami mempermudah proses pemesanan melalui integrasi website dan
                    aplikasi resmi kami.</p>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-12 relative">
                <!-- Step 1 -->
                <div class="space-y-4 sm:space-y-6 relative group text-center sm:text-left">
                    <div
                        class="w-10 h-10 sm:w-16 sm:h-16 mx-auto sm:mx-0 bg-green-500 text-white rounded-xl sm:rounded-2xl flex items-center justify-center text-lg sm:text-2xl font-bold shadow-xl shadow-green-200 group-hover:scale-110 transition-transform">
                        1</div>
                    <div class="space-y-1 sm:space-y-2">
                        <h4 class="text-sm sm:text-xl font-bold text-slate-900">Pilih Menu</h4>
                        <p class="text-[10px] sm:text-sm text-slate-500 leading-relaxed line-clamp-2 sm:line-clamp-none">
                            Cari menu favorit Anda di landing page ini.</p>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="space-y-4 sm:space-y-6 relative group text-center sm:text-left">
                    <div
                        class="w-10 h-10 sm:w-16 sm:h-16 mx-auto sm:mx-0 bg-green-500 text-white rounded-xl sm:rounded-2xl flex items-center justify-center text-lg sm:text-2xl font-bold shadow-xl shadow-green-200 group-hover:scale-110 transition-transform">
                        2</div>
                    <div class="space-y-1 sm:space-y-2">
                        <h4 class="text-sm sm:text-xl font-bold text-slate-900">Klik Pesan</h4>
                        <p class="text-[10px] sm:text-sm text-slate-500 leading-relaxed line-clamp-2 sm:line-clamp-none">
                            Klik tombol pemesanan di menu pilihan.</p>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="space-y-4 sm:space-y-6 relative group text-center sm:text-left">
                    <div
                        class="w-10 h-10 sm:w-16 sm:h-16 mx-auto sm:mx-0 bg-orange-500 text-white rounded-xl sm:rounded-2xl flex items-center justify-center text-lg sm:text-2xl font-bold shadow-xl shadow-orange-200 group-hover:scale-110 transition-transform">
                        3</div>
                    <div class="space-y-1 sm:space-y-2">
                        <h4 class="text-sm sm:text-xl font-bold text-slate-900">Get App</h4>
                        <p class="text-[10px] sm:text-sm text-slate-500 leading-relaxed line-clamp-2 sm:line-clamp-none">
                            Download aplikasi resmi (Android) kami.</p>
                    </div>
                </div>

                <!-- Step 4 -->
                <div class="space-y-4 sm:space-y-6 relative group text-center sm:text-left">
                    <div
                        class="w-10 h-10 sm:w-16 sm:h-16 mx-auto sm:mx-0 bg-green-500 text-white rounded-xl sm:rounded-2xl flex items-center justify-center text-lg sm:text-2xl font-bold shadow-xl shadow-green-200 group-hover:scale-110 transition-transform">
                        4</div>
                    <div class="space-y-1 sm:space-y-2">
                        <h4 class="text-sm sm:text-xl font-bold text-slate-900">Buka Aplikasi</h4>
                        <p class="text-[10px] sm:text-sm text-slate-500 leading-relaxed line-clamp-2 sm:line-clamp-none">
                            Pasang dan jalankan aplikasi AISH Catering.</p>
                    </div>
                </div>

                <!-- Step 5 -->
                <div class="space-y-4 sm:space-y-6 relative group text-center sm:text-left">
                    <div
                        class="w-10 h-10 sm:w-16 sm:h-16 mx-auto sm:mx-0 bg-green-500 text-white rounded-xl sm:rounded-2xl flex items-center justify-center text-lg sm:text-2xl font-bold shadow-xl shadow-green-200 group-hover:scale-110 transition-transform">
                        5</div>
                    <div class="space-y-1 sm:space-y-2">
                        <h4 class="text-sm sm:text-xl font-bold text-slate-900">Tentukan Menu</h4>
                        <p class="text-[10px] sm:text-sm text-slate-500 leading-relaxed line-clamp-2 sm:line-clamp-none">
                            Tentukan jumlah porsi dan detail acara.</p>
                    </div>
                </div>

                <!-- Step 6 -->
                <div class="space-y-4 sm:space-y-6 relative group text-center sm:text-left">
                    <div
                        class="w-10 h-10 sm:w-16 sm:h-16 mx-auto sm:mx-0 bg-green-500 text-white rounded-xl sm:rounded-2xl flex items-center justify-center text-lg sm:text-2xl font-bold shadow-xl shadow-green-200 group-hover:scale-110 transition-transform">
                        6</div>
                    <div class="space-y-1 sm:space-y-2">
                        <h4 class="text-sm sm:text-xl font-bold text-slate-900">Isi Alamat</h4>
                        <p class="text-[10px] sm:text-sm text-slate-500 leading-relaxed line-clamp-2 sm:line-clamp-none">
                            Masukkan detail pengiriman di Singkawang.</p>
                    </div>
                </div>

                <!-- Step 7 -->
                <div class="space-y-4 sm:space-y-6 relative group text-center sm:text-left">
                    <div
                        class="w-10 h-10 sm:w-16 sm:h-16 mx-auto sm:mx-0 bg-green-500 text-white rounded-xl sm:rounded-2xl flex items-center justify-center text-lg sm:text-2xl font-bold shadow-xl shadow-green-200 group-hover:scale-110 transition-transform">
                        7</div>
                    <div class="space-y-1 sm:space-y-2">
                        <h4 class="text-sm sm:text-xl font-bold text-slate-900">Konfirmasi</h4>
                        <p class="text-[10px] sm:text-sm text-slate-500 leading-relaxed line-clamp-2 sm:line-clamp-none">
                            Lakukan pembayaran di aplikasi.</p>
                    </div>
                </div>

                <!-- Step 8 -->
                <div class="space-y-4 sm:space-y-6 relative group text-center sm:text-left">
                    <div
                        class="w-10 h-10 sm:w-16 sm:h-16 mx-auto sm:mx-0 bg-green-500 text-white rounded-xl sm:rounded-2xl flex items-center justify-center text-lg sm:text-2xl font-bold shadow-xl shadow-green-200 group-hover:scale-110 transition-transform">
                        8</div>
                    <div class="space-y-1 sm:space-y-2">
                        <h4 class="text-sm sm:text-xl font-bold text-slate-900">Validasi</h4>
                        <p class="text-[10px] sm:text-sm text-slate-500 leading-relaxed line-clamp-2 sm:line-clamp-none">
                            Admin akan memproses pesanan Anda.</p>
                    </div>
                </div>

                <!-- Step 9 -->
                <div class="space-y-4 sm:space-y-6 relative group text-center sm:text-left">
                    <div
                        class="w-10 h-10 sm:w-16 sm:h-16 mx-auto sm:mx-0 bg-orange-500 text-white rounded-xl sm:rounded-2xl flex items-center justify-center text-lg sm:text-2xl font-bold shadow-xl shadow-orange-200 group-hover:scale-110 transition-transform">
                        9</div>
                    <div class="space-y-1 sm:space-y-2">
                        <h4 class="text-sm sm:text-xl font-bold text-slate-900">Selesai</h4>
                        <p class="text-[10px] sm:text-sm text-slate-500 leading-relaxed line-clamp-2 sm:line-clamp-none">
                            Pesanan dikirim tepat waktu ke lokasi.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- App Download Section -->
    <section id="download"
        class="py-20 sm:py-32 text-slate-900 dark:text-white overflow-hidden relative bg-white dark:bg-transparent">
        <!-- Overlay Gradient instead of image -->
        <div
            class="absolute inset-0 bg-transparent dark:bg-gradient-to-br dark:from-[#08100d] dark:via-[#0d1a15] dark:to-black z-0">
        </div>

        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                <div class="space-y-6 sm:space-y-8 animate-fade-in text-center lg:text-left">
                    <div
                        class="inline-flex items-center px-4 py-1.5 rounded-full bg-emerald-500/10 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 text-xs font-bold uppercase tracking-wider border border-emerald-500/20">
                        📱 Aplikasi Mobile
                    </div>
                    <h2 class="text-3xl sm:text-6xl font-poppins font-bold leading-tight">
                        Pesan Lebih Mudah dengan <span class="text-emerald-500">Aplikasi</span> Kami.
                    </h2>
                    <p
                        class="text-sm sm:text-lg text-slate-500 dark:text-slate-400 leading-relaxed max-w-lg mx-auto lg:mx-0">
                        Dapatkan kemudahan memesan katering langsung dari smartphone Anda. Pantau status pesanan dan nikmati
                        promo khusus.
                    </p>
                    <div class="flex flex-col space-y-4">
                        <div class="flex flex-col sm:flex-row justify-center lg:justify-start space-y-4 sm:space-y-0 sm:space-x-4">
                            <!-- Option 1: Direct APK Download -->
                            <a href="{{ asset('aish_catering.apk') }}" download="aish_catering.apk"
                                class="bg-emerald-600 hover:bg-emerald-700 border border-emerald-500 px-6 sm:px-8 py-3 sm:py-4 rounded-xl sm:rounded-2xl flex items-center justify-center sm:justify-start space-x-3 sm:space-x-4 transition-all duration-300 group shadow-lg shadow-emerald-950/20">
                                <svg class="w-8 h-8 sm:w-10 sm:h-10 text-white group-hover:scale-110 transition-transform"
                                    viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M17.6,9.48l1.72-2.98c0.11-0.2,0.05-0.45-0.15-0.56c-0.2-0.11-0.45-0.05-0.56,0.15l-1.75,3.03C15.2,8.46,13.67,8,12,8S8.8,8.46,7.1,9.12L5.35,6.09C5.24,5.89,4.99,5.83,4.79,5.94C4.59,6.05,4.53,6.3,4.64,6.5l1.72,2.98C3.54,11.23,1.83,13.88,1.52,17h20.97C22.17,13.88,20.46,11.23,17.6,9.48z M7,13.5c-0.55,0-1-0.45-1-1s0.45-1,1-1s1,0.45,1,1S7.55,13.5,7,13.5z M17,13.5c-0.55,0-1-0.45-1-1s0.45-1,1-1s1,0.45,1,1S17.55,13.5,17,13.5z" />
                                </svg>
                                <div class="text-left font-poppins">
                                    <p class="text-[10px] sm:text-[11px] uppercase font-bold text-emerald-200 leading-none mb-1">
                                        Download APK</p>
                                    <p class="text-xs sm:text-sm font-bold text-white leading-none">Direct Android</p>
                                </div>
                            </a>
                        </div>
                        <p class="text-[10px] sm:text-xs text-slate-500 dark:text-slate-400 italic">
                            *Catatan: Jika mengunduh file APK secara langsung, Anda mungkin perlu mengaktifkan opsi "Izinkan instalasi dari sumber tidak dikenal" (Allow installation from unknown sources) di pengaturan keamanan HP Android Anda.
                        </p>
                    </div>
                </div>
                <div class="relative flex justify-center mt-12 md:mt-0"
                    style="display: flex; justify-content: center; width: 100%;">
                    <!-- Mockup Smartphone Image -->
                    <div class="relative mx-auto group" style="max-width: 250px; sm:max-width: 300px; width: 100%;">
                        <img src="{{ asset('images/mockup-app.png') }}" alt="Mockup Aplikasi AISH Catering"
                            style="width: 100%; height: auto; border-radius: 2.5rem; sm:border-radius: 3rem; display: block;"
                            class="drop-shadow-2xl transform transition duration-500 group-hover:-translate-y-2 group-hover:scale-105 z-10 relative">
                        <!-- Decorative Orbs -->
                        <div
                            class="absolute -top-12 -right-12 w-64 h-64 bg-green-500/30 rounded-full blur-3xl -z-10 transition-transform group-hover:scale-110">
                        </div>
                        <div
                            class="absolute -bottom-12 -left-12 w-64 h-64 bg-orange-500/30 rounded-full blur-3xl -z-10 transition-transform group-hover:scale-110">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Elegant Catering Banner Separator -->
    <section class="py-32 md:py-40 relative overflow-hidden shadow-2xl bg-white dark:bg-[#08100d] reveal">
        <!-- Background Image -->
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('images/catering_banner_bg.png') }}" alt="Catering Background"
                class="w-full h-full object-cover object-center opacity-10 dark:opacity-30 mix-blend-luminosity transform scale-105 hover:scale-100 transition-transform duration-1000">
        </div>

        <!-- Elegant Botanical Overlay Gradient -->
        <div
            class="absolute inset-0 bg-white/90 dark:bg-gradient-to-t dark:from-[#08100d] dark:via-[#1b4d3e]/80 dark:to-[#08100d] z-0">
        </div>

        <div class="max-w-5xl mx-auto px-6 relative z-10 text-center flex flex-col items-center">
            <!-- Decorative Accent -->
            <div class="flex items-center justify-center gap-4 mb-8 opacity-80">
                <div class="w-16 h-[1px] bg-emerald-500/30 dark:bg-emerald-400/50"></div>
                <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                </svg>
                <div class="w-16 h-[1px] bg-emerald-500/30 dark:bg-emerald-400/50"></div>
            </div>

            <h2
                class="text-4xl md:text-5xl lg:text-6xl font-poppins font-extrabold text-slate-900 dark:text-white mb-6 tracking-tight leading-tight drop-shadow-sm dark:drop-shadow-2xl">
                Rasa Lezat, Kenangan<br class="hidden sm:block">
                <span
                    class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-emerald-400 dark:from-emerald-300 dark:to-[#a2dfcc]">Tak
                    Terlupakan.</span>
            </h2>
            <p
                class="text-slate-600 dark:text-emerald-50/90 text-lg md:text-xl font-medium max-w-2xl mx-auto leading-relaxed">
                AISH Catering hadir sebagai mitra terpercaya, menghidangkan sajian istimewa berkelas untuk menyempurnakan
                setiap momen berharga Anda.
            </p>

            <div class="mt-12">
                <a href="#menu"
                    class="inline-flex items-center gap-3 px-8 py-3.5 rounded-full bg-emerald-500/20 text-[#a2dfcc] border border-emerald-500/30 hover:bg-emerald-500 hover:text-white transition-all duration-300 backdrop-blur-md shadow-lg shadow-emerald-900/20 group">
                    <span class="font-bold tracking-wide text-sm uppercase">Jelajahi Menu Kami</span>
                    <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- Gallery Section -->

    <!-- Lightbox Modal -->
    <div id="lightbox"
        class="fixed inset-0 z-[2000] bg-black/95 hidden flex-col items-center justify-center p-6 backdrop-blur-md transition-all duration-500"
        onclick="this.style.display='none'">
        <div class="relative max-w-5xl w-full flex flex-col items-center gap-6 animate-zoom-in"
            onclick="event.stopPropagation()">
            <img id="lightbox-img" src="" class="max-w-full max-h-[75vh] rounded-3xl shadow-2xl border border-white/10">

            <div id="lightbox-caption-box" class="text-center space-y-2 opacity-0 transition-opacity duration-500">
                <h4 id="lightbox-title" class="text-2xl font-poppins font-black text-white"></h4>
                <p id="lightbox-desc" class="text-slate-400 text-sm font-medium tracking-wide"></p>
            </div>

            <button onclick="document.getElementById('lightbox').style.display='none'"
                class="absolute -top-12 -right-4 text-white/50 hover:text-white transition-colors">
                <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path d="M6 18L18 6M6 6l12 12" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>
        </div>
    </div>
    <!-- FAQ Section -->
    <section id="faq" class="py-24 bg-white reveal">
        <div class="max-w-4xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-16 space-y-4">
                <h2 class="text-orange-500 font-bold uppercase tracking-widest text-sm">FAQ</h2>
                <h3 class="text-4xl font-poppins font-bold text-slate-900">Pertanyaan Umum</h3>
            </div>

            <div class="space-y-4">
                <!-- FAQ 1 -->
                <div class="faq-item group bg-slate-50 rounded-2xl border border-slate-100 overflow-hidden">
                    <button class="w-full p-6 text-left flex justify-between items-center focus:outline-none"
                        onclick="toggleFaq(this)">
                        <span class="font-bold text-lg text-slate-900">Bagaimana cara memesan katering di AISH
                            Catering?</span>
                        <svg class="faq-icon w-6 h-6 text-slate-400 transition-transform duration-300" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                    <div class="faq-answer px-6">
                        <div class="pb-6 text-slate-500 leading-relaxed">
                            Anda dapat memesan melalui Chatbot kami di website ini, aplikasi mobile, atau langsung
                            menghubungi tim kami melalui WhatsApp di nomor yang tertera di bagian kontak.
                        </div>
                    </div>
                </div>

                <!-- FAQ 2 -->
                <div class="faq-item group bg-slate-50 rounded-2xl border border-slate-100 overflow-hidden">
                    <button class="w-full p-6 text-left flex justify-between items-center focus:outline-none"
                        onclick="toggleFaq(this)">
                        <span class="font-bold text-lg text-slate-900">Apakah ada minimal pemesanan?</span>
                        <svg class="faq-icon w-6 h-6 text-slate-400 transition-transform duration-300" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                    <div class="faq-answer px-6">
                        <div class="pb-6 text-slate-500 leading-relaxed">
                            Ya, minimal pemesanan tergantung pada jenis paket. Untuk Nasi Kotak minimal 25 pax, sedangkan
                            untuk Snack Box minimal 30 pax. Info detail ada di deskripsi setiap menu.
                        </div>
                    </div>
                </div>

                <!-- FAQ 3 -->
                <div class="faq-item group bg-slate-50 rounded-2xl border border-slate-100 overflow-hidden">
                    <button class="w-full p-6 text-left flex justify-between items-center focus:outline-none"
                        onclick="toggleFaq(this)">
                        <span class="font-bold text-lg text-slate-900">Apakah AISH Catering menyediakan menu
                            vegetarian?</span>
                        <svg class="faq-icon w-6 h-6 text-slate-400 transition-transform duration-300" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                    <div class="faq-answer px-6">
                        <div class="pb-6 text-slate-500 leading-relaxed">
                            Tentu! Kami menyediakan berbagai pilihan menu sehat dan vegetarian. Anda dapat melakukan
                            kustomisasi menu saat proses pemesanan melalui tim admin kami.
                        </div>
                    </div>
                </div>

                <!-- FAQ 4 -->
                <div class="faq-item group bg-slate-50 rounded-2xl border border-slate-100 overflow-hidden">
                    <button class="w-full p-6 text-left flex justify-between items-center focus:outline-none"
                        onclick="toggleFaq(this)">
                        <span class="font-bold text-base sm:text-lg text-slate-900 text-left">Berapa lama waktu minimal
                            untuk pemesanan?</span>
                        <svg class="faq-icon w-6 h-6 text-slate-400 transition-transform duration-300" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                    <div class="faq-answer px-6">
                        <div class="pb-6 text-slate-500 leading-relaxed">
                            Kami menyarankan pemesanan dilakukan minimal H-3 untuk acara kecil, dan H-7 untuk acara besar
                            seperti prasmanan atau tumpeng besar agar tim kami bisa memberikan layanan terbaik.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Gallery Section -->
    <section id="gallery" class="py-24 bg-white reveal">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-16 space-y-4">
                <div
                    class="inline-flex items-center gap-2 px-4 py-2 bg-green-50 text-green-600 rounded-full text-[10px] font-black uppercase tracking-widest">
                    📸 Dokumentasi Kami
                </div>
                <h3 class="text-4xl font-poppins font-black text-slate-900 reveal-text">Momen Spesial Bersama AISH Catering
                </h3>
                <p class="text-slate-500 max-w-2xl mx-auto reveal-text text-sm">Sekilas kebahagiaan dari berbagai acara yang
                    telah kami layani dengan sepenuh hati.</p>
            </div>
            <div id="gallery-grid" class="grid grid-cols-3 lg:grid-cols-3 gap-1.5 sm:gap-8">
                @forelse($galleries as $photo)
                    @php
                        $imgUrl = asset($photo->image_path);
                    @endphp
                    <div
                        class="gallery-item group relative aspect-square rounded-lg sm:rounded-[2.5rem] overflow-hidden shadow-lg border border-slate-100 hover:shadow-2xl transition-all duration-700 cursor-pointer"
                        onclick="openLightbox('{{ $imgUrl }}', '{{ addslashes($photo->title) }}')">
                        <img src="{{ $imgUrl }}"
                            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                            alt="{{ $photo->title }}">
                        <div
                            class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex items-center justify-center">
                            <div
                                class="p-4 bg-white rounded-full text-slate-900 transform scale-50 opacity-0 group-hover:scale-100 group-hover:opacity-100 transition-all duration-500 delay-100">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="2.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-24 text-center">
                        <p class="text-slate-400 italic">Foto galeri akan segera hadir.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination Controls for Gallery -->
            <div id="gallery-pagination" class="flex justify-center items-center gap-1 sm:gap-2 mt-12 sm:mt-16">
                <!-- Will be dynamically populated by JS -->
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-24 bg-slate-50 reveal">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-16">
                <div class="space-y-8">
                    <h2 class="text-4xl font-poppins font-bold text-slate-900">Hubungi Kami</h2>
                    <p class="text-sm sm:text-lg text-slate-500 leading-relaxed">Punya pertanyaan atau ingin melakukan
                        pemesanan khusus? Tim
                        kami siap membantu Anda kapanpun.</p>

                    <!-- Modern Interactive Contact Buttons -->
                    <div class="grid grid-cols-2 gap-4">
                        @php
                            $waPhone = isset($contents['phone']) ? preg_replace('/[^0-9]/', '', $contents['phone']) : '628123456789';
                            $emailAddress = $contents['email'] ?? 'aishcatering2@gmail.com';
                        @endphp
                        <a href="https://wa.me/{{ $waPhone }}" target="_blank"
                            class="flex flex-col sm:flex-row items-center space-y-2 sm:space-y-0 sm:space-x-4 p-4 rounded-2xl bg-gradient-to-br from-emerald-500/10 to-green-500/5 dark:from-emerald-500/20 dark:to-green-500/10 border border-emerald-500/20 hover:border-emerald-500/50 hover:bg-emerald-500 hover:text-white dark:hover:bg-emerald-500 dark:hover:text-white hover:-translate-y-1 hover:shadow-xl hover:shadow-emerald-500/10 transition-all duration-300 group">
                            <div
                                class="w-10 h-10 sm:w-12 sm:h-12 bg-emerald-500 text-white rounded-full flex items-center justify-center shadow-md shadow-emerald-500/20 group-hover:bg-white group-hover:text-emerald-500 transition-colors">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                                </svg>
                            </div>
                            <div class="text-center sm:text-left">
                                <span
                                    class="block text-xs font-bold text-slate-400 group-hover:text-emerald-200 transition-colors uppercase tracking-widest leading-none mb-1">WhatsApp</span>
                                <span class="font-poppins font-black text-sm sm:text-base leading-none">Hubungi CS</span>
                            </div>
                        </a>

                        <a href="mailto:{{ $emailAddress }}"
                            class="flex flex-col sm:flex-row items-center space-y-2 sm:space-y-0 sm:space-x-4 p-4 rounded-2xl bg-gradient-to-br from-orange-500/10 to-amber-500/5 dark:from-orange-500/20 dark:to-amber-500/10 border border-orange-500/20 hover:border-orange-500/50 hover:bg-orange-500 hover:text-white dark:hover:bg-orange-500 dark:hover:text-white hover:-translate-y-1 hover:shadow-xl hover:shadow-orange-500/10 transition-all duration-300 group">
                            <div
                                class="w-10 h-10 sm:w-12 sm:h-12 bg-orange-500 text-white rounded-full flex items-center justify-center shadow-md shadow-orange-500/20 group-hover:bg-white group-hover:text-orange-500 transition-colors">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <div class="text-center sm:text-left">
                                <span
                                    class="block text-xs font-bold text-slate-400 group-hover:text-orange-200 transition-colors uppercase tracking-widest leading-none mb-1">Email</span>
                                <span class="font-poppins font-black text-sm sm:text-base leading-none">Kirim Surat</span>
                            </div>
                        </a>
                    </div>

                    <!-- Interactive Map Container -->
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-black uppercase tracking-wider text-slate-400">🗺️ Peta Lokasi
                                Pengiriman</span>
                            <div class="flex gap-2">
                                <button type="button" onclick="focusBusinessLocation()"
                                    class="px-3 py-1.5 bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-bold rounded-xl transition duration-300 flex items-center gap-1.5 shadow-sm">
                                    🏢 Kantor Aish Catering
                                </button>
                                <button type="button" onclick="detectGPSLocation()"
                                    class="px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold rounded-xl transition duration-300 flex items-center gap-1.5 shadow-md shadow-emerald-500/20 animate-pulse hover:animate-none">
                                    📍 Deteksi GPS
                                </button>
                            </div>
                        </div>

                        <div
                            class="relative rounded-3xl overflow-hidden shadow-2xl h-80 sm:h-96 border border-slate-100 dark:border-slate-800 group">
                            <!-- Loading / Status overlay -->
                            <div id="map-status-overlay"
                                class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm z-[999] flex flex-col items-center justify-center text-white transition-opacity duration-500 opacity-0 pointer-events-none">
                                <div
                                    class="w-12 h-12 border-4 border-emerald-500 border-t-transparent rounded-full animate-spin mb-3">
                                </div>
                                <p id="map-status-text" class="text-sm font-semibold tracking-wide">Mencari lokasi Anda...
                                </p>
                            </div>

                            <!-- Leaflet Map Container -->
                            <div id="leaflet-map" class="w-full h-full z-10"></div>
                        </div>

                        <!-- Location Address Badge -->
                        <div id="detected-address-card"
                            class="hidden p-4 bg-emerald-50 border border-emerald-100 dark:bg-emerald-950/20 dark:border-emerald-900/50 rounded-2xl flex items-start gap-3 transition-all duration-500 transform translate-y-2 opacity-0">
                            <div
                                class="w-8 h-8 rounded-full bg-emerald-500/10 flex items-center justify-center text-emerald-600 shrink-0">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path d="M5 13l4 4L19 7" stroke-width="3" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </div>
                            <div class="space-y-0.5">
                                <p
                                    class="text-xs font-bold text-emerald-800 dark:text-emerald-400 uppercase tracking-widest leading-none">
                                    Lokasi GPS Terdeteksi</p>
                                <p id="detected-address-text"
                                    class="text-sm font-medium text-emerald-700 dark:text-emerald-300 leading-snug">
                                    Mengambil alamat...</p>
                            </div>
                        </div>
                    </div>
                </div> <!-- Closing left column wrapper (space-y-8) -->

                <!-- Right Column: Quotation Form -->
                <div
                    class="bg-white dark:bg-slate-800 p-6 sm:p-10 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-2xl relative overflow-hidden group">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-emerald-500 to-teal-500"></div>
                    <form class="space-y-6" onsubmit="submitQuotation(event)">
                        <div class="space-y-2">
                            <label class="font-bold text-sm text-slate-700 dark:text-slate-300 flex items-center gap-2">
                                👤 Nama Lengkap
                            </label>
                            <input type="text" placeholder="Masukkan nama Anda" required
                                class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-900 border-2 border-transparent focus:border-emerald-500 focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-emerald-500/10 rounded-2xl transition-all shadow-sm text-slate-950 dark:text-white font-medium">
                        </div>
                        <div class="space-y-2">
                            <label class="font-bold text-sm text-slate-700 dark:text-slate-300 flex items-center gap-2">
                                📞 Nomor WhatsApp
                            </label>
                            <input type="tel" placeholder="Contoh: 08123456789" required
                                class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-900 border-2 border-transparent focus:border-emerald-500 focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-emerald-500/10 rounded-2xl transition-all shadow-sm text-slate-950 dark:text-white font-medium">
                        </div>
                        <div class="space-y-2">
                            <label class="font-bold text-sm text-slate-700 dark:text-slate-300 flex items-center gap-2">
                                🍱 Pilih Acara / Kategori
                            </label>
                            <select required
                                class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-900 border-2 border-transparent focus:border-emerald-500 focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-emerald-500/10 rounded-2xl transition-all shadow-sm text-slate-950 dark:text-white font-medium">
                                <option value="Pernikahan">🍱 Pernikahan (Wedding)</option>
                                <option value="Acara Kantor">🏢 Acara Kantor (Corporate)</option>
                                <option value="Ulang Tahun">🎈 Ulang Tahun (Birthday)</option>
                                <option value="Syukuran / Aqiqah">🐑 Syukuran / Aqiqah</option>
                                <option value="Nasi Box Harian">🍱 Nasi Box Harian</option>
                                <option value="Lauk Pauk">🍗 Lauk Pauk</option>
                                <option value="Lain-lain">🎁 Lain-lain (Custom)</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="font-bold text-sm text-slate-700 dark:text-slate-300 flex items-center gap-2">
                                📝 Catatan & Detail Pesanan
                            </label>
                            <textarea
                                placeholder="Ceritakan detail pesanan Anda (Misal: Jumlah porsi, menu pilihan, alamat pengiriman)..."
                                required
                                class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-900 border-2 border-transparent focus:border-emerald-500 focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-emerald-500/10 rounded-2xl transition-all shadow-sm h-36 text-slate-950 dark:text-white font-medium"></textarea>
                        </div>
                        <button type="submit"
                            class="w-full py-4 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 shadow-xl shadow-emerald-500/20 hover:shadow-emerald-500/40 text-white font-bold rounded-2xl transform hover:-translate-y-0.5 active:translate-y-0 transition-all duration-300 flex items-center justify-center gap-2 text-base">
                            <span>🚀 Kirim Penawaran via WhatsApp</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Chatbot sudah dipindahkan ke layouts/app.blade.php sebagai komponen global -->

    <!-- Operational Hours & Availability Section -->
    <section id="operational" class="py-24 bg-white reveal">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid lg:grid-cols-12 gap-12 items-center">
                <!-- Left Side: Status Card -->
                <div class="lg:col-span-5 space-y-8">
                    <div
                        class="inline-flex items-center px-4 py-1.5 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-500 text-xs font-bold uppercase tracking-wider mb-2">
                        🕒 Real-time Availability
                    </div>
                    <h3 class="text-4xl lg:text-5xl font-poppins font-bold text-slate-900 leading-tight">
                        Kami Selalu <span class="text-green-500">Siap</span> Melayani Anda.
                    </h3>
                    <p class="text-slate-500 text-lg leading-relaxed">
                        Tim kami bekerja dengan standar kebersihan tinggi untuk memastikan pesanan Anda tiba tepat waktu dan
                        dalam kondisi terbaik.
                    </p>

                    <!-- Dynamic Status Banner -->
                    <div id="status-banner"
                        class="relative overflow-hidden group rounded-[2rem] p-6 sm:p-8 border-2 transition-all duration-500">
                        <div
                            class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-current opacity-10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700">
                        </div>
                        <div class="relative z-10 flex items-center space-x-4 sm:space-x-6">
                            <div id="status-icon-bg"
                                class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl flex items-center justify-center text-2xl sm:text-3xl shadow-lg transform group-hover:rotate-12 transition-transform duration-500">
                                <span id="status-emoji">⌛</span>
                            </div>
                            <div>
                                <h4 id="status-title" class="text-xl sm:text-2xl font-bold mb-1">Checking...</h4>
                                <p id="status-subtitle" class="text-xs sm:text-sm opacity-80 font-medium">Harap tunggu
                                    sebentar...</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Side: Schedule Table -->
                <div class="lg:col-span-7">
                    <div
                        class="bg-slate-50 dark:bg-slate-800/50 rounded-[2rem] sm:rounded-[3rem] p-4 sm:p-12 border border-slate-100 dark:border-slate-700 shadow-sm relative overflow-hidden group">
                        <!-- Decorative background -->
                        <div
                            class="absolute -top-24 -right-24 w-64 h-64 bg-green-500/5 rounded-full blur-3xl group-hover:bg-green-500/10 transition-colors duration-700">
                        </div>
                        <div
                            class="absolute -bottom-24 -left-24 w-64 h-64 bg-orange-500/5 rounded-full blur-3xl group-hover:bg-orange-500/10 transition-colors duration-700">
                        </div>

                        <div class="relative z-10 space-y-4" id="schedule-list">
                            @foreach($hours as $hour)
                                <div class="schedule-row flex items-center justify-between p-3 sm:p-5 rounded-xl sm:rounded-2xl transition-all duration-300"
                                    data-day="{{ $hour->day_index }}" data-open="{{ $hour->open_time }}"
                                    data-close="{{ $hour->close_time }}" data-closed="{{ $hour->is_closed ? '1' : '0' }}">
                                    <div class="flex items-center space-x-2 sm:space-x-4">
                                        <div
                                            class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl {{ $hour->day_index == 0 || $hour->day_index == 6 ? 'bg-orange-100 text-orange-600' : 'bg-white dark:bg-slate-700' }} flex items-center justify-center shadow-sm text-[10px] sm:text-sm font-bold">
                                            {{ substr($hour->day_name, 0, 3) }}
                                        </div>
                                        <span
                                            class="font-bold text-xs sm:text-base text-slate-700 dark:text-slate-200">{{ $hour->day_name }}</span>
                                    </div>
                                    <div class="flex items-center space-x-2 sm:space-x-3">
                                        @if($hour->is_closed)
                                            <span
                                                class="text-[10px] sm:text-sm font-bold text-red-500 uppercase tracking-widest">Tutup</span>
                                        @else
                                            <span
                                                class="text-[10px] sm:text-sm font-medium {{ $hour->day_index == 0 || $hour->day_index == 6 ? 'text-orange-400' : 'text-slate-400' }} italic">
                                                {{ \Carbon\Carbon::parse($hour->open_time)->format('H:i') }} -
                                                {{ \Carbon\Carbon::parse($hour->close_time)->format('H:i') }}
                                            </span>
                                        @endif
                                        <div class="day-indicator w-2 h-2 rounded-full bg-transparent"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer
        class="py-12 bg-white dark:bg-transparent text-slate-900 dark:text-white border-t border-slate-100 dark:border-transparent">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 text-center space-y-6">
            <div class="flex justify-center">
                <img src="{{ asset('images/logo.jpg') }}" alt="AISH Catering Logo"
                    class="h-20 sm:h-24 w-auto object-contain rounded-full shadow-lg bg-white p-0.5 border-2 border-emerald-50/50">
            </div>
            <p class="text-slate-500 dark:text-slate-400 text-sm max-w-md mx-auto">Menyajikan kebahagiaan melalui hidangan
                terbaik sejak 2018.
                Kualitas bintang 5, harga kaki lima.</p>
            <div class="flex justify-center space-x-8 text-xs font-bold uppercase tracking-widest text-slate-500">
                <a href="{{ $contents['instagram'] ?? '#' }}" class="hover:text-white transition-colors">Instagram</a>
                <a href="{{ $contents['facebook'] ?? '#' }}" class="hover:text-white transition-colors">Facebook</a>
                <a href="{{ $contents['tiktok'] ?? '#' }}" class="hover:text-white transition-colors">tiktok</a>
            </div>
            <div class="pt-8 border-t border-white/5 text-[10px] text-slate-600">
                &copy; 2026 AISH Catering Indonesia. All Rights Reserved.
            </div>
        </div>
    </footer>

    <script>


        // Testimonial Carousel Logics
        let currentSlide = 0;
        const slider = document.getElementById('testimonial-slider');
        const slideCount = 3;

        if (slider) {
            setInterval(() => {
                currentSlide = (currentSlide + 1) % slideCount;
                const offset = currentSlide * -100;
                slider.style.transform = `translateX(${offset}%)`;
            }, 5000);
        }

        // Lightbox Logic
        function openLightbox(src, title = '', desc = '') {
            const lightbox = document.getElementById('lightbox');
            const img = document.getElementById('lightbox-img');
            const titleEl = document.getElementById('lightbox-title');
            const descEl = document.getElementById('lightbox-desc');
            const captionBox = document.getElementById('lightbox-caption-box');

            if (!lightbox || !img) return;

            img.src = src;
            titleEl.innerText = title;
            descEl.innerText = desc;

            lightbox.style.display = 'flex';
            lightbox.classList.remove('hidden');

            if (title || desc) {
                captionBox.classList.remove('opacity-0');
                captionBox.classList.add('opacity-100');
            } else {
                captionBox.classList.add('opacity-0');
                captionBox.classList.remove('opacity-100');
            }
        }

        // Chatbot logic sudah dipindahkan ke layouts/app.blade.php

        // Theme Toggle Logic
        const themeToggleBtn = document.getElementById('theme-toggle');
        const darkIcon = document.getElementById('theme-toggle-dark-icon');
        const lightIcon = document.getElementById('theme-toggle-light-icon');

        function updateThemeIcons() {
            if (document.documentElement.classList.contains('dark')) {
                lightIcon.classList.remove('hidden');
                darkIcon.classList.add('hidden');
            } else {
                darkIcon.classList.remove('hidden');
                lightIcon.classList.add('hidden');
            }
        }

        updateThemeIcons();

        themeToggleBtn.addEventListener('click', () => {
            document.documentElement.classList.toggle('dark');
            const isDark = document.documentElement.classList.contains('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            updateThemeIcons();
        });

        // Menu Search, Filter & Pagination Logic
        const filterBtns = document.querySelectorAll('.filter-btn');
        const menuSearch = document.getElementById('menu-search');
        const menuItems = document.querySelectorAll('.menu-item');
        const menuGrid = document.getElementById('menu-grid');
        const menuPagination = document.getElementById('menu-pagination');

        let currentPage = 1;
        const itemsPerPage = 6;

        function filterMenu(isSearch = false) {
            const searchTerm = menuSearch.value.toLowerCase();
            const activeCategory = document.querySelector('.filter-btn.active').dataset.category;
            const noResults = document.getElementById('no-results');

            const performFilter = () => {
                let matchedItems = [];
                menuItems.forEach(item => {
                    const title = item.getAttribute('data-title').toLowerCase();
                    const category = item.getAttribute('data-category');
                    const matchesSearch = title.includes(searchTerm);
                    const matchesCategory = activeCategory === 'all' || category === activeCategory;

                    if (matchesSearch && matchesCategory) {
                        matchedItems.push(item);
                    } else {
                        item.style.display = 'none';
                        item.classList.remove('animate-reveal');
                    }
                });

                const visibleCount = matchedItems.length;

                if (visibleCount === 0) {
                    noResults.classList.remove('hidden');
                    menuGrid.classList.add('hidden');
                    menuPagination.innerHTML = '';
                    menuPagination.classList.add('hidden');
                } else {
                    noResults.classList.add('hidden');
                    menuGrid.classList.remove('hidden');

                    const totalPages = Math.ceil(visibleCount / itemsPerPage);

                    // Adjust currentPage if it exceeds totalPages
                    if (currentPage > totalPages) {
                        currentPage = totalPages;
                    }
                    if (currentPage < 1) {
                        currentPage = 1;
                    }

                    // Display only items for current page
                    const startIndex = (currentPage - 1) * itemsPerPage;
                    const endIndex = startIndex + itemsPerPage;

                    matchedItems.forEach((item, index) => {
                        if (index >= startIndex && index < endIndex) {
                            item.style.display = 'block';
                            item.classList.add('animate-reveal');
                        } else {
                            item.style.display = 'none';
                            item.classList.remove('animate-reveal');
                        }
                    });

                    // Render pagination buttons
                    renderPagination(totalPages);
                }
            };

            if (isSearch) {
                // Instant filter for search
                performFilter();
            } else {
                // Loading effect only for category filter
                menuGrid.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
                setTimeout(() => {
                    performFilter();
                    menuGrid.classList.remove('opacity-0', 'scale-95', 'pointer-events-none');
                    menuGrid.classList.add('transition-all', 'duration-500');
                }, 300);
            }
        }

        function renderPagination(totalPages) {
            if (totalPages < 1 || menuItems.length <= 6) {
                menuPagination.innerHTML = '';
                menuPagination.classList.add('hidden');
                return;
            }

            menuPagination.classList.remove('hidden');
            let html = '';

            // Prev Button
            if (currentPage > 1) {
                html += `
                    <button onclick="goToMenuPage(${currentPage - 1})"
                        class="px-4 py-2.5 rounded-2xl text-xs sm:text-sm font-black uppercase tracking-wider transition-all duration-300 hover:scale-105 active:scale-95 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-white/10 text-slate-700 dark:text-white/80 hover:text-emerald-500 dark:hover:text-emerald-400">
                        ‹
                    </button>
                `;
            } else {
                html += `
                    <button disabled
                        class="px-4 py-2.5 rounded-2xl text-xs sm:text-sm font-black uppercase tracking-wider opacity-40 cursor-not-allowed bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-400 dark:text-white/30">
                        ‹
                    </button>
                `;
            }

            // Numeric Buttons
            for (let i = 1; i <= totalPages; i++) {
                if (i === currentPage) {
                    html += `
                        <button class="w-10 h-10 rounded-2xl text-xs sm:text-sm font-black transition-all duration-300 scale-105 bg-gradient-to-r from-emerald-500 to-emerald-600 dark:from-[#3d8c75] dark:to-[#1b4d3e] text-white shadow-lg shadow-emerald-500/30">
                            ${i}
                        </button>
                    `;
                } else {
                    html += `
                        <button onclick="goToMenuPage(${i})"
                            class="w-10 h-10 rounded-2xl text-xs sm:text-sm font-black transition-all duration-300 hover:scale-105 active:scale-95 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-white/10 text-slate-700 dark:text-white/80 hover:text-emerald-500 dark:hover:text-emerald-400">
                            ${i}
                        </button>
                    `;
                }
            }

            // Next Button
            if (currentPage < totalPages) {
                html += `
                    <button onclick="goToMenuPage(${currentPage + 1})"
                        class="px-4 py-2.5 rounded-2xl text-xs sm:text-sm font-black uppercase tracking-wider transition-all duration-300 hover:scale-105 active:scale-95 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-white/10 text-slate-700 dark:text-white/80 hover:text-emerald-500 dark:hover:text-emerald-400">
                        ›
                    </button>
                `;
            } else {
                html += `
                    <button disabled
                        class="px-4 py-2.5 rounded-2xl text-xs sm:text-sm font-black uppercase tracking-wider opacity-40 cursor-not-allowed bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-400 dark:text-white/30">
                        ›
                    </button>
                `;
            }

            menuPagination.innerHTML = html;
        }

        window.goToMenuPage = function(page) {
            currentPage = page;
            filterMenu(false); // isSearch = false, triggers loading animation

            // Scroll smoothly back up to the menus section
            const menusSection = document.getElementById('menu');
            if (menusSection) {
                menusSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        };

        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                if (btn.classList.contains('active')) return;
                filterBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                currentPage = 1; // Reset to page 1 on category filter change
                filterMenu(false); // isSearch = false, triggers loading animation
            });
        });

        menuSearch.addEventListener('input', () => {
            currentPage = 1; // Reset to page 1 on search input
            filterMenu(true); // isSearch = true, instant update
        });

        // Initial setup for buttons
        document.querySelector('.filter-btn[data-category="all"]').classList.add('active');
        filterMenu(true); // Initial run to apply 6-item pagination

        // Special Packages Pagination Logic
        const specialPackageItems = document.querySelectorAll('.special-package-item');
        const specialPackagesGrid = document.getElementById('special-packages-grid');
        const specialPackagesPagination = document.getElementById('special-packages-pagination');

        let currentPkgPage = 1;
        const pkgsPerPage = 3;

        function renderSpecialPackages() {
            if (!specialPackagesGrid) return;

            const totalItems = specialPackageItems.length;

            if (totalItems === 0) {
                if (specialPackagesPagination) specialPackagesPagination.classList.add('hidden');
                return;
            }

            const totalPages = Math.ceil(totalItems / pkgsPerPage);

            if (currentPkgPage > totalPages) currentPkgPage = totalPages;
            if (currentPkgPage < 1) currentPkgPage = 1;

            const startIndex = (currentPkgPage - 1) * pkgsPerPage;
            const endIndex = startIndex + pkgsPerPage;

            specialPackageItems.forEach((item, index) => {
                if (index >= startIndex && index < endIndex) {
                    item.style.display = 'block';
                    item.classList.add('animate-reveal');
                } else {
                    item.style.display = 'none';
                    item.classList.remove('animate-reveal');
                }
            });

            renderPkgPagination(totalPages);
        }

        function renderPkgPagination(totalPages) {
            if (!specialPackagesPagination) return;

            if (totalPages < 1 || specialPackageItems.length <= 3) {
                specialPackagesPagination.innerHTML = '';
                specialPackagesPagination.classList.add('hidden');
                return;
            }

            specialPackagesPagination.classList.remove('hidden');
            let html = '';

            // Prev Button
            if (currentPkgPage > 1) {
                html += `
                    <button onclick="goToPkgPage(${currentPkgPage - 1})"
                        class="px-4 py-2.5 rounded-2xl text-xs sm:text-sm font-black uppercase tracking-wider transition-all duration-300 hover:scale-105 active:scale-95 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-white/10 text-slate-700 dark:text-white/80 hover:text-green-500 dark:hover:text-green-400">
                        ‹
                    </button>
                `;
            } else {
                html += `
                    <button disabled
                        class="px-4 py-2.5 rounded-2xl text-xs sm:text-sm font-black uppercase tracking-wider opacity-40 cursor-not-allowed bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-400 dark:text-white/30">
                        ‹
                    </button>
                `;
            }

            // Numeric Buttons
            for (let i = 1; i <= totalPages; i++) {
                if (i === currentPkgPage) {
                    html += `
                        <button class="w-10 h-10 rounded-2xl text-xs sm:text-sm font-black transition-all duration-300 scale-105 bg-gradient-to-r from-green-500 to-emerald-600 text-white shadow-lg shadow-green-500/30">
                            ${i}
                        </button>
                    `;
                } else {
                    html += `
                        <button onclick="goToPkgPage(${i})"
                            class="w-10 h-10 rounded-2xl text-xs sm:text-sm font-black transition-all duration-300 hover:scale-105 active:scale-95 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-white/10 text-slate-700 dark:text-white/80 hover:text-green-500 dark:hover:text-green-400">
                            ${i}
                        </button>
                    `;
                }
            }

            // Next Button
            if (currentPkgPage < totalPages) {
                html += `
                    <button onclick="goToPkgPage(${currentPkgPage + 1})"
                        class="px-4 py-2.5 rounded-2xl text-xs sm:text-sm font-black uppercase tracking-wider transition-all duration-300 hover:scale-105 active:scale-95 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-white/10 text-slate-700 dark:text-white/80 hover:text-green-500 dark:hover:text-green-400">
                        ›
                    </button>
                `;
            } else {
                html += `
                    <button disabled
                        class="px-4 py-2.5 rounded-2xl text-xs sm:text-sm font-black uppercase tracking-wider opacity-40 cursor-not-allowed bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-400 dark:text-white/30">
                        ›
                    </button>
                `;
            }

            specialPackagesPagination.innerHTML = html;
        }

        window.goToPkgPage = function(page) {
            currentPkgPage = page;

            // Subtle transition effect
            specialPackagesGrid.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
            setTimeout(() => {
                renderSpecialPackages();
                specialPackagesGrid.classList.remove('opacity-0', 'scale-95', 'pointer-events-none');
                specialPackagesGrid.classList.add('transition-all', 'duration-500');
            }, 300);

            // Scroll smoothly back up
            const pkgSection = document.getElementById('event-packages');
            if (pkgSection) {
                pkgSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        };

        // Initial render for special packages
        renderSpecialPackages();

        // Gallery/Dokumentasi Pagination Logic
        const galleryItems = document.querySelectorAll('.gallery-item');
        const galleryGrid = document.getElementById('gallery-grid');
        const galleryPagination = document.getElementById('gallery-pagination');

        let currentGalleryPage = 1;
        const galleryPerPage = 6;

        function renderGallery() {
            if (!galleryGrid) return;

            const totalItems = galleryItems.length;

            if (totalItems === 0) {
                if (galleryPagination) galleryPagination.classList.add('hidden');
                return;
            }

            const totalPages = Math.ceil(totalItems / galleryPerPage);

            if (currentGalleryPage > totalPages) currentGalleryPage = totalPages;
            if (currentGalleryPage < 1) currentGalleryPage = 1;

            const startIndex = (currentGalleryPage - 1) * galleryPerPage;
            const endIndex = startIndex + galleryPerPage;

            galleryItems.forEach((item, index) => {
                if (index >= startIndex && index < endIndex) {
                    item.style.display = 'block';
                    item.classList.add('animate-reveal');
                } else {
                    item.style.display = 'none';
                    item.classList.remove('animate-reveal');
                }
            });

            renderGalleryPagination(totalPages);
        }

        function renderGalleryPagination(totalPages) {
            if (!galleryPagination) return;

            if (totalPages < 1 || galleryItems.length <= 6) {
                galleryPagination.innerHTML = '';
                galleryPagination.classList.add('hidden');
                return;
            }

            galleryPagination.classList.remove('hidden');
            let html = '';

            // Prev Button
            if (currentGalleryPage > 1) {
                html += `
                    <button onclick="goToGalleryPage(${currentGalleryPage - 1})"
                        class="px-4 py-2.5 rounded-2xl text-xs sm:text-sm font-black uppercase tracking-wider transition-all duration-300 hover:scale-105 active:scale-95 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-white/10 text-slate-700 dark:text-white/80 hover:text-green-500 dark:hover:text-green-400">
                        ‹
                    </button>
                `;
            } else {
                html += `
                    <button disabled
                        class="px-4 py-2.5 rounded-2xl text-xs sm:text-sm font-black uppercase tracking-wider opacity-40 cursor-not-allowed bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-400 dark:text-white/30">
                        ‹
                    </button>
                `;
            }

            // Numeric Buttons
            for (let i = 1; i <= totalPages; i++) {
                if (i === currentGalleryPage) {
                    html += `
                        <button class="w-10 h-10 rounded-2xl text-xs sm:text-sm font-black transition-all duration-300 scale-105 bg-gradient-to-r from-green-500 to-emerald-600 text-white shadow-lg shadow-green-500/30">
                            ${i}
                        </button>
                    `;
                } else {
                    html += `
                        <button onclick="goToGalleryPage(${i})"
                            class="w-10 h-10 rounded-2xl text-xs sm:text-sm font-black transition-all duration-300 hover:scale-105 active:scale-95 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-white/10 text-slate-700 dark:text-white/80 hover:text-green-500 dark:hover:text-green-400">
                            ${i}
                        </button>
                    `;
                }
            }

            // Next Button
            if (currentGalleryPage < totalPages) {
                html += `
                    <button onclick="goToGalleryPage(${currentGalleryPage + 1})"
                        class="px-4 py-2.5 rounded-2xl text-xs sm:text-sm font-black uppercase tracking-wider transition-all duration-300 hover:scale-105 active:scale-95 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-white/10 text-slate-700 dark:text-white/80 hover:text-green-500 dark:hover:text-green-400">
                        ›
                    </button>
                `;
            } else {
                html += `
                    <button disabled
                        class="px-4 py-2.5 rounded-2xl text-xs sm:text-sm font-black uppercase tracking-wider opacity-40 cursor-not-allowed bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-400 dark:text-white/30">
                        ›
                    </button>
                `;
            }

            galleryPagination.innerHTML = html;
        }

        window.goToGalleryPage = function(page) {
            currentGalleryPage = page;

            // Subtle transition effect
            galleryGrid.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
            setTimeout(() => {
                renderGallery();
                galleryGrid.classList.remove('opacity-0', 'scale-95', 'pointer-events-none');
                galleryGrid.classList.add('transition-all', 'duration-500');
            }, 300);

            // Scroll smoothly back up
            const gallerySection = document.getElementById('gallery');
            if (gallerySection) {
                gallerySection.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        };

        // Initial render for gallery
        renderGallery();
        // Scroll Reveal Logic
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.reveal, .reveal-text').forEach(el => observer.observe(el));

        // Navbar Scroll Effect (Optimized)
        const nav = document.getElementById('main-nav');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 20) {
                nav.classList.add('bg-white/90', 'dark:bg-[#0f172a]/95', 'backdrop-blur-xl', 'shadow-xl', 'py-1', 'border-slate-200/50', 'dark:border-slate-700/50');
                nav.classList.remove('bg-transparent', 'py-3', 'border-transparent');
            } else {
                nav.classList.remove('bg-white/90', 'dark:bg-[#0f172a]/95', 'backdrop-blur-xl', 'shadow-xl', 'py-1', 'border-slate-200/50', 'dark:border-slate-700/50');
                nav.classList.add('bg-transparent', 'py-3', 'border-transparent');
            }
        });

        // Auto-close mobile menu on desktop resize
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024 && mobileMenuOpen) {
                closeMobileMenu();
            }
        });
        // Portion Calculator Logic
        let currentCalcType = 'prasmanan';
        const dbMenus = @json($allMenus);

        const defaultPackages = {
            prasmanan: [
                { name: 'Prasmanan Paket A (Standard)', price: 65000, description: 'Nasi putih, 1 lauk utama (ayam/ikan), 1 sayur, sambal, kerupuk, air mineral.' },
                { name: 'Prasmanan Paket B (Premium)', price: 85000, description: 'Nasi putih, 2 lauk utama (ayam & daging/kakap), 1 sayur, sup, hidangan sampingan, es buah, sambal, kerupuk.' },
                { name: 'Prasmanan Paket C (Luxury)', price: 120000, description: 'Lengkap dengan sup kimlo, rendang daging sapi, kakap asam manis, hidangan pembuka/karedok, pudding, aneka es, buah potong.' }
            ],
            box: [
                { name: 'Nasi Box Paket A (Standard)', price: 20000, description: 'Nasi putih, ayam goreng/bakar, mie goreng, lalapan, sambal.' },
                { name: 'Nasi Box Paket B (Premium)', price: 25000, description: 'Nasi putih, ayam/daging sapi lada hitam, telur balado, capcay, kerupuk, buah.' },
                { name: 'Nasi Box Paket C (Luxury)', price: 35000, description: 'Nasi kuning/putih, ayam bakar, sambal terasi, rendang daging, tahu tempe, lalap, buah, kerupuk, air mineral.' }
            ],
            snack: [
                { name: 'Snack Box Paket A (Hemat)', price: 10000, description: '2 jenis kue tradisional/modern (contoh: risol & bolu gulung), air mineral cup.' },
                { name: 'Snack Box Paket B (Standard)', price: 15000, description: '3 jenis kue (lemper ayam, risoles rogout, lapis legit), air mineral cup.' },
                { name: 'Snack Box Paket C (Premium)', price: 20000, description: '4 jenis kue premium (pastry, sus buah, pastel telur, lemper), air mineral cup.' }
            ],
            tumpeng: [
                { name: 'Tumpeng Paket A (Porsi 10 Orang)', price: 140000, description: 'Tumpeng mini/kecil dengan ayam kuning, orek tempe, perkedel, mie, telur dadar iris, sambal.' },
                { name: 'Tumpeng Paket B (Porsi 20 Orang)', price: 260000, description: 'Tumpeng sedang dengan ayam goreng, telur balado, perkedel, tempe orek, mie goreng, urap sayur, sambal goreng ati.' },
                { name: 'Tumpeng Paket C (Porsi 30 Orang)', price: 370000, description: 'Tumpeng jumbo/mewah lengkap dengan ayam ingkung, telur balado, perkedel kentang, tempe orek, mie goreng jawa, urap sayuran, sambal goreng ati, emping.' }
            ],
            lainnya: [
                { name: 'Paket Aqiqah Sederhana', price: 1800000, description: '1 ekor kambing matang (sate & gulai), nasi kotak standar untuk 50 porsi.' },
                { name: 'Paket Aqiqah Lengkap', price: 2500000, description: '1 ekor kambing matang, nasi kotak premium untuk 100 porsi, sertifikat aqiqah, dokumentasi.' },
                { name: 'Paket Lauk Pauk Pesta', price: 40000, description: 'Custom porsi lauk pauk (ayam, sapi, ikan) untuk tambahan konsumsi acara.' }
            ]
        };

        function updatePackageDropdown() {
            const selectEl = document.getElementById('calc-package');
            if (!selectEl) return;
            selectEl.innerHTML = '';

            // 1. Get database menus for the selected type
            let filteredDbMenus = [];
            if (dbMenus && dbMenus.length > 0) {
                filteredDbMenus = dbMenus.filter(menu => {
                    const cat = menu.category.toLowerCase();
                    if (currentCalcType === 'prasmanan') {
                        return cat.includes('prasmanan');
                    } else if (currentCalcType === 'box') {
                        return cat.includes('box') || cat.includes('kotak');
                    } else if (currentCalcType === 'snack') {
                        return cat.includes('snack');
                    } else if (currentCalcType === 'tumpeng') {
                        return cat.includes('tumpeng');
                    } else {
                        return cat.includes('lauk') || cat.includes('aqiqah') || cat.includes('hemat') || cat.includes('lain');
                    }
                });
            }

            // 2. Add database menus to options
            if (filteredDbMenus.length > 0) {
                const optGroupDb = document.createElement('optgroup');
                optGroupDb.label = 'Menu Utama Restoran';
                filteredDbMenus.forEach(menu => {
                    const opt = document.createElement('option');
                    opt.value = `db_${menu.id}`;
                    opt.text = `${menu.name} - ${formatRupiah(menu.price)}`;
                    opt.setAttribute('data-price', menu.price);
                    opt.setAttribute('data-description', menu.description || 'Sajian lezat menu pilihan AISH Catering.');
                    optGroupDb.appendChild(opt);
                });
                selectEl.appendChild(optGroupDb);
            }

            // 3. Add default/fallback packages
            const defaults = defaultPackages[currentCalcType] || [];
            if (defaults.length > 0) {
                const optGroupDefault = document.createElement('optgroup');
                optGroupDefault.label = 'Paket Standar AISH';
                defaults.forEach((pkg, index) => {
                    const opt = document.createElement('option');
                    opt.value = `default_${currentCalcType}_${index}`;
                    opt.text = `${pkg.name} - ${formatRupiah(pkg.price)}`;
                    opt.setAttribute('data-price', pkg.price);
                    opt.setAttribute('data-description', pkg.description || '');
                    optGroupDefault.appendChild(opt);
                });
                selectEl.appendChild(optGroupDefault);
            }
        }

        function setCalcType(type) {
            currentCalcType = type;
            localStorage.setItem('calc_type', currentCalcType);
            localStorage.removeItem('calc_package');
            document.querySelectorAll('.calc-type-btn').forEach(btn => {
                const isActive = btn.getAttribute('data-type') === type;
                btn.classList.toggle('active', isActive);

                const label = btn.querySelector('span:last-child');

                if (isActive) {
                    btn.className = 'calc-type-btn active py-2.5 sm:py-3 px-1 rounded-xl text-center transition-all duration-200 border-2 border-emerald-500 bg-emerald-50 dark:border-emerald-500/50 dark:bg-emerald-500/10 shadow-sm';
                    if (label) label.className = 'block text-[8px] sm:text-[10px] font-bold text-emerald-600 dark:text-emerald-400';
                } else {
                    btn.className = 'calc-type-btn py-2.5 sm:py-3 px-1 rounded-xl text-center transition-all duration-200 border-2 border-slate-200 bg-white dark:border-white/10 dark:bg-white/5 hover:border-slate-300 dark:hover:border-white/20 shadow-sm dark:shadow-none';
                    if (label) label.className = 'block text-[8px] sm:text-[10px] font-bold text-slate-500 dark:text-white/50';
                }
            });
            updatePackageDropdown();
            calculatePortions();
        }

        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(number);
        }

        function calculatePortions() {
            const guestInput = document.getElementById('calc-guests');
            const guests = parseInt(guestInput.value) || 0;

            const selectEl = document.getElementById('calc-package');
            if (!selectEl) return;

            const selectedOpt = selectEl.options[selectEl.selectedIndex];
            let unitPrice = 0;
            let description = '';

            if (selectedOpt) {
                unitPrice = parseInt(selectedOpt.getAttribute('data-price')) || 0;
                description = selectedOpt.getAttribute('data-description') || '';
            }

            let total = 0;
            let side = 0;
            let buffer = "10%";

            if (currentCalcType === 'prasmanan') {
                total = Math.ceil(guests * 1.5);
                side = Math.ceil(guests * 2);
                buffer = "10%";
            } else if (currentCalcType === 'box') {
                total = guests;
                side = guests;
                buffer = "5%";
            } else if (currentCalcType === 'snack') {
                total = Math.ceil(guests * 2);
                side = guests;
                buffer = "5%";
            } else if (currentCalcType === 'tumpeng') {
                total = guests;
                side = Math.ceil(guests / 10);
                buffer = "5%";
            } else {
                total = guests;
                side = guests;
                buffer = "5%";
            }

            // Update labels dynamically based on service type
            const sideLabel = document.getElementById('calc-side-label');
            if (sideLabel) {
                if (currentCalcType === 'tumpeng') {
                    sideLabel.innerText = "🎋 Estimasi Jumlah Tumpeng";
                } else if (currentCalcType === 'snack') {
                    sideLabel.innerText = "🥤 Air Mineral & Cup";
                } else {
                    sideLabel.innerText = "🥤 Minuman & Dessert";
                }
            }

            const sideValEl = document.getElementById('result-side');
            if (sideValEl) {
                if (currentCalcType === 'tumpeng') {
                    sideValEl.innerText = side + " Tumpeng (Porsi 10)";
                } else {
                    sideValEl.innerText = side + " Porsi";
                }
            }

            // Estimate total price
            let totalPrice = 0;
            if (currentCalcType === 'tumpeng') {
                // If it is a Tumpeng, check package porsi size (e.g. 10, 20, 30) from the option text
                let porsiSize = 10;
                if (selectedOpt) {
                    const match = selectedOpt.text.match(/porsi\s*(\d+)/i);
                    if (match) {
                        porsiSize = parseInt(match[1]) || 10;
                    }
                }
                const qtyNeeded = Math.ceil(guests / porsiSize);
                totalPrice = qtyNeeded * unitPrice;

                animateValue("result-total", parseInt(document.getElementById('result-total').innerText) || 0, qtyNeeded, 500);
                document.getElementById('result-main').innerText = qtyNeeded + " Tumpeng (" + porsiSize + " Porsi/Tumpeng)";
                document.getElementById('result-main-label').innerText = "🎋 Jumlah Tumpeng";
                document.getElementById('result-total-label').innerText = "Total Tumpeng Disarankan";
            } else if (currentCalcType === 'lainnya' && selectedOpt && selectedOpt.text.toLowerCase().includes('aqiqah')) {
                // Aqiqah packages are priced flat per unit/package (usually 1 or 2 goats)
                let porsiSize = 50;
                if (selectedOpt.text.toLowerCase().includes('100 porsi') || description.toLowerCase().includes('100 porsi')) {
                    porsiSize = 100;
                }
                const qtyNeeded = Math.ceil(guests / porsiSize);
                totalPrice = qtyNeeded * unitPrice;

                animateValue("result-total", parseInt(document.getElementById('result-total').innerText) || 0, qtyNeeded, 500);
                document.getElementById('result-main').innerText = qtyNeeded + " Paket Aqiqah (" + porsiSize + " Porsi/Paket)";
                document.getElementById('result-main-label').innerText = "🐑 Jumlah Paket";
                document.getElementById('result-total-label').innerText = "Total Paket Disarankan";
            } else {
                totalPrice = total * unitPrice;
                animateValue("result-total", parseInt(document.getElementById('result-total').innerText) || 0, total, 500);
                document.getElementById('result-main').innerText = total + " Porsi";
                document.getElementById('result-main-label').innerText = "🍛 Lauk Utama";
                document.getElementById('result-total-label').innerText = "Total Porsi Disarankan";
            }

            // Display package description
            const descEl = document.getElementById('calc-package-desc');
            const descContainer = document.getElementById('calc-package-desc-container');
            if (descEl && descContainer) {
                descEl.innerText = description;
                if (description) {
                    descContainer.classList.remove('hidden');
                } else {
                    descContainer.classList.add('hidden');
                }
            }

            document.getElementById('result-buffer').innerText = buffer;
            document.getElementById('result-price').innerText = formatRupiah(totalPrice);

            // Persist selections to localStorage
            localStorage.setItem('calc_guests', guests);
            localStorage.setItem('calc_type', currentCalcType);
            if (selectedOpt) {
                localStorage.setItem('calc_package', selectedOpt.value);
            }
        }

        function animateValue(id, start, end, duration) {
            const obj = document.getElementById(id);
            const range = end - start;
            const minTimer = 50;
            let stepTime = Math.abs(Math.floor(duration / range));
            stepTime = Math.max(stepTime, minTimer);
            const startTime = new Date().getTime();
            const endTime = startTime + duration;
            let timer;

            function run() {
                const now = new Date().getTime();
                const remaining = Math.max((endTime - now) / duration, 0);
                const value = Math.round(end - (remaining * range));
                obj.innerText = value;
                if (value == end) {
                    clearInterval(timer);
                }
            }

            timer = setInterval(run, stepTime);
            run();
        }

        // Initialize Calculator with LocalStorage persistence
        const savedGuests = localStorage.getItem('calc_guests');
        const savedType = localStorage.getItem('calc_type');
        const savedPackage = localStorage.getItem('calc_package');

        if (savedGuests) {
            document.getElementById('calc-guests').value = savedGuests;
        }

        if (savedType) {
            currentCalcType = savedType;
            document.querySelectorAll('.calc-type-btn').forEach(btn => {
                const isActive = btn.getAttribute('data-type') === currentCalcType;
                btn.classList.toggle('active', isActive);

                const label = btn.querySelector('span:last-child');

                if (isActive) {
                    btn.className = 'calc-type-btn active py-2.5 sm:py-3 px-1 rounded-xl text-center transition-all duration-200 border-2 border-emerald-500 bg-emerald-50 dark:border-emerald-500/50 dark:bg-emerald-500/10 shadow-sm';
                    if (label) label.className = 'block text-[8px] sm:text-[10px] font-bold text-emerald-600 dark:text-emerald-400';
                } else {
                    btn.className = 'calc-type-btn py-2.5 sm:py-3 px-1 rounded-xl text-center transition-all duration-200 border-2 border-slate-200 bg-white dark:border-white/10 dark:bg-white/5 hover:border-slate-300 dark:hover:border-white/20 shadow-sm dark:shadow-none';
                    if (label) label.className = 'block text-[8px] sm:text-[10px] font-bold text-slate-500 dark:text-white/50';
                }
            });
        }

        updatePackageDropdown();

        if (savedPackage) {
            const selectEl = document.getElementById('calc-package');
            if (selectEl) {
                for (let i = 0; i < selectEl.options.length; i++) {
                    if (selectEl.options[i].value === savedPackage) {
                        selectEl.value = savedPackage;
                        break;
                    }
                }
            }
        }

        calculatePortions();

        // Add real-time listener
        document.getElementById('calc-guests').addEventListener('input', calculatePortions);

        // FAQ Toggle Function
        function toggleFaq(button) {
            const item = button.parentElement;
            const isActive = item.classList.contains('active');

            // Close all other items
            document.querySelectorAll('.faq-item').forEach(faq => {
                faq.classList.remove('active');
            });

            // Toggle current item
            if (!isActive) {
                item.classList.add('active');
            }
        }

        // Initial Calculation on Load
        window.addEventListener('load', () => {
            calculatePortions();
        });

        // Update operational status input

        function updateOpStatus() {
            const now = new Date();
            const hour = now.getHours();
            const min = now.getMinutes();
            const currentTimeStr = `${hour.toString().padStart(2, '0')}:${min.toString().padStart(2, '0')}`;
            const day = now.getDay(); // 0 is Sunday, 1 is Monday...

            // Navbar Elements
            const statusPill = document.getElementById('op-status-pill');
            const statusPing = document.getElementById('op-status-ping');
            const statusDot = document.getElementById('op-status-dot');
            const statusText = document.getElementById('op-status-text');

            // Section Elements
            const statusBanner = document.getElementById('status-banner');
            const statusIconBg = document.getElementById('status-icon-bg');
            const statusEmoji = document.getElementById('status-emoji');
            const statusTitle = document.getElementById('status-title');
            const statusSubtitle = document.getElementById('status-subtitle');

            // Business Hours Logic from DOM
            const currentRow = document.querySelector(`.schedule-row[data-day="${day}"]`);
            let isOpen = false;
            let displayHours = "Closed";

            if (currentRow) {
                const openTime = currentRow.getAttribute('data-open');
                const closeTime = currentRow.getAttribute('data-close');
                const isClosedManual = currentRow.getAttribute('data-closed') === '1';

                if (!isClosedManual && openTime && closeTime) {
                    isOpen = (currentTimeStr >= openTime && currentTimeStr < closeTime);
                    displayHours = `${openTime} - ${closeTime}`;
                }
            }

            // Update Navbar Pill
            if (isOpen) {
                statusPill?.classList.remove('bg-rose-500/10', 'bg-slate-100/80', 'border-rose-500/20', 'border-slate-200/50', 'dark:bg-rose-500/20', 'dark:border-rose-500/30');
                statusPill?.classList.add('bg-emerald-500/10', 'border-emerald-500/20', 'dark:bg-emerald-500/20', 'dark:border-emerald-500/30');
                statusPing?.classList.remove('bg-rose-400');
                statusPing?.classList.add('bg-emerald-400');
                statusDot?.classList.remove('bg-rose-500');
                statusDot?.classList.add('bg-emerald-500');
                if (statusText) {
                    statusText.innerText = 'OPEN NOW';
                    statusText.classList.remove('text-slate-500', 'text-rose-600', 'dark:text-rose-400');
                    statusText.classList.add('text-emerald-600', 'dark:text-emerald-400');
                }
            } else {
                statusPill?.classList.remove('bg-emerald-500/10', 'bg-slate-100/80', 'border-emerald-500/20', 'border-slate-200/50', 'dark:bg-emerald-500/20', 'dark:border-emerald-500/30');
                statusPill?.classList.add('bg-rose-500/10', 'border-rose-500/20', 'dark:bg-rose-500/20', 'dark:border-rose-500/30');
                statusPing?.classList.remove('bg-emerald-400');
                statusPing?.classList.add('bg-rose-400');
                statusDot?.classList.remove('bg-emerald-500');
                statusDot?.classList.add('bg-rose-500');
                if (statusText) {
                    statusText.innerText = 'CLOSED';
                    statusText.classList.remove('text-emerald-600', 'text-slate-500', 'dark:text-emerald-400');
                    statusText.classList.add('text-rose-600', 'dark:text-rose-400');
                }
            }

            // Update Section Banner
            if (isOpen) {
                statusBanner.className = "relative overflow-hidden group rounded-[2rem] p-8 border-2 transition-all duration-500 bg-green-50 border-green-200 text-green-900";
                statusIconBg.className = "w-20 h-20 rounded-2xl flex items-center justify-center text-3xl shadow-lg transform group-hover:rotate-12 transition-transform duration-500 bg-white text-green-500";
                statusEmoji.innerText = "🍳";
                statusTitle.innerText = "Open Now";
                statusSubtitle.innerText = `Kami sedang melayani pesanan hingga pukul ${displayHours.split(' - ')[1]} hari ini.`;
            } else {
                statusBanner.className = "relative overflow-hidden group rounded-[2rem] p-8 border-2 transition-all duration-500 bg-red-50 border-red-200 text-red-900";
                statusIconBg.className = "w-20 h-20 rounded-2xl flex items-center justify-center text-3xl shadow-lg transform group-hover:rotate-12 transition-transform duration-500 bg-white text-red-500";
                statusEmoji.innerText = "😴";
                statusTitle.innerText = "Closed Now";
                statusSubtitle.innerText = "Maaf, kami sedang tutup. Silakan cek kembali jadwal operasional kami.";
            }

            // Highlight Current Day in List
            document.querySelectorAll('.schedule-row').forEach(row => {
                const dayValue = parseInt(row.getAttribute('data-day'));
                if (dayValue === day) {
                    row.classList.add('bg-white', 'shadow-xl', 'ring-1', 'ring-slate-200', 'scale-[1.02]', 'z-10');
                    const indicator = row.querySelector('.day-indicator');
                    indicator.classList.remove('bg-transparent');
                    indicator.classList.add(isOpen ? 'bg-green-500' : 'bg-red-500', 'animate-pulse');
                } else {
                    row.classList.remove('bg-white', 'shadow-xl', 'ring-1', 'ring-slate-200', 'scale-[1.02]', 'z-10');
                    const indicator = row.querySelector('.day-indicator');
                    indicator.classList.add('bg-transparent');
                    indicator.classList.remove('bg-green-500', 'bg-red-500', 'animate-pulse');
                }
            });
        }

        // Initial update and periodic check
        updateOpStatus();
        setInterval(updateOpStatus, 60000); // Check every minute

        // --- LEAFLET MAP & GPS GEOLOCATION LOGIC ---
        let map;
        let mainMarker;
        const businessCoordinates = [0.8917, 108.9869]; // Jl. Pramuka Gg. Ali Ahmad, Condong, Kota Singkawang

        // Initialize Leaflet Map
        function initLeafletMap() {
            if (map) return;

            // Create map object
            map = L.map('leaflet-map', {
                center: businessCoordinates,
                zoom: 15,
                zoomControl: false // Custom controls for visual beauty
            });

            // Add custom premium map tiles (CartoDB Positron / Voyager)
            L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
                subdomains: 'abcd',
                maxZoom: 20
            }).addTo(map);

            // Add standard zoom control at the bottom right
            L.control.zoom({
                position: 'bottomright'
            }).addTo(map);

            // Pulse CSS style for GPS location
            const style = document.createElement('style');
            style.innerHTML = `
                    .pulsing-marker {
                        background: #3d8c75;
                        border: 3px solid #ffffff;
                        border-radius: 50%;
                        box-shadow: 0 0 0 0 rgba(61, 140, 117, 0.7);
                        animation: pulse 1.8s infinite cubic-bezier(0.66, 0, 0, 1);
                    }
                    @keyframes pulse {
                        to {
                            box-shadow: 0 0 0 18px rgba(61, 140, 117, 0);
                        }
                    }
                    .custom-business-marker {
                        display: flex;
                        align-items: center;
                        justify-content: center;
                    }
                `;
            document.head.appendChild(style);

            // Place Aish Catering office default marker
            const businessIcon = L.divIcon({
                className: 'custom-business-marker',
                html: `<div class="w-8 h-8 rounded-full bg-emerald-500 border-2 border-white shadow-xl flex items-center justify-center text-white font-bold animate-bounce text-sm">🍱</div>`,
                iconSize: [32, 32],
                iconAnchor: [16, 32]
            });

            mainMarker = L.marker(businessCoordinates, { icon: businessIcon }).addTo(map);
            mainMarker.bindPopup('<div class="font-outfit p-1"><h4 class="font-bold text-slate-800 text-sm">🏢 Kantor Aish Catering</h4><p class="text-xs text-slate-500 mt-0.5">Jl. Pramuka Gg. Ali Ahmad, Condong, Kota Singkawang, Kalimantan Barat</p></div>').openPopup();
        }

        // Reset to office location
        function focusBusinessLocation() {
            if (!map) return;
            map.flyTo(businessCoordinates, 15, { duration: 1.5 });

            // Re-apply business icon
            const businessIcon = L.divIcon({
                className: 'custom-business-marker',
                html: `<div class="w-8 h-8 rounded-full bg-emerald-500 border-2 border-white shadow-xl flex items-center justify-center text-white font-bold animate-bounce text-sm">🍱</div>`,
                iconSize: [32, 32],
                iconAnchor: [16, 32]
            });
            mainMarker.setIcon(businessIcon);
            mainMarker.setLatLng(businessCoordinates);
            mainMarker.bindPopup('<div class="font-outfit p-1"><h4 class="font-bold text-slate-800 text-sm">🏢 Kantor Aish Catering</h4><p class="text-xs text-slate-500 mt-0.5">Jl. Pramuka Gg. Ali Ahmad, Condong, Kota Singkawang, Kalimantan Barat</p></div>').openPopup();

            // Hide GPS address card
            const addressCard = document.getElementById('detected-address-card');
            if (addressCard) {
                addressCard.classList.add('opacity-0', 'translate-y-2');
                setTimeout(() => addressCard.classList.add('hidden'), 500);
            }
        }

        // Detect GPS Location
        function detectGPSLocation() {
            const overlay = document.getElementById('map-status-overlay');
            const statusText = document.getElementById('map-status-text');
            const addressCard = document.getElementById('detected-address-card');
            const addressText = document.getElementById('detected-address-text');
            const notesField = document.querySelector('textarea[placeholder*="Ceritakan detail pesanan Anda"]');

            if (!navigator.geolocation) {
                alert("Browser Anda tidak mendukung deteksi lokasi (GPS).");
                return;
            }

            // Show loading overlay
            overlay.classList.remove('hidden', 'opacity-0');
            statusText.innerText = "Mengambil koordinat GPS...";

            navigator.geolocation.getCurrentPosition(
                async (position) => {
                    const lat = position.coords.latitude;
                    const lon = position.coords.longitude;
                    const accuracy = position.coords.accuracy;

                    statusText.innerText = "Menerjemahkan alamat...";

                    // Center map on user location
                    map.flyTo([lat, lon], 17, { duration: 1.5 });

                    // Put pulsing custom GPS marker
                    const gpsIcon = L.divIcon({
                        className: 'pulsing-marker',
                        iconSize: [16, 16],
                        iconAnchor: [8, 8]
                    });
                    mainMarker.setIcon(gpsIcon);
                    mainMarker.setLatLng([lat, lon]);

                    try {
                        // Reverse Geocode using OpenStreetMap Nominatim API
                        const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lon}`);
                        if (response.ok) {
                            const data = await response.json();
                            const address = data.display_name || `Koordinat: ${lat.toFixed(5)}, ${lon.toFixed(5)}`;

                            // Update popup
                            mainMarker.bindPopup(`<div class="font-outfit p-1"><h4 class="font-bold text-emerald-600 text-sm">📍 Lokasi Anda (Akurasi: ${Math.round(accuracy)}m)</h4><p class="text-xs text-slate-500 mt-1 leading-relaxed">${address}</p></div>`).openPopup();

                            // Show detected address card
                            addressText.innerText = address;
                            addressCard.classList.remove('hidden');
                            setTimeout(() => addressCard.classList.remove('opacity-0', 'translate-y-2'), 50);

                            // Autofill the shipping notes in the form!
                            if (notesField) {
                                // Keep existing notes if user typed something
                                const currentText = notesField.value;
                                const gpsHeader = `📍 RENCANA LOKASI PENGIRIMAN (GPS):\n📍 Alamat: ${address}\n📍 Koordinat: https://www.google.com/maps?q=${lat},${lon}\n\n`;

                                if (!currentText.includes("LOKASI PENGIRIMAN (GPS)")) {
                                    notesField.value = gpsHeader + currentText;
                                }
                            }
                        } else {
                            throw new Error();
                        }
                    } catch (e) {
                        const fallbackAddress = `Koordinat GPS: ${lat.toFixed(5)}, ${lon.toFixed(5)}`;
                        mainMarker.bindPopup(`<div class="font-outfit p-1"><h4 class="font-bold text-emerald-600 text-sm">📍 Lokasi Anda</h4><p class="text-xs text-slate-500 mt-1">${fallbackAddress}</p></div>`).openPopup();
                        addressText.innerText = fallbackAddress;
                        addressCard.classList.remove('hidden');
                        setTimeout(() => addressCard.classList.remove('opacity-0', 'translate-y-2'), 50);
                    } finally {
                        overlay.classList.add('opacity-0');
                        setTimeout(() => overlay.classList.add('hidden'), 500);
                    }
                },
                (error) => {
                    overlay.classList.add('opacity-0');
                    setTimeout(() => overlay.classList.add('hidden'), 500);

                    let errorMsg = "Gagal mendeteksi lokasi GPS Anda.";
                    if (error.code === error.PERMISSION_DENIED) {
                        errorMsg = "Izin GPS ditolak. Silakan aktifkan izin lokasi di browser Anda.";
                    } else if (error.code === error.POSITION_UNAVAILABLE) {
                        errorMsg = "Informasi lokasi GPS tidak tersedia.";
                    } else if (error.code === error.TIMEOUT) {
                        errorMsg = "Waktu pencarian lokasi GPS habis.";
                    }
                    alert(errorMsg);
                },
                { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
            );
        }

        // Initialize systems on window load
        window.addEventListener('load', () => {
            initLeafletMap();
            initStatsCounter();
            initSecondaryStatsCounter();
        });

        // --- COUNTING STATS ANIMATION ENGINE ---
        function initStatsCounter() {
            const statsSection = document.getElementById('stats-section');
            const counters = document.querySelectorAll('.stat-counter');

            if (!statsSection || counters.length === 0) return;

            const animateCounter = (el) => {
                const target = parseFloat(el.getAttribute('data-target'));
                const decimals = parseInt(el.getAttribute('data-decimals') || '0');
                const duration = 2000; // 2 seconds animation duration
                const startTime = performance.now();

                const updateCount = (now) => {
                    const elapsed = now - startTime;
                    const progress = Math.min(elapsed / duration, 1);

                    // Smooth easing cubic Out function (easeOutCubic)
                    const easeProgress = 1 - Math.pow(1 - progress, 3);

                    const currentValue = easeProgress * target;
                    el.innerText = currentValue.toFixed(decimals);

                    if (progress < 1) {
                        requestAnimationFrame(updateCount);
                    } else {
                        el.innerText = target.toFixed(decimals);
                    }
                };

                requestAnimationFrame(updateCount);
            };

            // Using IntersectionObserver for Scroll Reveal
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        counters.forEach(counter => animateCounter(counter));
                        observer.unobserve(entry.target); // Trigger only once
                    }
                });
            }, {
                threshold: 0.15 // Trigger when 15% of the card is visible
            });

            observer.observe(statsSection);
        }

        // --- SECONDARY COUNTING STATS ENGINE ---
        function initSecondaryStatsCounter() {
            const statsSection = document.getElementById('stats-section-secondary');
            const counters = document.querySelectorAll('.stat-counter-secondary');

            if (!statsSection || counters.length === 0) return;

            const animateCounter = (el) => {
                const target = parseFloat(el.getAttribute('data-target'));
                const decimals = parseInt(el.getAttribute('data-decimals') || '0');
                const hasThousands = el.getAttribute('data-thousands') === 'true';
                const duration = 2000; // 2 seconds animation duration
                const startTime = performance.now();

                const updateCount = (now) => {
                    const elapsed = now - startTime;
                    const progress = Math.min(elapsed / duration, 1);

                    // Smooth easing cubic Out function (easeOutCubic)
                    const easeProgress = 1 - Math.pow(1 - progress, 3);

                    const currentValue = easeProgress * target;

                    if (hasThousands) {
                        el.innerText = Math.floor(currentValue).toLocaleString('id-ID');
                    } else {
                        el.innerText = currentValue.toFixed(decimals);
                    }

                    if (progress < 1) {
                        requestAnimationFrame(updateCount);
                    } else {
                        if (hasThousands) {
                            el.innerText = target.toLocaleString('id-ID');
                        } else {
                            el.innerText = target.toFixed(decimals);
                        }
                    }
                };

                requestAnimationFrame(updateCount);
            };

            // Using IntersectionObserver for Scroll Reveal
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        counters.forEach(counter => animateCounter(counter));
                        observer.unobserve(entry.target); // Trigger only once
                    }
                });
            }, {
                threshold: 0.15 // Trigger when 15% of the card is visible
            });

            observer.observe(statsSection);
        }

        // =============================================
        // LEAFLET MAP - AISH CATERING LOCATION
        // =============================================

        // Koordinat Kantor Aish Catering - Jl. Pramuka Gg. Ali Ahmad, Singkawang Tengah
        const BUSINESS_LAT  = 0.9079;
        const BUSINESS_LNG  = 108.9845;
        const BUSINESS_NAME = 'Kantor Aish Catering';
        const BUSINESS_ADDR = 'Jl. Pramuka Gg. Ali Ahmad, Condong, Kota Singkawang, Kalimantan Barat';
        const BUSINESS_GMAPS = 'https://www.google.com/maps/search/?api=1&query=Jl.+Pramuka+Gg.+Ali+Ahmad+Condong+Kota+Singkawang';

        let leafletMap        = null;
        let businessMarker    = null;
        let userMarker        = null;
        let userCircle        = null;

        function initLeafletMap() {
            const mapEl = document.getElementById('leaflet-map');
            if (!mapEl || leafletMap) return;

            // Inisialisasi peta dengan tile OpenStreetMap
            leafletMap = L.map('leaflet-map', {
                center: [BUSINESS_LAT, BUSINESS_LNG],
                zoom: 16,
                zoomControl: true,
                scrollWheelZoom: false,
            });

            // Tile layer OpenStreetMap
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors | &copy; Carto',
                maxZoom: 19,
            }).addTo(leafletMap);

            // Custom icon untuk kantor
            const officeIcon = L.divIcon({
                html: `<div style="background:#10b981;width:42px;height:42px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid #fff;box-shadow:0 4px 15px rgba(16,185,129,0.5);display:flex;align-items:center;justify-content:center;">
                            <span style="transform:rotate(45deg);font-size:18px;display:block;text-align:center;line-height:36px;">🏢</span>
                       </div>`,
                iconSize: [42, 42],
                iconAnchor: [21, 42],
                popupAnchor: [0, -44],
                className: '',
            });

            // Marker kantor
            businessMarker = L.marker([BUSINESS_LAT, BUSINESS_LNG], { icon: officeIcon })
                .addTo(leafletMap)
                .bindPopup(`
                    <div style="font-family:'Poppins',sans-serif;padding:6px 2px;">
                        <p style="font-weight:700;font-size:13px;margin:0 0 4px;color:#064e3b;">🏢 ${BUSINESS_NAME}</p>
                        <p style="font-size:11px;color:#6b7280;margin:0 0 8px;line-height:1.4;">${BUSINESS_ADDR}</p>
                        <a href="${BUSINESS_GMAPS}"
                           target="_blank"
                           style="display:inline-block;background:#10b981;color:#fff;padding:4px 10px;border-radius:8px;font-size:11px;font-weight:600;text-decoration:none;">
                           📍 Buka di Google Maps
                        </a>
                    </div>
                `, { maxWidth: 240 })
                .openPopup();
        }

        // Fokus ke lokasi kantor Aish Catering
        function focusBusinessLocation() {
            if (!leafletMap) initLeafletMap();
            leafletMap.flyTo([BUSINESS_LAT, BUSINESS_LNG], 17, { duration: 1.2 });
            if (businessMarker) businessMarker.openPopup();
        }

        // Deteksi GPS lokasi pengguna
        function detectGPSLocation() {
            if (!leafletMap) initLeafletMap();

            if (!navigator.geolocation) {
                alert('Browser Anda tidak mendukung GPS / Geolocation.');
                return;
            }

            // Tampilkan loading overlay
            const overlay   = document.getElementById('map-status-overlay');
            const statusTxt = document.getElementById('map-status-text');
            if (overlay) {
                overlay.style.opacity    = '1';
                overlay.style.pointerEvents = 'all';
            }
            if (statusTxt) statusTxt.textContent = '📡 Mencari lokasi Anda...';

            navigator.geolocation.getCurrentPosition(
                // SUCCESS
                function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    const acc = position.coords.accuracy;

                    // Sembunyikan overlay
                    if (overlay) {
                        overlay.style.opacity       = '0';
                        overlay.style.pointerEvents = 'none';
                    }

                    // Hapus marker user lama
                    if (userMarker) leafletMap.removeLayer(userMarker);
                    if (userCircle) leafletMap.removeLayer(userCircle);

                    // Custom icon untuk user
                    const userIcon = L.divIcon({
                        html: `<div style="background:#3b82f6;width:38px;height:38px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid #fff;box-shadow:0 4px 15px rgba(59,130,246,0.5);">
                                   <span style="transform:rotate(45deg);font-size:17px;display:block;text-align:center;line-height:32px;">📍</span>
                               </div>`,
                        iconSize: [38, 38],
                        iconAnchor: [19, 38],
                        popupAnchor: [0, -40],
                        className: '',
                    });

                    // Tambah marker user baru
                    userMarker = L.marker([lat, lng], { icon: userIcon })
                        .addTo(leafletMap)
                        .bindPopup(`
                            <div style="font-family:'Poppins',sans-serif;padding:6px 2px;">
                                <p style="font-weight:700;font-size:13px;margin:0 0 4px;color:#1e3a8a;">📍 Lokasi Anda</p>
                                <p style="font-size:11px;color:#6b7280;margin:0;">Akurasi: ±${Math.round(acc)} meter</p>
                                <p style="font-size:10px;color:#9ca3af;margin:4px 0 0;">${lat.toFixed(6)}, ${lng.toFixed(6)}</p>
                            </div>
                        `, { maxWidth: 200 })
                        .openPopup();

                    // Lingkaran akurasi
                    userCircle = L.circle([lat, lng], {
                        radius: acc,
                        color: '#3b82f6',
                        fillColor: '#3b82f6',
                        fillOpacity: 0.08,
                        weight: 1.5,
                    }).addTo(leafletMap);

                    // Fly to user location
                    leafletMap.flyTo([lat, lng], 16, { duration: 1.5 });

                    // Tampilkan kartu alamat GPS
                    const addrCard = document.getElementById('detected-address-card');
                    const addrText = document.getElementById('detected-address-text');
                    if (addrCard) {
                        addrCard.classList.remove('hidden');
                        setTimeout(() => {
                            addrCard.style.opacity   = '1';
                            addrCard.style.transform = 'translateY(0)';
                        }, 50);
                    }

                    // Reverse geocode pakai Nominatim
                    if (addrText) {
                        addrText.textContent = 'Mengambil alamat...';
                        fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`)
                            .then(r => r.json())
                            .then(data => {
                                addrText.textContent = data.display_name || `${lat.toFixed(5)}, ${lng.toFixed(5)}`;
                            })
                            .catch(() => {
                                addrText.textContent = `Koordinat: ${lat.toFixed(5)}, ${lng.toFixed(5)}`;
                            });
                    }
                },
                // ERROR
                function(err) {
                    if (overlay) {
                        overlay.style.opacity       = '0';
                        overlay.style.pointerEvents = 'none';
                    }
                    let msg = 'Gagal mendapatkan lokasi GPS.';
                    if (err.code === 1) msg = '❌ Izin lokasi ditolak. Aktifkan izin lokasi di browser Anda.';
                    if (err.code === 2) msg = '❌ Posisi tidak tersedia. Pastikan GPS aktif.';
                    if (err.code === 3) msg = '❌ Waktu habis. Coba lagi.';
                    alert(msg);
                },
                { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
            );
        }

        // Inisialisasi peta saat halaman siap
        document.addEventListener('DOMContentLoaded', function () {
            // Inisialisasi setelah sedikit delay agar kontainer sudah ter-render
            setTimeout(initLeafletMap, 300);
        });

        // Submit quotation form to WhatsApp
        function submitQuotation(event) {
            event.preventDefault();
            const name = event.target.querySelector('input[placeholder*="nama"]').value;
            const phone = event.target.querySelector('input[placeholder*="Contoh:"]').value;
            const eventType = event.target.querySelector('select').value;
            const notes = event.target.querySelector('textarea').value;

            if (!name || !phone) {
                alert("Harap isi Nama Lengkap dan Nomor WhatsApp Anda.");
                return;
            }

            const businessPhone = "{{ $contents['phone'] ?? '628123456789' }}".replace(/[^0-9]/g, '');
            const message = `Halo Aish Catering! 🍱\n\nSaya ingin meminta penawaran katering:\n\n👤 *Nama Lengkap:* ${name}\n📞 *Nomor WhatsApp:* ${phone}\n🎉 *Jenis Acara:* ${eventType}\n📝 *Catatan & Rencana Lokasi Pengiriman:* \n${notes}\n\nMohon informasi selanjutnya. Terima kasih!`;

            const waUrl = `https://api.whatsapp.com/send?phone=${businessPhone}&text=${encodeURIComponent(message)}`;
            window.open(waUrl, '_blank');
        }

        // --- Menu Item Click Detail Modal Logic ---
        function openMenuDetail(card, event) {
            try {
                // Ignore click if it's on a button/anchor/input/textarea
                if (event && (event.target.closest('a') || event.target.closest('button') || event.target.closest('input') || event.target.closest('textarea'))) {
                    return;
                }

                const menuModal = document.getElementById('menu-detail-modal');
                if (!menuModal) {
                    console.error('menu-detail-modal element not found!');
                    return;
                }
                const modalContent = document.getElementById('modal-content');

                const menuDataRaw = card.getAttribute('data-menu');
                if (!menuDataRaw) {
                    console.error('Menu data attribute not found!');
                    return;
                }

                let menu = {};
                try {
                    menu = JSON.parse(menuDataRaw);
                } catch (e) {
                    console.error('Error parsing menu JSON data:', e);
                    return;
                }

                const name = menu.name || '';
                const category = menu.category || '';
                const description = menu.description || '';
                const price = menu.price || '0';
                const image = menu.image_url || '';
                const isAvailable = menu.is_available === true || menu.is_available === 1 || menu.is_available === '1';
                const isFeatured = menu.is_featured === true || menu.is_featured === 1 || menu.is_featured === '1';
                const rating = parseFloat(menu.rating) || 5.0;
                const sold = parseInt(menu.sold) || 0;

                // Set content safely
                const nameEl = document.getElementById('modal-menu-name');
                if (nameEl) nameEl.textContent = name;

                const descEl = document.getElementById('modal-menu-description');
                if (descEl) descEl.textContent = description || 'Hidangan istimewa pilihan dengan kualitas terbaik, higienis, dan lezat dari Aish Catering.';

                const priceEl = document.getElementById('modal-menu-price');
                if (priceEl) priceEl.textContent = price;

                const imgEl = document.getElementById('modal-menu-image');
                if (imgEl) {
                    imgEl.src = image;
                    imgEl.alt = name;
                }

                const ratingEl = document.getElementById('modal-menu-rating');
                if (ratingEl) ratingEl.textContent = rating.toFixed(1);

                const soldEl = document.getElementById('modal-menu-sold');
                if (soldEl) soldEl.textContent = sold > 0 ? `${sold}+ terjual` : '0 terjual';

                // Set current menu name for operations (Save, Share, Report)
                if (modalContent) {
                    modalContent.setAttribute('data-current-menu-name', name);
                }

                // Set availability status badge
                const statusBadge = document.getElementById('modal-menu-status');
                if (statusBadge) {
                    if (isAvailable) {
                        statusBadge.textContent = 'Tersedia';
                        statusBadge.className = 'px-2.5 py-0.5 rounded-full text-[9px] font-extrabold uppercase tracking-wider bg-emerald-500/10 text-emerald-500 dark:bg-emerald-500/20';
                    } else {
                        statusBadge.textContent = 'Habis / Kosong';
                        statusBadge.className = 'px-2.5 py-0.5 rounded-full text-[9px] font-extrabold uppercase tracking-wider bg-rose-500/10 text-rose-500 dark:bg-rose-500/20 animate-pulse';
                    }
                }

                // Set category badge on image
                const catBadge = document.getElementById('modal-menu-category');
                if (catBadge) {
                    catBadge.textContent = category;
                }

                // Set recommended badge
                const featuredBadge = document.getElementById('modal-menu-featured');
                if (featuredBadge) {
                    if (isFeatured) {
                        featuredBadge.classList.remove('hidden');
                    } else {
                        featuredBadge.classList.add('hidden');
                    }
                }

                // Check and set Saved/Liked status
                let likedMenus = {};
                try {
                    likedMenus = JSON.parse(localStorage.getItem('liked_menus') || '{}');
                    if (typeof likedMenus !== 'object' || likedMenus === null) {
                        likedMenus = {};
                    }
                } catch (e) {
                    likedMenus = {};
                }
                const heartIcon = document.getElementById('modal-heart-icon');
                const btnSave = document.getElementById('modal-btn-save');
                const btnSaveText = btnSave ? btnSave.querySelector('span') : null;

                if (likedMenus[name]) {
                    if (heartIcon) {
                        heartIcon.setAttribute('fill', 'currentColor');
                        heartIcon.classList.add('text-rose-500');
                        heartIcon.classList.remove('text-slate-600', 'dark:text-slate-400');
                    }
                    if (btnSave) {
                        btnSave.classList.add('bg-rose-50', 'border-rose-200', 'text-rose-600', 'dark:bg-rose-950/20', 'dark:border-rose-900/50', 'dark:text-rose-400');
                        btnSave.classList.remove('text-slate-700', 'dark:text-slate-300');
                    }
                    if (btnSaveText) btnSaveText.textContent = 'Disimpan';
                } else {
                    if (heartIcon) {
                        heartIcon.setAttribute('fill', 'none');
                        heartIcon.classList.remove('text-rose-500');
                        heartIcon.classList.add('text-slate-600', 'dark:text-slate-400');
                    }
                    if (btnSave) {
                        btnSave.classList.remove('bg-rose-50', 'border-rose-200', 'text-rose-600', 'dark:bg-rose-950/20', 'dark:border-rose-900/50', 'dark:text-rose-400');
                        btnSave.classList.add('text-slate-700', 'dark:text-slate-300');
                    }
                    if (btnSaveText) btnSaveText.textContent = 'Simpan';
                }

                // Set Action Button WhatsApp redirect
                const actionBtn = document.getElementById('modal-menu-action');
                if (actionBtn) {
                    if (isAvailable) {
                        actionBtn.removeAttribute('disabled');
                        actionBtn.className = 'w-full py-3.5 bg-[#00880d] hover:bg-[#00700a] text-white rounded-full font-bold text-sm sm:text-base flex items-center justify-center transition-colors shadow-md';
                        
                        const waNumber = "{{ preg_replace('/[^0-9]/', '', $contents['whatsapp_number'] ?? '628123456789') }}";
                        const orderMessage = `Halo AISH Catering! 🍱\n\nSaya ingin memesan menu:\n* ${name} (${category})\n💰 *Harga:* Rp ${price}\n\nMohon dibantu proses pemesanan ini. Terima kasih!`;
                        actionBtn.href = `https://wa.me/${waNumber}?text=${encodeURIComponent(orderMessage)}`;
                        actionBtn.target = '_blank';
                    } else {
                        actionBtn.setAttribute('disabled', 'true');
                        actionBtn.removeAttribute('href');
                        actionBtn.className = 'w-full py-3.5 bg-slate-200 dark:bg-white/5 text-slate-400 dark:text-white/30 rounded-full font-bold text-sm sm:text-base flex items-center justify-center cursor-not-allowed';
                    }
                }

                // Animate Modal Open — use inline styles to bypass Tailwind purge
                menuModal.style.opacity = '1';
                menuModal.style.pointerEvents = 'auto';

                if (modalContent) {
                    modalContent.style.transform = 'translateY(0)';
                }

                // Lock scroll
                document.body.style.overflow = 'hidden';
            } catch (err) {
                console.error('Error opening menu detail modal:', err);
            }
        }

        function closeMenuDetail() {
            try {
                const menuModal = document.getElementById('menu-detail-modal');
                if (!menuModal) return;
                const modalContent = document.getElementById('modal-content');

                menuModal.style.opacity = '0';
                menuModal.style.pointerEvents = 'none';

                if (modalContent) {
                    modalContent.style.transform = 'translateY(100%)';
                }

                // Unlock scroll
                document.body.style.overflow = '';
            } catch (err) {
                console.error('Error closing menu detail modal:', err);
            }
        }

        // Simpan / Like function
        function toggleLikeMenu() {
            const modalContent = document.getElementById('modal-content');
            const name = modalContent ? modalContent.getAttribute('data-current-menu-name') : null;
            if (!name) return;

            const likedMenus = JSON.parse(localStorage.getItem('liked_menus') || '{}');
            const heartIcon = document.getElementById('modal-heart-icon');
            const btnSave = document.getElementById('modal-btn-save');
            const btnSaveText = btnSave ? btnSave.querySelector('span') : null;

            if (likedMenus[name]) {
                delete likedMenus[name];
                if (heartIcon) {
                    heartIcon.setAttribute('fill', 'none');
                    heartIcon.classList.remove('text-rose-500');
                    heartIcon.classList.add('text-slate-600', 'dark:text-slate-400');
                }
                if (btnSave) {
                    btnSave.classList.remove('bg-rose-50', 'border-rose-200', 'text-rose-600', 'dark:bg-rose-950/20', 'dark:border-rose-900/50', 'dark:text-rose-400');
                    btnSave.classList.add('text-slate-700', 'dark:text-slate-300');
                }
                if (btnSaveText) btnSaveText.textContent = 'Simpan';
                showToast('Menu dihapus dari favorit');
            } else {
                likedMenus[name] = true;
                if (heartIcon) {
                    heartIcon.setAttribute('fill', 'currentColor');
                    heartIcon.classList.add('text-rose-500');
                    heartIcon.classList.remove('text-slate-600', 'dark:text-slate-400');
                }
                if (btnSave) {
                    btnSave.classList.add('bg-rose-50', 'border-rose-200', 'text-rose-600', 'dark:bg-rose-950/20', 'dark:border-rose-900/50', 'dark:text-rose-400');
                    btnSave.classList.remove('text-slate-700', 'dark:text-slate-300');
                }
                if (btnSaveText) btnSaveText.textContent = 'Disimpan';
                showToast('Menu disimpan ke favorit');
            }

            localStorage.setItem('liked_menus', JSON.stringify(likedMenus));
        }

        // Lapor function
        function reportMenu() {
            const modalContent = document.getElementById('modal-content');
            const name = modalContent ? modalContent.getAttribute('data-current-menu-name') : null;
            if (!name) return;

            const waNumber = "{{ preg_replace('/[^0-9]/', '', $contents['whatsapp_number'] ?? '628123456789') }}";
            const message = `Halo AISH Catering! 🍱\n\nSaya ingin melaporkan kendala/bertanya mengenai menu:\n* ${name}\n\n[Mohon tuliskan keluhan/pertanyaan Anda di sini...]`;
            
            const confirmReport = confirm(`Laporkan menu "${name}" ke WhatsApp Aish Catering?`);
            if (confirmReport) {
                window.open(`https://wa.me/${waNumber}?text=${encodeURIComponent(message)}`, '_blank');
            }
        }

        // Bagikan function
        function shareMenu() {
            const modalContent = document.getElementById('modal-content');
            const name = modalContent ? modalContent.getAttribute('data-current-menu-name') : null;
            if (!name) return;

            const shareUrl = `${window.location.origin}${window.location.pathname}?menu=${encodeURIComponent(name)}`;
            
            if (navigator.share) {
                navigator.share({
                    title: `Menu Aish Catering: ${name}`,
                    text: `Cobain menu lezat "${name}" dari Aish Catering!`,
                    url: shareUrl
                }).catch(err => console.log(err));
            } else {
                navigator.clipboard.writeText(shareUrl)
                    .then(() => {
                        showToast('Tautan menu berhasil disalin!');
                    })
                    .catch(() => {
                        showToast('Gagal menyalin tautan');
                    });
            }
        }

        // Toast utility function
        function showToast(message) {
            let toast = document.getElementById('app-toast');
            if (!toast) {
                toast = document.createElement('div');
                toast.id = 'app-toast';
                toast.className = 'fixed bottom-24 left-1/2 -translate-x-1/2 z-[2000] px-4 py-2.5 bg-slate-900/90 dark:bg-white/95 text-white dark:text-slate-900 rounded-full text-xs font-bold shadow-lg flex items-center gap-2 opacity-0 pointer-events-none transition-all duration-300 transform translate-y-2';
                document.body.appendChild(toast);
            }
            toast.textContent = message;
            toast.classList.remove('opacity-0', 'pointer-events-none', 'translate-y-2');
            toast.classList.add('opacity-100', 'translate-y-0');
            
            setTimeout(() => {
                toast.classList.add('opacity-0', 'pointer-events-none', 'translate-y-2');
                toast.classList.remove('opacity-100', 'translate-y-0');
            }, 2500);
        }

        function initMenuModal() {
            const overlay = document.getElementById('modal-overlay');
            if (overlay) overlay.addEventListener('click', closeMenuDetail);
        }

        // Bind functions to window scope to guarantee global accessibility
        window.openMenuDetail = openMenuDetail;
        window.closeMenuDetail = closeMenuDetail;
        window.toggleLikeMenu = toggleLikeMenu;
        window.reportMenu = reportMenu;
        window.shareMenu = shareMenu;

        // Run immediately or on DOM ready
        if (document.readyState !== 'loading') {
            initMenuModal();
        } else {
            document.addEventListener('DOMContentLoaded', initMenuModal);
        }

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeMenuDetail();
            }
        });

        // Handle direct menu link sharing
        document.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            const menuName = urlParams.get('menu');
            if (menuName) {
                const menuCards = document.querySelectorAll('.menu-item');
                for (const card of menuCards) {
                    const menuDataRaw = card.getAttribute('data-menu');
                    if (menuDataRaw) {
                        try {
                            const menu = JSON.parse(menuDataRaw);
                            if (menu.name === menuName) {
                                setTimeout(() => {
                                    card.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                    openMenuDetail(card);
                                }, 500);
                                break;
                            }
                        } catch (e) {
                            console.error(e);
                        }
                    }
                }
            }
        });
    </script>

    <!-- Mobile Bottom Sheet / Desktop Modal HTML -->
    <div id="menu-detail-modal" class="fixed inset-0 flex items-end sm:items-center justify-center p-0 sm:p-4 transition-all duration-300" style="z-index:1000;opacity:0;pointer-events:none;">
        <!-- Backdrop overlay -->
        <div id="modal-overlay" class="absolute inset-0 transition-opacity duration-300" style="background:rgba(15,23,42,0.6);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);"></div>

        <!-- Modal Content Window -->
        <div id="modal-content" class="relative w-full sm:max-w-md bg-white dark:bg-slate-900 overflow-hidden shadow-2xl flex flex-col border border-slate-100 dark:border-white/5" style="border-radius:2.5rem 2.5rem 0 0;max-height:92vh;transform:translateY(100%);transition:transform 0.3s ease;">
            <!-- Mobile Drag Handle -->
            <div class="sm:hidden w-full flex justify-center py-4 cursor-pointer" onclick="closeMenuDetail()">
                <div class="w-12 h-1.5 bg-slate-300 dark:bg-slate-700 rounded-full"></div>
            </div>

            <!-- Scrollable content wrapper -->
            <div class="overflow-y-auto flex-1 px-4 pb-6 scrollbar-hide">
                <!-- Padded Image Card -->
                <div class="px-0 pt-1">
                    <div class="relative w-full aspect-square rounded-[1.5rem] overflow-hidden bg-slate-100 dark:bg-slate-950">
                        <img id="modal-menu-image" src="" alt="" class="w-full h-full object-cover">
                        
                        <!-- Floating Badges over Image -->
                        <span id="modal-menu-category" class="absolute top-4 left-4 px-3.5 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest bg-emerald-500 text-white shadow-md">
                            Kategori
                        </span>
                        <span id="modal-menu-featured" class="hidden absolute top-4 right-4 px-3.5 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest bg-orange-500 text-white shadow-md">
                            ✨ Recommended
                        </span>
                    </div>
                </div>

                <!-- Info Body -->
                <div class="mt-4 space-y-3">
                    <h3 id="modal-menu-name" class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white leading-tight">
                        Nama Menu
                    </h3>
                    
                    <!-- Rating and Sold Row (GrabFood Style) -->
                    <div id="modal-menu-rating-row" class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4 text-amber-500 fill-current" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <span id="modal-menu-rating" class="font-bold text-slate-900 dark:text-white">0.0</span>
                        </div>
                        <span class="text-slate-300 dark:text-slate-700">•</span>
                        <span id="modal-menu-sold">0 terjual</span>
                    </div>

                    <p id="modal-menu-description" class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
                        Deskripsi menu lengkap.
                    </p>

                    <div class="flex items-baseline gap-1">
                        <span class="text-sm font-bold text-emerald-600 dark:text-emerald-400">Rp</span>
                        <span id="modal-menu-price" class="text-lg sm:text-xl font-bold text-slate-900 dark:text-white">
                            0
                        </span>
                    </div>

                    <div class="flex items-center gap-2 pt-1">
                        <span id="modal-menu-status" class="px-2.5 py-0.5 rounded-full text-[9px] font-extrabold uppercase tracking-wider bg-emerald-500/10 text-emerald-500 dark:bg-emerald-500/20">
                            Tersedia
                        </span>
                    </div>

                    <!-- Action Pills Row -->
                    <div class="flex items-center gap-2 pt-3 flex-wrap">
                        <!-- Simpan Button -->
                        <button id="modal-btn-save" onclick="toggleLikeMenu()" class="flex items-center gap-1.5 px-4 py-2 border border-slate-200 dark:border-slate-800 rounded-full text-xs font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                            <svg id="modal-heart-icon" class="w-4 h-4 text-slate-600 dark:text-slate-400 transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            <span>Simpan</span>
                        </button>

                        <!-- Lapor Button -->
                        <button id="modal-btn-report" onclick="reportMenu()" class="flex items-center gap-1.5 px-4 py-2 border border-slate-200 dark:border-slate-800 rounded-full text-xs font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                            <svg class="w-4 h-4 text-slate-800 dark:text-slate-200" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="12" cy="12" r="10" fill="currentColor"/>
                                <path d="M12 8v4" class="stroke-white dark:stroke-slate-900" stroke-width="2" stroke-linecap="round"/>
                                <circle cx="12" cy="16" r="1.25" class="fill-white dark:fill-slate-900"/>
                            </svg>
                            <span>Lapor</span>
                        </button>

                        <!-- Bagikan Button -->
                        <button id="modal-btn-share" onclick="shareMenu()" class="flex items-center gap-1.5 px-4 py-2 border border-slate-200 dark:border-slate-800 rounded-full text-xs font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                            <svg class="w-4 h-4 text-slate-600 dark:text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <circle cx="18" cy="5" r="3" />
                                <circle cx="6" cy="12" r="3" />
                                <circle cx="18" cy="19" r="3" />
                                <path d="M8.59 13.51l6.83 3.98M15.41 6.51l-6.82 3.98" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <span>Bagikan</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Sticky Bottom CTA -->
            <div class="bg-white dark:bg-slate-900 border-t border-slate-100 dark:border-slate-800/50 p-4 shrink-0">
                <a id="modal-menu-action" href="#" class="w-full py-3.5 bg-[#00880d] hover:bg-[#00700a] text-white rounded-full font-bold text-sm sm:text-base flex items-center justify-center transition-colors shadow-md">
                    Tambah pembelian
                </a>
            </div>
        </div>
    </div>
@endsection
