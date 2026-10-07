<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class CleanOldLogsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'log:clean {--days= : Jumlah hari retensi log (default 7 hari)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Membersihkan dan menghapus file log Laravel lama yang melebihi batas hari yang ditentukan (default 7 hari)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = (int) ($this->option('days') ?: env('LOG_DAILY_DAYS', 7));
        $logPath = storage_path('logs');

        if (!File::exists($logPath)) {
            $this->warn("Folder log tidak ditemukan di: {$logPath}");
            return 0;
        }

        $files = File::files($logPath);
        $deletedCount = 0;
        $cutoffDate = Carbon::now()->subDays($days)->startOfDay();

        $this->info("Menghapus log yang lebih tua dari {$days} hari (sebelum {$cutoffDate->format('Y-m-d')})...");

        foreach ($files as $file) {
            $filename = $file->getFilename();

            // Abaikan file .gitignore
            if ($filename === '.gitignore') {
                continue;
            }

            $shouldDelete = false;

            // Pola format file log harian Laravel: laravel-YYYY-MM-DD.log
            if (preg_match('/laravel-(\d{4}-\d{2}-\d{2})\.log$/', $filename, $matches)) {
                $logDateStr = $matches[1];
                try {
                    $fileDate = Carbon::createFromFormat('Y-m-d', $logDateStr)->startOfDay();
                    if ($fileDate->lt($cutoffDate)) {
                        $shouldDelete = true;
                    }
                } catch (\Exception $e) {
                    // Jika parsing tanggal gagal, gunakan file last modified time
                    $fileLastModified = Carbon::createFromTimestamp($file->getMTime());
                    if ($fileLastModified->lt($cutoffDate)) {
                        $shouldDelete = true;
                    }
                }
            } else {
                // Untuk file log lain yang bukan log utama hari ini
                if ($filename !== 'laravel.log') {
                    $fileLastModified = Carbon::createFromTimestamp($file->getMTime());
                    if ($fileLastModified->lt($cutoffDate)) {
                        $shouldDelete = true;
                    }
                }
            }

            if ($shouldDelete) {
                File::delete($file->getRealPath());
                $this->line(" <fg=red>[DIHAPUS]</> {$filename}");
                $deletedCount++;
            }
        }

        $this->info("Pembersihan log selesai. Sebanyak {$deletedCount} file log lama berhasil dihapus.");
        return 0;
    }
}
