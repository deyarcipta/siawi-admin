<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PointSiswa;
use App\Models\Point;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Jurusan;
use App\Models\Guru;
use App\Models\Setting;
use Carbon\Carbon;
use DB;

class PointSiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $kelas = Kelas::orderBy('created_at', 'desc')->get();
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        $kelasId = '';

        // Jika form sudah diisi, ambil data siswa berdasarkan kelas yang dipilih
        if ($request->filled('tanggal') && $request->filled('kelas')) {
            $tanggal = $request->tanggal;
            $kelasId = $request->kelas;
            $carbonDate = Carbon::parse($tanggal);
            $carbonDate->locale('id');
            $namaHari = $carbonDate->translatedFormat('l');
            $dataKelas = Kelas::where('id_kelas', $kelasId)->first();
            $siswa = Siswa::whereHas('kelas', function($query) use ($kelasId) {
                $query->where('id_kelas', $kelasId);
            })->get();

            return view('pointSiswa.data_point_siswa', compact('kelas', 'siswa', 'kelasId', 'layout', 'setting','dataKelas','tanggal','user'));
        }

        return view('pointSiswa.data_point_siswa', compact('kelas', 'layout', 'setting', 'kelasId','user'));
    }

    public function proses(string $id_siswa, $tanggal)
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        $carbonDate = Carbon::parse($tanggal)->formatLocalized('%d %B %Y %H:%M');
        $siswa = Siswa::where('id_siswa', $id_siswa)->first();
        $point = Point::orderBy('skor_point', 'asc')->orderByRaw("CASE WHEN jenis_point = 'KERAJINAN' THEN 0 ELSE 1 END")->get();
        return view('pointSiswa.tambah_point_siswa', compact('siswa', 'layout', 'setting', 'point', 'carbonDate','user'));
    }

    public function inputPoint(Request $request, $id_point, $id_siswa, $id_kelas, $id_jurusan,  $tanggal)
    {  
        // dd($id_siswa);
        $idSiswa = $request->input('id_siswa');
        $layout = 'layout.app';
        $carbonDate = Carbon::parse($tanggal)->formatLocalized('%d %B %Y %H:%M');
        $setting = Setting::find('1');
        $user = Auth::user();
        $siswa = Siswa::where('id_siswa', $id_siswa)->first();
        $request->session()->put('siswa', $siswa);
        $point = Point::where('id_point', $id_point)->first();
        $guru = Guru::where('id_guru', '1')->first();

        PointSiswa::create([
            'id_siswa' => $id_siswa,
            'id_point' => $id_point,
            'id_kelas' => $id_kelas,
            'id_jurusan' => $id_jurusan,
            'id_guru' => $guru->id_guru,
            'role' => $guru->role,
            'skor_point' => $point->skor_point,
            'tanggal' => $tanggal
        ]);

        // $pointSiswa = PointSiswa::orderBy('tanggal', 'desc')->get();
        // dd($siswa);
        // $total_point = 0;

        return redirect()->route('admin.pointSiswa.review_point_siswa', ['id_siswa' => $id_siswa])->with('success','Data Point Berhasil Ditambah');
        
        // return view('pointSiswa.review_point_siswa', compact('siswa', 'layout', 'setting', 'pointSiswa', 'total_point'));
    }

    public function reviewPointSiswa(Request $request, $id_siswa)
    {
        // dd($request->all());
        $layout = 'layout.app';
        $user = Auth::user();
        // $carbonDate = Carbon::parse($tanggal)->formatLocalized('%d %B %Y %H:%M');
        $setting = Setting::find('1');
        $siswa = Siswa::where('id_siswa', $id_siswa)->first();
        // $siswa = $request->session()->get('siswa');
        // dd($siswa->id_siswa);
        // $siswa = Siswa::where('id_siswa', $id_siswa)->first();
        // $point = Point::where('id_point', $id_point)->first();
        // $guru = Guru::where('id_guru', '1')->first();
        $pointSiswa = PointSiswa::where('id_siswa', $id_siswa)->get();
        // dd($pointSiswa);
        $total_point = 0; // Inisialisasi variabel total point

        // Iterasi melalui setiap data PointSiswa dan tambahkan nilai skor_point
        foreach ($pointSiswa as $data) {
            $total_point += $data->skor_point;
        }
        return view('pointSiswa.review_point_siswa', compact('siswa','layout', 'setting', 'pointSiswa','total_point','user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id_point_siswa)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id_point_siswa)
    {
        // Cari data point yang akan dihapus
        $pointSiswa = PointSiswa::findOrFail($id_point_siswa);

        // Ambil id_siswa sebelum menghapus data
        $id_siswa = $pointSiswa->id_siswa;

        // Hapus data
        $pointSiswa->delete();

        // Redirect dengan membawa id_siswa
        return redirect()->route('admin.pointSiswa.review_point_siswa', ['id_siswa' => $id_siswa])
                        ->with('success', 'Data Point Berhasil Dihapus');
    }
}
