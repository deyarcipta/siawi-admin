<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Guru;
use App\Models\GuruPiket;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;

class GuruPiketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        $piket = GuruPiket::with('guru')->get();
        
        $hariOrder = [
            'Senin' => 1,
            'Selasa' => 2,
            'Rabu' => 3,
            'Kamis' => 4,
            'Jumat' => 5,
            'Sabtu' => 6,
            'Minggu' => 7
        ];
        
        $piket = $piket->sortBy(function ($item) use ($hariOrder) {
            return $hariOrder[$item->hari] ?? 99;
        });

        return view('dataPiket.data_piket', compact('layout', 'setting', 'user', 'piket'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        $guru = Guru::orderBy('nama_guru', 'asc')->get();
        return view('dataPiket.tambah_piket', compact('layout', 'setting', 'user', 'guru'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_guru' => 'required|exists:guru,id_guru',
            'hari' => 'required',
            'waktu_awal' => 'required',
            'waktu_akhir' => 'required',
        ]);

        GuruPiket::create([
            'id_guru' => $request->id_guru,
            'hari' => $request->hari,
            'waktu_awal' => $request->waktu_awal,
            'waktu_akhir' => $request->waktu_akhir,
        ]);

        return redirect('/admin/guruPiket')->with('success', 'Jadwal piket guru berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id_piket)
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        $edit = GuruPiket::findOrFail($id_piket);
        $guru = Guru::orderBy('nama_guru', 'asc')->get();
        return view('dataPiket.edit_piket', compact('layout', 'setting', 'user', 'edit', 'guru'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id_piket)
    {
        $request->validate([
            'id_guru' => 'required|exists:guru,id_guru',
            'hari' => 'required',
            'waktu_awal' => 'required',
            'waktu_akhir' => 'required',
        ]);

        $piket = GuruPiket::findOrFail($id_piket);
        $piket->update([
            'id_guru' => $request->id_guru,
            'hari' => $request->hari,
            'waktu_awal' => $request->waktu_awal,
            'waktu_akhir' => $request->waktu_akhir,
        ]);

        return redirect('/admin/guruPiket')->with('success', 'Jadwal piket guru berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_piket)
    {
        $piket = GuruPiket::findOrFail($id_piket);
        $piket->delete();

        return redirect('/admin/guruPiket')->with('success', 'Jadwal piket guru berhasil dihapus.');
    }
}
