<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class DokumenController extends Controller
{
    public function index(Request $request)
    {
        $layout = 'layout.app';
        $setting = Setting::find(1);
        $user = Auth::user();
        $kelas = Kelas::orderBy('created_at', 'desc')->get();
        $kelasId = '';

        // Jika filter kelas digunakan
        if ($request->filled('kelas')) {
            $kelasId = $request->kelas;
            $dataKelas = Kelas::where('id_kelas', $kelasId)->first();

            $siswa = Siswa::whereHas('kelas', function($query) use ($kelasId) {
                $query->where('id_kelas', $kelasId);
            })->get();

            return view('dokumen.index', compact('siswa', 'kelas', 'kelasId', 'layout', 'setting', 'dataKelas', 'user'));
        }

        // Jika tidak ada filter
        $dokumen = Dokumen::with('siswa.kelas')->latest()->get();
        return view('dokumen.index', compact('dokumen', 'kelas', 'layout', 'setting', 'kelasId', 'user'));
    }

    public function create()
    {
        $kelas = Kelas::all();
        return view('dokumen.create', compact('kelas'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'id_siswa' => 'required',
            'id_kelas' => 'required',
            'jenis_dokumen' => 'required',
            'file_dokumen' => 'required|mimes:pdf,PDF',
        ]);
        // Periksa apakah file diunggah
        if ($request->hasFile('file_dokumen')) {
            $file = $request->file('file_dokumen');
            $nama_file = $file->getClientOriginalName();
            $tujuan_upload = 'file_dokumen';
        
            // Simpan ke storage/app/public/file_dokumen
            $file->storeAs($tujuan_upload, $nama_file, 'public');
        }

        $dokumen = Dokumen::create([
            'id_siswa' => $request->id_siswa,
            'id_kelas' => $request->id_kelas,
            'jenis_dokumen' => $request->jenis_dokumen,
            'file_dokumen' => $nama_file,
        ]);

        return redirect()->route('admin.dokumen.index', ['kelas' => $request->id_kelas])->with('success', 'Dokumen berhasil ditambahkan.');
    }

    public function show(string $id_siswa)
    {
        // dd($id_siswa);
        $layout = 'layout.app';
        $dokumen = Dokumen::where('id_siswa', $id_siswa)->get();
        $setting = Setting::find('1');
        $user = Auth::user();
        return view('dokumen.review_dokumen', compact('dokumen','layout','setting','user'));
    }

    public function destroy(string $id_dokumen)
    {
        $dokumen = Dokumen::findOrFail($id_dokumen);
        if ($dokumen->file_dokumen) {
            // Hapus file lama dari penyimpanan (misalnya, menggunakan Storage di Laravel)
            Storage::delete('public/file_dokumen/' . $dokumen->file_dokumen);
        }
        Dokumen::destroy($id_dokumen);
        return redirect('/admin/dokumen');
    }
}
