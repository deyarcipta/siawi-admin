<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;
use PDO;
use ZipArchive;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class DatabaseBackupService
{
    protected string $backupDir;

    public function __construct()
    {
        $this->backupDir = storage_path('app/backups');
        if (!File::exists($this->backupDir)) {
            File::makeDirectory($this->backupDir, 0755, true);
        }
    }

    /**
     * Buat file backup database (.sql) atau backup lengkap database + storage (.zip).
     *
     * @param string $type 'database' | 'full'
     * @return array
     */
    public function createBackup(string $type = 'database'): array
    {
        $timestamp = Carbon::now('Asia/Jakarta')->format('Y_m_d_His');

        if ($type === 'full') {
            return $this->createFullZipBackup($timestamp);
        }

        return $this->createDatabaseSqlBackup($timestamp);
    }

    /**
     * Buat backup SQL database murni.
     */
    protected function createDatabaseSqlBackup(string $timestamp): array
    {
        $filename = "backup_siawi_db_{$timestamp}.sql";
        $filePath = $this->backupDir . DIRECTORY_SEPARATOR . $filename;

        $this->dumpDatabaseToSqlFile($filePath);

        $sizeBytes = filesize($filePath);

        return [
            'filename' => $filename,
            'path' => $filePath,
            'type' => 'database',
            'is_full' => false,
            'size' => $this->formatSize($sizeBytes),
            'size_bytes' => $sizeBytes,
            'created_at' => Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Buat backup lengkap: Database SQL + File Storage (Foto, Dokumen, Modul) dalam ZIP.
     */
    protected function createFullZipBackup(string $timestamp): array
    {
        if (!class_exists('ZipArchive')) {
            throw new \Exception('Ekstensi PHP ZipArchive tidak aktif pada server.');
        }

        $tempSqlFile = $this->backupDir . DIRECTORY_SEPARATOR . "temp_db_{$timestamp}.sql";
        $zipFilename = "backup_siawi_full_{$timestamp}.zip";
        $zipPath = $this->backupDir . DIRECTORY_SEPARATOR . $zipFilename;

        // 1. Buat dump database sementara
        $this->dumpDatabaseToSqlFile($tempSqlFile);

        // 2. Buat file ZIP
        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            File::delete($tempSqlFile);
            throw new \Exception("Gagal menginisialisasi file ZIP pada: {$zipPath}");
        }

        // 3. Masukkan database.sql ke dalam ZIP
        $zip->addFile($tempSqlFile, 'database.sql');

        // 4. Masukkan seluruh file di storage/app/public ke dalam folder storage/ di dalam ZIP
        $publicStoragePath = storage_path('app/public');
        if (File::exists($publicStoragePath)) {
            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($publicStoragePath, RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($files as $file) {
                if (!$file->isDir()) {
                    $realPath = $file->getRealPath();
                    $relativePath = 'storage/' . substr($realPath, strlen($publicStoragePath) + 1);
                    // Normalisasi pemisah direktori untuk kompatibilitas ZIP
                    $relativePath = str_replace('\\', '/', $relativePath);
                    $zip->addFile($realPath, $relativePath);
                }
            }
        }

        $zip->close();

        // 5. Hapus file dump SQL sementara
        if (File::exists($tempSqlFile)) {
            File::delete($tempSqlFile);
        }

        $sizeBytes = filesize($zipPath);

        return [
            'filename' => $zipFilename,
            'path' => $zipPath,
            'type' => 'full',
            'is_full' => true,
            'size' => $this->formatSize($sizeBytes),
            'size_bytes' => $sizeBytes,
            'created_at' => Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Helper untuk dump seluruh database ke file SQL.
     */
    protected function dumpDatabaseToSqlFile(string $filePath): void
    {
        $databaseName = config('database.connections.mysql.database');
        $pdo = DB::connection()->getPdo();

        $handle = fopen($filePath, 'w+');
        if (!$handle) {
            throw new \Exception("Gagal membuat file SQL pada: {$filePath}");
        }

        $header = "-- ========================================================\n";
        $header .= "-- SISTEM INFORMASI AKADEMIK SIAWI - DATABASE DUMP\n";
        $header .= "-- Database: `{$databaseName}`\n";
        $header .= "-- Tanggal Backup: " . Carbon::now('Asia/Jakarta')->format('d F Y H:i:s') . " WIB\n";
        $header .= "-- ========================================================\n\n";
        $header .= "SET FOREIGN_KEY_CHECKS=0;\n";
        $header .= "SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';\n";
        $header .= "SET NAMES utf8mb4;\n\n";
        fwrite($handle, $header);

        $tables = [];
        $stmt = $pdo->query("SHOW FULL TABLES WHERE Table_type = 'BASE TABLE'");
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $tables[] = $row[0];
        }

        foreach ($tables as $table) {
            fwrite($handle, "\n-- --------------------------------------------------------\n");
            fwrite($handle, "-- Struktur Tabel `{$table}`\n");
            fwrite($handle, "-- --------------------------------------------------------\n");
            fwrite($handle, "DROP TABLE IF EXISTS `{$table}`;\n");

            $createStmt = $pdo->query("SHOW CREATE TABLE `{$table}`");
            $createRow = $createStmt->fetch(PDO::FETCH_NUM);
            if (!empty($createRow[1])) {
                fwrite($handle, $createRow[1] . ";\n\n");
            }

            fwrite($handle, "-- Data untuk Tabel `{$table}`\n");
            $dataStmt = $pdo->query("SELECT * FROM `{$table}`");

            $batchRows = [];
            $batchCount = 0;

            while ($row = $dataStmt->fetch(PDO::FETCH_ASSOC)) {
                $values = [];
                foreach ($row as $val) {
                    if (is_null($val)) {
                        $values[] = 'NULL';
                    } elseif (is_numeric($val) && !str_starts_with((string)$val, '0')) {
                        $values[] = $val;
                    } else {
                        $values[] = $pdo->quote($val);
                    }
                }
                $batchRows[] = '(' . implode(', ', $values) . ')';
                $batchCount++;

                if ($batchCount >= 200) {
                    fwrite($handle, "INSERT INTO `{$table}` VALUES \n" . implode(",\n", $batchRows) . ";\n");
                    $batchRows = [];
                    $batchCount = 0;
                }
            }

            if (!empty($batchRows)) {
                fwrite($handle, "INSERT INTO `{$table}` VALUES \n" . implode(",\n", $batchRows) . ";\n");
            }

            fwrite($handle, "\n");
        }

        $footer = "SET FOREIGN_KEY_CHECKS=1;\n";
        $footer .= "-- Dump selesai pada " . Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s') . "\n";
        fwrite($handle, $footer);
        fclose($handle);
    }

    /**
     * Restore database dari file SQL atau ZIP lengkap.
     *
     * @param string $filePath
     * @return void
     */
    public function restoreBackup(string $filePath): void
    {
        if (!File::exists($filePath)) {
            throw new \Exception("File backup tidak ditemukan: {$filePath}");
        }

        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $isZip = ($ext === 'zip');

        // Deteksi magic bytes jika file tidak berekstensi .zip (misal file upload .tmp)
        if (!$isZip) {
            $handle = fopen($filePath, 'rb');
            if ($handle) {
                $magic = fread($handle, 4);
                fclose($handle);
                if ($magic === "PK\x03\x04" || $magic === "PK\x05\x06" || $magic === "PK\x07\x08") {
                    $isZip = true;
                }
            }
        }

        if ($isZip) {
            $this->restoreFromZip($filePath);
        } else {
            $this->restoreFromSql($filePath);
        }

        // Bersihkan cache aplikasi agar sinkron dengan database baru
        Artisan::call('optimize:clear');
    }

    /**
     * Restore database murni dari file SQL.
     */
    protected function restoreFromSql(string $sqlFilePath): void
    {
        $sqlContent = File::get($sqlFilePath);
        if (empty(trim($sqlContent))) {
            throw new \Exception("File backup SQL kosong atau tidak valid.");
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        try {
            DB::unprepared($sqlContent);
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }

    /**
     * Restore dari file ZIP lengkap (Database SQL + File Storage).
     */
    protected function restoreFromZip(string $zipFilePath): void
    {
        if (!class_exists('ZipArchive')) {
            throw new \Exception('Ekstensi PHP ZipArchive tidak aktif pada server.');
        }

        $zip = new ZipArchive();
        if ($zip->open($zipFilePath) !== true) {
            throw new \Exception("Gagal membuka file arsip ZIP: {$zipFilePath}");
        }

        $tempExtractDir = storage_path('app/backups/temp_restore_' . uniqid());
        File::makeDirectory($tempExtractDir, 0755, true);

        try {
            $zip->extractTo($tempExtractDir);
            $zip->close();

            // 1. Eksekusi database.sql
            $sqlFile = $tempExtractDir . DIRECTORY_SEPARATOR . 'database.sql';
            if (File::exists($sqlFile)) {
                $this->restoreFromSql($sqlFile);
            } else {
                throw new \Exception("File database.sql tidak ditemukan di dalam arsip ZIP.");
            }

            // 2. Pulihkan file storage jika ada
            $storageFolder = $tempExtractDir . DIRECTORY_SEPARATOR . 'storage';
            if (File::isDirectory($storageFolder)) {
                $targetPublicStorage = storage_path('app/public');
                if (!File::exists($targetPublicStorage)) {
                    File::makeDirectory($targetPublicStorage, 0755, true);
                }
                File::copyDirectory($storageFolder, $targetPublicStorage);
            }
        } finally {
            // Bersihkan folder ekstraksi sementara
            if (File::isDirectory($tempExtractDir)) {
                File::deleteDirectory($tempExtractDir);
            }
        }
    }

    /**
     * Ambil daftar file backup yang tersimpan di server (.sql dan .zip).
     *
     * @return array
     */
    public function getBackupList(): array
    {
        $sqlFiles = File::glob($this->backupDir . '/*.sql');
        $zipFiles = File::glob($this->backupDir . '/*.zip');
        $files = array_merge($sqlFiles, $zipFiles);
        $backups = [];

        foreach ($files as $file) {
            $filename = basename($file);
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            $size = filesize($file);
            $mtime = filemtime($file);
            $isFull = ($ext === 'zip');

            $backups[] = [
                'filename' => $filename,
                'path' => $file,
                'ext' => $ext,
                'type' => $isFull ? 'full' : 'database',
                'is_full' => $isFull,
                'type_label' => $isFull ? 'Lengkap (DB + Storage)' : 'Database Saja',
                'size' => $this->formatSize($size),
                'size_bytes' => $size,
                'created_at' => Carbon::createFromTimestamp($mtime, 'Asia/Jakarta')->format('d M Y H:i'),
                'relative_time' => Carbon::createFromTimestamp($mtime, 'Asia/Jakarta')->diffForHumans(),
            ];
        }

        // Urutkan dari yang paling baru
        usort($backups, function ($a, $b) {
            return filemtime($b['path']) <=> filemtime($a['path']);
        });

        return $backups;
    }

    /**
     * Hapus file backup tertentu.
     *
     * @param string $filename
     * @return bool
     */
    public function deleteBackup(string $filename): bool
    {
        $cleanFilename = basename($filename);
        $filePath = $this->backupDir . DIRECTORY_SEPARATOR . $cleanFilename;

        if (File::exists($filePath)) {
            return File::delete($filePath);
        }

        return false;
    }

    /**
     * Format ukuran file ke bentuk yang mudah dibaca (KB / MB / GB).
     */
    protected function formatSize(int $bytes): string
    {
        if ($bytes >= 1073741824) {
            return round($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return round($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return round($bytes / 1024, 2) . ' KB';
        }

        return $bytes . ' Bytes';
    }
}
