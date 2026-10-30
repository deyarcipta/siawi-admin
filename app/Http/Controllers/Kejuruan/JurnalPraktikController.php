<?php

namespace App\Http\Controllers\Kejuruan;

use App\Http\Controllers\Controller;
use App\Models\JadwalPraktik;
use App\Models\JurnalPraktik;
use App\Models\JurnalPraktikPresensi;
use App\Models\Siswa;
use App\Models\ModulPraktik;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class JurnalPraktikController extends Controller
{
/**
     * Ambil daftar siswa berdasarkan jadwal praktik (untuk absensi praktik)
     */
    public function getSiswaByJadwal(string $id)
    {
        $jadwal = JadwalPraktik::with('kelas')->find($id);
        if (!$jadwal) {
            return response()->json(['success' => false, 'message' => 'Jadwal tidak ditemukan', 'siswas' => []], 404);
        }

        $siswas = Siswa::where('id_kelas', $jadwal->id_kelas)
            ->orderBy('nama_siswa', 'asc')
            ->get(['id_siswa', 'nisn', 'nis', 'nama_siswa']);

        return response()->json([
            'success' => true,
            'kelas' => $jadwal->kelas->nama_kelas ?? '',
            'siswas' => $siswas
        ]);
    }

    /**
     * Tampilkan riwayat jurnal praktik dan daftar jadwal hari ini yang siap diisi.
     */
    public function index(Request $request)
    {
        $layout = 'layout.app';
        $setting = Setting::find(1);
        $user = Auth::user();

        // Nama hari hari ini dalam Bahasa Indonesia
        $hariMap = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        ];
        $todayEnglish = Carbon::now()->format('l');
        $todayIndo = $hariMap[$todayEnglish] ?? 'Senin';
        $todayDate = Carbon::now()->toDateString();

        // 1. Ambil jadwal praktik hari ini khusus untuk user yang login (memperhitungkan siklus ganjil/genap dan dadakan)
        $jadwalHariIniQuery = JadwalPraktik::with(['kelas', 'lab', 'mapel', 'guru']);
        if (!$user->hasRole('admin')) {
            $jadwalHariIniQuery->where('id_guru', $user->id_guru);
        }
        $jadwalHariIni = $jadwalHariIniQuery
            ->where(function($q) use ($todayIndo, $todayDate) {
                $q->where(function($sub) use ($todayIndo) {
                    $sub->where('tipe_jadwal', 'rutin')->where('hari', $todayIndo);
                })->orWhere(function($sub) use ($todayDate) {
                    $sub->where('tipe_jadwal', 'insidental')->where('tanggal_khusus', $todayDate);
                });
            })
            ->orderBy('jam_mulai', 'asc')
            ->get()
            ->filter(function($j) use ($todayDate) {
                return $j->isActiveOnDate($todayDate);
            })
            ->values();

        // Cek jurnal yang sudah diisi hari ini
        $jurnalHariIniJadwalIds = JurnalPraktik::where('tanggal', $todayDate)
            ->pluck('id_jadwal_praktik')
            ->toArray();

        // 2. Ambil riwayat jurnal praktik sesuai hak akses
        $query = JurnalPraktik::forUserJurusan($user)
            ->with(['jadwalPraktik.kelas', 'jadwalPraktik.lab', 'jadwalPraktik.guru', 'jadwalPraktik.mapel', 'approver']);

        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('tanggal', [$request->tanggal_awal, $request->tanggal_akhir]);
        }

        if ($request->filled('status_approval')) {
            $query->where('status_approval', $request->status_approval);
        }

        $jurnals = $query->latest('tanggal')->latest('id_jurnal_praktik')->get();

        return view('kejuruan.jurnal_praktik.index', compact(
            'layout', 'setting', 'user', 'jurnals', 'jadwalHariIni',
            'jurnalHariIniJadwalIds', 'todayIndo', 'todayDate'
        ));
    }

    /**
     * Form pengisian jurnal praktik baru berdasarkan jadwal.
     */
    public function create(Request $request)
    {
        $layout = 'layout.app';
        $setting = Setting::find(1);
        $user = Auth::user();

        $idJadwal = $request->query('id_jadwal_praktik');
        $jadwal = null;
        $siswaList = collect();

        if ($idJadwal) {
            $jadwal = JadwalPraktik::with(['kelas', 'lab', 'mapel', 'guru'])->findOrFail($idJadwal);
            $siswaList = Siswa::where('id_kelas', $jadwal->id_kelas)->orderBy('nama_siswa', 'asc')->get();
        }

        $tanggal = $request->query('tanggal', Carbon::now()->toDateString());

        // Ambil daftar jadwal aktif milik guru
        $jadwalQuery = JadwalPraktik::with(['kelas', 'lab', 'mapel', 'guru']);
        if (!$user->hasRole('admin')) {
            $jadwalQuery->where('id_guru', $user->id_guru);
        }
        $jadwalList = $jadwalQuery->orderBy('hari', 'asc')->orderBy('jam_mulai', 'asc')->get();

        // Prediksi jumlah hadir siswa
        $defaultHadir = 0;
        if ($jadwal) {
            $defaultHadir = $this->calculateStudentAttendance($jadwal->id_kelas, $tanggal);
        }

        // Ambil daftar modul praktik terbit yang dapat dipilih
        $modulList = ModulPraktik::forUserJurusan($user)->terbit()->with('kompetensi')->orderBy('judul', 'asc')->get();

        return view('kejuruan.jurnal_praktik.create', compact(
            'layout', 'setting', 'user', 'jadwal', 'jadwalList', 'tanggal', 'defaultHadir', 'modulList', 'siswaList'
        ));
    }

    /**
     * Simpan jurnal praktik pertemuan.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'id_jadwal_praktik' => 'required|exists:jadwal_praktik,id_jadwal_praktik',
            'modul_praktik_id' => 'nullable|exists:modul_praktik,id_modul_praktik',
            'tanggal' => 'required|date',
            'materi' => 'required|string',
            'jumlah_hadir' => 'required|integer|min:0',
            'kendala' => 'nullable|string',
            'alat_dipakai' => 'nullable|string',
            'catatan' => 'nullable|string',
            'foto_praktik' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:512',
        ], [
            'id_jadwal_praktik.required' => 'Jadwal praktik wajib dipilih.',
            'tanggal.required' => 'Tanggal pelaksanaan wajib diisi.',
            'materi.required' => 'Materi/jobsheet praktik wajib diisi.',
            'jumlah_hadir.required' => 'Jumlah siswa hadir wajib diisi.',
            'foto_praktik.image' => 'File dokumentasi harus berupa gambar.',
            'foto_praktik.max' => 'Ukuran foto dokumentasi maksimal 512 KB.',
        ]);

        $jadwal = JadwalPraktik::findOrFail($request->id_jadwal_praktik);

        // Pastikan hanya pengajar yang bersangkutan atau Admin yang dapat mengisi
        if (!$user->hasRole('admin') && $jadwal->id_guru != $user->id_guru) {
            abort(403, 'Anda hanya dapat mengisi jurnal untuk jadwal mengajar Anda sendiri.');
        }

        // Cek apakah jurnal untuk jadwal dan tanggal ini sudah diisi
        $existing = JurnalPraktik::where('id_jadwal_praktik', $jadwal->id_jadwal_praktik)
            ->where('tanggal', $request->tanggal)
            ->first();

        if ($existing) {
            return back()->withInput()->with('error', 'Jurnal praktik untuk jadwal ini pada tanggal ' . $request->tanggal . ' sudah pernah diisi.');
        }

        $fotoPath = null;
        if ($request->hasFile('foto_praktik')) {
            $fotoPath = $request->file('foto_praktik')->store('jurnal_praktik', 'public');
        }

        $jurnal = JurnalPraktik::create([
            'id_jadwal_praktik' => $jadwal->id_jadwal_praktik,
            'tanggal' => $request->tanggal,
            'modul_praktik_id' => $request->modul_praktik_id,
            'materi' => trim($request->materi),
            'jumlah_hadir' => $request->jumlah_hadir,
            'kendala' => $request->kendala ? trim($request->kendala) : null,
            'alat_dipakai' => $request->alat_dipakai ? trim($request->alat_dipakai) : null,
            'catatan' => $request->catatan ? trim($request->catatan) : null,
            'foto_praktik' => $fotoPath,
            'status_approval' => 'menunggu',
        ]);

        // Simpan presensi per siswa (hanya siswa yang dicentang / ikut praktik)
        if ($request->has('presensi') && is_array($request->presensi)) {
            $presensiData = [];
            foreach ($request->presensi as $idSiswa => $p) {
                if (empty($p['terpilih'])) {
                    continue;
                }
                $status = in_array($p['status'] ?? '', ['hadir', 'sakit', 'izin', 'alpa']) ? $p['status'] : 'hadir';
                $presensiData[] = [
                    'id_jurnal_praktik' => $jurnal->id_jurnal_praktik,
                    'id_siswa' => $idSiswa,
                    'status' => $status,
                    'catatan' => !empty($p['catatan']) ? trim($p['catatan']) : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            if (!empty($presensiData)) {
                JurnalPraktikPresensi::insert($presensiData);
            }
        }

        return redirect('/admin/kejuruan/jurnal-praktik')->with('success', 'Jurnal praktik berhasil disimpan dan siap ditinjau oleh Kaprog.');
    }

    /**
     * Tampilkan detail jurnal praktik.
     */
    public function show(string $id)
    {
        $layout = 'layout.app';
        $setting = Setting::find(1);
        $user = Auth::user();

        $jurnal = JurnalPraktik::forUserJurusan($user)
            ->with(['jadwalPraktik.kelas', 'jadwalPraktik.lab', 'jadwalPraktik.guru', 'jadwalPraktik.mapel', 'approver', 'modulPraktik', 'presensis.siswa'])
            ->findOrFail($id);

        return view('kejuruan.jurnal_praktik.show', compact('layout', 'setting', 'user', 'jurnal'));
    }

    /**
     * Form edit jurnal praktik.
     */
    public function edit(string $id)
    {
        $layout = 'layout.app';
        $setting = Setting::find(1);
        $user = Auth::user();

        $jurnal = JurnalPraktik::forUserJurusan($user)
            ->with(['jadwalPraktik.kelas', 'jadwalPraktik.lab', 'jadwalPraktik.guru', 'jadwalPraktik.mapel', 'modulPraktik', 'presensis'])
            ->findOrFail($id);

        // Jika sudah disetujui, guru biasa tidak dapat mengubah tanpa izin Kaprog/Admin
        if (!$user->hasRole('admin') && $jurnal->status_approval === 'disetujui') {
            return redirect('/admin/kejuruan/jurnal-praktik/' . $id)->with('error', 'Jurnal yang telah disetujui oleh Kaprog tidak dapat diubah kembali.');
        }

        $modulList = ModulPraktik::forUserJurusan($user)->terbit()->with('kompetensi')->orderBy('judul', 'asc')->get();

        $siswaList = collect();
        if ($jurnal->jadwalPraktik && $jurnal->jadwalPraktik->id_kelas) {
            $siswaList = Siswa::where('id_kelas', $jurnal->jadwalPraktik->id_kelas)->orderBy('nama_siswa', 'asc')->get();
        }
        $existingPresensi = $jurnal->presensis->keyBy('id_siswa');

        return view('kejuruan.jurnal_praktik.edit', compact('layout', 'setting', 'user', 'jurnal', 'modulList', 'siswaList', 'existingPresensi'));
    }

    /**
     * Update jurnal praktik.
     */
    public function update(Request $request, string $id)
    {
        $user = Auth::user();
        $jurnal = JurnalPraktik::forUserJurusan($user)->findOrFail($id);

        $request->validate([
            'modul_praktik_id' => 'nullable|exists:modul_praktik,id_modul_praktik',
            'materi' => 'required|string',
            'jumlah_hadir' => 'required|integer|min:0',
            'kendala' => 'nullable|string',
            'alat_dipakai' => 'nullable|string',
            'catatan' => 'nullable|string',
            'foto_praktik' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:512',
        ], [
            'materi.required' => 'Materi/jobsheet praktik wajib diisi.',
            'jumlah_hadir.required' => 'Jumlah siswa hadir wajib diisi.',
            'foto_praktik.image' => 'File dokumentasi harus berupa gambar.',
            'foto_praktik.max' => 'Ukuran foto dokumentasi maksimal 512 KB.',
        ]);

        $updateData = [
            'modul_praktik_id' => $request->modul_praktik_id,
            'materi' => trim($request->materi),
            'jumlah_hadir' => $request->jumlah_hadir,
            'kendala' => $request->kendala ? trim($request->kendala) : null,
            'alat_dipakai' => $request->alat_dipakai ? trim($request->alat_dipakai) : null,
            'catatan' => $request->catatan ? trim($request->catatan) : null,
        ];

        if ($request->hasFile('foto_praktik')) {
            if ($jurnal->foto_praktik && Storage::disk('public')->exists($jurnal->foto_praktik)) {
                Storage::disk('public')->delete($jurnal->foto_praktik);
            }
            $updateData['foto_praktik'] = $request->file('foto_praktik')->store('jurnal_praktik', 'public');
        }

        // Jika sebelumnya ditolak, setelah diedit kembalikan status ke menunggu
        if ($jurnal->status_approval === 'ditolak') {
            $updateData['status_approval'] = 'menunggu';
            $updateData['catatan_kaprog'] = null;
        }

        $jurnal->update($updateData);

        // Sinkronisasi data presensi siswa jika dikirim
        if ($request->has('presensi') && is_array($request->presensi)) {
            JurnalPraktikPresensi::where('id_jurnal_praktik', $jurnal->id_jurnal_praktik)->delete();
            $presensiData = [];
            foreach ($request->presensi as $idSiswa => $p) {
                if (empty($p['terpilih'])) {
                    continue;
                }
                $status = in_array($p['status'] ?? '', ['hadir', 'sakit', 'izin', 'alpa']) ? $p['status'] : 'hadir';
                $presensiData[] = [
                    'id_jurnal_praktik' => $jurnal->id_jurnal_praktik,
                    'id_siswa' => $idSiswa,
                    'status' => $status,
                    'catatan' => !empty($p['catatan']) ? trim($p['catatan']) : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            if (!empty($presensiData)) {
                JurnalPraktikPresensi::insert($presensiData);
            }
        }

        $redirectUrl = '/admin/kejuruan/jurnal-praktik/' . $id;
        if ($request->input('from') === 'laporan-praktik') {
            $redirectUrl .= '?from=laporan-praktik';
        }
        return redirect($redirectUrl)->with('success', 'Jurnal praktik berhasil diperbarui.');
    }

    /**
     * Hapus jurnal praktik.
     */
    public function destroy(string $id)
    {
        $user = Auth::user();
        $jurnal = JurnalPraktik::forUserJurusan($user)->findOrFail($id);

        if (!$user->hasAnyRole(['admin', 'kaprog']) && $jurnal->jadwalPraktik->id_guru != $user->id_guru) {
            abort(403, 'Akses terbatas.');
        }

        if ($jurnal->foto_praktik && Storage::disk('public')->exists($jurnal->foto_praktik)) {
            Storage::disk('public')->delete($jurnal->foto_praktik);
        }

        $jurnal->delete();

        return redirect('/admin/kejuruan/jurnal-praktik')->with('success', 'Jurnal praktik berhasil dihapus.');
    }

    /**
     * Persetujuan (Approval / Verifikasi) Jurnal Praktik oleh Kaprog atau Admin.
     */
    public function approve(Request $request, string $id)
    {
        $user = Auth::user();

        if (!$user->hasAnyRole(['admin', 'kaprog'])) {
            abort(403, 'Hanya Kaprog dan Admin yang berhak memverifikasi jurnal praktik.');
        }

        $jurnal = JurnalPraktik::forUserJurusan($user)->findOrFail($id);

        $request->validate([
            'status' => 'required|in:disetujui,ditolak',
            'catatan_kaprog' => 'nullable|string|max:500',
        ]);

        $jurnal->update([
            'status_approval' => $request->status,
            'approved_by' => $user->id_guru,
            'approved_at' => now(),
            'catatan_kaprog' => $request->catatan_kaprog ? trim($request->catatan_kaprog) : null,
        ]);

        $statusText = $request->status === 'disetujui' ? 'disetujui' : 'ditolak dengan catatan revisi';
        return back()->with('success', "Jurnal praktik berhasil diverifikasi dan {$statusText}.");
    }

    /**
     * Hitung perkiraan kehadiran siswa dari data absensi harian kelas yang sudah ada.
     */
    protected function calculateStudentAttendance(int $idKelas, string $tanggal): int
    {
        // 1. Cek di tabel absensi harian jika sudah diinput wali kelas / piket
        $hadirCount = DB::table('absensi')
            ->where('id_kelas', $idKelas)
            ->where('tanggal', $tanggal)
            ->whereIn(DB::raw('LOWER(kehadiran)'), ['hadir', 'h', 'masuk'])
            ->count();

        if ($hadirCount > 0) {
            return $hadirCount;
        }

        // 2. Jika belum ada absensi khusus tanggal tersebut, defaultkan ke jumlah total siswa di kelas
        $totalSiswa = DB::table('siswa')
            ->where('id_kelas', $idKelas)
            ->count();

        return $totalSiswa > 0 ? $totalSiswa : 36;
    }
}


