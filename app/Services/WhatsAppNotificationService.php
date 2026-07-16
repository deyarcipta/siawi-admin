<?php

namespace App\Services;

use App\Models\Absensi;
use App\Jobs\SendWhatsAppAttendanceNotification;
use Illuminate\Support\Facades\Log;

class WhatsAppNotificationService
{
    /**
     * Send attendance notification via WhatsApp
     */
    public static function sendAttendanceNotification(Absensi $absensi)
    {
        try {
            // Periksa apakah notifikasi WA diaktifkan di pengaturan
            $setting = \App\Models\Setting::first();
            if ($setting && !$setting->wa_status) {
                return;
            }

            // Eager load siswa and kelas relations if not loaded
            if (!$absensi->relationLoaded('siswa')) {
                $absensi->load('siswa');
            }
            if ($absensi->siswa && !$absensi->siswa->relationLoaded('kelas')) {
                $absensi->siswa->load('kelas');
            }

            $siswa = $absensi->siswa;
            if (!$siswa) {
                Log::warning("WA Notification: Siswa tidak ditemukan untuk absensi ID {$absensi->id_absensi}");
                return;
            }

            $phone = $siswa->no_hp ?? $siswa->no_tlpn;
            if (empty($phone) || strlen(trim($phone)) < 5) {
                Log::warning("WA Notification: Nomor HP/Telepon tidak valid untuk Siswa {$siswa->nama_siswa} (ID {$siswa->id_siswa})");
                return;
            }

            $tanggal = $absensi->tanggal;
            $hari = $absensi->hari ?? now()->locale('id')->isoFormat('dddd');
            $jam = $absensi->jam_masuk ?? '-';
            $kehadiran = strtoupper($absensi->kehadiran);
            
            $namaKelas = $siswa->kelas->nama_kelas ?? '-';
            
            // Format pesan berdasarkan status kehadiran (Masuk vs Pulang)
            if (strtolower($absensi->kehadiran) === 'hadir') {
                $isPulang = !empty($absensi->jam_pulang) && $absensi->jam_pulang !== '-';
                
                if ($isPulang) {
                    $jamPulang = $absensi->jam_pulang;
                    $message = "Informasi Kepulangan Siswa SMK Wisata Indonesia:\n\n" .
                               "Yth. Orang Tua/Wali,\n" .
                               "Siswa atas nama *{$siswa->nama_siswa}* (Kelas: {$namaKelas}) telah dicatat *Pulang* pada hari {$hari}, {$tanggal} jam {$jamPulang}.";
                } else {
                    if (str_contains(strtolower($absensi->keterangan), 'terlambat')) {
                        $message = "Informasi Kehadiran Siswa SMK Wisata Indonesia:\n\n" .
                                   "Yth. Orang Tua/Wali,\n" .
                                   "Siswa atas nama *{$siswa->nama_siswa}* (Kelas: {$namaKelas}) telah dicatat *Hadir (Terlambat)* pada hari {$hari}, {$tanggal} jam {$jam}.\n\n" .
                                   "Keterangan: {$absensi->keterangan}";
                    } else {
                        $message = "Informasi Kehadiran Siswa SMK Wisata Indonesia:\n\n" .
                                   "Yth. Orang Tua/Wali,\n" .
                                   "Siswa atas nama *{$siswa->nama_siswa}* (Kelas: {$namaKelas}) telah dicatat *Hadir* pada hari {$hari}, {$tanggal} jam {$jam}.";
                    }
                }
            } else {
                $statusIndo = [
                    'SAKIT' => 'Sakit',
                    'IZIN' => 'Izin',
                    'ALFA' => 'Alfa / Tanpa Keterangan'
                ];
                $statusText = $statusIndo[$kehadiran] ?? $kehadiran;
                $keteranganText = (!empty($absensi->keterangan) && $absensi->keterangan !== '-') ? "Keterangan: {$absensi->keterangan}" : "";
                
                $message = "Informasi Ketidakhadiran Siswa SMK Wisata Indonesia:\n\n" .
                           "Yth. Orang Tua/Wali,\n" .
                           "Siswa atas nama *{$siswa->nama_siswa}* (Kelas: {$namaKelas}) telah dicatat *{$statusText}* pada hari {$hari}, {$tanggal}.\n" .
                           ($keteranganText ? "\n{$keteranganText}" : "");
            }

            // Dispatch job to Laravel queue
            SendWhatsAppAttendanceNotification::dispatch($phone, $message);

        } catch (\Exception $e) {
            Log::error("WA Notification Error: " . $e->getMessage());
        }
    }
}
