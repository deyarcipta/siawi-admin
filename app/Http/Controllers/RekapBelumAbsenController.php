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
        $guruPiketList = $guruPiket->map(fn($gp) => $gp->guru->nama_guru)->implode(', ');

        return Excel::download(new RekapBelumAbsenExport($date, $guruPiketList), 'rekap_belum_absen_' . $date . '.xlsx');
    }
}
