<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Level;
use App\Models\Jurusan;
use App\Models\Setting;
use DB;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    
    public function index()
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        $siswa = Siswa::orderBy('created_at', 'desc')->get();
        return view('dataSiswa.data_siswa', compact('layout','siswa','setting','user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $layout = 'layout.app';
        $level = Level::orderBy('created_at', 'desc')->get();
        $jurusan = Jurusan::orderBy('created_at', 'desc')->get();
        $kelas = Kelas::orderBy('created_at', 'desc')->get();
        $setting = Setting::find('1');
        $user = Auth::user();
        return view('dataSiswa.tambah_siswa', compact('layout','level','jurusan','kelas','setting','user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        dd($request->all());
        $request->validate([
            'nis' => 'required',
            'nisn' => 'required',
            'password' => 'required',
            'nama_siswa' => 'required',
            'kode_level' => 'required',
            'kode_kelas' => 'required',
            'kode_jurusan' => 'required',
            'tmpt_lahir' => 'required',
            'tgl_lahir' => 'required',
            'agama' => 'required',
            'jenis_kelamin' => 'required',
            'no_hp' => 'required',
            'email' => 'required',
            'foto' => 'max:2048|mimes:png,PNG,jpg,JPG,jpeg,JPEG',
            'alamat' => 'required',
            'rt' => 'required',
            'rw' => 'required',
            'no_rumah' => 'required',
            'kel' => 'required',
            'kec' => 'required',
            'prov' => 'required',
            'kota' => 'required',
        ]);

	$nama_file = 'avatar.jpg';
        // Periksa apakah file diunggah
        if ($request->hasFile('foto')) {
             // Proses file yang diunggah
            // $imagePath = request()->file('foto')->store('gambar');
            $file = $request->file('foto');
            $nama_file = $file->getClientOriginalName();
            $tujuan_upload = 'foto-siswa';
            $imeagePath = $file->storeAs($tujuan_upload, $nama_file);

            // Simpan nama file baru ke dalam data
            // $siswa->foto = $nama_file;
        }

        $siswa = Siswa::create([
            'nis' => $request->nis,
            'nisn' => $request->nisn,
            'nama_siswa' => $request->nama_siswa,
            'password' => $request->password,
            'id_level' => $request->kode_level,
            'id_kelas' => $request->kode_kelas,
            'id_jurusan' => $request->kode_jurusan,
            'foto' => $nama_file,
            'tmpt_lahir' => $request->tmpt_lahir,
            'tgl_lahir' => $request->tgl_lahir,
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

        return redirect('/admin/siswa');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id_siswa)
    {
        $layout = 'layout.app';
        $detail = Siswa::find($id_siswa);
        $setting = Setting::find('1');
        $user = Auth::user();
        return view('dataSiswa.detail_siswa', compact('detail','layout','setting','user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id_siswa)
    {
        $layout = 'layout.app';
        $edit = Siswa::find($id_siswa);
        session(['old_foto' => $edit->foto]);
        $level = Level::orderBy('created_at', 'desc')->get();
        $jurusan = Jurusan::orderBy('created_at', 'desc')->get();
        $kelas = Kelas::orderBy('created_at', 'desc')->get();
        $setting = Setting::find('1');
        $user = Auth::user();
        return view('dataSiswa.edit_siswa', compact('layout','edit','level','jurusan','kelas','setting','user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id_siswa)
    {
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
            'tgl_lahir' => 'required',
            'agama' => 'required',
            'jenis_kelamin' => 'required',
            'no_hp' => 'required',
            'email' => 'required',
            'foto' => 'max:2048|mimes:png,PNG,jpg,JPG,jpeg,JPEG',
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
            if ($siswa->foto && $siswa->foto != 'avatar.jpg') {
                // Hapus foto lama dari penyimpanan jika bukan "avatar.jpg"
                Storage::delete('foto-siswa/' . $siswa->foto);
            }
             // Proses file yang diunggah
            // $imagePath = request()->file('foto')->store('gambar');
            $file = $request->file('foto');
            $nama_file = $file->getClientOriginalName();
            $tujuan_upload = 'foto-siswa';
            $imeagePath = $file->storeAs($tujuan_upload, $nama_file);

            // Simpan nama file baru ke dalam data
            $siswa->foto = $nama_file;
        } else {
            // Gunakan nama file yang ada dalam session
            $foto = session('old_foto');
            $siswa->foto = $foto;
        }
        //dd($siswa->foto);
        // Proses penyimpanan data lainnya

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
            'tgl_lahir' => $request->tgl_lahir,
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

        return redirect('/admin/siswa');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id_siswa)
    {
		DB::table('absensi')->where('id_siswa', $id_siswa)->delete();
        Siswa::destroy($id_siswa);
        return redirect('/admin/siswa');
    }

    public function reset(string $id_siswa)
    {
        $layout = 'layout.app'; // Misalnya, layout default Anda adalah 'layouts.app'
        $setting = Setting::find('1');
        $user = Auth::user();
        $siswa = Siswa::find($id_siswa);
        $changPass = 'siswa123';
        // $siswa->password = Hash::make($changPass);
        $siswa->save();
        return redirect('/admin/siswa')->with('success', 'Password berhasil direset<br>password default adalah <b>siswa123</b>');
    }
}
