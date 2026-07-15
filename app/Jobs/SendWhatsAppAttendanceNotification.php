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
     * Execute the job.
     */
    public function handle(): void
    {
        $baseUrl = env('OPEN_WA_API_URL', 'http://localhost:2000');
        $apiKey = env('OPEN_WA_API_KEY');
        
        $headers = [
            'Content-Type' => 'application/json',
        ];
        
        if ($apiKey) {
            $headers['Authorization'] = 'Bearer ' . $apiKey;
            $headers['X-API-Key'] = $apiKey;
        }

        try {
            // 1. Mulai simulasi mengetik (typing)
            \Illuminate\Support\Facades\Log::info("WA Queue: Mengirim sinyal typing ke {$this->phoneNumber}");
            \Illuminate\Support\Facades\Http::withHeaders($headers)
                ->post("{$baseUrl}/simulateTyping", [
                    'chatId' => $this->phoneNumber,
                    'on' => true,
                    'args' => [
                        $this->phoneNumber,
                        true
                    ]
                ]);

            // 2. Durasi mengetik (typing) selama 3 detik
            sleep(3);

            // 3. Hentikan simulasi mengetik (typing)
            \Illuminate\Support\Facades\Http::withHeaders($headers)
                ->post("{$baseUrl}/simulateTyping", [
                    'chatId' => $this->phoneNumber,
                    'on' => false,
                    'args' => [
                        $this->phoneNumber,
                        false
                    ]
                ]);

            // 4. Kirim pesan text
            \Illuminate\Support\Facades\Log::info("WA Queue: Mengirim pesan ke {$this->phoneNumber}");
            $response = \Illuminate\Support\Facades\Http::withHeaders($headers)
                ->post("{$baseUrl}/sendText", [
                    'chatId' => $this->phoneNumber,
                    'text' => $this->message,
                    'args' => [
                        $this->phoneNumber,
                        $this->message
                    ]
                ]);

            if ($response->failed()) {
                \Illuminate\Support\Facades\Log::error("WA Queue: Gagal mengirim pesan ke {$this->phoneNumber}. Response: " . $response->body());
            } else {
                \Illuminate\Support\Facades\Log::info("WA Queue: Berhasil mengirim pesan ke {$this->phoneNumber}");
            }

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("WA Queue Exception: " . $e->getMessage());
        }
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
