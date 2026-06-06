@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="max-w-7xl mx-auto space-y-10 animate-in p-4 sm:p-0">

    {{-- HERO --}}
    <div class="relative group">
        <div class="absolute inset-0 bg-gradient-to-r from-emerald-600 to-emerald-400 rounded-[3rem] blur-2xl opacity-20 group-hover:opacity-30 transition duration-1000"></div>
        <div class="relative bg-[var(--bg-card)]/60 backdrop-blur-xl rounded-3xl sm:rounded-[2.5rem] border border-[var(--border)] p-5 sm:p-8 lg:p-10 overflow-hidden shadow-[var(--card-shadow)]">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8 relative z-10">
                <div class="space-y-4">
                    <div class="inline-flex items-center gap-3 px-4 py-2 bg-emerald-500/10 border border-emerald-500/20 rounded-full">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        <span class="text-[10px] font-black text-emerald-500 uppercase tracking-[0.2em]">Live · Terintegrasi Web & Android</span>
                    </div>
                    <h1 id="aish-greeting" class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-[var(--text-main)] leading-tight">
                        Selamat Datang, <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-emerald-400 dark:from-emerald-400 dark:to-emerald-200">Admin Aish</span>
                    </h1>
                    <p class="text-[var(--text-muted)] text-base max-w-xl font-medium leading-relaxed">
                        Data real-time dari database yang sama antara website dan aplikasi Android Aish Catering.
                    </p>
                    <div class="flex flex-wrap gap-4 pt-2">
                        <a href="{{ route('admin.menus.index') }}" class="px-6 py-3 bg-emerald-500 hover:bg-emerald-600 text-white rounded-2xl font-bold transition shadow-[0_15px_30px_-5px_rgba(16,185,129,0.3)] hover:scale-105 active:scale-95 duration-300">
                            🍽️ Kelola Menu
                        </a>
                        <a href="{{ route('admin.chats.index') }}" class="relative px-6 py-3 bg-slate-100 dark:bg-[var(--bg-card)]/10 hover:bg-slate-200 dark:hover:bg-[var(--bg-card)]/20 text-[var(--text-main)] border border-[var(--border)] rounded-2xl font-bold transition backdrop-blur-md">
                            💬 Live Chat
                            @if($stats['unread_chats'] > 0)
                            <span class="absolute -top-2 -right-2 w-5 h-5 bg-red-500 rounded-full text-[10px] font-black text-white flex items-center justify-center">{{ $stats['unread_chats'] }}</span>
                            @endif
                        </a>
                    </div>
                </div>
                <div class="hidden xl:flex flex-col items-center gap-2">
                    <div class="text-6xl animate-bounce">🍱</div>
                    <span class="text-[10px] text-emerald-500 font-black uppercase tracking-widest">Web + App Sync</span>
                </div>
            </div>
            <div class="absolute -bottom-10 -right-10 w-64 h-64 bg-emerald-500 opacity-10 blur-[100px] rounded-full"></div>
        </div>
    </div>

    {{-- STAT CARDS — sama dengan Android App --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
        {{-- Pesanan Aktif --}}
        <div class="group bg-[var(--bg-card)] border border-[var(--border)] p-4 sm:p-6 rounded-2xl sm:rounded-[2rem] hover:border-orange-500/30 transition-all duration-500 shadow-[var(--card-shadow)]">
            <div class="flex items-start justify-between mb-6">
                <div class="w-12 h-12 bg-orange-500/10 rounded-2xl flex items-center justify-center text-2xl group-hover:scale-110 transition">⏳</div>
                <span class="text-orange-400 bg-orange-500/10 px-2 py-1 rounded-full text-[9px] font-black uppercase tracking-widest">Aktif</span>
            </div>
            <p class="text-[var(--text-muted)] text-[10px] font-black uppercase tracking-widest mb-1">Pesanan Aktif</p>
            <h3 class="text-4xl font-black text-[var(--text-main)]">{{ $stats['active_orders'] }}</h3>
            <p class="text-[var(--text-muted)]/60 text-[10px] mt-2">Total: {{ $stats['total_orders'] }} pesanan</p>
        </div>

        {{-- Total Menu --}}
        <div class="group bg-[var(--bg-card)] border border-[var(--border)] p-4 sm:p-6 rounded-2xl sm:rounded-[2rem] hover:border-emerald-500/30 transition-all duration-500 shadow-[var(--card-shadow)]">
            <div class="flex items-start justify-between mb-6">
                <div class="w-12 h-12 bg-emerald-500/10 rounded-2xl flex items-center justify-center text-2xl group-hover:scale-110 transition">🍲</div>
                <span class="text-emerald-400 bg-emerald-500/10 px-2 py-1 rounded-full text-[9px] font-black uppercase tracking-widest">Katalog</span>
            </div>
            <p class="text-[var(--text-muted)] text-[10px] font-black uppercase tracking-widest mb-1">Total Menu</p>
            <h3 class="text-4xl font-black text-[var(--text-main)]">{{ $stats['total_menu'] }}</h3>
            <p class="text-[var(--text-muted)]/60 text-[10px] mt-2">{{ $stats['total_categories'] }} kategori</p>
        </div>

        {{-- Total Pelanggan --}}
        <div class="group bg-[var(--bg-card)] border border-[var(--border)] p-4 sm:p-6 rounded-2xl sm:rounded-[2rem] hover:border-blue-500/30 transition-all duration-500 shadow-[var(--card-shadow)]">
            <div class="flex items-start justify-between mb-6">
                <div class="w-12 h-12 bg-blue-500/10 rounded-2xl flex items-center justify-center text-2xl group-hover:scale-110 transition">👥</div>
                <span class="text-blue-400 bg-blue-500/10 px-2 py-1 rounded-full text-[9px] font-black uppercase tracking-widest">Users</span>
            </div>
            <p class="text-[var(--text-muted)] text-[10px] font-black uppercase tracking-widest mb-1">Pelanggan</p>
            <h3 class="text-4xl font-black text-[var(--text-main)]">{{ $stats['total_customers'] }}</h3>
            <p class="text-[var(--text-muted)]/60 text-[10px] mt-2">Pengguna terdaftar</p>
        </div>

        {{-- Total Revenue --}}
        <div class="group bg-[var(--bg-card)] border border-[var(--border)] p-4 sm:p-6 rounded-2xl sm:rounded-[2rem] hover:border-purple-500/30 transition-all duration-500 shadow-[var(--card-shadow)]">
            <div class="flex items-start justify-between mb-6">
                <div class="w-12 h-12 bg-purple-500/10 rounded-2xl flex items-center justify-center text-2xl group-hover:scale-110 transition">💰</div>
                <span class="text-purple-400 bg-purple-500/10 px-2 py-1 rounded-full text-[9px] font-black uppercase tracking-widest">Revenue</span>
            </div>
            <p class="text-[var(--text-muted)] text-[10px] font-black uppercase tracking-widest mb-1">Total Pendapatan</p>
            <h3 class="text-2xl font-black text-[var(--text-main)]">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</h3>
            <p class="text-[var(--text-muted)]/60 text-[10px] mt-2">{{ $stats['selesai_orders'] }} pesanan selesai</p>
        </div>
    </div>

    {{-- STATUS PILLS — sama dengan Android App --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-yellow-500/10 border border-yellow-500/20 rounded-2xl p-4 text-center">
            <p class="text-2xl font-black text-yellow-600 dark:text-yellow-400">{{ $order_stats['pending'] }}</p>
            <p class="text-[10px] font-black text-yellow-600/70 dark:text-yellow-500/70 uppercase tracking-widest mt-1">⏰ Pending</p>
        </div>
        <div class="bg-blue-500/10 border border-blue-500/20 rounded-2xl p-4 text-center">
            <p class="text-2xl font-black text-blue-600 dark:text-blue-400">{{ $order_stats['diproses'] }}</p>
            <p class="text-[10px] font-black text-blue-600/70 dark:text-blue-500/70 uppercase tracking-widest mt-1">⏳ Diproses</p>
        </div>
        <div class="bg-orange-500/10 border border-orange-500/20 rounded-2xl p-4 text-center">
            <p class="text-2xl font-black text-orange-600 dark:text-orange-400">{{ $order_stats['dikirim'] }}</p>
            <p class="text-[10px] font-black text-orange-600/70 dark:text-orange-500/70 uppercase tracking-widest mt-1">🚗 Dikirim</p>
        </div>
        <div class="bg-emerald-500/10 border border-emerald-500/20 rounded-2xl p-4 text-center">
            <p class="text-2xl font-black text-emerald-600 dark:text-emerald-400">{{ $order_stats['selesai'] }}</p>
            <p class="text-[10px] font-black text-emerald-600/70 dark:text-emerald-500/70 uppercase tracking-widest mt-1">✅ Selesai</p>
        </div>
    </div>

    {{-- CONTENT GRID --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">

        {{-- KIRI: Top Menu berdasarkan terjual --}}
        <div class="xl:col-span-2 space-y-5">
            <div class="flex items-center justify-between px-1">
                <h3 class="text-xl font-bold text-[var(--text-main)] flex items-center gap-3">
                    <span class="w-1.5 h-7 bg-emerald-500 rounded-full"></span>
                    🏆 Top Menu (Terlaris)
                </h3>
                <a href="{{ route('admin.menus.index') }}" class="text-xs font-bold text-emerald-500 uppercase tracking-widest hover:underline">Lihat Semua</a>
            </div>

            <div class="space-y-3">
                @php $medals = ['🥇','🥈','🥉','4️⃣','5️⃣']; @endphp
                @forelse($top_menus as $i => $menu)
                <div class="group bg-[var(--bg-card)] border border-[var(--border)] hover:border-emerald-500/20 p-4 rounded-2xl flex items-center justify-between transition duration-300 shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-[var(--bg-main)] rounded-2xl flex items-center justify-center text-2xl group-hover:scale-110 transition">
                            {{ $menu->emoji ?: ($medals[$i] ?? '🍱') }}
                        </div>
                        <div>
                            <h4 class="text-[var(--text-main)] font-bold text-sm leading-none mb-1">{{ $menu->name }}</h4>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-[9px] font-black px-2 py-0.5 bg-emerald-500/10 text-emerald-500 border border-emerald-500/20 rounded uppercase">{{ $menu->category }}</span>
                                <span class="text-[10px] text-yellow-400">⭐ {{ number_format($menu->rating, 1) }}</span>
                                <span class="text-[10px] text-[var(--text-muted)]">🛒 {{ number_format($menu->sold) }} terjual</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-base font-black text-[var(--text-main)]">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
                        <a href="{{ route('admin.menus.edit', $menu) }}" class="text-[10px] text-emerald-500 hover:underline">Edit</a>
                    </div>
                </div>
                @empty
                <div class="bg-[var(--bg-main)] border border-[var(--border)] p-8 rounded-2xl text-center text-[var(--text-muted)] text-sm">
                    Belum ada data menu.
                    <a href="{{ route('admin.menus.create') }}" class="text-emerald-500 font-bold ml-1">Tambah Menu</a>
                </div>
                @endforelse
            </div>

            {{-- Pesanan Terbaru dari Android App --}}
            <div class="flex items-center justify-between px-1 pt-4">
                <h3 class="text-xl font-bold text-[var(--text-main)] flex items-center gap-3">
                    <span class="w-1.5 h-7 bg-blue-500 rounded-full"></span>
                    📋 Pesanan Terbaru (Android App)
                </h3>
            </div>
            <div id="latest-orders-container" class="space-y-3">
                @forelse($latest_orders as $order)
                <div class="bg-[var(--bg-card)] border border-[var(--border)] hover:border-blue-500/20 p-4 rounded-2xl flex items-center justify-between transition duration-300 shadow-sm">
                    <div>
                        <p class="text-[var(--text-main)] font-bold text-sm">{{ $order->items_title }}</p>
                        <p class="text-[var(--text-muted)] text-[10px] mt-0.5">{{ $order->user_email }} · {{ \Carbon\Carbon::parse($order->created_at)->diffForHumans() }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[var(--text-main)] font-black text-sm">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                        <span class="inline-block text-[9px] font-black px-2 py-0.5 rounded uppercase mt-1
                            {{ in_array($order->status, ['Selesai','COMPLETED']) ? 'bg-emerald-500/20 text-emerald-400' :
                               ($order->status == 'Dikirim' ? 'bg-orange-500/20 text-orange-400' :
                               ($order->status == 'Diproses' ? 'bg-blue-500/20 text-blue-400' : 'bg-yellow-500/20 text-yellow-400')) }}">
                            {{ $order->status }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="bg-[var(--bg-main)] border border-[var(--border)] p-6 rounded-2xl text-center text-[var(--text-muted)] text-sm">
                    Belum ada pesanan dari aplikasi Android.
                </div>
                @endforelse
            </div>
        </div>

        {{-- KANAN: Quick Actions + Chat --}}
        <div class="space-y-5">
            {{-- Database Sync Status --}}
            <div class="bg-[var(--bg-card)] border border-emerald-500/20 p-6 rounded-[2rem] space-y-4 shadow-[var(--card-shadow)]">
                <h4 class="text-sm font-black text-[var(--text-main)] uppercase tracking-widest">🔗 Status Sinkronisasi</h4>
                <div class="space-y-3">
                    <div>
                        <div class="flex justify-between text-xs mb-1">
                            <span class="text-[var(--text-muted)] font-bold">Database (Web ↔ Android)</span>
                            <span class="text-emerald-400 font-bold">✅ Terhubung</span>
                        </div>
                        <div class="h-2 bg-slate-100 dark:bg-white/5 rounded-full"><div class="h-full bg-emerald-500 rounded-full shadow-[0_0_10px_rgba(16,185,129,0.5)]"></div></div>
                    </div>
                    <div>
                        <div class="flex justify-between text-xs mb-1">
                            <span class="text-[var(--text-muted)] font-bold">Tabel Menus</span>
                            <span id="sync-menu-count" class="text-emerald-400 font-bold">{{ $stats['total_menu'] }} item</span>
                        </div>
                        <div class="h-2 bg-slate-100 dark:bg-white/5 rounded-full"><div id="sync-menu-bar" class="h-full bg-blue-500 rounded-full" style="width:{{ min(100, $stats['total_menu'] * 10) }}%"></div></div>
                    </div>
                    <div>
                        <div class="flex justify-between text-xs mb-1">
                            <span class="text-[var(--text-muted)] font-bold">Tabel Orders</span>
                            <span id="sync-order-count" class="text-orange-400 font-bold">{{ $stats['total_orders'] }} pesanan</span>
                        </div>
                        <div class="h-2 bg-slate-100 dark:bg-white/5 rounded-full"><div id="sync-order-bar" class="h-full bg-orange-500 rounded-full" style="width:{{ min(100, $stats['total_orders'] * 5) }}%"></div></div>
                    </div>
                    <div>
                        <div class="flex justify-between text-xs mb-1">
                            <span class="text-[var(--text-muted)] font-bold">Tabel Users</span>
                            <span id="sync-user-count" class="text-purple-400 font-bold">{{ $stats['total_customers'] }} pelanggan</span>
                        </div>
                        <div class="h-2 bg-slate-100 dark:bg-white/5 rounded-full"><div id="sync-user-bar" class="h-full bg-purple-500 rounded-full" style="width:{{ min(100, $stats['total_customers'] * 10) }}%"></div></div>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="bg-[var(--bg-card)] border border-[var(--border)] p-6 rounded-[2rem] shadow-[var(--card-shadow)]">
                <h4 class="text-sm font-black text-[var(--text-main)] uppercase tracking-widest mb-4">⚡ Aksi Cepat</h4>
                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('admin.menus.create') }}" class="p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl text-center hover:bg-emerald-500/20 transition group">
                        <p class="text-2xl mb-1 group-hover:scale-125 transition">➕</p>
                        <p class="text-[10px] font-black text-emerald-600 dark:text-emerald-400 uppercase tracking-widest">Tambah Menu</p>
                    </a>
                    <a href="{{ route('admin.chats.index') }}" class="relative p-4 bg-blue-500/10 border border-blue-500/20 rounded-2xl text-center hover:bg-blue-500/20 transition group">
                        <p class="text-2xl mb-1 group-hover:scale-125 transition">💬</p>
                        <p class="text-[10px] font-black text-blue-600 dark:text-blue-400 uppercase tracking-widest">Live Chat</p>
                        @if($stats['unread_chats'] > 0)
                        <span class="absolute -top-2 -right-2 w-5 h-5 bg-red-500 rounded-full text-[9px] font-black text-white flex items-center justify-center">{{ $stats['unread_chats'] }}</span>
                        @endif
                    </a>
                    <a href="{{ route('admin.menus.index') }}" class="p-4 bg-purple-500/10 border border-purple-500/20 rounded-2xl text-center hover:bg-purple-500/20 transition group">
                        <p class="text-2xl mb-1 group-hover:scale-125 transition">📋</p>
                        <p class="text-[10px] font-black text-purple-600 dark:text-purple-400 uppercase tracking-widest">Daftar Menu</p>
                    </a>
                    <a href="{{ route('admin.gallery.index') }}" class="p-4 bg-amber-500/10 border border-amber-500/20 rounded-2xl text-center hover:bg-amber-500/20 transition group">
                        <p class="text-2xl mb-1 group-hover:scale-125 transition">🖼️</p>
                        <p class="text-[10px] font-black text-amber-600 dark:text-amber-400 uppercase tracking-widest">Galeri</p>
                    </a>
                </div>
            </div>

            {{-- Info tip --}}
            <div class="p-5 bg-amber-500/5 border border-amber-500/20 rounded-2xl flex gap-3">
                <div class="text-xl">💡</div>
                <p class="text-[11px] text-amber-400/80 font-medium leading-relaxed">
                    <b>Tip:</b> Data menu yang diubah di sini akan langsung tampil di aplikasi Android karena menggunakan database <code class="text-amber-300">db_aishcatering</code> yang sama.
                </p>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    function dynamicGreeting() {
        const hour = new Date().getHours();
        const el = document.getElementById('aish-greeting');
        let text = hour < 11 ? 'Selamat Pagi,' : hour < 15 ? 'Selamat Siang,' : hour < 19 ? 'Selamat Sore,' : 'Selamat Malam,';
        if (el) el.innerHTML = `${text} <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-emerald-200">Admin Aish</span>`;
    }
    dynamicGreeting();

    // --- Real-time Orders Sync ---
    let lastOrderCount = {{ count($latest_orders) }};
    let lastOrderId = {{ count($latest_orders) > 0 ? $latest_orders[0]->id : 0 }};
    const orderAudio = new Audio('/mixkit-bell-notification-933.wav');

    async function updateLatestOrders() {
        try {
            const response = await fetch('{{ route("admin.orders.latest_raw") }}');
            const data = await response.json();
            const orders = data.orders;
            const stats = data.stats;
            
            if (orders.length > 0 && orders[0].id > lastOrderId) {
                // New order detected!
                orderAudio.play().catch(e => console.log("Audio play blocked"));
                lastOrderId = orders[0].id;
                
                if (Notification.permission === "granted") {
                    new Notification("Pesanan Baru!", {
                        body: `Pesanan dari ${orders[0].user_email} baru saja masuk.`,
                        icon: "/favicon.ico"
                    });
                }
            }

            // Update Sync Status Bars
            if (stats) {
                document.getElementById('sync-menu-count').innerText = `${stats.total_menu} item`;
                document.getElementById('sync-order-count').innerText = `${stats.total_orders} pesanan`;
                document.getElementById('sync-user-count').innerText = `${stats.total_customers} pelanggan`;
                
                document.getElementById('sync-menu-bar').style.width = `${Math.min(100, stats.total_menu * 10)}%`;
                document.getElementById('sync-order-bar').style.width = `${Math.min(100, stats.total_orders * 5)}%`;
                document.getElementById('sync-user-bar').style.width = `${Math.min(100, stats.total_customers * 10)}%`;
            }

            const container = document.getElementById('latest-orders-container');
            if (orders.length === 0) {
                container.innerHTML = `<div class="bg-[var(--bg-main)] border border-[var(--border)] p-6 rounded-2xl text-center text-[var(--text-muted)] text-sm">Belum ada pesanan dari aplikasi Android.</div>`;
                return;
            }

            let html = '';
            orders.forEach(order => {
                const date = new Date(order.created_at);
                const timeStr = date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                
                let statusClass = 'bg-yellow-500/20 text-yellow-400';
                if (['Selesai', 'COMPLETED'].includes(order.status)) statusClass = 'bg-emerald-500/20 text-emerald-400';
                else if (order.status === 'Dikirim') statusClass = 'bg-orange-500/20 text-orange-400';
                else if (order.status === 'Diproses') statusClass = 'bg-blue-500/20 text-blue-400';

                html += `
                <div class="bg-[var(--bg-card)] border border-[var(--border)] hover:border-blue-500/20 p-4 rounded-2xl flex items-center justify-between transition duration-300 shadow-sm animate-in slide-in-from-right">
                    <div>
                        <p class="text-[var(--text-main)] font-bold text-sm">${order.items_title}</p>
                        <p class="text-[var(--text-muted)] text-[10px] mt-0.5">${order.user_email} · ${timeStr}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[var(--text-main)] font-black text-sm">Rp ${Number(order.total_price).toLocaleString('id-ID')}</p>
                        <span class="inline-block text-[9px] font-black px-2 py-0.5 rounded uppercase mt-1 ${statusClass}">
                            ${order.status}
                        </span>
                    </div>
                </div>`;
            });
            container.innerHTML = html;
        } catch (error) {
            console.error("Failed to fetch latest orders:", error);
        }
    }

    // Initial permission request
    if (Notification.permission !== "denied" && Notification.permission !== "granted") {
        Notification.requestPermission();
    }

    // Poll every 10 seconds
    setInterval(updateLatestOrders, 10000);
</script>
@endpush