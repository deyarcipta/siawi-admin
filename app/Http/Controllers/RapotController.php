<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Rapot;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Setting;
use DB;

class RapotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        $kelas = Kelas::orderBy('nama_kelas', 'asc')->get();
        $kelasId = $request->get('kelas', '');

        $query = Siswa::with(['kelas', 'rapot']);

        if (!empty($kelasId)) {
            $query->where('id_kelas', $kelasId);
            $dataKelas = Kelas::where('id_kelas', $kelasId)->first();
        } else {
            $dataKelas = null;
        }

        $siswa = $query->orderBy('nama_siswa', 'asc')->get();
        $allSiswa = Siswa::with('kelas')->orderBy('nama_siswa', 'asc')->get();

        return view('rapot.data_rapot', compact('kelas', 'siswa', 'allSiswa', 'kelasId', 'layout', 'setting', 'dataKelas', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($kelasId = null)
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        $rapot = Rapot::orderBy('created_at', 'desc')->get();
        if ($kelasId) {
            $siswa = Siswa::where('id_kelas', $kelasId)->orderBy('nama_siswa', 'asc')->get();
        } else {
            $siswa = Siswa::with('kelas')->orderBy('nama_siswa', 'asc')->get();
        }
        return view('rapot.tambah_rapot', compact('layout','setting','rapot','siswa','kelasId','user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_siswa' => 'required',
            'semester' => 'required',
            'rata_rata' => 'required',
            'file_rapot' => 'required|mimes:pdf,PDF',
        ]);

        $siswa = Siswa::findOrFail($request->id_siswa);
        $id_kelas = $request->id_kelas ?: $siswa->id_kelas;

        // Periksa apakah file diunggah
        $nama_file = null;
        if ($request->hasFile('file_rapot')) {
            $file = $request->file('file_rapot');
            $nama_file = 'rapot_' . $request->id_siswa . '_sem' . $request->semester . '_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $tujuan_upload = 'file_rapot';
            $file->storeAs($tujuan_upload, $nama_file, 'public');
        }

        $rapot = Rapot::create([
            'id_siswa' => $request->id_siswa,
            'id_kelas' => $id_kelas,
            'semester' => $request->semester,
            'rata_rata' => $request->rata_rata,
            'file_rapot' => $nama_file,
        ]);

        return redirect('/admin/rapot')->with('success', 'Data Rapot berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id_siswa)
    {
        // dd($id_siswa);
        $layout = 'layout.app';
        $rapot = Rapot::where('id_siswa', $id_siswa)->get();
        $setting = Setting::find('1');
        $user = Auth::user();
        return view('rapot.review_rapot', compact('rapot','layout','setting','user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id_rapot)
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        $edit = Rapot::find($id_rapot);
        session(['old_rapot' => $edit->file_rapot]);
        return view('rapot.edit_rapot', compact('layout','edit','setting','user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id_rapot)
    {
        $request->validate([
            'id_siswa' => 'required',
            'id_kelas' => 'required',
            'semester' => 'required',
            'rata_rata' => 'required',
            'file_rapot' => 'mimes:pdf,PDF',
        ]);
        $rapot = Rapot::findOrFail($id_rapot);

        // dd($request ->id_rapot);
        // Periksa apakah file diunggah
        if ($request->hasFile('file_rapot')) {
            if ($rapot->file_rapot) {
                Storage::disk('public')->delete('file_rapot/' . $rapot->file_rapot);
            }
            $file = $request->file('file_rapot');
            $nama_file = 'rapot_' . $request->id_siswa . '_sem' . $request->semester . '_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $tujuan_upload = 'file_rapot';
            $file->storeAs($tujuan_upload, $nama_file, 'public');
            $rapot->file_rapot = $nama_file;
        } else {
            $file = session('old_rapot');
            $rapot->file_rapot = $file;
        }
        // dd($rapot->file_rapot);
        // Proses penyimpanan data lainnya
        $id_siswa = $request->id_siswa;
        // Hapus nama file dari session setelah digunakan
        session()->forget('old_rapot');
        Rapot::where('id_rapot', $id_rapot)->update([
            'id_siswa' => $request->id_siswa,
            'id_kelas' => $request->id_kelas,
            'semester' => $request->semester,
            'rata_rata' => $request->rata_rata,
            'file_rapot' => $rapot->file_rapot,
        ]);

        

        return redirect('/admin/rapot/' . $id_siswa);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id_rapot)
    {
        $rapot = Rapot::findOrFail($id_rapot);
        if ($rapot->file_rapot) {
            Storage::disk('public')->delete('file_rapot/' . $rapot->file_rapot);
        }
        Rapot::destroy($id_rapot);
        return redirect('/admin/rapot');
    }
}
