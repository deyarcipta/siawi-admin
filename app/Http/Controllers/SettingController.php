<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;
use DB;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        session(['old_logo' => $setting->logo]);
        return view('settingApp.setting', compact('layout','setting','user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $layout = 'layout.app';
        $user = Auth::user();
        return view('settingApp.setting', compact('layout','user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_app' => 'required',
            'nama_sekolah' => 'required',
            'nama_kepsek' => 'required',
            'nip_kepsek' => 'required',
            'alamat' => 'required',
            'kel' => 'required',
            'kec' => 'required',
            'prov' => 'required',
            'kota' => 'required',
            // 'logo' => 'required',
        ]);

        $setting = Setting::create([
            'nama_app' => $request->nama_app,
            'nama_sekolah' => $request->nama_sekolah,
            'nama_kepsek' => $request->nama_kepsek,
            'nip_kepsek' => $request->nip_kepsek,
            'alamat' => $request->alamat,
            'kel' => $request->kel,
            'kec' => $request->kec,
            'prov' => $request->prov,
            'kota' => $request->kota,
            'logo' => $setting->logo,
        ]);

        return redirect('/admin/setting');
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
        $edit = Setting::find($id);
        $user = Auth::user();
        // session(['old_foto' => $edit->foto]);
        return view('settingApp.setting', compact('layout','edit','user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        
        $validate = $request->validate([
            'nama_app' => 'required',
            'nama_sekolah' => 'required',
            'nama_kepsek' => 'required',
            'nip_kepsek' => 'required',
            'alamat' => 'required',
            'kel' => 'required',
            'kec' => 'required',
            'prov' => 'required',
            'kota' => 'required',
            'logo' => 'required',
        ]);

        // dd($validate);
        $setting = Setting::findOrFail($id);

        // Periksa apakah file diunggah
        if ($request->hasFile('logo')) {
            if ($setting->logo) {
                // Hapus logo lama dari penyimpanan (misalnya, menggunakan Storage di Laravel)
                Storage::delete('gambar/' . $setting->logo);
            }
             // Proses file yang diunggah
            // $imagePath = request()->file('logo')->store('gambar');
            $file = $request->file('logo');
            $nama_file = $file->getClientOriginalName();
            $tujuan_upload = 'gambar';
            $imeagePath = $file->storeAs($tujuan_upload, $nama_file);

            // Simpan nama file baru ke dalam data
            $setting->logo = $nama_file;
        } else {
            // Gunakan nama file yang ada dalam session
            $logo = session('old_logo');
            $setting->logo = $logo;
        }

        // Proses penyimpanan data lainnya

        // Hapus nama file dari session setelah digunakan
        session()->forget('old_foto');

        Setting::where('id', $id)->update([
            'nama_app' => $request->nama_app,
            'nama_sekolah' => $request->nama_sekolah,
            'nama_kepsek' => $request->nama_kepsek,
            'nip_kepsek' => $request->nip_kepsek,
            'alamat' => $request->alamat,
            'kel' => $request->kel,
            'kec' => $request->kec,
            'prov' => $request->prov,
            'kota' => $request->kota,
            'logo' => $setting->logo,
        ]);

        return redirect('/admin/setting');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
