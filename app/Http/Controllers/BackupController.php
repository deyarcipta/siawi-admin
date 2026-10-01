<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;
use App\Services\DatabaseBackupService;

class BackupController extends Controller
{
    /**
     * Tampilkan halaman utama Backup & Restore Data.
     */
    public function index(DatabaseBackupService $backupService)
    {
        $layout = 'layout.app';
        $setting = Setting::find(1);
        $user = Auth::user();
        $backups = $backupService->getBackupList();

        return view('backup.index', compact('layout', 'setting', 'user', 'backups'));
    }

    /**
     * Buat backup database (.sql) atau backup lengkap (.zip).
     */
    public function create(Request $request, DatabaseBackupService $backupService)
    {
        $type = $request->input('type', 'database');

        try {
            $result = $backupService->createBackup($type);
            $typeText = $result['is_full'] ? 'Lengkap (Database + File Storage)' : 'Database';
            return redirect()->back()->with('success', "Backup {$typeText} berhasil dibuat: {$result['filename']} ({$result['size']}).");
        } catch (\Exception $e) {
            return redirect()->back()->with('failed', 'Gagal membuat backup: ' . $e->getMessage());
        }
    }

    /**
     * Unduh file backup (.sql / .zip) tertentu.
     */
    public function download(string $filename)
    {
        $cleanFilename = basename($filename);
        $filePath = storage_path('app/backups/' . $cleanFilename);

        if (!file_exists($filePath)) {
            return redirect()->back()->with('failed', 'File backup tidak ditemukan di server.');
        }

        $ext = strtolower(pathinfo($cleanFilename, PATHINFO_EXTENSION));
        $contentType = $ext === 'zip' ? 'application/zip' : 'application/sql';

        return response()->download($filePath, $cleanFilename, [
            'Content-Type' => $contentType
        ]);
    }

    /**
     * Pulihkan database & storage dari file (.sql / .zip) yang diunggah.
     */
    public function restore(Request $request, DatabaseBackupService $backupService)
    {
        $request->validate([
            'backup_file' => 'required|file|max:204800', // Maksimal 200MB
        ], [
            'backup_file.required' => 'Silakan pilih file backup (.sql atau .zip) terlebih dahulu.',
            'backup_file.max' => 'Ukuran file backup tidak boleh melebihi 200 MB.',
        ]);

        $file = $request->file('backup_file');
        $extension = strtolower($file->getClientOriginalExtension());

        if ($extension !== 'sql' && $extension !== 'zip' && $extension !== 'txt') {
            return redirect()->back()->with('failed', 'Format file tidak didukung. Harap unggah file berekstensi .sql atau .zip.');
        }

        $tempFilename = 'upload_restore_' . uniqid() . '.' . $extension;
        $tempPath = storage_path('app/backups/' . $tempFilename);
        $file->move(storage_path('app/backups'), $tempFilename);

        try {
            $backupService->restoreBackup($tempPath);
            return redirect()->back()->with('success', 'Sistem berhasil dipulihkan dari berkas cadangan yang diunggah. Database dan berkas storage telah disinkronkan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('failed', 'Gagal memulihkan data: ' . $e->getMessage());
        } finally {
            if (file_exists($tempPath)) {
                @unlink($tempPath);
            }
        }
    }

    /**
     * Pulihkan database dari arsip backup lokal yang tersimpan di server (.sql atau .zip).
     */
    public function restoreExisting(string $filename, DatabaseBackupService $backupService)
    {
        $cleanFilename = basename($filename);
        $filePath = storage_path('app/backups/' . $cleanFilename);

        try {
            $backupService->restoreBackup($filePath);
            return redirect()->back()->with('success', "Sistem berhasil dipulihkan ke titik snapshot {$cleanFilename}. Seluruh data telah diperbarui.");
        } catch (\Exception $e) {
            return redirect()->back()->with('failed', 'Gagal memulihkan data: ' . $e->getMessage());
        }
    }

    /**
     * Hapus file backup dari server.
     */
    public function destroy(string $filename, DatabaseBackupService $backupService)
    {
        try {
            $deleted = $backupService->deleteBackup($filename);
            if ($deleted) {
                return redirect()->back()->with('success', "File backup {$filename} berhasil dihapus.");
            }
            return redirect()->back()->with('failed', 'File backup tidak ditemukan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('failed', 'Gagal menghapus file backup: ' . $e->getMessage());
        }
    }
}
