<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\GuruPiket;
use App\Models\Setting;
use App\Exports\RekapBelumAbsenExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RekapBelumAbsenController extends Controller
{
    /**
     * Display the index page with filters.
     */
    public function index(Request $request)
    {
        $layout = 'layout.app';
        $setting = Setting::find(1);
        $user = Auth::user();

        $date = $request->input('tanggal', Carbon::today()->toDateString());

        // 1. Get all classes
        $classes = Kelas::orderBy('nama_kelas', 'asc')->get();

        // 2. Count how many classes are fully inputted
        $fullClassesCount = 0;
        foreach ($classes as $kelas) {
            $totalSiswa = Siswa::where('id_kelas', $kelas->id_kelas)->count();
            if ($totalSiswa > 0) {
                $totalAbsen = \App\Models\Absensi::where('tanggal', $date)
                    ->where('id_kelas', $kelas->id_kelas)
                    ->count();

                if ($totalAbsen === $totalSiswa) {
                    $fullClassesCount++;
                }
            }
        }

        $criteriaMet = ($fullClassesCount >= 2);

        $kelasBelumAbsen = [];
        $guruPiket = [];
        $dayInd = '';

        if ($criteriaMet) {
            // 3. Get classes that have NOT completed attendance
            $allClasses = Kelas::with(['siswa', 'waliKelas'])->orderBy('nama_kelas', 'asc')->get();
            
            foreach ($allClasses as $kelas) {
                $totalSiswa = $kelas->siswa->count();
                if ($totalSiswa > 0) {
                    // Find students in this class who don't have attendance record
                    $siswaSudahAbsenIds = \App\Models\Absensi::where('tanggal', $date)
                        ->where('id_kelas', $kelas->id_kelas)
                        ->pluck('id_siswa')
                        ->toArray();
                    
                    $siswaBelumAbsen = $kelas->siswa->filter(function ($siswa) use ($siswaSudahAbsenIds) {
                        return !in_array($siswa->id_siswa, $siswaSudahAbsenIds);
                    });

                    if ($siswaBelumAbsen->count() > 0) {
                        $pesanWa = self::generateWaMessage($kelas, $siswaBelumAbsen, $date, $setting->nama_sekolah ?? null);
                        $waliNoHp = $kelas->waliKelas?->no_hp;
                        $waliWaLinkNumber = self::formatWaLinkNumber($waliNoHp);
                        $waUrl = $waliWaLinkNumber ? "https://wa.me/{$waliWaLinkNumber}?text=" . rawurlencode($pesanWa) : null;

                        $kelasBelumAbsen[] = [
                            'kelas' => $kelas,
                            'totalSiswa' => $totalSiswa,
                            'jumlahBelumAbsen' => $siswaBelumAbsen->count(),
                            'siswaBelumAbsen' => $siswaBelumAbsen,
                            'waliKelas' => $kelas->waliKelas,
                            'waliNoHp' => $waliNoHp,
                            'waUrl' => $waUrl,
                            'pesanWa' => $pesanWa
                        ];
                    }
                }
            }

            // 4. Get scheduled Guru Piket for this day
            $daysInIndonesian = [
                'Sunday' => 'Minggu',
                'Monday' => 'Senin',
                'Tuesday' => 'Selasa',
                'Wednesday' => 'Rabu',
                'Thursday' => 'Kamis',
                'Friday' => 'Jumat',
                'Saturday' => 'Sabtu'
            ];
            $dayEng = Carbon::parse($date)->format('l');
            $dayInd = $daysInIndonesian[$dayEng] ?? 'Senin';

            $guruPiket = GuruPiket::with('guru')->where('hari', $dayInd)->get();
        } else {
            $daysInIndonesian = [
                'Sunday' => 'Minggu',
                'Monday' => 'Senin',
                'Tuesday' => 'Selasa',
                'Wednesday' => 'Rabu',
                'Thursday' => 'Kamis',
                'Friday' => 'Jumat',
                'Saturday' => 'Sabtu'
            ];
            $dayEng = Carbon::parse($date)->format('l');
            $dayInd = $daysInIndonesian[$dayEng] ?? 'Senin';
        }

        return view('dataMaster.rekap_belum_absen', compact(
            'layout',
            'setting',
            'user',
            'date',
            'criteriaMet',
            'fullClassesCount',
            'kelasBelumAbsen',
            'guruPiket',
            'dayInd'
        ));
    }

    /**
     * Format WhatsApp phone number for link (wa.me)
     */
    public static function formatWaLinkNumber($number)
    {
        if (empty($number)) return null;
        $clean = preg_replace('/[^0-9]/', '', $number);
        if (str_starts_with($clean, '620')) {
            $clean = '62' . substr($clean, 3);
        } elseif (str_starts_with($clean, '0')) {
            $clean = '62' . substr($clean, 1);
        } elseif (str_starts_with($clean, '8')) {
            $clean = '62' . $clean;
        }
        return $clean;
    }

    /**
     * Generate standard WhatsApp message for informing Wali Kelas about uninputted students.
     */
    public static function generateWaMessage($kelas, $siswaBelumAbsen, $date, $namaSekolah = null)
    {
        if (!$namaSekolah) {
            $setting = Setting::first();
            $namaSekolah = $setting->nama_sekolah ?? 'SMK Wisata Indonesia';
        }

        $carbonDate = Carbon::parse($date);
        $daysInIndonesian = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ];
        $dayEng = $carbonDate->format('l');
        $dayInd = $daysInIndonesian[$dayEng] ?? 'Senin';
        $tanggalFormatted = $dayInd . ', ' . $carbonDate->translatedFormat('d F Y');

        $wali = $kelas->waliKelas;
        $namaWali = $wali ? $wali->nama_guru : 'Wali Kelas ' . $kelas->nama_kelas;
        $totalBelum = count($siswaBelumAbsen);

        $daftarSiswaText = "";
        $no = 1;
        foreach ($siswaBelumAbsen as $s) {
            $nis = $s->nis ?? '-';
            $daftarSiswaText .= "{$no}. *{$s->nama_siswa}* (NIS: {$nis})\n";
            $no++;
        }

        $pesan = "📢 *PEMBERITAHUAN KELALAIAN INPUT ABSENSI*\n"
               . "*{$namaSekolah}*\n\n"
               . "Yth. Bapak/Ibu *{$namaWali}*\n"
               . "Wali Kelas: *{$kelas->nama_kelas}*\n\n"
               . "Berdasarkan rekap data absensi sistem SIAWI pada:\n"
               . "📅 *Hari/Tanggal:* {$tanggalFormatted}\n\n"
               . "Daftar *{$totalBelum} siswa* di kelas Anda yang *BELUM TERINPUT* data absensinya:\n\n"
               . $daftarSiswaText . "\n"
               . "⚠️ *INFORMASI PENTING:*\n"
               . "Penginputan data absensi susulan *HANYA DAPAT DILAKUKAN OLEH ADMIN*.\n\n"
               . "Mohon kesediaan Bapak/Ibu untuk segera mengonfirmasi status kehadiran siswa-siswa di atas (Hadir / Sakit / Izin / Alfa) dengan menghubungi Admin SIAWI melalui kontak WhatsApp berikut:\n\n"
               . "📱 *WhatsApp Admin:* 081382053328\n"
               . "🔗 *Chat Admin Langsung:* https://wa.me/6281382053328\n\n"
               . "Terima kasih atas kerja sama dan perhatian Bapak/Ibu.\n"
               . "_Pesan otomatis sistem SIAWI_";

        return $pesan;
    }

    /**
     * Kirim pengingat WhatsApp kelalaian input ke Wali Kelas via Gateway
     */
    public function kirimWaWaliKelas(Request $request, $id_kelas)
    {
        $date = $request->input('tanggal', Carbon::today()->toDateString());
        $kelas = Kelas::with(['waliKelas', 'siswa'])->findOrFail($id_kelas);
        $setting = Setting::first();
        $namaSekolah = $setting->nama_sekolah ?? 'SMK Wisata Indonesia';

        if (!$kelas->waliKelas || empty($kelas->waliKelas->no_hp)) {
            return response()->json([
                'success' => false,
                'message' => "Wali Kelas untuk kelas {$kelas->nama_kelas} belum diatur atau nomor WhatsApp belum terisi di data guru."
            ], 422);
        }

        $siswaSudahAbsenIds = \App\Models\Absensi::where('tanggal', $date)
            ->where('id_kelas', $kelas->id_kelas)
            ->pluck('id_siswa')
            ->toArray();

        $siswaBelumAbsen = $kelas->siswa->filter(function ($siswa) use ($siswaSudahAbsenIds) {
            return !in_array($siswa->id_siswa, $siswaSudahAbsenIds);
        });

        if ($siswaBelumAbsen->count() == 0) {
            return response()->json([
                'success' => false,
                'message' => "Seluruh siswa di kelas {$kelas->nama_kelas} sudah selesai diabsen pada tanggal tersebut."
            ], 422);
        }

        $pesan = self::generateWaMessage($kelas, $siswaBelumAbsen, $date, $namaSekolah);
        $wali = $kelas->waliKelas;

        $targetSessionId = null;
        if ($setting && isset($setting->rekap_wa_settings['walas']['session_id']) && $setting->rekap_wa_settings['walas']['session_id'] !== 'auto') {
            $targetSessionId = $setting->rekap_wa_settings['walas']['session_id'];
        }

        try {
            \App\Jobs\SendWhatsAppAttendanceNotification::dispatch($wali->no_hp, $pesan, $targetSessionId);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "Gagal mengirim ke antrean WhatsApp: " . $e->getMessage()
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => "Pesan data kelalaian berhasil dikirimkan ke nomor WhatsApp Wali Kelas {$wali->nama_guru} ({$kelas->nama_kelas})."
        ]);
    }

    /**
     * Kirim pengingat WhatsApp ke semua Wali Kelas yang kelasnya belum selesai absen
     */
    public function kirimWaSemua(Request $request)
    {
        $date = $request->input('tanggal', Carbon::today()->toDateString());
        $setting = Setting::first();
        $namaSekolah = $setting->nama_sekolah ?? 'SMK Wisata Indonesia';

        $targetSessionId = null;
        if ($setting && isset($setting->rekap_wa_settings['walas']['session_id']) && $setting->rekap_wa_settings['walas']['session_id'] !== 'auto') {
            $targetSessionId = $setting->rekap_wa_settings['walas']['session_id'];
        }

        $allClasses = Kelas::with(['siswa', 'waliKelas'])->orderBy('nama_kelas', 'asc')->get();
        $terkirimCount = 0;
        $tidakAdaNomorCount = 0;

        foreach ($allClasses as $kelas) {
            $siswaSudahAbsenIds = \App\Models\Absensi::where('tanggal', $date)
                ->where('id_kelas', $kelas->id_kelas)
                ->pluck('id_siswa')
                ->toArray();

            $siswaBelumAbsen = $kelas->siswa->filter(function ($siswa) use ($siswaSudahAbsenIds) {
                return !in_array($siswa->id_siswa, $siswaSudahAbsenIds);
            });

            if ($siswaBelumAbsen->count() > 0) {
                $wali = $kelas->waliKelas;
                if ($wali && !empty($wali->no_hp)) {
                    $pesan = self::generateWaMessage($kelas, $siswaBelumAbsen, $date, $namaSekolah);
                    try {
                        \App\Jobs\SendWhatsAppAttendanceNotification::dispatch($wali->no_hp, $pesan, $targetSessionId);
                        $terkirimCount++;
                    } catch (\Exception $e) {
                        \Log::error("Gagal kirim WA kelalaian massal ke {$wali->no_hp}: " . $e->getMessage());
                    }
                } else {
                    $tidakAdaNomorCount++;
                }
            }
        }

        if ($terkirimCount == 0 && $tidakAdaNomorCount == 0) {
            return response()->json([
                'success' => true,
                'message' => "Seluruh kelas telah menyelesaikan absensi pada tanggal tersebut."
            ]);
        }

        $pesanHasil = "Berhasil mengirimkan data kelalaian ke {$terkirimCount} Wali Kelas.";
        if ($tidakAdaNomorCount > 0) {
            $pesanHasil .= " ({$tidakAdaNomorCount} kelas dilewati karena nomor WhatsApp Wali Kelas belum terdaftar).";
        }

        return response()->json([
            'success' => true,
            'message' => $pesanHasil
        ]);
    }

    /**
     * Export the recap of unsubmitted students to Excel.
     */
    public function export(Request $request)
    {
        $date = $request->input('tanggal', Carbon::today()->toDateString());
        
        // Get scheduled Guru Piket for this day to pass to Excel
        $daysInIndonesian = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ];
        $dayEng = Carbon::parse($date)->format('l');
        $dayInd = $daysInIndonesian[$dayEng] ?? 'Senin';

        $guruPiket = GuruPiket::with('guru')->where('hari', $dayInd)->get();
        $guruPiketList = $guruPiket->map(fn($gp) => $gp->guru?->nama_guru ?? 'Guru Telah Dihapus')->implode(', ');

        return Excel::download(new RekapBelumAbsenExport($date, $guruPiketList), 'rekap_belum_absen_' . $date . '.xlsx');
    }

    /**
     * Store attendance from Rekap Kelalaian page.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'siswa' => 'required|array',
        ]);

        $date = $request->input('tanggal');
        $kelasId = $request->input('id_kelas');
        $siswaData = $request->input('siswa');

        $carbonDate = Carbon::parse($date);
        $daysInIndonesian = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ];
        $dayEng = $carbonDate->format('l');
        $dayInd = $daysInIndonesian[$dayEng] ?? 'Senin';
        $jam = now()->format('H:i:s');

        $savedCount = 0;

        foreach ($siswaData as $siswaId => $data) {
            $kehadiran = $data['kehadiran'] ?? null;
            if (!$kehadiran) {
                continue; // skip if not selected
            }

            $isHadir = strtolower($kehadiran) === 'hadir';
            $jamMasuk = $isHadir ? $jam : '-';
            $keterangan = $data['keterangan'] ?? '-';
            if (empty($keterangan)) {
                $keterangan = '-';
            }

            $siswa = Siswa::find($siswaId);
            if (!$siswa) {
                continue;
            }
            $idJurusan = $siswa->id_jurusan;

            $absensi = \App\Models\Absensi::updateOrCreate(
                [
                    'id_siswa' => $siswaId,
                    'tanggal' => $date,
                ],
                [
                    'hari' => $dayInd,
                    'id_kelas' => $kelasId,
                    'id_jurusan' => $idJurusan,
                    'kehadiran' => $kehadiran,
                    'keterangan' => $keterangan,
                    'jam_masuk' => $jamMasuk,
                    'tipe_masuk' => 'manual',
                ]
            );

            // Send WhatsApp Notification if service exists
            try {
                if (class_exists('\App\Services\WhatsAppNotificationService')) {
                    \App\Services\WhatsAppNotificationService::sendAttendanceNotification($absensi);
                }
            } catch (\Exception $e) {
                \Log::error('Gagal mengirim WhatsApp notifikasi: ' . $e->getMessage());
            }

            // Send push notification if student exists and has FCM token
            if ($siswa && !empty($siswa->fcm_token)) {
                try {
                    if (class_exists('\App\Services\FcmService')) {
                        \App\Services\FcmService::sendNotification(
                            $siswa->fcm_token,
                            'Absensi Hari Ini',
                            "Status absensi kamu pada tanggal " . $carbonDate->format('d-m-Y') . " telah dicatat: " . ucfirst($kehadiran)
                        );
                    }
                } catch (\Exception $e) {
                    \Log::error('Gagal mengirim FCM: ' . $e->getMessage());
                }
            }

            $savedCount++;
        }

        if ($savedCount > 0) {
            return redirect()->route('admin.rekapBelumAbsen.index', ['tanggal' => $date])
                ->with('success', "$savedCount data kehadiran berhasil disimpan.");
        }

        return redirect()->route('admin.rekapBelumAbsen.index', ['tanggal' => $date])
            ->with('failed', "Tidak ada data kehadiran yang dipilih untuk disimpan.");
    }
}
