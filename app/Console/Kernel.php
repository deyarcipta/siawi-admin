<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Heartbeat tracker untuk mendeteksi apakah background scheduler sedang berjalan aktif
        $schedule->call(function () {
            \Illuminate\Support\Facades\Cache::put('siawi_scheduler_last_heartbeat', now()->timestamp, 3600);
        })->everyMinute();

        $schedule->command('clear:data-txt')->dailyAt('00:00')->timezone('Asia/Jakarta');
        // Notifikasi Rekap Absensi Terjadwal Dinamis (Walas Mingguan / Ortu Bulanan sesuai Setting Admin)
        $schedule->command('absensi:kirim-rekap-terjadwal')->hourly()->timezone('Asia/Jakarta');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
