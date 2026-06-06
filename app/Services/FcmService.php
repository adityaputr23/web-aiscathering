<?php

namespace App\Services;

use Google\Auth\Credentials\ServiceAccountCredentials;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FcmService
{
    protected $serviceAccountPath;
    protected $projectId;

    public function __construct()
    {
        $this->serviceAccountPath = storage_path('app/firebase/service-account.json');
        
        if (file_exists($this->serviceAccountPath)) {
            $json = json_decode(file_get_contents($this->serviceAccountPath), true);
            $this->projectId = $json['project_id'] ?? null;
        }
    }

    /**
     * Mendapatkan Access Token dari Google OAuth2
     */
    protected function getAccessToken()
    {
        try {
            $credentials = new ServiceAccountCredentials(
                'https://www.googleapis.com/auth/firebase.messaging',
                $this->serviceAccountPath
            );

            $token = $credentials->fetchAuthToken();
            return $token['access_token'] ?? null;
        } catch (\Exception $e) {
            Log::error("FCM Token Error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Mengirim Push Notification ke Token Tertentu
     */
    public function sendPush($token, $title, $body, $data = [])
    {
        if (!$this->projectId) {
            Log::error("FCM Error: Project ID not found in service account.");
            return false;
        }

        $accessToken = $this->getAccessToken();
        if (!$accessToken) return false;

        $url = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";

        $payload = [
            'message' => [
                'token' => $token,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                ],
                'data' => array_merge($data, [
                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK', // Untuk mobile
                ]),
                'webpush' => [
                    'fcm_options' => [
                        'link' => url('/admin/chats')
                    ]
                ]
            ]
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ])->post($url, $payload);

            if ($response->successful()) {
                return true;
            } else {
                Log::error("FCM Send Error: " . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error("FCM Exception: " . $e->getMessage());
            return false;
        }
    }
}
