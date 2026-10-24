<?php

namespace App\Http\Controllers\Kejuruan;

use App\Http\Controllers\Controller;
use App\Models\CapaianSiswa;
use App\Models\Kelas;
use App\Models\Kompetensi;
use App\Models\Setting;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CapaianSiswaController extends Controller
{
    /**
     * Tampilkan buku induk capaian kompetensi siswa (Praktik, UKK, TKK, Sertifikat).
     */
    public function index(Request $request)
    {
        $layout = 'layout.app';
        $setting = Setting::find(1);
        $user = Auth::user();

        $query = CapaianSiswa::forUserJurusan($user)
            ->with(['siswa.kelas', 'kompetensi.jurusan', 'penilai'])
            ->orderBy('tanggal', 'desc')
            ->orderBy('id_capaian', 'desc');

        if ($request->filled('kelas_id')) {
            $query->whereHas('siswa', function ($q) use ($request) {
                $q->where('id_kelas', $request->kelas_id);
            });
        }

        if ($request->filled('kompetensi_id')) {
            $query->where('id_kompetensi', $request->kompetensi_id);
        }

        if ($request->filled('jenis_capaian')) {
            $query->where('jenis_capaian', $request->jenis_capaian);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $keyword = trim($request->q);
            $query->whereHas('siswa', function ($q) use ($keyword) {
                $q->where('nama_siswa', 'like', "%{$keyword}%")
                    ->orWhere('nis', 'like', "%{$keyword}%")
                    ->orWhere('nisn', 'like', "%{$keyword}%");
            });
        }

        $capaians = $query->get();

        $kelas = Kelas::forUserJurusan($user)->orderBy('nama_kelas', 'asc')->get();
        $kompetensis = Kompetensi::forUserJurusan($user)->with('jurusan')->orderBy('kode_kompetensi', 'asc')->get();

        // Statistik ringkas
        $totalCapaian = CapaianSiswa::forUserJurusan($user)->count();
        $totalKompeten = CapaianSiswa::forUserJurusan($user)->where('status', 'kompeten')->count();
        $totalBelum = $totalCapaian - $totalKompeten;

        return view('kejuruan.capaian_siswa.index', compact(
            'layout', 'setting', 'user', 'capaians', 'kelas', 'kompetensis',
            'totalCapaian', 'totalKompeten', 'totalBelum'
        ));
    }

    /**
     * Tampilkan transkrip portofolio capaian kejuruan resmi per siswa.
     */
    public function transkrip(Request $request, string $idSiswa)
    {
        $layout = 'layout.app';
        $setting = Setting::find(1);
        $user = Auth::user();

        if (!$user->hasRole('admin')) {
            $siswa = Siswa::forUserJurusan($user)->with(['kelas.jurusan', 'jurusan'])->findOrFail($idSiswa);
        } else {
            $siswa = Siswa::with(['kelas.jurusan', 'jurusan'])->findOrFail($idSiswa);
        }

        // Ambil ID jurusan siswa (dari kolom siswa->id_jurusan atau relasi kelas->jurusan->id_jurusan)
        $idJurusan = $siswa->id_jurusan;
        if (!$idJurusan && $siswa->kelas && $siswa->kelas->jurusan) {
            $idJurusan = $siswa->kelas->jurusan->id_jurusan;
        }

        // Ambil kompetensi hanya sesuai jurusan siswa
        $kompetensiQuery = Kompetensi::orderBy('tingkat', 'asc')
            ->orderBy('semester', 'asc')
            ->orderBy('kode_kompetensi', 'asc');

        if ($idJurusan) {
            $kompetensiQuery->where('id_jurusan', $idJurusan);
        }

        $kompetensis = $kompetensiQuery->get();

        // Ambil semua capaian siswa yang sudah tercatat
        $capaians = CapaianSiswa::where('id_siswa', $idSiswa)
            ->with(['kompetensi', 'penilai'])
            ->get()
            ->keyBy('id_kompetensi');

        $totalKompetensi = $kompetensis->count();
        $kompetenCount = 0;
        foreach ($kompetensis as $k) {
            if (isset($capaians[$k->id_kompetensi]) && $capaians[$k->id_kompetensi]->status === 'kompeten') {
                $kompetenCount++;
            }
        }
        $persentase = $totalKompetensi > 0 ? round(($kompetenCount / $totalKompetensi) * 100, 1) : 0;

        $isPrint = $request->boolean('print');

        return view('kejuruan.capaian_siswa.transkrip', compact(
            'layout', 'setting', 'user', 'siswa', 'kompetensis', 'capaians',
            'totalKompetensi', 'kompetenCount', 'persentase', 'isPrint'
        ));
    }
}
