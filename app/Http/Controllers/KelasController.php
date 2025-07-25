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

    public function naikKelas($id)
    {
        $kelas = Kelas::findOrFail($id);

        // Tentukan level berikutnya
        $naikLevel = [
            'X' => 'XI',
            'XI' => 'XII'
        ];

        $kelasBaru = Kelas::where('kode_level', $naikLevel[$kelas->kode_level] ?? null)
            ->where('kode_jurusan', $kelas->kode_jurusan)
            ->first();

        if (!$kelasBaru) {
            return back()->with('error', 'Kelas tujuan tidak ditemukan!');
        }
        dd($kelasBaru->id_level);
        Siswa::where('id_kelas', $kelas->id_kelas)->update([
            'id_kelas' => $kelasBaru->id_kelas,
            'id_level' => $kelasBaru->id_level,
        ]);

        return redirect()->back()->with('success', 'Semua siswa berhasil dinaikkan ke kelas ' . $kelasBaru->nama_kelas);
    }

    public function prosesIndividu(Request $request)
    {
        $action = $request->input('action');
        $selectedSiswa = $request->input('selected_siswa', []);

        if (empty($selectedSiswa)) {
            return back()->with('error', 'Tidak ada siswa yang dipilih.');
        }

        if ($action === 'alumni') {
            $tahun = $request->input('tahun_lulus', date('Y'));
           Siswa::whereIn('id_siswa', $selectedSiswa)->update([
                'status' => 'alumni',
                'tahun_lulus' => $tahun,
                'id_kelas' => null
            ]);

            return back()->with('success', 'Siswa berhasil dipindahkan ke alumni.');
        }

        if ($action === 'naik') {
            $selectedSiswa = $request->input('selected_siswa', []);
            $tujuanKelas = $request->input('tujuan_kelas', []);

            foreach ($selectedSiswa as $idSiswa) {
                if (isset($tujuanKelas[$idSiswa])) {
                    $kelas = Kelas::find($tujuanKelas[$idSiswa]);

                    if ($kelas) {
                        // Ambil id_level berdasarkan kode_level dari kelas
                        $level = Level::where('kode_level', $kelas->kode_level)->first();

                        if ($level) {
                            Siswa::where('id_siswa', $idSiswa)->update([
                                'id_kelas' => $kelas->id_kelas,
                                'id_level' => $level->id_level // <-- diambil berdasarkan kode_level
                            ]);
                        }
                    }
                }
            }

            return back()->with('success', 'Siswa berhasil dinaikkan ke kelas dan level tujuan.');
        }

        return back()->with('error', 'Aksi tidak dikenali.');
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