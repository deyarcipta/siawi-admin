<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FcmService
{
    /**
     * Send Push Notification to all active devices registered under a student (Multi-Device).
     *
     * @param \App\Models\Siswa|int $siswa
     * @param string $title
     * @param string $body
     * @param array $data
     * @return int Number of devices sent to
     */
    public static function sendToSiswa($siswa, $title, $body, $data = [])
    {
        if (is_numeric($siswa)) {
            $siswa = \App\Models\Siswa::with('fcmTokens')->find($siswa);
        }

        if (!$siswa) {
            return 0;
        }

        // Ambil semua token dari tabel multi-device
        $tokens = [];
        if ($siswa->relationLoaded('fcmTokens') || method_exists($siswa, 'fcmTokens')) {
            $tokens = $siswa->fcmTokens->pluck('fcm_token')->filter()->toArray();
        }

        // Fallback jika belum ada di tabel multi-device, gunakan fcm_token di tabel siswa
        if (empty($tokens) && !empty($siswa->fcm_token)) {
            $tokens[] = $siswa->fcm_token;
        }

        $sentCount = 0;
        foreach (array_unique($tokens) as $token) {
            if (self::sendNotification($token, $title, $body, $data)) {
                $sentCount++;
            }
        }

        return $sentCount;
    }

    /**
     * Send Push Notification using Firebase Cloud Messaging HTTP v1 API.
     *
     * @param string $deviceToken
     * @param string $title
     * @param string $body
     * @param array $data
     * @return bool
     */
    public static function sendNotification($deviceToken, $title, $body, $data = [])
    {
        try {
            if (empty($deviceToken)) {
                return false;
            }

            $keyPath = storage_path('app/firebase_credentials.json');

            if (!file_exists($keyPath)) {
                Log::warning('FCM: firebase_credentials.json file not found at ' . $keyPath . '. Skip sending notification.');
                return false;
            }

            $accessToken = self::getGoogleAccessToken($keyPath);
            if (!$accessToken) {
                Log::error('FCM: Failed to retrieve Google Access Token.');
                return false;
            }

            $key = json_decode(file_get_contents($keyPath), true);
            $projectId = $key['project_id'] ?? null;

            if (!$projectId) {
                Log::error('FCM: project_id not found in credentials.');
                return false;
            }

            $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

            $payload = [
                'message' => [
                    'token' => $deviceToken,
                    'notification' => [
                        'title' => $title,
                        'body' => $body,
                    ],
                    'android' => [
                        'notification' => [
                            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                            'sound' => 'default',
                        ]
                    ],
                ]
            ];

            if (!empty($data)) {
                $payload['message']['data'] = array_map('strval', $data);
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ])->post($url, $payload);

            if ($response->successful()) {
                Log::info("FCM: Push notification sent to token: $deviceToken");
                return true;
            }

            $resBody = $response->json();
            $errorCode = $resBody['error']['details'][0]['errorCode'] ?? ($resBody['error']['message'] ?? null);

            // Jika token sudah kedaluwarsa, aplikasi di-uninstall, atau tidak terdaftar lagi
            if ($response->status() === 404 || $errorCode === 'UNREGISTERED' || $errorCode === 'NotRegistered') {
                \App\Models\SiswaFcmToken::where('fcm_token', $deviceToken)->orWhere('token_hash', md5($deviceToken))->delete();
                \App\Models\Siswa::where('fcm_token', $deviceToken)->update(['fcm_token' => null]);
                Log::warning("FCM Self-Healing: Token perangkat sudah tidak terdaftar/UNREGISTERED ({$deviceToken}). Token otomatis dibersihkan dari database.");
                return false;
            }

            Log::error('FCM Send Error: ' . $response->body());
        } catch (\Throwable $e) {
            Log::error('FCM Send Exception: ' . $e->getMessage());
        }

        return false;
    }

    /**
     * Generate OAuth2 Access Token using Google Service Account Credentials.
     *
     * @param string $keyFilepath
     * @return string|null
     */
    private static function getGoogleAccessToken($keyFilepath)
    {
        $key = json_decode(file_get_contents($keyFilepath), true);
        if (!isset($key['client_email']) || !isset($key['private_key'])) {
            return null;
        }

        $header = json_encode(['alg' => 'RS256', 'typ' => 'JWT']);
        $now = time();
        $payload = json_encode([
            'iss' => $key['client_email'],
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            'aud' => 'https://oauth2.googleapis.com/token',
            'exp' => $now + 3600,
            'iat' => $now
        ]);

        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

        $signature = '';
        if (!openssl_sign($base64UrlHeader . "." . $base64UrlPayload, $signature, $key['private_key'], 'SHA256')) {
            return null;
        }
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

        try {
            $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $jwt
            ]);

            if ($response->successful()) {
                return $response->json()['access_token'] ?? null;
            }
        } catch (\Exception $e) {
            Log::error('FCM Token Request Exception: ' . $e->getMessage());
        }

        return null;
    }
}
