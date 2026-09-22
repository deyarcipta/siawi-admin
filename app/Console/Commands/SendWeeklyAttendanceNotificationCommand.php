<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Kelas;
use App\Services\LaporanAbsensiMingguanService;
use Carbon\Carbon;

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
    protected $description = 'Kirim laporan rekap absensi mingguan siswa ke WhatsApp Orang Tua dan Wali Kelas';

    /**
     * Execute the console command.
     */
    public function handle(LaporanAbsensiMingguanService $service): int
    {
        $this->info("=== Memulai Pengiriman Rekap Absensi Mingguan ===");

        $seninPekanIni = Carbon::now()->startOfWeek(Carbon::MONDAY)->format('Y-m-d');
        $jumatPekanIni = Carbon::now()->startOfWeek(Carbon::MONDAY)->addDays(4)->format('Y-m-d');

        $idKelas = $this->option('kelas');
        $target = strtolower($this->option('target') ?? 'all');

        $daftarKelas = $idKelas ? Kelas::where('id_kelas', $idKelas)->get() : Kelas::all();

        if ($daftarKelas->isEmpty()) {
            $this->error("Tidak ada kelas yang ditemukan.");
            return Command::FAILURE;
        }

        $totalQueuedOrtu = 0;
        $totalQueuedWali = 0;

        foreach ($daftarKelas as $kelas) {
            $this->line("Memproses Kelas: {$kelas->nama_kelas}...");

            if ($target === 'all' || $target === 'orangtua') {
                $resOrtu = $service->dispatchNotifikasiOrangTua($kelas->id_kelas, $seninPekanIni, $jumatPekanIni);
                $totalQueuedOrtu += $resOrtu['queued'];
                $this->info(" -> [Orang Tua] {$resOrtu['queued']} dijadwalkan, {$resOrtu['skipped']} dilewati.");
            }

            if ($target === 'all' || $target === 'wali') {
                $resWali = $service->dispatchNotifikasiWaliKelas($kelas->id_kelas, $seninPekanIni, $jumatPekanIni);
                if ($resWali) {
                    $totalQueuedWali++;
                    $this->info(" -> [Wali Kelas] Dijadwalkan.");
                } else {
                    $this->warn(" -> [Wali Kelas] Dilewati (Nomor tidak valid/belum diatur).");
                }
            }
        }

        $this->info("=== Selesai! Total antrean Orang Tua: {$totalQueuedOrtu}, Wali Kelas: {$totalQueuedWali} ===");
        return Command::SUCCESS;
    }
}
