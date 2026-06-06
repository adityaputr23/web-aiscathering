<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{
    public function chat(Request $request)
    {
        $message = strtolower($request->input('message'));
        $chatbotComKey = config('services.chatbot_com.key');
        $difyKey = config('services.dify.key');
        $geminiKey = config('services.gemini.key');

        // 1. Try ChatBot.com
        if ($chatbotComKey) {
            try {
                $response = Http::withoutVerifying()->timeout(30)
                    ->withToken($chatbotComKey)
                    ->post("https://api.chatbot.com/v2/query", [
                        'query' => $message,
                        'sessionId' => 'guest-session',
                    ]);

                if ($response->successful()) {
                    $reply = $response->json()['result']['fulfillment'][0]['message'] ?? null;
                    if ($reply) {
                        return response()->json(['reply' => $reply]);
                    }
                }
            } catch (\Exception $e) {
                \Log::error('ChatBot.com Controller Error: ' . $e->getMessage());
            }
        }

        // Fallback Mock Logic ONLY if both keys are missing
        if (!$difyKey && (!$geminiKey || $geminiKey === 'YOUR_GEMINI_API_KEY')) {
            $reply = "Halo! (Mode Demo) ";
            
            if (str_contains($message, 'menu')) {
                $reply .= "Kami punya Nasi Kotak (25rb), Tumpeng (45rb), dan Prasmanan (85rb). Cek di sekson Menu ya!";
            } elseif (str_contains($message, 'harga')) {
                $reply .= "Harga mulai dari Rp 15.000 untuk Snack Box sampai Rp 85.000 untuk Prasmanan Premium.";
            } elseif (str_contains($message, 'lokasi') || str_contains($message, 'alamat')) {
                $reply .= "Kami berlokasi di Singkawang. Kami melayani pengiriman ke seluruh area kota!";
            } else {
                $reply .= "Ada yang bisa saya bantu tentang katering AISH? Saya bisa info soal menu, harga, atau lokasi.";
            }

            return response()->json(['reply' => $reply]);
        }

        $replied = false;

        // 1. Try Dify AI
        if ($difyKey) {
            try {
                $response = Http::withoutVerifying()->timeout(30)
                    ->withToken($difyKey)
                    ->post("https://api.dify.ai/v1/chat-messages", [
                        'inputs' => [],
                        'query' => $message,
                        'response_mode' => 'blocking',
                        'user' => 'guest-user',
                    ]);

                if ($response->successful()) {
                    $reply = $response->json()['answer'] ?? null;
                    if ($reply) {
                        return response()->json(['reply' => $reply]);
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Dify Chatbot Error: ' . $e->getMessage());
            }
        }

        // 2. Fallback to Gemini
        try {
            $apiKey = config('services.gemini.key');
            $response = Http::withoutVerifying()->withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1/models/gemini-1.5-flash:generateContent?key={$apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => "Anda adalah asisten AI katering yang cerdas untuk 'AISH Catering'. 
                            Informasi Produk & Harga: 
                            1. Paket Nasi Kotak Komplit (Rp 25.000) - Ayam Bakar, Nasi, Lauk. Paling pas untuk makan siang kantor.
                            2. Tumpeng Mini Nusantara (Rp 45.000) - Min order 10 pax. Cocok untuk syukuran kecil.
                            3. Prasmanan Premium (Rp 85.000) - Min order 100 pax. Cocok untuk pernikahan mewah.
                            4. Snack Box Premium (Rp 15.000) - 3 kue + air. Rekomendasi untuk rapat/seminar.
                            5. Tumpeng Besar (Rp 650.000) - Untuk 20 orang. Cocok untuk grand opening.
                            6. Coffee Break (Rp 30.000) - Kopi/Teh + 4 snack.
 
                            Panduan Rekomendasi:
                            - Pernikahan: Prasmanan Premium.
                            - Rapat Kantor: Snack Box atau Coffee Break.
                            - Syukuran: Tumpeng Mini atau Tumpeng Besar.
                            - Makan Siang: Nasi Kotak Komplit.
 
                            Lokasi: Singkawang.
                            Kualitas: Higienis, Halal, Terpercaya sejak 2015.
                            User: {$message}"]
                        ]
                    ]
                ]
            ]);
 
            $data = $response->json();
            $reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Maaf, saya tidak mengerti. Bisa diulangi?';
 
            return response()->json([
                'reply' => $reply
            ]);
 
        } catch (\Exception $e) {
            \Log::error('Chatbot Error: ' . $e->getMessage());
            return response()->json([
                'reply' => 'Ada gangguan koneksi ke otak AI saya. Silakan coba lagi nanti ya! 🙏'
            ]);
        }
    }
}

