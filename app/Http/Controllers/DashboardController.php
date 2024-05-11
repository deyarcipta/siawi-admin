<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Absensi;
use App\Models\Siswa;
use App\Models\PointSiswa;
use App\Models\Modul;
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
        // Kriteria untuk status kehadiran yang diinginkan
        $statusKehadiran = ['alfa', 'izin', 'sakit'];
        $jumlahTidakHadir = Absensi::where('tanggal', $today)
                            ->whereIn('kehadiran', $statusKehadiran)
                            ->count();
                            // Menghitung jumlah siswa yang hadir hari ini
        $jumlahHadir = $totalSiswa - $jumlahTidakHadir;

        // Menghitung presentase kehadiran
        if ($totalSiswa > 0) {
            $presentaseKehadiran = number_format(($jumlahHadir / $totalSiswa) * 100,0);
        } else {
            $presentaseKehadiran = 0;
        }
        $absensi = Absensi::where('tanggal',$today)->whereIn('kehadiran', $statusKehadiran)->get();
        $point = PointSiswa::orderBy('tanggal', 'desc')->take(5)->get();
        return view('dashboard', compact('layout','setting','absensi','totalSiswa','presentaseKehadiran','jumlahTidakHadir','totalModul','point','user'));
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
