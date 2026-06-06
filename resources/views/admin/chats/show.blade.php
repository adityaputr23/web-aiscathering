@extends('layouts.admin')

@section('title', 'Ruang Percakapan')

@section('content')
<!-- Notification Permission Modal -->
<div id="notif-permission-modal" class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/60 backdrop-blur-sm transition-all duration-500 opacity-0 invisible">
    <div class="bg-[var(--bg-card)] border border-emerald-500/30 p-8 rounded-[2.5rem] shadow-2xl max-w-sm w-full text-center transform scale-90 transition-all duration-500">
        <div class="w-20 h-20 bg-emerald-500/10 rounded-3xl flex items-center justify-center mx-auto mb-6 border border-emerald-500/20">
            <svg class="w-10 h-10 text-emerald-500 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
        </div>
        <h3 class="text-xl font-bold text-[var(--text-main)] mb-3 font-outfit">Aktifkan Notifikasi?</h3>
        <p class="text-[var(--text-muted)] text-sm mb-8 leading-relaxed">Aktifkan notifikasi dan suara agar Anda tidak melewatkan pesan penting dari pelanggan.</p>
        
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

<div class="h-[calc(100vh-64px)] lg:h-[calc(100vh-100px)] flex flex-col p-0 sm:p-4 lg:p-6">
    <!-- Chat Header -->
    <div class="bg-[var(--bg-card)] border-b sm:border border-[var(--border)] sm:rounded-t-3xl p-3 sm:p-5 flex items-center justify-between shadow-sm sm:shadow-lg transition-all">
        <div class="flex items-center gap-2 sm:gap-4 overflow-hidden">
            <a href="{{ route('admin.chats.index') }}" class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-[var(--bg-main)] hover:bg-[var(--border)] flex items-center justify-center border border-[var(--border)] transition-all shrink-0">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-[var(--text-muted)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div class="relative shrink-0 hidden xs:block">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-2xl bg-emerald-500/10 flex items-center justify-center border border-emerald-500/20">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <div class="absolute -bottom-1 -right-1 w-3 h-3 sm:w-4 sm:h-4 rounded-full bg-emerald-500 border-2 border-[var(--bg-card)] animate-pulse"></div>
            </div>
            <div class="min-w-0">
                <h2 class="text-[var(--text-main)] font-bold font-outfit text-sm sm:text-base truncate">{{ $senderEmail }}</h2>
                <div class="flex items-center gap-2">
                    <span class="text-[10px] text-emerald-500 font-bold uppercase tracking-widest">Online Now</span>
                </div>
            </div>
        </div>
        
        <div class="flex items-center gap-1.5 sm:gap-3">
            <form action="{{ route('admin.chats.delete_all', $senderEmail) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus seluruh percakapan ini?');" class="inline-block m-0">
                @csrf
                @method('DELETE')
                <button type="submit" title="Hapus Percakapan" class="p-2 sm:p-2.5 rounded-lg sm:rounded-xl bg-red-500/10 border border-red-500/20 hover:bg-red-500 hover:text-white text-red-500 transition-all">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </form>
            <button onclick="notificationSound.play()" title="Test Sound" class="hidden sm:flex p-2.5 rounded-xl bg-[var(--bg-main)] border border-[var(--border)] hover:border-yellow-500/30 text-[var(--text-muted)] hover:text-yellow-400 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" />
                </svg>
            </button>
            <button onclick="window.location.reload()" class="p-2 sm:p-2.5 rounded-lg sm:rounded-xl bg-[var(--bg-main)] border border-[var(--border)] hover:border-emerald-500/30 text-[var(--text-muted)] hover:text-emerald-400 transition-all">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Message Area -->
    <div id="admin-chat-messages" class="flex-1 bg-white dark:bg-gray-950 border-x border-[var(--border)] overflow-y-auto p-3 sm:p-6 space-y-4 custom-scrollbar">
        @foreach($messages as $msg)
            @php $isAdmin = $msg->sender_type === 'ADMIN'; @endphp
            <div id="msg-{{ $msg->id }}" class="flex {{ $isAdmin ? 'justify-end' : 'justify-start' }} animate-fade-in group mb-4">
                @if($isAdmin)
                <div class="opacity-0 group-hover:opacity-100 transition-opacity flex items-center gap-1 mr-1 sm:mr-2 self-end mb-2">
                    <button onclick="editMessage({{ $msg->id }}, '{{ addslashes($msg->message) }}')" class="p-1 hover:text-emerald-400 text-[var(--text-muted)] transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    </button>
                    <button onclick="deleteMessage({{ $msg->id }})" class="p-1 hover:text-red-400 text-[var(--text-muted)] transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
                @endif

                <div class="max-w-[85%] sm:max-w-[75%]">
                    <div class="flex items-center gap-2 mb-1 {{ $isAdmin ? 'flex-row-reverse' : '' }}">
                        <span class="text-[9px] sm:text-[10px] text-[var(--text-muted)] font-black uppercase tracking-widest opacity-80">
                            {{ $isAdmin ? 'YOU' : 'CUSTOMER' }}
                        </span>
                        <span class="text-[8px] sm:text-[9px] text-[var(--text-muted)] opacity-60 font-medium">{{ \Carbon\Carbon::parse($msg->created_at)->format('H:i') }}</span>
                    </div>
                    
                    <div class="px-4 py-3 rounded-2xl shadow-sm transition-all {{ $isAdmin ? 'bg-green-500 text-white rounded-tr-none shadow-md' : 'bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 border border-gray-200 dark:border-gray-700 rounded-tl-none' }}">
                        @php
                            $text = e($msg->message);
                            $text = preg_replace('/\[IMAGE: (.*?)\]/', '<img src="$1" class="max-w-full rounded-xl mt-2 mb-1 shadow-sm border border-black/5" />', $text);
                            $text = preg_replace('/\[VOICE: (.*?)\]/', '<div class="mt-2 p-2 bg-black/5 dark:bg-white/5 rounded-xl"><audio controls class="w-full h-8"><source src="$1" type="audio/mpeg"></audio></div>', $text);
                        @endphp
                        <div class="message-content text-[13px] sm:text-[14px] leading-relaxed whitespace-pre-wrap">{!! $text !!}</div>
                        @if($isAdmin)
                        <div class="flex justify-end mt-1">
                             <svg class="w-3 h-3 text-green-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                        </div>
                        @endif
                    </div>
                </div>

                @if(!$isAdmin)
                <div class="opacity-0 group-hover:opacity-100 transition-opacity flex items-center gap-1 ml-1 sm:ml-2 self-end mb-2">
                    <button onclick="deleteMessage({{ $msg->id }})" class="p-1 hover:text-red-400 text-[var(--text-muted)] transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
                @endif
            </div>
        @endforeach
        <div id="scroll-anchor"></div>
    </div>

    <!-- Input Area -->
    <div class="bg-[var(--bg-card)] border-t sm:border border-[var(--border)] sm:rounded-b-3xl p-3 sm:p-5 shadow-2xl relative">
        <!-- Image Preview -->
        <div id="chat-image-preview-container" class="hidden absolute bottom-[100%] left-0 w-full p-4 bg-[var(--bg-card)] border-t border-[var(--border)] z-10 shadow-lg rounded-t-3xl">
            <div class="relative inline-block">
                <img id="chat-image-preview" src="" class="h-20 w-auto rounded-xl object-cover border border-[var(--border)]">
                <button type="button" onclick="window.removeChatImage()" class="absolute -top-2 -right-2 bg-rose-500 text-white rounded-full p-1 shadow-md hover:bg-rose-600 transition-all">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
        </div>

        <!-- Voice Recording Overlay -->
        <div id="voice-overlay" class="hidden absolute inset-0 bg-white dark:bg-gray-900 z-20 flex items-center justify-between px-4 sm:px-8 rounded-b-2xl sm:rounded-b-3xl border-t border-[var(--border)] animate-fade-in">
            <div class="flex items-center gap-3">
                <span class="w-3 h-3 bg-rose-500 rounded-full animate-pulse shadow-[0_0_8px_rgba(239,68,68,0.6)]"></span>
                <span id="voice-timer" class="text-sm font-bold text-gray-800 dark:text-gray-100 tabular-nums">00:00</span>
            </div>
            <div class="flex items-center gap-3">
                <button type="button" id="cancel-voice" class="text-[10px] font-black text-rose-500 uppercase tracking-widest hover:bg-rose-500/10 px-4 py-2 rounded-xl transition-all">Batal</button>
                <button type="button" id="stop-voice" class="w-10 h-10 sm:w-12 sm:h-12 bg-green-500 text-white rounded-full flex items-center justify-center shadow-lg animate-bounce">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                </button>
            </div>
        </div>

        <!-- Emoji Picker Popup -->
        <div id="emoji-picker" class="hidden absolute bottom-[100%] left-4 w-64 bg-[var(--bg-card)] border border-[var(--border)] rounded-2xl shadow-2xl p-4 z-[100] animate-fade-in mb-2">
            <div class="grid grid-cols-6 gap-2 text-xl max-h-48 overflow-y-auto custom-scrollbar">
                @php $emojis = ['😊','😂','🥰','😍','👍','🙌','✨','🔥','🙏','🍱','🍽️','🥘','😋','👋','❤️','💯','📍','✅']; @endphp
                @foreach($emojis as $emoji)
                    <button type="button" onclick="window.addEmoji('{{ $emoji }}')" class="hover:bg-[var(--bg-main)] p-2 rounded-xl transition-all">{{ $emoji }}</button>
                @endforeach
            </div>
        </div>

        <form id="admin-reply-form" action="{{ route('admin.chats.reply', $senderEmail) }}" method="POST" class="flex items-center gap-2 sm:gap-4">
            @csrf
            <label for="chat-image-input" class="w-10 h-10 sm:w-12 sm:h-12 bg-[var(--bg-main)] hover:bg-[var(--border)] text-[var(--text-muted)] rounded-xl flex items-center justify-center cursor-pointer transition-all border border-[var(--border)] shrink-0 group">
                <svg class="w-5 h-5 group-hover:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
            </label>
            <input type="file" id="chat-image-input" class="hidden" accept="image/*" onchange="window.previewChatImage(this)">

            <div class="flex-1 bg-[var(--bg-main)] border border-[var(--border)] rounded-2xl px-3 sm:px-4 py-2 sm:py-2.5 focus-within:border-emerald-500/50 focus-within:ring-2 focus-within:ring-emerald-500/10 transition-all flex items-center gap-2">
                <button type="button" id="emoji-btn" class="w-8 h-8 flex items-center justify-center text-[var(--text-muted)] hover:text-orange-400 transition-colors shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </button>
                <input type="text" id="admin-reply-input" placeholder="Ketik balasan..."
                       class="w-full bg-transparent border-none text-xs sm:text-sm text-[var(--text-main)] placeholder:text-[var(--text-muted)]/50 focus:ring-0 outline-none p-0">
            </div>
            
            <button id="voice-btn" type="button"
                    class="w-10 h-10 sm:w-12 sm:h-12 bg-[var(--bg-main)] hover:bg-[var(--border)] text-[var(--text-muted)] rounded-xl flex items-center justify-center transition-all border border-[var(--border)] shrink-0 group">
                <svg class="w-5 h-5 group-hover:text-rose-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" /></svg>
            </button>

            <button type="submit" class="w-10 h-10 sm:w-12 sm:h-12 bg-green-500 hover:bg-green-600 text-white rounded-xl flex items-center justify-center shadow-lg shadow-green-500/20 transition-all hover:scale-110 active:scale-90 shrink-0">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                </svg>
            </button>
        </form>
    </div>

</div>

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in { animation: fade-in 0.3s ease-out forwards; }
    
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.05); border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #10b981; }
</style>

<!-- Firebase Scripts -->
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

    function initFirebase() {
        messaging.getToken({ vapidKey: 'BIBDUamdRKW9NM_QJYxCYFwNgzqonF8uSgDnXCPitTWG84_lo5oRKHHITfW5iwHZYRXVyG5yqwp59pOr8QVhk0Q' }).then((currentToken) => {
            if (currentToken) {
                console.log('FCM Token:', currentToken);
                saveTokenToServer(currentToken);
            } else {
                console.log('No registration token available. Request permission to generate one.');
            }
        }).catch((err) => {
            console.log('An error occurred while retrieving token. ', err);
        });
    }

    function saveTokenToServer(token) {
        fetch('{{ route('admin.chats.save_token') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ token: token })
        });
    }

    // Handle incoming messages while in foreground
    messaging.onMessage((payload) => {
        console.log('Message received in foreground: ', payload);
        // Current pooling system will handle UI update, but we can play sound
        playNotification();
        showBrowserNotification(payload.notification.title, payload.notification.body);
    });

    const messageContainer = document.getElementById('admin-chat-messages');
    const replyForm = document.getElementById('admin-reply-form');
    const replyInput = document.getElementById('admin-reply-input');
    const notificationSound = new Audio('/mixkit-bell-notification-933.wav');
    let originalTitle = document.title;
    let titleInterval = null;
    let lastCount = {{ count($messages) }};

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

    // Stop flashing when admin clicks input or focus
    replyInput.addEventListener('focus', stopFlashTitle);

    function playNotification() {
        notificationSound.currentTime = 0;
        notificationSound.play().catch(e => {
            console.log("Audio play blocked by browser.");
        });
    }

    // Unlock audio for browser policy on first click anywhere
    document.addEventListener('click', function unlock() {
        notificationSound.play().then(() => {
            notificationSound.pause();
            notificationSound.currentTime = 0;
            document.removeEventListener('click', unlock);
        }).catch(() => {});
    }, { once: true });

    const scrollToBottom = () => {
        messageContainer.scrollTop = messageContainer.scrollHeight;
    };
    scrollToBottom();

    function parseMessageContent(text) {
        if (!text) return '';
        let sanitized = text.replace(/</g, "&lt;").replace(/>/g, "&gt;");
        sanitized = sanitized.replace(/\[IMAGE: (.*?)\]/g, '<img src="$1" class="max-w-full rounded-xl mt-2 mb-1 shadow-sm border border-black/5" />');
        sanitized = sanitized.replace(/\[VOICE: (.*?)\]/g, `
            <div class="mt-2 p-3 bg-black/5 dark:bg-white/5 rounded-xl border border-black/5">
                <audio controls class="w-full h-8 custom-audio">
                    <source src="$1" type="audio/mpeg">
                </audio>
            </div>`);
        return sanitized;
    }

    function appendMessage(msg) {
        const isAdmin = msg.sender_type === 'ADMIN';
        const time = new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        
        const div = document.createElement('div');
        div.id = `msg-${msg.id}`;
        div.className = `flex ${isAdmin ? 'justify-end' : 'justify-start'} animate-fade-in group mb-4`;
        
        let actionsHtml = isAdmin ? `
            <div class="opacity-0 group-hover:opacity-100 transition-opacity flex items-center gap-1 mr-2 self-end mb-2">
                <button onclick="editMessage(${msg.id}, '${msg.message.replace(/'/g, "\\'")}')" class="p-1 hover:text-emerald-400 text-[var(--text-muted)] transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                </button>
                <button onclick="deleteMessage(${msg.id})" class="p-1 hover:text-red-400 text-[var(--text-muted)] transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            </div>
        ` : `
            <div class="opacity-0 group-hover:opacity-100 transition-opacity flex items-center gap-1 ml-2 self-end mb-2">
                <button onclick="deleteMessage(${msg.id})" class="p-1 hover:text-red-400 text-[var(--text-muted)] transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            </div>
        `;

        div.innerHTML = `
            ${isAdmin ? actionsHtml : ''}
            <div class="max-w-[85%] sm:max-w-[75%]">
                <div class="flex items-center gap-2 mb-1 ${isAdmin ? 'flex-row-reverse' : ''}">
                    <span class="text-[9px] sm:text-[10px] text-[var(--text-muted)] font-black uppercase tracking-widest opacity-80">
                        ${isAdmin ? 'YOU' : 'CUSTOMER'}
                    </span>
                    <span class="text-[8px] sm:text-[9px] text-[var(--text-muted)] opacity-60 font-medium">${time}</span>
                </div>
                <div class="px-4 py-3 rounded-2xl shadow-sm transition-all ${isAdmin ? 'bg-green-500 text-white rounded-tr-none shadow-md' : 'bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 border border-gray-200 dark:border-gray-700 rounded-tl-none'}">
                    <div class="message-content text-[13px] sm:text-[14px] leading-relaxed whitespace-pre-wrap">${parseMessageContent(msg.message)}</div>
                    ${isAdmin ? `
                        <div class="flex justify-end mt-1">
                             <svg class="w-3 h-3 text-green-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                        </div>
                    ` : ''}
                </div>
            </div>
            ${!isAdmin ? actionsHtml : ''}
        `;
        messageContainer.appendChild(div);
        scrollToBottom();
    }

    // Emoji, Image, Voice UI Logic
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

    window.addEmoji = function(emoji) {
        replyInput.value += emoji;
        replyInput.focus();
    };

    document.getElementById('emoji-btn').addEventListener('click', (e) => {
        e.stopPropagation();
        document.getElementById('emoji-picker').classList.toggle('hidden');
    });

    document.addEventListener('click', (e) => {
        const picker = document.getElementById('emoji-picker');
        if (picker && !picker.contains(e.target)) picker.classList.add('hidden');
    });

    // Voice Logic
    let mediaRecorder;
    let audioChunks = [];
    let voiceTimer;
    let voiceSeconds = 0;
    const voiceBtn = document.getElementById('voice-btn');
    const voiceOverlay = document.getElementById('voice-overlay');
    const timerEl = document.getElementById('voice-timer');

    voiceBtn.addEventListener('click', async () => {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
            mediaRecorder = new MediaRecorder(stream);
            audioChunks = [];
            mediaRecorder.ondataavailable = e => audioChunks.push(e.data);
            mediaRecorder.onstop = async () => {
                const audioBlob = new Blob(audioChunks, { type: 'audio/mpeg' });
                if (!window.voiceCancelled) await sendAdminReply(null, null, audioBlob);
                stream.getTracks().forEach(track => track.stop());
            };
            mediaRecorder.start();
            window.voiceCancelled = false;
            voiceOverlay.classList.remove('hidden');
            voiceSeconds = 0;
            voiceTimer = setInterval(() => {
                voiceSeconds++;
                const min = Math.floor(voiceSeconds / 60).toString().padStart(2, '0');
                const sec = (voiceSeconds % 60).toString().padStart(2, '0');
                timerEl.innerText = `${min}:${sec}`;
            }, 1000);
        } catch (err) { alert("Mikrofon tidak dapat diakses."); }
    });

    document.getElementById('cancel-voice').addEventListener('click', () => {
        window.voiceCancelled = true;
        mediaRecorder.stop();
        clearInterval(voiceTimer);
        voiceOverlay.classList.add('hidden');
    });
    document.getElementById('stop-voice').addEventListener('click', () => {
        mediaRecorder.stop();
        clearInterval(voiceTimer);
        voiceOverlay.classList.add('hidden');
    });

    // Handle AJAX Reply with Files
    async function sendAdminReply(text, imageFile, voiceBlob) {
        const formData = new FormData();
        if (text) formData.append('message', text);
        if (imageFile) formData.append('image', imageFile);
        if (voiceBlob) formData.append('voice', voiceBlob, 'voice.mp3');

        try {
            const response = await fetch(replyForm.action, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                body: formData
            });
            if (response.ok) checkNewMessages();
        } catch (e) { console.error("Reply error:", e); }
    }

    replyForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const text = replyInput.value.trim();
        const imageInput = document.getElementById('chat-image-input');
        const imageFile = imageInput.files[0];

        if (!text && !imageFile) return;

        replyInput.value = '';
        window.removeChatImage();
        await sendAdminReply(text, imageFile);
    });

    async function editMessage(id, oldText) {
        const newText = prompt('Edit Pesan:', oldText.replace(' (diedit)', ''));
        if (newText && newText !== oldText) {
            try {
                const response = await fetch(`/admin/chats/message/${id}`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                    body: JSON.stringify({ message: newText })
                });
                if (response.ok) {
                    const data = await response.json();
                    document.querySelector(`#msg-${id} .message-content`).innerHTML = parseMessageContent(data.message.message);
                }
            } catch (e) { console.error(e); }
        }
    }

    async function deleteMessage(id) {
        if (confirm('Hapus pesan ini?')) {
            try {
                const response = await fetch(`/admin/chats/message/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                });
                if (response.ok) document.getElementById(`msg-${id}`).remove();
            } catch (e) { console.error(e); }
        }
    }

    // Handle Enter to Submit
    replyInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            replyForm.dispatchEvent(new Event('submit'));
        }
    });

    // Notification Permission Logic
    const notifModal = document.getElementById('notif-permission-modal');
    const btnAllowNotif = document.getElementById('btn-allow-notif');
    const btnSkipNotif = document.getElementById('btn-skip-notif');

    // Show modal if permission not granted
    if (Notification.permission !== 'granted' && !localStorage.getItem('notif_modal_skipped')) {
        setTimeout(() => {
            notifModal.classList.remove('invisible', 'opacity-0');
            notifModal.querySelector('div').classList.remove('scale-90');
        }, 1000);
    }

    btnAllowNotif.addEventListener('click', () => {
        Notification.requestPermission().then(permission => {
            if (permission === 'granted') {
                playNotification(); // Unlock audio
                initFirebase();     // Get FCM Token
                hideNotifModal();
            }
        });
    });

    btnSkipNotif.addEventListener('click', () => {
        localStorage.setItem('notif_modal_skipped', 'true');
        hideNotifModal();
    });

    function hideNotifModal() {
        notifModal.classList.add('opacity-0');
        notifModal.querySelector('div').classList.add('scale-90');
        setTimeout(() => {
            notifModal.classList.add('invisible');
        }, 500);
    }

    function showBrowserNotification(title, body) {
        if (Notification.permission === 'granted') {
            const n = new Notification(title, {
                body: body,
                icon: 'https://ui-avatars.com/api/?name=AISH+Catering&background=22c55e&color=fff'
            });
            n.onclick = () => {
                window.focus();
                n.close();
            };
        }
    }

    // Auto-poll for new messages
    async function checkNewMessages() {
        try {
            const response = await fetch('{{ route('admin.chats.raw', $senderEmail) }}');
            if (response.ok) {
                const data = await response.json();
                if (data.length > lastCount) {
                    const newMessages = data.slice(lastCount);
                    let hasCustomerMsg = false;
                    let lastMsgText = '';
                    
                    newMessages.forEach(msg => {
                        appendMessage(msg);
                        if (msg.sender_type === 'USER') {
                            hasCustomerMsg = true;
                            lastMsgText = msg.message;
                        }
                    });

                    if (hasCustomerMsg) {
                        playNotification();
                        showBrowserNotification('Pesan Baru dari {{ $senderEmail }}', lastMsgText);
                        if (document.activeElement !== replyInput) flashTitle();
                    }
                    lastCount = data.length;
                }
            }
        } catch (e) {}
    }
    setInterval(checkNewMessages, 2000);
</script>
@endsection
