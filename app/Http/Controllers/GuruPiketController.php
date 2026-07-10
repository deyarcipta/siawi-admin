<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Guru;
use App\Models\GuruPiket;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;

class GuruPiketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        $piket = GuruPiket::with('guru')->get();
        
        $hariOrder = [
            'Senin' => 1,
            'Selasa' => 2,
            'Rabu' => 3,
            'Kamis' => 4,
            'Jumat' => 5,
            'Sabtu' => 6,
            'Minggu' => 7
        ];
        
        $piket = $piket->sortBy(function ($item) use ($hariOrder) {
            return $hariOrder[$item->hari] ?? 99;
        });

        return view('dataPiket.data_piket', compact('layout', 'setting', 'user', 'piket'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        $guru = Guru::orderBy('nama_guru', 'asc')->get();
        return view('dataPiket.tambah_piket', compact('layout', 'setting', 'user', 'guru'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_guru' => 'required|exists:guru,id_guru',
            'hari' => 'required',
            'waktu_awal' => 'required',
            'waktu_akhir' => 'required',
        ]);

        GuruPiket::create([
            'id_guru' => $request->id_guru,
            'hari' => $request->hari,
            'waktu_awal' => $request->waktu_awal,
            'waktu_akhir' => $request->waktu_akhir,
        ]);

        return redirect('/admin/guruPiket')->with('success', 'Jadwal piket guru berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id_piket)
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        $edit = GuruPiket::findOrFail($id_piket);
        $guru = Guru::orderBy('nama_guru', 'asc')->get();
        return view('dataPiket.edit_piket', compact('layout', 'setting', 'user', 'edit', 'guru'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id_piket)
    {
        $request->validate([
            'id_guru' => 'required|exists:guru,id_guru',
            'hari' => 'required',
            'waktu_awal' => 'required',
            'waktu_akhir' => 'required',
        ]);

        $piket = GuruPiket::findOrFail($id_piket);
        $piket->update([
            'id_guru' => $request->id_guru,
            'hari' => $request->hari,
            'waktu_awal' => $request->waktu_awal,
            'waktu_akhir' => $request->waktu_akhir,
        ]);

        return redirect('/admin/guruPiket')->with('success', 'Jadwal piket guru berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_piket)
    {
        $piket = GuruPiket::findOrFail($id_piket);
        $piket->delete();

        return redirect('/admin/guruPiket')->with('success', 'Jadwal piket guru berhasil dihapus.');
    }

    /**
     * Tampilkan halaman panel khusus Guru Piket
     */
    public function panel()
    {
        $layout = 'layout.app';
        $setting = \App\Models\Setting::find('1');
        $user = Auth::user();
        
        $daysInIndonesian = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ];
        $todayDayEng = \Carbon\Carbon::now()->format('l');
        $todayDayInd = $daysInIndonesian[$todayDayEng] ?? 'Senin';
        
        // Ambil jadwal piket hari ini
        $piketHariIni = GuruPiket::with('guru')
            ->where('hari', $todayDayInd)
            ->get();
            
        // Ambil data siswa untuk dropdown
        $siswa = \App\Models\Siswa::with('kelas', 'jurusan')->orderBy('nama_siswa', 'asc')->get();
        
        // Ambil absensi hari ini yang terlambat
        $today = \Carbon\Carbon::now()->toDateString();
        $absensiTerlambat = \App\Models\Absensi::where('tanggal', $today)
            ->where('kehadiran', 'hadir')
            ->where('keterangan', 'like', '%Terlambat%')
            ->with('siswa', 'kelas')
            ->get();
            
        return view('dataPiket.piket_panel', compact('layout', 'setting', 'user', 'piketHariIni', 'siswa', 'absensiTerlambat', 'todayDayInd'));
    }

    /**
     * Catat keterlambatan siswa dan tambahkan poin pelanggaran otomatis
     */
    public function catatTerlambat(Request $request)
    {
        $request->validate([
            'id_siswa' => 'required|exists:siswa,id_siswa',
        ]);
        
        $siswa = \App\Models\Siswa::findOrFail($request->id_siswa);
        $now = \Carbon\Carbon::now('Asia/Jakarta');
        $today = $now->toDateString();
        $jam = $now->format('H:i');
        
        $daysInIndonesian = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ];
        $todayDayEng = $now->format('l');
        $hari = $daysInIndonesian[$todayDayEng] ?? 'Senin';
        
        // Cek apakah siswa sudah dicatat terlambat hari ini untuk mencegah pencatatan ganda
        $isAlreadyLate = \App\Models\Absensi::where('id_siswa', $siswa->id_siswa)
            ->where('tanggal', $today)
            ->where('keterangan', 'like', '%Terlambat%')
            ->exists();
            
        if ($isAlreadyLate) {
            return redirect()->route('admin.guruPiket.panel')->with('failed', 'Siswa ini sudah dicatat terlambat hari ini!');
        }
        
        // 1. Cek atau buat absensi hari ini
        $absensi = \App\Models\Absensi::where('id_siswa', $siswa->id_siswa)
            ->where('tanggal', $today)
            ->first();
            
        if ($absensi) {
            $absensi->update([
                'kehadiran' => 'hadir',
                'keterangan' => 'Terlambat (Dicatat Guru Piket pada ' . $jam . ')',
                'jam_masuk' => $jam
            ]);
        } else {
            \App\Models\Absensi::create([
                'id_siswa' => $siswa->id_siswa,
                'id_kelas' => $siswa->id_kelas,
                'id_jurusan' => $siswa->id_jurusan,
                'hari' => $hari,
                'tanggal' => $today,
                'jam_masuk' => $jam,
                'kehadiran' => 'hadir',
                'keterangan' => 'Terlambat (Dicatat Guru Piket)',
            ]);
        }
        
        // 2. Tambahkan Point Pelanggaran "Terlambat Masuk Sekolah" (id_point = 1, skor = 5)
        $point = \App\Models\Point::find(1);
        if (!$point) {
            $point = \App\Models\Point::where('nama_point', 'like', '%Terlambat%')->first();
        }
        
        $skor = $point ? $point->skor_point : 5;
        $id_point = $point ? $point->id_point : 1;
        
        $guru = Auth::user();
        
        \App\Models\PointSiswa::create([
            'id_siswa' => $siswa->id_siswa,
            'id_point' => $id_point,
            'id_kelas' => $siswa->id_kelas,
            'id_jurusan' => $siswa->id_jurusan,
            'id_guru' => $guru->id_guru,
            'role' => $guru->role,
            'skor_point' => $skor,
            'tanggal' => $now->locale('id')->translatedFormat('d F Y') . ' ' . $jam
        ]);
        
        return redirect()->route('admin.guruPiket.panel')->with('success', 'Keterlambatan siswa ' . $siswa->nama_siswa . ' berhasil dicatat dan poin pelanggaran (+5) ditambahkan.');
    }

    /**
     * Hapus data keterlambatan siswa pada hari ini (menghapus absensi dan poin pelanggaran terkait)
     */
    public function hapusTerlambat($id_absensi)
    {
        $absensi = \App\Models\Absensi::findOrFail($id_absensi);
        $id_siswa = $absensi->id_siswa;
        $tanggal = $absensi->tanggal; // Format: Yyyy-mm-dd
        
        // Hapus absensi harian
        $absensi->delete();
        
        // Hapus point_siswa keterlambatan terkait (id_point = 1) yang diinput hari ini
        \App\Models\PointSiswa::where('id_siswa', $id_siswa)
            ->where('id_point', 1)
            ->whereDate('created_at', $tanggal)
            ->delete();
            
        return redirect()->route('admin.guruPiket.panel')->with('success', 'Data keterlambatan siswa berhasil dihapus dan poin pelanggaran dikembalikan.');
    }
}
