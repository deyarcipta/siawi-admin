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

        // Ambil jadwal mapel kurikulum yang dapat ditautkan secara langsung
        $jadwalMapelQuery = JadwalMapel::with(['kelas.jurusan', 'guru', 'mapel']);
        if (!$user->hasRole('admin')) {
            $accessibleJurusanIds = Lab::getAccessibleJurusanIds($user);
            $jadwalMapelQuery->whereHas('kelas', function ($q) use ($accessibleJurusanIds) {
                $q->whereIn('id_jurusan', $accessibleJurusanIds);
            });
        }
        $jadwalMapelList = $jadwalMapelQuery->orderBy('hari', 'asc')->orderBy('waktu_awal', 'asc')->get();

        return view('kejuruan.jadwal_praktik.create', compact(
            'layout', 'setting', 'user', 'labs', 'kelas', 'gurus', 'mapels', 'hariList', 'jadwalMapelList'
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

        $tipeJadwal = $request->input('tipe_jadwal', 'rutin');
        $tanggalKhusus = $request->input('tanggal_khusus');
        $allowOverrideMapel = $request->boolean('override_mapel');

        $rules = [
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'id_lab' => 'required|exists:lab,id_lab',
            'id_guru' => 'required|exists:guru,id_guru',
            'id_mapel' => 'required|exists:mapel,id_mapel',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'alat_bahan' => 'nullable|string',
            'tipe_jadwal' => 'required|in:rutin,insidental',
            'id_jadwal_mapel' => 'nullable|exists:jadwal_mapel,id_jadwal',
        ];

        if ($tipeJadwal === 'insidental') {
            $rules['tanggal_khusus'] = 'required|date';
            $hariMap = [
                'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu',
                'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu', 'Sunday' => 'Minggu',
            ];
            $hari = $hariMap[\Carbon\Carbon::parse($tanggalKhusus)->format('l')] ?? 'Senin';
            $frekuensi = 'setiap_minggu';
        } else {
            $rules['hari'] = 'required|string';
            $rules['frekuensi'] = 'required|in:setiap_minggu,minggu_ganjil,minggu_genap';
            $hari = ucfirst(strtolower($request->hari));
            $frekuensi = $request->input('frekuensi', 'setiap_minggu');
            $tanggalKhusus = null;
        }

        $request->validate($rules, [
            'id_kelas.required' => 'Kelas wajib dipilih.',
            'id_lab.required' => 'Laboratorium wajib dipilih.',
            'id_guru.required' => 'Guru instruktur wajib dipilih.',
            'id_mapel.required' => 'Mata pelajaran kejuruan wajib dipilih.',
            'hari.required' => 'Hari pelaksanaan wajib dipilih.',
            'tanggal_khusus.required' => 'Tanggal pelaksanaan khusus wajib diisi untuk praktik dadakan.',
            'jam_mulai.required' => 'Jam mulai wajib diisi.',
            'jam_selesai.required' => 'Jam selesai wajib diisi.',
            'jam_selesai.after' => 'Jam selesai harus lebih akhir dari jam mulai.',
        ]);

        $setting = Setting::find(1);
        $tahunAjaran = $setting->tahun_ajaran ?? '2026/2027';
        $semester = $setting->semester_aktif ?? 'Ganjil';

        // Pengecekan bentrok cerdas
        $conflictError = $this->detectScheduleConflict(
            $hari,
            $request->jam_mulai,
            $request->jam_selesai,
            (int) $request->id_lab,
            (int) $request->id_guru,
            (int) $request->id_kelas,
            (int) $request->id_mapel,
            null,
            $tipeJadwal,
            $frekuensi,
            $tanggalKhusus,
            $allowOverrideMapel
        );

        if ($conflictError) {
            return back()->withInput()->with('error', $conflictError);
        }

        JadwalPraktik::create([
            'id_kelas' => $request->id_kelas,
            'id_lab' => $request->id_lab,
            'id_guru' => $request->id_guru,
            'id_mapel' => $request->id_mapel,
            'tipe_jadwal' => $tipeJadwal,
            'frekuensi' => $frekuensi,
            'tanggal_khusus' => $tanggalKhusus,
            'id_jadwal_mapel' => $request->id_jadwal_mapel,
            'hari' => $hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'tahun_ajaran' => $tahunAjaran,
            'semester' => $semester,
            'alat_bahan' => $request->alat_bahan,
        ]);

        $pesan = $tipeJadwal === 'insidental' 
            ? 'Praktik tambahan / insidental berhasil dijadwalkan.'
            : 'Jadwal praktik mingguan berhasil disimpan.';

        return redirect('/admin/kejuruan/jadwal-praktik')->with('success', $pesan);
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

        $jadwalMapelQuery = JadwalMapel::with(['kelas.jurusan', 'guru', 'mapel']);
        if (!$user->hasRole('admin')) {
            $accessibleJurusanIds = Lab::getAccessibleJurusanIds($user);
            $jadwalMapelQuery->whereHas('kelas', function ($q) use ($accessibleJurusanIds) {
                $q->whereIn('id_jurusan', $accessibleJurusanIds);
            });
        }
        $jadwalMapelList = $jadwalMapelQuery->orderBy('hari', 'asc')->orderBy('waktu_awal', 'asc')->get();

        return view('kejuruan.jadwal_praktik.edit', compact(
            'layout', 'setting', 'user', 'jadwal', 'labs', 'kelas', 'gurus', 'mapels', 'hariList', 'jadwalMapelList'
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

        $tipeJadwal = $request->input('tipe_jadwal', 'rutin');
        $tanggalKhusus = $request->input('tanggal_khusus');
        $allowOverrideMapel = $request->boolean('override_mapel');

        $rules = [
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'id_lab' => 'required|exists:lab,id_lab',
            'id_guru' => 'required|exists:guru,id_guru',
            'id_mapel' => 'required|exists:mapel,id_mapel',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'alat_bahan' => 'nullable|string',
            'tipe_jadwal' => 'required|in:rutin,insidental',
            'id_jadwal_mapel' => 'nullable|exists:jadwal_mapel,id_jadwal',
        ];

        if ($tipeJadwal === 'insidental') {
            $rules['tanggal_khusus'] = 'required|date';
            $hariMap = [
                'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu',
                'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu', 'Sunday' => 'Minggu',
            ];
            $hari = $hariMap[\Carbon\Carbon::parse($tanggalKhusus)->format('l')] ?? 'Senin';
            $frekuensi = 'setiap_minggu';
        } else {
            $rules['hari'] = 'required|string';
            $rules['frekuensi'] = 'required|in:setiap_minggu,minggu_ganjil,minggu_genap';
            $hari = ucfirst(strtolower($request->hari));
            $frekuensi = $request->input('frekuensi', 'setiap_minggu');
            $tanggalKhusus = null;
        }

        $request->validate($rules);

        // Cek bentrok dengan mengabaikan ID saat ini
        $conflictError = $this->detectScheduleConflict(
            $hari,
            $request->jam_mulai,
            $request->jam_selesai,
            (int) $request->id_lab,
            (int) $request->id_guru,
            (int) $request->id_kelas,
            (int) $request->id_mapel,
            (int) $id,
            $tipeJadwal,
            $frekuensi,
            $tanggalKhusus,
            $allowOverrideMapel
        );

        if ($conflictError) {
            return back()->withInput()->with('error', $conflictError);
        }

        $jadwal->update([
            'id_kelas' => $request->id_kelas,
            'id_lab' => $request->id_lab,
            'id_guru' => $request->id_guru,
            'id_mapel' => $request->id_mapel,
            'tipe_jadwal' => $tipeJadwal,
            'frekuensi' => $frekuensi,
            'tanggal_khusus' => $tanggalKhusus,
            'id_jadwal_mapel' => $request->id_jadwal_mapel,
            'hari' => $hari,
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
     * Deteksi bentrok jadwal praktik cerdas dengan mempertimbangkan siklus, tanggal, dan toleransi mapel sendiri.
     */
    public function detectScheduleConflict(
        string $hari,
        string $jamMulai,
        string $jamSelesai,
        int $idLab,
        int $idGuru,
        int $idKelas,
        int $idMapel,
        ?int $excludeId = null,
        string $tipeJadwal = 'rutin',
        string $frekuensi = 'setiap_minggu',
        ?string $tanggalKhusus = null,
        bool $allowOverrideMapel = false
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

        $existingPraktiks = $basePraktikQuery->with(['lab', 'kelas', 'guru', 'mapel'])->get();

        foreach ($existingPraktiks as $item) {
            // Cek apakah ada irisan siklus/minggu pelaksanaan
            $isOverlapCycle = false;

            if ($tipeJadwal === 'insidental' && $tanggalKhusus) {
                // Untuk jadwal insidental: overlap jika jadwal yang dibandingkan aktif di tanggal tersebut
                $isOverlapCycle = $item->isActiveOnDate($tanggalKhusus);
            } elseif ($item->isInsidental() && $item->tanggal_khusus) {
                // Jadwal pembanding adalah insidental, cek apakah aktif di siklus rutin
                $dt = \Carbon\Carbon::parse($item->tanggal_khusus);
                $isOverlapCycle = (new JadwalPraktik(['hari' => $hariNorm, 'frekuensi' => $frekuensi, 'tipe_jadwal' => 'rutin']))->isActiveOnDate($dt);
            } else {
                // Keduanya adalah jadwal rutin:
                $f1 = $frekuensi;
                $f2 = $item->frekuensi ?? 'setiap_minggu';

                if ($f1 === 'setiap_minggu' || $f2 === 'setiap_minggu') {
                    $isOverlapCycle = true;
                } elseif ($f1 === $f2) {
                    $isOverlapCycle = true;
                } else {
                    // Satu ganjil, satu genap -> TIDAK PERNAH BENTROK!
                    $isOverlapCycle = false;
                }
            }

            if (!$isOverlapCycle) {
                continue; // Lewati karena beda siklus minggu
            }

            // 1a. Bentrok Laboratorium
            if ($item->id_lab == $idLab) {
                $detailSiklus = $item->isInsidental() 
                    ? " (Praktik Tambahan tanggal " . \Carbon\Carbon::parse($item->tanggal_khusus)->format('d/m/Y') . ")" 
                    : ($item->frekuensi !== 'setiap_minggu' ? " [" . $item->label_frekuensi . "]" : "");

                return "Bentrok Laboratorium: Ruang " . ($item->lab->nama_lab ?? 'Lab') .
                    " sudah dijadwalkan untuk kelas " . ($item->kelas->nama_kelas ?? 'Lain') .
                    $detailSiklus . " pada hari " . $hariNorm . " pukul " . substr($item->jam_mulai, 0, 5) .
                    " sampai " . substr($item->jam_selesai, 0, 5) . ".";
            }

            // 1b. Bentrok Guru di jadwal praktik
            if ($item->id_guru == $idGuru) {
                return "Bentrok Jadwal Guru: Guru " . ($item->guru->nama_guru ?? 'Pengajar') .
                    " sudah memiliki jadwal praktik di " . ($item->lab->nama_lab ?? 'Lab lain') .
                    " pada hari " . $hariNorm . " pukul " . substr($item->jam_mulai, 0, 5) .
                    " sampai " . substr($item->jam_selesai, 0, 5) . ".";
            }

            // 1c. Bentrok Kelas di jadwal praktik
            if ($item->id_kelas == $idKelas) {
                return "Bentrok Jadwal Kelas: Kelas " . ($item->kelas->nama_kelas ?? 'Kelas') .
                    " sudah memiliki jadwal praktik di " . ($item->lab->nama_lab ?? 'Lab lain') .
                    " pada hari " . $hariNorm . " pukul " . substr($item->jam_mulai, 0, 5) .
                    " sampai " . substr($item->jam_selesai, 0, 5) . ".";
            }
        }

        // -------------------------------------------------------------
        // 2. Cek silang bentrok dengan jadwal_mapel kurikulum
        // -------------------------------------------------------------
        if ($allowOverrideMapel) {
            // Pengguna memberi persetujuan dispensasi/praktik blok mengambil jam KBM
            return null;
        }

        $baseMapelQuery = JadwalMapel::whereRaw('LOWER(hari) = ?', [$hariLower])
            ->where('waktu_awal', '<', substr($selesaiTime, 0, 5))
            ->where('waktu_akhir', '>', substr($mulaiTime, 0, 5));

        // 2a. Bentrok Guru dengan jadwal mengajar umum
        $guruMapelConflicts = (clone $baseMapelQuery)
            ->where('id_guru', $idGuru)
            ->with(['guru', 'mapel', 'kelas'])
            ->get();

        foreach ($guruMapelConflicts as $conf) {
            // Toleransi KBM produktif: jika ini adalah guru, kelas, dan mapel yang sama persis,
            // berarti jam mengajar tersebut memang dialokasikan sebagai praktik di Lab!
            if ($conf->id_kelas == $idKelas && $conf->id_mapel == $idMapel) {
                continue; // BUKAN BENTROK
            }

            return "Bentrok Jadwal Mengajar: Guru " . ($conf->guru->nama_guru ?? 'Pengajar') .
                " memiliki jadwal mengajar " . ($conf->mapel->nama_mapel ?? 'Mapel') .
                " di kelas " . ($conf->kelas->nama_kelas ?? '-') .
                " pada hari " . $hariNorm . " pukul " . $conf->waktu_awal .
                " sampai " . $conf->waktu_akhir . ". (Aktifkan centang 'Izinkan Mengambil Jam Pelajaran' jika ini adalah Praktik Blok).";
        }

        // 2b. Bentrok Kelas dengan jadwal pelajaran umum
        $kelasMapelConflicts = (clone $baseMapelQuery)
            ->where('id_kelas', $idKelas)
            ->with(['kelas', 'mapel', 'guru'])
            ->get();

        foreach ($kelasMapelConflicts as $conf) {
            if ($conf->id_guru == $idGuru && $conf->id_mapel == $idMapel) {
                continue; // BUKAN BENTROK
            }

            return "Bentrok Jadwal Pelajaran: Kelas " . ($conf->kelas->nama_kelas ?? 'Kelas') .
                " memiliki jadwal " . ($conf->mapel->nama_mapel ?? 'Mapel') .
                " bersama " . ($conf->guru->nama_guru ?? 'Guru') .
                " pada hari " . $hariNorm . " pukul " . $conf->waktu_awal .
                " sampai " . $conf->waktu_akhir . ". (Aktifkan centang 'Izinkan Mengambil Jam Pelajaran' jika ini adalah Praktik Blok).";
        }

        return null;
    }
}
