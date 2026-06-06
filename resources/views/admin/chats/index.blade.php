@extends('layouts.admin')

@section('title', 'Pesan Masuk')

@section('content')
<div class="p-4 sm:p-6">
    <!-- Header Section -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex-1">
            <h1 class="text-xl sm:text-3xl font-bold font-outfit tracking-tight text-[var(--text-main)]">Pesan Masuk</h1>
            <p class="text-[var(--text-muted)] text-[10px] sm:text-base mt-0.5">Kelola percakapan real-time.</p>
        </div>
        <div class="flex items-center gap-2 bg-emerald-500/10 border border-emerald-500/20 px-3 py-1 rounded-full w-fit shrink-0">
            <span class="flex h-2 w-2 sm:h-3 sm:w-3 relative">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 sm:h-3 sm:w-3 bg-emerald-500"></span>
            </span>
            <span class="text-[10px] sm:text-sm font-black uppercase tracking-widest text-emerald-500">Live Active</span>
        </div>
    </div>

    <!-- Chat List Table -->
    <div class="bg-[var(--bg-card)] border border-[var(--border)] rounded-2xl sm:rounded-3xl overflow-hidden shadow-2xl">
        <div class="overflow-x-auto hidden md:block">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[var(--bg-main)] border-b border-[var(--border)]">
                        <th class="px-6 py-5 text-sm font-semibold text-[var(--text-muted)]">ID Pelanggan</th>
                        <th class="px-6 py-5 text-sm font-semibold text-[var(--text-muted)]">Status</th>
                        <th class="px-6 py-5 text-sm font-semibold text-[var(--text-muted)]">Terakhir Dilihat</th>
                        <th class="px-6 py-5 text-sm font-semibold text-[var(--text-muted)] text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--border)]">
                    @forelse($chats as $chat)
                    <tr class="group hover:bg-[var(--bg-main)]/50 transition-all duration-300">
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-500/20 to-emerald-500/5 flex items-center justify-center border border-emerald-500/20">
                                    <span class="text-emerald-500 font-bold text-sm">#</span>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-[var(--text-main)]">{{ $chat->sender_email }}</p>
                                    <p class="text-[10px] text-[var(--text-muted)] uppercase tracking-widest">{{ str_starts_with($chat->sender_email, 'guest_') ? 'GUEST USER' : 'REGISTERED USER' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-500 border border-emerald-500/20">
                                Aktif
                            </span>
                        </td>
                        <td class="px-6 py-5 text-sm text-[var(--text-muted)]">
                            {{ $chat->max_created_at }}
                        </td>
                        <td class="px-6 py-5 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <form action="{{ route('admin.chats.delete_all', $chat->sender_email) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus seluruh percakapan ini?');" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center p-2.5 bg-red-500/10 hover:bg-red-500 text-red-500 hover:text-white rounded-xl transition-all shadow-lg shadow-red-500/10 hover:shadow-red-500/30 hover:scale-105 active:scale-95" title="Hapus Percakapan">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                                <a href="{{ route('admin.chats.show', $chat->sender_email) }}" 
                                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold rounded-xl transition-all shadow-lg shadow-emerald-500/20 hover:scale-105 active:scale-95">
                                    BUKA PERCAKAPAN
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center opacity-30">
                                <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                </svg>
                                <p class="text-lg font-medium text-[var(--text-muted)] italic">Belum ada pesan masuk hari ini</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="md:hidden divide-y divide-[var(--border)]" id="mobile-chat-list">
            @foreach($chats as $chat)
            <div class="p-4 space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-10 h-10 rounded-full bg-emerald-500/10 flex items-center justify-center border border-emerald-500/20 shrink-0">
                            <span class="text-emerald-500 font-bold text-sm">#</span>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-[var(--text-main)] truncate">{{ $chat->sender_email }}</p>
                            <p class="text-[9px] text-[var(--text-muted)] uppercase tracking-widest">{{ str_starts_with($chat->sender_email, 'guest_') ? 'GUEST' : 'USER' }}</p>
                        </div>
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-[8px] font-black bg-emerald-500/10 text-emerald-500 border border-emerald-500/20 shrink-0">AKTIF</span>
                </div>
                <div class="flex items-center justify-between text-[10px]">
                    <span class="text-[var(--text-muted)] font-medium">Terakhir: {{ \Carbon\Carbon::parse($chat->max_created_at)->diffForHumans() }}</span>
                    <div class="flex gap-2">
                         <form action="{{ route('admin.chats.delete_all', $chat->sender_email) }}" method="POST" onsubmit="return confirm('Hapus percakapan?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-1.5 bg-red-500/10 text-red-500 rounded-lg border border-red-500/20"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                        </form>
                        <a href="{{ route('admin.chats.show', $chat->sender_email) }}" class="px-4 py-1.5 bg-emerald-500 text-white rounded-lg font-black text-[9px] uppercase tracking-widest shadow-lg shadow-emerald-500/20">BUKA</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</div>
<!-- Notification Permission Modal -->
<div id="notif-permission-modal" class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/60 backdrop-blur-sm transition-all duration-500 opacity-0 invisible">
    <div class="bg-[var(--bg-card)] border border-emerald-500/30 p-8 rounded-[2.5rem] shadow-2xl max-w-sm w-full text-center transform scale-90 transition-all duration-500">
        <div class="w-20 h-20 bg-emerald-500/10 rounded-3xl flex items-center justify-center mx-auto mb-6 border border-emerald-500/20">
            <svg class="w-10 h-10 text-emerald-500 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
        </div>
        <h3 class="text-xl font-bold text-[var(--text-main)] mb-3 font-outfit">Aktifkan Notifikasi?</h3>
        <p class="text-[var(--text-muted)] text-sm mb-8 leading-relaxed">Aktifkan notifikasi desktop dan suara agar Anda segera tahu jika ada pelanggan baru.</p>
        
        <div class="flex flex-col gap-3">
            <button id="btn-allow-notif" class="w-full py-4 bg-emerald-500 hover:bg-emerald-600 text-white font-bold rounded-2xl transition-all shadow-lg shadow-emerald-500/30">
                IZINKAN SEKARANG
            </button>
            <button id="btn-skip-notif" class="w-full py-3 text-[var(--text-muted)] hover:text-[var(--text-main)] text-xs font-bold uppercase tracking-widest transition-all">
                Nanti Saja
            </button>
        </div>
    </div>
</div>

<script>
    const notificationSound = new Audio('/mixkit-bell-notification-933.wav');
    let lastChatCount = {{ count($chats) }};
    const chatTableBody = document.querySelector('tbody');

    function playNotification() {
        notificationSound.currentTime = 0;
        notificationSound.play().catch(e => console.log("Audio blocked"));
    }

    // Audio Unlocker
    document.addEventListener('click', function unlock() {
        notificationSound.play().then(() => {
            notificationSound.pause();
            notificationSound.currentTime = 0;
            document.removeEventListener('click', unlock);
        }).catch(() => {});
    }, { once: true });


    function showBrowserNotification(title, body) {
        if (Notification.permission === 'granted') {
            new Notification(title, {
                body: body,
                icon: '/favicon.ico'
            }).onclick = () => window.focus();
        }
    }

    let lastMaxTime = "";
    
    async function pollNewChats() {
        try {
            const response = await fetch('{{ route('admin.chats.index_raw') }}');
            if (response.ok) {
                const chats = await response.json();
                
                // Get the latest timestamp from all chats
                const currentMaxTime = chats.length > 0 ? chats[0].max_created_at : "";
                
                if (lastMaxTime && currentMaxTime > lastMaxTime) {
                    playNotification();
                    showBrowserNotification('Pesan Baru!', 'Ada pesan baru dari pelanggan.');
                }
                
                if (chats.length !== lastChatCount || currentMaxTime !== lastMaxTime) {
                    renderTable(chats);
                    lastChatCount = chats.length;
                    lastMaxTime = currentMaxTime;
                } else if (!lastMaxTime) {
                    lastMaxTime = currentMaxTime;
                }
            }
        } catch (e) {}
    }

    function renderTable(chats) {
        if (chats.length === 0) {
            chatTableBody.innerHTML = `<tr><td colspan="4" class="px-6 py-20 text-center text-[var(--text-muted)] italic">Belum ada pesan masuk hari ini</td></tr>`;
            document.getElementById('mobile-chat-list').innerHTML = `<div class="p-10 text-center text-[var(--text-muted)] italic">Belum ada pesan masuk</div>`;
            return;
        }

        // Render Desktop Table
        chatTableBody.innerHTML = chats.map(chat => `
            <tr class="group hover:bg-[var(--bg-main)]/50 transition-all duration-300">
                <td class="px-6 py-5">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-500/20 to-emerald-500/5 flex items-center justify-center border border-emerald-500/20">
                            <span class="text-emerald-500 font-bold text-sm">#</span>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-[var(--text-main)]">${chat.sender_email}</p>
                            <p class="text-[10px] text-[var(--text-muted)] uppercase tracking-widest">${chat.sender_email.startsWith('guest_') ? 'GUEST USER' : 'REGISTERED USER'}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-5">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-500 border border-emerald-500/20">
                        Aktif
                    </span>
                </td>
                <td class="px-6 py-5 text-sm text-[var(--text-muted)]">${chat.max_created_at}</td>
                <td class="px-6 py-5 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <form action="/admin/chats/${chat.sender_email}/delete-all" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus seluruh percakapan ini?');" class="inline-block">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="inline-flex items-center justify-center p-2.5 bg-red-500/10 hover:bg-red-500 text-red-500 hover:text-white rounded-xl transition-all shadow-lg shadow-red-500/10 hover:shadow-red-500/30 hover:scale-105 active:scale-95" title="Hapus Percakapan">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                        <a href="/admin/chats/${chat.sender_email}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold rounded-xl transition-all shadow-lg shadow-emerald-500/20 hover:scale-105 active:scale-95">
                            BUKA PERCAKAPAN
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </a>
                    </div>
                </td>
            </tr>
        `).join('');

        // Render Mobile Cards
        document.getElementById('mobile-chat-list').innerHTML = chats.map(chat => `
            <div class="p-5 space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-emerald-500/10 flex items-center justify-center border border-emerald-500/20">
                            <span class="text-emerald-500 font-bold text-sm">#</span>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-[var(--text-main)]">${chat.sender_email.length > 20 ? chat.sender_email.substring(0, 17) + '...' : chat.sender_email}</p>
                            <p class="text-[9px] text-[var(--text-muted)] uppercase tracking-widest">${chat.sender_email.startsWith('guest_') ? 'GUEST' : 'USER'}</p>
                        </div>
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-500 border border-emerald-500/20">Aktif</span>
                </div>
                <div class="flex items-center justify-between text-xs">
                    <span class="text-[var(--text-muted)]">Terakhir: ${chat.max_created_at}</span>
                    <div class="flex gap-2">
                        <form action="/admin/chats/${chat.sender_email}/delete-all" method="POST" onsubmit="return confirm('Hapus percakapan?');">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="p-2 bg-red-500/10 text-red-500 rounded-lg"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                        </form>
                        <a href="/admin/chats/${chat.sender_email}" class="px-4 py-2 bg-emerald-500 text-white rounded-lg font-bold text-[10px]">BUKA</a>
                    </div>
                </div>
            </div>
        `).join('');
    }

    // Modal Logic
    const notifModal = document.getElementById('notif-permission-modal');
    if (Notification.permission !== 'granted' && !localStorage.getItem('notif_modal_skipped_index')) {
        setTimeout(() => {
            notifModal.classList.remove('invisible', 'opacity-0');
            notifModal.querySelector('div').classList.remove('scale-90');
        }, 1000);
    }

    document.getElementById('btn-allow-notif').addEventListener('click', () => {
        Notification.requestPermission().then(permission => {
            if (permission === 'granted') {
                playNotification();
                notifModal.classList.add('invisible');
            }
        });
    });

    document.getElementById('btn-skip-notif').addEventListener('click', () => {
        localStorage.setItem('notif_modal_skipped_index', 'true');
        notifModal.classList.add('invisible');
    });

    // Start polling
    // Start polling - Reduced to 2s for responsiveness
    setInterval(pollNewChats, 2000);

</script>
@endsection
