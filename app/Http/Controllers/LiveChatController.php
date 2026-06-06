<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LiveChatController extends Controller
{
    public function sendMessage(Request $request)
    {
        Log::info("Chat Send Hit", ['data' => $request->all()]);

        $request->validate([
            'message' => 'nullable|string',
            'image' => 'nullable|image|max:5120',
            'voice' => 'nullable|mimes:mp3,wav,ogg,m4a,webm,mp4|max:10240'
        ]);

        if (!$request->message && !$request->hasFile('image') && !$request->hasFile('voice')) {
            return response()->json(['status' => 'error', 'message' => 'Pesan tidak boleh kosong'], 400);
        }

        $senderEmail = auth()->check() ? auth()->user()->email : 'guest_' . session()->getId();

        $imageUrl = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/chats'), $filename);
            $imageUrl = '/uploads/chats/' . $filename;
        }

        $voiceUrl = null;
        if ($request->hasFile('voice')) {
            $file = $request->file('voice');
            $filename = 'voice_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/chats/voice'), $filename);
            $voiceUrl = '/uploads/chats/voice/' . $filename;
        }

        $messageContent = $request->message ?? '';
        if ($imageUrl) {
            $messageContent = $messageContent . ($messageContent ? "\n" : "") . "[IMAGE: $imageUrl]";
        }
        if ($voiceUrl) {
            $messageContent = $messageContent . ($messageContent ? "\n" : "") . "[VOICE: $voiceUrl]";
        }

        if (trim(strtolower($request->message)) === '/admin' || trim(strtolower($request->message)) === 'chat dengan admin') {
            session(['chat_mode' => 'ADMIN']);
            $userMessage = Message::create([
                'sender_email' => $senderEmail,
                'receiver_email' => 'admin@aishcatering.com',
                'message' => "Pengguna beralih untuk chat dengan admin.",
                'sender_type' => 'USER',
            ]);
            Message::create([
                'sender_email' => 'admin@aishcatering.com',
                'receiver_email' => $senderEmail,
                'message' => "Anda sekarang terhubung dengan Admin. Silakan tinggalkan pesan Anda.",
                'sender_type' => 'ADMIN',
            ]);
            return response()->json(['status' => 'success', 'message' => $userMessage]);
        }

        if (trim(strtolower($request->message)) === '/bot' || trim(strtolower($request->message)) === 'chat dengan bot') {
            session(['chat_mode' => 'BOT']);
            $userMessage = Message::create([
                'sender_email' => $senderEmail,
                'receiver_email' => 'admin@aishcatering.com',
                'message' => "Pengguna beralih untuk chat dengan bot.",
                'sender_type' => 'USER',
            ]);
            Message::create([
                'sender_email' => 'admin@aishcatering.com',
                'receiver_email' => $senderEmail,
                'message' => "Anda kembali terhubung dengan Bot AISH.",
                'sender_type' => 'ADMIN',
            ]);
            return response()->json(['status' => 'success', 'message' => $userMessage]);
        }
        
        // Save user message
        $userMessage = Message::create([
            'sender_email' => $senderEmail,
            'receiver_email' => 'admin@aishcatering.com',
            'message' => $messageContent,
            'sender_type' => 'USER',
        ]);

        $mode = session('chat_mode', 'BOT');

        if ($mode !== 'ADMIN') {
            Log::info("Triggering Bot Response for " . $senderEmail);
            
            // Always use AI for a smarter response that can answer "everything"
            // We pass the menu data and history to the AI
            $this->getAIResponse($senderEmail, $messageContent);
            
            /* 
            // Previous Rule-based logic (Disabled to allow AI to handle everything)
            $ruleReply = $this->getRuleResponse($messageContent);
            if ($ruleReply) {
                Message::create([
                    'sender_email' => 'admin@aishcatering.com',
                    'receiver_email' => $senderEmail,
                    'message' => $ruleReply,
                    'sender_type' => 'ADMIN',
                ]);
            }
            */
        } else {
            // Send FCM Push to Admin
            $admin = \App\Models\User::where('email', 'admin@aishcatering.com')->first();
            if ($admin && $admin->fcm_token) {
                $fcm = new \App\Services\FcmService();
                $fcm->sendPush(
                    $admin->fcm_token, 
                    "Pesan Baru dari " . $senderEmail, 
                    $messageContent,
                    ['email' => $senderEmail]
                );
            }
        }

        return response()->json(['status' => 'success', 'message' => $userMessage]);
    }

    private function getRuleResponse($text)
    {
        $low = strtolower($text);
        if (str_contains($low, 'menu') || str_contains($low, 'daftar')) {
            return "Berikut menu kami:\n🍱 Nasi Kotak (25rb)\n🍲 Tumpeng Mini (45rb)\n🏰 Prasmanan (85rb)\n🥪 Snack Box (15rb)\nCek selengkapnya di halaman Menu ya!";
        }
        if (str_contains($low, 'harga') || str_contains($low, 'biaya')) {
            return "Harga kami sangat terjangkau, mulai dari Rp 15.000 untuk Snack Box. Ingin paket khusus? Beritahu saya kebutuhan Anda!";
        }
        if (str_contains($low, 'lokasi') || str_contains($low, 'alamat') || str_contains($low, 'singkawang')) {
            return "AISH Catering berlokasi di Singkawang. Kami siap antar pesanan ke seluruh area kota!";
        }
        if (str_contains($low, 'telp') || str_contains($low, 'wa') || str_contains($low, 'whatsapp')) {
            return "Anda bisa menghubungi kami via WA di 0812-XXXX-XXXX atau klik tombol WhatsApp di halaman utama.";
        }
        return null;
    }

    public function getMessages(Request $request)
    {
        $senderEmail = auth()->check() ? auth()->user()->email : 'guest_' . session()->getId();
        
        // Only mark admin messages to this user as read if mark_as_read is explicitly true
        if ($request->query('mark_as_read') === 'true') {
            Message::where('sender_email', 'admin@aishcatering.com')
                ->where('receiver_email', $senderEmail)
                ->where('is_read', 0)
                ->update(['is_read' => 1]);
        }

        $messages = Message::where(function($q) use ($senderEmail) {
                $q->where('sender_email', $senderEmail)
                  ->orWhere('receiver_email', $senderEmail);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }

    public function getUnreadCount()
    {
        $count = Message::where('receiver_email', 'admin@aishcatering.com')
            ->where('is_read', 0)
            ->count();
        return response()->json(['unread_count' => $count]);
    }

    private function getAIResponse($senderEmail, $userText)
    {
        $apiKey = config('services.gemini.key');
        $difyKey = config('services.dify.key');

        if (!$apiKey && !$difyKey) {
            Log::warning("No AI API Keys found (Gemini and Dify are both missing)");
            return;
        }

        // 1. Fetch Menu Data for Context (So AI knows exactly what we sell)
        $menuContext = "Informasi Menu AISH Catering Saat Ini:\n";
        try {
            $menus = \App\Models\Menu::where('is_available', true)->get();
            if ($menus->isEmpty()) {
                $menuContext .= "- Nasi Kotak (Rp 25.000)\n- Tumpeng Mini (Rp 45.000)\n- Prasmanan Premium (Rp 85.000)\n- Snack Box (Rp 15.000)";
            } else {
                foreach ($menus as $menu) {
                    $menuContext .= "- {$menu->name}: Rp " . number_format($menu->price, 0, ',', '.') . " ({$menu->description})\n";
                }
            }
        } catch (\Exception $e) {
            $menuContext .= "- Nasi Kotak (Rp 25.000)\n- Tumpeng Mini (Rp 45.000)";
        }

        // 2. Fetch Chat History (For context-aware conversation)
        $history = Message::where(function($q) use ($senderEmail) {
                $q->where('sender_email', $senderEmail)
                  ->orWhere('receiver_email', $senderEmail);
            })
            ->orderBy('id', 'desc')
            ->limit(12)
            ->get()
            ->reverse()
            ->values();

        // Build contents with strict alternating user/model roles (Gemini requirement)
        $contents = [];
        $lastRole = null;
        foreach ($history as $msg) {
            $role = ($msg->sender_type === 'USER') ? 'user' : 'model';
            $cleanText = preg_replace('/\[IMAGE: (.*?)\]/', '(Mengirim gambar)', $msg->message);
            $cleanText = $cleanText ?: '(Pesan kosong)';

            // Skip consecutive same-role messages (merge them instead)
            if ($role === $lastRole && !empty($contents)) {
                // Append to previous message to avoid duplication errors
                $last = array_pop($contents);
                $cleanText = $last['parts'][0]['text'] . "\n" . $cleanText;
                $contents[] = ['role' => $role, 'parts' => [['text' => $cleanText]]];
            } else {
                $contents[] = ['role' => $role, 'parts' => [['text' => $cleanText]]];
                $lastRole = $role;
            }
        }

        // Gemini MUST start with 'user' role
        while (!empty($contents) && $contents[0]['role'] !== 'user') {
            array_shift($contents);
        }

        // Fallback: if still empty, add the current message
        if (empty($contents)) {
            $contents[] = ['role' => 'user', 'parts' => [['text' => $userText]]];
        }

        // Ensure the last message is user (the current message)
        // If last is already the current user message, great. Otherwise ensure it ends with user.
        if (end($contents)['role'] !== 'user') {
            $contents[] = ['role' => 'user', 'parts' => [['text' => $userText]]];
        }

        $systemPrompt = "Anda adalah 'AISH Bot', asisten AI resmi AISH Catering di Singkawang. 
        Tugas Anda adalah melayani konsumen dengan ramah, cerdas, dan membantu.
        
        INFORMASI PENTING:
        - Lokasi: Singkawang, Kalimantan Barat.
        - Keunggulan: Halal, Higienis, dan Bahan Segar.
        - Kontak: Bisa hubungi Admin via tombol '/admin'.
        
        {$menuContext}
        
        INSTRUKSI KHUSUS:
        1. Jawab SEMUA pertanyaan konsumen. Jika mereka bertanya hal umum (seperti tips masak, info dunia, atau sekadar ngobrol), jawablah dengan cerdas dan membantu. JANGAN menolak menjawab.
        2. Gunakan gaya bahasa Indonesia yang santai tapi tetap sopan (seperti asisten profesional yang bersahabat).
        3. Gunakan emoji agar percakapan terasa hidup.
        4. Jika konsumen ingin memesan atau komplain serius, arahkan mereka untuk bicara dengan Admin dengan mengetik '/admin'.";

        $chatbotComKey = config('services.chatbot_com.key');
        $difyKey = config('services.dify.key');
        $replied = false;

        // 1. Try ChatBot.com (Newest request)
        if ($chatbotComKey) {
            try {
                Log::info("Trying ChatBot.com for {$senderEmail}");
                $response = Http::withoutVerifying()->timeout(30)
                    ->withToken($chatbotComKey)
                    ->post("https://api.chatbot.com/v2/query", [
                        'query' => $userText,
                        'sessionId' => substr(md5($senderEmail), 0, 16),
                    ]);

                if ($response->successful()) {
                    $data = $response->json();
                    // Extract text message from ChatBot.com fulfillment
                    $reply = $data['result']['fulfillment'][0]['message'] ?? null;

                    if ($reply) {
                        Message::create([
                            'sender_email' => 'admin@aishcatering.com',
                            'receiver_email' => $senderEmail,
                            'message' => $reply,
                            'sender_type' => 'ADMIN',
                        ]);
                        $replied = true;
                    }
                } else {
                    Log::error("ChatBot.com API Fail: " . $response->body());
                }
            } catch (\Exception $e) {
                Log::error("ChatBot.com Error: " . $e->getMessage());
            }
        }

        // 2. Try Dify AI if ChatBot.com failed
        if (!$replied && $difyKey) {
            try {
                Log::info("Trying Dify AI for {$senderEmail}");
                $response = Http::withoutVerifying()->timeout(30)
                    ->withToken($difyKey)
                    ->post("https://api.dify.ai/v1/chat-messages", [
                        'inputs' => [
                            'menu_context' => $menuContext,
                        ],
                        'query' => $userText,
                        'response_mode' => 'blocking',
                        'user' => substr(md5($senderEmail), 0, 16),
                    ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $reply = $data['answer'] ?? null;

                    if ($reply) {
                        Message::create([
                            'sender_email' => 'admin@aishcatering.com',
                            'receiver_email' => $senderEmail,
                            'message' => $reply,
                            'sender_type' => 'ADMIN',
                        ]);
                        $replied = true;
                    }
                }
            } catch (\Exception $e) {
                Log::error("Dify Error: " . $e->getMessage());
            }
        }

        // 2. Fallback to Gemini if Dify failed or not configured
        if (!$replied) {
            $models = [
                "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}",
                "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-pro:generateContent?key={$apiKey}",
                "https://generativelanguage.googleapis.com/v1/models/gemini-pro:generateContent?key={$apiKey}",
            ];

            foreach ($models as $modelUrl) {
                try {
                    Log::info("Trying Gemini model: " . $modelUrl . " with " . count($contents) . " messages");

                    $payload = [
                        'contents' => $contents,
                        'generationConfig' => [
                            'temperature' => 0.8,
                            'topK' => 40,
                            'topP' => 0.95,
                            'maxOutputTokens' => 800,
                        ]
                    ];

                    // system_instruction only supported on v1beta
                    if (str_contains($modelUrl, 'v1beta')) {
                        $payload['system_instruction'] = [
                            'parts' => [['text' => $systemPrompt]]
                        ];
                    } else {
                        // For v1/gemini-pro, prepend system prompt as first user turn
                        array_unshift($payload['contents'], [
                            'role' => 'user',
                            'parts' => [['text' => $systemPrompt . "\n\nPertanyaan: " . $userText]]
                        ]);
                    }

                    $response = Http::withoutVerifying()->timeout(25)->post($modelUrl, $payload);

                    if ($response->successful()) {
                        $data = $response->json();
                        $reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

                        if ($reply) {
                            Message::create([
                                'sender_email' => 'admin@aishcatering.com',
                                'receiver_email' => $senderEmail,
                                'message' => $reply,
                                'sender_type' => 'ADMIN',
                            ]);
                            $replied = true;
                            break; // Success! Stop trying other models
                        }
                    } elseif ($response->status() === 429) {
                        Log::warning("Quota exhausted for model: " . $modelUrl . " — trying next model");
                        continue; // Try next model
                    } else {
                        Log::error("Gemini API Fail (" . $modelUrl . "): " . $response->body());
                        continue; // Try next model
                    }

                } catch (\Exception $e) {
                    Log::error("Chat AI Error for model " . $modelUrl . ": " . $e->getMessage());
                    continue; // Try next model
                }
            }
        }

        if (!$replied) {
            $this->triggerAdminNotification($senderEmail, $userText);
        }
    }

    private function triggerAdminNotification($senderEmail, $userText)
    {
        Message::create([
            'sender_email' => 'admin@aishcatering.com',
            'receiver_email' => $senderEmail,
            'message' => "Maaf, koneksi AI sedang sibuk. Silakan tunggu sebentar atau ketik '/admin' untuk langsung berbicara dengan tim kami.",
            'sender_type' => 'ADMIN',
        ]);
    }

    // Admin Methods
    public function adminIndex()
    {
        $chats = $this->getChatList();
        return view('admin.chats.index', compact('chats'));
    }

    public function adminChatsRaw()
    {
        return response()->json($this->getChatList());
    }

    protected function getChatList()
    {
        return Message::where('sender_type', 'USER')
            ->select('sender_email')
            ->groupBy('sender_email')
            ->orderBy('max_created_at', 'desc')
            ->selectRaw('max(created_at) as max_created_at')
            ->get();
    }

    public function adminChat($senderEmail)
    {
        // Mark all messages from this user as read
        Message::where('sender_email', $senderEmail)
            ->where('receiver_email', 'admin@aishcatering.com')
            ->where('is_read', 0)
            ->update(['is_read' => 1]);

        $messages = Message::where('sender_email', $senderEmail)
            ->orWhere('receiver_email', $senderEmail)
            ->orderBy('created_at', 'asc')
            ->get();
        return view('admin.chats.show', compact('messages', 'senderEmail'));
    }

    public function adminReply(Request $request, $senderEmail)
    {
        \Log::info("Admin Reply Hit for " . $senderEmail, ['message' => $request->message]);
        
        $request->validate([
            'message' => 'nullable|string',
            'image' => 'nullable|image|max:5120',
            'voice' => 'nullable|mimes:mp3,wav,ogg,m4a,webm,mp4|max:10240'
        ]);

        if (!$request->message && !$request->hasFile('image') && !$request->hasFile('voice')) {
            return response()->json(['status' => 'error', 'message' => 'Pesan tidak boleh kosong'], 400);
        }

        $imageUrl = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/chats'), $filename);
            $imageUrl = '/uploads/chats/' . $filename;
        }

        $voiceUrl = null;
        if ($request->hasFile('voice')) {
            $file = $request->file('voice');
            $filename = 'voice_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/chats/voice'), $filename);
            $voiceUrl = '/uploads/chats/voice/' . $filename;
        }

        $messageContent = $request->message ?? '';
        if ($imageUrl) {
            $messageContent = $messageContent . ($messageContent ? "\n" : "") . "[IMAGE: $imageUrl]";
        }
        if ($voiceUrl) {
            $messageContent = $messageContent . ($messageContent ? "\n" : "") . "[VOICE: $voiceUrl]";
        }

        $reply = Message::create([
            'sender_email' => 'admin@aishcatering.com',
            'receiver_email' => $senderEmail,
            'message' => $messageContent,
            'sender_type' => 'ADMIN',
        ]);

        // Kirim Push Notification ke USER (jika ada token)
        $user = \App\Models\User::where('email', $senderEmail)->first();
        if ($user && $user->fcm_token) {
            try {
                $fcm = new \App\Services\FcmService();
                $fcm->sendPush(
                    $user->fcm_token, 
                    "AISH Catering: Pesan Baru", 
                    $messageContent,
                    ['type' => 'chat_reply']
                );
            } catch (\Exception $e) {
                \Log::error("Failed to send FCM to user: " . $e->getMessage());
            }
        }

        if ($request->wantsJson()) {
            return response()->json(['status' => 'success', 'message' => $reply]);
        }

        return back();
    }

    public function getRawMessages($senderEmail)
    {
        // Mark all messages from this user as read since admin is actively in this room
        Message::where('sender_email', $senderEmail)
            ->where('receiver_email', 'admin@aishcatering.com')
            ->where('is_read', 0)
            ->update(['is_read' => 1]);

        $messages = Message::where('sender_email', $senderEmail)
            ->orWhere('receiver_email', $senderEmail)
            ->orderBy('created_at', 'asc')
            ->get();
        return response()->json($messages);
    }

    public function updateMessage(Request $request, Message $message)
    {
        $request->validate(['message' => 'required|string']);
        
        // Only admin can edit admin messages, or user can edit theirs (if we allow)
        // For now, let's just allow the current session/user to edit their own
        $message->update([
            'message' => $request->message . ' (diedit)'
        ]);

        return response()->json(['status' => 'success', 'message' => $message]);
    }

    public function deleteMessage(Message $message)
    {
        $message->delete();
        return response()->json(['status' => 'success']);
    }

    public function userDeleteMessage(Message $message)
    {
        $senderEmail = auth()->check() ? auth()->user()->email : 'guest_' . session()->getId();
        if ($message->sender_email === $senderEmail) {
            $message->delete();
            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
    }

    public function adminDeleteChat($senderEmail)
    {
        Message::where('sender_email', $senderEmail)
            ->orWhere('receiver_email', $senderEmail)
            ->delete();
            
        return redirect()->route('admin.chats.index')->with('success', 'Percakapan berhasil dihapus.');
    }

    public function saveFcmToken(Request $request)
    {
        $request->validate(['token' => 'required|string']);
        
        if (auth()->check()) {
            auth()->user()->update(['fcm_token' => $request->token]);
            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
    }

    public function clearChat()
    {
        $senderEmail = auth()->check() ? auth()->user()->email : 'guest_' . session()->getId();
        Message::where('sender_email', $senderEmail)
            ->orWhere('receiver_email', $senderEmail)
            ->delete();
        session()->regenerate();
        return response()->json(['status' => 'success']);
    }
}
