<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tagihan;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Jurusan;
use App\Models\Setting;
use DB;

class TagihanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $kelas = Kelas::orderBy('nama_kelas', 'asc')->get();
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        $tagihan = Tagihan::find('1');
        $kelasId = $request->input('kelas', '');

        $query = Siswa::with('kelas');
        if (!empty($kelasId) && $kelasId !== 'all') {
            $query->where('id_kelas', $kelasId);
        }
        $siswa = $query->orderBy('nama_siswa', 'asc')->get();
        $dataKelas = (!empty($kelasId) && $kelasId !== 'all') ? Kelas::find($kelasId) : null;

        return view('tagihan.data_tagihan', compact('kelas', 'siswa', 'kelasId', 'layout', 'setting', 'dataKelas', 'tagihan', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id_tagihan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        $edit = Tagihan::find('1');
        return view('tagihan.edit_tagihan', compact('layout','edit','setting','user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id_tagihan)
    {
        $request->validate([
            'link' => 'required',
        ]);

        Tagihan::where('id_tagihan', $id_tagihan)->update([
            'link' => $request->link,
        ]);

        return redirect('/admin/tagihan');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id_tagihan)
    {
        //
    }
}
