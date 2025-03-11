<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\KalenderSekolah;
use App\Models\Setting;
use DB;

class KalenderSekolahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        $kalender = KalenderSekolah::orderBy('created_at', 'desc')->get();
        return view('kalenderSekolah.data_kalender', compact('layout','kalender','setting','user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        return view('kalenderSekolah.tambah_kalender', compact('layout','setting','user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kegiatan' => 'required',
            'tgl_mulai' => 'required',
            'tgl_akhir' => 'required',
        ]);

        $kalender = KalenderSekolah::create([
            'kegiatan' => $request->kegiatan,
            'tgl_mulai' => $request->tgl_mulai,
            'tgl_akhir' => $request->tgl_akhir,
        ]);

        return redirect('/admin/kalender');
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
        $edit = KalenderSekolah::find($id);
        session(['old_file' => $edit->file]);
        return view('kalenderSekolah.edit_kalender', compact('layout','edit','setting','user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'kegiatan' => 'required',
            'tgl_mulai' => 'required',
            'tgl_akhir' => 'required',
        ]);

        KalenderSekolah::where('id', $id)->update([
            'kegiatan' => $request->kegiatan,
            'tgl_mulai' => $request->tgl_mulai,
            'tgl_akhir' => $request->tgl_akhir,
        ]);

        return redirect('/admin/kalender');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        KalenderSekolah::destroy($id);
        return redirect('/admin/kalender');
    }
}
