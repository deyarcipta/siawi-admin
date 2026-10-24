<?php

namespace App\Http\Controllers\Kejuruan;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\JadwalMapel;
use App\Models\JadwalPraktik;
use App\Models\Kelas;
use App\Models\Lab;
use App\Models\Mapel;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalPraktikController extends Controller
{
    /**
     * Tampilkan kalender mingguan atau daftar jadwal praktik.
     */
    public function index(Request $request)
    {
        $layout = 'layout.app';
        $setting = Setting::find(1);
        $user = Auth::user();

        $query = JadwalPraktik::forUserJurusan($user)
            ->with(['kelas.jurusan', 'lab.jurusan', 'guru', 'mapel']);

        if ($request->filled('id_kelas')) {
            $query->where('id_kelas', $request->id_kelas);
        }

        if ($request->filled('id_lab')) {
            $query->where('id_lab', $request->id_lab);
        }

        if ($request->filled('id_guru')) {
            $query->where('id_guru', $request->id_guru);
        }

        if ($request->filled('hari')) {
            $query->where('hari', $request->hari);
        }

        $jadwals = $query->orderBy('jam_mulai', 'asc')->get();

        // Data untuk dropdown filter
        $labs = Lab::forUserJurusan($user)->orderBy('nama_lab', 'asc')->get();
        $kelas = Kelas::forUserJurusan($user)->orderBy('nama_kelas', 'asc')->get();
        $gurus = Guru::orderBy('nama_guru', 'asc')->get();

        // Kelompokkan jadwal per hari untuk kalender mingguan
        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $jadwalPerHari = [];
        foreach ($hariList as $h) {
            $jadwalPerHari[$h] = $jadwals->where('hari', $h)->sortBy('jam_mulai');
        }

        $viewMode = $request->input('view', 'calendar'); // 'calendar' atau 'table'

        return view('kejuruan.jadwal_praktik.index', compact(
            'layout', 'setting', 'user', 'jadwals', 'labs', 'kelas', 'gurus',
            'hariList', 'jadwalPerHari', 'viewMode'
        ));
    }

    /**
     * Form tambah jadwal praktik baru.
     */
    public function create()
    {
        $layout = 'layout.app';
        $setting = Setting::find(1);
        $user = Auth::user();

        if (!$user->hasAnyRole(['admin', 'kaprog', 'guru_jurusan'])) {
            abort(403, 'Akses terbatas untuk penambahan jadwal praktik.');
        }

        $labs = Lab::forUserJurusan($user)->orderBy('nama_lab', 'asc')->get();
        $kelas = Kelas::forUserJurusan($user)->orderBy('nama_kelas', 'asc')->get();
        $gurus = Guru::orderBy('nama_guru', 'asc')->get();
        $mapels = Mapel::orderBy('nama_mapel', 'asc')->get();
        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        return view('kejuruan.jadwal_praktik.create', compact(
            'layout', 'setting', 'user', 'labs', 'kelas', 'gurus', 'mapels', 'hariList'
        ));
    }

    /**
     * Simpan jadwal praktik baru dengan validasi bentrok bertingkat.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user->hasAnyRole(['admin', 'kaprog', 'guru_jurusan'])) {
            abort(403, 'Akses terbatas.');
        }

        $request->validate([
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'id_lab' => 'required|exists:lab,id_lab',
            'id_guru' => 'required|exists:guru,id_guru',
            'id_mapel' => 'required|exists:mapel,id_mapel',
            'hari' => 'required|string',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'alat_bahan' => 'nullable|string',
        ], [
            'id_kelas.required' => 'Kelas wajib dipilih.',
            'id_lab.required' => 'Laboratorium wajib dipilih.',
            'id_guru.required' => 'Guru instruktur wajib dipilih.',
            'id_mapel.required' => 'Mata pelajaran kejuruan wajib dipilih.',
            'hari.required' => 'Hari pelaksanaan wajib dipilih.',
            'jam_mulai.required' => 'Jam mulai wajib diisi.',
            'jam_selesai.required' => 'Jam selesai wajib diisi.',
            'jam_selesai.after' => 'Jam selesai harus lebih akhir dari jam mulai.',
        ]);

        $setting = Setting::find(1);
        $tahunAjaran = $setting->tahun_ajaran ?? '2026/2027';
        $semester = $setting->semester_aktif ?? 'Ganjil';

        // Pengecekan bentrok otomatis
        $conflictError = $this->detectScheduleConflict(
            $request->hari,
            $request->jam_mulai,
            $request->jam_selesai,
            $request->id_lab,
            $request->id_guru,
            $request->id_kelas
        );

        if ($conflictError) {
            return back()->withInput()->with('error', $conflictError);
        }

        JadwalPraktik::create([
            'id_kelas' => $request->id_kelas,
            'id_lab' => $request->id_lab,
            'id_guru' => $request->id_guru,
            'id_mapel' => $request->id_mapel,
            'hari' => ucfirst(strtolower($request->hari)),
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'tahun_ajaran' => $tahunAjaran,
            'semester' => $semester,
            'alat_bahan' => $request->alat_bahan,
        ]);

        return redirect('/admin/kejuruan/jadwal-praktik')->with('success', 'Jadwal praktik berhasil disimpan tanpa bentrok.');
    }

    /**
     * Form edit jadwal praktik.
     */
    public function edit(string $id)
    {
        $layout = 'layout.app';
        $setting = Setting::find(1);
        $user = Auth::user();

        if (!$user->hasAnyRole(['admin', 'kaprog', 'guru_jurusan'])) {
            abort(403, 'Akses terbatas.');
        }

        $jadwal = JadwalPraktik::forUserJurusan($user)->findOrFail($id);
        $labs = Lab::forUserJurusan($user)->orderBy('nama_lab', 'asc')->get();
        $kelas = Kelas::forUserJurusan($user)->orderBy('nama_kelas', 'asc')->get();
        $gurus = Guru::orderBy('nama_guru', 'asc')->get();
        $mapels = Mapel::orderBy('nama_mapel', 'asc')->get();
        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        return view('kejuruan.jadwal_praktik.edit', compact(
            'layout', 'setting', 'user', 'jadwal', 'labs', 'kelas', 'gurus', 'mapels', 'hariList'
        ));
    }

    /**
     * Update jadwal praktik dengan pengecekan bentrok.
     */
    public function update(Request $request, string $id)
    {
        $user = Auth::user();
        if (!$user->hasAnyRole(['admin', 'kaprog', 'guru_jurusan'])) {
            abort(403, 'Akses terbatas.');
        }

        $jadwal = JadwalPraktik::forUserJurusan($user)->findOrFail($id);

        $request->validate([
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'id_lab' => 'required|exists:lab,id_lab',
            'id_guru' => 'required|exists:guru,id_guru',
            'id_mapel' => 'required|exists:mapel,id_mapel',
            'hari' => 'required|string',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'alat_bahan' => 'nullable|string',
        ]);

        // Cek bentrok dengan mengabaikan ID saat ini
        $conflictError = $this->detectScheduleConflict(
            $request->hari,
            $request->jam_mulai,
            $request->jam_selesai,
            $request->id_lab,
            $request->id_guru,
            $request->id_kelas,
            $id
        );

        if ($conflictError) {
            return back()->withInput()->with('error', $conflictError);
        }

        $jadwal->update([
            'id_kelas' => $request->id_kelas,
            'id_lab' => $request->id_lab,
            'id_guru' => $request->id_guru,
            'id_mapel' => $request->id_mapel,
            'hari' => ucfirst(strtolower($request->hari)),
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'alat_bahan' => $request->alat_bahan,
        ]);

        return redirect('/admin/kejuruan/jadwal-praktik')->with('success', 'Jadwal praktik berhasil diperbarui.');
    }

    /**
     * Hapus jadwal praktik.
     */
    public function destroy(string $id)
    {
        $user = Auth::user();
        if (!$user->hasAnyRole(['admin', 'kaprog', 'guru_jurusan'])) {
            abort(403, 'Akses terbatas.');
        }

        $jadwal = JadwalPraktik::forUserJurusan($user)->findOrFail($id);
        $jadwal->delete();

        return redirect('/admin/kejuruan/jadwal-praktik')->with('success', 'Jadwal praktik berhasil dihapus.');
    }

    /**
     * Deteksi bentrok jadwal praktik dengan jadwal praktik lain maupun jadwal pelajaran umum.
     */
    public function detectScheduleConflict(
        string $hari,
        string $jamMulai,
        string $jamSelesai,
        int $idLab,
        int $idGuru,
        int $idKelas,
        ?int $excludeId = null
    ): ?string {
        $hariNorm = ucfirst(strtolower(trim($hari)));
        $hariLower = strtolower(trim($hari));

        // Format waktu pembanding
        $mulaiTime = strlen($jamMulai) === 5 ? $jamMulai . ':00' : $jamMulai;
        $selesaiTime = strlen($jamSelesai) === 5 ? $jamSelesai . ':00' : $jamSelesai;

        // -------------------------------------------------------------
        // 1. Cek bentrok pada tabel jadwal_praktik
        // -------------------------------------------------------------
        $basePraktikQuery = JadwalPraktik::where('hari', $hariNorm)
            ->where('jam_mulai', '<', $selesaiTime)
            ->where('jam_selesai', '>', $mulaiTime);

        if ($excludeId) {
            $basePraktikQuery->where('id_jadwal_praktik', '!=', $excludeId);
        }

        // 1a. Bentrok Laboratorium
        $labConflict = (clone $basePraktikQuery)
            ->where('id_lab', $idLab)
            ->with(['lab', 'kelas'])
            ->first();

        if ($labConflict) {
            return "Bentrok Laboratorium: Ruang " . ($labConflict->lab->nama_lab ?? 'Lab') .
                " sudah dijadwalkan untuk kelas " . ($labConflict->kelas->nama_kelas ?? 'Lain') .
                " pada hari " . $hariNorm . " pukul " . substr($labConflict->jam_mulai, 0, 5) .
                " sampai " . substr($labConflict->jam_selesai, 0, 5) . ".";
        }

        // 1b. Bentrok Guru di jadwal praktik
        $guruPraktikConflict = (clone $basePraktikQuery)
            ->where('id_guru', $idGuru)
            ->with(['guru', 'lab'])
            ->first();

        if ($guruPraktikConflict) {
            return "Bentrok Jadwal Guru: Guru " . ($guruPraktikConflict->guru->nama_guru ?? 'Pengajar') .
                " sudah memiliki jadwal praktik lain di " . ($guruPraktikConflict->lab->nama_lab ?? 'Lab lain') .
                " pada hari " . $hariNorm . " pukul " . substr($guruPraktikConflict->jam_mulai, 0, 5) .
                " sampai " . substr($guruPraktikConflict->jam_selesai, 0, 5) . ".";
        }

        // 1c. Bentrok Kelas di jadwal praktik
        $kelasPraktikConflict = (clone $basePraktikQuery)
            ->where('id_kelas', $idKelas)
            ->with(['kelas', 'lab'])
            ->first();

        if ($kelasPraktikConflict) {
            return "Bentrok Jadwal Kelas: Kelas " . ($kelasPraktikConflict->kelas->nama_kelas ?? 'Kelas') .
                " sudah memiliki jadwal praktik di " . ($kelasPraktikConflict->lab->nama_lab ?? 'Lab lain') .
                " pada hari " . $hariNorm . " pukul " . substr($kelasPraktikConflict->jam_mulai, 0, 5) .
                " sampai " . substr($kelasPraktikConflict->jam_selesai, 0, 5) . ".";
        }

        // -------------------------------------------------------------
        // 2. Cek silang bentrok dengan jadwal_mapel umum (teori)
        // -------------------------------------------------------------
        $baseMapelQuery = JadwalMapel::whereRaw('LOWER(hari) = ?', [$hariLower])
            ->where('waktu_awal', '<', substr($selesaiTime, 0, 5))
            ->where('waktu_akhir', '>', substr($mulaiTime, 0, 5));

        // 2a. Bentrok Guru dengan jadwal mengajar teori umum
        $guruMapelConflict = (clone $baseMapelQuery)
            ->where('id_guru', $idGuru)
            ->with(['guru', 'mapel', 'kelas'])
            ->first();

        if ($guruMapelConflict) {
            return "Bentrok Jadwal Teori Umum: Guru " . ($guruMapelConflict->guru->nama_guru ?? 'Pengajar') .
                " memiliki jadwal mengajar mapel " . ($guruMapelConflict->mapel->nama_mapel ?? 'Teori') .
                " di kelas " . ($guruMapelConflict->kelas->nama_kelas ?? '-') .
                " pada hari " . $hariNorm . " pukul " . $guruMapelConflict->waktu_awal .
                " sampai " . $guruMapelConflict->waktu_akhir . ".";
        }

        // 2b. Bentrok Kelas dengan jadwal teori umum
        $kelasMapelConflict = (clone $baseMapelQuery)
            ->where('id_kelas', $idKelas)
            ->with(['kelas', 'mapel', 'guru'])
            ->first();

        if ($kelasMapelConflict) {
            return "Bentrok Jadwal Teori Umum: Kelas " . ($kelasMapelConflict->kelas->nama_kelas ?? 'Kelas') .
                " sudah memiliki jadwal pelajaran " . ($kelasMapelConflict->mapel->nama_mapel ?? 'Mapel') .
                " bersama " . ($kelasMapelConflict->guru->nama_guru ?? 'Guru') .
                " pada hari " . $hariNorm . " pukul " . $kelasMapelConflict->waktu_awal .
                " sampai " . $kelasMapelConflict->waktu_akhir . ".";
        }

        return null;
    }
}
