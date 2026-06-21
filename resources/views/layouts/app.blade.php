<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AISH Catering - Modern, Fresh & Healthy</title>

    <!-- SEO -->
    <meta name="description"
        content="AISH Catering menghadirkan layanan katering premium, modern, dan sehat untuk setiap momen spesial Anda. Hijau segar, Orange menggugah selera.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap"
        rel="stylesheet">

    <!-- Tailwind CSS (Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --primary-green: #3d8c75; /* Medium Emerald Botanical */
            --primary-orange: #f97316;
            --bg-light: #ffffff;
            --bg-dark: #08100d; /* Midnight Forest Black */
            --surface-light: #ffffff;
            --surface-dark: #0d1a15; /* Forest Muted Deep Green Slate */
            --text-dark: #0e2820;
            --text-light: #f0fbf7;
            --text-muted: #4e6b62;
            --text-muted-dark: #a2dfcc; /* Mint Sage Sparkle */
            --border-light: #e6f7f2;
            --border-dark: #1b4d3e; /* Deep Forest Sage */
        }

        html.dark body {
            background-color: var(--bg-dark);
            color: var(--text-light);
        }

        html.dark .bg-white,
        html.dark section {
            background-color: var(--surface-dark) !important;
        }

        html.dark header#home {
            background-image: radial-gradient(at top left, rgba(61, 140, 117, 0.15), transparent 50%),
                radial-gradient(at bottom right, rgba(249, 115, 22, 0.1), transparent 50%),
                linear-gradient(to bottom right, #08100d, #040907) !important;
        }

        html.dark section#menu {
            background-image: radial-gradient(at top right, rgba(61, 140, 117, 0.05), transparent 40%),
                linear-gradient(to bottom, #08100d, #060b09) !important;
        }

        html.dark .card-menu {
            background: linear-gradient(145deg, #0d1a15, #08100d) !important;
            border: 1px solid rgba(162, 223, 204, 0.07) !important;
            box-shadow: 0 10px 30px -15px rgba(0, 0, 0, 0.7) !important;
        }

        html.dark .card-menu:hover {
            border-color: rgba(61, 140, 117, 0.4) !important;
            box-shadow: 0 20px 40px -20px rgba(61, 140, 117, 0.3) !important;
        }

        html.dark .bg-green-50,
        html.dark .bg-green-100 {
            background-color: rgba(61, 140, 117, 0.1) !important;
        }

        html.dark .bg-orange-50,
        html.dark .bg-orange-100 {
            background-color: rgba(249, 115, 22, 0.1) !important;
        }

        html.dark .text-slate-900,
        html.dark .text-slate-800,
        html.dark .text-slate-700,
        html.dark h1,
        html.dark h2,
        html.dark h3,
        html.dark h4 {
            color: var(--text-light) !important;
        }

        html.dark .text-slate-500,
        html.dark .text-slate-600,
        html.dark .text-slate-400,
        html.dark p {
            color: var(--text-muted-dark) !important;
        }

        html.dark .border-slate-100,
        html.dark .border-slate-200,
        html.dark .ring-slate-100,
        html.dark .border-transparent {
            border-color: var(--border-dark) !important;
        }

        html.dark nav {
            background-color: rgba(8, 16, 13, 0.92) !important;
            border-bottom-color: var(--border-dark) !important;
        }

        html.dark nav .text-slate-600,
        html.dark nav .text-slate-700 {
            color: #cbd5e1 !important;
        }

        /* Logo brand name in dark mode */
        html.dark nav .text-slate-900 {
            color: #f1f5f9 !important;
        }

        /* Mobile drawer dark mode */
        html.dark #mobile-menu > div {
            background-color: rgba(8, 16, 13, 0.97) !important;
            border-top-color: rgba(255,255,255,0.07) !important;
        }

        html.dark #mobile-menu a.text-slate-700 {
            color: #cbd5e1 !important;
        }

        html.dark #mobile-menu a.text-slate-700:hover {
            background-color: rgba(61, 140, 117, 0.1) !important;
            color: #a2dfcc !important;
        }

        html.dark #mobile-menu > div > div[style*="height:1px"] {
            background: rgba(255,255,255,0.07) !important;
        }

        /* Mobile hamburger & theme buttons in dark mode */
        html.dark #mobile-menu-btn,
        html.dark #theme-toggle-mobile {
            color: #cbd5e1 !important;
            background-color: rgba(255,255,255,0.06) !important;
        }

        /* Op status pill in dark mode */
        html.dark #op-status-pill {
            background-color: rgba(255,255,255,0.05) !important;
            border-color: rgba(255,255,255,0.1) !important;
        }

        html.dark .card-menu {
            border-color: var(--border-dark);
            background-color: #0d1a15 !important;
        }

        html.dark .bg-slate-900 {
            background-color: #040907 !important;
        }

        html.dark input,
        html.dark textarea,
        html.dark select {
            background-color: #0b1411 !important;
            border-color: var(--border-dark) !important;
            color: white !important;
        }

        body.loading {
            overflow: hidden !important;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-light);
            color: var(--text-dark);
        }

        h1,
        h2,
        h3,
        h4,
        .font-poppins {
            font-family: 'Poppins', sans-serif;
        }

        .btn-orange {
            background-color: var(--primary-orange);
            color: white;
            transition: all 0.3s ease;
        }

        .btn-orange:hover {
            background-color: #ea580c;
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(249, 115, 22, 0.3);
        }

        .btn-green {
            background-color: var(--primary-green);
            color: white;
            transition: all 0.3s ease;
        }

        .btn-green:hover {
            background-color: #16a34a;
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(34, 197, 94, 0.3);
        }

        .card-menu {
            border-radius: 1.5rem;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            border: 1px solid #f1f5f9;
        }

        .card-menu:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
            border-color: #dcfce7;
        }

        .card-menu:hover img {
            transform: scale(1.1);
        }

        .chatbot-container {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 1000;
        }

        .chatbot-btn {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 25px rgba(34, 197, 94, 0.4);
            cursor: pointer;
            transition: all 0.3s;
            animation: bounce-subtle 3s infinite ease-in-out;
        }

        .chatbot-btn:hover {
            transform: scale(1.1) rotate(5deg);
        }

        @keyframes reveal {
            0% {
                transform: translateY(30px) scale(0.95);
                opacity: 0;
            }

            100% {
                transform: translateY(0) scale(1);
                opacity: 1;
            }
        }

        .animate-reveal {
            animation: reveal 0.6s cubic-bezier(0.23, 1, 0.32, 1) forwards;
        }

        .filter-btn {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .filter-btn:hover {
            transform: translateY(-3px);
            background-color: rgba(34, 197, 94, 0.15) !important;
            border-color: rgba(34, 197, 94, 0.5) !important;
            color: #4ade80 !important;
        }

        .filter-btn.active {
            transform: scale(1.05);
            background: linear-gradient(135deg, #22c55e, #16a34a) !important;
            border-color: transparent !important;
            color: white !important;
            box-shadow: 0 8px 20px -4px rgba(34, 197, 94, 0.5);
        }

        .animate-marquee {
            animation: marquee 30s linear infinite;
        }

        .animate-marquee:hover {
            animation-play-state: paused;
        }

        @keyframes marquee {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-50%);
            }
        }

        @keyframes bounce-subtle {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        @media (max-width: 640px) {
            .chat-box {
                width: calc(100vw - 48px);
                right: -12px;
                height: 400px;
            }
        }

        .chat-box {
            position: absolute;
            bottom: 80px;
            right: 0;
            width: 350px;
            height: 500px;
            background: white;
            border-radius: 1.5rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            display: none;
            flex-direction: column;
            overflow: hidden;
        }

        .chat-header {
            background: var(--primary-green);
            padding: 20px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .chat-messages {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            background: #f8fafc;
        }

        .message {
            margin-bottom: 12px;
            max-width: 80%;
            padding: 10px 16px;
            border-radius: 1rem;
            font-size: 14px;
        }

        .message-bot {
            background: white;
            color: var(--text-dark);
            align-self: flex-start;
            border-bottom-left-radius: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
        }

        .message-user {
            background: var(--primary-green);
            color: white;
            align-self: flex-end;
            border-bottom-right-radius: 4px;
        }

        html.dark #chatbot-window {
            background-color: #1e293b !important;
            border-color: var(--border-dark) !important;
        }

        html.dark #chatbot-window .bg-slate-50 {
            background-color: #0f172a !important;
        }

        html.dark #chatbot-window .text-slate-800,
        html.dark #chatbot-window .text-slate-900 {
            color: white !important;
        }

        html.dark .chat-bubble-received {
            background-color: #334155 !important;
            color: white !important;
        }

        html.dark .chat-bubble-sent {
            background-color: var(--primary-green) !important;
            color: white !important;
        }

        html.dark #chatbot-window input {
            background-color: #0f172a !important;
            border-color: var(--border-dark) !important;
            color: white !important;
        }

        /* Scroll Reveal Animation */
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
            pointer-events: none;
        }

        .reveal.active {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }

        .reveal-delay-1 {
            transition-delay: 0.1s;
        }

        .reveal-delay-2 {
            transition-delay: 0.2s;
        }

        .reveal-delay-3 {
            transition-delay: 0.3s;
        }

        .reveal-delay-3 {
            transition-delay: 0.3s;
        }

        /* FAQ Accordion */
        .faq-item {
            transition: all 0.3s ease;
        }

        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.5s cubic-bezier(0, 1, 0, 1);
        }

        .faq-item.active .faq-answer {
            max-height: 1000px;
            transition: max-height 1s ease-in-out;
        }

        .faq-item.active .faq-icon {
            transform: rotate(180deg);
        }

        html.dark .faq-item {
            background-color: #1a2233 !important;
            border-color: var(--border-dark) !important;
        }

        html.dark .faq-item.active {
            background-color: #1e293b !important;
            border-color: rgba(34, 197, 94, 0.4) !important;
        }

        /* Calculator Buttons Styling */
        .calc-type-btn {
            background: var(--surface-light) !important;
            border-color: var(--border-light) !important;
            color: var(--text-dark) !important;
        }

        html.dark .calc-type-btn {
            background: rgba(255, 255, 255, 0.03) !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
            color: var(--text-light) !important;
        }

        .calc-type-btn.active {
            background: rgba(14, 165, 233, 0.05) !important;
            border-color: var(--primary-green) !important;
            color: var(--primary-green) !important;
            box-shadow: 0 4px 15px rgba(14, 165, 233, 0.2) !important;
        }

        html.dark .calc-type-btn.active {
            background: rgba(14, 165, 233, 0.1) !important;
        }

        /* Premium Preloader Styles */
        #preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #ffffff 0%, #f0fdf4 50%, #fff7ed 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 10000;
            transition: all 0.8s cubic-bezier(0.65, 0, 0.35, 1);
        }

        html.dark #preloader {
            background: radial-gradient(circle at top left, #1e293b, #0f172a);
        }

        .preloader-content {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 3rem;
            border-radius: 2.5rem;
            background: rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.05);
            transform: scale(0.9);
            opacity: 0;
            animation: preloader-in 1s cubic-bezier(0.23, 1, 0.32, 1) forwards;
        }

        html.dark .preloader-content {
            background: rgba(15, 23, 42, 0.6);
            border-color: rgba(255, 255, 255, 0.05);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        @keyframes preloader-in {
            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        .logo-container {
            position: relative;
            margin-bottom: 2rem;
        }

        .logo-glow {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 120px;
            height: 120px;
            background: radial-gradient(circle, var(--primary-green) 0%, transparent 70%);
            opacity: 0.3;
            filter: blur(20px);
            animation: glow-pulse 3s infinite ease-in-out;
        }

        @keyframes glow-pulse {

            0%,
            100% {
                transform: translate(-50%, -50%) scale(1);
                opacity: 0.2;
            }

            50% {
                transform: translate(-50%, -50%) scale(1.3);
                opacity: 0.4;
            }
        }

        .premium-loader-logo {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, var(--primary-green), #16a34a);
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 15px 30px -5px rgba(34, 197, 94, 0.4);
            animation: logo-float 4s infinite ease-in-out;
            position: relative;
            z-index: 10;
        }

        .premium-loader-logo span {
            font-family: 'Poppins', sans-serif;
            font-weight: 800;
            font-size: 48px;
            color: white;
            text-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        @keyframes logo-float {

            0%,
            100% {
                transform: translateY(0) rotate(0deg);
            }

            50% {
                transform: translateY(-10px) rotate(3deg);
            }
        }

        .progress-container {
            width: 200px;
            height: 6px;
            background: rgba(0, 0, 0, 0.05);
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 1rem;
            position: relative;
        }

        html.dark .progress-container {
            background: rgba(255, 255, 255, 0.1);
        }

        .progress-fill {
            height: 100%;
            width: 0%;
            background: linear-gradient(90deg, var(--primary-green), var(--primary-orange));
            border-radius: 10px;
            transition: width 0.4s cubic-bezier(0.1, 0.5, 0.5, 1);
        }

        .loading-text {
            font-family: 'Inter', sans-serif;
            font-weight: 500;
            font-size: 14px;
            letter-spacing: 0.05em;
            color: #64748b;
            text-align: center;
        }

        html.dark .loading-text {
            color: #94a3b8;
        }

        .percentage {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 13px;
            color: var(--primary-green);
            margin-left: 8px;
        }

        .preloader-hidden {
            opacity: 0;
            visibility: hidden;
            transform: scale(1.1);
            pointer-events: none;
        }

        /* Exit Animation for Main Content */
        #main-content {
            opacity: 0;
            transform: scale(0.98);
            transition: all 1s cubic-bezier(0.23, 1, 0.32, 1);
        }

        #main-content.visible {
            opacity: 1;
            transform: scale(1);
        }

        /* Calculator Styles */
        .calc-type-btn.active {
            border-color: var(--primary-green) !important;
            background-color: rgba(34, 197, 94, 0.05);
            color: var(--primary-green) !important;
        }

        html.dark .calc-type-btn {
            background-color: #1a2233;
            border-color: var(--border-dark);
            color: #94a3b8;
        }

        html.dark .calc-type-btn.active {
            background-color: rgba(34, 197, 94, 0.1);
            border-color: var(--primary-green) !important;
            color: white !important;
        }

        html.dark .bg-white.p-8.rounded-3xl.shadow-sm.border.border-slate-100 {
            background-color: #1a2233 !important;
            border-color: var(--border-dark) !important;
        }

        html.dark .text-slate-600.italic {
            color: #94a3b8 !important;
        }

        html.dark .text-slate-900 {
            color: white !important;
        }

        /* Parallax Effect */
        .parallax-bg {
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }

        @media (max-width: 1024px) {
            .parallax-bg {
                background-attachment: scroll;
                /* Disable on mobile for performance */
            }
        }

        @keyframes revealText {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .reveal-text {
            opacity: 0;
        }

        .reveal-text.active {
            animation: revealText 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
        }

        :root {
            --primary-green: #0ea5e9;
            /* Sky Blue 500 */
            --primary-orange: #ec4899;
            /* Pink 500 */
            --bg-light: #ffffff;
            --bg-dark: #09090b;
            /* Zinc 950 */
            --surface-light: #f8fafc;
            /* Slate 50 */
            --surface-dark: #18181b;
            /* Zinc 900 */
            --text-dark: #0f172a;
            --text-light: #f8fafc;
            --text-muted: #64748b;
            --text-muted-dark: #94a3b8;
            --border-light: rgba(0, 0, 0, 0.05);
            --border-dark: rgba(255, 255, 255, 0.08);
        }

        /* RESTORE NORMAL SCROLLING */
        html {
            scroll-behavior: smooth;
        }

        /* Show scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        #main-content>section,
        #main-content>header {
            min-height: auto !important;
            display: block;
            padding-top: 5rem !important;
            padding-bottom: 5rem !important;
        }

        /* Fix Footer */
        footer {
            scroll-snap-align: none;
        }

        /* Modernize Sections with Broken Backgrounds (Aplikasi & Parallax) */
        section.parallax-bg {
            background-image: none !important;
            background-color: var(--bg-dark) !important;
            color: white !important;
        }

        section.parallax-bg .absolute.inset-0 {
            display: none !important;
            /* Remove old overlays */
        }

        section.parallax-bg h2,
        section.parallax-bg h3,
        section.parallax-bg p {
            color: white !important;
        }

        /* GLOBAL OVERRIDES FOR TAILWIND CLASSES (Tanpa perlu rebuild npm) */
        .bg-green-500,
        .bg-green-600 {
            background-color: var(--primary-green) !important;
            color: white !important;
        }

        .text-green-500,
        .text-green-600,
        .hover\:text-green-600:hover {
            color: var(--primary-green) !important;
        }

        .border-green-500,
        .hover\:border-green-500:hover {
            border-color: var(--primary-green) !important;
        }

        .bg-green-50 {
            background-color: rgba(14, 165, 233, 0.05) !important;
        }

        .bg-green-100 {
            background-color: rgba(14, 165, 233, 0.1) !important;
        }

        .text-green-400 {
            color: #38bdf8 !important;
        }

        .text-green-700 {
            color: #0369a1 !important;
        }

        .shadow-green-200,
        .shadow-green-500\/40 {
            box-shadow: 0 10px 40px -10px rgba(14, 165, 233, 0.25) !important;
        }

        .from-green-500\/20 {
            --tw-gradient-from: rgba(14, 165, 233, 0.1) !important;
        }

        .bg-orange-500,
        .bg-orange-600 {
            background-color: var(--primary-orange) !important;
            color: white !important;
        }

        .text-orange-500,
        .text-orange-600 {
            color: var(--primary-orange) !important;
        }

        .border-orange-500 {
            border-color: var(--primary-orange) !important;
        }

        .bg-orange-50 {
            background-color: rgba(236, 72, 153, 0.05) !important;
        }

        .bg-orange-100 {
            background-color: rgba(236, 72, 153, 0.1) !important;
        }

        .text-orange-400 {
            color: #f472b6 !important;
        }

        .text-orange-700 {
            color: #be185d !important;
        }

        .shadow-orange-200 {
            box-shadow: 0 10px 40px -10px rgba(236, 72, 153, 0.25) !important;
        }

        .from-orange-500\/20 {
            --tw-gradient-from: rgba(236, 72, 153, 0.1) !important;
        }

        .focus-within\:border-green-500:focus-within {
            border-color: var(--primary-green) !important;
        }

        .focus-within\:ring-green-500\/10:focus-within {
            --tw-ring-color: rgba(0, 0, 0, 0.1) !important;
        }

        .group-focus-within\:text-green-500:focus-within {
            color: var(--primary-green) !important;
        }

        /* Premium Modern Minimalist & Bento Grid */
        nav {
            backdrop-filter: saturate(180%) blur(20px) !important;
            -webkit-backdrop-filter: saturate(180%) blur(20px) !important;
            background: rgba(255, 255, 255, 0.6) !important;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05) !important;
        }

        .card-menu,
        .bg-white.p-8.rounded-\[2rem\] {
            background: #ffffff !important;
            border: 1px solid rgba(0, 0, 0, 0.05) !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.02) !important;
            border-radius: 2rem !important;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }

        .card-menu:hover {
            transform: translateY(-8px) scale(1.02) !important;
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.1) !important;
            border-color: rgba(0, 0, 0, 0.1) !important;
        }

        /* Ambient Glows Removed for sleek minimal look */
        body::before,
        body::after {
            display: none !important;
        }

        html.dark body {
            background-color: var(--bg-dark);
            color: var(--text-light);
        }

        html.dark .bg-white,
        html.dark section {
            background-color: var(--bg-dark) !important;
        }

        html.dark header#home,
        html.dark section#menu {
            background-image: none !important;
        }

        html.dark nav {
            background: rgba(9, 9, 11, 0.7) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
        }

        html.dark .card-menu,
        html.dark .bg-white.p-8.rounded-\[2rem\] {
            background: var(--surface-dark) !important;
            border: 1px solid rgba(255, 255, 255, 0.05) !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2) !important;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-light);
            color: var(--text-dark);
        }

        h1,
        h2,
        h3,
        h4,
        .font-poppins {
            font-family: 'Space Grotesk', sans-serif !important;
            letter-spacing: -0.04em;
        }
    </style>
    <script>
        // Check for saved dark mode preference
        if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

    </script>
</head>

<body class="antialiased loading">
    <!-- Modern Preloader — JANGAN DIHAPUS: Sudah terintegrasi dengan brand AISH Catering -->
    <div id="preloader" class="fixed inset-0 z-[9999] flex items-center justify-center bg-slate-50 dark:bg-[#050c14] transition-all duration-1000 ease-in-out">
        <div class="relative flex flex-col items-center px-4">
            <!-- Animated Frame -->
            <div class="relative w-48 h-48 sm:w-64 sm:h-64 flex items-center justify-center">
                <!-- Inner Spinning Ring -->
                <div class="absolute inset-0 border-4 border-emerald-500/10 dark:border-emerald-500/20 rounded-full"></div>
                <div class="absolute inset-0 border-4 border-t-emerald-500 rounded-full animate-spin [animation-duration:1.5s]"></div>
                
                <!-- Outer Pulse Ring -->
                <div class="absolute -inset-4 border border-emerald-500/5 dark:border-emerald-500/10 rounded-full animate-pulse"></div>

                <!-- Brand Name -->
                <div class="text-center z-10 p-4 rounded-full bg-white/90 shadow-[0_0_30px_rgba(16,185,129,0.2)]">
                    <img src="{{ asset('images/logo.jpg') }}" alt="Aish Catering Logo" class="w-24 sm:w-32 h-auto object-contain rounded-full">
                </div>
            </div>
            
            <!-- Dynamic Status Text -->
            <div class="mt-12 text-center">
                <div class="flex items-center justify-center gap-1.5 mb-3">
                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-bounce [animation-delay:-0.3s]"></span>
                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-bounce [animation-delay:-0.15s]"></span>
                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-bounce"></span>
                </div>
                <p class="text-[10px] sm:text-xs text-slate-500 dark:text-slate-400 font-bold uppercase tracking-[0.4em] opacity-80">Sajian Istimewa Sedang Disiapkan</p>
            </div>

            <!-- Background Glow -->
            <div class="absolute -z-10 w-full max-w-[400px] aspect-square bg-emerald-500/10 rounded-full blur-[100px] animate-pulse"></div>
        </div>
    </div>

    <div id="main-content">
        @yield('content')
    </div>

    <!-- Integrated Brand Chat Container -->
    <div class="fixed bottom-4 right-4 sm:bottom-8 sm:right-8 z-[9999]" id="global-chatbot-container">
        <!-- Chat Window -->
        <div id="chat-box" 
             class="absolute bottom-16 sm:bottom-20 right-0 w-[280px] xs:w-[320px] sm:w-[420px] h-[450px] xs:h-[500px] sm:h-[600px] bg-slate-50 dark:bg-slate-900 rounded-3xl shadow-2xl flex flex-col overflow-hidden transition-all duration-500 origin-bottom-right scale-0 opacity-0 border border-slate-200/50 dark:border-slate-700"
             style="display: flex;">
            
            <!-- Brand Header -->
            <div class="bg-[#ffffff] dark:bg-gray-800 p-5 flex items-center justify-between border-b border-gray-100 dark:border-gray-700 shadow-sm relative">
                <!-- Subtle Gradient Accent -->
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-400 to-emerald-600"></div>
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-emerald-50 dark:bg-gray-700 flex items-center justify-center border border-emerald-100 dark:border-gray-600 overflow-hidden shadow-inner">
                        <img src="https://ui-avatars.com/api/?name=Admin+AISH&background=10b981&color=fff" alt="Admin Avatar" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <h4 class="text-gray-800 dark:text-white font-bold text-[15px] tracking-tight">Admin AISH Catering</h4>
                        <div class="flex items-center gap-1.5">
                            <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse shadow-[0_0_8px_rgba(16,185,129,0.6)]"></span>
                            <span class="text-[11px] text-emerald-600 font-bold uppercase tracking-wide">Online</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-1 relative">
                    <button id="chat-options-btn" type="button" class="w-9 h-9 rounded-full hover:bg-gray-100 flex items-center justify-center transition-all text-gray-500 hover:text-gray-900 dark:hover:text-white" title="Opsi Chat">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 5v.01M12 12v.01M12 19v.01" />
                        </svg>
                    </button>
                    <!-- Dropdown -->
                    <div id="chat-options-dropdown" class="hidden absolute right-0 top-full mt-2 w-48 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-100 dark:border-gray-700 z-[100] overflow-hidden animate-fade-in">
                        <button onclick="window.chatWithAdmin()" class="w-full text-left px-4 py-2.5 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            Chat dengan Admin
                        </button>
                        <button onclick="window.chatWithBot()" class="w-full text-left px-4 py-2.5 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 flex items-center gap-2 border-b border-gray-100 dark:border-gray-700">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                            Kembali ke Bot
                        </button>
                        <button onclick="window.clearUserChat()" class="w-full text-left px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            Hapus Semua Chat
                        </button>
                    </div>

                    <button id="close-chat" class="w-9 h-9 rounded-full hover:bg-gray-100 flex items-center justify-center transition-all text-gray-400 hover:text-gray-900 dark:hover:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Messages Area -->
            <div id="chat-messages" class="flex-1 overflow-y-auto p-5 flex flex-col gap-4 custom-scrollbar bg-gray-50/50 dark:bg-gray-900/50 backdrop-blur-sm">
                <!-- Date Separator -->
                <div class="flex justify-center my-4">
                    <span class="bg-[#ffffff] dark:bg-gray-800 px-3 py-1 rounded-full text-[10px] text-gray-400 dark:text-gray-300 font-bold uppercase tracking-wider shadow-sm border border-gray-100 dark:border-gray-700">Hari ini</span>
                </div>
                
                <div class="flex justify-start">
                    <div class="max-w-[85%] bg-[#ffffff] dark:bg-gray-800 p-3 rounded-2xl rounded-tl-none shadow-sm text-gray-800 dark:text-gray-100 text-[14px] leading-relaxed border border-gray-200 dark:border-gray-700">
                        <div class="!text-gray-800 dark:!text-gray-100">Halo! Selamat datang di AISH Catering. Kami menyajikan hidangan lezat &amp; higienis untuk berbagai acara Anda di Singkawang. Ada yang bisa dibantu? 😊</div>
                    </div>
                </div>
            </div>

            <!-- Input Area -->
            <div class="p-4 bg-[#ffffff] dark:bg-gray-800 border-t border-gray-100 dark:border-gray-700 shadow-[0_-10px_20px_rgba(0,0,0,0.02)] relative">
                <!-- Image Preview Area -->
                <div id="chat-image-preview-container" class="hidden absolute bottom-[100%] left-0 w-full p-3 bg-white dark:bg-gray-800 border-t border-gray-100 dark:border-gray-700 z-10 shadow-lg rounded-t-3xl">
                    <div class="relative inline-block">
                        <img id="chat-image-preview" src="" class="h-20 w-auto rounded-lg object-cover border border-gray-200">
                        <button type="button" onclick="window.removeChatImage()" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 shadow-md hover:bg-red-600">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                </div>

                <!-- Emoji Picker Popup -->
                <div id="emoji-picker" class="hidden absolute bottom-[100%] left-4 w-64 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl shadow-2xl p-3 z-[100] animate-fade-in mb-2">
                    <div class="grid grid-cols-6 gap-2 text-xl max-h-48 overflow-y-auto custom-scrollbar">
                        <button type="button" onclick="window.addEmoji('😊')" class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-lg">😊</button>
                        <button type="button" onclick="window.addEmoji('😂')" class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-lg">😂</button>
                        <button type="button" onclick="window.addEmoji('🥰')" class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-lg">🥰</button>
                        <button type="button" onclick="window.addEmoji('😍')" class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-lg">😍</button>
                        <button type="button" onclick="window.addEmoji('👍')" class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-lg">👍</button>
                        <button type="button" onclick="window.addEmoji('🙌')" class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-lg">🙌</button>
                        <button type="button" onclick="window.addEmoji('✨')" class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-lg">✨</button>
                        <button type="button" onclick="window.addEmoji('🔥')" class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-lg">🔥</button>
                        <button type="button" onclick="window.addEmoji('🙏')" class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-lg">🙏</button>
                        <button type="button" onclick="window.addEmoji('🍱')" class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-lg">🍱</button>
                        <button type="button" onclick="window.addEmoji('🍽️')" class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-lg">🍽️</button>
                        <button type="button" onclick="window.addEmoji('🥘')" class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-lg">🥘</button>
                        <button type="button" onclick="window.addEmoji('😋')" class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-lg">😋</button>
                        <button type="button" onclick="window.addEmoji('👋')" class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-lg">👋</button>
                        <button type="button" onclick="window.addEmoji('❤️')" class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-lg">❤️</button>
                        <button type="button" onclick="window.addEmoji('💯')" class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-lg">💯</button>
                        <button type="button" onclick="window.addEmoji('📍')" class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-lg">📍</button>
                        <button type="button" onclick="window.addEmoji('✅')" class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-lg">✅</button>
                    </div>
                </div>

                <!-- Voice Recording Overlay -->
                <div id="voice-overlay" class="hidden absolute inset-0 bg-white dark:bg-gray-800 z-20 flex items-center justify-between px-6 rounded-b-3xl border-t border-gray-100 dark:border-gray-700 animate-fade-in">
                    <div class="flex items-center gap-3">
                        <span class="w-3 h-3 bg-red-500 rounded-full animate-pulse shadow-[0_0_8px_rgba(239,68,68,0.6)]"></span>
                        <span id="voice-timer" class="text-sm font-bold text-gray-700 dark:text-gray-100 tabular-nums">00:00</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="button" id="cancel-voice" class="text-xs font-bold text-red-500 uppercase tracking-widest hover:bg-red-50 dark:hover:bg-red-900/20 px-3 py-1.5 rounded-lg transition-all">Batal</button>
                        <button type="button" id="stop-voice" class="w-10 h-10 bg-emerald-500 text-white rounded-full flex items-center justify-center shadow-lg animate-bounce">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        </button>
                    </div>
                </div>

                <div class="relative flex items-center gap-2">
                    <label for="chat-image-input" class="w-[42px] h-[42px] bg-gray-50 hover:bg-gray-100 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-500 rounded-2xl flex items-center justify-center cursor-pointer transition-all border border-gray-200 dark:border-gray-600 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    </label>
                    <input type="file" id="chat-image-input" class="hidden" accept="image/*" onchange="window.previewChatImage(this)">

                    <div class="flex-1 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-600 rounded-2xl px-3 py-2 focus-within:border-emerald-500 focus-within:ring-2 focus-within:ring-emerald-500/20 transition-all flex items-center gap-1.5">
                        <button type="button" id="emoji-btn" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-orange-400 transition-colors shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </button>
                        <input type="text" id="chat-input" placeholder="Ketik pesan..."
                               class="w-full bg-transparent border-none text-[14px] text-gray-700 dark:text-gray-100 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-0 outline-none p-0">
                    </div>
                    
                    <button id="voice-btn" type="button"
                            class="w-[42px] h-[42px] bg-gray-50 hover:bg-gray-100 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-500 rounded-2xl flex items-center justify-center transition-all border border-gray-200 dark:border-gray-600 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" /></svg>
                    </button>

                    <button id="send-chat" onclick="window.handleChat()" 
                            class="w-[42px] h-[42px] bg-emerald-500 hover:bg-emerald-600 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-emerald-500/30 transition-all hover:scale-105 active:scale-95 shrink-0">
                        <svg class="w-4 h-4 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Brand Toggle Button -->
        <button id="chatbot-toggle" 
                class="w-14 h-14 sm:w-16 sm:h-16 bg-emerald-500 hover:bg-emerald-600 text-white rounded-2xl flex items-center justify-center shadow-[0_10px_30px_rgba(16,185,129,0.4)] transition-all hover:scale-110 active:scale-95 group relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-tr from-emerald-600/0 to-white/20 group-hover:translate-y-full transition-transform duration-500"></div>
            <svg class="w-7 h-7 sm:w-8 sm:h-8 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
            </svg>
            <div class="absolute -top-1.5 -right-1.5 min-w-[20px] h-5 px-1.5 bg-rose-500 text-white font-black text-[10px] rounded-full border-2 border-white dark:border-slate-900 shadow-lg flex items-center justify-center hidden z-20 transition-all duration-300 select-none" id="chat-notification">
                <span id="chat-notification-count">0</span>
            </div>
        </button>
    </div>

    <style>
        #global-chatbot-container {
            transition: bottom 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        #chat-box.scale-100 { transform: scale(1); opacity: 1; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #10b981; }
        @keyframes fade-in { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in { animation: fade-in 0.25s ease-out forwards; }
    </style>

    <script>
        (function() {
            // Stable Live Chat Logic (Polling based)
            const chatToggle = document.getElementById('chatbot-toggle');
            const chatBox = document.getElementById('chat-box');
            const closeChat = document.getElementById('close-chat');
            const chatInput = document.getElementById('chat-input');
            const chatMessages = document.getElementById('chat-messages');
            const chatNotification = document.getElementById('chat-notification');
            
            let notificationSound = new Audio('/mixkit-bell-notification-933.wav');
            let originalTitle = document.title;
            let titleInterval = null;
            let lastMessageCount = 0;
            let isChatOpen = false;

            // Audio Unlocker
            document.addEventListener('click', function unlock() {
                notificationSound.play().then(() => {
                    notificationSound.pause();
                    notificationSound.currentTime = 0;
                    document.removeEventListener('click', unlock);
                }).catch(() => {});
            }, { once: true });

            function flashTitle() {
                if (titleInterval) return;
                titleInterval = setInterval(() => {
                    document.title = document.title === originalTitle ? '🔔 (1) Pesan Baru!' : originalTitle;
                }, 1000);
            }

            function stopFlashTitle() {
                clearInterval(titleInterval);
                titleInterval = null;
                document.title = originalTitle;
            }

            async function fetchMessages(silent = false) {
                try {
                    const shouldMarkRead = isChatOpen;
                    const response = await fetch(`/chat-live/messages?mark_as_read=${shouldMarkRead}`);
                    if (!response.ok) return;
                    const messages = await response.json();
                    
                    if (messages.length < lastMessageCount) {
                        chatMessages.innerHTML = '';
                        lastMessageCount = 0;
                    }
                    
                    if (messages.length > lastMessageCount) {
                        const newMessages = messages.slice(lastMessageCount);
                        let hasNewAdminMessage = false;

                        newMessages.forEach(msg => {
                            const time = new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                            const isAdmin = msg.sender_type === 'ADMIN';
                            
                            if (isAdmin) {
                                appendBotMessage(msg.message, time);
                                hasNewAdminMessage = true;
                            } else {
                                appendUserMessage(msg.message, time, msg.id);
                            }
                        });

                        if (hasNewAdminMessage && silent) {
                            notificationSound.play().catch(() => {});
                            if (!isChatOpen) {
                                flashTitle();
                            }
                        }
                        
                        lastMessageCount = messages.length;
                    }

                    // Dynamically update unread count if the chat box is currently closed
                    if (!isChatOpen) {
                        const unreadMessages = messages.filter(msg => msg.sender_type === 'ADMIN' && (msg.is_read == 0 || msg.is_read === false));
                        const unreadCount = unreadMessages.length;
                        
                        const countEl = document.getElementById('chat-notification-count');
                        if (unreadCount > 0) {
                            if (countEl) countEl.innerText = unreadCount;
                            chatNotification?.classList.remove('hidden');
                        } else {
                            chatNotification?.classList.add('hidden');
                        }
                    } else {
                        chatNotification?.classList.add('hidden');
                    }
                } catch (err) {
                    console.error("Fetch Error:", err);
                }
            }

            // Initial load and Echo registration
            window.addEventListener('DOMContentLoaded', () => {
                fetchMessages(true);
                
                if (window.Echo) {
                    const userEmail = "{{ auth()->check() ? auth()->user()->email : 'guest_' . session()->getId() }}";
                    window.Echo.channel('chat.' + userEmail)
                        .listen('.ChatMessageSent', (e) => {
                            fetchMessages(true);
                        })
                        .listen('.ChatMessageDeleted', (e) => {
                            fetchMessages(true);
                        })
                        .listen('.ChatMessageUpdated', (e) => {
                            fetchMessages(true);
                        });
                }
            });

            // Fallback background polling (every 30 seconds instead of 2 seconds)
            setInterval(() => fetchMessages(true), 30000);


            function parseMessageContent(text) {
                if (!text) return '';
                // Escape HTML tags slightly but handle our image wrapper
                let sanitized = text.replace(/</g, "&lt;").replace(/>/g, "&gt;");
                sanitized = sanitized.replace(/\[IMAGE: (.*?)\]/g, '<img src="$1" class="max-w-full rounded-xl mt-2 mb-1 shadow-sm border border-black/5" />');
                sanitized = sanitized.replace(/\[VOICE: (.*?)\]/g, `
                    <div class="audio-player mt-2 p-3 bg-black/5 dark:bg-white/5 rounded-xl border border-black/5">
                        <audio controls class="w-full h-8 custom-audio">
                            <source src="$1" type="audio/mpeg">
                            Your browser does not support the audio element.
                        </audio>
                    </div>`);
                return sanitized;
            }

            function appendBotMessage(text, time = null) {
                const now = time || new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                const msgEl = document.createElement('div');
                msgEl.className = 'flex justify-start animate-fade-in mb-1';
                msgEl.innerHTML = `
                    <div class="max-w-[85%] bg-[#ffffff] dark:bg-gray-800 p-3 rounded-2xl rounded-tl-none shadow-sm text-gray-800 dark:text-gray-100 text-[14px] leading-relaxed border border-gray-200 dark:border-gray-700">
                        <div class="!text-gray-800 dark:!text-gray-100 whitespace-pre-wrap">${parseMessageContent(text)}</div>
                        <span class="text-[9px] text-gray-400 dark:text-gray-400 font-medium block mt-1">${now}</span>
                    </div>`;
                chatMessages.appendChild(msgEl);
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }

            function appendUserMessage(text, time = null, id = null) {
                const now = time || new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                const msgEl = document.createElement('div');
                const msgIdAttr = id ? `id="user-msg-${id}"` : '';
                const deleteBtnHtml = id ? `
                    <button onclick="window.deleteUserMessage(${id})" class="text-white/60 hover:text-white transition-colors" title="Hapus pesan">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </button>
                ` : '';

                msgEl.className = 'flex justify-end animate-fade-in mb-1 group';
                msgEl.innerHTML = `
                    <div ${msgIdAttr} class="max-w-[85%] bg-emerald-500 p-3 rounded-2xl rounded-tr-none shadow-md text-white text-[14px] leading-relaxed">
                        <div class="!text-white whitespace-pre-wrap">${parseMessageContent(text)}</div>
                        <div class="flex items-center justify-end gap-2 mt-1">
                            ${deleteBtnHtml}
                            <span class="text-[9px] text-emerald-100 font-medium">${now}</span>
                            <svg class="w-3 h-3 text-emerald-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </div>`;
                chatMessages.appendChild(msgEl);
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }

            window.previewChatImage = function(input) {
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('chat-image-preview').src = e.target.result;
                        document.getElementById('chat-image-preview-container').classList.remove('hidden');
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            };

            window.removeChatImage = function() {
                document.getElementById('chat-image-input').value = '';
                document.getElementById('chat-image-preview-container').classList.add('hidden');
            };

            window.deleteUserMessage = async function(id) {
                if (!confirm("Hapus pesan ini?")) return;
                try {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
                    const res = await fetch('/chat-live/message/' + id, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': csrfToken }
                    });
                    if (res.ok) {
                        const el = document.getElementById('user-msg-' + id);
                        if (el) el.closest('.flex.justify-end').remove();
                    }
                } catch (e) { console.error(e); }
            };

            window.clearUserChat = async function() {
                if (!confirm("Anda yakin ingin menghapus seluruh riwayat chat?")) return;
                try {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
                    await fetch('/chat-live/clear', { method: 'POST', headers: { 'X-CSRF-TOKEN': csrfToken } });
                    document.getElementById('chat-messages').innerHTML = '';
                    document.getElementById('chat-options-dropdown').classList.add('hidden');
                } catch (e) { console.error(e); }
            };

            window.chatWithAdmin = async function() {
                chatInput.value = "/admin";
                window.handleChat();
                document.getElementById('chat-options-dropdown').classList.add('hidden');
            };

            window.chatWithBot = async function() {
                chatInput.value = "/bot";
                window.handleChat();
                document.getElementById('chat-options-dropdown').classList.add('hidden');
            };

            // Options menu toggle
            document.getElementById('chat-options-btn')?.addEventListener('click', (e) => {
                e.stopPropagation();
                document.getElementById('chat-options-dropdown').classList.toggle('hidden');
                document.getElementById('emoji-picker').classList.add('hidden');
            });

            // Emoji Picker Toggle
            document.getElementById('emoji-btn')?.addEventListener('click', (e) => {
                e.stopPropagation();
                document.getElementById('emoji-picker').classList.toggle('hidden');
                document.getElementById('chat-options-dropdown').classList.add('hidden');
            });

            window.addEmoji = function(emoji) {
                chatInput.value += emoji;
                chatInput.focus();
            };

            // Voice Recording Logic
            let mediaRecorder;
            let audioChunks = [];
            let voiceTimer;
            let voiceSeconds = 0;

            const voiceBtn = document.getElementById('voice-btn');
            const voiceOverlay = document.getElementById('voice-overlay');
            const cancelVoice = document.getElementById('cancel-voice');
            const stopVoice = document.getElementById('stop-voice');
            const timerEl = document.getElementById('voice-timer');

            voiceBtn?.addEventListener('click', async () => {
                try {
                    const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                    mediaRecorder = new MediaRecorder(stream);
                    audioChunks = [];

                    mediaRecorder.ondataavailable = (event) => {
                        audioChunks.push(event.data);
                    };

                    mediaRecorder.onstop = async () => {
                        const audioBlob = new Blob(audioChunks, { type: 'audio/mpeg' });
                        if (audioChunks.length > 0 && !window.voiceCancelled) {
                            await window.sendVoiceMessage(audioBlob);
                        }
                        stream.getTracks().forEach(track => track.stop());
                    };

                    mediaRecorder.start();
                    window.voiceCancelled = false;
                    voiceOverlay.classList.remove('hidden');
                    voiceSeconds = 0;
                    timerEl.innerText = "00:00";
                    voiceTimer = setInterval(() => {
                        voiceSeconds++;
                        const min = Math.floor(voiceSeconds / 60).toString().padStart(2, '0');
                        const sec = (voiceSeconds % 60).toString().padStart(2, '0');
                        timerEl.innerText = `${min}:${sec}`;
                    }, 1000);

                } catch (err) {
                    alert("Gagal mengakses mikrofon. Pastikan Anda memberikan izin.");
                }
            });

            cancelVoice?.addEventListener('click', () => {
                window.voiceCancelled = true;
                if (mediaRecorder) mediaRecorder.stop();
                clearInterval(voiceTimer);
                voiceOverlay.classList.add('hidden');
            });

            stopVoice?.addEventListener('click', () => {
                if (mediaRecorder) mediaRecorder.stop();
                clearInterval(voiceTimer);
                voiceOverlay.classList.add('hidden');
            });

            window.sendVoiceMessage = async function(blob) {
                const formData = new FormData();
                formData.append('voice', blob, 'voice.mp3');
                
                try {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
                    const response = await fetch('/chat-live/send', {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': csrfToken },
                        body: formData
                    });
                    if (response.ok) fetchMessages(true);
                } catch (err) { console.error("Voice Send Error:", err); }
            };

            document.addEventListener('click', (e) => {
                const drop = document.getElementById('chat-options-dropdown');
                const emoji = document.getElementById('emoji-picker');
                if (drop && !drop.contains(e.target)) drop.classList.add('hidden');
                if (emoji && !emoji.contains(e.target)) emoji.classList.add('hidden');
            });

            window.handleChat = async function() {
                const text = chatInput.value.trim();
                const imageInput = document.getElementById('chat-image-input');
                const hasImage = imageInput.files && imageInput.files.length > 0;

                if (!text && !hasImage) return;

                chatInput.value = '';
                
                // Construct form data
                const formData = new FormData();
                if (text) formData.append('message', text);
                if (hasImage) {
                    formData.append('image', imageInput.files[0]);
                    window.removeChatImage(); // clear preview
                }

                // Optimistic UI for text only (hard to do optimistic for image without complex blob URL mgmt)
                if (text && !hasImage) {
                    appendUserMessage(text);
                    lastMessageCount++;
                }

                try {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
                    const response = await fetch('/chat-live/send', {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': csrfToken },
                        body: formData // No Content-Type header so browser sets multipart/form-data
                    });
                    
                    if (response.ok) {
                        fetchMessages(true);
                    }

                } catch (err) {
                    console.error("Send Error:", err);
                }
            };

            chatToggle.addEventListener('click', () => {
                isChatOpen = !isChatOpen;
                if (isChatOpen) {
                    chatBox.classList.add('scale-100');
                    chatNotification?.classList.add('hidden');
                    stopFlashTitle();
                    fetchMessages(); // Refresh messages when opening
                } else {
                    chatBox.classList.remove('scale-100');
                }
            });

            closeChat.addEventListener('click', () => {
                isChatOpen = false;
                chatBox.classList.remove('scale-100');
            });

            chatInput.addEventListener('keypress', (e) => { if (e.key === 'Enter') window.handleChat(); });


            // Keyboard Adaptability (Mobile UX)
            if (window.visualViewport) {
                const container = document.getElementById('global-chatbot-container');
                const updatePosition = () => {
                    const viewport = window.visualViewport;
                    const keyboardHeight = window.innerHeight - viewport.height;
                    
                    if (keyboardHeight > 100) { // Keyboard is likely visible
                        // Move up precisely above keyboard with extra padding
                        container.style.bottom = `${keyboardHeight + 20}px`;
                    } else {
                        // Return to default position (32px = bottom-8)
                        container.style.bottom = '32px';
                    }
                };

                window.visualViewport.addEventListener('resize', updatePosition);
                window.visualViewport.addEventListener('scroll', updatePosition);
            }
        })();
    </script>
    <script>
        // Preloader Handler
        const preloaderEl = document.getElementById('preloader');
        if (preloaderEl) {
            const hidePreloader = () => {
                if (preloaderEl.classList.contains('preloader-hidden')) return;
                preloaderEl.classList.add('preloader-hidden');
                
                setTimeout(() => {
                    preloaderEl.style.opacity = '0';
                    preloaderEl.style.visibility = 'hidden';
                    // Enable scroll and force scroll to top
                    document.body.classList.remove('loading');
                    if (window.location.hash) {
                        history.replaceState("", document.title, window.location.pathname + window.location.search);
                    }
                    window.scrollTo(0, 0);
                    document.documentElement.scrollTop = 0;
                    document.body.scrollTop = 0;

                    const mainContent = document.getElementById('main-content');
                    if (mainContent) mainContent.classList.add('visible');

                    // Force scroll to top on first load
                    window.scrollTo(0, 0);

                    // Trigger scroll reveal for hero content if on home
                    document.querySelectorAll('#home .reveal-text').forEach(el => {
                        el.classList.add('active');
                    });

                    setTimeout(() => {
                        preloaderEl.remove();
                    }, 1000);
                }, 500); 
            };

            window.addEventListener('load', hidePreloader);
            // Fallback safety (3 seconds max for faster experience)
            setTimeout(hidePreloader, 3000);
        }
    </script>
</body>

</html>