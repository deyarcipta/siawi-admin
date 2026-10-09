<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Absensi;
use App\Models\AbsensiGuru;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\PointSiswa;
use App\Models\Modul;
use App\Models\SiswaPkl;
use App\Models\Setting;
use App\Models\JadwalMapel;
use App\Models\GuruPiket;
use App\Models\PiketPembiasaanPagi;
use App\Jobs\SendWhatsAppAttendanceNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use DB;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $layout = 'layout.app';
        $setting = Setting::find(1);
        $user = Auth::user();
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

        $guruPiketHariIni = \App\Models\GuruPiket::with('guru')
            ->where('hari', $todayDayInd)
            ->get();

        $piketPembiasaanHariIni = \App\Models\PiketPembiasaanPagi::with('guru')
            ->where('hari', $todayDayInd)
            ->get();

        // Mengambil total jumlah siswa
        $totalSiswa = Siswa::count();
        $totalModul = Modul::count();

        // Menghitung total siswa yang hadir hari ini
        $totalHadir = Absensi::where('tanggal', $today)
                            ->where('kehadiran', 'hadir')
                            ->count();

        // Menghitung total guru
        $totalGuru = Guru::count();
        $totalGuruHadir = AbsensiGuru::where('tanggal', $today)->count();

        // Mengambil absensi siswa yang tidak hadir (izin, sakit, alfa)
        $statusKehadiran = ['alfa', 'izin', 'sakit'];
        $absensiTidakHadir = Absensi::where('tanggal', $today)
                                ->whereIn('kehadiran', $statusKehadiran)
                                ->with('siswa', 'kelas')
                                ->get();

        // Menghitung jumlah ketidakhadiran berdasarkan kelas
        $jumlahTidakHadir = $absensiTidakHadir->groupBy('id_kelas')->map->count();

        $jumlahTidakHadirAll = $absensiTidakHadir->groupBy('id_kelas')->map->count()->sum();

        // Ambil 10 siswa yang paling cepat hadir hari ini
        $siswaTerajin = Absensi::where('tanggal', $today)
            ->where('kehadiran', 'hadir') // Filter hanya yang hadir
            ->where('jam_masuk', '!=', '-') // Filter jam_masuk yang bukan tanda
            ->orderBy('jam_masuk', 'asc') // Urutkan dari yang paling awal datang
            ->with('siswa', 'kelas') // Ambil relasi siswa dan kelas
            ->limit(5) // Ambil hanya 5 siswa
            ->get();

        $guruTerajin = AbsensiGuru::where('tanggal', $today)
            ->where('kehadiran', 'hadir') // Filter hanya yang hadir
            ->where('jam_masuk', '!=', '-') // Filter jam_masuk yang bukan tanda
            ->orderBy('jam_masuk', 'asc') // Urutkan dari yang paling awal datang 
            ->limit(5) // Ambil hanya 5 guru
            ->get();

        // Ambil daftar ID siswa yang sedang PKL (status = PKL)
        $siswaSedangPKL = SiswaPkl::where('status', 'PKL')->pluck('id_siswa')->toArray();

        // Mengambil semua kelas beserta jumlah siswa dan yang belum absen
        $kelasData = Kelas::with('siswa', 'jurusan')->get()->map(function ($kelas) use ($today, $siswaSedangPKL) {
            $totalSiswaKelas = $kelas->siswa->count();

            // Ambil ID siswa yang sudah absen hari ini
            $siswaSudahAbsen = Absensi::where('tanggal', $today)
                ->where('id_kelas', $kelas->id_kelas)
                ->pluck('id_siswa')
                ->toArray();

            // Ambil daftar siswa yang belum absen dan tidak sedang PKL
            $siswaBelumAbsen = $kelas->siswa->filter(function ($siswa) use ($siswaSudahAbsen, $siswaSedangPKL) {
                return !in_array($siswa->id_siswa, $siswaSudahAbsen) && !in_array($siswa->id_siswa, $siswaSedangPKL);
            });

            return [
                'kelas' => $kelas,
                'jurusan' => $kelas->jurusan->nama_jurusan ?? 'Tidak Diketahui',
                'id_jurusan' => optional($kelas->jurusan)->id_jurusan,
                'totalSiswaKelas' => $totalSiswaKelas,
                'jumlahBelumAbsen' => $siswaBelumAbsen->count(),
                'siswaBelumAbsen' => $siswaBelumAbsen
            ];
        })
        // **Filter hanya kelas yang masih ada siswa yang belum absen**
        ->filter(function ($data) {
            return $data['jumlahBelumAbsen'] > 0;
        })
        // **Sorting berdasarkan jenjang kelas (X, XI, XII) terlebih dahulu**
        ->sortBy(function ($data) {
            $namaKelas = $data['kelas']->nama_kelas;

            // Memisahkan angka kelas (X, XI, XII) dan nama setelah "-"
            preg_match('/^(X{1,3})-(.*)$/', $namaKelas, $matches);

            if (isset($matches[1]) && isset($matches[2])) {
                // Konversi X, XI, XII ke angka untuk sorting
                $tingkat = ['X' => 1, 'XI' => 2, 'XII' => 3][$matches[1]];
                $namaLanjutan = $matches[2];
            } else {
                // Jika format tidak sesuai, anggap tingkat paling bawah
                $tingkat = 4;
                $namaLanjutan = $namaKelas;
            }

            return [$tingkat, $namaLanjutan];
        });

        // --- DATA ANALYTICS ---
        // 1. Trend Kehadiran Mingguan (7 hari aktif terakhir)
        $recentDates = Absensi::select('tanggal')
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'desc')
            ->limit(7)
            ->pluck('tanggal')
            ->reverse()
            ->toArray();

        $weeklyAttendance = [];
        $dateLabels = [];
        foreach ($recentDates as $date) {
            $hadirCount = Absensi::where('tanggal', $date)->where('kehadiran', 'hadir')->count();
            $rate = $totalSiswa > 0 ? round(($hadirCount / $totalSiswa) * 100) : 0;
            
            try {
                $formattedDate = Carbon::parse($date)->locale('id')->translatedFormat('d M');
            } catch (\Exception $e) {
                $formattedDate = $date;
            }
            $dateLabels[] = $formattedDate;
            $weeklyAttendance[] = $rate;
        }

        // 2. Distribusi Kategori Pelanggaran
        $violationStats = PointSiswa::join('point', 'point_siswa.id_point', '=', 'point.id_point')
            ->select('point.jenis_point', DB::raw('count(*) as total'))
            ->groupBy('point.jenis_point')
            ->get();

        $violationCategories = [];
        $violationCounts = [];
        foreach ($violationStats as $stat) {
            $violationCategories[] = $stat->jenis_point;
            $violationCounts[] = $stat->total;
        }

        // 3. Radar Siswa Kritis (5 siswa dengan point terbanyak)
        $siswaKritis = PointSiswa::select('id_siswa', DB::raw('SUM(skor_point) as total_skor'))
            ->groupBy('id_siswa')
            ->orderBy('total_skor', 'desc')
            ->with('siswa.kelas')
            ->limit(5)
            ->get();

        // Hitung persentase kehadiran siswa
        $persenHadir = $totalSiswa > 0 ? round(($totalHadir / $totalSiswa) * 100, 1) : 0;
        $persenHadirFormatted = str_replace('.', ',', (string)$persenHadir);

        // Siswa yang belum absen sama sekali
        $totalBelumAbsen = max(0, $totalSiswa - ($totalHadir + $jumlahTidakHadirAll));

        // Jumlah guru yang belum hadir
        $guruBelumHadirCount = max(0, $totalGuru - $totalGuruHadir);

        // Jumlah kelas yang belum selesai absen
        $kelasBelumAbsenCount = $kelasData->count();

        // Greeting & Ikon dinamis berdasarkan jam
        $currentHour = (int) Carbon::now()->format('H');
        if ($currentHour >= 4 && $currentHour < 11) {
            $greetingText = 'Selamat pagi';
            $greetingIcon = 'fas fa-cloud-sun';
            $greetingIconColor = '#f59e0b'; // Amber hangat matahari pagi
            $greetingIconBg = '#fffbeb';
            $greetingIconBorder = '#fef3c7';
        } elseif ($currentHour >= 11 && $currentHour < 15) {
            $greetingText = 'Selamat siang';
            $greetingIcon = 'fas fa-sun';
            $greetingIconColor = '#eab308'; // Kuning keemasan cerah matahari siang
            $greetingIconBg = '#fefce8';
            $greetingIconBorder = '#fef08a';
        } elseif ($currentHour >= 15 && $currentHour < 18) {
            $greetingText = 'Selamat sore';
            $greetingIcon = 'fas fa-cloud-sun';
            $greetingIconColor = '#f97316'; // Oranye jingga senja
            $greetingIconBg = '#fff7ed';
            $greetingIconBorder = '#ffedd5';
        } else {
            $greetingText = 'Selamat malam';
            $greetingIcon = 'fas fa-moon';
            $greetingIconColor = '#6366f1'; // Indigo lembut malam
            $greetingIconBg = '#eef2ff';
            $greetingIconBorder = '#e0e7ff';
        }

        // Tanggal Indonesia dinamis
        try {
            $tanggalHariIni = Carbon::now()->locale('id')->translatedFormat('l, d F Y');
        } catch (\Exception $e) {
            $tanggalHariIni = $todayDayInd . ', ' . Carbon::now()->format('d M Y');
        }

        // Menyiapkan data waktu piket jika belum ada field jam_mulai/jam_selesai
        $defaultPiketSlots = ['06.30 - 10.00', '10.00 - 12.30', '12.30 - 15.00', '15.00 - 17.00'];
        foreach ($guruPiketHariIni as $idx => $piket) {
            if (empty($piket->jam_tugas)) {
                $piket->jam_tugas = $defaultPiketSlots[$idx % count($defaultPiketSlots)];
            }
        }

        // Format data siswa kritis dengan status SP dan styling
        $siswaKritisFormatted = $siswaKritis->map(function ($item) {
            $skor = $item->total_skor;
            if ($skor >= 70) {
                $statusSP = 'SP 1 (Orang tua)';
                $badgeClass = 'sp-danger';
            } elseif ($skor >= 45) {
                $statusSP = 'SP 1';
                $badgeClass = 'sp-warning';
            } elseif ($skor >= 30) {
                $statusSP = 'Teguran Tertulis';
                $badgeClass = 'sp-amber';
            } else {
                $statusSP = 'Perhatian';
                $badgeClass = 'sp-info';
            }
            $item->status_sp = $statusSP;
            $item->badge_class = $badgeClass;
            return $item;
        });

        // 4. Data Siswa Terlambat Hari Ini
        $totalTerlambat = Absensi::where('tanggal', $today)
            ->where('kehadiran', 'hadir')
            ->where('keterangan', 'like', '%Terlambat%')
            ->count();

        $siswaTerlambat = Absensi::where('tanggal', $today)
            ->where('kehadiran', 'hadir')
            ->where('keterangan', 'like', '%Terlambat%')
            ->with('siswa', 'kelas')
            ->orderBy('jam_masuk', 'desc')
            ->get();

        return view('dashboard', compact(
            'layout',
            'setting',
            'absensiTidakHadir',
            'totalSiswa',
            'jumlahTidakHadir',
            'totalModul',
            'user',
            'totalHadir',
            'persenHadir',
            'persenHadirFormatted',
            'totalBelumAbsen',
            'kelasData',
            'kelasBelumAbsenCount',
            'totalGuruHadir',
            'totalGuru',
            'guruBelumHadirCount',
            'jumlahTidakHadirAll',
            'siswaTerajin',
            'guruTerajin',
            'guruPiketHariIni',
            'piketPembiasaanHariIni',
            'todayDayInd',
            'tanggalHariIni',
            'greetingText',
            'greetingIcon',
            'greetingIconColor',
            'greetingIconBg',
            'greetingIconBorder',
            'weeklyAttendance',
            'dateLabels',
            'violationCategories',
            'violationCounts',
            'siswaKritis',
            'siswaKritisFormatted',
            'totalTerlambat',
            'siswaTerlambat'
        ));
    }

    public function login()
    {
        return view('login');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id_guru)
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        $edit = Guru::find($id_guru);
        $kelasWali = Kelas::with(['siswa', 'jurusan', 'level'])->where('id_guru', $id_guru)->get();

        // Ambil jadwal mengajar guru beserta mata pelajaran dan kelas (urut dari Senin s/d Sabtu)
        $jadwalMengajar = JadwalMapel::with(['mapel', 'kelas.jurusan'])
            ->where('id_guru', $id_guru)
            ->orderByRaw("FIELD(LOWER(hari), 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu')")
            ->orderByRaw('CAST(jam_awal AS UNSIGNED) ASC')
            ->get();

        // Hitung total JP per minggu dan kelompokkan per mapel & kelas
        $totalJpSeminggu = 0;
        foreach ($jadwalMengajar as $j) {
            $jp = max(1, (intval($j->jam_akhir) - intval($j->jam_awal) + 1));
            $j->jp_count = $jp;
            $j->hari_formatted = ucfirst(strtolower($j->hari));
            $totalJpSeminggu += $jp;
        }

        // Kelompokkan ringkasan mapel yang diampu
        $mapelDiampu = $jadwalMengajar->groupBy('id_mapel')->map(function ($items) {
            $first = $items->first();
            $namaMapel = $first->mapel->nama_mapel ?? 'Mata Pelajaran';
            $kodeMapel = $first->mapel->kode_mapel ?? '';
            $totalJpMapel = $items->sum('jp_count');
            $kelasList = $items->pluck('kelas.nama_kelas')->unique()->filter()->values();
            return [
                'nama_mapel' => $namaMapel,
                'kode_mapel' => $kodeMapel,
                'total_jp' => $totalJpMapel,
                'kelas_list' => $kelasList,
                'jadwal_items' => $items,
            ];
        });

        // Ambil penugasan piket jika ada
        $jadwalPiket = \App\Models\GuruPiket::where('id_guru', $id_guru)->get();
        $jadwalPembiasaan = \App\Models\PiketPembiasaanPagi::where('id_guru', $id_guru)->get();

        return view('dataGuru.edit_profile', compact(
            'layout', 'edit', 'setting', 'user', 'kelasWali',
            'jadwalMengajar', 'totalJpSeminggu', 'mapelDiampu',
            'jadwalPiket', 'jadwalPembiasaan'
        ));
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id_guru)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'nullable|string|min:8',
            'nama_guru' => 'required|string|max:255',
            'no_hp' => 'nullable|string|max:25',
            'role' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        $guru = Guru::findOrFail($id_guru);

        $guru->username = $request->username;
        $guru->nama_guru = $request->nama_guru;
        $guru->no_hp = $request->no_hp;
        $guru->role = $request->role;

        // Proses upload foto profil
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($guru->foto && Storage::disk('public')->exists('foto_guru/' . $guru->foto)) {
                Storage::disk('public')->delete('foto_guru/' . $guru->foto);
            }

            $file = $request->file('foto');
            $nama_file = 'guru_' . $guru->id_guru . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('foto_guru', $nama_file, 'public');
            $guru->foto = $nama_file;
        }

        if ($request->filled('password')) {
            $guru->password = Hash::make($request->password);
        }

        $guru->save();

        return redirect()->back()->with('success', 'Profil guru, foto, dan kredensial berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Kirim pengingat WhatsApp ke Wali Kelas untuk kelas tertentu
     */
    public function ingatkanWaliKelas(Request $request, $id_kelas)
    {
        $kelas = Kelas::with(['waliKelas', 'siswa'])->findOrFail($id_kelas);
        $setting = Setting::first();
        $namaSekolah = $setting->nama_sekolah ?? 'SMK Wisata Indonesia';
        $today = Carbon::now()->toDateString();
        $todayLabel = Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y');

        if (!$kelas->waliKelas || empty($kelas->waliKelas->no_hp)) {
            return response()->json([
                'success' => false,
                'message' => "Wali Kelas untuk kelas {$kelas->nama_kelas} belum diatur atau nomor WhatsApp belum terisi di data guru."
            ], 422);
        }

        $siswaSedangPKL = SiswaPkl::pluck('id_siswa')->toArray();
        $siswaSudahAbsen = Absensi::where('tanggal', $today)
            ->where('id_kelas', $kelas->id_kelas)
            ->pluck('id_siswa')
            ->toArray();

        $siswaBelumAbsen = $kelas->siswa->filter(function ($siswa) use ($siswaSudahAbsen, $siswaSedangPKL) {
            return !in_array($siswa->id_siswa, $siswaSudahAbsen) && !in_array($siswa->id_siswa, $siswaSedangPKL);
        });

        $jumlahBelumAbsen = $siswaBelumAbsen->count();

        if ($jumlahBelumAbsen == 0) {
            return response()->json([
                'success' => false,
                'message' => "Seluruh siswa di kelas {$kelas->nama_kelas} sudah selesai diabsen hari ini."
            ], 422);
        }

        $wali = $kelas->waliKelas;
        $pesan = "📢 *PENGINGAT PRESENSI HARIAN - {$namaSekolah}*\n\n"
               . "Yth. Bapak/Ibu *{$wali->nama_guru}*\n"
               . "Wali Kelas: *{$kelas->nama_kelas}*\n\n"
               . "Diberitahukan bahwa terdapat *{$jumlahBelumAbsen} siswa* di kelas Anda yang *belum melakukan presensi* pada hari ini (*{$todayLabel}*).\n\n"
               . "Mohon kesediaannya untuk segera memeriksa dan melengkapi presensi siswa melalui aplikasi SIAWI.\n\n"
               . "Terima kasih atas kerja sama dan dedikasi Bapak/Ibu.\n"
               . "_Pesan otomatis sistem SIAWI_";

        SendWhatsAppAttendanceNotification::dispatch($wali->no_hp, $pesan);

        return response()->json([
            'success' => true,
            'message' => "Pesan pengingat berhasil dikirimkan ke antrean WhatsApp Wali Kelas {$wali->nama_guru} ({$kelas->nama_kelas})."
        ]);
    }

    /**
     * Kirim pengingat WhatsApp ke semua Wali Kelas yang kelasnya belum absen
     */
    public function ingatkanSemuaWaliKelas(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->hasAnyRole(['admin', 'kurikulum'])) {
            return response()->json([
                'success' => false,
                'message' => 'Hanya Admin dan Kurikulum yang berhak mengirimkan pengingat massal.'
            ], 403);
        }

        $setting = Setting::first();
        $namaSekolah = $setting->nama_sekolah ?? 'SMK Wisata Indonesia';
        $today = Carbon::now()->toDateString();
        $todayLabel = Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y');
        $siswaSedangPKL = SiswaPkl::pluck('id_siswa')->toArray();

        $kelasList = Kelas::with(['waliKelas', 'siswa'])->get();
        $terkirimCount = 0;
        $tidakAdaNomorCount = 0;

        foreach ($kelasList as $kelas) {
            $siswaSudahAbsen = Absensi::where('tanggal', $today)
                ->where('id_kelas', $kelas->id_kelas)
                ->pluck('id_siswa')
                ->toArray();

            $siswaBelumAbsen = $kelas->siswa->filter(function ($siswa) use ($siswaSudahAbsen, $siswaSedangPKL) {
                return !in_array($siswa->id_siswa, $siswaSudahAbsen) && !in_array($siswa->id_siswa, $siswaSedangPKL);
            });

            $jumlah = $siswaBelumAbsen->count();

            if ($jumlah > 0) {
                $wali = $kelas->waliKelas;
                if ($wali && !empty($wali->no_hp)) {
                    $pesan = "📢 *PENGINGAT PRESENSI HARIAN - {$namaSekolah}*\n\n"
                           . "Yth. Bapak/Ibu *{$wali->nama_guru}*\n"
                           . "Wali Kelas: *{$kelas->nama_kelas}*\n\n"
                           . "Diberitahukan bahwa terdapat *{$jumlah} siswa* di kelas Anda yang *belum melakukan presensi* pada hari ini (*{$todayLabel}*).\n\n"
                           . "Mohon kesediaannya untuk segera memeriksa dan melengkapi presensi siswa melalui aplikasi SIAWI.\n\n"
                           . "Terima kasih atas kerja sama dan dedikasi Bapak/Ibu.\n"
                           . "_Pesan otomatis sistem SIAWI_";

                    SendWhatsAppAttendanceNotification::dispatch($wali->no_hp, $pesan);
                    $terkirimCount++;
                } else {
                    $tidakAdaNomorCount++;
                }
            }
        }

        if ($terkirimCount == 0 && $tidakAdaNomorCount == 0) {
            return response()->json([
                'success' => true,
                'message' => "Semua kelas telah menyelesaikan presensi hari ini."
            ]);
        }

        $pesanHasil = "Berhasil mengirim pengingat ke {$terkirimCount} Wali Kelas.";
        if ($tidakAdaNomorCount > 0) {
            $pesanHasil .= " ({$tidakAdaNomorCount} kelas dilewati karena Wali Kelas belum memiliki nomor WhatsApp).";
        }

        return response()->json([
            'success' => true,
            'message' => $pesanHasil
        ]);
    }
}
