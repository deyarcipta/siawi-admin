<?php

namespace App\Services;

use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Setting;
use App\Jobs\SendWhatsAppAttendanceNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class LaporanAbsensiBulananService
{
    /**
     * Hitung rekapitulasi absensi bulanan untuk sebuah kelas atau seluruh siswa.
     *
     * @param string|null $idKelas
     * @param string $tanggalMulai (Format: Y-m-d)
     * @param string $tanggalSelesai (Format: Y-m-d)
     * @return array
     */
    public function hitungRekapBulanan(?string $idKelas, string $tanggalMulai, string $tanggalSelesai): array
    {
        $querySiswa = Siswa::with(['kelas.waliKelas']);

        if ($idKelas && $idKelas !== 'all') {
            $querySiswa->where('id_kelas', $idKelas);
        }

        $daftarSiswa = $querySiswa->orderBy('nama_siswa', 'asc')->get();

        // Ambil data absensi dalam rentang tanggal
        $queryAbsensi = Absensi::whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai]);
        if ($idKelas && $idKelas !== 'all') {
            $queryAbsensi->where('id_kelas', $idKelas);
        }
        $dataAbsensi = $queryAbsensi->get()->groupBy('id_siswa');

        $rekapPerSiswa = [];
        $totalHadirTepat = 0;
        $totalTerlambat = 0;
        $totalSakit = 0;
        $totalIzin = 0;
        $totalAlfa = 0;
        $totalHariEfektif = Carbon::parse($tanggalMulai)->diffInDaysFiltered(function (Carbon $date) {
            return !$date->isSunday(); // Hari aktif non-Minggu
        }, Carbon::parse($tanggalSelesai)) + 1;

        if ($totalHariEfektif <= 0) {
            $totalHariEfektif = 20; // Default estimasi hari efektif bulanan
        }

        foreach ($daftarSiswa as $siswa) {
            $absensiSiswa = $dataAbsensi->get($siswa->id_siswa, collect());

            $hadirTepat = 0;
            $terlambat = 0;
            $sakit = 0;
            $izin = 0;
            $alfa = 0;

            foreach ($absensiSiswa as $absen) {
                $status = strtolower($absen->kehadiran);
                $isTerlambat = str_contains(strtolower($absen->keterangan ?? ''), 'terlambat');

                if ($status === 'hadir') {
                    if ($isTerlambat) {
                        $terlambat++;
                    } else {
                        $hadirTepat++;
                    }
                } elseif ($status === 'sakit') {
                    $sakit++;
                } elseif ($status === 'izin') {
                    $izin++;
                } elseif ($status === 'alfa' || $status === 'alpha' || $status === 'tanpa keterangan') {
                    $alfa++;
                }
            }

            $totalPresensi = $hadirTepat + $terlambat;
            // Persentase kehadiran berdasarkan hari aktif
            $persentase = $totalHariEfektif > 0 
                ? min(100, round(($totalPresensi / $totalHariEfektif) * 100)) 
                : 0;

            // Tentukan status kedisiplinan bulanan
            if ($persentase >= 90 && $alfa === 0 && $terlambat <= 2) {
                $statusKedisiplinan = 'Sangat Disiplin';
                $badgeClass = 'badge-success';
            } elseif ($persentase >= 75 && $alfa <= 2) {
                $statusKedisiplinan = 'Cukup Baik';
                $badgeClass = 'badge-primary';
            } elseif ($alfa >= 3 || $terlambat >= 5 || $persentase < 70) {
                $statusKedisiplinan = 'Perlu Perhatian';
                $badgeClass = 'badge-danger';
            } else {
                $statusKedisiplinan = 'Perlu Ditingkatkan';
                $badgeClass = 'badge-warning';
            }

            $rekapItem = [
                'siswa' => $siswa,
                'hadir_tepat' => $hadirTepat,
                'terlambat' => $terlambat,
                'sakit' => $sakit,
                'izin' => $izin,
                'alfa' => $alfa,
                'total_hadir' => $totalPresensi,
                'persentase' => $persentase,
                'status_kedisiplinan' => $statusKedisiplinan,
                'badge_class' => $badgeClass,
                'total_hari_efektif' => $totalHariEfektif,
                'no_hp' => $siswa->no_hp ?? $siswa->no_tlpn,
            ];

            $rekapPerSiswa[] = $rekapItem;

            $totalHadirTepat += $hadirTepat;
            $totalTerlambat += $terlambat;
            $totalSakit += $sakit;
            $totalIzin += $izin;
            $totalAlfa += $alfa;
        }

        $totalSiswa = count($rekapPerSiswa);
        $rataRataKehadiran = $totalSiswa > 0 
            ? round(collect($rekapPerSiswa)->avg('persentase'), 1) 
            : 0;

        return [
            'rekap_siswa' => $rekapPerSiswa,
            'summary' => [
                'total_siswa' => $totalSiswa,
                'rata_rata_kehadiran' => $rataRataKehadiran,
                'total_hadir_tepat' => $totalHadirTepat,
                'total_terlambat' => $totalTerlambat,
                'total_sakit' => $totalSakit,
                'total_izin' => $totalIzin,
                'total_alfa' => $totalAlfa,
                'total_hari_efektif' => $totalHariEfektif,
            ],
            'periode' => [
                'tanggal_mulai' => $tanggalMulai,
                'tanggal_selesai' => $tanggalSelesai,
                'label' => Carbon::parse($tanggalMulai)->locale('id')->translatedFormat('d M Y') . ' s/d ' . Carbon::parse($tanggalSelesai)->locale('id')->translatedFormat('d M Y'),
                'nama_bulan' => Carbon::parse($tanggalMulai)->locale('id')->translatedFormat('F Y'),
            ]
        ];
    }

    /**
     * Format template pesan WhatsApp untuk Orang Tua Siswa (Dinamis Mingguan / Bulanan).
     */
    public function formatPesanOrangTua(array $rekapSiswa, string $labelPeriode, ?Setting $setting = null): string
    {
        $siswa = $rekapSiswa['siswa'];
        $namaSekolah = $setting->nama_sekolah ?? 'SMK Wisata Indonesia';
        $namaKelas = $siswa->kelas->nama_kelas ?? '-';
        $namaWaliKelas = $siswa->kelas->waliKelas->nama_guru ?? '-';
        $jamSekarang = Carbon::now()->locale('id')->translatedFormat('H:i');

        // Deteksi tipe periode berdasarkan hari efektif / rentang
        $totalHari = $rekapSiswa['total_hari_efektif'] ?? 20;
        $tipeLabel = ($totalHari <= 7) ? 'Mingguan' : 'Bulanan';
        $waktuEvaluasi = ($totalHari <= 7) ? 'minggu ini' : 'bulan ini';

        // Dynamic greeting berdasarkan waktu
        $hour = (int) date('H');
        if ($hour < 11) {
            $salam = "Selamat Pagi";
        } elseif ($hour < 15) {
            $salam = "Selamat Siang";
        } elseif ($hour < 18) {
            $salam = "Selamat Sore";
        } else {
            $salam = "Selamat Malam";
        }

        $catatan = match ($rekapSiswa['status_kedisiplinan']) {
            'Sangat Disiplin' => "Ananda menunjukkan kedisiplinan dan kehadiran yang sangat baik {$waktuEvaluasi}. Pertahankan!",
            'Cukup Baik' => "Kehadiran Ananda cukup baik, mohon tetap dipertahankan dan ditingkatkan.",
            'Perlu Perhatian' => "Terdapat catatan ketidakhadiran/keterlambatan. Mohon bimbingan dan pengawasan lebih intensif di rumah.",
            default => "Mohon untuk terus memotivasi Ananda agar lebih tertib dan tepat waktu dalam kehadiran sekolah."
        };

        $msg = "Assalamu'alaikum Wr. Wb. / {$salam},\n" .
               "Yth. Bapak/Ibu Orang Tua / Wali dari:\n\n" .
               "👤 *Nama:* {$siswa->nama_siswa}\n" .
               "🆔 *NIS:* {$siswa->nis}\n" .
               "🏫 *Kelas:* {$namaKelas}\n" .
               "👨‍🏫 *Wali Kelas:* {$namaWaliKelas}\n\n" .
               "Berikut Laporan *Rekapitulasi Kehadiran {$tipeLabel}*:\n" .
               "📅 *Periode:* {$labelPeriode}\n" .
               "━━━━━━━━━━━━━━━━━━━━\n" .
               "✅ *Hadir Tepat Waktu* : {$rekapSiswa['hadir_tepat']} Hari\n" .
               "⏰ *Hadir Terlambat*   : {$rekapSiswa['terlambat']} Kali\n" .
               "🤒 *Sakit*             : {$rekapSiswa['sakit']} Hari\n" .
               "📝 *Izin*              : {$rekapSiswa['izin']} Hari\n" .
               "❌ *Alfa / Tanpa Ket.* : {$rekapSiswa['alfa']} Hari\n" .
               "━━━━━━━━━━━━━━━━━━━━\n" .
               "📊 *Persentase Kehadiran*: *{$rekapSiswa['persentase']}%*\n" .
               "📌 *Evaluasi:* {$catatan}\n\n" .
               "Demikian laporan {$tipeLabel} ini kami sampaikan demi mendukung kedisiplinan dan keberhasilan belajar Ananda.\n\n" .
               "Salam hangat,\n" .
               "*{$namaSekolah}*\n" .
               "_Pesan resmi otomatis sistem SIAWI • {$jamSekarang} WIB_";

        return $msg;
    }

    /**
     * Format template pesan WhatsApp untuk Wali Kelas (Dinamis Mingguan / Bulanan).
     */
    public function formatPesanWaliKelas(Kelas $kelas, array $rekapData, ?Setting $setting = null): string
    {
        $waliKelas = $kelas->waliKelas;
        $namaWali = $waliKelas ? $waliKelas->nama_guru : 'Bapak/Ibu Wali Kelas';
        $namaKelas = $kelas->nama_kelas;
        $summary = $rekapData['summary'];
        $labelPeriode = $rekapData['periode']['label'];
        $namaSekolah = $setting->nama_sekolah ?? 'SMK Wisata Indonesia';

        $totalHari = $summary['total_hari_efektif'] ?? 20;
        $tipeLabel = ($totalHari <= 7) ? 'Mingguan' : 'Bulanan';
        $waktuEvaluasi = ($totalHari <= 7) ? 'minggu ini' : 'bulan ini';

        // Cari siswa yang perlu perhatian (Alfa >= 2 atau Terlambat >= 3)
        $minAlfa = ($totalHari <= 7) ? 1 : 2;
        $minTelat = ($totalHari <= 7) ? 2 : 3;

        $siswaPerhatian = collect($rekapData['rekap_siswa'])
            ->filter(function ($item) use ($minAlfa, $minTelat) {
                return $item['alfa'] >= $minAlfa || $item['terlambat'] >= $minTelat;
            })
            ->values();

        $daftarPerhatianText = "";
        if ($siswaPerhatian->isNotEmpty()) {
            $daftarPerhatianText = "\n⚠️ *Siswa yang Memerlukan Perhatian:*\n";
            foreach ($siswaPerhatian->take(10) as $idx => $sp) {
                $detail = [];
                if ($sp['alfa'] > 0) $detail[] = "{$sp['alfa']}x Alfa";
                if ($sp['terlambat'] > 0) $detail[] = "{$sp['terlambat']}x Telat";
                if ($sp['sakit'] > 0) $detail[] = "{$sp['sakit']}x Sakit";
                if ($sp['izin'] > 0) $detail[] = "{$sp['izin']}x Izin";
                $daftarPerhatianText .= ($idx + 1) . ". *{$sp['siswa']->nama_siswa}* (" . implode(', ', $detail) . ")\n";
            }
            if ($siswaPerhatian->count() > 10) {
                $sisa = $siswaPerhatian->count() - 10;
                $daftarPerhatianText .= "... dan {$sisa} siswa lainnya.\n";
            }
        } else {
            $daftarPerhatianText = "\n✨ *Semua siswa dalam kelas ini berdisiplin sangat baik {$waktuEvaluasi}.*\n";
        }

        $msg = "Yth. Bapak/Ibu *{$namaWali}*,\n" .
               "Wali Kelas {$namaKelas} {$namaSekolah}\n\n" .
               "Berikut Ringkasan *Rekap Absensi {$tipeLabel} Kelas {$namaKelas}*:\n" .
               "📅 *Periode:* {$labelPeriode}\n" .
               "━━━━━━━━━━━━━━━━━━━━\n" .
               "👥 *Total Siswa*         : {$summary['total_siswa']} Siswa\n" .
               "📊 *Rata-rata Kehadiran* : *{$summary['rata_rata_kehadiran']}%*\n" .
               "✅ *Total Hadir Tepat*   : {$summary['total_hadir_tepat']} presensi\n" .
               "⏰ *Total Terlambat*     : {$summary['total_terlambat']} kasus\n" .
               "🤒 *Total Sakit*         : {$summary['total_sakit']} hari\n" .
               "📝 *Total Izin*          : {$summary['total_izin']} hari\n" .
               "❌ *Total Alfa / Bolos*  : {$summary['total_alfa']} hari\n" .
               "━━━━━━━━━━━━━━━━━━━━" .
               $daftarPerhatianText . "\n" .
               "Laporan ini dapat diakses secara detail pada portal SIAWI Admin.\n" .
               "Terima kasih atas dedikasi Bapak/Ibu dalam mendampingi dan membimbing siswa.\n\n" .
               "*Tim Kesiswaan & Kedisiplinan {$namaSekolah}*";

        return $msg;
    }

    /**
     * Dispatch queue pengiriman notifikasi bulanan ke seluruh Orang Tua di kelas tertentu.
     *
     * @param string|null $idKelas
     * @param string $tanggalMulai
     * @param string $tanggalSelesai
     * @return array (count queued, count skipped, total)
     */
    public function dispatchNotifikasiOrangTua(?string $idKelas, string $tanggalMulai, string $tanggalSelesai): array
    {
        $setting = Setting::first();
        $rekapData = $this->hitungRekapBulanan($idKelas, $tanggalMulai, $tanggalSelesai);
        $queued = 0;
        $skipped = 0;

        foreach ($rekapData['rekap_siswa'] as $item) {
            $noHp = $item['no_hp'];
            if (empty($noHp) || strlen(trim($noHp)) < 5) {
                $skipped++;
                Log::warning("Rekap Bulanan WA: Siswa {$item['siswa']->nama_siswa} tidak memiliki nomor HP Orang Tua yang valid.");
                continue;
            }

            $message = $this->formatPesanOrangTua($item, $rekapData['periode']['label'], $setting);

            // Dispatch ke queue anti-ban
            SendWhatsAppAttendanceNotification::dispatch($noHp, $message);
            $queued++;
        }

        return [
            'queued' => $queued,
            'skipped' => $skipped,
            'total' => count($rekapData['rekap_siswa'])
        ];
    }

    /**
     * Dispatch queue pengiriman ringkasan bulanan ke Wali Kelas.
     *
     * @param string $idKelas
     * @param string $tanggalMulai
     * @param string $tanggalSelesai
     * @return bool
     */
    public function dispatchNotifikasiWaliKelas(string $idKelas, string $tanggalMulai, string $tanggalSelesai): bool
    {
        $setting = Setting::first();
        $kelas = Kelas::with('waliKelas')->find($idKelas);
        if (!$kelas || !$kelas->waliKelas) {
            return false;
        }

        $noHp = $kelas->waliKelas->no_hp;
        if (empty($noHp) || strlen(trim($noHp)) < 5) {
            Log::warning("Rekap Bulanan WA: Wali Kelas {$kelas->waliKelas->nama_guru} tidak memiliki nomor HP yang valid.");
            return false;
        }

        $rekapData = $this->hitungRekapBulanan($idKelas, $tanggalMulai, $tanggalSelesai);
        $message = $this->formatPesanWaliKelas($kelas, $rekapData, $setting);

        SendWhatsAppAttendanceNotification::dispatch($noHp, $message);
        return true;
    }
}
