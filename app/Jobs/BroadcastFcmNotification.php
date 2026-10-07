<?php

namespace App\Jobs;

use App\Services\FcmService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class BroadcastFcmNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $title;
    public $body;
    public $data;

    /**
     * Jumlah percobaan maksimal jika gagal.
     */
    public $tries = 3;

    /**
     * Create a new job instance.
     *
     * @param string $title
     * @param string $body
     * @param array $data
     */
    public function __construct(string $title, string $body, array $data = [])
    {
        $this->title = $title;
        $this->body = $body;
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            FcmService::broadcastToAllStudents($this->title, $this->body, $this->data);
        } catch (\Throwable $e) {
            Log::error('Job BroadcastFcmNotification error: ' . $e->getMessage());
        }
    }
}
