<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Setting;
use App\Models\Kelas;
use App\Services\LaporanAbsensiBulananService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CheckAndSendScheduledAttendanceNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'absensi:kirim-rekap-terjadwal 
                            {--force : Paksa jalankan tanpa memeriksa kecocokan jam/hari}
                            {--target=all : Target khusus: all, walas, orangtua}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Periksa pengaturan jadwal rekap WA dari database dan kirimkan notifikasi jika waktu sesuai';

    /**
     * Execute the console command.
     */
    public function handle(LaporanAbsensiBulananService $service): int
    {
        // Perbarui timestamp heartbeat
        \Illuminate\Support\Facades\Cache::put('siawi_scheduler_last_heartbeat', now()->timestamp, 3600);

        $this->info("=== Memeriksa Jadwal Notifikasi Rekap Absensi WhatsApp ===");

        $setting = Setting::first();
        if (!$setting) {
            $this->error("Tabel setting belum memiliki data.");
            return Command::FAILURE;
        }

        $rekapSettings = $setting->rekap_wa_settings;
        $force = $this->option('force');
        $target = strtolower($this->option('target') ?? 'all');

        $now = Carbon::now('Asia/Jakarta');
        $currentDayName = strtolower($now->format('l')); // 'friday', 'monday', etc.
        $currentDayNum = (string) $now->day;
        $isLastDayOfMonth = ($now->day === $now->copy()->endOfMonth()->day);
        $currentHour = $now->format('H'); // Cek per jam untuk keandalan scheduler

        $daftarKelas = Kelas::with('waliKelas')->get();

        // 1. PROSES WALI KELAS
        if ($target === 'all' || $target === 'walas') {
            $walasCfg = $rekapSettings['walas'] ?? [];
            $isWalasActive = $walasCfg['is_active'] ?? true;
            $walasFreq = $walasCfg['frequency'] ?? 'weekly';
            $walasDay = strtolower($walasCfg['day'] ?? 'friday');
            $walasTime = $walasCfg['time'] ?? '16:00';
            $walasHour = explode(':', $walasTime)[0] ?? '16';

            $shouldRunWalas = false;

            if ($force) {
                $shouldRunWalas = true;
            } elseif ($isWalasActive && $currentHour === $walasHour) {
                if ($walasFreq === 'weekly') {
                    if ($currentDayName === $walasDay) {
                        $shouldRunWalas = true;
                    }
                } elseif ($walasFreq === 'monthly') {
                    if ($walasDay === 'last_day' && $isLastDayOfMonth) {
                        $shouldRunWalas = true;
                    } elseif ($walasDay === $currentDayNum) {
                        $shouldRunWalas = true;
                    }
                }
            }

            if ($shouldRunWalas) {
                $this->info(">> [Wali Kelas] Memproses pengiriman rekap...");
                
                // Tentukan range tanggal
                if ($walasFreq === 'weekly') {
                    $start = $now->copy()->startOfWeek(Carbon::MONDAY)->format('Y-m-d');
                    $end = $now->copy()->format('Y-m-d');
                } else {
                    $start = $now->copy()->startOfMonth()->format('Y-m-d');
                    $end = $now->copy()->endOfMonth()->format('Y-m-d');
                }

                $totalSentWalas = 0;
                foreach ($daftarKelas as $kelas) {
                    $res = $service->dispatchNotifikasiWaliKelas($kelas->id_kelas, $start, $end);
                    if ($res) {
                        $totalSentWalas++;
                    }
                }

                $this->info(">> [Wali Kelas] Berhasil menjadwalkan notifikasi untuk {$totalSentWalas} kelas (Periode: {$start} s/d {$end}).");
                Log::info("Scheduler Rekap WA: Terkirim {$totalSentWalas} rekap Walas ({$walasFreq}).");
            } else {
                $this->line(".. [Wali Kelas] Belum waktunya dikirim (Jadwal: {$walasFreq} - {$walasDay} pukul {$walasTime}).");
            }
        }

        // 2. PROSES ORANG TUA
        if ($target === 'all' || $target === 'orangtua') {
            $ortuCfg = $rekapSettings['orangtua'] ?? [];
            $isOrtuActive = $ortuCfg['is_active'] ?? true;
            $ortuFreq = $ortuCfg['frequency'] ?? 'monthly';
            $ortuDay = strtolower($ortuCfg['day'] ?? 'last_day');
            $ortuTime = $ortuCfg['time'] ?? '17:00';
            $ortuHour = explode(':', $ortuTime)[0] ?? '17';

            $shouldRunOrtu = false;

            if ($force) {
                $shouldRunOrtu = true;
            } elseif ($isOrtuActive && $currentHour === $ortuHour) {
                if ($ortuFreq === 'monthly') {
                    if ($ortuDay === 'last_day' && $isLastDayOfMonth) {
                        $shouldRunOrtu = true;
                    } elseif ($ortuDay === $currentDayNum) {
                        $shouldRunOrtu = true;
                    }
                } elseif ($ortuFreq === 'weekly') {
                    if ($currentDayName === $ortuDay) {
                        $shouldRunOrtu = true;
                    }
                }
            }

            if ($shouldRunOrtu) {
                $this->info(">> [Orang Tua] Memproses pengiriman rekap...");

                if ($ortuFreq === 'weekly') {
                    $start = $now->copy()->startOfWeek(Carbon::MONDAY)->format('Y-m-d');
                    $end = $now->copy()->format('Y-m-d');
                } else {
                    $start = $now->copy()->startOfMonth()->format('Y-m-d');
                    $end = $now->copy()->endOfMonth()->format('Y-m-d');
                }

                $totalQueuedOrtu = 0;
                foreach ($daftarKelas as $kelas) {
                    $res = $service->dispatchNotifikasiOrangTua($kelas->id_kelas, $start, $end);
                    $totalQueuedOrtu += $res['queued'];
                }

                $this->info(">> [Orang Tua] Berhasil menjadwalkan {$totalQueuedOrtu} notifikasi orang tua (Periode: {$start} s/d {$end}).");
                Log::info("Scheduler Rekap WA: Terkirim {$totalQueuedOrtu} rekap Orang Tua ({$ortuFreq}).");
            } else {
                $this->line(".. [Orang Tua] Belum waktunya dikirim (Jadwal: {$ortuFreq} - {$ortuDay} pukul {$ortuTime}).");
            }
        }

        $this->info("=== Selesai Memeriksa Jadwal ===");
        return Command::SUCCESS;
    }
}
