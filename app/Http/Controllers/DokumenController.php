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
        $request->validate([
            'id_siswa' => 'required',
            'jenis_dokumen' => 'required',
            'file_dokumen' => 'required|mimes:pdf,PDF',
        ]);

        $siswa = Siswa::findOrFail($request->id_siswa);

        // Periksa apakah file diunggah
        if ($request->hasFile('file_dokumen')) {
            $file = $request->file('file_dokumen');
            $extension = $file->getClientOriginalExtension();
            
            // Bersihkan nama dari karakter berbahaya untuk penamaan file
            $safe_jenis = preg_replace('/[\\/\\\?\*:\(\)"<>|]/', '', $request->jenis_dokumen);
            $safe_siswa = preg_replace('/[\\/\\\?\*:\(\)"<>|]/', '', $siswa->nama_siswa);
            
            $nama_file = $safe_jenis . '_' . $safe_siswa . '.' . $extension;
            $tujuan_upload = 'file_dokumen';
        
            // Simpan ke storage/app/public/file_dokumen
            $file->storeAs($tujuan_upload, $nama_file, 'public');
        }

        $dokumen = Dokumen::create([
            'id_siswa' => $request->id_siswa,
            'jenis_dokumen' => $request->jenis_dokumen,
            'file_dokumen' => $nama_file,
        ]);

        return redirect()->route('admin.dokumen.index', ['kelas' => $siswa->id_kelas])->with('success', 'Dokumen berhasil ditambahkan.');
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

    public function edit(string $id_dokumen)
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        $dokumen = Dokumen::with('siswa')->findOrFail($id_dokumen);
        return view('dokumen.edit', compact('dokumen', 'layout', 'setting', 'user'));
    }

    public function update(Request $request, string $id_dokumen)
    {
        $request->validate([
            'jenis_dokumen' => 'required',
            'file_dokumen' => 'nullable|mimes:pdf,PDF',
        ]);

        $dokumen = Dokumen::with('siswa')->findOrFail($id_dokumen);
        $siswa = $dokumen->siswa;
        
        // Bersihkan nama dari karakter berbahaya untuk penamaan file
        $safe_jenis = preg_replace('/[\\/\\\?\*:\(\)"<>|]/', '', $request->jenis_dokumen);
        $safe_siswa = preg_replace('/[\\/\\\?\*:\(\)"<>|]/', '', $siswa->nama_siswa);

        $nama_file_lama = $dokumen->file_dokumen;
        $nama_file_baru = $nama_file_lama;

        if ($request->hasFile('file_dokumen')) {
            if ($nama_file_lama) {
                Storage::delete('public/file_dokumen/' . $nama_file_lama);
            }

            $file = $request->file('file_dokumen');
            $extension = $file->getClientOriginalExtension();
            $nama_file_baru = $safe_jenis . '_' . $safe_siswa . '.' . $extension;
            $file->storeAs('file_dokumen', $nama_file_baru, 'public');
        } else {
            // Jika tidak mengunggah file baru, tetapi jenis dokumen berubah
            if ($nama_file_lama && $request->jenis_dokumen != $dokumen->jenis_dokumen) {
                $extension = pathinfo($nama_file_lama, PATHINFO_EXTENSION);
                $nama_file_baru = $safe_jenis . '_' . $safe_siswa . '.' . $extension;

                // Rename file di storage jika berbeda
                if ($nama_file_baru !== $nama_file_lama && Storage::disk('public')->exists('file_dokumen/' . $nama_file_lama)) {
                    Storage::disk('public')->move('file_dokumen/' . $nama_file_lama, 'file_dokumen/' . $nama_file_baru);
                }
            }
        }

        $dokumen->update([
            'jenis_dokumen' => $request->jenis_dokumen,
            'file_dokumen' => $nama_file_baru,
        ]);

        return redirect('/admin/dokumen/' . $dokumen->id_siswa)->with('success', 'Dokumen berhasil diubah.');
    }

    public function destroy(string $id_dokumen)
    {
        $dokumen = Dokumen::findOrFail($id_dokumen);
        $id_siswa = $dokumen->id_siswa;
        if ($dokumen->file_dokumen) {
            // Hapus file lama dari penyimpanan (misalnya, menggunakan Storage di Laravel)
            Storage::delete('public/file_dokumen/' . $dokumen->file_dokumen);
        }
        Dokumen::destroy($id_dokumen);
        return redirect('/admin/dokumen/' . $id_siswa)->with('success', 'Dokumen berhasil dihapus.');
    }
}
