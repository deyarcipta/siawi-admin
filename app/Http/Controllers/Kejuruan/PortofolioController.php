<?php

namespace App\Http\Controllers\Kejuruan;

use App\Http\Controllers\Controller;
use App\Models\CapaianSiswa;
use App\Models\Kelas;
use App\Models\Kompetensi;
use App\Models\PortofolioSiswa;
use App\Models\Setting;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PortofolioController extends Controller
{
    /**
     * Daftar Portofolio & Sertifikat Siswa.
     */
    public function index(Request $request)
    {
        $layout = 'layout.app';
        $setting = Setting::find(1);
        $user = Auth::user();

        $query = PortofolioSiswa::forUserJurusan($user)
            ->with(['siswa.kelas', 'kompetensi', 'verifikator'])
            ->latest('id_portofolio');

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('status_verifikasi')) {
            $query->where('status_verifikasi', $request->status_verifikasi);
        }

        if ($request->filled('id_kelas')) {
            $query->whereHas('siswa', function ($q) use ($request) {
                $q->where('id_kelas', $request->id_kelas);
            });
        }

        $portofolios = $query->get();

        $kelas = Kelas::forUserJurusan($user)->orderBy('nama_kelas', 'asc')->get();

        // Statistik sesuai hak akses jurusan
        $totalPorto = PortofolioSiswa::forUserJurusan($user)->count();
        $totalSertifikat = PortofolioSiswa::forUserJurusan($user)->where('kategori', 'sertifikat')->count();
        $totalKarya = PortofolioSiswa::forUserJurusan($user)->where('kategori', 'proyek_karya')->count();
        $totalMenunggu = PortofolioSiswa::forUserJurusan($user)->where('status_verifikasi', 'menunggu')->count();

        return view('kejuruan.portofolio.index', compact(
            'layout', 'setting', 'user', 'portofolios', 'kelas',
            'totalPorto', 'totalSertifikat', 'totalKarya', 'totalMenunggu'
        ));
    }

    /**
     * Form unggah sertifikat / hasil karya baru.
     */
    public function create()
    {
        $layout = 'layout.app';
        $setting = Setting::find(1);
        $user = Auth::user();

        $kompetensis = Kompetensi::forUserJurusan($user)->orderBy('kode_kompetensi', 'asc')->get();
        $kelas = Kelas::forUserJurusan($user)->orderBy('nama_kelas', 'asc')->get();
        $siswas = Siswa::forUserJurusan($user)->orderBy('nama_siswa', 'asc')->get();

        return view('kejuruan.portofolio.create', compact('layout', 'setting', 'user', 'kompetensis', 'kelas', 'siswas'));
    }

    /**
     * Simpan file karya/sertifikat dengan validasi tipe aman dan batas ukuran.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_siswa' => 'required|exists:siswa,id_siswa',
            'judul' => 'required|string|max:255',
            'kategori' => 'required|in:sertifikat,proyek_karya,prestasi_lomba',
            'tanggal_kegiatan' => 'required|date',
            'file_dokumen' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // Batas 5MB, format aman
            'tautan_karya' => 'nullable|url|max:255',
        ], [
            'file_dokumen.mimes' => 'Format file bukti harus berupa PDF, JPG, JPEG, atau PNG.',
            'file_dokumen.max' => 'Ukuran file tidak boleh melebihi 5 MB demi keamanan sistem.',
        ]);

        $user = Auth::user();

        // Pastikan siswa yang dipilih berada di bawah binaan jurusan user
        if (!$user->hasRole('admin')) {
            Siswa::forUserJurusan($user)->findOrFail($request->id_siswa);
        }

        $filePath = null;
        if ($request->hasFile('file_dokumen')) {
            $filePath = $request->file('file_dokumen')->store('portofolio_siswa', 'public');
        }

        // Jika yang mengunggah adalah Guru / Kaprog / Admin, bisa langsung disetujui
        $isTeacher = $user->hasAnyRole(['admin', 'kaprog', 'guru_jurusan', 'guru']);

        DB::beginTransaction();
        try {
            $porto = PortofolioSiswa::create([
                'id_siswa' => $request->id_siswa,
                'id_kompetensi' => $request->id_kompetensi,
                'kategori' => $request->kategori,
                'judul' => $request->judul,
                'penyelenggara' => $request->penyelenggara,
                'tanggal_kegiatan' => $request->tanggal_kegiatan,
                'file_dokumen' => $filePath,
                'tautan_karya' => $request->tautan_karya,
                'deskripsi' => $request->deskripsi,
                'status_verifikasi' => $isTeacher ? 'disetujui' : 'menunggu',
                'id_verifikator' => $isTeacher ? $user->id_guru : null,
                'catatan_verifikasi' => $isTeacher ? 'Diverifikasi langsung oleh guru' : null,
            ]);

            // Jika langsung disetujui, alirkan ke capaian_siswa
            if ($isTeacher) {
                CapaianSiswa::create([
                    'id_siswa' => $request->id_siswa,
                    'id_kompetensi' => $request->id_kompetensi,
                    'jenis_capaian' => 'sertifikat',
                    'id_referensi' => $porto->id_portofolio,
                    'nilai' => 100.00,
                    'kkm' => 75.00,
                    'status' => 'kompeten',
                    'tanggal' => $request->tanggal_kegiatan,
                    'id_penilai' => $user->id_guru,
                    'file_bukti' => $filePath,
                    'catatan' => ucfirst($request->kategori) . ': ' . $request->judul . ($request->penyelenggara ? ' (' . $request->penyelenggara . ')' : ''),
                ]);
            }

            DB::commit();

            return redirect('/admin/kejuruan/portofolio')
                ->with('success', 'Portofolio / Sertifikat ' . $porto->judul . ' berhasil diunggah.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal mengunggah portofolio: ' . $e->getMessage());
        }
    }

    /**
     * Verifikasi (Persetujuan / Penolakan) Portofolio oleh Guru/Kaprog.
     */
    public function verifikasi(Request $request, $id)
    {
        $request->validate([
            'status_verifikasi' => 'required|in:disetujui,ditolak',
            'catatan_verifikasi' => 'nullable|string',
        ]);

        $user = Auth::user();
        $porto = PortofolioSiswa::forUserJurusan($user)->with('siswa')->findOrFail($id);

        DB::beginTransaction();
        try {
            $porto->update([
                'status_verifikasi' => $request->status_verifikasi,
                'id_verifikator' => $user->id_guru,
                'catatan_verifikasi' => $request->catatan_verifikasi,
            ]);

            if ($request->status_verifikasi === 'disetujui') {
                CapaianSiswa::updateOrCreate([
                    'id_siswa' => $porto->id_siswa,
                    'jenis_capaian' => 'sertifikat',
                    'id_referensi' => $porto->id_portofolio,
                ], [
                    'id_kompetensi' => $porto->id_kompetensi,
                    'nilai' => 100.00,
                    'kkm' => 75.00,
                    'status' => 'kompeten',
                    'tanggal' => $porto->tanggal_kegiatan,
                    'id_penilai' => $user->id_guru,
                    'file_bukti' => $porto->file_dokumen,
                    'catatan' => ucfirst($porto->kategori) . ': ' . $porto->judul . ($porto->penyelenggara ? ' (' . $porto->penyelenggara . ')' : ''),
                ]);
            }

            DB::commit();

            return back()->with('success', 'Status verifikasi portofolio berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memverifikasi: ' . $e->getMessage());
        }
    }

    /**
     * Hapus portofolio.
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $porto = PortofolioSiswa::forUserJurusan($user)->findOrFail($id);

        if ($porto->file_dokumen && Storage::disk('public')->exists($porto->file_dokumen)) {
            Storage::disk('public')->delete($porto->file_dokumen);
        }

        CapaianSiswa::where('jenis_capaian', 'sertifikat')->where('id_referensi', $id)->delete();
        $porto->delete();

        return back()->with('success', 'Portofolio berhasil dihapus.');
    }
}
