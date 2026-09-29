<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Absensi;
use App\Models\Setting;
use App\Exports\LaporanKedisiplinanExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class LaporanKedisiplinanController extends Controller
{
    public function index(Request $request)
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();

        // Tanggal patokan (default hari ini)
        $tanggalPilihan = $request->input('tanggal', Carbon::today()->toDateString());
        $carbonDate = Carbon::parse($tanggalPilihan);

        // Cari Hari Senin s/d Sabtu pada pekan tersebut
        $startOfWeek = $carbonDate->copy()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = $startOfWeek->copy()->addDays(5); // Sabtu

        $startDateStr = $startOfWeek->toDateString();
        $endDateStr = $endOfWeek->toDateString();

        // Array 6 hari sekolah (Senin s/d Sabtu)
        $daysInWeek = [];
        $indonesianDayNames = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ];

        for ($i = 0; $i < 6; $i++) {
            $curr = $startOfWeek->copy()->addDays($i);
            $dayNameEng = $curr->format('l');
            $daysInWeek[] = [
                'tanggal' => $curr->toDateString(),
                'hari' => $indonesianDayNames[$dayNameEng] ?? $curr->locale('id')->dayName,
                'hari_singkat' => strtoupper(substr($indonesianDayNames[$dayNameEng] ?? $curr->locale('id')->dayName, 0, 3)),
                'tgl_formatted' => $curr->format('d/m'),
            ];
        }

        $kelasList = Kelas::orderBy('nama_kelas', 'asc')->get();
        $selectedKelasId = $request->input('id_kelas');
        $selectedKategori = $request->input('kategori'); // all, tertib, cukup, perlu_pembinaan

        // Query siswa
        $siswaQuery = Siswa::with('kelas')->orderBy('id_kelas', 'asc')->orderBy('nama_siswa', 'asc');
        if (!empty($selectedKelasId)) {
            $siswaQuery->where('id_kelas', $selectedKelasId);
        }
        $siswaList = $siswaQuery->get();

        // Ambil semua data absensi dalam rentang minggu tersebut
        $absensiRecords = Absensi::whereBetween('tanggal', [$startDateStr, $endDateStr])
            ->whereIn('id_siswa', $siswaList->pluck('id_siswa'))
            ->get()
            ->groupBy('id_siswa');

        // Olah data per siswa
        $rekapSiswa = [];
        $totalSiswa = count($siswaList);
        $countSangatTertib = 0;
        $countCukupTertib = 0;
        $countPerluPembinaan = 0;
        $sumPersentase = 0;
        $siswaWithAttendanceCount = 0;

        foreach ($siswaList as $siswa) {
            $siswaAbsen = $absensiRecords->get($siswa->id_siswa, collect());
            $absenByDate = $siswaAbsen->keyBy('tanggal');

            $harian = [];
            $totalHadirMesin = 0;
            $totalHadirManual = 0;
            $totalHadirPiket = 0;
            $totalSakit = 0;
            $totalIzin = 0;
            $totalAlfa = 0;

            foreach ($daysInWeek as $day) {
                $tgl = $day['tanggal'];
                $absen = $absenByDate->get($tgl);

                if ($absen) {
                    $kehadiran = strtolower($absen->kehadiran);
                    $tipeMasuk = $absen->tipe_masuk;
                    $tipePulang = $absen->tipe_pulang;
                    $ketLower = strtolower($absen->keterangan ?? '');

                    // Cek tipe masuk jika null (fallback untuk data lama)
                    if (empty($tipeMasuk)) {
                        if (str_contains($ketLower, 'check in') || str_contains($ketLower, 'face') || str_contains($ketLower, 'presence') || $ketLower === 'masuk') {
                            $tipeMasuk = 'mesin';
                        } elseif (str_contains($ketLower, 'terlambat')) {
                            $tipeMasuk = 'piket';
                        } else {
                            $tipeMasuk = 'manual';
                        }
                    }

                    if ($kehadiran === 'hadir') {
                        if ($tipeMasuk === 'mesin') {
                            $statusHari = 'mesin';
                            $totalHadirMesin++;
                        } elseif ($tipeMasuk === 'piket') {
                            $statusHari = 'piket';
                            $totalHadirPiket++;
                            $totalHadirManual++;
                        } else {
                            $statusHari = 'manual';
                            $totalHadirManual++;
                        }
                    } elseif ($kehadiran === 'sakit') {
                        $statusHari = 'sakit';
                        $totalSakit++;
                    } elseif ($kehadiran === 'izin') {
                        $statusHari = 'izin';
                        $totalIzin++;
                    } elseif ($kehadiran === 'alfa') {
                        $statusHari = 'alfa';
                        $totalAlfa++;
                    } else {
                        $statusHari = 'manual';
                        $totalHadirManual++;
                    }

                    $harian[$tgl] = [
                        'status' => $statusHari,
                        'kehadiran' => $absen->kehadiran,
                        'jam_masuk' => $absen->jam_masuk ?? '-',
                        'jam_pulang' => $absen->jam_pulang ?? '-',
                        'tipe_masuk' => $tipeMasuk,
                        'tipe_pulang' => $tipePulang,
                        'keterangan' => $absen->keterangan ?? '-',
                    ];
                } else {
                    $harian[$tgl] = [
                        'status' => 'belum_absen',
                        'kehadiran' => '-',
                        'jam_masuk' => '-',
                        'jam_pulang' => '-',
                        'tipe_masuk' => null,
                        'tipe_pulang' => null,
                        'keterangan' => '-',
                    ];
                }
            }

            $totalHadir = $totalHadirMesin + $totalHadirManual;
            
            if ($totalHadir > 0) {
                $persentaseMesin = round(($totalHadirMesin / $totalHadir) * 100);
                $siswaWithAttendanceCount++;
                $sumPersentase += $persentaseMesin;

                if ($persentaseMesin >= 80) {
                    $statusDisiplin = 'tertib';
                    $statusDisiplinLabel = 'Tertib';
                    $statusDisiplinBadge = 'status-pill-green';
                    $progressBarClass = 'progress-bar-green';
                    $countSangatTertib++;
                } elseif ($persentaseMesin >= 70) {
                    $statusDisiplin = 'baik';
                    $statusDisiplinLabel = 'Baik';
                    $statusDisiplinBadge = 'status-pill-blue';
                    $progressBarClass = 'progress-bar-blue';
                    $countCukupTertib++;
                } elseif ($persentaseMesin >= 50) {
                    $statusDisiplin = 'cukup';
                    $statusDisiplinLabel = 'Perlu pantau';
                    $statusDisiplinBadge = 'status-pill-amber';
                    $progressBarClass = 'progress-bar-amber';
                    $countCukupTertib++;
                } else {
                    $statusDisiplin = 'perlu_pembinaan';
                    $statusDisiplinLabel = 'Pembinaan';
                    $statusDisiplinBadge = 'status-pill-red';
                    $progressBarClass = 'progress-bar-red';
                    $countPerluPembinaan++;
                }
            } else {
                $persentaseMesin = 0;
                $statusDisiplin = 'tidak_hadir';
                $statusDisiplinLabel = 'Belum hadir';
                $statusDisiplinBadge = 'status-pill-gray';
                $progressBarClass = 'progress-bar-gray';
            }

            // Filter berdasarkan kategori kepatuhan
            if (!empty($selectedKategori)) {
                if ($selectedKategori === 'tertib' && !in_array($statusDisiplin, ['tertib', 'baik'])) {
                    continue;
                }
                if ($selectedKategori === 'cukup' && $statusDisiplin !== 'cukup') {
                    continue;
                }
                if ($selectedKategori === 'perlu_pembinaan' && $statusDisiplin !== 'perlu_pembinaan') {
                    continue;
                }
            }

            $rekapSiswa[] = [
                'siswa' => $siswa,
                'harian' => $harian,
                'total_hadir_mesin' => $totalHadirMesin,
                'total_hadir_manual' => $totalHadirManual,
                'total_hadir_piket' => $totalHadirPiket,
                'total_hadir' => $totalHadir,
                'total_sakit' => $totalSakit,
                'total_izin' => $totalIzin,
                'total_alfa' => $totalAlfa,
                'persentase_mesin' => $persentaseMesin,
                'status_disiplin' => $statusDisiplin,
                'status_label' => $statusDisiplinLabel,
                'status_badge' => $statusDisiplinBadge,
                'progress_class' => $progressBarClass,
            ];
        }

        $rataRataKepatuhan = $siswaWithAttendanceCount > 0 ? round($sumPersentase / $siswaWithAttendanceCount) : 0;
        $totalHasilFilter = count($rekapSiswa);

        // Pagination setup (20 items per page)
        $perPage = 20;
        $currentPage = Paginator::resolveCurrentPage() ?: 1;
        $collection = collect($rekapSiswa);
        $currentPageItems = $collection->slice(($currentPage - 1) * $perPage, $perPage)->values()->all();
        
        $paginatedRekap = new LengthAwarePaginator(
            $currentPageItems,
            $collection->count(),
            $perPage,
            $currentPage,
            ['path' => Paginator::resolveCurrentPath(), 'query' => $request->query()]
        );

        return view('absensi.laporan_kedisiplinan', compact(
            'layout',
            'setting',
            'user',
            'kelasList',
            'selectedKelasId',
            'selectedKategori',
            'tanggalPilihan',
            'startDateStr',
            'endDateStr',
            'daysInWeek',
            'paginatedRekap',
            'totalHasilFilter',
            'totalSiswa',
            'rataRataKepatuhan',
            'countSangatTertib',
            'countCukupTertib',
            'countPerluPembinaan'
        ));
    }

    public function exportExcel(Request $request)
    {
        $tanggalPilihan = $request->input('tanggal', Carbon::today()->toDateString());
        $selectedKelasId = $request->input('id_kelas');
        $selectedKategori = $request->input('kategori');

        $carbonDate = Carbon::parse($tanggalPilihan);
        $startOfWeek = $carbonDate->copy()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = $startOfWeek->copy()->addDays(5);

        $kelasNama = 'Semua Kelas';
        if (!empty($selectedKelasId)) {
            $k = Kelas::find($selectedKelasId);
            if ($k) {
                $kelasNama = $k->nama_kelas;
            }
        }

        $fileName = 'Laporan_Kedisiplinan_Absensi_' . $startOfWeek->format('d-m-Y') . '_sd_' . $endOfWeek->format('d-m-Y') . '.xlsx';

        return Excel::download(new LaporanKedisiplinanExport($tanggalPilihan, $selectedKelasId, $selectedKategori), $fileName);
    }
}
