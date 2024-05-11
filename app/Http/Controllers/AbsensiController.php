<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Absensi;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Jurusan;
use App\Models\Setting;
use Carbon\Carbon;
use DB;

class AbsensiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $kelas = Kelas::pluck('nama_kelas', 'kode_kelas','id_kelas');
        $kelas = Kelas::orderBy('created_at', 'desc')->get();
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $kelasId = '';
        $user = Auth::user();

        // Jika form sudah diisi, ambil data siswa berdasarkan kelas yang dipilih
        if ($request->filled('tanggal') && $request->filled('kelas')) {
            $tanggal = $request->tanggal;
            $kelasId = $request->kelas;
            $carbonDate = Carbon::parse($tanggal);
            $carbonDate->locale('id');
            $namaHari = $carbonDate->translatedFormat('l');
            $dataKelas = Kelas::where('id_kelas', $kelasId)->first();
            $siswa = Siswa::whereHas('kelas', function($query) use ($kelasId) {
                $query->where('id_kelas', $kelasId);
            })->get();

            $absensiSiswa = [];

            // Loop setiap siswa untuk mendapatkan data absensi
            foreach ($siswa as $s) {
                // Mengambil data absensi untuk siswa saat ini
                $absensiSiswa[$s->id_siswa] = Absensi::where('id_siswa', $s->id_siswa)->where('tanggal', $tanggal)->first();
            }

            return view('absensi.data_absensi', compact('kelas', 'siswa', 'tanggal', 'kelasId', 'layout', 'setting', 'namaHari','dataKelas','absensiSiswa','user'));
        }

        return view('absensi.data_absensi', compact('kelas', 'layout', 'setting', 'kelasId','user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function absen(Request $request)
    {
        $request->validate([
            'hari' => 'required',
            'tanggal' => 'required',
            'kelas_id' => 'required|exists:kelas,id_kelas',
            'siswa' => 'required|array',
            'siswa.*.id_siswa' => 'required|exists:siswa,id_siswa',
            'siswa.*.kehadiran' => 'required|in:hadir,sakit,izin,alfa',
            'siswa.*.keterangan' => 'nullable|string|max:255',
        ]);

        $siswaData = $request->input('siswa');
        foreach ($siswaData as $siswaId => $data) {

            $kehadiran = $data['kehadiran'];
            $keterangan = $data['keterangan'];
            
            Absensi::updateOrCreate(
                ['id_siswa' => $siswaId, 'tanggal' => $request->input('tanggal'), 'hari' => $request->input('hari'), 'id_kelas' => $request->kelas_id, 'id_jurusan' => $siswaId],
                ['kehadiran' => $kehadiran, 'keterangan' => $keterangan ?? '-']
            );
        }
        return redirect('/admin/absensi');
        // return response()->json(['success' => true, 'message' => 'Absensi berhasil disimpan.']);
    }

    public function dataAbsen(Request $request)
    {
        // $kelas = Kelas::pluck('nama_kelas', 'kode_kelas','id_kelas');
        $kelas = Kelas::orderBy('created_at', 'desc')->get();
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $kelasId = '';
        $user = Auth::user();

        // Jika form sudah diisi, ambil data siswa berdasarkan kelas yang dipilih
        if ($request->filled('kelas')) {
            $kelasId = $request->kelas;
            $dataKelas = Kelas::where('id_kelas', $kelasId)->first();
            $siswa = Siswa::whereHas('kelas', function($query) use ($kelasId) {
                $query->where('id_kelas', $kelasId);
            })->get();

            // Inisialisasi array untuk menyimpan jumlah kehadiran, sakit, izin, dan alfa setiap siswa
            $absensiSiswa = [];
            $countSakit = [];
            $countIzin = [];
            $countAlfa = [];

            // Ambil data absensi untuk kelas dan hitung jumlah kehadiran, sakit, izin, dan alfa setiap siswa
            $dataAbsen = Absensi::get();

            foreach ($siswa as $s) {
                // Inisialisasi jumlah kehadiran, sakit, izin, dan alfa menjadi 0 untuk setiap siswa
                $absensiSiswa[$s->id_siswa] = 0;
                $countSakit[$s->id_siswa] = 0;
                $countIzin[$s->id_siswa] = 0;
                $countAlfa[$s->id_siswa] = 0;
                $countMasuk[$s->id_siswa] = 0;
            }

                // Loop setiap data absensi untuk menghitung jumlah kehadiran, sakit, izin, dan alfa setiap siswa
                foreach ($dataAbsen as $absensi) {
                    // Periksa apakah siswa dengan ID tertentu ada dalam array $absensiSiswa
                    if (isset($absensiSiswa[$absensi->id_siswa])) {
                        // Tambahkan 1 ke jumlah kehadiran setiap siswa
                        $absensiSiswa[$absensi->id_siswa]++;

                        // Periksa jenis kehadiran dan tambahkan 1 ke jumlah sesuai jenis kehadiran
                        switch ($absensi->kehadiran) {
                            case 'sakit':
                                $countSakit[$absensi->id_siswa]++;
                                break;
                            case 'izin':
                                $countIzin[$absensi->id_siswa]++;
                                break;
                            case 'alfa':
                                $countAlfa[$absensi->id_siswa]++;
                                break;
                            case 'hadir':
                                $countMasuk[$absensi->id_siswa]++;
                                break;
                            default:
                                // Tidak ada tindakan khusus untuk jenis kehadiran lainnya
                                break;
                        }
                    }
                }

                // Mengirimkan data ke view
                return view('dataAbsen.data_absen', compact('kelas', 'siswa', 'kelasId', 'layout', 'setting', 'dataKelas', 'absensiSiswa', 'user', 'countMasuk','countSakit', 'countIzin', 'countAlfa'));
            }

            // Jika form belum diisi, kembalikan view tanpa data siswa
            return view('dataAbsen.data_absen', compact('kelas', 'layout', 'setting', 'kelasId', 'user'));

            }
        }
