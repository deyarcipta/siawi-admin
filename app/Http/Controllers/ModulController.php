<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Modul;
use App\Models\Level;
use App\Models\Jurusan;
use App\Models\Mapel;
use App\Models\Guru;
use App\Models\Setting;
use DB;

class ModulController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        $modul = Modul::orderBy('created_at', 'desc')->get();
        return view('modul.data_modul', compact('layout','modul','setting','user'));
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
        $level = Level::orderBy('created_at', 'desc')->get();
        $jurusan = Jurusan::orderBy('created_at', 'desc')->get();
        $mapel = Mapel::orderBy('created_at', 'desc')->get();
        return view('modul.tambah_modul', compact('layout','setting','guru','level','jurusan','mapel','user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'id_mapel' => 'required',
            'id_guru' => 'required',
            'id_level' => 'required',
            'id_jurusan' => 'required',
            'nama_modul' => 'required',
            'file_modul' => 'required|mimes:pdf,PDF',
        ]);

        // Periksa apakah file diunggah
        if ($request->hasFile('file_modul')) {
            // Proses file yang diunggah
            $file = $request->file('file_modul');
            $nama_file = $file->getClientOriginalName();
            $tujuan_upload = 'file_modul';
            $imeagePath = $file->storeAs($tujuan_upload, $nama_file);
        }

        $modul = Modul::create([
            'id_mapel' => $request->id_mapel,
            'id_guru' => $request->id_guru,
            'id_level' => $request->id_level,
            'id_jurusan' => $request->id_jurusan,
            'nama_modul' => $request->nama_modul,
            'file_modul' => $nama_file,
        ]);

        return redirect('/admin/modul');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id_modul)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id_modul)
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        $edit = Modul::find($id_modul);
        session(['file_lama' => $edit->file_modul]);
        $guru = Guru::orderBy('created_at', 'desc')->get();
        $level = Level::orderBy('created_at', 'desc')->get();
        $jurusan = Jurusan::orderBy('created_at', 'desc')->get();
        $mapel = Mapel::orderBy('created_at', 'desc')->get();
        return view('modul.edit_modul', compact('layout','edit','setting','guru','level','jurusan','mapel','user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id_modul)
    {
        $request->validate([
            'id_mapel' => 'required',
            'id_guru' => 'required',
            'id_level' => 'required',
            'id_jurusan' => 'required',
            'nama_modul' => 'required',
            'file_modul' => 'mimes:pdf,PDF',
        ]);

        $modul = Modul::findOrFail($id_modul);

        // Periksa apakah file diunggah
        if ($request->hasFile('file_modul')) {
            if ($modul->file_modul) {
                // Hapus file lama dari penyimpanan (misalnya, menggunakan Storage di Laravel)
                Storage::delete('file_modul/' . $modul->file_modul);
            }
             // Proses file yang diunggah
            // $imagePath = request()->file('file')->store('gambar');
            $file = $request->file('file_modul');
            $nama_file = $file->getClientOriginalName();
            $tujuan_upload = 'file_modul';
            $imeagePath = $file->storeAs($tujuan_upload, $nama_file);

            // Simpan nama file baru ke dalam data
            $modul->file_modul = $nama_file;
        } else {
            // Gunakan nama file yang ada dalam session
            $file = session('file_lama');
            $modul->file_modul = $file;
        }
        // dd($modul->file_modul);
        // Proses penyimpanan data lainnya

        // Hapus nama file dari session setelah digunakan
        session()->forget('file_lama');
        Modul::where('id_modul', $id_modul)->update([
            'id_mapel' => $request->id_mapel,
            'id_guru' => $request->id_guru,
            'id_level' => $request->id_level,
            'id_jurusan' => $request->id_jurusan,
            'nama_modul' => $request->nama_modul,
            'file_modul' => $modul->file_modul,
        ]);

        return redirect('/admin/modul');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id_modul)
    {
        $modul = Modul::findOrFail($id_modul);
        if ($modul->file_modul) {
            // Hapus file lama dari penyimpanan (misalnya, menggunakan Storage di Laravel)
            Storage::delete('file_modul/' . $modul->file_modul);
        }
        Modul::destroy($id_modul);
        return redirect('/admin/modul');
    }
}
