<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;
use App\Models\AppVersion;
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
        $appVersi = AppVersion::where('id_versi', 1)->first();
        $user = Auth::user();
        session(['old_logo' => $setting->logo]);
        return view('settingApp.setting', compact('layout','setting','appVersi','user'));
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
            'nama_app' => 'nullable|string|max:255',
            'nama_sekolah' => 'nullable|string|max:255',
            'nama_kepsek' => 'nullable|string|max:255',
            'nip_kepsek' => 'nullable|string|max:255',
            'alamat' => 'nullable|string|max:255',
            'kel' => 'nullable|string|max:255',
            'kec' => 'nullable|string|max:255',
            'prov' => 'nullable|string|max:255',
            'kota' => 'nullable|string|max:255',
            'primary_color' => 'nullable|string|max:255',
            'secondary_color' => 'nullable|string|max:255',
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
            'primary_color' => $setting->primary_color,
            'secondary_color' => $setting->secondary_color,
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
        // Mengambil data setting berdasarkan ID
        $setting = Setting::find($id);

        // Cek apakah request mengandung data yang perlu diproses dari settingDasar
        if ($request->has('nama_sekolah')) {
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
            // Cek apakah ada file logo yang diunggah
            if ($request->hasFile('logo')) {
                
                // Hapus logo lama jika ada
                if ($setting->logo) {
                    Storage::delete('public/gambar/' . $setting->logo);
                }

                // Simpan logo baru
                $file = $request->file('logo');
                $nama_file = time() . '_' . $file->getClientOriginalName(); // Tambahkan timestamp agar unik
                $file->storeAs('public/gambar/', $nama_file);

                // Simpan nama file baru
                $setting->logo = $nama_file;
            } else {
                // Jika tidak ada file logo yang diunggah, biarkan logo lama tetap digunakan
                $nama_file = $setting->logo; 
            }
            // Update settingDasar
            $setting->update([
                'nama_app' => $request->nama_app,
                'nama_sekolah' => $request->nama_sekolah,
                'nama_kepsek' => $request->nama_kepsek,
                'nip_kepsek' => $request->nip_kepsek,
                'alamat' => $request->alamat,
                'kel' => $request->kel,
                'kec' => $request->kec,
                'prov' => $request->prov,
                'kota' => $request->kota,
                'logo' => $nama_file
            ]);
        }

        // Cek apakah request mengandung data yang perlu diproses dari settingAplikasi
        if ($request->has('primary_color') || $request->has('secondary_color')) {
            $request->validate([
                'nama_app' => 'required',
                'primary_color' => 'required',
                'secondary_color' => 'required',
                'third_color' => 'required',
                'four_color' => 'required',
                'five_color' => 'required',
                'six_color' => 'required',
                'text_color' => 'required',
            ]);

            // Konversi warna HEX ke format Flutter (0xFF + HEX tanpa #)
            $primaryColorFlutter = '0xFF' . strtoupper(ltrim($request->primary_color, '#'));
            $secondaryColorFlutter = '0xFF' . strtoupper(ltrim($request->secondary_color, '#'));
            $thirdColorFlutter = '0xFF' . strtoupper(ltrim($request->third_color, '#'));
            $fourColorFlutter = '0xFF' . strtoupper(ltrim($request->four_color, '#'));
            $fiveColorFlutter = '0xFF' . strtoupper(ltrim($request->five_color, '#'));
            $sixColorFlutter = '0xFF' . strtoupper(ltrim($request->six_color, '#'));
            $textColorFlutter = '0xFF' . strtoupper(ltrim($request->text_color, '#'));
            // Update settingAplikasi
            $setting->update([
                'primary_color' => $primaryColorFlutter,
                'secondary_color' => $secondaryColorFlutter,
                'third_color' => $thirdColorFlutter,
                'four_color' => $fourColorFlutter,
                'five_color' => $fiveColorFlutter,
                'six_color' => $sixColorFlutter,
                'text_color' => $textColorFlutter,
            ]);
        }

        // Cek apakah request mengandung 'versi_app' (berarti update versi aplikasi)
        if ($request->has('versi_app')) {
            $request->validate([
                'versi_app' => 'required',
                'link_app' => 'required',
            ]);

            DB::table('versi_siawi')->where('id_versi', $id)->update([
                'versi' => $request->versi_app,
                'download_url' => $request->link_app,
                // 'updated_at' => now()
            ]);

            return redirect()->back()->with('success', 'Versi aplikasi berhasil diperbarui.');
        }

        return redirect('/admin/setting')->with('success', 'Pengaturan berhasil diperbarui.');
        
    }

       /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
