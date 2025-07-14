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
        $setting = Setting::find(1);
        $user = Auth::user();
        $today = Carbon::now()->toDateString();

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
            ->limit(10) // Ambil hanya 10 siswa
            ->get();

        // Ambil daftar ID siswa yang sedang PKL (status = PKL)
        $siswaSedangPKL = \App\Models\SiswaPkl::where('status', 'PKL')->pluck('id_siswa')->toArray();

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

        return view('dashboard', compact(
            'layout',
            'setting',
            'absensiTidakHadir',
            'totalSiswa',
            'jumlahTidakHadir',
            'totalModul',
            'user',
            'totalHadir',
            'kelasData',
            'totalGuruHadir',
            'totalGuru',
            'jumlahTidakHadirAll',
            'siswaTerajin'
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
