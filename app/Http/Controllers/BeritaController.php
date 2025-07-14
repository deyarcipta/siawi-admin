<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Berita;
use App\Models\Guru;
use App\Models\Setting;
use Carbon\Carbon;
use DB;

class BeritaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        $berita = Berita::orderBy('created_at', 'desc')->get();
        return view('berita.data_berita', compact('layout','berita','setting','user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        $guru = Guru::orderBy('created_at', 'desc')->get();
        return view('berita.tambah_berita', compact('layout','setting','guru','user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul_berita' => 'required',
            'isi_berita' => 'required',
            'pembuat' => 'required',
            'tanggal' => 'required',
            'cover' => 'required|mimes:jpg,JPG,png,PNG',
        ]);

        $carbonDate = Carbon::parse($request->tanggal)->formatLocalized('%d %B %Y %H:%M');

        // Periksa apakah file diunggah
        if ($request->hasFile('cover')) {
            // Proses file yang diunggah
            $file = $request->file('cover');
            $nama_file = $file->getClientOriginalName();
            $tujuan_upload = 'berita';
            $file->storeAs($tujuan_upload, $nama_file, 'public');
        }

        $berita = Berita::create([
            'judul_berita' => $request->judul_berita,
            'isi_berita' => $request->isi_berita,
            'pembuat' => $request->pembuat,
            'tanggal' => $carbonDate,
            'cover' => $nama_file,
        ]);

        return redirect('/admin/berita');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id_berita)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id_berita)
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        $edit = Berita::find($id_berita);
        session(['old_file' => $edit->cover]);
        $guru = Guru::orderBy('created_at', 'desc')->get();
        $carbonDate = Carbon::createFromFormat('d F Y H:i', $edit->tanggal);
        return view('berita.edit_berita', compact('layout','edit','setting','guru','carbonDate','user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id_berita)
    {
        
        $request->validate([
            'judul_berita' => 'required',
            'isi_berita' => 'required',
            'pembuat' => 'required',
            'tanggal' => 'required',
            'cover' => 'max:2048|mimes:png,PNG,jpg,JPG,jpeg,JPEG',
        ]);

        $berita = Berita::findOrFail($id_berita);
        
        // Periksa apakah file diunggah
        if ($request->hasFile('cover')) {
            if ($berita->cover) {
                // Hapus file lama dari penyimpanan (misalnya, menggunakan Storage di Laravel)
                Storage::delete('berita/' . $berita->cover);
            }
             // Proses file yang diunggah
            // $imagePath = request()->file('file')->store('gambar');
            $file = $request->file('cover');
            $nama_file = $file->getClientOriginalName();
            $tujuan_upload = 'berita';
            $imeagePath = $file->storeAs($tujuan_upload, $nama_file);

            // Simpan nama file baru ke dalam data
            $berita->cover = $nama_file;
        } else {
            // Gunakan nama file yang ada dalam session
            $file = session('old_file');
            $berita->cover = $file;
        }
        // dd($berita->cover);
        // Proses penyimpanan data lainnya

        // Hapus nama file dari session setelah digunakan
        session()->forget('old_file');
        $carbonDate = Carbon::parse($request->tanggal)->formatLocalized('%d %B %Y %H:%M');
        Berita::where('id_berita', $id_berita)->update([
            'judul_berita' => $request->judul_berita,
            'isi_berita' => $request->isi_berita,
            'pembuat' => $request->pembuat,
            'tanggal' => $carbonDate,
            'cover' => $berita->cover,
        ]);

        return redirect('/admin/berita');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id_berita)
    {
        Berita::destroy($id_berita);
        return redirect('/admin/berita');
    }
}
