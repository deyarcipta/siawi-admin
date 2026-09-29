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

        $carbonDate = Carbon::parse($request->tanggal)->locale('id')->translatedFormat('d F Y H:i');

        // Periksa apakah file diunggah
        $nama_file = null;
        if ($request->hasFile('cover')) {
            $file = $request->file('cover');
            $nama_file = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
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
        $edit = Berita::findOrFail($id_berita);
        session(['old_file' => $edit->cover]);
        $guru = Guru::orderBy('created_at', 'desc')->get();

        $indonesianMonths = [
            'Januari' => 'January', 'Februari' => 'February', 'Maret' => 'March',
            'April' => 'April', 'Mei' => 'May', 'Juni' => 'June',
            'Juli' => 'July', 'Agustus' => 'August', 'September' => 'September',
            'Oktober' => 'October', 'November' => 'November', 'Desember' => 'December',
        ];

        $carbonDate = '';
        if (!empty($edit->tanggal)) {
            try {
                $cleanDate = str_ireplace(array_keys($indonesianMonths), array_values($indonesianMonths), $edit->tanggal);
                $carbonDate = Carbon::parse($cleanDate)->format('Y-m-d\TH:i');
            } catch (\Exception $e) {
                try {
                    $carbonDate = Carbon::parse($edit->tanggal)->format('Y-m-d\TH:i');
                } catch (\Exception $ex) {
                    $carbonDate = now()->format('Y-m-d\TH:i');
                }
            }
        } else {
            $carbonDate = now()->format('Y-m-d\TH:i');
        }

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
                Storage::disk('public')->delete('berita/' . $berita->cover);
            }
            $file = $request->file('cover');
            $nama_file = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $tujuan_upload = 'berita';
            $file->storeAs($tujuan_upload, $nama_file, 'public');
            $berita->cover = $nama_file;
        } else {
            $file = session('old_file');
            $berita->cover = $file;
        }
        // dd($berita->cover);
        // Proses penyimpanan data lainnya

        // Hapus nama file dari session setelah digunakan
        session()->forget('old_file');
        $carbonDate = Carbon::parse($request->tanggal)->locale('id')->translatedFormat('d F Y H:i');
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
        $berita = Berita::find($id_berita);
        if ($berita && $berita->cover) {
            Storage::disk('public')->delete('berita/' . $berita->cover);
        }
        Berita::destroy($id_berita);
        return redirect('/admin/berita');
    }
}
