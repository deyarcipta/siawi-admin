<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Absensi;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Setting;
use App\Exports\AbsensiExport;
use App\Exports\AbsensiSiswaExport;
use App\Exports\AbsensiSiswaRekapExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class AbsensiController extends Controller
{

    public function index()
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        $now = Carbon::now('Asia/Jakarta');
        $tanggal = $now->toDateString();
        $hari = $now->locale('id')->dayName;
        $siswaList = Siswa::orderBy('nama_siswa', 'asc')->get();
        $kelasList = Kelas::orderBy('nama_kelas', 'asc')->get();
        // Ambil data absensi hanya untuk hari ini
        $absensiSiswa = Absensi::whereDate('tanggal', $tanggal)
                              ->orderBy('created_at', 'desc')
                              ->get();
        return view('absensi.index', compact('absensiSiswa', 'layout', 'setting', 'user','hari','siswaList','kelasList'));
    }

    // public function index(Request $request)
    // {
    //     $kelas = Kelas::orderBy('created_at', 'desc')->get();
    //     $layout = 'layout.app';
    //     $setting = Setting::find('1');
    //     $kelasId = '';
    //     $user = Auth::user();

    //     if ($request->filled('tanggal') && $request->filled('kelas')) {
    //         $tanggal = $request->tanggal;
    //         $kelasId = $request->kelas;
    //         $carbonDate = Carbon::parse($tanggal)->locale('id');
    //         $namaHari = $carbonDate->translatedFormat('l');
    //         $dataKelas = Kelas::where('id_kelas', $kelasId)->first();
    //         $siswa = Siswa::whereHas('kelas', function($query) use ($kelasId) {
    //             $query->where('id_kelas', $kelasId);
    //         })->orderBy('nama_siswa', 'asc')->get();

    //         $absensiSiswa = [];
    //         foreach ($siswa as $s) {
    //             $absensiSiswa[$s->id_siswa] = Absensi::where('id_siswa', $s->id_siswa)
    //                 ->where('tanggal', $tanggal)
    //                 ->first();
    //         }

    //         return view('absensi.data_absensi', compact('kelas', 'siswa', 'tanggal', 'kelasId', 'layout', 'setting', 'namaHari', 'dataKelas', 'absensiSiswa', 'user'));
    //     }

    //     return view('absensi.data_absensi', compact('kelas', 'layout', 'setting', 'kelasId', 'user'));
    // }
	
	/**
     * Store a newly created resource in storage.
     */

    //  Controller Absensi Harian Siswa
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

    public function getSiswaByKelas($id_kelas)
    {
        $siswa = Siswa::where('id_kelas', $id_kelas)->get();
        return response()->json($siswa);
    }

    public function tambahKehadiran(Request $request)
    {
        // Validasi input (tanpa tanggal dan hari karena otomatis)
        $request->validate([
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'id_siswa' => 'required|exists:siswa,id_siswa',
            'kehadiran' => 'required|in:Hadir,Izin,Sakit,Alfa',
        ]);

        $siswaId = $request->input('id_siswa');
        $kelasId = $request->input('id_kelas');
        $kehadiran = strtolower($request->input('kehadiran'));
        $keterangan = $request->input('keterangan') ?? '-';

        // Ambil tanggal dan hari sekarang
        $tanggal = Carbon::today()->toDateString(); // contoh hasil: 2025-04-28
        $hari = Carbon::today()->isoFormat('dddd'); // contoh hasil: Senin, Selasa, dst.

        // Ambil id_jurusan dari data siswa
        $siswa = Siswa::findOrFail($siswaId);
        $jurusanId = $siswa->id_jurusan;

        // Simpan absensi
        Absensi::create([
            'id_siswa' => $siswaId,
            'tanggal' => $tanggal,
            'hari' => $hari,
            'id_kelas' => $kelasId,
            'id_jurusan' => $jurusanId,
            'kehadiran' => $kehadiran,
            'keterangan' => $keterangan,
        ]);

        return back()->with('success', 'Data kehadiran berhasil disimpan.');
    }

    // Controller Data Absensi Kelas
    public function rekapAbsen(Request $request)
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        $dataKelas = Kelas::orderBy('nama_kelas', 'asc')->get();
        $rekapKehadiran = [];

        // Cek apakah tanggal_awal dan tanggal_akhir sudah diisi
        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $tanggal_awal = $request->input('tanggal_awal');
            $tanggal_akhir = $request->input('tanggal_akhir');

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
                    'id_kelas' => $kelas->id_kelas,
                    'nama_kelas' => $kelas->nama_kelas,
                    'presentase' => $presentase
                ];
            }

            return view('dataAbsen.data_absen', compact(
                'rekapKehadiran', 
                'tanggal_awal', 
                'tanggal_akhir', 
                'layout', 
                'setting', 
                'user', 
                'dataKelas'
            ));
        }

        // Kalau belum pilih tanggal, hanya kirim view tanpa data kehadiran
        return view('dataAbsen.data_absen', compact('layout', 'setting', 'user', 'dataKelas'));
    }

    // Controller Menampilkan Rekap Absensi Kelas
    public function showRekapAbsen(Request $request)
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        $kelasId = $request->query('id_kelas');
        $tanggal_awal = $request->query('tanggal_awal');
        $tanggal_akhir = $request->query('tanggal_akhir');

        $dataKelas = Kelas::findOrFail($kelasId);
        
        // Misalnya kamu pakai model Absen untuk ambil data
        $siswa = Siswa::where('id_kelas', $kelasId)->get();

        // Lanjutkan proses penghitungan seperti:
        $absensiSiswa = [];
        $countMasuk = [];
        $countSakit = [];
        $countIzin = [];
        $countAlfa = [];

        foreach ($siswa as $s) {
            $absensi = Absensi::where('id_siswa', $s->id_siswa)
                ->whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir])
                ->get();

            $absensiSiswa[$s->id_siswa] = $absensi->count();
            $countMasuk[$s->id_siswa] = $absensi->where('kehadiran', 'hadir')->count();
            $countSakit[$s->id_siswa] = $absensi->where('kehadiran', 'sakit')->count();
            $countIzin[$s->id_siswa] = $absensi->where('kehadiran', 'izin')->count();
            $countAlfa[$s->id_siswa] = $absensi->where('kehadiran', 'alfa')->count();
        }

        return view('dataAbsen.data_absenSiswa', compact(
            'layout', 'setting', 'user','siswa', 'dataKelas', 'kelasId', 'tanggal_awal', 'tanggal_akhir', 'absensiSiswa', 'countMasuk', 'countSakit', 'countIzin', 'countAlfa'
        ))->with('kelasId', $kelasId);
    }

    // Controller Untuk Mendownload Rekap Data Kelas
    public function downloadShowRekap(Request $request)
    {
        $id_kelas = $request->query('kelas');
        $tanggal_awal = $request->query('tanggal_awal');
        $tanggal_akhir = $request->query('tanggal_akhir');

        $dataKelas = Kelas::where('id_kelas', $id_kelas)->first();

        $siswa = Siswa::whereHas('kelas', function ($query) use ($id_kelas) {
            $query->where('id_kelas', $id_kelas);
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

        // ✅ Ambil data absensi berdasarkan kelas DAN filter tanggal
        $dataAbsen = Absensi::where('id_kelas', $id_kelas)
            ->whereDate('tanggal', '>=', Carbon::parse($tanggal_awal)->toDateString())
            ->whereDate('tanggal', '<=', Carbon::parse($tanggal_akhir)->toDateString())
            ->get();

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

        $filename = 'data_absensi_' . $dataKelas->nama_kelas . '_' . $tanggal_awal . '_sampai_' . $tanggal_akhir . '.xlsx';

        return Excel::download(new AbsensiExport(
            $siswa,
            $absensiSiswa,
            $countMasuk,
            $countSakit,
            $countIzin,
            $countAlfa,
            $dataKelas->nama_kelas
        ), $filename);
    }

    // Kontroller Untuk Menampilkan Rekap Waktu Kehadiran dan Pulang Siswa
    public function rekapAbsenSiswa(Request $request)
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        $kelas = Kelas::orderBy('nama_kelas', 'asc')->get();
        $kelasId = '';

        if ($request->filled('kelas') && $request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $kelasId = $request->kelas;
            $dataKelas = Kelas::where('id_kelas', $kelasId)->first();
            $tanggalAwal = $request->input('tanggal_awal', Carbon::now()->startOfMonth()->toDateString());
            $tanggalAkhir = $request->input('tanggal_akhir', Carbon::now()->endOfMonth()->toDateString());

            $rekapKehadiran = Absensi::whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
                ->whereHas('siswa', function ($query) use ($kelasId) {
                    $query->where('id_kelas', $kelasId);
                })
                ->with('siswa')
                ->get()
                ->groupBy('id_siswa');
            $kelas = Kelas::orderBy('nama_kelas', 'asc')->get();

            return view('dataAbsen.rekap_absen', compact('layout', 'setting', 'user', 'kelas', 'rekapKehadiran', 'tanggalAwal', 'tanggalAkhir', 'kelasId','dataKelas'));
        }
        return view('dataAbsen.rekap_absen', compact('layout', 'setting', 'user', 'kelas', 'kelasId'));
        
    }

    // Controller Untuk Mendownload Rekap Waktu Kehadiran dan Pulang Siswa
    public function exportRekapSiswa(Request $request)
    {
        $id_kelas     = $request->id_kelas;
        $tanggal_awal = $request->tanggal_awal;
        $tanggal_akhir= $request->tanggal_akhir;

        // Ambil data kelas untuk mendapatkan nama kelas
        $kelas = Kelas::find($id_kelas);
        // Format nama kelas agar tidak ada spasi, misalnya diganti dengan underscore
        $namaKelas = $kelas ? str_replace(' ', '_', strtoupper($kelas->nama_kelas)) : 'kelas';

        // Buat filename dengan format: rekap_absen_nama kelas_tanggal awal-tanggal akhir.xlsx
        $filename = 'rekap_absen_' . $namaKelas . '_' . $tanggal_awal . '-' . $tanggal_akhir . '.xlsx';

        return Excel::download(new AbsensiSiswaRekapExport($id_kelas, $tanggal_awal, $tanggal_akhir), $filename);
    }

    public function AbsensiSiswaExport()
    {
        $tanggal = Carbon::now()->format('Y-m-d'); // Format: 2025-02-25
        $hari = Carbon::now()->locale('id')->translatedFormat('l'); // Format: Selasa

        $fileName = "Kehadiran_Siswa_{$hari}_{$tanggal}.xlsx"; // Contoh: Absensi_Selasa_2025-02-25.xlsx

        return Excel::download(new AbsensiSiswaExport($tanggal), $fileName);
    }

    // Controller Untuk Menyimpan Kehadiran Siswa
    public function simpan(Request $request)
    {
        $siswaIds = $request->input('id_siswa');
        $kelasIds = $request->input('id_kelas');
        $jurusanIds = $request->input('id_jurusan');
        $kehadiran = $request->input('kehadiran');
        $tanggal = now()->toDateString(); // Ambil tanggal hari ini
        $hari = now()->isoFormat('dddd'); // Ambil nama hari dalam format lokal
        $jam = now()->format('H:i:s'); // ambil jam sekarang
        $today = Carbon::now()->toDateString();

        foreach ($siswaIds as $index => $siswaId) {
            $status = strtolower($kehadiran[$index]);

            $isHadir = $status === 'hadir';
            $jamMasuk = $isHadir ? $jam : '-';
            $keterangan = $isHadir ? 'Masuk' : 'Tidak Masuk';
            Absensi::create([
                'id_siswa' => $siswaId,
                'id_kelas' => $kelasIds[$index],
                'id_jurusan' => $jurusanIds[$index],
                'hari' => $hari,
                'tanggal' => $today,
                'jam_masuk' => $jamMasuk,
                'kehadiran' => $kehadiran[$index],
                'keterangan' => $keterangan
            ]);
        }

        return redirect()->back()->with('success', 'Absensi berhasil disimpan!');
    }

    // Controller untuk melakukan perubahan kehadiran siswa di hari ini
    public function update(Request $request, $id_absensi)
    {
        $request->validate([
            'kehadiran' => 'required|in:Hadir,Izin,Sakit,Alfa',
        ]);

        $absensi = Absensi::findOrFail($id_absensi);
        $jam = now()->format('H:i:s');

        // Cek jika status sebelumnya bukan 'Hadir' dan akan diubah menjadi 'Hadir'
        if ($absensi->kehadiran !== 'Hadir' && $request->kehadiran === 'Hadir') {
            $absensi->jam_masuk = $jam; // isi jam masuk sekarang
            $absensi->keterangan = 'Masuk'; // set keterangan masuk
        }

        $absensi->kehadiran = $request->kehadiran;
        $absensi->save();

        return redirect()->back()->with('success', 'Data kehadiran berhasil diperbarui.');
    }

    public function siswaTidakHadir(Request $request)
    {
        $dataTidakHadir = null;
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();

        if ($request->has(['tanggal_mulai', 'tanggal_akhir'])) {
            $dataTidakHadir = Absensi::with('siswa')
                ->whereBetween('tanggal', [$request->tanggal_mulai, $request->tanggal_akhir])
                ->whereIn('kehadiran', ['sakit', 'izin', 'alfa'])
                ->orderBy('tanggal', 'asc')
                ->get();
        } elseif ($request->has('today')) {
            $today = now()->toDateString();
            $dataTidakHadir = Absensi::with('siswa')
                ->whereDate('tanggal', $today)
                ->whereIn('kehadiran', ['sakit', 'izin', 'alfa'])
                ->orderBy('tanggal', 'asc')
                ->get();
        }

        return view('dataAbsen.data_siswaTidakHadir', compact('layout', 'setting', 'user', 'dataTidakHadir'));
    }

    public function destroy(string $id_absensi)
    {
        Absensi::where('id_absensi', $id_absensi)->delete();
        return redirect()->back()->with('success', 'Data kehadiran berhasil dihapus.');
    }

    // public function viewRfidAbsen(Request $request)
    // {
    //     $kelas = Kelas::orderBy('created_at', 'desc')->get();
    //     $layout = 'layout.app';
    //     $setting = Setting::find('1');
    //     $kelasId = '';
    //     $user = Auth::user();
    //     // Ambil tanggal hari ini
    //     $tanggalHariIni = Carbon::now()->toDateString();

    //     // Ambil data absensi siswa terbaru yang hadir hari ini
    //     $dataSiswa = Absensi::whereDate('created_at', $tanggalHariIni) // Filter berdasarkan tanggal
    //         ->orderBy('created_at', 'desc') // Urutkan data terbaru
    //         ->take(15) // Batasi hanya 15 data
    //         ->get()
    //         ->map(function ($item) {
    //             $item->jam_masuk = Carbon::parse($item->created_at)->format('H:i'); // Ambil jam saja
    //             return $item;
    //         });
    //     return view('dataAbsen.rfid_absen', compact('kelas', 'layout', 'setting', 'kelasId', 'user','dataSiswa'));
    // }

    // public function rfidAbsen(Request $request)
    // {
    //     $kelas = Kelas::orderBy('created_at', 'desc')->get();
    //     $layout = 'layout.app';
    //     $setting = Setting::find(1);
    //     $kelasId = '';
    //     $user = Auth::user();

    //     // Validasi input RFID
    //     $request->validate([
    //         'rfid' => 'required|string'
    //     ]);

    //     $rfid = $request->input('rfid');

    //     // Cari siswa berdasarkan RFID
    //     $siswa = Siswa::where('rfid', $rfid)->first();

    //     // Jika ditemukan, lakukan absensi
    //     if ($siswa) {
    //         $kehadiran = 'hadir';
    //         $keterangan = '-';
    //         Carbon::setLocale('id'); // Atur locale ke bahasa Indonesia

    //         $dataTanggal = Carbon::now(); // Ambil tanggal sekarang
    //         $tanggal = $dataTanggal->format('Y-m-d'); // Format tanggal yyyy-mm-dd
    //         $namaHari = $dataTanggal->translatedFormat('l'); // Nama hari dalam bahasa Indonesia

    //         // Update atau buat absensi baru
    //         Absensi::updateOrCreate(
    //             ['id_siswa' => $siswa->id_siswa, 'tanggal' => $tanggal, 'hari' => $namaHari, 'id_kelas' => $siswa->id_kelas, 'id_jurusan' => $siswa->id_jurusan],
    //             ['kehadiran' => $kehadiran, 'keterangan' => $keterangan]
    //         );

            
    //         $message = null; // Tidak ada pesan error
    //     } else {
    //         // Jika tidak ditemukan, beri pesan error
    //         $message = 'Kartu RFID tidak ditemukan dalam database.';
    //     }

    //     // Ambil tanggal hari ini
    //     $tanggalHariIni = Carbon::now()->toDateString();

    //     // Ambil data absensi siswa terbaru yang hadir hari ini
    //     $dataSiswa = Absensi::whereDate('created_at', $tanggalHariIni) // Filter berdasarkan tanggal
    //         ->orderBy('created_at', 'desc') // Urutkan data terbaru
    //         ->take(15) // Batasi hanya 15 data
    //         ->get()
    //         ->map(function ($item) {
    //             $item->jam_masuk = Carbon::parse($item->created_at)->format('H:i'); // Ambil jam saja
    //             return $item;
    //         });

    //     return view('dataAbsen.rfid_absen', compact('kelas', 'layout', 'setting', 'kelasId', 'user', 'siswa', 'dataSiswa', 'message'));
    // }
}
