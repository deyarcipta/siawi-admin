<?php

namespace App\Http\Controllers\Kejuruan;

use App\Http\Controllers\Controller;
use App\Models\CapaianSiswa;
use App\Models\JadwalPraktik;
use App\Models\Kelas;
use App\Models\Kompetensi;
use App\Models\ModulPraktik;
use App\Models\NilaiPraktikSiswa;
use App\Models\PenilaianPraktik;
use App\Models\RubrikPenilaian;
use App\Models\Setting;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PenilaianPraktikController extends Controller
{
    /**
     * Tampilkan daftar sesi penilaian praktik per kelas.
     */
    public function index(Request $request)
    {
        $layout = 'layout.app';
        $setting = Setting::find(1);
        $user = Auth::user();

        $query = PenilaianPraktik::forUserJurusan($user)
            ->with(['kelas', 'modul', 'kompetensi.jurusan', 'guru'])
            ->withCount('nilaiSiswas')
            ->orderBy('tanggal_penilaian', 'desc')
            ->orderBy('id_penilaian_praktik', 'desc');

        if ($request->filled('kelas_id')) {
            $query->where('id_kelas', $request->kelas_id);
        }

        if ($request->filled('kompetensi_id')) {
            $query->where('id_kompetensi', $request->kompetensi_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $penilaians = $query->get();

        $kelas = Kelas::forUserJurusan($user)->orderBy('nama_kelas', 'asc')->get();
        $kompetensis = Kompetensi::forUserJurusan($user)->orderBy('kode_kompetensi', 'asc')->get();

        return view('kejuruan.penilaian_praktik.index', compact(
            'layout', 'setting', 'user', 'penilaians', 'kelas', 'kompetensis'
        ));
    }

    /**
     * Form buat sesi penilaian baru.
     */
    public function create(Request $request)
    {
        $layout = 'layout.app';
        $setting = Setting::find(1);
        $user = Auth::user();

        if (!$user->hasAnyRole(['admin', 'kaprog', 'guru_jurusan'])) {
            abort(403, 'Akses terbatas untuk penilaian praktik.');
        }

        $kelas = Kelas::forUserJurusan($user)->orderBy('nama_kelas', 'asc')->get();
        $kompetensis = Kompetensi::forUserJurusan($user)->with('jurusan')->orderBy('kode_kompetensi', 'asc')->get();
        $moduls = ModulPraktik::forUserJurusan($user)->terbit()->orderBy('judul', 'asc')->get();

        $selectedJadwal = null;
        if ($request->filled('jadwal_id')) {
            $selectedJadwal = JadwalPraktik::forUserJurusan($user)->find($request->jadwal_id);
        }

        $jadwals = JadwalPraktik::forUserJurusan($user)
            ->with(['lab', 'kelas', 'mapel'])
            ->orderBy('hari', 'asc')
            ->orderBy('jam_mulai', 'asc')
            ->get();

        return view('kejuruan.penilaian_praktik.create', compact(
            'layout', 'setting', 'user', 'kelas', 'kompetensis', 'moduls', 'selectedJadwal', 'jadwals'
        ));
    }

    /**
     * Simpan header sesi penilaian praktik dan buat daftar siswa kelas.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'id_modul_praktik' => 'required|exists:modul_praktik,id_modul_praktik',
            'tanggal_penilaian' => 'required|date',
            'id_jadwal_praktik' => 'nullable|exists:jadwal_praktik,id_jadwal_praktik',
            'catatan' => 'nullable|string',
        ], [
            'id_kelas.required' => 'Kelas wajib dipilih.',
            'id_modul_praktik.required' => 'Modul praktik wajib dipilih.',
            'tanggal_penilaian.required' => 'Tanggal penilaian wajib diisi.',
        ]);

        $modul = ModulPraktik::with('kompetensi')->findOrFail($request->id_modul_praktik);
        $kkm = $modul->kompetensi ? $modul->kompetensi->kkm : 75.00;

        DB::beginTransaction();
        try {
            $penilaian = PenilaianPraktik::create([
                'id_kelas' => $request->id_kelas,
                'id_modul_praktik' => $modul->id_modul_praktik,
                'id_kompetensi' => $modul->id_kompetensi,
                'id_jadwal_praktik' => $request->id_jadwal_praktik,
                'id_guru' => $user->id_guru,
                'tanggal_penilaian' => $request->tanggal_penilaian,
                'kkm_saat_penilaian' => $kkm,
                'status' => 'draft',
                'catatan' => $request->catatan,
            ]);

            // Ambil semua siswa di kelas tersebut
            $siswas = Siswa::where('id_kelas', $request->id_kelas)
                ->orderBy('nama_siswa', 'asc')
                ->get();

            foreach ($siswas as $siswa) {
                NilaiPraktikSiswa::create([
                    'id_penilaian_praktik' => $penilaian->id_penilaian_praktik,
                    'id_siswa' => $siswa->id_siswa,
                    'nilai_per_indikator' => [],
                    'nilai_awal' => 0.00,
                    'nilai_remedial' => null,
                    'nilai_akhir' => 0.00,
                    'status_kompetensi' => 'belum_kompeten',
                    'is_remedial' => false,
                    'catatan_guru' => null,
                ]);
            }

            DB::commit();

            return redirect('/admin/kejuruan/penilaian-praktik/' . $penilaian->id_penilaian_praktik . '/input')
                ->with('success', 'Sesi penilaian kelas berhasil dibuat. Silakan masukkan nilai siswa.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal membuat sesi penilaian: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan formulir matriks penilaian satu kelas sekaligus.
     */
    public function inputNilai(string $id)
    {
        $layout = 'layout.app';
        $setting = Setting::find(1);
        $user = Auth::user();

        $penilaian = PenilaianPraktik::forUserJurusan($user)
            ->with([
                'kelas',
                'modul.rubriks',
                'kompetensi.jurusan',
                'guru',
                'nilaiSiswas.siswa'
            ])
            ->findOrFail($id);

        $rubriks = $penilaian->modul->rubriks;
        // Jika modul belum memiliki rubrik khusus, buat rubrik default 1 indikator
        if ($rubriks->isEmpty()) {
            $rubriks = collect([
                (object) [
                    'id_rubrik' => 0,
                    'nama_indikator' => 'Keterampilan & Hasil Praktik',
                    'bobot' => 100,
                ]
            ]);
        }

        $nilaiSiswas = $penilaian->nilaiSiswas->sortBy(function ($item) {
            return $item->siswa->nama_siswa ?? '';
        });

        $existingSiswaIds = $penilaian->nilaiSiswas->pluck('id_siswa')->toArray();

        // Siswa di kelas yang belum terdaftar di sesi penilaian ini
        $missingSiswas = Siswa::where('id_kelas', $penilaian->id_kelas)
            ->whereNotIn('id_siswa', $existingSiswaIds)
            ->orderBy('nama_siswa', 'asc')
            ->get();

        // Daftar siswa tersedia untuk opsi tambah susulan manual
        $availableSiswas = Siswa::whereNotIn('id_siswa', $existingSiswaIds)
            ->with('kelas')
            ->orderBy('nama_siswa', 'asc')
            ->get();

        return view('kejuruan.penilaian_praktik.input', compact(
            'layout', 'setting', 'user', 'penilaian', 'rubriks', 'nilaiSiswas', 'missingSiswas', 'availableSiswas'
        ));
    }

    /**
     * Simpan nilai matriks kelas sekaligus (bisa draft atau final).
     */
    public function saveScores(Request $request, string $id)
    {
        $user = Auth::user();
        $penilaian = PenilaianPraktik::forUserJurusan($user)->findOrFail($id);

        $scores = $request->input('scores', []);
        $kkm = floatval($penilaian->kkm_saat_penilaian);

        DB::beginTransaction();
        try {
            foreach ($scores as $idSiswa => $data) {
                $nilaiRecord = NilaiPraktikSiswa::where('id_penilaian_praktik', $penilaian->id_penilaian_praktik)
                    ->where('id_siswa', $idSiswa)
                    ->first();

                if (!$nilaiRecord) {
                    continue;
                }

                $indikatorScores = isset($data['indikator']) && is_array($data['indikator']) ? $data['indikator'] : [];
                $nilaiAwal = isset($data['nilai_awal']) ? floatval($data['nilai_awal']) : 0.00;
                $nilaiRemedial = isset($data['nilai_remedial']) && $data['nilai_remedial'] !== '' ? floatval($data['nilai_remedial']) : null;
                $catatanGuru = isset($data['catatan']) ? trim($data['catatan']) : null;

                // Tentukan nilai akhir
                $isRemedial = ($nilaiRemedial !== null);
                $nilaiAkhir = $isRemedial ? $nilaiRemedial : $nilaiAwal;

                // Tentukan status kompetensi otomatis dari KKM
                $statusKompetensi = ($nilaiAkhir >= $kkm) ? 'kompeten' : 'belum_kompeten';

                $nilaiRecord->update([
                    'nilai_per_indikator' => $indikatorScores,
                    'nilai_awal' => $nilaiAwal,
                    'nilai_remedial' => $nilaiRemedial,
                    'nilai_akhir' => $nilaiAkhir,
                    'status_kompetensi' => $statusKompetensi,
                    'is_remedial' => $isRemedial,
                    'catatan_guru' => $catatanGuru,
                ]);
            }

            // Jika tombol Finalkan ditekan
            if ($request->input('action') === 'finalkan') {
                $penilaian->update(['status' => 'final']);

                // Sinkronkan otomatis ke tabel inti capaian_siswa
                $this->syncToCapaianSiswa($penilaian);
            }

            DB::commit();

            if ($request->input('action') === 'finalkan') {
                return redirect('/admin/kejuruan/penilaian-praktik/' . $penilaian->id_penilaian_praktik)
                    ->with('success', 'Nilai praktik kelas berhasil difinalkan dan tercatat pada Capaian Siswa.');
            }

            return back()->with('success', 'Nilai praktik tersimpan sebagai draft.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan nilai: ' . $e->getMessage());
        }
    }

    /**
     * Finalisasi nilai praktik dari tombol di halaman detail.
     */
    public function finalkan(string $id)
    {
        $user = Auth::user();
        $penilaian = PenilaianPraktik::forUserJurusan($user)->findOrFail($id);

        DB::beginTransaction();
        try {
            $penilaian->update(['status' => 'final']);
            $this->syncToCapaianSiswa($penilaian);
            DB::commit();

            return redirect('/admin/kejuruan/penilaian-praktik/' . $penilaian->id_penilaian_praktik)
                ->with('success', 'Nilai berhasil difinalkan dan otomatis tercatat pada portofolio Capaian Siswa.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memfinalkan nilai: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan hasil nilai detail sesi penilaian.
     */
    public function show(string $id)
    {
        $layout = 'layout.app';
        $setting = Setting::find(1);
        $user = Auth::user();

        $penilaian = PenilaianPraktik::forUserJurusan($user)
            ->with([
                'kelas',
                'modul.rubriks',
                'kompetensi.jurusan',
                'guru',
                'nilaiSiswas.siswa'
            ])
            ->findOrFail($id);

        $rubriks = $penilaian->modul->rubriks;
        $totalSiswa = $penilaian->nilaiSiswas->count();
        $kompetenCount = $penilaian->nilaiSiswas->where('status_kompetensi', 'kompeten')->count();
        $belumKompetenCount = $totalSiswa - $kompetenCount;
        $rataRata = $totalSiswa > 0 ? round($penilaian->nilaiSiswas->avg('nilai_akhir'), 2) : 0;

        $missingCount = Siswa::where('id_kelas', $penilaian->id_kelas)
            ->whereNotIn('id_siswa', $penilaian->nilaiSiswas->pluck('id_siswa'))
            ->count();

        return view('kejuruan.penilaian_praktik.show', compact(
            'layout', 'setting', 'user', 'penilaian', 'rubriks',
            'totalSiswa', 'kompetenCount', 'belumKompetenCount', 'rataRata', 'missingCount'
        ));
    }

    /**
     * Sinkronkan seluruh siswa kelas yang belum terdaftar ke sesi penilaian ini.
     */
    public function syncSiswa(string $id)
    {
        $user = Auth::user();
        $penilaian = PenilaianPraktik::forUserJurusan($user)->findOrFail($id);

        $existingSiswaIds = NilaiPraktikSiswa::where('id_penilaian_praktik', $penilaian->id_penilaian_praktik)
            ->pluck('id_siswa')
            ->toArray();

        $missingSiswas = Siswa::where('id_kelas', $penilaian->id_kelas)
            ->whereNotIn('id_siswa', $existingSiswaIds)
            ->orderBy('nama_siswa', 'asc')
            ->get();

        if ($missingSiswas->isEmpty()) {
            return back()->with('info', 'Semua siswa di kelas ini sudah terdaftar pada sesi penilaian.');
        }

        DB::beginTransaction();
        try {
            foreach ($missingSiswas as $siswa) {
                NilaiPraktikSiswa::create([
                    'id_penilaian_praktik' => $penilaian->id_penilaian_praktik,
                    'id_siswa' => $siswa->id_siswa,
                    'nilai_per_indikator' => [],
                    'nilai_awal' => 0.00,
                    'nilai_remedial' => null,
                    'nilai_akhir' => 0.00,
                    'status_kompetensi' => 'belum_kompeten',
                    'is_remedial' => false,
                    'catatan_guru' => null,
                ]);
            }
            DB::commit();

            return back()->with('success', $missingSiswas->count() . ' siswa kelas berhasil disinkronkan ke dalam sesi penilaian.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyinkronkan siswa: ' . $e->getMessage());
        }
    }

    /**
     * Tambahkan siswa secara manual (misal siswa susulan atau pindahan).
     */
    public function addSiswaManual(Request $request, string $id)
    {
        $user = Auth::user();
        $penilaian = PenilaianPraktik::forUserJurusan($user)->findOrFail($id);

        $request->validate([
            'id_siswa' => 'required|exists:siswa,id_siswa',
        ], [
            'id_siswa.required' => 'Pilih siswa yang akan ditambahkan.',
        ]);

        $alreadyExists = NilaiPraktikSiswa::where('id_penilaian_praktik', $penilaian->id_penilaian_praktik)
            ->where('id_siswa', $request->id_siswa)
            ->exists();

        if ($alreadyExists) {
            return back()->with('warning', 'Siswa tersebut sudah terdaftar pada sesi penilaian ini.');
        }

        NilaiPraktikSiswa::create([
            'id_penilaian_praktik' => $penilaian->id_penilaian_praktik,
            'id_siswa' => $request->id_siswa,
            'nilai_per_indikator' => [],
            'nilai_awal' => 0.00,
            'nilai_remedial' => null,
            'nilai_akhir' => 0.00,
            'status_kompetensi' => 'belum_kompeten',
            'is_remedial' => false,
            'catatan_guru' => null,
        ]);

        return back()->with('success', 'Siswa susulan berhasil ditambahkan ke sesi penilaian.');
    }

    /**
     * Hapus satu siswa dari sesi penilaian ini.
     */
    public function deleteSiswa(string $id, string $idSiswa)
    {
        $user = Auth::user();
        $penilaian = PenilaianPraktik::forUserJurusan($user)->findOrFail($id);

        $record = NilaiPraktikSiswa::where('id_penilaian_praktik', $penilaian->id_penilaian_praktik)
            ->where('id_siswa', $idSiswa)
            ->firstOrFail();

        DB::beginTransaction();
        try {
            // Hapus nilai capaian jika sesi sudah pernah difinalkan
            CapaianSiswa::where('jenis_capaian', 'praktik')
                ->where('id_referensi', $penilaian->id_penilaian_praktik)
                ->where('id_siswa', $idSiswa)
                ->delete();

            $record->delete();
            DB::commit();

            return back()->with('success', 'Siswa berhasil dihapus dari sesi penilaian.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus siswa: ' . $e->getMessage());
        }
    }

    /**
     * Hapus sesi penilaian.
     */
    public function destroy(string $id)
    {
        $user = Auth::user();
        $penilaian = PenilaianPraktik::forUserJurusan($user)->findOrFail($id);

        if (!$user->hasRole('admin') && !$user->hasRole('kaprog') && $penilaian->id_guru != $user->id_guru) {
            abort(403, 'Akses ditolak.');
        }

        DB::beginTransaction();
        try {
            // Hapus juga capaian_siswa yang berasal dari penilaian ini
            CapaianSiswa::where('jenis_capaian', 'praktik')
                ->where('id_referensi', $penilaian->id_penilaian_praktik)
                ->delete();

            $penilaian->delete();
            DB::commit();

            return redirect('/admin/kejuruan/penilaian-praktik')->with('success', 'Sesi penilaian praktik berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus penilaian: ' . $e->getMessage());
        }
    }

    /**
     * Sinkronisasi nilai final ke tabel inti capaian_siswa.
     */
    protected function syncToCapaianSiswa(PenilaianPraktik $penilaian)
    {
        $penilaian->load('nilaiSiswas');

        foreach ($penilaian->nilaiSiswas as $nilaiSiswa) {
            CapaianSiswa::updateOrCreate(
                [
                    'id_siswa' => $nilaiSiswa->id_siswa,
                    'id_kompetensi' => $penilaian->id_kompetensi,
                    'jenis_capaian' => 'praktik',
                    'id_referensi' => $penilaian->id_penilaian_praktik,
                ],
                [
                    'nilai' => $nilaiSiswa->nilai_akhir,
                    'kkm' => $penilaian->kkm_saat_penilaian,
                    'status' => $nilaiSiswa->status_kompetensi,
                    'tanggal' => $penilaian->tanggal_penilaian,
                    'id_penilai' => $penilaian->id_guru,
                    'file_bukti' => null,
                    'catatan' => $nilaiSiswa->catatan_guru ?: ('Penilaian Praktik Modul: ' . ($penilaian->modul->judul ?? '-')),
                ]
            );
        }
    }
}
