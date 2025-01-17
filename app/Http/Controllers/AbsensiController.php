<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Absensi;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Setting;
use App\Exports\AbsensiExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $kelas = Kelas::orderBy('created_at', 'desc')->get();
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $kelasId = '';
        $user = Auth::user();

        if ($request->filled('tanggal') && $request->filled('kelas')) {
            $tanggal = $request->tanggal;
            $kelasId = $request->kelas;
            $carbonDate = Carbon::parse($tanggal)->locale('id');
            $namaHari = $carbonDate->translatedFormat('l');
            $dataKelas = Kelas::where('id_kelas', $kelasId)->first();
            $siswa = Siswa::whereHas('kelas', function($query) use ($kelasId) {
                $query->where('id_kelas', $kelasId);
            })->orderBy('nama_siswa', 'asc')->get();

            $absensiSiswa = [];
            foreach ($siswa as $s) {
                $absensiSiswa[$s->id_siswa] = Absensi::where('id_siswa', $s->id_siswa)
                    ->where('tanggal', $tanggal)
                    ->first();
            }

            return view('absensi.data_absensi', compact('kelas', 'siswa', 'tanggal', 'kelasId', 'layout', 'setting', 'namaHari', 'dataKelas', 'absensiSiswa', 'user'));
        }

        return view('absensi.data_absensi', compact('kelas', 'layout', 'setting', 'kelasId', 'user'));
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
        $kelas = Kelas::orderBy('created_at', 'desc')->get();
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $kelasId = '';
        $user = Auth::user();

        if ($request->filled('kelas')) {
            $kelasId = $request->kelas;
            $dataKelas = Kelas::where('id_kelas', $kelasId)->first();
            $siswa = Siswa::whereHas('kelas', function($query) use ($kelasId) {
                $query->where('id_kelas', $kelasId);
            })->orderBy('nama_siswa', 'asc')->get();

            $absensiSiswa = [];
            $countSakit = [];
            $countIzin = [];
            $countAlfa = [];
            $countMasuk = [];

            foreach ($siswa as $s) {
                $absensiSiswa[$s->id_siswa] = 0;
                $countSakit[$s->id_siswa] = 0;
                $countIzin[$s->id_siswa] = 0;
                $countAlfa[$s->id_siswa] = 0;
                $countMasuk[$s->id_siswa] = 0;
            }

            $dataAbsen = Absensi::where('id_kelas', $kelasId)->get();

            foreach ($dataAbsen as $absensi) {
                if (isset($absensiSiswa[$absensi->id_siswa])) {
                    $absensiSiswa[$absensi->id_siswa]++;
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
                    }
                }
            }

            return view('dataAbsen.data_absen', compact('kelas', 'siswa', 'kelasId', 'layout', 'setting', 'dataKelas', 'absensiSiswa', 'user', 'countMasuk', 'countSakit', 'countIzin', 'countAlfa'));
        }

        return view('dataAbsen.data_absen', compact('kelas', 'layout', 'setting', 'kelasId', 'user'));
    }
	
	 public function rekapAbsen(Request $request)
            {
                $tanggal_awal = $request->input('tanggal_awal');
                $tanggal_akhir = $request->input('tanggal_akhir');
                $layout = 'layout.app';
                $setting = Setting::find('1');
                $user = Auth::user();

                $dataKelas = Kelas::all();
                $rekapKehadiran = [];

                foreach ($dataKelas as $kelas) {
                    $totalAbsen = Absensi::where('id_kelas', $kelas->id_kelas)
                                        ->whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir])
                                        ->count();

                    $countMasuk = Absensi::where('id_kelas', $kelas->id_kelas)
                                        ->whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir])
                                        ->where('kehadiran', 'hadir')
                                        ->count();

                    $presentase = $totalAbsen > 0 ? ($countMasuk / $totalAbsen) * 100 : 0;
                    $rekapKehadiran[] = [
                        'nama_kelas' => $kelas->nama_kelas,
                        'presentase' => $presentase
                    ];
                }

                return view('dataAbsen.rekap_absen', [
                    'rekapKehadiran' => $rekapKehadiran,
                    'tanggal_awal' => $tanggal_awal,
                    'tanggal_akhir' => $tanggal_akhir,
                    'layout' => $layout,
                    'setting' => $setting,
                    'user' => $user,
                ]);
            }

    public function download($kelasId)
{
        $dataKelas = Kelas::where('id_kelas', $kelasId)->first();
        $siswa = Siswa::whereHas('kelas', function ($query) use ($kelasId) {
            $query->where('id_kelas', $kelasId);
        })->orderBy('nama_siswa', 'asc')->get();

        $absensiSiswa = [];
        $countSakit = [];
        $countIzin = [];
        $countAlfa = [];
        $countMasuk = [];

        foreach ($siswa as $s) {
            $absensiSiswa[$s->id_siswa] = 0;
            $countSakit[$s->id_siswa] = 0;
            $countIzin[$s->id_siswa] = 0;
            $countAlfa[$s->id_siswa] = 0;
            $countMasuk[$s->id_siswa] = 0;
        }

        $dataAbsen = Absensi::where('id_kelas', $kelasId)->get();

        foreach ($dataAbsen as $absensi) {
            if (isset($absensiSiswa[$absensi->id_siswa])) {
                $absensiSiswa[$absensi->id_siswa]++;
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
                }
            }
        }

        return Excel::download(new AbsensiExport($siswa, $absensiSiswa, $countMasuk, $countSakit, $countIzin, $countAlfa, $dataKelas->nama_kelas), 'data_absensi_' . $dataKelas->nama_kelas . '.xlsx');


    }
    public function viewRfidAbsen(Request $request)
    {
        $kelas = Kelas::orderBy('created_at', 'desc')->get();
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $kelasId = '';
        $user = Auth::user();
        // Ambil tanggal hari ini
        $tanggalHariIni = Carbon::now()->toDateString();

        // Ambil data absensi siswa terbaru yang hadir hari ini
        $dataSiswa = Absensi::whereDate('created_at', $tanggalHariIni) // Filter berdasarkan tanggal
            ->orderBy('created_at', 'desc') // Urutkan data terbaru
            ->take(15) // Batasi hanya 15 data
            ->get()
            ->map(function ($item) {
                $item->jam_masuk = Carbon::parse($item->created_at)->format('H:i'); // Ambil jam saja
                return $item;
            });
        return view('dataAbsen.rfid_absen', compact('kelas', 'layout', 'setting', 'kelasId', 'user','dataSiswa'));
    }

    public function rfidAbsen(Request $request)
    {
        $kelas = Kelas::orderBy('created_at', 'desc')->get();
        $layout = 'layout.app';
        $setting = Setting::find(1);
        $kelasId = '';
        $user = Auth::user();

        // Validasi input RFID
        $request->validate([
            'rfid' => 'required|string'
        ]);

        $rfid = $request->input('rfid');

        // Cari siswa berdasarkan RFID
        $siswa = Siswa::where('rfid', $rfid)->first();

        // Jika ditemukan, lakukan absensi
        if ($siswa) {
            $kehadiran = 'hadir';
            $keterangan = '-';
            Carbon::setLocale('id'); // Atur locale ke bahasa Indonesia

            $dataTanggal = Carbon::now(); // Ambil tanggal sekarang
            $tanggal = $dataTanggal->format('Y-m-d'); // Format tanggal yyyy-mm-dd
            $namaHari = $dataTanggal->translatedFormat('l'); // Nama hari dalam bahasa Indonesia

            // Update atau buat absensi baru
            Absensi::updateOrCreate(
                ['id_siswa' => $siswa->id_siswa, 'tanggal' => $tanggal, 'hari' => $namaHari, 'id_kelas' => $siswa->id_kelas, 'id_jurusan' => $siswa->id_jurusan],
                ['kehadiran' => $kehadiran, 'keterangan' => $keterangan]
            );

            
            $message = null; // Tidak ada pesan error
        } else {
            // Jika tidak ditemukan, beri pesan error
            $message = 'Kartu RFID tidak ditemukan dalam database.';
        }

        // Ambil tanggal hari ini
        $tanggalHariIni = Carbon::now()->toDateString();

        // Ambil data absensi siswa terbaru yang hadir hari ini
        $dataSiswa = Absensi::whereDate('created_at', $tanggalHariIni) // Filter berdasarkan tanggal
            ->orderBy('created_at', 'desc') // Urutkan data terbaru
            ->take(15) // Batasi hanya 15 data
            ->get()
            ->map(function ($item) {
                $item->jam_masuk = Carbon::parse($item->created_at)->format('H:i'); // Ambil jam saja
                return $item;
            });

        return view('dataAbsen.rfid_absen', compact('kelas', 'layout', 'setting', 'kelasId', 'user', 'siswa', 'dataSiswa', 'message'));
    }
    
}
