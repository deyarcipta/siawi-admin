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
use Illuminate\Support\Facades\Hash;
use App\Models\Setting;
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
        $setting = Setting::find('1');
        $user = Auth::user();

        $today = Carbon::now()->toDateString();

        // Mengambil total jumlah siswa
        $totalSiswa = Siswa::count();
        $totalModul = Modul::count();

        // Menghitung total siswa yang hadir hari ini
        $totalHadir = Absensi::where('tanggal', $today)
                            ->where('kehadiran', 'hadir')
                            ->count();
        
        $totalGuru = Guru::count();

        $totalGuruHadir = AbsensiGuru::where('tanggal', $today)
                            ->count();

        // Kriteria untuk status kehadiran yang diinginkan
        $statusKehadiran = ['alfa', 'izin', 'sakit'];

        // Mengambil absensi berdasarkan status yang diinginkan
        $absensiTidakHadir = Absensi::where('tanggal', $today)
                                ->whereIn('kehadiran', $statusKehadiran)
                                ->with('siswa', 'kelas') // Eager load related siswa and kelas
                                ->get();

    // Group absences by class and count absences per class
    $kelasData = $absensiTidakHadir->groupBy('id_kelas')->map(function($item) {
        return [
            'kelas' => $item->first()->kelas,
            'jumlahTidakHadir' => $item->count(),
            'absensi' => $item
        ];
    });

        // Menghitung jumlah siswa yang tidak hadir
        $jumlahTidakHadir = $absensiTidakHadir->count();

        // Menghitung jumlah siswa yang hadir hari ini
        $jumlahHadir = $totalHadir - $jumlahTidakHadir;

        // Menghitung presentase kehadiran
        if ($totalSiswa > 0) {
            $presentaseKehadiran = number_format(($jumlahHadir / $totalSiswa) * 100, 0);
        } else {
            $presentaseKehadiran = 0;
        }

        // Data lain yang diperlukan untuk view
        $point = PointSiswa::orderBy('tanggal', 'desc')->take(5)->get();
        $allKelas = Kelas::orderBy('created_at', 'desc')->get();
        $kelasSudahAbsensi = Absensi::where('tanggal', $today)
                                    ->pluck('id_kelas')
                                    ->unique();

        $kelasBelumAbsensi = $allKelas->filter(function($kelas) use ($kelasSudahAbsensi) {
            return !$kelasSudahAbsensi->contains($kelas->id_kelas);
        });

        return view('dashboard', compact(
            'layout', 'setting', 'absensiTidakHadir', 'totalSiswa', 
            'presentaseKehadiran', 'jumlahTidakHadir', 'totalModul', 
            'point', 'user', 'jumlahHadir', 'kelasBelumAbsensi', 'kelasData', 'totalGuruHadir', 'totalGuru'
        ));
    }



    public function login(){
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
        $layout = 'layout.app'; // Misalnya, layout default Anda adalah 'layouts.app'
        $setting = Setting::find('1');
        $user = Auth::user();
        $edit = Guru::find($id_guru);
        return view('dataGuru.edit_profile', compact('layout','edit','setting','user'));
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id_guru)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'nullable|string|min:8', // password tidak wajib diisi
            'nama_guru' => 'required|string|max:255',
            'role' => 'required|string',
        ]);

        // Ambil data guru yang ada
        $guru = Guru::find($id_guru);

        // Jika tidak ditemukan, return error
        if (!$guru) {
            return redirect()->back()->with('error', 'Guru not found.');
        }

        // Update data guru
        $guru->username = $request->username;
        $guru->nama_guru = $request->nama_guru;
        $guru->role = $request->role;

        // Update password hanya jika diisi
        if ($request->filled('password')) {
            $guru->password = Hash::make($request->password);
        }

        $guru->save();

        return redirect()->action([DashboardController::class, 'index']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
