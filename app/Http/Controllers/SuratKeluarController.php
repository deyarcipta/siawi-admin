<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\SuratKeluar;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Setting;
use Carbon\Carbon;

class SuratKeluarController extends Controller
{
    /**
     * Daftar klasifikasi standar surat keluar sekolah (dinamis dari database).
     */
    public static function getKlasifikasiList(): array
    {
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('klasifikasi_surat')) {
                $fromDb = \App\Models\KlasifikasiSurat::where('is_active', true)
                    ->orderBy('urutan', 'asc')
                    ->orderBy('id', 'asc')
                    ->pluck('nama', 'kode')
                    ->toArray();

                if (!empty($fromDb)) {
                    return $fromDb;
                }
            }
        } catch (\Throwable $e) {
            // Fallback ke default array jika ada kendala database
        }

        return [
            'TU' => 'Tata Usaha / Administrasi Umum',
            'SK-SISWA' => 'Surat Keterangan Siswa Aktif',
            'PKL' => 'Surat Pengantar PKL / Magang',
            'UND' => 'Surat Undangan Rapat / Dinas',
            'ST' => 'Surat Tugas Guru & Pegawai',
            'MUTASI' => 'Surat Keterangan Pindah / Mutasi',
            'EDR' => 'Surat Edaran & Pemberitahuan Orang Tua',
            'REK' => 'Surat Rekomendasi',
            'LAIN' => 'Lainnya',
        ];
    }

    /**
     * Display a listing of Surat Keluar.
     */
    public function index(Request $request)
    {
        $layout = 'layout.app';
        $setting = Setting::find(1);
        $user = Auth::user();

        $klasifikasiList = self::getKlasifikasiList();
        $selectedKlasifikasi = $request->input('klasifikasi', '');
        $selectedTahun = $request->input('tahun', Carbon::now()->format('Y'));
        $search = $request->input('search', '');

        $query = SuratKeluar::with(['siswa', 'guru', 'creator'])
            ->where('tahun', $selectedTahun);

        if (!empty($selectedKlasifikasi)) {
            $query->where('kode_klasifikasi', $selectedKlasifikasi);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('nomor_surat', 'like', "%{$search}%")
                  ->orWhere('perihal', 'like', "%{$search}%")
                  ->orWhere('tujuan_surat', 'like', "%{$search}%");
            });
        }

        $suratKeluar = $query->orderBy('no_urut', 'desc')->get();

        // Rekap statistik tahunan
        $totalSuratTahunIni = SuratKeluar::where('tahun', $selectedTahun)->count();
        $totalSuratBulanIni = SuratKeluar::where('tahun', $selectedTahun)
            ->whereMonth('tanggal_surat', Carbon::now()->month)
            ->count();

        // Estimasi nomor berikutnya untuk tombol cepat
        $previewKode = !empty($selectedKlasifikasi) ? $selectedKlasifikasi : 'TU';
        $nextNumberPreview = SuratKeluar::generateNextNumber($previewKode, Carbon::now()->toDateString());

        // Daftar tahun yang tersedia di database
        $tahunList = SuratKeluar::select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun')->toArray();
        if (!in_array((int) Carbon::now()->format('Y'), $tahunList)) {
            array_unshift($tahunList, (int) Carbon::now()->format('Y'));
        }

        return view('surat.surat_keluar.index', compact(
            'layout', 'setting', 'user', 'suratKeluar', 'klasifikasiList',
            'selectedKlasifikasi', 'selectedTahun', 'search',
            'totalSuratTahunIni', 'totalSuratBulanIni', 'nextNumberPreview', 'tahunList'
        ));
    }

    /**
     * Show the form for creating a new Surat Keluar.
     */
    public function create()
    {
        $layout = 'layout.app';
        $setting = Setting::find(1);
        $user = Auth::user();

        $klasifikasiList = self::getKlasifikasiList();
        $siswaList = Siswa::with('kelas')->orderBy('nama_siswa', 'asc')->get();
        $guruList = Guru::orderBy('nama_guru', 'asc')->get();
        $today = Carbon::now()->toDateString();
        $nextNumberPreview = SuratKeluar::generateNextNumber('TU', $today);

        return view('surat.surat_keluar.tambah', compact(
            'layout', 'setting', 'user', 'klasifikasiList', 'siswaList', 'guruList',
            'today', 'nextNumberPreview'
        ));
    }

    /**
     * AJAX Endpoint: Memberikan live preview nomor surat saat tanggal/klasifikasi berubah.
     */
    public function getNomorPreview(Request $request)
    {
        $kodeKlasifikasi = $request->input('kode_klasifikasi', 'TU');
        $tanggal = $request->input('tanggal_surat', Carbon::now()->toDateString());

        $preview = SuratKeluar::generateNextNumber($kodeKlasifikasi, $tanggal);

        return response()->json([
            'success' => true,
            'nomor_surat' => $preview['nomor_surat'],
            'no_urut' => $preview['no_urut'],
            'tahun' => $preview['tahun'],
            'bulan_romawi' => $preview['bulan_romawi']
        ]);
    }

    /**
     * Store a newly created Surat Keluar in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_klasifikasi' => 'required|string',
            'perihal' => 'required|string|max:255',
            'tujuan_surat' => 'required|string|max:255',
            'tanggal_surat' => 'required|date',
            'penandatangan' => 'nullable|string|max:150',
            'file_lampiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $user = Auth::user();
        $klasifikasiList = self::getKlasifikasiList();
        $kodeKlasifikasi = strtoupper(trim($request->kode_klasifikasi));
        $namaKlasifikasi = $klasifikasiList[$kodeKlasifikasi] ?? 'Surat Keluar';

        $fileLampiranName = null;
        if ($request->hasFile('file_lampiran')) {
            $file = $request->file('file_lampiran');
            $fileLampiranName = 'surat_keluar_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('lampiran_surat_keluar', $fileLampiranName, 'public');
        }

        // Database transaction untuk mengunci penomoran agar bebas bentrok
        $surat = DB::transaction(function () use ($request, $kodeKlasifikasi, $namaKlasifikasi, $fileLampiranName, $user) {
            $date = Carbon::parse($request->tanggal_surat);
            $tahun = (int) $date->format('Y');
            $bulanRomawi = SuratKeluar::getRomawiMonth((int) $date->format('n'));

            // Mengambil no urut terakhir dalam tahun dan klasifikasi tersebut dengan lock
            $maxUrut = SuratKeluar::where('tahun', $tahun)
                ->where('kode_klasifikasi', $kodeKlasifikasi)
                ->lockForUpdate()
                ->max('no_urut') ?? 0;
            $nextUrut = $maxUrut + 1;
            $formattedUrut = str_pad((string) $nextUrut, 3, '0', STR_PAD_LEFT);

            // Format nomor: 001/SK-SISWA/SMK-WI/X/2026
            $nomorSurat = "{$formattedUrut}/{$kodeKlasifikasi}/SMK-WI/{$bulanRomawi}/{$tahun}";

            return SuratKeluar::create([
                'nomor_surat' => $nomorSurat,
                'no_urut' => $nextUrut,
                'tahun' => $tahun,
                'kode_klasifikasi' => $kodeKlasifikasi,
                'nama_klasifikasi' => $namaKlasifikasi,
                'perihal' => $request->perihal,
                'tujuan_surat' => $request->tujuan_surat,
                'tanggal_surat' => $request->tanggal_surat,
                'id_siswa' => $request->id_siswa ?: null,
                'id_guru' => $request->id_guru ?: null,
                'penandatangan' => $request->penandatangan ?: 'M. Taufiqurrahman, S.Hum.',
                'file_lampiran' => $fileLampiranName,
                'keterangan' => $request->keterangan,
                'created_by' => $user->id_guru ?? null,
            ]);
        });

        return redirect()->route('admin.surat-keluar.index')
            ->with('success', "Nomor surat [{$surat->nomor_surat}] berhasil dibuat & dicatat di agenda.");
    }

    /**
     * Show the form for editing the specified Surat Keluar.
     */
    public function edit(string $id)
    {
        $layout = 'layout.app';
        $setting = Setting::find(1);
        $user = Auth::user();

        $surat = SuratKeluar::findOrFail($id);
        $klasifikasiList = self::getKlasifikasiList();

        // Jika klasifikasi surat lama sedang dinonaktifkan, tetap sertakan pada form edit agar tidak hilang
        if (!empty($surat->kode_klasifikasi) && !isset($klasifikasiList[$surat->kode_klasifikasi])) {
            $klasifikasiList[$surat->kode_klasifikasi] = ($surat->nama_klasifikasi ?: $surat->kode_klasifikasi) . ' (Nonaktif)';
        }

        $siswaList = Siswa::with('kelas')->orderBy('nama_siswa', 'asc')->get();
        $guruList = Guru::orderBy('nama_guru', 'asc')->get();

        return view('surat.surat_keluar.edit', compact(
            'layout', 'setting', 'user', 'surat', 'klasifikasiList', 'siswaList', 'guruList'
        ));
    }

    /**
     * Update the specified Surat Keluar in storage.
     */
    public function update(Request $request, string $id)
    {
        $surat = SuratKeluar::findOrFail($id);

        $request->validate([
            'perihal' => 'required|string|max:255',
            'tujuan_surat' => 'required|string|max:255',
            'tanggal_surat' => 'required|date',
            'penandatangan' => 'nullable|string|max:150',
            'file_lampiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $fileLampiranName = $surat->file_lampiran;
        if ($request->hasFile('file_lampiran')) {
            if ($surat->file_lampiran && Storage::disk('public')->exists('lampiran_surat_keluar/' . $surat->file_lampiran)) {
                Storage::disk('public')->delete('lampiran_surat_keluar/' . $surat->file_lampiran);
            }
            $file = $request->file('file_lampiran');
            $fileLampiranName = 'surat_keluar_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('lampiran_surat_keluar', $fileLampiranName, 'public');
        }

        $surat->update([
            'perihal' => $request->perihal,
            'tujuan_surat' => $request->tujuan_surat,
            'tanggal_surat' => $request->tanggal_surat,
            'id_siswa' => $request->id_siswa ?: null,
            'id_guru' => $request->id_guru ?: null,
            'penandatangan' => $request->penandatangan ?: 'M. Taufiqurrahman, S.Hum.',
            'file_lampiran' => $fileLampiranName,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('admin.surat-keluar.index')
            ->with('success', "Data surat [{$surat->nomor_surat}] berhasil diperbarui.");
    }

    /**
     * Remove the specified Surat Keluar from storage.
     */
    public function destroy(string $id)
    {
        $surat = SuratKeluar::findOrFail($id);
        $nomorSurat = $surat->nomor_surat;

        if ($surat->file_lampiran && Storage::disk('public')->exists('lampiran_surat_keluar/' . $surat->file_lampiran)) {
            Storage::disk('public')->delete('lampiran_surat_keluar/' . $surat->file_lampiran);
        }

        $surat->delete();

        return redirect()->route('admin.surat-keluar.index')
            ->with('success', "Data surat [{$nomorSurat}] berhasil dihapus dari buku agenda.");
    }
}
