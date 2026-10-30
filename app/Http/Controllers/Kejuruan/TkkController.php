<?php

namespace App\Http\Controllers\Kejuruan;

use App\Http\Controllers\Controller;
use App\Models\CapaianSiswa;
use App\Models\Guru;
use App\Models\JenisTkk;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\Kompetensi;
use App\Models\Setting;
use App\Models\Siswa;
use App\Models\SiswaTkk;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TkkController extends Controller
{
    /**
     * Master Jenis Tanda Kecakapan Khusus (TKK).
     */
    public function index(Request $request)
    {
        $layout = 'layout.app';
        $setting = Setting::find(1);
        $user = Auth::user();

        $query = JenisTkk::forUserJurusan($user)->with(['jurusan', 'syarats', 'siswaTkks']);

        if ($request->filled('id_jurusan')) {
            $query->where('id_jurusan', $request->id_jurusan);
        }

        if ($request->filled('tingkat')) {
            $query->where('tingkat', $request->tingkat);
        }

        $jenisTkks = $query->get();

        $accessibleJurusanIds = JenisTkk::getAccessibleJurusanIds($user);
        $jurusans = Jurusan::whereIn('id_jurusan', $accessibleJurusanIds)->get();

        // Statistik
        $allTkkIds = JenisTkk::forUserJurusan($user)->pluck('id_jenis_tkk');
        $totalJenisTkk = $allTkkIds->count();
        $totalPenerima = SiswaTkk::whereIn('id_jenis_tkk', $allTkkIds)->where('status', 'diberikan')->count();
        $totalProses = SiswaTkk::whereIn('id_jenis_tkk', $allTkkIds)->where('status', 'proses')->count();
        $totalSyaratKomp = DB::table('tkk_syarat_kompetensi')->whereIn('id_jenis_tkk', $allTkkIds)->count();

        return view('kejuruan.tkk.index', compact(
            'layout', 'setting', 'user', 'jenisTkks', 'jurusans',
            'totalJenisTkk', 'totalPenerima', 'totalProses', 'totalSyaratKomp'
        ));
    }

    /**
     * Form tambah Jenis TKK baru.
     */
    public function create()
    {
        $layout = 'layout.app';
        $setting = Setting::find(1);
        $user = Auth::user();

        $accessibleJurusanIds = JenisTkk::getAccessibleJurusanIds($user);
        $jurusans = Jurusan::whereIn('id_jurusan', $accessibleJurusanIds)->get();
        $kompetensis = Kompetensi::forUserJurusan($user)->with('jurusan')->orderBy('kode_kompetensi', 'asc')->get();

        return view('kejuruan.tkk.create', compact('layout', 'setting', 'user', 'jurusans', 'kompetensis'));
    }

    /**
     * Simpan Jenis TKK baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_tkk' => 'required|string|max:255',
            'kode_tkk' => 'nullable|string|max:50',
            'nomor_klaster' => 'nullable|integer|min:1|max:9',
            'id_jurusan' => 'required|exists:jurusan,id_jurusan',
            'tingkat' => 'required|in:dasar,menengah,utama',
            'syarat_kompetensi' => 'nullable|array',
            'syarat_kompetensi.*' => 'exists:kompetensi,id_kompetensi',
        ]);

        DB::beginTransaction();
        try {
            $tkk = JenisTkk::create([
                'nama_tkk' => $request->nama_tkk,
                'kode_tkk' => $request->kode_tkk,
                'nomor_klaster' => $request->nomor_klaster,
                'id_jurusan' => $request->id_jurusan,
                'tingkat' => $request->tingkat,
                'deskripsi' => $request->deskripsi,
            ]);

            $tkk->syarats()->sync($request->syarat_kompetensi ?? []);

            DB::commit();

            return redirect('/admin/kejuruan/tkk')
                ->with('success', 'Jenis TKK ' . $tkk->nama_tkk . ' berhasil dibuat beserta syarat kompetensinya.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal membuat Jenis TKK: ' . $e->getMessage());
        }
    }

    /**
     * Matriks Kelayakan & Pemberian TKK ke Siswa.
     */
    public function pemberian(Request $request)
    {
        $layout = 'layout.app';
        $setting = Setting::find(1);
        $user = Auth::user();

        $accessibleJurusanIds = JenisTkk::getAccessibleJurusanIds($user);
        $jurusans = Jurusan::whereIn('id_jurusan', $accessibleJurusanIds)->get();
        $kelas = Kelas::whereHas('jurusan', function ($q) use ($accessibleJurusanIds) {
            $q->whereIn('id_jurusan', $accessibleJurusanIds);
        })->orderBy('nama_kelas', 'asc')->get();

        $selectedTkkId = $request->input('id_jenis_tkk');
        $selectedKelasId = $request->input('id_kelas');

        $jenisTkks = JenisTkk::forUserJurusan($user)->with('syarats')->get();
        $activeTkk = $selectedTkkId ? JenisTkk::with('syarats')->find($selectedTkkId) : $jenisTkks->first();

        $siswaList = collect();
        if ($activeTkk) {
            $syaratIds = $activeTkk->syarats->pluck('id_kompetensi')->toArray();
            $totalSyarat = count($syaratIds);

            if ($selectedKelasId) {
                // Tampilkan seluruh siswa di kelas yang dipilih untuk analisis matriks
                $querySiswa = Siswa::with('kelas')->where('id_kelas', $selectedKelasId);
            } else {
                // Tampilkan siswa yang sudah menerima TKK ATAU siswa yang berhak (memenuhi syarat 100%)
                $siswaTkkIds = SiswaTkk::where('id_jenis_tkk', $activeTkk->id_jenis_tkk)
                    ->pluck('id_siswa')
                    ->toArray();

                $candidateSiswaIds = [];
                if ($totalSyarat > 0) {
                    $candidateSiswaIds = CapaianSiswa::whereIn('id_kompetensi', $syaratIds)
                        ->where('status', 'kompeten')
                        ->groupBy('id_siswa')
                        ->havingRaw('COUNT(DISTINCT id_kompetensi) >= ?', [$totalSyarat])
                        ->pluck('id_siswa')
                        ->toArray();
                } else {
                    // Jika tanpa syarat kompetensi web (buku saku fisik), tampilkan siswa di kelas jurusan ini
                    $jurusanKelasIds = Kelas::whereHas('jurusan', function ($q) use ($activeTkk) {
                        $q->where('id_jurusan', $activeTkk->id_jurusan);
                    })->pluck('id_kelas')->toArray();
                    $candidateSiswaIds = Siswa::whereIn('id_kelas', $jurusanKelasIds)->pluck('id_siswa')->toArray();
                }

                $allRelevantSiswaIds = array_unique(array_merge($siswaTkkIds, $candidateSiswaIds));

                if (empty($allRelevantSiswaIds)) {
                    $querySiswa = Siswa::whereRaw('1 = 0');
                } else {
                    $querySiswa = Siswa::with('kelas')->whereIn('id_siswa', $allRelevantSiswaIds);
                }
            }

            $siswaList = $querySiswa->orderBy('nama_siswa', 'asc')->get()->map(function ($s) use ($activeTkk, $syaratIds, $totalSyarat) {
                // Ambil data capaian kompetensi siswa yang sudah lulus
                $lulusCapaians = CapaianSiswa::where('id_siswa', $s->id_siswa)
                    ->whereIn('id_kompetensi', $syaratIds)
                    ->where('status', 'kompeten')
                    ->get()
                    ->keyBy('id_kompetensi');

                $lulusKompetensiIds = $lulusCapaians->keys()->toArray();
                $lulusCount = min($totalSyarat, count($lulusKompetensiIds));

                // Cek rekor di siswa_tkk
                $rekorTkk = SiswaTkk::where('id_siswa', $s->id_siswa)
                    ->where('id_jenis_tkk', $activeTkk->id_jenis_tkk)
                    ->first();

                // Status otomatis
                $statusKalkulasi = 'belum_memenuhi';
                if ($rekorTkk && $rekorTkk->status === 'diberikan') {
                    $statusKalkulasi = 'diberikan';
                } elseif ($totalSyarat === 0) {
                    // Model Buku Saku Fisik: Tanpa syarat kompetensi di web, verifikasi dilakukan oleh guru langsung
                    $statusKalkulasi = 'memenuhi_syarat';
                } elseif ($totalSyarat > 0 && $lulusCount >= $totalSyarat) {
                    $statusKalkulasi = 'memenuhi_syarat';
                } elseif ($lulusCount > 0) {
                    $statusKalkulasi = 'proses';
                }

                // Rincian unit kompetensi untuk modal verifikasi / checklist
                $syaratDetails = $activeTkk->syarats->map(function ($komp) use ($lulusCapaians) {
                    $isLulus = $lulusCapaians->has($komp->id_kompetensi);
                    $capaian = $isLulus ? $lulusCapaians->get($komp->id_kompetensi) : null;
                    return (object) [
                        'id_kompetensi' => $komp->id_kompetensi,
                        'kode_kompetensi' => $komp->kode_kompetensi,
                        'nama_kompetensi' => $komp->nama_kompetensi,
                        'is_lulus' => $isLulus,
                        'nilai' => $capaian ? floatval($capaian->nilai) : null,
                        'tanggal' => $capaian ? $capaian->tanggal : null,
                    ];
                });

                $s->tkk_rekor = $rekorTkk;
                $s->tkk_syarat_total = $totalSyarat;
                $s->tkk_syarat_lulus = $totalSyarat === 0 ? 0 : $lulusCount;
                $s->tkk_syarat_persen = $totalSyarat === 0 ? 100 : ($totalSyarat > 0 ? min(100, round(($lulusCount / $totalSyarat) * 100)) : 0);
                $s->tkk_syarat_ids_lulus = $lulusKompetensiIds;
                $s->tkk_syarat_details = $syaratDetails;
                $s->tkk_status = $statusKalkulasi;

                return $s;
            });
        }

        $defaultNoPiagam = $activeTkk ? $setting->generateNomorTkk($activeTkk, null, false) : '';
        $defaultFormatTkk = $setting->kejuruan_nomor_settings['tkk']['format'] ?? 'TKK/{JURUSAN}/{KLASTER}/{ROMAWI}/{TAHUN}/{NO}';

        return view('kejuruan.tkk.pemberian', compact(
            'layout', 'setting', 'user', 'jurusans', 'kelas', 'jenisTkks',
            'activeTkk', 'selectedKelasId', 'siswaList', 'defaultNoPiagam', 'defaultFormatTkk'
        ));
    }

    /**
     * Sahkan pemberian TKK ke siswa dan alirkan ke capaian_siswa.
     */
    public function sahkanTkk(Request $request)
    {
        $request->validate([
            'id_siswa' => 'required|exists:siswa,id_siswa',
            'id_jenis_tkk' => 'required|exists:jenis_tkk,id_jenis_tkk',
            'tanggal_perolehan' => 'required|date',
            'nomor_piagam' => 'nullable|string|max:100',
            'file_bukti' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'is_dispensasi' => 'nullable|boolean',
            'alasan_dispensasi' => 'nullable|string|max:255',
        ]);

        $tkk = JenisTkk::with('syarats')->findOrFail($request->id_jenis_tkk);
        $totalSyarat = $tkk->syarats->count();
        $syaratIds = $tkk->syarats->pluck('id_kompetensi')->toArray();

        $lulusCount = CapaianSiswa::where('id_siswa', $request->id_siswa)
            ->whereIn('id_kompetensi', $syaratIds)
            ->where('status', 'kompeten')
            ->distinct('id_kompetensi')
            ->count('id_kompetensi');

        $isDispensasi = $request->boolean('is_dispensasi');

        // Validasi kelayakan: harus 100% memenuhi atau melalui jalur dispensasi
        if (!$isDispensasi && $totalSyarat > 0 && $lulusCount < $totalSyarat) {
            return back()->with('error', 'Siswa belum memenuhi seluruh unit kompetensi prasyarat (baru ' . $lulusCount . ' dari ' . $totalSyarat . ' unit). Gunakan opsi Dispensasi Khusus jika disahkan berdasarkan portofolio eksternal.');
        }

        $user = Auth::user();
        $fileBukti = null;

        if ($request->hasFile('file_bukti')) {
            $fileBukti = $request->file('file_bukti')->store('tkk_bukti', 'public');
        }

        $catatanPengesahan = $request->catatan;
        if ($isDispensasi) {
            $alasan = $request->alasan_dispensasi ? ': ' . $request->alasan_dispensasi : '';
            $catatanPengesahan = '[Dispensasi Portofolio Luar' . $alasan . '] ' . $catatanPengesahan;
        }

        $setting = Setting::find(1);
        $nomorPiagam = trim($request->nomor_piagam);
        if (empty($nomorPiagam)) {
            $nomorPiagam = $setting->generateNomorTkk($tkk, null, true);
        }

        DB::beginTransaction();
        try {
            $siswaTkk = SiswaTkk::updateOrCreate([
                'id_siswa' => $request->id_siswa,
                'id_jenis_tkk' => $request->id_jenis_tkk,
            ], [
                'status' => 'diberikan',
                'tanggal_perolehan' => $request->tanggal_perolehan,
                'id_pemberi' => $user->id_guru,
                'nomor_piagam' => $nomorPiagam,
                'file_bukti' => $fileBukti,
                'catatan' => $catatanPengesahan,
            ]);

            $siswa = Siswa::find($request->id_siswa);

            // Alirkan ke tabel capaian_siswa dengan jenis "tkk"
            CapaianSiswa::updateOrCreate([
                'id_siswa' => $request->id_siswa,
                'jenis_capaian' => 'tkk',
                'id_referensi' => $tkk->id_jenis_tkk,
            ], [
                'id_kompetensi' => null,
                'nilai' => 100.00,
                'kkm' => 75.00,
                'status' => 'kompeten',
                'tanggal' => $request->tanggal_perolehan,
                'id_penilai' => $user->id_guru,
                'file_bukti' => $fileBukti,
                'catatan' => 'Penganugerahan TKK: ' . $tkk->nama_tkk . ($nomorPiagam ? ' (No. ' . $nomorPiagam . ')' : ''),
            ]);

            DB::commit();

            return back()->with('success', 'TKK ' . $tkk->nama_tkk . ' resmi disahkan untuk siswa ' . $siswa->nama_siswa . ' dan tercatat di Buku Induk Capaian Siswa.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengesahkan TKK: ' . $e->getMessage());
        }
    }

    /**
     * Batalkan status pengesahan TKK siswa dan kembalikan ke Dalam Proses.
     */
    public function batalkanPengesahan(string $id_siswa_tkk)
    {
        $user = Auth::user();
        if (!$user->hasAnyRole(['admin', 'kaprog', 'guru'])) {
            abort(403, 'Akses ditolak.');
        }

        $siswaTkk = SiswaTkk::with(['siswa', 'jenisTkk'])->findOrFail($id_siswa_tkk);

        DB::beginTransaction();
        try {
            // Hapus capaian di buku induk
            CapaianSiswa::where('id_siswa', $siswaTkk->id_siswa)
                ->where('jenis_capaian', 'tkk')
                ->where('id_referensi', $siswaTkk->id_jenis_tkk)
                ->delete();

            $namaSiswa = $siswaTkk->siswa->nama_siswa ?? 'Siswa';
            $namaTkk = $siswaTkk->jenisTkk->nama_tkk ?? 'TKK';

            $siswaTkk->update([
                'status' => 'proses',
                'tanggal_perolehan' => null,
                'nomor_piagam' => null,
                'id_pemberi' => null,
                'catatan' => 'Pengesahan dibatalkan oleh guru/kaprog pada ' . date('d/m/Y H:i'),
            ]);

            DB::commit();

            return back()->with('success', 'Pengesahan TKK ' . $namaTkk . ' untuk siswa ' . $namaSiswa . ' berhasil dibatalkan dan status dikembalikan ke Dalam Proses.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membatalkan pengesahan TKK: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan pratinjau dan cetak piagam sertifikat resmi TKK siswa.
     */
    public function cetakPiagam(string $id_siswa_tkk)
    {
        $setting = Setting::find(1);
        $user = Auth::user();

        $siswaTkk = SiswaTkk::with([
            'siswa.kelas.jurusan',
            'jenisTkk.jurusan',
            'jenisTkk.syarats',
            'pemberi'
        ])->findOrFail($id_siswa_tkk);

        $syaratIds = $siswaTkk->jenisTkk->syarats->pluck('id_kompetensi')->toArray();
        $capaianUnits = CapaianSiswa::where('id_siswa', $siswaTkk->id_siswa)
            ->whereIn('id_kompetensi', $syaratIds)
            ->get()
            ->keyBy('id_kompetensi');

        return view('kejuruan.tkk.cetak_piagam', compact(
            'setting', 'user', 'siswaTkk', 'capaianUnits'
        ));
    }

    /**
     * Sahkan pemberian TKK ke banyak siswa sekaligus (Batch / Masal).
     */
    public function sahkanMasal(Request $request)
    {
        $request->validate([
            'id_jenis_tkk' => 'required|exists:jenis_tkk,id_jenis_tkk',
            'siswa_ids' => 'required|array|min:1',
            'siswa_ids.*' => 'exists:siswa,id_siswa',
            'tanggal_perolehan' => 'required|date',
            'prefix_piagam' => 'nullable|string|max:100',
            'catatan' => 'nullable|string',
        ]);

        $user = Auth::user();
        $tkk = JenisTkk::with('jurusan')->findOrFail($request->id_jenis_tkk);
        $setting = Setting::find(1);

        $customPrefix = trim($request->prefix_piagam);

        DB::beginTransaction();
        try {
            $count = 0;
            foreach ($request->siswa_ids as $idSiswa) {
                if (!empty($customPrefix)) {
                    $noPiagam = $customPrefix . '/' . str_pad($count + 1, 4, '0', STR_PAD_LEFT);
                } else {
                    $noPiagam = $setting->generateNomorTkk($tkk, null, true);
                }

                SiswaTkk::updateOrCreate([
                    'id_siswa' => $idSiswa,
                    'id_jenis_tkk' => $tkk->id_jenis_tkk,
                ], [
                    'status' => 'diberikan',
                    'tanggal_perolehan' => $request->tanggal_perolehan,
                    'id_pemberi' => $user->id_guru,
                    'nomor_piagam' => $noPiagam,
                    'catatan' => $request->catatan ?: 'Pengesahan masal lencana TKK ' . $tkk->nama_tkk,
                ]);

                // Sinkronkan ke capaian_siswa
                CapaianSiswa::updateOrCreate([
                    'id_siswa' => $idSiswa,
                    'jenis_capaian' => 'tkk',
                    'id_referensi' => $tkk->id_jenis_tkk,
                ], [
                    'id_kompetensi' => null,
                    'nilai' => 100.00,
                    'kkm' => 75.00,
                    'status' => 'kompeten',
                    'tanggal' => $request->tanggal_perolehan,
                    'id_penilai' => $user->id_guru,
                    'catatan' => 'Penganugerahan TKK: ' . $tkk->nama_tkk . ' (No. ' . $noPiagam . ')',
                ]);

                $count++;
            }

            DB::commit();

            return back()->with('success', 'Berhasil mengesahkan TKK ' . $tkk->nama_tkk . ' untuk ' . $count . ' siswa terpilih sekaligus.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal melakukan pengesahan masal: ' . $e->getMessage());
        }
    }

    /**
     * Rekapitulasi TKK per Rombel Kelas & Jurusan untuk Kaprog dan Hubin.
     */
    public function rekap(Request $request)
    {
        $layout = 'layout.app';
        $setting = Setting::find(1);
        $user = Auth::user();

        $accessibleJurusanIds = JenisTkk::getAccessibleJurusanIds($user);
        $jurusans = Jurusan::whereIn('id_jurusan', $accessibleJurusanIds)->get();
        $kelas = Kelas::whereHas('jurusan', function ($q) use ($accessibleJurusanIds) {
            $q->whereIn('id_jurusan', $accessibleJurusanIds);
        })->orderBy('nama_kelas', 'asc')->get();

        $query = SiswaTkk::with(['siswa.kelas', 'jenisTkk.jurusan', 'pemberi'])
            ->whereHas('jenisTkk', function ($q) use ($accessibleJurusanIds) {
                $q->whereIn('id_jurusan', $accessibleJurusanIds);
            })->latest('id_siswa_tkk');

        if ($request->filled('id_jurusan')) {
            $query->whereHas('jenisTkk', function ($q) use ($request) {
                $q->where('id_jurusan', $request->id_jurusan);
            });
        }

        if ($request->filled('id_kelas')) {
            $query->whereHas('siswa', function ($q) use ($request) {
                $q->where('id_kelas', $request->id_kelas);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $rekapData = $query->get();

        // Statistik kesiapan kerja (Hubin)
        $totalDiberikan = SiswaTkk::where('status', 'diberikan')->count();
        $totalSiswaPenerima = SiswaTkk::where('status', 'diberikan')->distinct('id_siswa')->count('id_siswa');

        return view('kejuruan.tkk.rekap', compact(
            'layout', 'setting', 'user', 'jurusans', 'kelas', 'rekapData',
            'totalDiberikan', 'totalSiswaPenerima'
        ));
    }

    /**
     * Form edit Jenis TKK.
     */
    public function edit(string $id)
    {
        $layout = 'layout.app';
        $setting = Setting::find(1);
        $user = Auth::user();

        $tkk = JenisTkk::with('syarats')->findOrFail($id);
        $accessibleJurusanIds = JenisTkk::getAccessibleJurusanIds($user);
        $jurusans = Jurusan::whereIn('id_jurusan', $accessibleJurusanIds)->get();
        $kompetensis = Kompetensi::forUserJurusan($user)->with('jurusan')->orderBy('kode_kompetensi', 'asc')->get();

        $syaratIds = $tkk->syarats->pluck('id_kompetensi')->toArray();

        return view('kejuruan.tkk.edit', compact(
            'layout', 'setting', 'user', 'tkk', 'jurusans', 'kompetensis', 'syaratIds'
        ));
    }

    /**
     * Update Jenis TKK.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_tkk' => 'required|string|max:255',
            'id_jurusan' => 'required|exists:jurusan,id_jurusan',
            'tingkat' => 'required|in:dasar,menengah,utama',
            'nomor_klaster' => 'nullable|integer|between:1,9',
            'kode_tkk' => 'nullable|string|max:50',
            'deskripsi' => 'nullable|string',
            'syarat_kompetensi' => 'nullable|array',
            'syarat_kompetensi.*' => 'exists:kompetensi,id_kompetensi',
        ]);

        $tkk = JenisTkk::findOrFail($id);

        DB::beginTransaction();
        try {
            $tkk->update([
                'nama_tkk' => $request->nama_tkk,
                'id_jurusan' => $request->id_jurusan,
                'tingkat' => $request->tingkat,
                'nomor_klaster' => $request->nomor_klaster,
                'kode_tkk' => $request->kode_tkk,
                'deskripsi' => $request->deskripsi,
            ]);

            $tkk->syarats()->sync($request->syarat_kompetensi ?? []);
            DB::commit();

            return redirect('/admin/kejuruan/tkk')
                ->with('success', 'Jenis TKK ' . $tkk->nama_tkk . ' berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memperbarui Jenis TKK: ' . $e->getMessage());
        }
    }

    /**
     * Hapus Jenis TKK dan syarat kompetensinya.
     */
    public function destroy(string $id)
    {
        $tkk = JenisTkk::findOrFail($id);

        DB::beginTransaction();
        try {
            $namaTkk = $tkk->nama_tkk;
            $tkk->syarats()->detach();
            $tkk->siswaTkks()->delete();
            $tkk->delete();

            DB::commit();

            return redirect('/admin/kejuruan/tkk')
                ->with('success', 'Jenis TKK ' . $namaTkk . ' berhasil dihapus.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus Jenis TKK: ' . $e->getMessage());
        }
    }
}
