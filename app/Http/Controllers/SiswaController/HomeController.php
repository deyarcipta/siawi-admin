<?php

namespace App\Http\Controllers\SiswaController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Siswa;
use App\Models\Absensi;
use App\Models\Rapot;
use Carbon\Carbon;
use DB;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(String $id_siswa)
    {
        $siswa = Siswa::where('id_siswa', $id_siswa)
        ->with('kelas')
        ->with('jurusan')
        ->first();
        $today = Carbon::now()->toDateString();
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

        $kehadiranToday = Absensi::where('id_siswa', $id_siswa)
                            ->where('tanggal', $today)
                            ->first();

        $rapotTerakhir = Rapot::where('id_siswa', $id_siswa)
                            ->latest()
                            ->first();
        

        $semester = $rapotTerakhir->semester - 1;
        $dataRapot = Rapot::where('id_siswa', $id_siswa)
                            ->where('semester', $semester)
                            ->first();

        if($rapotTerakhir->rata_rata > $dataRapot->rata_rata){
            $pesan = 'benar';
        }else{
            $pesan = 'salah';
        }

        return response()->json([
            'success' => true,
            'data' => $siswa,
            'rapotTerakhir' => $rapotTerakhir,
            'dataRapot' => $dataRapot,
            'pesan' => $pesan,
            'presentaseKehadiran' => $presentaseKehadiran,
            'kehadiranToday' => $kehadiranToday,
            'message' => 'Berhasil login'
        ]);
    }

    public function login(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'nis' => 'required',
            'password' => 'required'
        ]);

        $user = Siswa::where('nis', $request->nis)->first();
        // dd($user->password);
        
        if (!$user || $request->password != $user->password) {
            // Pengguna tidak ditemukan atau kata sandi tidak cocok
            return response()->json([
                'success' => false,
                'message' => 'NIS atau password tidak valid'
            ], 401);
        }
    
        // Autentikasi berhasil, loginkan pengguna
        Auth::login($user);
    
        return response()->json([
            'success' => true,
            'data' => $user,
            'message' => 'Berhasil login'
        ]);
    }

    public function ubahPassword(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'idSiswa' => 'required',
            'password1' => 'required',
            'password2' => 'required'
        ]);

        
        if ($request->password1 != $request->password2) {
            // Pengguna tidak ditemukan atau kata sandi tidak cocok
            return response()->json([
                'success' => false,
                'message' => 'Password yang anda masukan tidak cocok'
            ], 401);
        }
            $user = Siswa::where('id_siswa', $request->idSiswa)->update([
                'password' => $request->password1,
            ]);
            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'Password Berhasil Diubah'
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
