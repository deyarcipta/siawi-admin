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
        $classes = Kelas::all();

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
            $allClasses = Kelas::with('siswa')->get();
            
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
                        $kelasBelumAbsen[] = [
                            'kelas' => $kelas,
                            'totalSiswa' => $totalSiswa,
                            'jumlahBelumAbsen' => $siswaBelumAbsen->count(),
                            'siswaBelumAbsen' => $siswaBelumAbsen
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
