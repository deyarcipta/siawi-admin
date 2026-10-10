<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SiswaExport;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Level;
use App\Models\Jurusan;
use App\Models\Alumni;
use App\Models\Absensi; 
use App\Models\PointSiswa;
use App\Models\SuratPeringatan;
use App\Models\Dokumen;
use App\Models\SiswaPkl;
use App\Models\Rapot;
use App\Models\Setting;
use DB;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    
    private function canManageMasterSiswa(?\App\Models\Guru $user): bool
    {
        return $user && $user->hasAnyRole(['admin', 'tata_usaha', 'kurikulum']);
    }

    private function canEditSiswa(?\App\Models\Guru $user, Siswa $siswa): bool
    {
        if ($this->canManageMasterSiswa($user)) {
            return true;
        }

        if ($user && $user->hasRole('wali_kelas')) {
            $walasKelasIds = $user->getKelasWaliIds();
            return in_array($siswa->id_kelas, $walasKelasIds);
        }

        return false;
    }

    public function index()
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();

        $walasKelasIds = ($user && method_exists($user, 'getKelasWaliIds')) ? $user->getKelasWaliIds() : [];
        $isWaliKelasUser = $user && !empty($walasKelasIds) && (
            $user->isWaliKelasStrict() || 
            in_array('wali_kelas', $user->roles_list ?? []) || 
            $user->hasRole('wali_kelas')
        );

        $query = Siswa::with('kelas');

        if ($isWaliKelasUser) {
            $idsList = implode(',', array_map('intval', $walasKelasIds));
            $query->orderByRaw("CASE WHEN id_kelas IN ($idsList) THEN 0 ELSE 1 END")
                  ->orderBy('nama_siswa', 'asc');
        } else {
            $query->orderBy('nama_siswa', 'asc');
        }

        $siswa = $query->get();
        return view('dataSiswa.data_siswa', compact('layout', 'siswa', 'setting', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        if (!$this->canManageMasterSiswa($user)) {
            abort(403, 'Akses ditolak: Hanya Admin, Tata Usaha, dan Kurikulum yang dapat menambah data siswa.');
        }

        $layout = 'layout.app';
        $level = Level::orderBy('kode_level', 'asc')->get();
        $jurusan = Jurusan::orderBy('nama_jurusan', 'asc')->get();
        $kelas = Kelas::orderBy('nama_kelas', 'asc')->get();
        $setting = Setting::find('1');
        return view('dataSiswa.tambah_siswa', compact('layout','level','jurusan','kelas','setting','user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$this->canManageMasterSiswa($user)) {
            abort(403, 'Akses ditolak: Hanya Admin, Tata Usaha, dan Kurikulum yang dapat menambah data siswa.');
        }

        // dd($request->all());
        $request->validate([
            'nis' => 'required',
            // 'nisn' => 'required',
            // 'password' => 'required',
            'nama_siswa' => 'required',
            'kode_level' => 'required',
            'kode_kelas' => 'required',
            'kode_jurusan' => 'required',
            // 'tmpt_lahir' => 'required',
            // 'tgl_lahir' => 'required',
            // 'agama' => 'required',
            // 'jenis_kelamin' => 'required',
            // 'no_hp' => 'required',
            // 'email' => 'required',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp,jfif,heic,heif,PNG,JPG,JPEG,WEBP|max:10240',
            // 'alamat' => 'required',
            // 'rt' => 'required',
            // 'rw' => 'required',
            // 'no_rumah' => 'required',
            // 'kel' => 'required',
            // 'kec' => 'required',
            // 'prov' => 'required',
            // 'kota' => 'required',
        ]);

        $nama_file = 'avatar.jpg';
        // Periksa apakah file diunggah
        if ($request->hasFile('foto')) {
            if (!Storage::disk('public')->exists('foto-siswa')) {
                Storage::disk('public')->makeDirectory('foto-siswa');
            }
            $file = $request->file('foto');
            $nama_file = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $tujuan_upload = 'foto-siswa';
            $file->storeAs($tujuan_upload, $nama_file, 'public');
        }

        $siswa = Siswa::create([
            'nis' => $request->nis,
            'nisn' => $request->nisn ?? '-',
            'nama_siswa' => $request->nama_siswa,
            'password' => $request->password ?? 'siswa123',
            'id_level' => $request->kode_level,
            'id_kelas' => $request->kode_kelas,
            'id_jurusan' => $request->kode_jurusan,
            'foto' => $nama_file,
            'tmpt_lahir' => $request->tmpt_lahir ?? '-',
            'tgl_lahir' => $request->tgl_lahir ?? '-',
            'agama' => $request->agama ?? '-',
            'jenis_kelamin' => $request->jenis_kelamin ?? '-',
            'no_hp' => $request->no_hp ?? '-',
            'no_tlpn' => $request->no_tlpn ?? '-',
            'email' => $request->email ?? '-',
            'alamat' => $request->alamat ?? '-',
            'rt' => $request->rt ?? '-',
            'rw' => $request->rw ?? '-',
            'no_rumah' => $request->no_rumah ?? '-',
            'kel' => $request->kel ?? '-',
            'kec' => $request->kec ?? '-',
            'kota' => $request->kota ?? '-',
            'prov' => $request->prov ?? '-',
            'nik_ayah' => $request->nik_ayah ?? '-',
            'nama_ayah' => $request->nama_ayah ?? '-',
            'tmpt_lahir_ayah' => $request->tmpt_lahir_ayah ?? '-',
            'tgl_lahir_ayah' => $request->tgl_lahir_ayah ?? '-',
            'pendidikan_ayah' => $request->pendidikan_ayah ?? '-',
            'pekerjaan_ayah' => $request->pekerjaan_ayah ?? '-',
            'penghasilan_ayah' => $request->penghasilan_ayah ?? '-',
            'nik_ibu' => $request->nik_ibu ?? '-',
            'nama_ibu' => $request->nama_ibu ?? '-',
            'tmpt_lahir_ibu' => $request->tmpt_lahir_ibu ?? '-',
            'tgl_lahir_ibu' => $request->tgl_lahir_ibu ?? '-',
            'pendidikan_ibu' => $request->pendidikan_ibu ?? '-',
            'pekerjaan_ibu' => $request->pekerjaan_ibu ?? '-',
            'penghasilan_ibu' => $request->penghasilan_ibu ?? '-',
            'nik_wali' => $request->nik_wali ?? '-',
            'nama_wali' => $request->nama_wali ?? '-',
            'tmpt_lahir_wali' => $request->tmpt_lahir_wali ?? '-',
            'tgl_lahir_wali' => $request->tgl_lahir_wali ?? '-',
            'pendidikan_wali' => $request->pendidikan_wali ?? '-',
            'pekerjaan_wali' => $request->pekerjaan_wali ?? '-',
            'penghasilan_wali' => $request->penghasilan_wali ?? '-',
        ]);

        return redirect('/admin/siswa');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id_siswa)
    {
        $layout = 'layout.app';
        $detail = Siswa::with(['kelas', 'jurusan', 'siswaPkl.perusahaan'])->findOrFail($id_siswa);
        $setting = Setting::find('1');
        $user = Auth::user();
        return view('dataSiswa.detail_siswa', compact('detail','layout','setting','user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id_siswa)
    {
        $user = Auth::user();
        $edit = Siswa::findOrFail($id_siswa);

        if (!$this->canEditSiswa($user, $edit)) {
            abort(403, 'Akses ditolak: Anda hanya berhak mengedit data siswa pada kelas binaan Anda.');
        }

        $layout = 'layout.app';
        session(['old_foto' => $edit->foto]);
        $level = Level::orderBy('kode_level', 'asc')->get();
        $jurusan = Jurusan::orderBy('nama_jurusan', 'asc')->get();
        $kelas = Kelas::orderBy('nama_kelas', 'asc')->get();
        $setting = Setting::find('1');
        return view('dataSiswa.edit_siswa', compact('layout','edit','level','jurusan','kelas','setting','user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id_siswa)
    {
        $user = Auth::user();
        $siswa = Siswa::findOrFail($id_siswa);

        if (!$this->canEditSiswa($user, $siswa)) {
            abort(403, 'Akses ditolak: Anda hanya berhak mengedit data siswa pada kelas binaan Anda.');
        }

        if (!$this->canManageMasterSiswa($user)) {
            $request->merge(['kode_kelas' => $siswa->id_kelas]);
        }

        $request->validate([
            'nis' => 'required',
            'nisn' => 'required',
            // 'rfid' => 'required',
            // 'password' => 'nullable|string|min:6',
            'nama_siswa' => 'required',
            'kode_level' => 'required',
            'kode_kelas' => 'required',
            'kode_jurusan' => 'required',
            'tmpt_lahir' => 'required',
            // 'tgl_lahir' => 'required',
            'agama' => 'required',
            'jenis_kelamin' => 'required',
            'no_hp' => 'required',
            'email' => 'required',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp,jfif,heic,heif,PNG,JPG,JPEG,WEBP|max:10240',
            'alamat' => 'required',
            'rt' => 'required',
            'rw' => 'required',
            'no_rumah' => 'required',
            'kel' => 'required',
            'kec' => 'required',
            'prov' => 'required',
            'kota' => 'required',
        ]);

        $siswa = Siswa::findOrFail($id_siswa);

        // Periksa apakah file diunggah
        if ($request->hasFile('foto')) {
            if (!Storage::disk('public')->exists('foto-siswa')) {
                Storage::disk('public')->makeDirectory('foto-siswa');
            }
            if ($siswa->foto && $siswa->foto != 'avatar.jpg') {
                // Hapus foto lama dari penyimpanan jika bukan "avatar.jpg"
                Storage::disk('public')->delete('foto-siswa/' . $siswa->foto);
                if (file_exists(public_path('storage/foto-siswa/' . $siswa->foto))) {
                    @unlink(public_path('storage/foto-siswa/' . $siswa->foto));
                }
            }
            $file = $request->file('foto');
            $nama_file = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $tujuan_upload = 'foto-siswa';
            $file->storeAs($tujuan_upload, $nama_file, 'public');
            $siswa->foto = $nama_file;
        } else {
            // Pertahankan foto yang sudah ada jika tidak mengunggah file baru
            $siswa->foto = $siswa->foto ?? session('old_foto') ?? 'avatar.jpg';
        }

        // Hapus nama file dari session setelah digunakan
        session()->forget('old_foto');

        // if ($request->filled('password')) {
        //     $siswa->password = Hash::make($request->password);
        // }

        Siswa::where('id_siswa', $id_siswa)->update([
            'nis' => $request->nis,
            'nisn' => $request->nisn,
            // 'rfid' => $request->rfid,
            'nama_siswa' => $request->nama_siswa,
            // 'password' => $request->password,
            'id_level' => $request->kode_level,
            'id_kelas' => $request->kode_kelas,
            'id_jurusan' => $request->kode_jurusan,
            'foto' => $siswa->foto,
            'tmpt_lahir' => $request->tmpt_lahir,
            'tgl_lahir' => $request->tgl_lahir ?? '-',
            'agama' => $request->agama,
            'jenis_kelamin' => $request->jenis_kelamin,
            'no_hp' => $request->no_hp,
            'no_tlpn' => $request->no_tlpn,
            'email' => $request->email,
            'alamat' => $request->alamat,
            'rt' => $request->rt,
            'rw' => $request->rw,
            'no_rumah' => $request->no_rumah,
            'kel' => $request->kel,
            'kec' => $request->kec,
            'kota' => $request->kota,
            'prov' => $request->prov,
            'nik_ayah' => $request->nik_ayah ?? '-',
            'nama_ayah' => $request->nama_ayah ?? '-',
            'tmpt_lahir_ayah' => $request->tmpt_lahir_ayah ?? '-',
            'tgl_lahir_ayah' => $request->tgl_lahir_ayah ?? '-',
            'pendidikan_ayah' => $request->pendidikan_ayah ?? '-',
            'pekerjaan_ayah' => $request->pekerjaan_ayah ?? '-',
            'penghasilan_ayah' => $request->penghasilan_ayah ?? '-',
            'nik_ibu' => $request->nik_ibu ?? '-',
            'nama_ibu' => $request->nama_ibu ?? '-',
            'tmpt_lahir_ibu' => $request->tmpt_lahir_ibu ?? '-',
            'tgl_lahir_ibu' => $request->tgl_lahir_ibu ?? '-',
            'pendidikan_ibu' => $request->pendidikan_ibu ?? '-',
            'pekerjaan_ibu' => $request->pekerjaan_ibu ?? '-',
            'penghasilan_ibu' => $request->penghasilan_ibu ?? '-',
            'nik_wali' => $request->nik_wali ?? '-',
            'nama_wali' => $request->nama_wali ?? '-',
            'tmpt_lahir_wali' => $request->tmpt_lahir_wali ?? '-',
            'tgl_lahir_wali' => $request->tgl_lahir_wali ?? '-',
            'pendidikan_wali' => $request->pendidikan_wali ?? '-',
            'pekerjaan_wali' => $request->pekerjaan_wali ?? '-',
            'penghasilan_wali' => $request->penghasilan_wali ?? '-',
        ]);

        if ($request->filled('from') && $request->from === 'siswaPkl') {
            return redirect('/admin/siswa/' . $id_siswa . '?from=siswaPkl')->with('success', 'Data siswa berhasil diperbarui.');
        }

        return redirect('/admin/siswa');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id_siswa)
    {
        $user = Auth::user();
        if (!$this->canManageMasterSiswa($user)) {
            abort(403, 'Akses ditolak: Hanya Admin, Tata Usaha, dan Kurikulum yang dapat menghapus data siswa.');
        }

        DB::transaction(function () use ($id_siswa) {
            Absensi::where('id_siswa', $id_siswa)->delete();
            PointSiswa::where('id_siswa', $id_siswa)->delete();
            SuratPeringatan::where('id_siswa', $id_siswa)->delete();
            Rapot::where('id_siswa', $id_siswa)->delete();
            Dokumen::where('id_siswa', $id_siswa)->delete();
            SiswaPkl::where('id_siswa', $id_siswa)->delete();
            
            $siswa = Siswa::find($id_siswa);
            if ($siswa && $siswa->foto && $siswa->foto != 'avatar.jpg') {
                Storage::disk('public')->delete('foto-siswa/' . $siswa->foto);
            }
            Siswa::destroy($id_siswa);
        });

        return redirect('/admin/siswa')->with('success', 'Data siswa dan relasi berhasil dihapus.');
    }

    public function reset(string $id_siswa)
    {
        $user = Auth::user();
        $siswa = Siswa::findOrFail($id_siswa);

        if (!$this->canEditSiswa($user, $siswa)) {
            abort(403, 'Akses ditolak: Anda hanya berhak mereset password siswa pada kelas binaan Anda.');
        }

        $layout = 'layout.app';
        $setting = Setting::find('1');
        $siswa->password = 'siswa123';
        $siswa->save();
        return redirect('/admin/siswa')->with('success', 'Password berhasil direset<br>password default adalah <b>siswa123</b>');
    }

    public function download()
    {
        return Excel::download(new SiswaExport, 'data_siswa.xlsx');
    }

    public function pindahKeAlumni(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);
        $tahun_sekarang = date('Y');

        DB::transaction(function () use ($siswa, $tahun_sekarang) {
            Alumni::create([
                'nama' => $siswa->nama_siswa,
                'nis' => $siswa->nis,
                'nisn' => $siswa->nisn,
                'id_jurusan' => $siswa->id_jurusan,
                'tahun_lulus' => $tahun_sekarang,
                'foto' => $siswa->foto,
                'status' => '-',
                'tempat_lahir' => $siswa->tmpt_lahir,
                'tanggal_lahir' => $siswa->tgl_lahir,
                'alamat' => $siswa->alamat,
                'no_hp' => $siswa->no_hp,
                'email' => $siswa->email,
                'jenis_kelamin' => $siswa->jenis_kelamin,
                'agama' => $siswa->agama,
            ]);

            // Bersihkan relasi aktif siswa
            Rapot::where('id_siswa', $siswa->id_siswa)->delete();
            PointSiswa::where('id_siswa', $siswa->id_siswa)->delete();
            SuratPeringatan::where('id_siswa', $siswa->id_siswa)->delete();
            Absensi::where('id_siswa', $siswa->id_siswa)->delete();

            // Hapus siswa dari tabel siswa
            $siswa->delete();
        });

        return redirect()->back()->with('success', 'Siswa berhasil dipindahkan ke alumni.');
    }

    public function pindahSemuaKeAlumni(Request $request, $id_kelas)
    {
        $siswaList = Siswa::where('id_kelas', $id_kelas)->get();
        $tahun_sekarang = date('Y');

        DB::transaction(function () use ($siswaList, $tahun_sekarang) {
            foreach ($siswaList as $siswa) {
                Alumni::create([
                    'nama' => $siswa->nama_siswa,
                    'nis' => $siswa->nis,
                    'nisn' => $siswa->nisn,
                    'id_jurusan' => $siswa->id_jurusan,
                    'tahun_lulus' => $tahun_sekarang,
                    'foto' => $siswa->foto,
                    'status' => '-',
                    'tempat_lahir' => $siswa->tmpt_lahir,
                    'tanggal_lahir' => $siswa->tgl_lahir,
                    'alamat' => $siswa->alamat,
                    'no_hp' => $siswa->no_hp,
                    'email' => $siswa->email,
                    'jenis_kelamin' => $siswa->jenis_kelamin,
                    'agama' => $siswa->agama,
                ]);

                // Hapus relasi aktif siswa
                Rapot::where('id_siswa', $siswa->id_siswa)->delete();
                PointSiswa::where('id_siswa', $siswa->id_siswa)->delete();
                SuratPeringatan::where('id_siswa', $siswa->id_siswa)->delete();
                Absensi::where('id_siswa', $siswa->id_siswa)->delete();
                $siswa->delete();
            }
        });

        return redirect()->back()->with('success', 'Seluruh siswa dari kelas tersebut telah dipindahkan ke alumni.');
    }

    public function getSiswaByKelas($id_kelas)
    {
        $siswa = Siswa::where('id_kelas', $id_kelas)->select('id_siswa', 'nama_siswa')->get();
        return response()->json($siswa);
    }
}
