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
        
        // Periksa kembali status toggle di queue worker sebelum eksekusi (jika wa_status = 0 berarti nonaktif)
        if ($setting && $setting->wa_status == 0) {
            \Illuminate\Support\Facades\Log::info("WA Queue: Notifikasi WA dinonaktifkan di pengaturan. Menolak pekerjaan.");
            return;
        }

        // Periksa apakah sistem sedang berada dalam Mode Istirahat (Sleep Mode) setelah kirim 50 pesan
        if (\Illuminate\Support\Facades\Cache::has('whatsapp-sleep-lock')) {
            // Rilis kembali job ke antrean dengan penundaan acak (10 hingga 20 detik)
            $this->release(rand(10, 20));
            return;
        }

        // Jeda Acak (Random Delay): Jeda acak antara 3 sampai 7 detik per pesan
        $delaySeconds = rand(3, 7);

        // Gunakan lock cache untuk memastikan jeda antar pesan minimal $delaySeconds detik
        $lockKey = 'whatsapp-send-lock';
        $lock = \Illuminate\Support\Facades\Cache::lock($lockKey, $delaySeconds);

        if (!$lock->get()) {
            // Jika gagal mendapatkan lock, kembalikan job ke antrean dengan penundaan acak (3-7 detik)
            $this->release(rand(3, 7));
            return;
        }

        $baseUrl = ($setting && $setting->wa_api_url) ? $setting->wa_api_url : env('OPEN_WA_API_URL', 'http://localhost:2785/api');
        $apiKey = ($setting && $setting->wa_api_key) ? $setting->wa_api_key : env('OPEN_WA_API_KEY');
        
        // Ambil sesi aktif yang berstatus CONNECTED secara acak (Load Balancing)
        $session = \App\Models\WhatsAppSession::where('is_active', true)
            ->where('status', 'CONNECTED')
            ->inRandomOrder()
            ->first();

        if (!$session) {
            // Fallback ke session default dari setting atau env
            $sessionId = ($setting && $setting->wa_session_id) ? $setting->wa_session_id : env('OPEN_WA_SESSION_ID', 'default');
            \Illuminate\Support\Facades\Log::warning("WA Queue: Tidak ada sesi WhatsApp aktif berstatus CONNECTED di database. Menggunakan sesi default: {$sessionId}");
        } else {
            $sessionId = $session->session_id;
        }
        
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
                    \Illuminate\Support\Facades\Log::info("WA Queue: Pesan diproses OpenWA dengan status Pending/Timeout dari server WhatsApp. Response: " . $response->body());
                }

                // Peningkatan counter pesan terkirim (Sleep Mode Tracker)
                $count = \Illuminate\Support\Facades\Cache::increment('whatsapp-sent-count');
                if ($count >= 50) {
                    $sleepSeconds = rand(60, 120);
                    \Illuminate\Support\Facades\Cache::put('whatsapp-sleep-lock', true, $sleepSeconds);
                    \Illuminate\Support\Facades\Cache::put('whatsapp-sent-count', 0);
                    \Illuminate\Support\Facades\Log::warning("WA Queue: Berhasil mengirim 50 pesan. Memasuki mode istirahat (Sleep Mode) selama {$sleepSeconds} detik untuk menghindari pemblokiran.");
                }
            } else {
                // Jika kegagalan disebabkan sesi terputus (error 409 / Conflict / not connected)
                if ($response->status() === 409 || (isset($responseData['message']) && str_contains($responseData['message'], 'Session is not connected'))) {
                    if ($session) {
                        // Tandai sesi tersebut sebagai NOT_READY di database agar tidak terpilih lagi
                        $session->update(['status' => 'NOT_READY']);
                        \Illuminate\Support\Facades\Log::warning("WA Queue: Sesi WhatsApp '{$session->label}' ({$sessionId}) terputus. Menandai sesi offline dan merilis ulang pekerjaan.");
                    } else {
                        \Illuminate\Support\Facades\Log::warning("WA Queue: Sesi WhatsApp default ({$sessionId}) terputus saat mengirim ke {$this->phoneNumber}. Menunda pekerjaan selama 5 menit.");
                    }
                    
                    // Jika menggunakan sesi multi-session, tunda 10 detik agar job dicoba dengan nomor lain
                    $this->release($session ? 10 : 300);
                    return;
                }

                \Illuminate\Support\Facades\Log::error("WA Queue: Gagal total mengirim pesan ke {$this->phoneNumber} via sesi {$sessionId}. Response: " . $response->body());
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
