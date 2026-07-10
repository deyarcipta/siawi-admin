<?php

namespace App\Http\Controllers\SiswaController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JadwalMapel;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\AbsensiGuru;
use Carbon\Carbon;
use DB;

class JadwalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(String $idSiswa, String $hari)
    {

        $siswa = Siswa::where('id_siswa', $idSiswa)->first();
        $jadwals = JadwalMapel::where('kelas', $siswa->kelas->nama_kelas)
        ->where('hari', $hari)
        ->orderBy('jam_awal', 'asc')
        ->get();

        $groupedJadwals = $jadwals->groupBy('hari')->map(function ($jadwals) {
            return $jadwals->map(function ($jadwal) {
                return [
                    'nama_mapel' => $jadwal->mapel->nama_mapel,
                    'nama_guru' => $jadwal->guru->nama_guru,
                    'jam_awal' => $jadwal->jam_awal,
                    'jam_akhir' => $jadwal->jam_akhir,
                    'waktu_awal' => $jadwal->waktu_awal,
                    'waktu_akhir' => $jadwal->waktu_akhir,
                ];
            });
        });

        return response()->json(['data' => $groupedJadwals]);
    }

    public function jadwalToday(String $id_siswa)
    {
        try {
            $siswa = Siswa::where('id_siswa', $id_siswa)->first();
            if (!$siswa) {
                return response()->json([
                    'success' => false,
                    'message' => 'Siswa tidak ditemukan'
                ], 404);
            }
            
            $id_kelas = $siswa->id_kelas;
            $kelas = Kelas::where('id_kelas', $id_kelas)->first();
            if (!$kelas) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kelas tidak ditemukan'
                ], 404);
            }
            $namaKelas = $kelas->kode_kelas;

            $today = Carbon::now()->toDateString();
            $dayName = Carbon::parse($today)->format('l');

            $englishDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            $indonesianDays = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'];

            // Cari indeks dari nama hari dalam bahasa Inggris
            $index = array_search($dayName, $englishDays);

            // Jika ditemukan, kembalikan nama hari dalam bahasa Indonesia
            $namaHari = 'senin'; // default fallback
            if ($index !== false) {
                $namaHari =  $indonesianDays[$index];
            }

            $jadwalToday = JadwalMapel::where('kelas', $namaKelas)
                ->where('hari', $namaHari)
                ->get();
                
            $jadwalTodayArray = [];
            foreach ($jadwalToday as $item) {
                // Cek apakah guru hadir di tabel AbsensiGuru berdasarkan tanggal hari ini
                $absensiGuru = null;
                if ($item->guru) {
                    $absensiGuru = AbsensiGuru::where('id_guru', $item->guru->id_guru)
                        ->whereDate('tanggal', $today)
                        ->first();
                }

                $status_kehadiran = match (optional($absensiGuru)->kehadiran) {
                    'Hadir' => 'Hadir',
                    'Sakit' => 'Sakit',
                    'Izin' => 'Izin',
                    default => 'Tidak Hadir'
                };

                $jadwalTodayArray[] = [
                    'nama_mapel' => $item->mapel?->nama_mapel ?? 'Mata Pelajaran Tidak Diketahui',
                    'nama_guru' => $item->guru?->nama_guru ?? 'Guru Tidak Diketahui',
                    'jam_awal' => $item->jam_awal,
                    'jam_akhir' => $item->jam_akhir,
                    'waktu_awal' => $item->waktu_awal,
                    'waktu_akhir' => $item->waktu_akhir,
                    'status_kehadiran' => $status_kehadiran,
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $jadwalTodayArray,
                'message' => 'Berhasil Ambil Data'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server (JadwalToday)',
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
