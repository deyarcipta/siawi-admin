<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\LaporanAbsensiBulananService;

class SendWeeklyAttendanceNotificationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'absensi:kirim-rekap-mingguan {--kelas= : ID Kelas tertentu} {--target=all : Target pengiriman: orangtua, wali, all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim laporan rekap absensi bulanan siswa ke WhatsApp Orang Tua dan Wali Kelas (Alias)';

    /**
     * Execute the console command.
     */
    public function handle(LaporanAbsensiBulananService $service): int
    {
        $this->info("Meneruskan ke perintah rekap bulanan (absensi:kirim-rekap-bulanan)...");
        return $this->call('absensi:kirim-rekap-bulanan', [
            '--kelas' => $this->option('kelas'),
            '--target' => $this->option('target'),
        ]);
    }
}
