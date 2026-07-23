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

        $baseUrl = ($setting && $setting->wa_api_url) ? $setting->wa_api_url : env('OPEN_WA_API_URL', 'http://localhost:2785/api');
        $apiKey = ($setting && $setting->wa_api_key) ? $setting->wa_api_key : env('OPEN_WA_API_KEY');

        // Ambil semua sesi aktif yang berstatus CONNECTED dari database (Load Balancing)
        $activeSessions = \App\Models\WhatsAppSession::where('is_active', true)
            ->where('status', 'CONNECTED')
            ->get();

        // Self-healing: Cek sesi aktif yang berstatus offline jika sudah lebih dari 5 menit sejak update terakhir
        $needsHealingSessions = \App\Models\WhatsAppSession::where('is_active', true)
            ->where('status', '!=', 'CONNECTED')
            ->where('updated_at', '<', now()->subMinutes(5))
            ->get();

        if ($needsHealingSessions->isNotEmpty()) {
            $headers = [
                'Content-Type' => 'application/json',
            ];
            if ($apiKey) {
                $headers['Authorization'] = 'Bearer ' . $apiKey;
                $headers['X-API-Key'] = $apiKey;
            }

            foreach ($needsHealingSessions as $s) {
                try {
                    $statusResponse = \Illuminate\Support\Facades\Http::withHeaders($headers)
                        ->timeout(3)
                        ->get("{$baseUrl}/sessions/{$s->session_id}");

                    if ($statusResponse->successful()) {
                        $statusData = $statusResponse->json();
                        $rawStatus = $statusData['status'] ?? 'CONNECTED';
                        $isConnected = in_array(strtolower($rawStatus), ['connected', 'working', 'ready']);
                        
                        $newStatus = $isConnected ? 'CONNECTED' : $rawStatus;
                        $s->update(['status' => $newStatus]);
                        
                        if ($isConnected) {
                            \Illuminate\Support\Facades\Log::info("WA Queue Self-Healing: Sesi '{$s->label}' ({$s->session_id}) berhasil terhubung kembali secara otomatis.");
                            $activeSessions->push($s);
                        }
                    } else {
                        $s->touch(); // Update updated_at agar tidak dicek lagi selama 5 menit berikutnya
                    }
                } catch (\Exception $e) {
                    $s->touch(); // Update updated_at agar tidak dicek lagi selama 5 menit berikutnya
                }
            }
        }

        $session = null;
        $sessionId = null;
        $delaySeconds = rand(12, 18); // Jeda acak 12-18 detik per pesan untuk menghindari pembatasan WhatsApp & timeout OpenWA

        if ($activeSessions->isNotEmpty()) {
            // Acak urutan sesi untuk load balancing yang merata
            $shuffledSessions = $activeSessions->shuffle();

            foreach ($shuffledSessions as $s) {
                $lockKey = 'whatsapp-send-lock-' . $s->session_id;
                $lock = \Illuminate\Support\Facades\Cache::lock($lockKey, $delaySeconds);

                if ($lock->get()) {
                    $session = $s;
                    $sessionId = $s->session_id;
                    break;
                }
            }

            // Jika semua sesi aktif sedang terkunci (baru saja mengirim pesan)
            if (!$session) {
                // Rilis kembali job ke antrean untuk dicoba ulang dalam 3-5 detik
                $this->release(rand(3, 5));
                return;
            }
        } else {
            // Fallback ke session default dari setting atau env
            $sessionId = ($setting && $setting->wa_session_id) ? $setting->wa_session_id : env('OPEN_WA_SESSION_ID', 'default');
            
            // Gunakan lock cache untuk sesi default
            $lockKey = 'whatsapp-send-lock-' . $sessionId;
            $lock = \Illuminate\Support\Facades\Cache::lock($lockKey, $delaySeconds);

            if (!$lock->get()) {
                $this->release(rand(3, 5));
                return;
            }

            \Illuminate\Support\Facades\Log::warning("WA Queue: Tidak ada sesi WhatsApp aktif berstatus CONNECTED di database. Menggunakan sesi default: {$sessionId}");
        }
        
        $headers = [
            'Content-Type' => 'application/json',
        ];
        
        if ($apiKey) {
            $headers['Authorization'] = 'Bearer ' . $apiKey;
            $headers['X-API-Key'] = $apiKey;
        }

        try {
            $targetChatId = $this->phoneNumber;

            // Resolusi JID otomatis untuk mengatasi migrasi LID
            try {
                $rawNumber = explode('@', $this->phoneNumber)[0];
                
                // Cari JID asli dari WhatsApp (apakah masih @c.us atau sudah berpindah ke @lid)
                $chkContactResponse = \Illuminate\Support\Facades\Http::withHeaders($headers)
                    ->get("{$baseUrl}/sessions/{$sessionId}/contacts/check/{$rawNumber}");
                
                \Illuminate\Support\Facades\Log::info("WA Queue Diagnostic: Hasil check kontak {$rawNumber} -> " . $chkContactResponse->body());
                
                if ($chkContactResponse->successful()) {
                    $chkData = $chkContactResponse->json();
                    
                    // wppconnect/openwa mengembalikan 'whatsappId' sebagai JID asli yang valid
                    if (isset($chkData['whatsappId'])) {
                        $targetChatId = $chkData['whatsappId'];
                        \Illuminate\Support\Facades\Log::info("WA Queue: Mengubah tujuan ke JID baru: {$targetChatId}");
                    }
                }
            } catch (\Exception $diagEx) {
                \Illuminate\Support\Facades\Log::warning("WA Queue Diagnostic Warning: Gagal melakukan resolusi JID. Error: " . $diagEx->getMessage());
            }

            \Illuminate\Support\Facades\Log::info("WA Queue: Memulai pengiriman pesan ke {$targetChatId} via sesi {$sessionId}");

            $response = \Illuminate\Support\Facades\Http::withHeaders($headers)
                ->post("{$baseUrl}/sessions/{$sessionId}/messages/send-text", [
                    'chatId' => $targetChatId,
                    'text' => $this->message,
                ]);

            $responseData = $response->json();
            $isHttpSuccess = $response->successful();
            
            // Periksa status sukses di dalam body response json
            $isWaSuccess = is_array($responseData) && ($responseData['success'] ?? true) === true;
            $isSuccess = $isHttpSuccess && $isWaSuccess;
            $isOpenWaTimeout = isset($responseData['statusCode']) && $responseData['statusCode'] == 500;

            if ($isOpenWaTimeout) {
                // Lakukan validasi status sesi WhatsApp ke server OpenWA untuk memastikan apakah sesi benar-benar terhubung
                try {
                    $statusResponse = \Illuminate\Support\Facades\Http::withHeaders($headers)
                        ->timeout(5)
                        ->get("{$baseUrl}/sessions/{$sessionId}");

                    if ($statusResponse->successful()) {
                        $statusData = $statusResponse->json();
                        $status = $statusData['status'] ?? 'CONNECTED';
                        $isConnected = in_array(strtolower($status), ['connected', 'working', 'ready']);

                        if (!$isConnected) {
                            // Jika sesi terdeteksi tidak aktif/terhubung, maka pesan dipastikan gagal dikirim
                            if ($session) {
                                $session->update(['status' => 'NOT_READY']);
                            }
                            \Illuminate\Support\Facades\Log::warning("WA Queue: Terjadi error 500 dari OpenWA dan sesi '{$sessionId}' terdeteksi tidak terhubung (Status: {$status}). Merilis ulang pekerjaan.");
                            $this->release($session ? 10 : 300);
                            return;
                        }

                        // Sesi aktif/terhubung, tetapi OpenWA mengembalikan status 500 (Timeout).
                        // Untuk menghindari badai retry/duplikat, kita batasi maksimal 2x percobaan saja.
                        if ($this->attempts() < 2) {
                            \Illuminate\Support\Facades\Log::warning("WA Queue: Terjadi error 500 (Timeout) saat mengirim ke {$this->phoneNumber} via sesi {$sessionId} (Sesi aktif). Mencoba mengirim ulang sekali lagi (Percobaan ke-" . $this->attempts() . ").");
                            $this->release(30);
                            return;
                        } else {
                            \Illuminate\Support\Facades\Log::warning("WA Queue: Terjadi error 500 berulang kali untuk nomor {$this->phoneNumber} via sesi {$sessionId} (Meskipun sesi aktif). Menghentikan percobaan ulang untuk menghindari pesan duplikat.");
                            // Kita anggap selesai agar tidak mengirim duplikat lagi
                        }
                    } else {
                        // Jika gagal mengecek status sesi (misal API merespon error), anggap sesi bermasalah dan lempar exception
                        throw new \Exception("Server OpenWA merespon dengan error saat pengecekan sesi setelah error 500. Response: " . $statusResponse->body());
                    }
                } catch (\Exception $checkEx) {
                    if ($checkEx instanceof \Illuminate\Queue\MaxAttemptsExceededException || $checkEx instanceof \Illuminate\Queue\ReleasedException) {
                        throw $checkEx;
                    }
                    \Illuminate\Support\Facades\Log::error("WA Queue: Gagal memverifikasi status sesi setelah error 500. Error: " . $checkEx->getMessage());
                    throw new \Exception("Gagal mengirim pesan via OpenWA (Error 500) dan verifikasi sesi gagal: " . $checkEx->getMessage());
                }
            }

            if ($isSuccess || $isOpenWaTimeout) {
                if ($isOpenWaTimeout) {
                    \Illuminate\Support\Facades\Log::info("WA Queue: Pesan diproses OpenWA dengan status Pending/Timeout dari server WhatsApp. Sesi terpantau aktif. Response: " . $response->body());
                } else {
                    \Illuminate\Support\Facades\Log::info("WA Queue: Berhasil mengirim pesan ke {$this->phoneNumber} via sesi {$sessionId}. Response: " . $response->body());
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
                $errorMessage = $responseData['message'] ?? ($responseData['error'] ?? 'Unknown Error');

                // Cek jika nomor tidak valid atau tidak terdaftar di WhatsApp
                $isInvalidNumber = str_contains(strtolower($errorMessage), 'not registered') || 
                                    str_contains(strtolower($errorMessage), 'does not exist') ||
                                    str_contains(strtolower($errorMessage), 'invalid chatid') ||
                                    str_contains(strtolower($errorMessage), 'invalid number');
                
                if ($isInvalidNumber) {
                    \Illuminate\Support\Facades\Log::warning("WA Queue: Nomor {$this->phoneNumber} tidak valid atau tidak terdaftar di WhatsApp. Pengiriman dibatalkan. Response: " . $response->body());
                    return; // Selesai, jangan dicoba ulang agar tidak menyumbat antrean
                }

                // Jika kegagalan disebabkan sesi terputus (error 409 / Conflict / not connected)
                $isSessionError = $response->status() === 409 || 
                                  str_contains(strtolower($errorMessage), 'session is not connected') || 
                                  str_contains(strtolower($errorMessage), 'not connected') ||
                                  str_contains(strtolower($errorMessage), 'session offline') ||
                                  str_contains(strtolower($errorMessage), 'auth');

                if ($isSessionError) {
                    if ($session) {
                        // Tandai sesi tersebut sebagai NOT_READY di database agar tidak terpilih lagi
                        $session->update(['status' => 'NOT_READY']);
                        \Illuminate\Support\Facades\Log::warning("WA Queue: Sesi WhatsApp '{$session->label}' ({$sessionId}) terputus. Menandai sesi offline dan merilis ulang pekerjaan. Error: {$errorMessage}");
                    } else {
                        \Illuminate\Support\Facades\Log::warning("WA Queue: Sesi WhatsApp default ({$sessionId}) terputus saat mengirim ke {$this->phoneNumber}. Menunda pekerjaan selama 5 menit. Error: {$errorMessage}");
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
        
        // Jika dimulai dengan 620..., ubah menjadi 62...
        if (str_starts_with($clean, '620')) {
            $clean = '62' . substr($clean, 3);
        }
        // Jika dimulai dengan 0..., ubah menjadi 62...
        elseif (str_starts_with($clean, '0')) {
            $clean = '62' . substr($clean, 1);
        }
        // Jika dimulai dengan 8... (dan bukan 628...), ubah menjadi 628...
        elseif (str_starts_with($clean, '8')) {
            $clean = '62' . $clean;
        }
        
        // Jika belum ada akhiran @c.us, tambahkan
        if (!str_contains($clean, '@c.us')) {
            $clean .= '@c.us';
        }
        
        return $clean;
    }
}
