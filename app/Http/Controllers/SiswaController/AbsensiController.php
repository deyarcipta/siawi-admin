<?php

namespace App\Http\Controllers\SiswaController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Absensi;
use App\Models\Kelas;
use Carbon\Carbon;
use DB;

class AbsensiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(String $id_siswa)
    {
        $absen = Absensi::where('id_siswa', $id_siswa)
        ->get();
        $jumlahSakit = Absensi::where('id_siswa', $id_siswa)
        ->where('kehadiran', 'sakit')
        ->count();
        $jumlahAlfa = Absensi::where('id_siswa', $id_siswa)
        ->where('kehadiran', 'alfa')
        ->count();
        $jumlahIzin = Absensi::where('id_siswa', $id_siswa)
        ->where('kehadiran', 'izin')
        ->count();
        $jumlahHadir = Absensi::where('id_siswa', $id_siswa)
        ->where('kehadiran', 'hadir')
        ->count();
        $totalKehadiran = Absensi::where('id_siswa', $id_siswa)->count();

        $statusKehadiran = ['alfa', 'izin', 'sakit'];
        $jumlahTidakHadir = Absensi::where('id_siswa', $id_siswa)
                            ->whereIn('kehadiran', $statusKehadiran)
                            ->count();
                            // Menghitung jumlah siswa yang hadir hari ini
        $jumlahHadir = $totalKehadiran - $jumlahTidakHadir;

        // Menghitung presentase kehadiran
        if ($totalKehadiran > 0) {
            $presentaseKehadiran = number_format(($jumlahHadir / $totalKehadiran) * 100,0);
        } else {
            $presentaseKehadiran = 0;
        }
        $siswa = Siswa::where('id_siswa', $id_siswa)
        ->with('kelas')
        ->first();
        $warna = '';
        $absenArray = []; 
        foreach ($absen as $item) {
            if ($item->kehadiran == 'sakit' || $item->kehadiran == 'izin' || $item->kehadiran == 'alfa') {
                // Jika status kehadiran adalah sakit, izin, atau alfa, tetapkan warna sebagai Colors.red
                $warna = 'red';
            }else{
                $warna = 'blue';
            }
            $tanggalBaru = Carbon::createFromFormat('Y-m-d', $item->tanggal)->format('d F Y');
            $absenArray[] = [
                'tanggal' => $tanggalBaru, 
                'kehadiran' => $item->kehadiran,
                'hari' => $item->hari,
                'ket' => $item->keterangan,
                'warna' => $warna
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $absenArray,
            'dataSiswa' => $siswa,
            'jumlahSakit' => $jumlahSakit,
            'jumlahIzin' => $jumlahIzin,
            'jumlahAlfa' => $jumlahAlfa,
            'jumlahHadir' => $jumlahHadir,
            'jumlahTidakHadir' => $jumlahTidakHadir,
            'presentaseKehadiran' => $presentaseKehadiran,
            'message' => 'Berhasil Ambil Data'
        ]);
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
