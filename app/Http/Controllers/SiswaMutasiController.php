<?php

namespace App\Http\Controllers;

use App\Exports\SiswaMutasiExport;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\Level;
use App\Models\Setting;
use App\Models\Siswa;
use App\Models\SiswaMutasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class SiswaMutasiController extends Controller
{
    private function canManageMutasi(?\App\Models\Guru $user): bool
    {
        return $user && $user->hasAnyRole(['admin', 'tata_usaha', 'kurikulum', 'kesiswaan']);
    }

    private function authorizeManage(): void
    {
        $user = Auth::user();
        if (!$this->canManageMutasi($user)) {
            abort(403, 'Akses ditolak: Anda tidak memiliki hak akses untuk mengelola data siswa mutasi.');
        }
    }

    public function index(Request $request)
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        $jurusan = Jurusan::orderBy('nama_jurusan', 'asc')->get();

        $query = SiswaMutasi::with(['kelas', 'jurusan', 'level', 'orangTua']);

        if ($request->filled('jenis_mutasi')) {
            $query->where('jenis_mutasi', $request->jenis_mutasi);
        }

        if ($request->filled('id_jurusan')) {
            $query->where('id_jurusan', $request->id_jurusan);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_mutasi', $request->tahun);
        }

        $mutasi = $query->orderBy('tanggal_mutasi', 'desc')->orderBy('nama_siswa', 'asc')->get();

        // Data statistik ringkas
        $totalMutasi = SiswaMutasi::count();
        $totalPindah = SiswaMutasi::where('jenis_mutasi', 'Pindah Sekolah')->count();
        $totalKeluar = SiswaMutasi::where('jenis_mutasi', 'Mengundurkan Diri')->count();
        $totalLainnya = SiswaMutasi::whereNotIn('jenis_mutasi', ['Pindah Sekolah', 'Mengundurkan Diri'])->count();

        // Daftar tahun mutasi unik untuk filter
        $tahunList = SiswaMutasi::selectRaw('YEAR(tanggal_mutasi) as tahun')
            ->distinct()
            ->whereNotNull('tanggal_mutasi')
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        return view('dataSiswaMutasi.index', compact(
            'layout',
            'setting',
            'user',
            'mutasi',
            'jurusan',
            'tahunList',
            'totalMutasi',
            'totalPindah',
            'totalKeluar',
            'totalLainnya'
        ));
    }

    public function store(Request $request, $id_siswa)
    {
        $this->authorizeManage();

        $request->validate([
            'tanggal_mutasi' => 'required|date',
            'jenis_mutasi' => 'required|string|max:100',
            'sekolah_tujuan' => 'nullable|string|max:255',
            'alasan_mutasi' => 'nullable|string',
        ]);

        $siswa = Siswa::with(['kelas', 'jurusan', 'level'])->findOrFail($id_siswa);
        $user = Auth::user();
        $dicatatOleh = $user ? ($user->nama_guru ?? $user->nama ?? 'Admin') : 'Admin';

        DB::transaction(function () use ($siswa, $request, $dicatatOleh) {
            SiswaMutasi::create([
                'id_siswa_asal' => $siswa->id_siswa,
                'tanggal_mutasi' => $request->tanggal_mutasi,
                'jenis_mutasi' => $request->jenis_mutasi,
                'sekolah_tujuan' => $request->sekolah_tujuan,
                'alasan_mutasi' => $request->alasan_mutasi,
                'dicatat_oleh' => $dicatatOleh,

                // Snapshot Akademik
                'id_level' => $siswa->id_level,
                'id_kelas' => $siswa->id_kelas,
                'id_jurusan' => $siswa->id_jurusan,
                'kelas_terakhir' => $siswa->kelas->nama_kelas ?? null,
                'jurusan_terakhir' => $siswa->jurusan->nama_jurusan ?? null,
                'level_terakhir' => $siswa->level->kode_level ?? null,

                // Identitas Siswa
                'nis' => $siswa->nis,
                'nisn' => $siswa->nisn,
                'password' => $siswa->password,
                'nama_siswa' => $siswa->nama_siswa,
                'foto' => $siswa->foto,
                'tmpt_lahir' => $siswa->tmpt_lahir,
                'tgl_lahir' => $siswa->tgl_lahir,
                'agama' => $siswa->agama,
                'jenis_kelamin' => $siswa->jenis_kelamin,
                'no_hp' => $siswa->no_hp,
                'no_tlpn' => $siswa->no_tlpn,
                'email' => $siswa->email,
                'alamat' => $siswa->alamat,
                'rt' => $siswa->rt,
                'rw' => $siswa->rw,
                'no_rumah' => $siswa->no_rumah,
                'kel' => $siswa->kel,
                'kec' => $siswa->kec,
                'prov' => $siswa->prov,
                'kota' => $siswa->kota,

                // Orang Tua
                'id_orang_tua' => $siswa->id_orang_tua,
                'nik_ayah' => $siswa->nik_ayah,
                'nama_ayah' => $siswa->nama_ayah,
                'tmpt_lahir_ayah' => $siswa->tmpt_lahir_ayah,
                'tgl_lahir_ayah' => $siswa->tgl_lahir_ayah,
                'pendidikan_ayah' => $siswa->pendidikan_ayah,
                'pekerjaan_ayah' => $siswa->pekerjaan_ayah,
                'penghasilan_ayah' => $siswa->penghasilan_ayah,
                'no_hp_ayah' => $siswa->no_hp_ayah,

                'nik_ibu' => $siswa->nik_ibu,
                'nama_ibu' => $siswa->nama_ibu,
                'tmpt_lahir_ibu' => $siswa->tmpt_lahir_ibu,
                'tgl_lahir_ibu' => $siswa->tgl_lahir_ibu,
                'pendidikan_ibu' => $siswa->pendidikan_ibu,
                'pekerjaan_ibu' => $siswa->pekerjaan_ibu,
                'penghasilan_ibu' => $siswa->penghasilan_ibu,
                'no_hp_ibu' => $siswa->no_hp_ibu,

                'nik_wali' => $siswa->nik_wali,
                'nama_wali' => $siswa->nama_wali,
                'tmpt_lahir_wali' => $siswa->tmpt_lahir_wali,
                'tgl_lahir_wali' => $siswa->tgl_lahir_wali,
                'pendidikan_wali' => $siswa->pendidikan_wali,
                'pekerjaan_wali' => $siswa->pekerjaan_wali,
                'penghasilan_wali' => $siswa->penghasilan_wali,
                'no_hp_wali' => $siswa->no_hp_wali,
            ]);

            // Hapus token FCM siswa jika ada agar tidak menerima push notifikasi lagi
            \App\Models\SiswaFcmToken::where('id_siswa', $siswa->id_siswa)->delete();

            // Hapus siswa dari tabel siswa aktif (foto tetap disimpan di storage untuk arsip)
            $siswa->delete();
        });

        return redirect('/admin/siswaMutasi')->with('success', 'Siswa ' . $siswa->nama_siswa . ' berhasil dimutasi ke arsip Siswa Mutasi.');
    }

    public function show($id_mutasi)
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        $detail = SiswaMutasi::with(['kelas', 'jurusan', 'level', 'orangTua'])->findOrFail($id_mutasi);

        return view('dataSiswaMutasi.detail_siswa_mutasi', compact('layout', 'setting', 'user', 'detail'));
    }

    public function edit($id_mutasi)
    {
        $this->authorizeManage();

        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        $edit = SiswaMutasi::findOrFail($id_mutasi);

        return view('dataSiswaMutasi.edit_siswa_mutasi', compact('layout', 'setting', 'user', 'edit'));
    }

    public function update(Request $request, $id_mutasi)
    {
        $this->authorizeManage();

        $request->validate([
            'tanggal_mutasi' => 'required|date',
            'jenis_mutasi' => 'required|string|max:100',
            'sekolah_tujuan' => 'nullable|string|max:255',
            'alasan_mutasi' => 'nullable|string',
        ]);

        $mutasi = SiswaMutasi::findOrFail($id_mutasi);
        $mutasi->update([
            'tanggal_mutasi' => $request->tanggal_mutasi,
            'jenis_mutasi' => $request->jenis_mutasi,
            'sekolah_tujuan' => $request->sekolah_tujuan,
            'alasan_mutasi' => $request->alasan_mutasi,
        ]);

        return redirect('/admin/siswaMutasi')->with('success', 'Data mutasi siswa berhasil diperbarui.');
    }

    public function restore($id_mutasi)
    {
        $this->authorizeManage();

        $mutasi = SiswaMutasi::findOrFail($id_mutasi);

        // Periksa apakah NIS sudah dipakai oleh siswa aktif lain
        if (!empty($mutasi->nis) && Siswa::where('nis', $mutasi->nis)->exists()) {
            return redirect()->back()->with('error', 'Gagal memulihkan siswa: NIS ' . $mutasi->nis . ' sudah digunakan oleh siswa aktif lain.');
        }

        DB::transaction(function () use ($mutasi) {
            $siswaData = [
                'id_orang_tua' => $mutasi->id_orang_tua,
                'nis' => $mutasi->nis ?? '-',
                'nisn' => $mutasi->nisn ?? '-',
                'password' => $mutasi->password ?? bcrypt('siswa123'),
                'nama_siswa' => $mutasi->nama_siswa,
                'id_level' => $mutasi->id_level,
                'id_kelas' => $mutasi->id_kelas,
                'id_jurusan' => $mutasi->id_jurusan,
                'foto' => $mutasi->foto ?? 'avatar.jpg',
                'tmpt_lahir' => $mutasi->tmpt_lahir ?? '-',
                'tgl_lahir' => $mutasi->tgl_lahir ?? date('Y-m-d'),
                'agama' => $mutasi->agama ?? '-',
                'jenis_kelamin' => $mutasi->jenis_kelamin ?? '-',
                'no_hp' => $mutasi->no_hp ?? '-',
                'no_tlpn' => $mutasi->no_tlpn ?? '-',
                'email' => $mutasi->email ?? '-',
                'alamat' => $mutasi->alamat ?? '-',
                'rt' => $mutasi->rt ?? '-',
                'rw' => $mutasi->rw ?? '-',
                'no_rumah' => $mutasi->no_rumah ?? '-',
                'kel' => $mutasi->kel ?? '-',
                'kec' => $mutasi->kec ?? '-',
                'prov' => $mutasi->prov ?? '-',
                'kota' => $mutasi->kota ?? '-',
                'nik_ayah' => $mutasi->nik_ayah ?? '-',
                'nama_ayah' => $mutasi->nama_ayah ?? '-',
                'tmpt_lahir_ayah' => $mutasi->tmpt_lahir_ayah ?? '-',
                'tgl_lahir_ayah' => $mutasi->tgl_lahir_ayah ?? '-',
                'pendidikan_ayah' => $mutasi->pendidikan_ayah ?? '-',
                'pekerjaan_ayah' => $mutasi->pekerjaan_ayah ?? '-',
                'penghasilan_ayah' => $mutasi->penghasilan_ayah ?? '-',
                'no_hp_ayah' => $mutasi->no_hp_ayah,
                'nik_ibu' => $mutasi->nik_ibu ?? '-',
                'nama_ibu' => $mutasi->nama_ibu ?? '-',
                'tmpt_lahir_ibu' => $mutasi->tmpt_lahir_ibu ?? '-',
                'tgl_lahir_ibu' => $mutasi->tgl_lahir_ibu ?? '-',
                'pendidikan_ibu' => $mutasi->pendidikan_ibu ?? '-',
                'pekerjaan_ibu' => $mutasi->pekerjaan_ibu ?? '-',
                'penghasilan_ibu' => $mutasi->penghasilan_ibu ?? '-',
                'no_hp_ibu' => $mutasi->no_hp_ibu,
                'nik_wali' => $mutasi->nik_wali ?? '-',
                'nama_wali' => $mutasi->nama_wali ?? '-',
                'tmpt_lahir_wali' => $mutasi->tmpt_lahir_wali ?? '-',
                'tgl_lahir_wali' => $mutasi->tgl_lahir_wali ?? '-',
                'pendidikan_wali' => $mutasi->pendidikan_wali ?? '-',
                'pekerjaan_wali' => $mutasi->pekerjaan_wali ?? '-',
                'penghasilan_wali' => $mutasi->penghasilan_wali ?? '-',
                'no_hp_wali' => $mutasi->no_hp_wali,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Coba pulihkan dengan ID asal jika belum terpakai
            if ($mutasi->id_siswa_asal && !Siswa::where('id_siswa', $mutasi->id_siswa_asal)->exists()) {
                $siswaData['id_siswa'] = $mutasi->id_siswa_asal;
            }

            DB::table('siswa')->insert($siswaData);

            // Hapus dari data mutasi
            $mutasi->delete();
        });

        return redirect('/admin/siswa')->with('success', 'Siswa ' . $mutasi->nama_siswa . ' berhasil dikembalikan ke Data Siswa Aktif.');
    }

    public function destroy($id_mutasi)
    {
        $this->authorizeManage();

        $mutasi = SiswaMutasi::findOrFail($id_mutasi);
        $namaSiswa = $mutasi->nama_siswa;

        $isDefaultAvatar = in_array($mutasi->foto ?? '', ['avatar.jpg', 'avatar.png', 'avatar_male.jpg', 'avatar_male.png', 'avatar_female.jpg', 'avatar_female.png']);
        if ($mutasi->foto && !$isDefaultAvatar) {
            // Periksa apakah foto masih dipakai oleh siswa lain atau alumni sebelum dihapus
            $usedByOther = Siswa::where('foto', $mutasi->foto)->exists() || SiswaMutasi::where('id_mutasi', '!=', $id_mutasi)->where('foto', $mutasi->foto)->exists();
            if (!$usedByOther) {
                Storage::disk('public')->delete('foto-siswa/' . $mutasi->foto);
                if (file_exists(public_path('storage/foto-siswa/' . $mutasi->foto))) {
                    @unlink(public_path('storage/foto-siswa/' . $mutasi->foto));
                }
            }
        }

        $mutasi->delete();

        return redirect('/admin/siswaMutasi')->with('success', 'Data mutasi siswa ' . $namaSiswa . ' berhasil dihapus permanen.');
    }

    public function download(Request $request)
    {
        $filters = [
            'jenis_mutasi' => $request->jenis_mutasi,
            'id_jurusan' => $request->id_jurusan,
            'tahun' => $request->tahun,
        ];

        return Excel::download(new SiswaMutasiExport($filters), 'data_siswa_mutasi.xlsx');
    }
}
