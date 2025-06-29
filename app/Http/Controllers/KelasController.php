<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Kelas;
use App\Models\Level;
use App\Models\Siswa;
use App\Models\Alumni;
use App\Models\Jurusan;
use App\Models\Setting;
use DB;

class KelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        $kelas = Kelas::orderBy('nama_kelas', 'asc')->get();
        return view('dataMaster.kelas.data_kelas', compact('layout','kelas','setting','user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        $level = Level::orderBy('created_at', 'desc')->get();
        $jurusan = Jurusan::orderBy('created_at', 'desc')->get();
        return view('dataMaster.kelas.tambah_kelas', compact('layout','setting','level','jurusan','user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_kelas' => 'required',
            'kode_level' => 'required',
            'nama_kelas' => 'required',
            'kode_jurusan' => 'required',
        ]);

        $kelas = Kelas::create([
            'kode_kelas' => $request->kode_kelas,
            'kode_level' => $request->kode_level,
            'nama_kelas' => $request->nama_kelas,
            'kode_jurusan' => $request->kode_jurusan,
        ]);

        return redirect('/admin/kelas');
    }

    public function pindahKeAlumni(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);

        Alumni::create([
            'nama' => $siswa->nama,
            'nis' => $siswa->nis,
            'nisn' => $siswa->nisn,
            'id_jurusan' => $siswa->id_jurusan,
            'tahun_lulus' => $request->tahun_lulus,
            'foto' => $siswa->foto,
            'status' => 'Alumni',
            'tempat_lahir' => $siswa->tempat_lahir,
            'tanggal_lahir' => $siswa->tanggal_lahir,
            'alamat' => $siswa->alamat,
            'no_hp' => $siswa->no_hp,
            'email' => $siswa->email,
            'jenis_kelamin' => $siswa->jenis_kelamin,
            'agama' => $siswa->agama,
        ]);

        $siswa->delete(); // opsional: hapus dari tabel siswa

        return redirect()->back()->with('success', 'Siswa berhasil dipindahkan ke alumni.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id_kelas)
    {
        $layout = 'layout.app';
        $edit = Kelas::find($id_kelas);
        $user = Auth::user();
        return view('dataMaster.kelas.edit_kelas', compact('layout','edit','user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id_kelas)
    {
        $layout = 'layout.app';
        $edit = Kelas::find($id_kelas);
        $setting = Setting::find('1');
        $user = Auth::user();
        $level = Level::orderBy('created_at', 'desc')->get();
        $jurusan = Jurusan::orderBy('created_at', 'desc')->get();
        return view('dataMaster.kelas.edit_kelas', compact('layout','edit','level','jurusan','setting','user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id_kelas)
    {
        $request->validate([
            'kode_kelas' => 'required',
            'kode_level' => 'required',
            'nama_kelas' => 'required',
            'kode_jurusan' => 'required',
        ]);

        Kelas::where('id_kelas', $id_kelas)->update([
            'kode_kelas' => $request->kode_kelas,
            'kode_level' => $request->kode_level,
            'nama_kelas' => $request->nama_kelas,
            'kode_jurusan' => $request->kode_jurusan,
        ]);

        return redirect('/admin/kelas');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id_kelas)
    {
        Kelas::destroy($id_kelas);
        return redirect('/admin/kelas');
    }
}