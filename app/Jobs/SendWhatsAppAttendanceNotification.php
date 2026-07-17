<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendWhatsAppAttendanceNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $phoneNumber;
    protected $message;

    /**
     * Create a new job instance.
     */
    public function __construct(string $phoneNumber, string $message)
    {
        $this->phoneNumber = $this->formatWhatsAppNumber($phoneNumber);
        $this->message = $message;
    }

    /**
     * Tentukan batas waktu maksimal percobaan pengerjaan job (2 jam).
     */
    public function retryUntil(): \DateTime
    {
        return now()->addHours(2);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $setting = \App\Models\Setting::first();
        
        // Periksa kembali status toggle di queue worker sebelum eksekusi
        if ($setting && !$setting->wa_status) {
            \Illuminate\Support\Facades\Log::info("WA Queue: Notifikasi WA dinonaktifkan di pengaturan. Menolak pekerjaan.");
            return;
        }

        // Pembatasan kecepatan pengiriman (Rate Limiting) secara global
        $limitPerMinute = ($setting && isset($setting->wa_rate_limit)) ? (int) $setting->wa_rate_limit : 10;
        $delaySeconds = 60 / $limitPerMinute;

        // Gunakan lock cache untuk memastikan jeda antar pesan minimal $delaySeconds detik
        $lockKey = 'whatsapp-send-lock';
        $lock = \Illuminate\Support\Facades\Cache::lock($lockKey, (int) ceil($delaySeconds));

        if (!$lock->get()) {
            // Jika gagal mendapatkan lock, kembalikan job ke antrean dengan penundaan
            $this->release((int) ceil($delaySeconds));
            return;
        }

        $baseUrl = ($setting && $setting->wa_api_url) ? $setting->wa_api_url : env('OPEN_WA_API_URL', 'http://localhost:2785/api');
        $apiKey = ($setting && $setting->wa_api_key) ? $setting->wa_api_key : env('OPEN_WA_API_KEY');
        $sessionId = ($setting && $setting->wa_session_id) ? $setting->wa_session_id : env('OPEN_WA_SESSION_ID', 'default');
        
        $headers = [
            'Content-Type' => 'application/json',
        ];
        
        if ($apiKey) {
            $headers['Authorization'] = 'Bearer ' . $apiKey;
            $headers['X-API-Key'] = $apiKey;
        }

        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders($headers)
                ->post("{$baseUrl}/sessions/{$sessionId}/messages/send-text", [
                    'chatId' => $this->phoneNumber,
                    'text' => $this->message,
                ]);

            $responseData = $response->json();
            $isSuccess = $response->successful();
            $isOpenWaTimeout = isset($responseData['statusCode']) && $responseData['statusCode'] == 500;

            if ($isSuccess || $isOpenWaTimeout) {
                if ($isOpenWaTimeout) {
                    \Illuminate\Support\Facades\Log::info("WA Queue: Pesan diproses OpenWA dengan status Pending/Timeout dari server WhatsApp (Pesan biasanya tetap terkirim).");
                }
            } else {
                // Jika kegagalan disebabkan sesi terputus (error 409 / Conflict)
                if ($response->status() === 409 || (isset($responseData['message']) && str_contains($responseData['message'], 'Session is not connected'))) {
                    // Tulis log peringatan ringan & tunda eksekusi selama 5 menit agar memberi waktu bagi admin untuk scan QR Code baru
                    \Illuminate\Support\Facades\Log::warning("WA Queue: Sesi WhatsApp terputus/tidak siap saat mengirim ke {$this->phoneNumber}. Menunda pekerjaan selama 5 menit.");
                    $this->release(300);
                    return;
                }

                \Illuminate\Support\Facades\Log::error("WA Queue: Gagal total mengirim pesan ke {$this->phoneNumber}. Response: " . $response->body());
                throw new \Exception("Gagal mengirim pesan WhatsApp via OpenWA: " . $response->body());
            }

        } catch (\Exception $e) {
            // Jika exception merupakan ReleasedException dari Laravel queue, rethrow agar Laravel menanganinya
            if ($e instanceof \Illuminate\Queue\MaxAttemptsExceededException || $e instanceof \Illuminate\Queue\ReleasedException) {
                throw $e;
            }

            \Illuminate\Support\Facades\Log::error("WA Queue Exception: " . $e->getMessage());
            // Untuk error lainnya (misal timeout koneksi ke server OpenWA), coba lagi dalam 60 detik
            $this->release(60);
        }
    }

    /**
     * Dipanggil ketika job gagal secara permanen (misal setelah 2 jam terlampaui).
     */
    public function failed(\Throwable $exception): void
    {
        \Illuminate\Support\Facades\Log::error("WA Queue: Pengiriman pesan ke {$this->phoneNumber} GAGAL PERMANEN setelah dicoba selama 2 jam. Error: " . $exception->getMessage());
    }

    /**
     * Format phone number to open-wa format
     */
    protected function formatWhatsAppNumber(string $number): string
    {
        // Bersihkan karakter non-digit
        $clean = preg_replace('/[^0-9]/', '', $number);
        
        // Ubah awalan 0 ke 62 (Indonesia)
        if (str_starts_with($clean, '0')) {
            $clean = '62' . substr($clean, 1);
        }
        
        // Jika belum ada akhiran @c.us, tambahkan
        if (!str_contains($clean, '@c.us')) {
            $clean .= '@c.us';
        }
        
        return $clean;
    }
}
