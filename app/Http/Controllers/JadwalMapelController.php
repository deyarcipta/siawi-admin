<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\JadwalMapel;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Guru;
use App\Models\Setting;
use DB;

class JadwalMapelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        $jadwal = JadwalMapel::orderBy('created_at', 'desc')->with('guru','mapel')->get();
        return view('jadwalMapel.data_jadwal', compact('layout','jadwal','setting','user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $kelas = Kelas::orderBy('created_at', 'desc')->get();
        $mapel = Mapel::orderBy('created_at', 'desc')->get();
        $guru = Guru::orderBy('created_at', 'desc')->get();
        $user = Auth::user();
        return view('jadwalMapel.tambah_jadwal', compact('layout','mapel','kelas','guru','setting','user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_mapel' => 'required',
            'id_guru' => 'required',
            'hari' => 'required',
            'kelas' => 'required',
            'jam_awal' => 'required',
            'jam_akhir' => 'required',
            'waktu_awal' => 'required',
            'waktu_akhir' => 'required',
        ]);

        $jadwal = JadwalMapel::create([
            'id_mapel' => $request->id_mapel,
            'id_guru' => $request->id_guru,
            'hari' => $request->hari,
            'kelas' => $request->kelas,
            'jam_awal' => $request->jam_awal,
            'jam_akhir' => $request->jam_akhir,
            'waktu_awal' => $request->waktu_awal,
            'waktu_akhir' => $request->waktu_akhir,
        ]);

        return redirect('/admin/jadwal');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id_jadwal)
    {
        // $layout = 'layout.app';
        // $edit = JadwalMapel::find($id_jadwal);
        // return view('jadwalMapel.edit_mapel', compact('layout','edit'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id_jadwal)
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        $kelas = Kelas::orderBy('created_at', 'desc')->get();
        $mapel = Mapel::orderBy('created_at', 'desc')->get();
        $guru = Guru::orderBy('created_at', 'desc')->get();
        $edit = JadwalMapel::find($id_jadwal);
        return view('jadwalMapel.edit_jadwal', compact('layout','edit','mapel','kelas','guru','setting','user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id_jadwal)
    {
        $request->validate([
            'id_mapel' => 'required',
            'id_guru' => 'required',
            'hari' => 'required',
            'kelas' => 'required',
            'jam_awal' => 'required',
            'jam_akhir' => 'required',
            'waktu_awal' => 'required',
            'waktu_akhir' => 'required',
        ]);

        JadwalMapel::where('id_jadwal', $id_jadwal)->update([
            'id_mapel' => $request->id_mapel,
            'id_guru' => $request->id_guru,
            'hari' => $request->hari,
            'kelas' => $request->kelas,
            'jam_awal' => $request->jam_awal,
            'jam_akhir' => $request->jam_akhir,
            'waktu_awal' => $request->waktu_awal,
            'waktu_akhir' => $request->waktu_akhir,
        ]);

        return redirect('/admin/jadwal');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id_jadwal)
    {
        JadwalMapel::destroy($id_jadwal);
        return redirect('/admin/jadwal');
    }
}
