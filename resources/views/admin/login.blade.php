<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Aish Catering</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #0f172a; /* Slate 900 */
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            overflow: hidden;
            position: relative;
            color: #f8fafc;
        }

        /* Ambient Glow Background */
        .ambient-glow {
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(76, 175, 80, 0.15) 0%, rgba(76, 175, 80, 0) 70%);
            border-radius: 50%;
            z-index: -1;
            filter: blur(60px);
        }

        .glow-1 { top: -10%; right: -10%; }
        .glow-2 { bottom: -10%; left: -10%; }

        .bg-overlay {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background-image: url('https://images.unsplash.com/photo-1555507036-ab1f4038808a?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            opacity: 0.05;
            filter: grayscale(100%) brightness(0.5);
            z-index: -1;
        }

        .login-card {
            background: rgba(30, 41, 59, 0.7); /* Slate 800 with alpha */
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            width: 100%;
            max-width: 440px;
            padding: 3.5rem;
            border-radius: 2.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.05);
            animation: slideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            z-index: 10;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .input-group {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .input-icon {
            position: absolute;
            left: 1.25rem;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            transition: all 0.3s;
        }

        .input-field {
            width: 100%;
            padding: 1.1rem 1rem 1.1rem 3.5rem;
            background: rgba(15, 23, 42, 0.6);
            border: 2px solid rgba(255, 255, 255, 0.03);
            border-radius: 1.25rem;
            font-size: 0.95rem;
            font-family: 'Inter', sans-serif;
            font-weight: 500;
            color: #f1f5f9;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            outline: none;
        }

        .input-field::placeholder {
            color: #475569;
        }

        .input-field:focus {
            background: rgba(15, 23, 42, 0.8);
            border-color: #4CAF50;
            box-shadow: 0 0 0 4px rgba(76, 175, 80, 0.1);
        }

        .input-field:focus + .input-icon {
            color: #4CAF50;
            transform: translateY(-50%) scale(1.1);
        }

        .btn-green {
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            color: white;
            width: 100%;
            padding: 1.1rem;
            border-radius: 1.25rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: none;
            cursor: pointer;
            box-shadow: 0 10px 25px -5px rgba(76, 175, 80, 0.4);
            margin-top: 0.5rem;
        }

        .btn-green:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 20px 35px -10px rgba(76, 175, 80, 0.5);
            filter: brightness(1.1);
        }

        .btn-green:active {
            transform: translateY(0) scale(0.98);
        }

        .link-text {
            color: #94a3b8;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.3s;
            text-decoration: none;
        }

        .link-text:hover {
            color: #4CAF50;
            text-shadow: 0 0 8px rgba(76, 175, 80, 0.3);
        }

        .accent-text {
            color: #4CAF50;
            font-weight: 700;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            padding: 1rem;
            border-radius: 1.25rem;
            margin-bottom: 1.5rem;
            color: #fca5a5;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .leaf-decor {
            position: absolute;
            z-index: 1;
            opacity: 0.15;
            pointer-events: none;
            filter: drop-shadow(0 0 10px rgba(76, 175, 80, 0.2));
        }

        .checkbox-container {
            display: flex;
            align-items: center;
            user-select: none;
        }

        .checkbox-container input {
            appearance: none;
            width: 1.15rem;
            height: 1.15rem;
            border: 2px solid #334155;
            border-radius: 0.4rem;
            background: rgba(15, 23, 42, 0.5);
            cursor: pointer;
            position: relative;
            transition: all 0.2s;
        }

        .checkbox-container input:checked {
            background: #4CAF50;
            border-color: #4CAF50;
        }

        .checkbox-container input:checked::after {
            content: '✓';
            position: absolute;
            color: white;
            font-size: 0.8rem;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
    </style>
</head>
<body>

    <div class="ambient-glow glow-1"></div>
    <div class="ambient-glow glow-2"></div>
    <div class="bg-overlay"></div>

    <!-- Decorative Elements -->
    <div class="leaf-decor" style="top: 15%; right: 10%; font-size: 4rem; transform: rotate(45deg);">🌿</div>
    <div class="leaf-decor" style="bottom: 15%; left: 10%; font-size: 4rem; transform: rotate(-12deg);">🍃</div>

    <div class="login-card">
        <!-- Branding -->
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-green-500/10 rounded-2xl mb-6 shadow-inner border border-green-500/20">
                <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 22V12h6v10"/></svg>
            </div>
            <h1 class="text-2xl font-extrabold text-white tracking-[0.1em] uppercase">AISH CATERING</h1>
            <div class="h-1 w-12 bg-green-500 mx-auto mt-2 rounded-full shadow-[0_0_10px_rgba(76,175,80,0.5)]"></div>
            <p class="text-slate-400 text-sm mt-4 font-medium">Panel Administrasi Sistem</p>
        </div>

        @if ($errors->any())
            <div class="alert-error">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-xs font-semibold leading-tight">{{ $errors->first() }}</p>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            
            <div class="input-group">
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="input-field" placeholder="Alamat Email Admin">
                <span class="input-icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </span>
            </div>

            <div class="input-group">
                <input type="password" name="password" required
                    class="input-field" placeholder="Kata Sandi">
                <span class="input-icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </span>
            </div>

            <div class="flex items-center justify-between mb-8 px-1">
                <label class="checkbox-container">
                    <input type="checkbox" name="remember">
                    <span class="ml-2 text-xs font-semibold text-slate-400 cursor-pointer">Ingat Sesi</span>
                </label>
                <a href="{{ route('password.request') }}" class="link-text text-xs">Lupa password?</a>
            </div>

            <button type="submit" class="btn-green">
                Autentikasi
            </button>
        </form>

        <div class="mt-10 text-center">
            <p class="text-xs font-semibold text-slate-500">
                Belum terdaftar sebagai admin? <a href="{{ route('register') }}" class="accent-text hover:underline transition-all">Hubungi Sistem</a>
            </p>
        </div>
    </div>

</body>
</html>

</body>
</html>
