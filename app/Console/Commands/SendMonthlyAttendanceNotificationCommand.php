<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Kelas;
use App\Services\LaporanAbsensiBulananService;
use Carbon\Carbon;

class SendMonthlyAttendanceNotificationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'absensi:kirim-rekap-bulanan 
                            {--kelas= : ID Kelas tertentu} 
                            {--target=all : Target pengiriman: orangtua, wali, all}
                            {--bulan= : Bulan rekap (1-12)}
                            {--tahun= : Tahun rekap (YYYY)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim laporan rekap absensi bulanan siswa ke WhatsApp Orang Tua dan Wali Kelas';

    /**
     * Execute the console command.
     */
    public function handle(LaporanAbsensiBulananService $service): int
    {
        $this->info("=== Memulai Pengiriman Rekap Absensi Bulanan ===");

        $bulan = $this->option('bulan');
        $tahun = $this->option('tahun') ?? date('Y');

        if ($bulan) {
            $date = Carbon::createFromDate($tahun, $bulan, 1);
            $awalBulan = $date->copy()->startOfMonth()->format('Y-m-d');
            $akhirBulan = $date->copy()->endOfMonth()->format('Y-m-d');
        } else {
            $awalBulan = Carbon::now()->startOfMonth()->format('Y-m-d');
            $akhirBulan = Carbon::now()->endOfMonth()->format('Y-m-d');
        }

        $this->info("Periode Rekap: {$awalBulan} s/d {$akhirBulan}");

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
                $resOrtu = $service->dispatchNotifikasiOrangTua($kelas->id_kelas, $awalBulan, $akhirBulan);
                $totalQueuedOrtu += $resOrtu['queued'];
                $this->info(" -> [Orang Tua] {$resOrtu['queued']} dijadwalkan, {$resOrtu['skipped']} dilewati.");
            }

            if ($target === 'all' || $target === 'wali') {
                $resWali = $service->dispatchNotifikasiWaliKelas($kelas->id_kelas, $awalBulan, $akhirBulan);
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
