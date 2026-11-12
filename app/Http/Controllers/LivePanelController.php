<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Setting;
use App\Models\Absensi;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\PointSiswa;
use App\Models\SiswaPkl;
use Carbon\Carbon;

class LivePanelController extends Controller
{
    /**
     * Tampilan Utama Live TV Panel Wallboard
     */
    public function index()
    {
        $data = $this->gatherPanelData();
        return view('panel.live_display', $data);
    }

    /**
     * Endpoint API JSON untuk Live Auto-Refresh Polling tanpa refresh halaman
     */
    public function getLiveStreamData()
    {
        $data = $this->gatherPanelData();
        return response()->json([
            'success' => true,
            'timestamp' => Carbon::now('Asia/Jakarta')->format('H:i:s'),
            'data' => $data
        ]);
    }

    /**
     * Agregasi data presensi, video, top ranking, dan keterlambatan
     */
    protected function gatherPanelData()
    {
        $setting = Setting::find(1);
        $today = Carbon::today('Asia/Jakarta')->toDateString();
        $now = Carbon::now('Asia/Jakarta');

        // 1. Dynamic Greeting & Waktu
        $hour = (int) $now->format('H');
        if ($hour >= 4 && $hour < 11) {
            $salam = 'SELAMAT PAGI';
        } elseif ($hour >= 11 && $hour < 15) {
            $salam = 'SELAMAT SIANG';
        } elseif ($hour >= 15 && $hour < 18) {
            $salam = 'SELAMAT SORE';
        } else {
            $salam = 'SELAMAT MALAM';
        }

        $namaSekolah = strtoupper($setting->nama_sekolah ?? 'SMK WISATA INDONESIA');
        $greetingHeader = "{$salam} - {$namaSekolah}";
        $tanggalFormatted = $now->locale('id')->isoFormat('dddd, D MMM Y');
        $jamFormatted = $now->format('H:i') . ' WIB';

        // 2. Video Source
        $videoUrl = null;
        if ($setting && $setting->video_panel && file_exists(public_path('storage/video/' . $setting->video_panel))) {
            $videoUrl = asset('storage/video/' . $setting->video_panel);
        }

        // 3. TOP 5 KEHADIRAN SISWA (BULAN INI) - Berdasarkan Persentase Kelas
        $startOfMonth = Carbon::now('Asia/Jakarta')->startOfMonth()->toDateString();
        $endOfMonth = $today;

        // Hitung total hari kerja yang ada data absen di bulan ini
        $distinctDaysCount = Absensi::whereBetween('tanggal', [$startOfMonth, $endOfMonth])
            ->distinct('tanggal')
            ->count('tanggal');
        $distinctDaysCount = max(1, $distinctDaysCount);

        $kelasList = Kelas::with('siswa')->orderBy('nama_kelas', 'asc')->get();
        $topKelasBulanan = $kelasList->map(function ($kelas) use ($startOfMonth, $endOfMonth, $distinctDaysCount) {
            $totalSiswa = $kelas->siswa->count();
            if ($totalSiswa === 0) {
                return [
                    'id_kelas' => $kelas->id_kelas,
                    'nama_kelas' => $kelas->nama_kelas,
                    'persen' => 0,
                    'persen_label' => '0%',
                    'total_hadir' => 0,
                    'total_siswa' => 0
                ];
            }

            // Total siswa hadir di kelas ini selama bulan berjalan
            $totalHadirBulanIni = Absensi::where('id_kelas', $kelas->id_kelas)
                ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
                ->where('kehadiran', 'hadir')
                ->count();

            $totalHarusnyaHadir = $totalSiswa * $distinctDaysCount;
            $persen = round(($totalHadirBulanIni / max(1, $totalHarusnyaHadir)) * 100, 1);
            if ($persen > 100) $persen = 100;

            return [
                'id_kelas' => $kelas->id_kelas,
                'nama_kelas' => $kelas->nama_kelas,
                'persen' => $persen,
                'persen_label' => ($persen == 100 ? '100%' : number_format($persen, 1, ',', '.') . '%'),
                'total_hadir' => $totalHadirBulanIni,
                'total_siswa' => $totalSiswa
            ];
        })
        ->sortByDesc('persen')
        ->values()
        ->take(5);

        // 4. TOP 5 SISWA DENGAN KEHADIRAN TERBAIK & TERAJIN BULAN INI (Hierarki Kedisiplinan Bertingkat)
        $startOfLastMonth = Carbon::now('Asia/Jakarta')->subMonth()->startOfMonth()->toDateString();
        $endOfLastMonth = Carbon::now('Asia/Jakarta')->subMonth()->endOfMonth()->toDateString();

        $absensiBulanLaluCounts = Absensi::whereBetween('tanggal', [$startOfLastMonth, $endOfLastMonth])
            ->where('kehadiran', 'hadir')
            ->select('id_siswa', DB::raw('count(*) as total'))
            ->groupBy('id_siswa')
            ->pluck('total', 'id_siswa');

        $absensiBulanIni = Absensi::whereBetween('tanggal', [$startOfMonth, $endOfMonth])
            ->select('id_siswa', 'id_kelas', 'kehadiran', 'keterangan', 'jam_masuk')
            ->get();

        $siswaRanking = $absensiBulanIni->groupBy('id_siswa')->map(function ($records, $idSiswa) use ($absensiBulanLaluCounts, $distinctDaysCount) {
            $totalHadir = $records->where('kehadiran', 'hadir')->count();
            if ($totalHadir === 0) return null;

            $totalTerlambat = $records->filter(function ($r) {
                return stripos($r->keterangan ?? '', 'terlambat') !== false;
            })->count();

            // Hitung rata-rata menit jam masuk pagi (antara 05:00 s.d 12:00)
            $totalMenit = 0;
            $validJamCount = 0;
            foreach ($records as $r) {
                if ($r->kehadiran === 'hadir' && !empty($r->jam_masuk) && $r->jam_masuk !== '-') {
                    $parts = explode(':', $r->jam_masuk);
                    if (count($parts) >= 2) {
                        $h = (int) $parts[0];
                        $m = (int) $parts[1];
                        if ($h >= 5 && $h <= 12) {
                            $totalMenit += ($h * 60 + $m);
                            $validJamCount++;
                        }
                    }
                }
            }
            $avgMenit = $validJamCount > 0 ? round($totalMenit / $validJamCount) : 480;
            $avgJam = sprintf('%02d:%02d', floor($avgMenit / 60), $avgMenit % 60);

            $persen = round(($totalHadir / max(1, $distinctDaysCount)) * 100);
            if ($persen > 100) $persen = 100;

            return [
                'id_siswa' => $idSiswa,
                'total_hadir' => $totalHadir,
                'total_terlambat' => $totalTerlambat,
                'avg_menit' => $avgMenit,
                'avg_jam' => $avgJam,
                'persen' => $persen,
                'persen_label' => ($persen == 100 ? '100%' : $persen . '%'),
                'hadir_bulan_lalu' => $absensiBulanLaluCounts[$idSiswa] ?? 0,
            ];
        })->filter()->sort(function ($a, $b) {
            // Level 1: Total Hadir Terbanyak Bulan Ini (DESC)
            if ($a['total_hadir'] !== $b['total_hadir']) {
                return $b['total_hadir'] <=> $a['total_hadir'];
            }
            // Level 2: Keterlambatan Terendah / Nol Terlambat (ASC)
            if ($a['total_terlambat'] !== $b['total_terlambat']) {
                return $a['total_terlambat'] <=> $b['total_terlambat'];
            }
            // Level 3: Rata-Rata Jam Masuk Paling Pagi (ASC)
            if ($a['avg_menit'] !== $b['avg_menit']) {
                return $a['avg_menit'] <=> $b['avg_menit'];
            }
            // Level 4: Rekam Jejak Kehadiran Bulan Lalu (DESC)
            return $b['hadir_bulan_lalu'] <=> $a['hadir_bulan_lalu'];
        })->take(5);

        $topSiswaIds = $siswaRanking->pluck('id_siswa');
        $siswasMap = Siswa::with('kelas')->whereIn('id_siswa', $topSiswaIds)->get()->keyBy('id_siswa');

        $topSiswaBulanan = $siswaRanking->map(function ($item) use ($siswasMap) {
            $siswa = $siswasMap->get($item['id_siswa']);
            $nama = $siswa->nama_siswa ?? 'Siswa';
            $kelas = $siswa->kelas->nama_kelas ?? '-';

            return [
                'id_siswa' => $item['id_siswa'],
                'nama' => $nama,
                'kelas' => $kelas,
                'total_hadir' => $item['total_hadir'],
                'persen' => $item['persen'],
                'persen_label' => $item['persen_label'],
                'total_terlambat' => $item['total_terlambat'],
                'avg_jam' => $item['avg_jam'],
            ];
        })->values();

        // 5. TOP 5 KEHADIRAN SISWA TERCEPAT HARI INI (Early Birds)
        $topSiswaTercepat = Absensi::whereDate('tanggal', $today)
            ->where('kehadiran', 'hadir')
            ->where('jam_masuk', '!=', '-')
            ->orderBy('jam_masuk', 'asc')
            ->with(['siswa.kelas', 'kelas'])
            ->limit(5)
            ->get()
            ->map(function ($item) {
                $nama = $item->siswa->nama_siswa ?? 'Siswa';
                $kelas = $item->kelas->nama_kelas ?? ($item->siswa->kelas->nama_kelas ?? '-');
                $jam = substr($item->jam_masuk, 0, 5); // Format HH:MM
                return [
                    'nama' => $nama,
                    'kelas' => $kelas,
                    'jam_masuk' => $jam
                ];
            });

        // 5. DATA SISWA TERLAMBAT HARI INI
        $siswaTerlambatList = Absensi::whereDate('tanggal', $today)
            ->where('kehadiran', 'hadir')
            ->where('keterangan', 'like', '%Terlambat%')
            ->with(['siswa.kelas', 'kelas'])
            ->orderBy('jam_masuk', 'asc')
            ->get()
            ->map(function ($item) {
                $siswa = $item->siswa;
                $nama = $siswa->nama_siswa ?? 'Siswa';
                $kelas = $item->kelas->nama_kelas ?? ($siswa->kelas->nama_kelas ?? '-');
                $jam = substr($item->jam_masuk, 0, 5); // Format HH:MM
                
                // Ambil total poin pelanggaran siswa
                $totalPoin = 0;
                if ($siswa) {
                    $totalPoin = PointSiswa::where('id_siswa', $siswa->id_siswa)->sum('skor_point');
                    if (!$totalPoin || $totalPoin == 0) {
                        $totalPoin = 5; // Default poin keterlambatan
                    }
                }

                // Foto siswa
                $fotoUrl = null;
                if ($siswa && $siswa->foto && $siswa->foto !== 'avatar.jpg') {
                    if (file_exists(public_path('storage/foto-siswa/' . $siswa->foto))) {
                        $fotoUrl = asset('storage/foto-siswa/' . $siswa->foto);
                    } elseif (file_exists(storage_path('app/public/foto-siswa/' . $siswa->foto))) {
                        $fotoUrl = asset('storage/foto-siswa/' . $siswa->foto);
                    } elseif (file_exists(public_path('storage/gambar/' . $siswa->foto))) {
                        $fotoUrl = asset('storage/gambar/' . $siswa->foto);
                    }
                }

                return [
                    'id_siswa' => $item->id_siswa,
                    'nama' => $nama,
                    'kelas' => $kelas,
                    'jam_masuk' => $jam,
                    'poin' => (int) $totalPoin,
                    'foto_url' => $fotoUrl,
                    'inisial' => strtoupper(substr($nama, 0, 1))
                ];
            });

        // 6. RIWAYAT ABSENSI TERBARU (UNTUK NOTIFIKASI REAL-TIME DI LIVE PANEL)
        $latestAbsensi = Absensi::whereDate('tanggal', $today)
            ->where(function($q) {
                $q->where(function($m) {
                    $m->whereNotNull('jam_masuk')
                      ->where('jam_masuk', '!=', '-')
                      ->where('jam_masuk', '!=', '');
                })->orWhere(function($p) {
                    $p->whereNotNull('jam_pulang')
                      ->where('jam_pulang', '!=', '-')
                      ->where('jam_pulang', '!=', '');
                });
            })
            ->with(['siswa.kelas', 'kelas'])
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                $siswa = $item->siswa;
                $nama = $siswa->nama_siswa ?? 'Siswa';
                $kelas = $item->kelas->nama_kelas ?? ($siswa->kelas->nama_kelas ?? '-');
                $isPulang = (!empty($item->jam_pulang) && $item->jam_pulang !== '-' && $item->jam_pulang !== '');
                $jam = $isPulang ? substr($item->jam_pulang, 0, 5) : substr($item->jam_masuk, 0, 5);
                $tipe = $isPulang ? 'PULANG' : 'MASUK';
                $isTerlambat = (stripos($item->keterangan ?? '', 'terlambat') !== false);

                // Foto siswa
                $fotoUrl = null;
                if ($siswa && $siswa->foto && $siswa->foto !== 'avatar.jpg') {
                    if (file_exists(public_path('storage/foto-siswa/' . $siswa->foto))) {
                        $fotoUrl = asset('storage/foto-siswa/' . $siswa->foto);
                    } elseif (file_exists(storage_path('app/public/foto-siswa/' . $siswa->foto))) {
                        $fotoUrl = asset('storage/foto-siswa/' . $siswa->foto);
                    } elseif (file_exists(public_path('storage/gambar/' . $siswa->foto))) {
                        $fotoUrl = asset('storage/gambar/' . $siswa->foto);
                    }
                }

                $key = $item->id_absensi . '_' . ($isPulang ? 'pulang' : 'masuk') . '_' . $jam;

                return [
                    'key' => $key,
                    'id_absensi' => $item->id_absensi,
                    'nama' => $nama,
                    'nis' => $siswa->nis ?? $siswa->nisn ?? '-',
                    'kelas' => $kelas,
                    'jam' => $jam,
                    'tipe' => $tipe,
                    'status' => $item->kehadiran,
                    'is_terlambat' => $isTerlambat,
                    'keterangan' => $item->keterangan ?? '',
                    'foto_url' => $fotoUrl,
                    'inisial' => strtoupper(substr($nama, 0, 1)),
                    'timestamp' => $item->updated_at ? $item->updated_at->timestamp : time()
                ];
            });

        // 7. Statistik Kehadiran Seluruh Kelas & Kurva Tren 7 Hari (Khusus Siswa Non-PKL)
        $classAnalytics = $this->buildNonPklClassAndTrendStats($kelasList, $today);

        return [
            'setting' => $setting,
            'greetingHeader' => $greetingHeader,
            'salam' => $salam,
            'namaSekolah' => $namaSekolah,
            'tanggalFormatted' => $tanggalFormatted,
            'jamFormatted' => $jamFormatted,
            'videoUrl' => $videoUrl,
            'panelVideoLoop' => max(1, (int) ($setting->panel_video_loop ?? 2)),
            'panelDataDuration' => max(15, (int) ($setting->panel_data_duration ?? 120)),
            'topKelasBulanan' => $topKelasBulanan,
            'topSiswaBulanan' => $topSiswaBulanan,
            'topSiswaTercepat' => $topSiswaTercepat,
            'siswaTerlambatList' => $siswaTerlambatList,
            'totalTerlambat' => $siswaTerlambatList->count(),
            'latestAbsensi' => $latestAbsensi,
            'allKelasToday' => $classAnalytics['allKelasToday'],
            'schoolTodaySummary' => $classAnalytics['schoolTodaySummary'],
            'kehadiranTrend7Days' => $classAnalytics['kehadiranTrend7Days'],
        ];
    }

    /**
     * Hitung tingkat kehadiran hari ini per kelas dan tren 7 hari aktif terakhir
     * hanya dari siswa yang tidak sedang PKL (non-PKL).
     */
    protected function buildNonPklClassAndTrendStats($kelasList, string $today): array
    {
        $siswaPklRows = SiswaPkl::where('status', '!=', 'selesai')
            ->select('id_siswa', 'tanggal_mulai', 'tanggal_selesai')
            ->get();

        $getPklIdSetForDate = function (string $date) use ($siswaPklRows): array {
            $set = [];
            foreach ($siswaPklRows as $row) {
                $mulai = $row->tanggal_mulai ? substr((string) $row->tanggal_mulai, 0, 10) : null;
                $selesai = $row->tanggal_selesai ? substr((string) $row->tanggal_selesai, 0, 10) : null;
                if ($mulai && $date < $mulai) {
                    continue;
                }
                if ($selesai && $date > $selesai) {
                    continue;
                }
                $set[$row->id_siswa] = true;
            }
            return $set;
        };

        $pklTodaySet = $getPklIdSetForDate($today);

        $absensiTodayBySiswa = Absensi::whereDate('tanggal', $today)
            ->select('id_siswa', 'kehadiran', 'keterangan')
            ->get()
            ->keyBy('id_siswa');

        $allKelasToday = [];
        $kelasFullPkl = [];
        $totalNonPklAll = 0;
        $totalHadirAll = 0;
        $totalTerlambatAll = 0;
        $totalTidakHadirAll = 0;
        $totalBelumAbsenAll = 0;
        $totalPklToday = 0;

        // Kelompokkan siswa per tingkat (X, XI, XII) untuk kurva tren
        $studentsByLevel = [
            'X' => [],
            'XI' => [],
            'XII' => [],
        ];
        $allStudentIds = [];

        $parseTingkat = function (string $namaKelas): string {
            if (preg_match('/^(XII|XI|X)(?:[- ]|$)/', strtoupper(trim($namaKelas)), $m)) {
                return $m[1];
            }
            return 'LAIN';
        };

        $sortedKelas = $kelasList->sortBy(function ($kelas) use ($parseTingkat) {
            $rankMap = ['X' => 1, 'XI' => 2, 'XII' => 3];
            $tingkat = $parseTingkat((string) $kelas->nama_kelas);
            $rank = $rankMap[$tingkat] ?? 9;
            return sprintf('%d-%s', $rank, $kelas->nama_kelas);
        })->values();

        foreach ($sortedKelas as $kelas) {
            $tingkat = $parseTingkat((string) $kelas->nama_kelas);

            $nonPklStudents = [];
            $pklCountInKelas = 0;

            foreach ($kelas->siswa as $siswa) {
                $allStudentIds[] = $siswa->id_siswa;
                if (isset($studentsByLevel[$tingkat])) {
                    $studentsByLevel[$tingkat][] = $siswa->id_siswa;
                }

                if (isset($pklTodaySet[$siswa->id_siswa])) {
                    $pklCountInKelas++;
                    $totalPklToday++;
                } else {
                    $nonPklStudents[] = $siswa->id_siswa;
                }
            }

            $nonPklCount = count($nonPklStudents);
            if ($nonPklCount === 0) {
                if ($pklCountInKelas > 0) {
                    $kelasFullPkl[] = $kelas->nama_kelas;
                }
                continue;
            }

            $hadir = 0;
            $terlambat = 0;
            $tidakHadir = 0;
            $belumAbsen = 0;

            foreach ($nonPklStudents as $idSiswa) {
                $absen = $absensiTodayBySiswa->get($idSiswa);
                if (!$absen) {
                    $belumAbsen++;
                    continue;
                }
                $status = strtolower(trim((string) $absen->kehadiran));
                if ($status === 'hadir' || $status === 'masuk') {
                    $hadir++;
                    if (stripos((string) $absen->keterangan, 'terlambat') !== false) {
                        $terlambat++;
                    }
                } else {
                    $tidakHadir++;
                }
            }

            $persen = round(($hadir / $nonPklCount) * 100, 1);

            $totalNonPklAll += $nonPklCount;
            $totalHadirAll += $hadir;
            $totalTerlambatAll += $terlambat;
            $totalTidakHadirAll += $tidakHadir;
            $totalBelumAbsenAll += $belumAbsen;

            $allKelasToday[] = [
                'id_kelas' => $kelas->id_kelas,
                'nama_kelas' => $kelas->nama_kelas,
                'tingkat' => $tingkat,
                'total_non_pkl' => $nonPklCount,
                'total_pkl' => $pklCountInKelas,
                'hadir' => $hadir,
                'terlambat' => $terlambat,
                'tidak_hadir' => $tidakHadir,
                'belum_absen' => $belumAbsen,
                'persen' => $persen,
            ];
        }

        $schoolTodaySummary = [
            'total_siswa_non_pkl' => $totalNonPklAll,
            'total_hadir' => $totalHadirAll,
            'total_terlambat' => $totalTerlambatAll,
            'total_tidak_hadir' => $totalTidakHadirAll,
            'total_belum_absen' => $totalBelumAbsenAll,
            'persen_hadir' => $totalNonPklAll > 0 ? round(($totalHadirAll / $totalNonPklAll) * 100, 1) : 0,
            'total_siswa_pkl' => $totalPklToday,
            'jumlah_kelas_aktif' => count($allKelasToday),
            'kelas_full_pkl' => $kelasFullPkl,
        ];

        // Kurva Tren 7 Hari Aktif Terakhir
        $recentDates = Absensi::whereDate('tanggal', '<=', $today)
            ->select('tanggal')
            ->distinct()
            ->orderBy('tanggal', 'desc')
            ->limit(7)
            ->pluck('tanggal')
            ->reverse()
            ->values();

        $trendLabels = [];
        $trendAll = [];
        $trendX = [];
        $trendXI = [];
        $trendXII = [];

        if ($recentDates->isNotEmpty()) {
            $absensiTrendRows = Absensi::whereIn('tanggal', $recentDates)
                ->whereIn('kehadiran', ['hadir', 'masuk'])
                ->select('id_siswa', 'tanggal')
                ->get()
                ->groupBy(fn ($r) => substr((string) $r->tanggal, 0, 10));

            foreach ($recentDates as $tgl) {
                $tglStr = substr((string) $tgl, 0, 10);
                $pklSetOnDate = $getPklIdSetForDate($tglStr);
                $hadirSet = [];
                foreach ($absensiTrendRows->get($tglStr, collect()) as $r) {
                    $hadirSet[$r->id_siswa] = true;
                }

                $calcPct = function (array $studentIds) use ($pklSetOnDate, $hadirSet): ?float {
                    $nonPkl = 0;
                    $hadir = 0;
                    foreach ($studentIds as $sid) {
                        if (isset($pklSetOnDate[$sid])) {
                            continue;
                        }
                        $nonPkl++;
                        if (isset($hadirSet[$sid])) {
                            $hadir++;
                        }
                    }
                    return $nonPkl > 0 ? round(($hadir / $nonPkl) * 100, 1) : null;
                };

                try {
                    $trendLabels[] = Carbon::parse($tglStr)->locale('id')->isoFormat('ddd, D/M');
                } catch (\Exception $e) {
                    $trendLabels[] = $tglStr;
                }
                $trendAll[] = $calcPct($allStudentIds);
                $trendX[] = $calcPct($studentsByLevel['X']);
                $trendXI[] = $calcPct($studentsByLevel['XI']);
                $trendXII[] = $calcPct($studentsByLevel['XII']);
            }
        }

        return [
            'allKelasToday' => $allKelasToday,
            'schoolTodaySummary' => $schoolTodaySummary,
            'kehadiranTrend7Days' => [
                'labels' => $trendLabels,
                'all' => $trendAll,
                'X' => $trendX,
                'XI' => $trendXI,
                'XII' => $trendXII,
            ],
        ];
    }
}
