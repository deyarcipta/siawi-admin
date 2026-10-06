<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;
use App\Models\Absensi;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\PointSiswa;
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

        // 4. TOP 5 KEHADIRAN SISWA TERCEPAT HARI INI (Early Birds)
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

        return [
            'setting' => $setting,
            'greetingHeader' => $greetingHeader,
            'salam' => $salam,
            'namaSekolah' => $namaSekolah,
            'tanggalFormatted' => $tanggalFormatted,
            'jamFormatted' => $jamFormatted,
            'videoUrl' => $videoUrl,
            'topKelasBulanan' => $topKelasBulanan,
            'topSiswaTercepat' => $topSiswaTercepat,
            'siswaTerlambatList' => $siswaTerlambatList,
            'totalTerlambat' => $siswaTerlambatList->count()
        ];
    }
}
