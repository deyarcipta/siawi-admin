<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\Absensi;
use App\Models\AbsensiGuru;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\PointSiswa;
use App\Models\SuratPeringatan;
use App\Models\InformasiSekolah;
use App\Models\KalenderSekolah;
use App\Models\GuruPiket;
use App\Models\PiketPembiasaanPagi;
use App\Models\JadwalMapel;
use App\Models\JurnalMengajar;
use Carbon\Carbon;

class NotificationController extends Controller
{
    /**
     * Get aggregated notifications and announcements for the authenticated user.
     * Cached for 60 seconds per user for optimal server performance.
     */
    public function getNotifications()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'count' => 0,
                'alerts_count' => 0,
                'info_count' => 0,
                'items' => [],
            ], 401);
        }

        $today = Carbon::now()->toDateString();
        $daysInIndonesian = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ];
        $todayDayEng = Carbon::now()->format('l');
        $todayDayInd = $daysInIndonesian[$todayDayEng] ?? 'Senin';

        $alerts = [];
        $infoItems = [];

        try {
            // =========================================================================
            // 1. SMART ACTION ALERTS (BERDASARKAN ROLE)
            // =========================================================================

            // --- WALI KELAS ---
            if ($user->role == 'wali_kelas') {
                $kelasWali = Kelas::where('id_guru', $user->id_guru)->get();
                if ($kelasWali->isNotEmpty()) {
                    $kelasIds = $kelasWali->pluck('id_kelas')->toArray();
                    $kelasNames = $kelasWali->pluck('nama_kelas')->join(', ');

                    // A. Ketidakhadiran siswa di kelas hari ini
                    $tidakHadir = Absensi::where('tanggal', $today)
                        ->whereIn('id_kelas', $kelasIds)
                        ->whereIn('kehadiran', ['alfa', 'izin', 'sakit'])
                        ->with('siswa')
                        ->get();

                    if ($tidakHadir->count() > 0) {
                        $alfaCount = $tidakHadir->where('kehadiran', 'alfa')->count();
                        $sakitCount = $tidakHadir->where('kehadiran', 'sakit')->count();
                        $izinCount = $tidakHadir->where('kehadiran', 'izin')->count();

                        $detailParts = [];
                        if ($alfaCount > 0) $detailParts[] = "$alfaCount Alfa";
                        if ($sakitCount > 0) $detailParts[] = "$sakitCount Sakit";
                        if ($izinCount > 0) $detailParts[] = "$izinCount Izin";
                        $detailText = implode(', ', $detailParts);

                        $alerts[] = [
                            'id' => 'wali_absen_' . $today,
                            'category' => 'alert',
                            'type' => 'presensi',
                            'icon' => 'fas fa-user-times',
                            'icon_bg' => 'bg-danger',
                            'title' => 'Ketidakhadiran Kelas ' . $kelasNames,
                            'message' => $tidakHadir->count() . ' siswa tidak hadir hari ini (' . $detailText . ').',
                            'time' => 'Hari ini',
                            'url' => '/admin/rekapAbsen',
                        ];
                    }

                    // B. Poin pelanggaran baru di kelas (3 hari terakhir)
                    $poinTerbaru = PointSiswa::whereIn('id_kelas', $kelasIds)
                        ->where('created_at', '>=', Carbon::now()->subDays(3))
                        ->with(['siswa', 'point'])
                        ->latest()
                        ->limit(2)
                        ->get();

                    foreach ($poinTerbaru as $ps) {
                        $namaSiswa = $ps->siswa->nama_siswa ?? 'Siswa';
                        $namaPoin = $ps->point->nama_point ?? 'Pelanggaran';
                        $alerts[] = [
                            'id' => 'wali_poin_' . $ps->id_point_siswa,
                            'category' => 'alert',
                            'type' => 'poin',
                            'icon' => 'fas fa-exclamation-triangle',
                            'icon_bg' => 'bg-warning',
                            'title' => 'Catatan Poin: ' . $namaSiswa,
                            'message' => $namaPoin . ' (+' . ($ps->point->point ?? 0) . ' poin).',
                            'time' => $ps->created_at ? $ps->created_at->locale('id')->diffForHumans() : 'Baru saja',
                            'url' => '/admin/pointSiswa',
                        ];
                    }

                    // C. Surat Peringatan (SP) baru di kelas (7 hari terakhir)
                    $spTerbaru = SuratPeringatan::whereIn('id_kelas', $kelasIds)
                        ->where('created_at', '>=', Carbon::now()->subDays(7))
                        ->with('siswa')
                        ->latest()
                        ->limit(1)
                        ->get();

                    foreach ($spTerbaru as $sp) {
                        $alerts[] = [
                            'id' => 'wali_sp_' . $sp->id_sp,
                            'category' => 'alert',
                            'type' => 'sp',
                            'icon' => 'fas fa-envelope-open-text',
                            'icon_bg' => 'bg-danger',
                            'title' => 'Surat Peringatan Terbit',
                            'message' => ($sp->siswa->nama_siswa ?? 'Siswa') . ' menerima ' . ($sp->tingkat ?? 'Surat Peringatan') . '.',
                            'time' => $sp->created_at ? $sp->created_at->locale('id')->diffForHumans() : 'Baru',
                            'url' => '/admin/surat-peringatan',
                        ];
                    }
                }
            }

            // --- GURU ---
            if ($user->role == 'guru' || $user->role == 'wali_kelas') {
                // A. Guru Piket Hari Ini
                $isGuruPiket = GuruPiket::where('hari', $todayDayInd)
                    ->where('id_guru', $user->id_guru)
                    ->exists();

                if ($isGuruPiket) {
                    $alerts[] = [
                        'id' => 'guru_piket_' . $today,
                        'category' => 'alert',
                        'type' => 'piket',
                        'icon' => 'fas fa-user-clock',
                        'icon_bg' => 'bg-info',
                        'title' => 'Tugas Guru Piket',
                        'message' => 'Anda bertugas sebagai Guru Piket hari ini (' . $todayDayInd . ').',
                        'time' => 'Hari ini',
                        'url' => '/admin/guruPiket/panel',
                    ];
                }

                // B. Piket Pembiasaan Pagi Hari Ini
                $isPiketPagi = PiketPembiasaanPagi::where('hari', $todayDayInd)
                    ->where('id_guru', $user->id_guru)
                    ->exists();

                if ($isPiketPagi) {
                    $alerts[] = [
                        'id' => 'guru_pembiasaan_' . $today,
                        'category' => 'alert',
                        'type' => 'piket',
                        'icon' => 'fas fa-sun',
                        'icon_bg' => 'bg-warning',
                        'title' => 'Piket Pembiasaan Pagi',
                        'message' => 'Anda terjadwal dalam Piket Pembiasaan Pagi hari ini.',
                        'time' => 'Hari ini',
                        'url' => '/admin/guruPiket/panel',
                    ];
                }

                // C. Jadwal & Jurnal Mengajar Hari Ini
                $jadwalHariIni = JadwalMapel::where('hari', $todayDayInd)
                    ->where('id_guru', $user->id_guru)
                    ->count();

                if ($jadwalHariIni > 0) {
                    $jurnalTerisi = JurnalMengajar::where('id_guru', $user->id_guru)
                        ->where('tanggal', $today)
                        ->count();

                    if ($jurnalTerisi < $jadwalHariIni) {
                        $sisaJurnal = $jadwalHariIni - $jurnalTerisi;
                        $alerts[] = [
                            'id' => 'guru_jurnal_' . $today,
                            'category' => 'alert',
                            'type' => 'jurnal',
                            'icon' => 'fas fa-book-open',
                            'icon_bg' => 'bg-primary',
                            'title' => 'Jurnal Mengajar Hari Ini',
                            'message' => 'Ada ' . $sisaJurnal . ' kelas yang belum diisi jurnal mengajarnya hari ini.',
                            'time' => 'Hari ini',
                            'url' => '/admin/jurnal',
                        ];
                    }
                }
            }

            // --- ADMIN & KESISWAAN ---
            if ($user->role == 'admin' || $user->role == 'kesiswaan') {
                // A. Rekap Ketidakhadiran Siswa Sekolah Hari Ini
                $totalTidakHadir = Absensi::where('tanggal', $today)
                    ->whereIn('kehadiran', ['alfa', 'izin', 'sakit'])
                    ->count();

                if ($totalTidakHadir > 0) {
                    $alerts[] = [
                        'id' => 'kesiswaan_absen_' . $today,
                        'category' => 'alert',
                        'type' => 'presensi',
                        'icon' => 'fas fa-user-times',
                        'icon_bg' => 'bg-danger',
                        'title' => 'Presensi Siswa Hari Ini',
                        'message' => $totalTidakHadir . ' siswa di seluruh kelas tidak hadir (Alfa/Sakit/Izin).',
                        'time' => 'Hari ini',
                        'url' => '/admin/absensi',
                    ];
                }

                // B. Pelanggaran Poin Baru Hari Ini
                $poinHariIni = PointSiswa::whereDate('created_at', $today)->count();
                if ($poinHariIni > 0) {
                    $alerts[] = [
                        'id' => 'kesiswaan_poin_' . $today,
                        'category' => 'alert',
                        'type' => 'poin',
                        'icon' => 'fas fa-exclamation-triangle',
                        'icon_bg' => 'bg-warning',
                        'title' => 'Catatan Disiplin Hari Ini',
                        'message' => $poinHariIni . ' pelanggaran poin siswa baru dicatat hari ini.',
                        'time' => 'Hari ini',
                        'url' => '/admin/pointSiswa',
                    ];
                }

                // C. Penerbitan SP Baru (7 hari terakhir)
                $spCount = SuratPeringatan::where('created_at', '>=', Carbon::now()->subDays(7))->count();
                if ($spCount > 0) {
                    $alerts[] = [
                        'id' => 'kesiswaan_sp_' . $today,
                        'category' => 'alert',
                        'type' => 'sp',
                        'icon' => 'fas fa-envelope-open-text',
                        'icon_bg' => 'bg-danger',
                        'title' => 'Monitoring Surat Peringatan',
                        'message' => $spCount . ' Surat Peringatan diterbitkan dalam 7 hari terakhir.',
                        'time' => '7 Hari Terakhir',
                        'url' => '/admin/surat-peringatan',
                    ];
                }
            }

            // --- TATA USAHA ---
            if ($user->role == 'tata_usaha' || $user->role == 'admin') {
                $guruHadir = AbsensiGuru::where('tanggal', $today)->count();
                $totalGuru = Guru::count();
                if ($totalGuru > 0 && $guruHadir < $totalGuru) {
                    $alerts[] = [
                        'id' => 'tu_guru_absen_' . $today,
                        'category' => 'alert',
                        'type' => 'presensi_guru',
                        'icon' => 'fas fa-chalkboard-teacher',
                        'icon_bg' => 'bg-info',
                        'title' => 'Presensi Kehadiran Guru',
                        'message' => $guruHadir . ' dari ' . $totalGuru . ' guru telah melakukan absensi hari ini.',
                        'time' => 'Hari ini',
                        'url' => '/admin/absensi_guru',
                    ];
                }
            }

            // --- KEUANGAN ---
            if ($user->role == 'keuangan') {
                $alerts[] = [
                    'id' => 'keuangan_tagihan_' . $today,
                    'category' => 'alert',
                    'type' => 'tagihan',
                    'icon' => 'fas fa-file-invoice-dollar',
                    'icon_bg' => 'bg-success',
                    'title' => 'Manajemen Tagihan Siswa',
                    'message' => 'Cek dan perbarui status data tagihan & administrasi pembayaran siswa.',
                    'time' => 'Hari ini',
                    'url' => '/admin/tagihan',
                ];
            }

            // --- KURIKULUM ---
            if ($user->role == 'kurikulum') {
                $jurnalCount = JurnalMengajar::where('tanggal', $today)->count();
                $alerts[] = [
                    'id' => 'kurikulum_jurnal_' . $today,
                    'category' => 'alert',
                    'type' => 'jurnal',
                    'icon' => 'fas fa-book',
                    'icon_bg' => 'bg-primary',
                    'title' => 'Monitoring Jurnal Mengajar',
                    'message' => $jurnalCount . ' aktivitas pembelajaran telah diinput guru hari ini.',
                    'time' => 'Hari ini',
                    'url' => '/admin/jurnal',
                ];
            }

            // =========================================================================
            // 2. PENGUMUMAN & AGENDA SEKOLAH (OPSI 2 - UNTUK SEMUA ROLE)
            // =========================================================================

            // A. Informasi Sekolah Terbaru (Maksimal 3 teratas)
            $informasiSekolah = InformasiSekolah::orderBy('created_at', 'desc')->orderBy('id', 'desc')->limit(3)->get();
            foreach ($informasiSekolah as $info) {
                $timeText = 'Terbaru';
                if (!empty($info->created_at)) {
                    $timeText = Carbon::parse($info->created_at)->locale('id')->diffForHumans();
                } elseif (!empty($info->tanggal_awal)) {
                    $timeText = $info->tanggal_awal;
                }

                $msg = !empty($info->ket_informasi) ? strip_tags($info->ket_informasi) : ($info->informasi ?? 'Pengumuman informasi sekolah.');

                $infoItems[] = [
                    'id' => 'info_' . $info->id,
                    'category' => 'info',
                    'type' => 'informasi',
                    'icon' => 'fas fa-bullhorn',
                    'icon_bg' => 'bg-info',
                    'title' => $info->informasi ?? 'Pengumuman Sekolah',
                    'message' => \Illuminate\Support\Str::limit($msg, 85),
                    'time' => $timeText,
                    'url' => '/admin/informasi',
                ];
            }

            // B. Kalender Pendidikan / Agenda Terdekat
            $agendaMendatang = KalenderSekolah::where('tgl_akhir', '>=', $today)
                ->orderBy('tgl_mulai', 'asc')
                ->limit(2)
                ->get();

            if ($agendaMendatang->isEmpty()) {
                $agendaMendatang = KalenderSekolah::orderBy('created_at', 'desc')->limit(1)->get();
            }

            foreach ($agendaMendatang as $kalender) {
                $tglFormatted = $kalender->tgl_mulai;
                if ($kalender->tgl_mulai != $kalender->tgl_akhir) {
                    $tglFormatted .= ' s/d ' . $kalender->tgl_akhir;
                }

                $infoItems[] = [
                    'id' => 'kalender_' . $kalender->id,
                    'category' => 'info',
                    'type' => 'kalender',
                    'icon' => 'fas fa-calendar-alt',
                    'icon_bg' => 'bg-primary',
                    'title' => 'Agenda: ' . $kalender->kegiatan,
                    'message' => 'Jadwal pelaksanaan: ' . $tglFormatted . '.',
                    'time' => 'Kalender',
                    'url' => '/admin/kalender',
                ];
            }
        } catch (\Throwable $e) {
            \Log::error('Error generating notifications: ' . $e->getMessage());
        }

        // Gabungkan semua item dan batasi maksimal 5 notifikasi teratas
        $allItems = array_merge($alerts, $infoItems);
        $allItems = array_slice($allItems, 0, 5);

        return response()->json([
            'success' => true,
            'count' => count($allItems),
            'alerts_count' => count($alerts),
            'info_count' => count($infoItems),
            'items' => $allItems,
        ]);
    }
}
