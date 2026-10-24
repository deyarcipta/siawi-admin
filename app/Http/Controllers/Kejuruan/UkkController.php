<?php

namespace App\Http\Controllers\Kejuruan;

use App\Http\Controllers\Controller;
use App\Models\CapaianSiswa;
use App\Models\Guru;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\Kompetensi;
use App\Models\Lab;
use App\Models\Setting;
use App\Models\Siswa;
use App\Models\UkkKegiatan;
use App\Models\UkkNilaiSiswa;
use App\Models\UkkPenguji;
use App\Models\UkkPeserta;
use App\Models\UkkUnitKompetensi;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UkkController extends Controller
{
    /**
     * Tampilkan daftar kegiatan Uji Kompetensi Keahlian (UKK).
     */
    public function index(Request $request)
    {
        $layout = 'layout.app';
        $setting = Setting::find(1);
        $user = Auth::user();

        $query = UkkKegiatan::forUserJurusan($user)
            ->with(['jurusan', 'lab', 'pembuat', 'pesertas', 'units', 'pengujis'])
            ->latest('id_ukk_kegiatan');

        if ($request->filled('id_jurusan')) {
            $query->where('id_jurusan', $request->id_jurusan);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tahun_ajaran')) {
            $query->where('tahun_ajaran', $request->tahun_ajaran);
        }

        $kegiatans = $query->get();

        // Dropdown filter
        $accessibleJurusanIds = UkkKegiatan::getAccessibleJurusanIds($user);
        $jurusans = Jurusan::whereIn('id_jurusan', $accessibleJurusanIds)->get();

        // Statistik
        $allKegiatanIds = UkkKegiatan::forUserJurusan($user)->pluck('id_ukk_kegiatan');
        $totalKegiatan = $allKegiatanIds->count();
        $totalPeserta = UkkPeserta::whereIn('id_ukk_kegiatan', $allKegiatanIds)->count();
        $totalKompeten = UkkPeserta::whereIn('id_ukk_kegiatan', $allKegiatanIds)
            ->where('status_kelulusan', 'kompeten')
            ->count();
        $persenLulus = $totalPeserta > 0 ? round(($totalKompeten / $totalPeserta) * 100) : 0;
        $totalPenguji = UkkPenguji::whereIn('id_ukk_kegiatan', $allKegiatanIds)->count();

        return view('kejuruan.uji_kompetensi.index', compact(
            'layout', 'setting', 'user', 'kegiatans', 'jurusans',
            'totalKegiatan', 'totalPeserta', 'totalKompeten', 'persenLulus', 'totalPenguji'
        ));
    }

    /**
     * Form buat kegiatan UKK baru.
     */
    public function create()
    {
        $layout = 'layout.app';
        $setting = Setting::find(1);
        $user = Auth::user();

        $accessibleJurusanIds = UkkKegiatan::getAccessibleJurusanIds($user);
        $jurusans = Jurusan::whereIn('id_jurusan', $accessibleJurusanIds)->get();
        $labs = Lab::forUserJurusan($user)->orderBy('nama_lab', 'asc')->get();
        $gurus = Guru::orderBy('nama_guru', 'asc')->get();
        $kompetensis = Kompetensi::forUserJurusan($user)->orderBy('kode_kompetensi', 'asc')->get();
        $kelas = Kelas::whereHas('jurusan', function ($q) use ($accessibleJurusanIds) {
            $q->whereIn('id_jurusan', $accessibleJurusanIds);
        })->orderBy('nama_kelas', 'asc')->get();

        return view('kejuruan.uji_kompetensi.create', compact(
            'layout', 'setting', 'user', 'jurusans', 'labs', 'gurus', 'kompetensis', 'kelas'
        ));
    }

    /**
     * Simpan kegiatan UKK baru beserta unit uji, penguji, dan peserta.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'id_jurusan' => 'required|exists:jurusan,id_jurusan',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'unit_kompetensi' => 'required|array|min:1',
            'unit_kompetensi.*' => 'exists:kompetensi,id_kompetensi',
        ]);

        $user = Auth::user();

        DB::beginTransaction();
        try {
            $kegiatan = UkkKegiatan::create([
                'nama_kegiatan' => $request->nama_kegiatan,
                'id_jurusan' => $request->id_jurusan,
                'id_lab' => $request->id_lab,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'tahun_ajaran' => $request->tahun_ajaran ?: '2026/2027',
                'status' => $request->status ?: 'draft',
                'nomor_sk' => $request->nomor_sk,
                'deskripsi' => $request->deskripsi,
                'created_by' => $user->id_guru,
            ]);

            // 1. Simpan Unit Kompetensi yang diuji
            foreach ($request->unit_kompetensi as $kompId) {
                $komp = Kompetensi::find($kompId);
                UkkUnitKompetensi::create([
                    'id_ukk_kegiatan' => $kegiatan->id_ukk_kegiatan,
                    'id_kompetensi' => $kompId,
                    'bobot' => 100.00,
                    'kkm' => $komp ? $komp->kkm : 75.00,
                ]);
            }

            // 2. Simpan Penguji Internal
            if ($request->has('penguji_internal') && is_array($request->penguji_internal)) {
                foreach ($request->penguji_internal as $idGuru) {
                    if (!empty($idGuru)) {
                        UkkPenguji::create([
                            'id_ukk_kegiatan' => $kegiatan->id_ukk_kegiatan,
                            'jenis' => 'internal',
                            'id_guru' => $idGuru,
                            'jabatan_penguji' => 'Penguji Internal',
                        ]);
                    }
                }
            }

            // 3. Simpan Penguji Eksternal (DU-DI / LSP)
            if ($request->has('penguji_eksternal_nama') && is_array($request->penguji_eksternal_nama)) {
                foreach ($request->penguji_eksternal_nama as $index => $namaExt) {
                    if (!empty(trim($namaExt))) {
                        $instansi = $request->penguji_eksternal_instansi[$index] ?? null;
                        UkkPenguji::create([
                            'id_ukk_kegiatan' => $kegiatan->id_ukk_kegiatan,
                            'jenis' => 'eksternal',
                            'nama_penguji' => trim($namaExt),
                            'instansi' => trim($instansi),
                            'jabatan_penguji' => 'Penguji Eksternal (DU-DI)',
                        ]);
                    }
                }
            }

            // 4. Daftarkan Peserta Siswa (dari kelas yang dipilih)
            if ($request->has('id_kelas') && is_array($request->id_kelas)) {
                $siswaList = Siswa::whereIn('id_kelas', $request->id_kelas)->get();
                $noUrut = 1;
                foreach ($siswaList as $s) {
                    UkkPeserta::firstOrCreate([
                        'id_ukk_kegiatan' => $kegiatan->id_ukk_kegiatan,
                        'id_siswa' => $s->id_siswa,
                    ], [
                        'nomor_peserta' => sprintf('UKK-%03d', $noUrut++),
                        'status_kehadiran' => 'hadir',
                        'status_kelulusan' => 'belum_dinilai',
                    ]);
                }
            }

            DB::commit();

            return redirect('/admin/kejuruan/uji-kompetensi/' . $kegiatan->id_ukk_kegiatan)
                ->with('success', 'Kegiatan UKK berhasil dibuat beserta unit kompetensi, penguji, dan peserta.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal membuat kegiatan UKK: ' . $e->getMessage());
        }
    }

    /**
     * Detail kegiatan UKK, daftar peserta, penguji, unit kompetensi, dan hasil penilaian.
     */
    public function show($id)
    {
        $layout = 'layout.app';
        $setting = Setting::find(1);
        $user = Auth::user();

        $kegiatan = UkkKegiatan::with([
            'jurusan', 'lab', 'pembuat',
            'units.kompetensi',
            'pengujis.guru',
            'pesertas.siswa.kelas',
            'pesertas.nilais.unit.kompetensi',
            'pesertas.nilais.penguji.guru'
        ])->findOrFail($id);

        $totalPeserta = $kegiatan->pesertas->count();
        $sudahDinilai = $kegiatan->pesertas->where('status_kelulusan', '!=', 'belum_dinilai')->count();
        $totalKompeten = $kegiatan->pesertas->where('status_kelulusan', 'kompeten')->count();
        $totalBelum = $kegiatan->pesertas->where('status_kelulusan', 'belum_kompeten')->count();

        // Peserta yang perlu remedial
        $pesertaRemedial = $kegiatan->pesertas->filter(function ($p) {
            return $p->status_kelulusan === 'belum_kompeten' || $p->nilais->contains('status', 'belum_kompeten');
        });

        return view('kejuruan.uji_kompetensi.show', compact(
            'layout', 'setting', 'user', 'kegiatan',
            'totalPeserta', 'sudahDinilai', 'totalKompeten', 'totalBelum', 'pesertaRemedial'
        ));
    }

    /**
     * Form input nilai UKK untuk seorang peserta.
     */
    public function inputNilai($id, $pesertaId)
    {
        $layout = 'layout.app';
        $setting = Setting::find(1);
        $user = Auth::user();

        $kegiatan = UkkKegiatan::with(['units.kompetensi', 'pengujis.guru'])->findOrFail($id);
        $peserta = UkkPeserta::with(['siswa.kelas', 'nilais'])->findOrFail($pesertaId);

        // Ambil nilai yang sudah ada di-index per id_ukk_unit
        $nilaiMap = $peserta->nilais->keyBy('id_ukk_unit');

        return view('kejuruan.uji_kompetensi.input_nilai', compact(
            'layout', 'setting', 'user', 'kegiatan', 'peserta', 'nilaiMap'
        ));
    }

    /**
     * Simpan nilai UKK peserta dan alirkan ke capaian_siswa.
     */
    public function simpanNilai(Request $request, $id, $pesertaId)
    {
        $kegiatan = UkkKegiatan::with(['units.kompetensi'])->findOrFail($id);
        $peserta = UkkPeserta::with(['siswa'])->findOrFail($pesertaId);
        $user = Auth::user();

        DB::beginTransaction();
        try {
            $totalNilai = 0;
            $unitCount = 0;
            $semuaKompeten = true;

            foreach ($kegiatan->units as $unit) {
                $nilaiKey = 'nilai_' . $unit->id_ukk_unit;
                $pengujiKey = 'penguji_' . $unit->id_ukk_unit;
                $catatanKey = 'catatan_' . $unit->id_ukk_unit;
                $remedialKey = 'remedial_' . $unit->id_ukk_unit;

                $nilaiAngka = floatval($request->input($nilaiKey, 0));
                $idPenguji = $request->input($pengujiKey);
                $catatan = $request->input($catatanKey);
                $jadwalRemedial = $request->input($remedialKey);

                $isKompeten = ($nilaiAngka >= $unit->kkm);
                if (!$isKompeten) {
                    $semuaKompeten = false;
                }

                $totalNilai += $nilaiAngka;
                $unitCount++;

                // Simpan atau update di ukk_nilai_siswa
                $nilaiSiswa = UkkNilaiSiswa::updateOrCreate([
                    'id_ukk_peserta' => $peserta->id_ukk_peserta,
                    'id_ukk_unit' => $unit->id_ukk_unit,
                ], [
                    'id_ukk_penguji' => $idPenguji,
                    'nilai' => $nilaiAngka,
                    'status' => $isKompeten ? 'kompeten' : 'belum_kompeten',
                    'catatan_penguji' => $catatan,
                    'jadwal_remedial' => !$isKompeten ? $jadwalRemedial : null,
                ]);

                // TERCATAT KE TABEL CAPAIAN_SISWA DENGAN JENIS "ukk"
                CapaianSiswa::updateOrCreate([
                    'id_siswa' => $peserta->id_siswa,
                    'id_kompetensi' => $unit->id_kompetensi,
                    'jenis_capaian' => 'ukk',
                    'id_referensi' => $kegiatan->id_ukk_kegiatan,
                ], [
                    'nilai' => $nilaiAngka,
                    'kkm' => $unit->kkm,
                    'status' => $isKompeten ? 'kompeten' : 'belum_kompeten',
                    'tanggal' => Carbon::now()->toDateString(),
                    'id_penilai' => $user->id_guru,
                    'catatan' => 'Uji Kompetensi: ' . $kegiatan->nama_kegiatan . ($catatan ? ' - ' . $catatan : ''),
                ]);
            }

            // Hitung nilai akhir peserta
            $nilaiRata = $unitCount > 0 ? round($totalNilai / $unitCount, 2) : 0;
            $peserta->update([
                'nilai_akhir' => $nilaiRata,
                'status_kelulusan' => $semuaKompeten ? 'kompeten' : 'belum_kompeten',
                'catatan_umum' => $request->input('catatan_umum'),
            ]);

            DB::commit();

            return redirect('/admin/kejuruan/uji-kompetensi/' . $kegiatan->id_ukk_kegiatan)
                ->with('success', 'Nilai UKK siswa ' . $peserta->siswa->nama_siswa . ' berhasil disimpan dan dialirkan ke Buku Induk Capaian Siswa.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan nilai UKK: ' . $e->getMessage());
        }
    }

    /**
     * Cetak Berita Acara Uji Kompetensi Keahlian (PDF).
     */
    public function cetakBeritaAcara($id)
    {
        $setting = Setting::find(1);
        $kegiatan = UkkKegiatan::with([
            'jurusan', 'lab', 'pembuat',
            'units.kompetensi',
            'pengujis.guru',
            'pesertas.siswa.kelas'
        ])->findOrFail($id);

        $totalPeserta = $kegiatan->pesertas->count();
        $totalKompeten = $kegiatan->pesertas->where('status_kelulusan', 'kompeten')->count();
        $totalBelum = $kegiatan->pesertas->where('status_kelulusan', 'belum_kompeten')->count();

        $pdf = Pdf::loadView('kejuruan.uji_kompetensi.pdf_berita_acara', compact(
            'setting', 'kegiatan', 'totalPeserta', 'totalKompeten', 'totalBelum'
        ))->setPaper('a4', 'portrait');

        $safeName = \Illuminate\Support\Str::slug($kegiatan->nama_kegiatan, '_');
        return $pdf->stream('Berita_Acara_UKK_' . $safeName . '.pdf');
    }

    /**
     * Cetak Rekapitulasi Hasil UKK Siswa (PDF).
     */
    public function cetakRekapPdf($id)
    {
        $setting = Setting::find(1);
        $kegiatan = UkkKegiatan::with([
            'jurusan', 'lab',
            'units.kompetensi',
            'pesertas.siswa.kelas',
            'pesertas.nilais.unit'
        ])->findOrFail($id);

        $pdf = Pdf::loadView('kejuruan.uji_kompetensi.pdf_rekap_nilai', compact(
            'setting', 'kegiatan'
        ))->setPaper('a4', 'landscape');

        $safeName = \Illuminate\Support\Str::slug($kegiatan->nama_kegiatan, '_');
        return $pdf->stream('Rekap_Nilai_UKK_' . $safeName . '.pdf');
    }

    /**
     * Hapus kegiatan UKK.
     */
    public function destroy($id)
    {
        $kegiatan = UkkKegiatan::findOrFail($id);
        $kegiatan->delete();

        return redirect('/admin/kejuruan/uji-kompetensi')
            ->with('success', 'Kegiatan UKK berhasil dihapus.');
    }
}
