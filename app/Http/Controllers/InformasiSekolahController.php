<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\InformasiSekolah;
use App\Models\Setting;
use DB;

class InformasiSekolahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        $informasi = InformasiSekolah::orderBy('created_at', 'desc')->get();
        return view('informasiSekolah.data_informasi', compact('layout','informasi','setting','user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        return view('informasiSekolah.tambah_informasi', compact('layout','setting','user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'informasi' => 'required',
            'tanggal_awal' => 'required',
            'tanggal_akhir' => 'required',
            'ket_informasi' => 'required',
            'file' => 'required|mimes:pdf,PDF,jpg,JPG',
        ]);

        // Periksa apakah file diunggah
        if ($request->hasFile('file')) {
            // Proses file yang diunggah
            $file = $request->file('file');
            $nama_file = $file->getClientOriginalName();
            $tujuan_upload = 'file-informasi';
            $imeagePath = $file->storeAs($tujuan_upload, $nama_file);
        }

        $informasi = InformasiSekolah::create([
            'informasi' => $request->informasi,
            'tanggal_awal' => $request->tanggal_awal,
            'tanggal_akhir' => $request->tanggal_akhir,
            'ket_informasi' => $request->ket_informasi,
            'file' => $nama_file,
        ]);

        return redirect('/admin/informasi')->with('success','Data Berhasil Ditambah');
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
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        $edit = InformasiSekolah::find($id);
        session(['old_file' => $edit->file]);
        return view('informasiSekolah.edit_informasi', compact('layout','edit','setting','user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'informasi' => 'required',
            'tanggal_awal' => 'required',
            'tanggal_akhir' => 'required',
            'ket_informasi' => 'required',
            'file' => 'mimes:pdf,PDF,jpg,JPG',
        ]);

        $informasi = InformasiSekolah::findOrFail($id);

        // Periksa apakah file diunggah
        if ($request->hasFile('file')) {
            if ($informasi->file) {
                // Hapus file lama dari penyimpanan (misalnya, menggunakan Storage di Laravel)
                Storage::delete('file-informasi/' . $informasi->file);
            }
             // Proses file yang diunggah
            // $imagePath = request()->file('file')->store('gambar');
            $file = $request->file('file');
            $nama_file = $file->getClientOriginalName();
            $tujuan_upload = 'file-informasi';
            $imeagePath = $file->storeAs($tujuan_upload, $nama_file);

            // Simpan nama file baru ke dalam data
            $informasi->file = $nama_file;
        } else {
            // Gunakan nama file yang ada dalam session
            $file = session('old_file');
            $informasi->file = $file;
        }

        // Proses penyimpanan data lainnya

        // Hapus nama file dari session setelah digunakan
        session()->forget('old_file');

        InformasiSekolah::where('id', $id)->update([
            'informasi' => $request->informasi,
            'tanggal_awal' => $request->tanggal_awal,
            'tanggal_akhir' => $request->tanggal_akhir,
            'ket_informasi' => $request->ket_informasi,
            'file' => $nama_file,
        ]);

        return redirect('/admin/informasi')->with('success','Data Berhasil Diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        InformasiSekolah::destroy($id);
        return redirect('/admin/informasi')->with('success','Data Berhasil Dihapus');
    }
}
