<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\PengajuanSuratPkl;
use App\Models\PengajuanSuratPklSiswa;
use App\Models\Perusahaan;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Jurusan;
use App\Models\Setting;
use App\Models\SiswaPkl;
use Barryvdh\DomPDF\Facade\Pdf;

class PengajuanPklController extends Controller
{
    /**
     * Pastikan role BKK/Hubin/Admin/Tata Usaha
     */
    private function authorizeBkkAccess(): void
    {
        $user = Auth::user();
        if (!$user || !$user->hasAnyRole(['admin', 'hubin', 'bkk', 'tata_usaha', 'kesiswaan'])) {
            abort(403, 'Akses ditolak: Hanya pihak BKK, Hubin, dan Admin yang dapat mengelola pengajuan surat PKL.');
        }
    }

    /**
     * Halaman Dashboard & Daftar Pengajuan Surat PKL untuk BKK
     */
    public function index(Request $request)
    {
        $this->authorizeBkkAccess();

        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();

        $query = PengajuanSuratPkl::with(['siswaList', 'perusahaan', 'penyetuju'])
                    ->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_pengajuan', 'like', "%{$search}%")
                  ->orWhere('nomor_surat', 'like', "%{$search}%")
                  ->orWhere('nama_perusahaan', 'like', "%{$search}%")
                  ->orWhere('ditujukan_kepada', 'like', "%{$search}%")
                  ->orWhere('nama_pemohon', 'like', "%{$search}%")
                  ->orWhereHas('siswaList', function ($sq) use ($search) {
                      $sq->where('nama_siswa', 'like', "%{$search}%")
                         ->orWhere('nis', 'like', "%{$search}%");
                  });
            });
        }

        $pengajuanList = $query->paginate(15)->withQueryString();

        $totalSemua = PengajuanSuratPkl::count();
        $totalMenunggu = PengajuanSuratPkl::where('status', 'menunggu')->count();
        $totalDisetujui = PengajuanSuratPkl::where('status', 'disetujui')->count();
        $totalDitolak = PengajuanSuratPkl::where('status', 'ditolak')->count();

        return view('bkk.pengajuan_pkl.index', compact(
            'layout', 'setting', 'user', 'pengajuanList',
            'totalSemua', 'totalMenunggu', 'totalDisetujui', 'totalDitolak'
        ));
    }

    /**
     * Form Tambah Pengajuan Surat PKL (Oleh BKK di Admin)
     */
    public function create()
    {
        $this->authorizeBkkAccess();

        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        $perusahaan = Perusahaan::orderBy('nama_perusahaan', 'asc')->get();
        $jurusan = Jurusan::orderBy('nama_jurusan', 'asc')->get();
        $suggestedNomorSurat = PengajuanSuratPkl::generateNomorSuratBaru();

        return view('bkk.pengajuan_pkl.create', compact(
            'layout', 'setting', 'user', 'perusahaan', 'jurusan', 'suggestedNomorSurat'
        ));
    }

    /**
     * Simpan Pengajuan Baru (Oleh BKK di Admin)
     */
    public function store(Request $request)
    {
        $this->authorizeBkkAccess();

        $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'ditujukan_kepada' => 'required|string|max:255',
            'jabatan_tujuan' => 'nullable|string|max:255',
            'periode_teks' => 'required|string|max:255',
            'siswa' => 'required|array|min:1',
            'siswa.*.nama' => 'required|string|max:255',
            'siswa.*.program_keahlian' => 'required|string|max:255',
        ]);

        DB::transaction(function () use ($request) {
            $kode = 'OJT-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

            $pengajuan = PengajuanSuratPkl::create([
                'kode_pengajuan' => $kode,
                'nomor_surat' => $request->nomor_surat ?: PengajuanSuratPkl::generateNomorSuratBaru(),
                'tanggal_surat' => $request->tanggal_surat ?: now()->toDateString(),
                'hal' => 'Permohonan PKL',
                'id_perusahaan' => $request->id_perusahaan,
                'nama_perusahaan' => $request->nama_perusahaan,
                'ditujukan_kepada' => $request->ditujukan_kepada,
                'jabatan_tujuan' => $request->jabatan_tujuan ?: 'H.R & Learning Manager',
                'alamat_perusahaan' => $request->alamat_perusahaan,
                'periode_teks' => $request->periode_teks,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'nama_pemohon' => $request->nama_pemohon ?: ($request->siswa[0]['nama'] ?? 'Siswa'),
                'kontak_pemohon' => $request->kontak_pemohon,
                'nama_penandatangan' => $request->nama_penandatangan ?: 'Nanan Supriatna',
                'jabatan_penandatangan' => $request->jabatan_penandatangan ?: 'Koordinator Traning & Wakahubin',
                'kontak_penandatangan' => $request->kontak_penandatangan ?: '082312261278',
                'status' => $request->status_langsung_acc ? 'disetujui' : 'menunggu',
                'disetujui_oleh' => $request->status_langsung_acc ? Auth::id() : null,
                'disetujui_pada' => $request->status_langsung_acc ? now() : null,
                'catatan_bkk' => $request->catatan_bkk,
            ]);

            foreach ($request->siswa as $item) {
                if (empty($item['nama'])) continue;

                PengajuanSuratPklSiswa::create([
                    'id_pengajuan' => $pengajuan->id_pengajuan,
                    'id_siswa' => $item['id_siswa'] ?? null,
                    'nama_siswa' => $item['nama'],
                    'nis' => $item['nis'] ?? null,
                    'program_keahlian' => $item['program_keahlian'],
                    'kelas' => $item['kelas'] ?? null,
                ]);
            }
        });

        return redirect()->route('admin.pengajuan-pkl.index')
                         ->with('success', 'Data pengajuan surat permohonan PKL berhasil dibuat.');
    }

    /**
     * Detail Pengajuan Surat PKL (BKK)
     */
    public function show($id)
    {
        $this->authorizeBkkAccess();

        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();

        $pengajuan = PengajuanSuratPkl::with(['siswaList', 'perusahaan', 'penyetuju'])
                                      ->findOrFail($id);

        $suggestedNomorSurat = $pengajuan->nomor_surat ?: PengajuanSuratPkl::generateNomorSuratBaru();

        return view('bkk.pengajuan_pkl.detail', compact(
            'layout', 'setting', 'user', 'pengajuan', 'suggestedNomorSurat'
        ));
    }

    /**
     * Form Edit Pengajuan Surat PKL
     */
    public function edit($id)
    {
        $this->authorizeBkkAccess();

        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        $pengajuan = PengajuanSuratPkl::with('siswaList')->findOrFail($id);
        $perusahaan = Perusahaan::orderBy('nama_perusahaan', 'asc')->get();
        $jurusan = Jurusan::orderBy('nama_jurusan', 'asc')->get();

        return view('bkk.pengajuan_pkl.edit', compact(
            'layout', 'setting', 'user', 'pengajuan', 'perusahaan', 'jurusan'
        ));
    }

    /**
     * Update Pengajuan Surat PKL
     */
    public function update(Request $request, $id)
    {
        $this->authorizeBkkAccess();

        $pengajuan = PengajuanSuratPkl::findOrFail($id);

        $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'ditujukan_kepada' => 'required|string|max:255',
            'periode_teks' => 'required|string|max:255',
            'siswa' => 'required|array|min:1',
            'siswa.*.nama' => 'required|string|max:255',
            'siswa.*.program_keahlian' => 'required|string|max:255',
        ]);

        DB::transaction(function () use ($request, $pengajuan) {
            $pengajuan->update([
                'nomor_surat' => $request->nomor_surat,
                'tanggal_surat' => $request->tanggal_surat ?: $pengajuan->tanggal_surat,
                'id_perusahaan' => $request->id_perusahaan,
                'nama_perusahaan' => $request->nama_perusahaan,
                'ditujukan_kepada' => $request->ditujukan_kepada,
                'jabatan_tujuan' => $request->jabatan_tujuan ?: 'H.R & Learning Manager',
                'alamat_perusahaan' => $request->alamat_perusahaan,
                'periode_teks' => $request->periode_teks,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'nama_pemohon' => $request->nama_pemohon ?: $pengajuan->nama_pemohon,
                'kontak_pemohon' => $request->kontak_pemohon,
                'nama_penandatangan' => $request->nama_penandatangan ?: 'Nanan Supriatna',
                'jabatan_penandatangan' => $request->jabatan_penandatangan ?: 'Koordinator Traning & Wakahubin',
                'kontak_penandatangan' => $request->kontak_penandatangan ?: '082312261278',
                'catatan_bkk' => $request->catatan_bkk,
            ]);

            // Re-sync daftar siswa
            $pengajuan->siswaList()->delete();
            foreach ($request->siswa as $item) {
                if (empty($item['nama'])) continue;

                PengajuanSuratPklSiswa::create([
                    'id_pengajuan' => $pengajuan->id_pengajuan,
                    'id_siswa' => $item['id_siswa'] ?? null,
                    'nama_siswa' => $item['nama'],
                    'nis' => $item['nis'] ?? null,
                    'program_keahlian' => $item['program_keahlian'],
                    'kelas' => $item['kelas'] ?? null,
                ]);
            }
        });

        return redirect()->route('admin.pengajuan-pkl.show', $id)
                         ->with('success', 'Data pengajuan surat PKL berhasil diperbarui.');
    }

    /**
     * ACC / Setujui Pengajuan Surat PKL oleh BKK
     */
    public function acc(Request $request, $id)
    {
        $this->authorizeBkkAccess();

        $pengajuan = PengajuanSuratPkl::with('siswaList')->findOrFail($id);

        $request->validate([
            'nomor_surat' => 'required|string|max:100',
            'tanggal_surat' => 'required|date',
            'nama_penandatangan' => 'required|string|max:255',
            'jabatan_penandatangan' => 'required|string|max:255',
        ]);

        DB::transaction(function () use ($request, $pengajuan) {
            $pengajuan->update([
                'status' => 'disetujui',
                'nomor_surat' => $request->nomor_surat,
                'tanggal_surat' => $request->tanggal_surat,
                'nama_penandatangan' => $request->nama_penandatangan,
                'jabatan_penandatangan' => $request->jabatan_penandatangan,
                'kontak_penandatangan' => $request->kontak_penandatangan ?: '082312261278',
                'catatan_bkk' => $request->catatan_bkk,
                'disetujui_oleh' => Auth::id(),
                'disetujui_pada' => now(),
            ]);

            // Jika opsi masukkan otomatis ke data Siswa PKL dicentang
            if ($request->boolean('sync_to_siswa_pkl') && $pengajuan->id_perusahaan) {
                foreach ($pengajuan->siswaList as $item) {
                    if ($item->id_siswa) {
                        $siswa = Siswa::find($item->id_siswa);
                        $idKelas = $siswa ? $siswa->id_kelas : 1;

                        SiswaPkl::updateOrCreate(
                            [
                                'id_siswa' => $item->id_siswa,
                                'id_perusahaan' => $pengajuan->id_perusahaan,
                            ],
                            [
                                'id_kelas' => $idKelas,
                                'tanggal_mulai' => $pengajuan->tanggal_mulai ?: now()->toDateString(),
                                'tanggal_selesai' => $pengajuan->tanggal_selesai ?: now()->addMonths(6)->toDateString(),
                                'status' => 'PKL',
                            ]
                        );
                    }
                }
            }
        });

        return redirect()->route('admin.pengajuan-pkl.show', $id)
                         ->with('success', 'Pengajuan surat permohonan PKL telah berhasil di-ACC (Disetujui). Surat kini siap dicetak.');
    }

    /**
     * Tolak Pengajuan Surat PKL oleh BKK
     */
    public function tolak(Request $request, $id)
    {
        $this->authorizeBkkAccess();

        $pengajuan = PengajuanSuratPkl::findOrFail($id);

        $request->validate([
            'alasan_penolakan' => 'required|string|max:1000',
        ]);

        $pengajuan->update([
            'status' => 'ditolak',
            'catatan_bkk' => $request->alasan_penolakan,
            'disetujui_oleh' => Auth::id(),
            'disetujui_pada' => now(),
        ]);

        return redirect()->route('admin.pengajuan-pkl.show', $id)
                         ->with('info', 'Pengajuan surat PKL telah ditandai ditolak dengan catatan.');
    }

    /**
     * Cetak Surat Permohonan PKL (Tampilan Resmi Siap Print)
     */
    public function cetakSurat($id)
    {
        $this->authorizeBkkAccess();

        $setting = Setting::find('1');
        $pengajuan = PengajuanSuratPkl::with(['siswaList', 'perusahaan'])
                                      ->findOrFail($id);

        return view('bkk.pengajuan_pkl.cetak', compact('pengajuan', 'setting'));
    }

    /**
     * Download PDF Surat Permohonan PKL (DomPDF)
     */
    public function downloadPdf($id)
    {
        $this->authorizeBkkAccess();

        $setting = Setting::find('1');
        $pengajuan = PengajuanSuratPkl::with(['siswaList', 'perusahaan'])
                                      ->findOrFail($id);

        $cleanNomor = preg_replace('/[^a-zA-Z0-9_-]/', '_', $pengajuan->nomor_surat ?: $pengajuan->kode_pengajuan);
        $filename = "Surat_Permohonan_PKL_{$cleanNomor}.pdf";

        $pdf = Pdf::loadView('bkk.pengajuan_pkl.cetak_pdf', compact('pengajuan', 'setting'))
                  ->setPaper('a4', 'portrait');

        return $pdf->download($filename);
    }

    /**
     * Hapus Pengajuan Surat PKL
     */
    public function destroy($id)
    {
        $this->authorizeBkkAccess();

        $pengajuan = PengajuanSuratPkl::findOrFail($id);
        $pengajuan->delete();

        return redirect()->route('admin.pengajuan-pkl.index')
                         ->with('success', 'Data pengajuan surat PKL berhasil dihapus.');
    }

    // =========================================================================
    // BAGIAN SISWA: FORM PENGAJUAN & TRACKING MANDIRI
    // =========================================================================

    /**
     * Halaman Formulir Pengajuan Mandiri Siswa (Public Web)
     */
    public function formSiswa()
    {
        $setting = Setting::find('1');
        $perusahaan = Perusahaan::orderBy('nama_perusahaan', 'asc')->get();
        $jurusan = Jurusan::orderBy('nama_jurusan', 'asc')->get();

        return view('bkk.pengajuan_pkl.form_siswa', compact('setting', 'perusahaan', 'jurusan'));
    }

    /**
     * Proses Submit Pengajuan Surat PKL oleh Siswa
     */
    public function submitSiswa(Request $request)
    {
        $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'ditujukan_kepada' => 'required|string|max:255',
            'periode_teks' => 'required|string|max:255',
            'nama_pemohon' => 'required|string|max:255',
            'kontak_pemohon' => 'required|string|max:30',
            'siswa' => 'required|array|min:1',
            'siswa.*.nama' => 'required|string|max:255',
            'siswa.*.program_keahlian' => 'required|string|max:255',
        ], [
            'nama_perusahaan.required' => 'Nama perusahaan / tempat PKL wajib diisi.',
            'ditujukan_kepada.required' => 'Nama penerima / pimpinan perusahaan wajib diisi.',
            'periode_teks.required' => 'Periode pelaksanaan PKL wajib diisi.',
            'nama_pemohon.required' => 'Nama perwakilan siswa wajib diisi.',
            'kontak_pemohon.required' => 'Nomor WhatsApp perwakilan wajib diisi.',
            'siswa.required' => 'Minimal 1 data siswa harus dimasukkan.',
        ]);

        $kode = 'PKL-' . date('Ym') . '-' . strtoupper(substr(uniqid(), -5));

        DB::transaction(function () use ($request, $kode) {
            $pengajuan = PengajuanSuratPkl::create([
                'kode_pengajuan' => $kode,
                'nomor_surat' => null, // Akan diisi saat ACC oleh BKK
                'tanggal_surat' => now()->toDateString(),
                'hal' => 'Permohonan PKL',
                'id_perusahaan' => $request->id_perusahaan ?: null,
                'nama_perusahaan' => $request->nama_perusahaan,
                'ditujukan_kepada' => $request->ditujukan_kepada,
                'jabatan_tujuan' => $request->jabatan_tujuan ?: 'H.R & Learning Manager',
                'alamat_perusahaan' => $request->alamat_perusahaan,
                'periode_teks' => $request->periode_teks,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'nama_pemohon' => $request->nama_pemohon,
                'kontak_pemohon' => $request->kontak_pemohon,
                'catatan_siswa' => $request->catatan_siswa,
                'status' => 'menunggu',
            ]);

            foreach ($request->siswa as $item) {
                if (empty($item['nama'])) continue;

                PengajuanSuratPklSiswa::create([
                    'id_pengajuan' => $pengajuan->id_pengajuan,
                    'id_siswa' => $item['id_siswa'] ?? null,
                    'nama_siswa' => $item['nama'],
                    'nis' => $item['nis'] ?? null,
                    'program_keahlian' => $item['program_keahlian'],
                    'kelas' => $item['kelas'] ?? null,
                ]);
            }
        });

        return redirect()->route('pengajuan-pkl.tracking', ['kode' => $kode])
                         ->with('success', "Pengajuan surat permohonan PKL berhasil dikirim! Simpan kode pengajuan Anda: {$kode}");
    }

    /**
     * Halaman Cek Status / Tracking Pengajuan untuk Siswa
     */
    public function trackingSiswa(Request $request)
    {
        $setting = Setting::find('1');
        $pengajuan = null;

        if ($request->filled('kode')) {
            $search = trim($request->kode);
            $pengajuan = PengajuanSuratPkl::with(['siswaList', 'perusahaan'])
                            ->where('kode_pengajuan', $search)
                            ->orWhere('kontak_pemohon', $search)
                            ->orWhereHas('siswaList', function ($q) use ($search) {
                                $q->where('nis', $search)->orWhere('nama_siswa', 'like', "%{$search}%");
                            })
                            ->latest()
                            ->first();
        }

        return view('bkk.pengajuan_pkl.tracking_siswa', compact('setting', 'pengajuan'));
    }

    /**
     * Endpoint API / AJAX untuk pencarian siswa otomatis
     */
    public function searchSiswa(Request $request)
    {
        $q = $request->get('q', '');
        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $siswa = Siswa::with(['jurusan', 'kelas'])
                      ->where('nama_siswa', 'like', "%{$q}%")
                      ->orWhere('nis', 'like', "%{$q}%")
                      ->limit(10)
                      ->get();

        $results = $siswa->map(function ($s) {
            return [
                'id_siswa' => $s->id_siswa,
                'nama_siswa' => $s->nama_siswa,
                'nis' => $s->nis,
                'program_keahlian' => $s->jurusan->nama_jurusan ?? ($s->kelas->nama_kelas ?? 'Pariwisata'),
                'kelas' => $s->kelas->nama_kelas ?? '',
            ];
        });

        return response()->json($results);
    }
}
