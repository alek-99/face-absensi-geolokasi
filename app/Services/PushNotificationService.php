<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

// Optional: minishlink/web-push library (composer require minishlink/web-push)
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription as WebPushSubscription;

// Dibutuhkan untuk FCM HTTP v1 (composer require google/apiclient)
use Google\Client as GoogleClient;

class PushNotificationService
{
    /**
     * Kirim notifikasi: menerima baik FCM token (string) maupun subscription WebPush (array/object)
     * - Jika $target adalah string => dianggap FCM token
     * - Jika $target adalah array/object yang berisi endpoint => Web Push
     */
    public static function sendToUser($target, $title, $body, $actionUrl = null)
    {
        try {
            // Jika target berupa array / object dan mengandung endpoint -> kirim WebPush
            if (is_array($target) || is_object($target)) {
                $sub = (array) $target;
                if (isset($sub['endpoint'])) {
                    return self::sendWebPush($sub, $title, $body, $actionUrl);
                }
            }

            // Fallback ke FCM (string token)
            if (is_string($target) && !empty($target)) {
                return self::sendFcm($target, $title, $body, $actionUrl);
            }

            return false;
        } catch (\Exception $e) {
            Log::error('PushNotificationService Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * ====================================================================
     * FCM — migrasi ke HTTP v1 API.
     *
     * PENTING: Endpoint lama "https://fcm.googleapis.com/fcm/send" dengan
     * header "Authorization: key=<FCM_SERVER_KEY>" SUDAH DIMATIKAN GOOGLE
     * sejak 22 Juli 2024. Semua request ke sana pasti gagal (biasanya 404),
     * berapa pun benar server key-nya. Itu sebabnya notifikasi FCM tidak
     * pernah sampai.
     *
     * Solusi: pakai FCM HTTP v1 API yang butuh OAuth2 access token dari
     * Service Account JSON (didapat dari Firebase Console > Project
     * Settings > Service Accounts > Generate new private key).
     *
     * Setup yang diperlukan:
     * 1. composer require google/apiclient
     * 2. Simpan file service-account.json di storage/app/firebase/service-account.json
     *    (JANGAN commit ke git — tambahkan ke .gitignore)
     * 3. Tambahkan di .env:
     *    FIREBASE_PROJECT_ID=nama-project-firebase-kamu
     * ====================================================================
     */
    protected static function getFcmAccessToken(): ?string
    {
        $credentialsPath = storage_path('app/firebase/service-account.json');

        if (!file_exists($credentialsPath)) {
            Log::warning('FCM service account JSON tidak ditemukan di ' . $credentialsPath);
            return null;
        }

        try {
            $client = new GoogleClient();
            $client->setAuthConfig($credentialsPath);
            $client->addScope('https://www.googleapis.com/auth/firebase.messaging');

            $token = $client->fetchAccessTokenWithAssertion();

            if (isset($token['error'])) {
                Log::error('FCM OAuth2 Error: ' . json_encode($token));
                return null;
            }

            return $token['access_token'] ?? null;
        } catch (\Exception $e) {
            Log::error('FCM OAuth2 Exception: ' . $e->getMessage());
            return null;
        }
    }

    protected static function sendFcm(string $fcmToken, string $title, string $body, ?string $actionUrl = null): bool
    {
        if (!$fcmToken) return false;

        $projectId = env('FIREBASE_PROJECT_ID');
        if (!$projectId) {
            Log::warning('FIREBASE_PROJECT_ID tidak ditemukan di .env');
            return false;
        }

        $accessToken = self::getFcmAccessToken();
        if (!$accessToken) {
            return false;
        }

        $payload = [
            'message' => [
                'token' => $fcmToken,
                'notification' => [
                    'title' => $title,
                    'body'  => $body,
                ],
                'webpush' => [
                    'fcm_options' => [
                        'link' => $actionUrl ?? url('/'),
                    ],
                    'notification' => [
                        'icon' => asset('icons/icon-192x192.png'),
                    ],
                ],
                'data' => [
                    'url' => $actionUrl ?? url('/'),
                ],
            ],
        ];

        try {
            $response = Http::withToken($accessToken)
                ->post("https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send", $payload);

            if (!$response->successful()) {
                Log::error('FCM v1 Push Error: status=' . $response->status() . ' body=' . $response->body());
                return false;
            }

            return true;
        } catch (\Exception $e) {
            Log::error('FCM v1 Push Exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * ====================================================================
     * WebPush — perbaikan contentEncoding.
     *
     * Sebelumnya contentEncoding di-hardcode ke 'aesgcm', padahal browser
     * modern (Chrome, Edge, Firefox terbaru) mengirim subscription dengan
     * encoding 'aes128gcm'. Kalau dipaksa 'aesgcm', push service akan
     * menolak payload dan notifikasi tidak pernah sampai — walau VAPID key
     * dan subscription-nya sudah benar.
     *
     * Perbaikan: ambil contentEncoding dari data subscription itu sendiri
     * (browser sudah mengirimkannya saat subscribe), fallback ke
     * 'aes128gcm' kalau tidak ada.
     *
     * CATATAN: pastikan saat menyimpan subscription di sisi client (JS),
     * kamu ikut menyimpan field contentEncoding, contoh:
     *   const contentEncoding = (PushManager.supportedContentEncodings || ['aes128gcm'])[0];
     *   // lalu kirim bersama subscription.toJSON() ke server
     * ====================================================================
     */
    protected static function sendWebPush($subscription, string $title, string $body, ?string $actionUrl = null): bool
    {
        $vapidPublic  = env('VAPID_PUBLIC_KEY');
        $vapidPrivate = env('VAPID_PRIVATE_KEY');
        $vapidSubject = env('VAPID_SUBJECT', 'mailto:admin@localhost');

        if (empty($vapidPublic) || empty($vapidPrivate)) {
            Log::warning('VAPID keys not configured in .env');
            return false;
        }

        try {
            // AMAN: Jika subscription berupa string JSON, kita decode ke array terlebih dahulu
            if (is_string($subscription)) {
                $subscription = json_decode($subscription, true);
            } elseif (is_object($subscription)) {
                $subscription = (array) $subscription;
            }

            // Validasi struktur minimal Web Push
            if (!isset($subscription['endpoint'])) {
                Log::warning('WebPush gagal: Data subscription tidak memiliki endpoint.', ['data' => $subscription]);
                return false;
            }

            $auth = [
                'VAPID' => [
                    'subject'    => $vapidSubject,
                    'publicKey'  => $vapidPublic,
                    'privateKey' => $vapidPrivate,
                ]
            ];

            $webPush = new WebPush($auth);

            // Mengambil keys dengan aman jika berbentuk array bersarang
            $keys    = $subscription['keys'] ?? [];
            $p256dh  = $keys['p256dh'] ?? ($subscription['p256dh'] ?? null);
            $authKey = $keys['auth']   ?? ($subscription['auth'] ?? null);

            // PERBAIKAN UTAMA: pakai contentEncoding dari subscription,
            // fallback ke standar terkini 'aes128gcm' (BUKAN 'aesgcm' lagi)
            $contentEncoding = $subscription['contentEncoding'] ?? 'aes128gcm';

            $webSub = WebPushSubscription::create([
                'endpoint'        => $subscription['endpoint'],
                'publicKey'       => $p256dh,
                'contentEncoding' => $contentEncoding,
                'authToken'       => $authKey,
            ]);

            $payload = json_encode([
                'title' => $title,
                'body'  => $body,
                'icon'  => asset('icons/icon-192x192.png'),
                'url'   => $actionUrl ?? url('/'),
            ]);

            $webPush->sendOneNotification($webSub, $payload);

            $success = false;
            foreach ($webPush->flush() as $report) {
                $endpoint = method_exists($report, 'getEndpoint') ? $report->getEndpoint() : $subscription['endpoint'];

                if ($report->isSuccess()) {
                    $success = true;
                } else {
                    // Log alasan asli kegagalan agar mudah didiagnosis
                    // (misal: expired subscription, invalid encoding, dsb.)
                    Log::warning('WebPush failed: ' . $report->getReason() . ' | endpoint: ' . $endpoint);

                    // Jika subscription sudah tidak valid (410 Gone / 404),
                    // sebaiknya dihapus dari database supaya tidak dicoba lagi.
                    if (method_exists($report, 'isSubscriptionExpired') && $report->isSubscriptionExpired()) {
                        Log::info('WebPush subscription kadaluarsa, sebaiknya dihapus dari DB: ' . $endpoint);
                    }
                }
            }

            return $success;
        } catch (\Exception $e) {
            Log::error('WebPush Exception: ' . $e->getMessage());
            return false;
        }
    }
}