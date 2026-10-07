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
use App\Exports\SiswaTidakHadirExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class AbsensiController extends Controller
{

    public function index(Request $request)
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        $now = Carbon::now('Asia/Jakarta');
        
        $tanggal = $now->toDateString();
        $hari = Carbon::parse($tanggal)->locale('id')->dayName;
        $kelasList = Kelas::orderBy('nama_kelas', 'asc')->get();
        $absensiQuery = Absensi::whereDate('tanggal', $tanggal)
                              ->with(['siswa.kelas', 'kelas'])
                              ->orderBy('created_at', 'desc');
        $siswaQuery = Siswa::orderBy('nama_siswa', 'asc');

        $siswaList = $siswaQuery->get();
        $absensiSiswa = $absensiQuery->get();
        return view('absensi.index', compact('absensiSiswa', 'layout', 'setting', 'user', 'hari', 'tanggal', 'siswaList', 'kelasList'));
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
        $jam = now()->format('H:i:s');
        foreach ($siswaData as $siswaId => $data) {

            $kehadiran = $data['kehadiran'];
            $keterangan = $data['keterangan'];
            
            $isHadir = strtolower($kehadiran) === 'hadir';
            
            // Periksa apakah sudah ada absensi hari ini yang memiliki status 'Terlambat'
            $existingAbsensi = Absensi::where('id_siswa', $siswaId)
                ->where('tanggal', $request->input('tanggal'))
                ->first();

            if ($existingAbsensi && $isHadir && str_contains(strtolower($existingAbsensi->keterangan), 'terlambat')) {
                // Pertahankan jam masuk dan keterangan terlambat asli
                $jamMasuk = $existingAbsensi->jam_masuk;
                $keterangan = $existingAbsensi->keterangan;
            } else {
                $jamMasuk = $isHadir ? $jam : '-';
                $keterangan = $keterangan ?? '-';
            }
            
            // Fetch student first to get correct id_jurusan and send push notification
            $siswa = Siswa::find($siswaId);
            $idJurusan = $siswa ? $siswa->id_jurusan : $siswaId;
            
            $tipeMasuk = $existingAbsensi ? ($existingAbsensi->tipe_masuk ?? 'manual') : 'manual';
            
            $absensi = Absensi::updateOrCreate(
                [
                    'id_siswa' => $siswaId, 
                    'tanggal' => $request->input('tanggal'), 
                    'hari' => $request->input('hari'), 
                    'id_kelas' => $request->kelas_id
                ],
                [
                    'id_jurusan' => $idJurusan,
                    'kehadiran' => $kehadiran, 
                    'keterangan' => $keterangan,
                    'jam_masuk' => $jamMasuk,
                    'tipe_masuk' => $tipeMasuk
                ]
            );

            \App\Services\WhatsAppNotificationService::sendAttendanceNotification($absensi);

            // Send push notification if student exists (Multi-Device)
            if ($siswa) {
                \App\Services\FcmService::sendToSiswa(
                    $siswa,
                    'Absensi Hari Ini',
                    "Status absensi kamu hari ini (" . date('d-m-Y') . ") telah diperbarui: " . ucfirst($kehadiran)
                );
            }
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
        $jam = now()->format('H:i:s');

        // Ambil id_jurusan dari data siswa
        $siswa = Siswa::findOrFail($siswaId);
        $jurusanId = $siswa->id_jurusan;

        $isHadir = $kehadiran === 'hadir';
        $jamMasuk = $isHadir ? $jam : '-';

        $existingAbsensi = Absensi::where('id_siswa', $siswaId)->where('tanggal', $tanggal)->first();
        $tipeMasuk = $existingAbsensi ? ($existingAbsensi->tipe_masuk ?? 'manual') : 'manual';

        // Simpan absensi
        $absensi = Absensi::updateOrCreate(
            [
                'id_siswa' => $siswaId,
                'tanggal' => $tanggal,
            ],
            [
                'hari' => $hari,
                'id_kelas' => $kelasId,
                'id_jurusan' => $jurusanId,
                'kehadiran' => $kehadiran,
                'keterangan' => $keterangan,
                'jam_masuk' => $jamMasuk,
                'tipe_masuk' => $tipeMasuk,
            ]
        );

        \App\Services\WhatsAppNotificationService::sendAttendanceNotification($absensi);

        // Fetch student and notify (Multi-Device)
        if ($siswa) {
            \App\Services\FcmService::sendToSiswa(
                $siswa,
                'Absensi Hari Ini',
                "Status absensi kamu hari ini (" . date('d-m-Y') . ") telah dicatat: " . ucfirst($kehadiran)
            );
        }

        return back()->with('success', 'Data kehadiran berhasil disimpan.');
    }

    // Controller Data Absensi Kelas
    public function rekapAbsen(Request $request)
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();

        // Jika user adalah wali_kelas, langsung arahkan ke detail rekap kelasnya sendiri
        if ($user && $user->role == 'wali_kelas') {
            $kelasWali = Kelas::where('id_guru', $user->id_guru)->first();
            if ($kelasWali) {
                $tanggal_awal = $request->input('tanggal_awal', Carbon::now()->startOfWeek()->toDateString());
                $tanggal_akhir = $request->input('tanggal_akhir', Carbon::now()->toDateString());
                return redirect('/admin/showRekapAbsen?tanggal_awal=' . $tanggal_awal . '&tanggal_akhir=' . $tanggal_akhir . '&id_kelas=' . $kelasWali->id_kelas);
            }
        }

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
                    'presentase' => round($presentase, 2)
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
        
        if (!$kelasId && $user && $user->role == 'wali_kelas') {
            $kelasWali = Kelas::where('id_guru', $user->id_guru)->first();
            if ($kelasWali) {
                $kelasId = $kelasWali->id_kelas;
            }
        }

        if (!$kelasId) {
            return redirect('/admin/rekapAbsen')->with('error', 'Kelas tidak ditemukan.');
        }

        $dataKelas = Kelas::findOrFail($kelasId);
        
        // Keamanan: Jika wali_kelas, pastikan hanya dapat melihat kelas miliknya
        if ($user && $user->role == 'wali_kelas' && $dataKelas->id_guru != $user->id_guru) {
            $kelasWali = Kelas::where('id_guru', $user->id_guru)->first();
            if ($kelasWali) {
                $kelasId = $kelasWali->id_kelas;
                $dataKelas = $kelasWali;
            } else {
                abort(403, 'Anda tidak memiliki akses ke kelas ini.');
            }
        }

        $tanggal_awal = $request->query('tanggal_awal', Carbon::now()->startOfWeek()->toDateString());
        $tanggal_akhir = $request->query('tanggal_akhir', Carbon::now()->toDateString());

        $siswa = Siswa::where('id_kelas', $kelasId)->orderBy('nama_siswa', 'asc')->get();

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
        $tanggal_awal = $request->query('tanggal_awal', Carbon::now()->startOfWeek()->toDateString());
        $tanggal_akhir = $request->query('tanggal_akhir', Carbon::now()->toDateString());

        if (!$id_kelas) {
            return redirect()->back()->with('error', 'Kelas tidak dipilih.');
        }

        $dataKelas = Kelas::where('id_kelas', $id_kelas)->first();
        if (!$dataKelas) {
            return redirect()->back()->with('error', 'Data kelas tidak ditemukan.');
        }

        $siswa = Siswa::where('id_kelas', $id_kelas)->orderBy('nama_siswa', 'asc')->get();

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
        $tglAwal = Carbon::parse($tanggal_awal)->toDateString();
        $tglAkhir = Carbon::parse($tanggal_akhir)->toDateString();

        $dataAbsen = Absensi::where('id_kelas', $id_kelas)
            ->whereDate('tanggal', '>=', $tglAwal)
            ->whereDate('tanggal', '<=', $tglAkhir)
            ->get();

        foreach ($dataAbsen as $absensi) {
            if (isset($absensiSiswa[$absensi->id_siswa])) {
                $absensiSiswa[$absensi->id_siswa]++;
                $kehadiran = strtolower(trim($absensi->kehadiran ?? ''));
                switch ($kehadiran) {
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
                    case 'masuk':
                        $countMasuk[$absensi->id_siswa]++;
                        break;
                }
            }
        }

        $safeKelas = preg_replace('/[^A-Za-z0-9_\-]/', '_', $dataKelas->nama_kelas);
        $filename = 'data_absensi_' . $safeKelas . '_' . $tglAwal . '_sampai_' . $tglAkhir . '.xlsx';

        try {
            return Excel::download(new AbsensiExport(
                $siswa,
                $absensiSiswa,
                $countMasuk,
                $countSakit,
                $countIzin,
                $countAlfa,
                $dataKelas->nama_kelas
            ), $filename);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Download Rekap Absensi Excel Error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Gagal mengunduh Excel: ' . $e->getMessage());
        }
    }

    /**
     * Fallback show method.
     */
    public function show($id)
    {
        return redirect()->route('admin.absensi.index');
    }

    // Kontroller Untuk Menampilkan Rekap Waktu Kehadiran dan Pulang Siswa
    public function rekapAbsenSiswa(Request $request)
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        $kelas = Kelas::orderBy('nama_kelas', 'asc')->get();
        if ($user && $user->role == 'wali_kelas') {
            $kelasWali = Kelas::where('id_guru', $user->id_guru)->orderBy('nama_kelas', 'asc')->get();
            if ($kelasWali->isNotEmpty()) {
                $kelas = $kelasWali;
            }
        }

        $tanggalAwal = $request->input('tanggal_awal', Carbon::now()->startOfMonth()->toDateString());
        $tanggalAkhir = $request->input('tanggal_akhir', Carbon::now()->toDateString());
        
        $kelasId = $request->input('kelas');
        if (!$kelasId && $user && $user->role == 'wali_kelas') {
            $kelasId = $kelas->first()?->id_kelas;
        }

        if ($kelasId) {
            $dataKelas = Kelas::where('id_kelas', $kelasId)->first();

            $rekapKehadiran = Absensi::whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
                ->whereHas('siswa', function ($query) use ($kelasId) {
                    $query->where('id_kelas', $kelasId);
                })
                ->with('siswa')
                ->get()
                ->groupBy('id_siswa');

            return view('dataAbsen.rekap_absen', compact('layout', 'setting', 'user', 'kelas', 'rekapKehadiran', 'tanggalAwal', 'tanggalAkhir', 'kelasId', 'dataKelas'));
        }

        return view('dataAbsen.rekap_absen', compact('layout', 'setting', 'user', 'kelas', 'kelasId', 'tanggalAwal', 'tanggalAkhir'));
    }

    // Controller Untuk Mendownload Rekap Waktu Kehadiran dan Pulang Siswa
    public function exportRekapSiswa(Request $request)
    {
        $user = Auth::user();
        $id_kelas     = $request->id_kelas;
        if ($user && $user->role == 'wali_kelas') {
            $kelasWali = Kelas::where('id_guru', $user->id_guru)->first();
            if ($kelasWali) {
                $id_kelas = $kelasWali->id_kelas;
            }
        }
        $tanggal_awal = $request->input('tanggal_awal', Carbon::now()->startOfMonth()->toDateString());
        $tanggal_akhir= $request->input('tanggal_akhir', Carbon::now()->toDateString());

        // Ambil data kelas untuk mendapatkan nama kelas
        $kelas = Kelas::find($id_kelas);
        // Format nama kelas agar tidak ada spasi, misalnya diganti dengan underscore
        $namaKelas = $kelas ? str_replace(' ', '_', strtoupper($kelas->nama_kelas)) : 'kelas';

        // Buat filename dengan format: rekap_absen_nama kelas_tanggal awal-tanggal akhir.xlsx
        $filename = 'rekap_absen_' . $namaKelas . '_' . $tanggal_awal . '-' . $tanggal_akhir . '.xlsx';

        return Excel::download(new AbsensiSiswaRekapExport($id_kelas, $tanggal_awal, $tanggal_akhir), $filename);
    }

    public function AbsensiSiswaExport(Request $request)
    {
        $now = Carbon::now('Asia/Jakarta');
        $tanggal = $now->format('Y-m-d');
        $hari = Carbon::parse($tanggal)->locale('id')->translatedFormat('l');

        $fileName = "Kehadiran_Siswa_{$hari}_{$tanggal}.xlsx";

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
            
            // Periksa apakah sudah ada absensi hari ini yang memiliki status 'Terlambat'
            $existingAbsensi = Absensi::where('id_siswa', $siswaId)
                ->where('tanggal', $today)
                ->first();

            if ($existingAbsensi && $isHadir && str_contains(strtolower($existingAbsensi->keterangan), 'terlambat')) {
                // Pertahankan jam masuk dan keterangan terlambat asli
                $jamMasuk = $existingAbsensi->jam_masuk;
                $keterangan = $existingAbsensi->keterangan;
            } else {
                $jamMasuk = $isHadir ? $jam : '-';
                $keterangan = '-';
            }

            $tipeMasuk = $existingAbsensi ? ($existingAbsensi->tipe_masuk ?? 'manual') : 'manual';

            $absensi = Absensi::updateOrCreate(
                [
                    'id_siswa' => $siswaId,
                    'tanggal' => $today,
                ],
                [
                    'id_kelas' => $kelasIds[$index],
                    'id_jurusan' => $jurusanIds[$index],
                    'hari' => $hari,
                    'jam_masuk' => $jamMasuk,
                    'kehadiran' => $kehadiran[$index],
                    'keterangan' => $keterangan,
                    'tipe_masuk' => $tipeMasuk
                ]
            );

            \App\Services\WhatsAppNotificationService::sendAttendanceNotification($absensi);

            // Fetch student and notify (Multi-Device)
            $siswa = Siswa::find($siswaId);
            if ($siswa) {
                \App\Services\FcmService::sendToSiswa(
                    $siswa,
                    'Absensi Hari Ini',
                    "Status absensi kamu hari ini (" . date('d-m-Y') . ") telah disimpan: " . ucfirst($kehadiran[$index])
                );
            }
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
        
        $oldKehadiran = $absensi->kehadiran;
        $oldKeterangan = $absensi->keterangan;
        $newKehadiran = $request->kehadiran;

        // Cek jika status sebelumnya bukan 'Hadir' (case-insensitive) dan akan diubah menjadi 'Hadir'
        if (strtolower($oldKehadiran) !== 'hadir' && strtolower($newKehadiran) === 'hadir') {
            $absensi->jam_masuk = $jam; // isi jam masuk sekarang
            $absensi->keterangan = '-'; // set keterangan to '-'
        }

        // JIKA diubah dari Hadir (Terlambat) menjadi Sakit/Izin/Alfa, hapus poin pelanggaran keterlambatannya
        if (strtolower($oldKehadiran) === 'hadir' && str_contains(strtolower($oldKeterangan), 'terlambat') && strtolower($newKehadiran) !== 'hadir') {
            // Hapus poin pelanggaran hari ini untuk siswa tersebut (ID Point 1)
            \App\Models\PointSiswa::where('id_siswa', $absensi->id_siswa)
                ->where('id_point', 1)
                ->whereDate('created_at', $absensi->tanggal)
                ->delete();
            
            $absensi->keterangan = '-'; // Reset keterangan agar tidak mengandung kata "Terlambat"
        }

        $absensi->kehadiran = $newKehadiran;
        $absensi->save();

        \App\Services\WhatsAppNotificationService::sendAttendanceNotification($absensi);

        // Notify student about attendance update (Multi-Device)
        $siswa = Siswa::find($absensi->id_siswa);
        if ($siswa) {
            \App\Services\FcmService::sendToSiswa(
                $siswa,
                'Perubahan Absensi',
                "Status absensi kamu pada tanggal " . $absensi->tanggal . " telah diubah menjadi: " . ucfirst($newKehadiran)
            );
        }

        return redirect()->back()->with('success', 'Data kehadiran berhasil diperbarui.');
    }

    public function siswaTidakHadir(Request $request)
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();

        $kelasList = Kelas::orderBy('nama_kelas', 'asc')->get();
        $selectedKelas = $request->input('id_kelas');
        $selectedStatus = $request->input('status'); // sakit, izin, alfa, all

        // Default tanggal: Jika ada filter rentang gunakan rentang, jika tidak default hari ini
        $tanggalMulai = $request->input('tanggal_mulai', Carbon::today()->toDateString());
        $tanggalAkhir = $request->input('tanggal_akhir', Carbon::today()->toDateString());

        // Jika ada param today, override ke hari ini
        if ($request->has('today')) {
            $tanggalMulai = Carbon::today()->toDateString();
            $tanggalAkhir = Carbon::today()->toDateString();
        }

        $query = Absensi::with(['siswa.kelas', 'kelas'])
            ->leftJoin('kelas', 'absensi.id_kelas', '=', 'kelas.id_kelas')
            ->leftJoin('siswa', 'absensi.id_siswa', '=', 'siswa.id_siswa')
            ->whereBetween('absensi.tanggal', [$tanggalMulai, $tanggalAkhir])
            ->whereIn('absensi.kehadiran', ['sakit', 'izin', 'alfa'])
            ->select('absensi.*');

        // Filter role wali_kelas
        if ($user && $user->role == 'wali_kelas') {
            $kelasWali = Kelas::where('id_guru', $user->id_guru)->first();
            if ($kelasWali) {
                $query->where('absensi.id_kelas', $kelasWali->id_kelas);
                $selectedKelas = $kelasWali->id_kelas;
            }
        } elseif (!empty($selectedKelas)) {
            $query->where('absensi.id_kelas', $selectedKelas);
        }

        if (!empty($selectedStatus) && in_array($selectedStatus, ['sakit', 'izin', 'alfa'])) {
            $query->where('absensi.kehadiran', $selectedStatus);
        }

        // Urutkan berdasarkan Kelas terlebih dahulu, kemudian Nama Siswa secara alfabetis (A-Z)
        $dataTidakHadir = $query
            ->orderBy('kelas.nama_kelas', 'asc')
            ->orderBy('siswa.nama_siswa', 'asc')
            ->orderBy('absensi.tanggal', 'desc')
            ->get();

        // Statistik rekap
        $countSakit = $dataTidakHadir->where('kehadiran', 'sakit')->count();
        $countIzin = $dataTidakHadir->where('kehadiran', 'izin')->count();
        $countAlfa = $dataTidakHadir->where('kehadiran', 'alfa')->count();
        $countTotal = $dataTidakHadir->count();

        return view('dataAbsen.data_siswaTidakHadir', compact(
            'layout', 'setting', 'user', 'dataTidakHadir', 'kelasList',
            'selectedKelas', 'selectedStatus', 'tanggalMulai', 'tanggalAkhir',
            'countSakit', 'countIzin', 'countAlfa', 'countTotal'
        ));
    }

    public function exportSiswaTidakHadir(Request $request)
    {
        $user = Auth::user();
        $tanggalMulai = $request->input('tanggal_mulai', Carbon::today()->toDateString());
        $tanggalAkhir = $request->input('tanggal_akhir', Carbon::today()->toDateString());
        $idKelas = $request->input('id_kelas');
        $status = $request->input('status');

        if ($request->has('today')) {
            $tanggalMulai = Carbon::today()->toDateString();
            $tanggalAkhir = Carbon::today()->toDateString();
        }

        $fileName = "Rekap_Siswa_Tidak_Hadir_{$tanggalMulai}_sd_{$tanggalAkhir}.xlsx";
        if ($tanggalMulai === $tanggalAkhir) {
            $fileName = "Rekap_Siswa_Tidak_Hadir_{$tanggalMulai}.xlsx";
        }

        return Excel::download(new SiswaTidakHadirExport(
            $tanggalMulai,
            $tanggalAkhir,
            $idKelas,
            $status,
            $user
        ), $fileName);
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
