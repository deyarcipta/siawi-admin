<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Mapel;
use App\Models\Setting;
use DB;

class MapelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        $mapel = Mapel::orderBy('created_at', 'desc')->get();
        return view('dataMaster.mapel.data_mapel', compact('layout','mapel','setting','user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        return view('dataMaster.mapel.tambah_mapel', compact('layout','setting','user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_mapel' => 'required',
            'nama_mapel' => 'required',
        ]);

        $mapel = Mapel::create([
            'kode_mapel' => $request->kode_mapel,
            'nama_mapel' => $request->nama_mapel,
        ]);

        return redirect('/admin/mapel');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id_mapel)
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $edit = Mapel::find($id_mapel);
        $user = Auth::user();
        return view('dataMaster.mapel.edit_mapel', compact('layout','edit','user','setting'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id_mapel)
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        $edit = Mapel::find($id_mapel);
        return view('dataMaster.mapel.edit_mapel', compact('layout','edit','setting','user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id_mapel)
    {
        $request->validate([
            'kode_mapel' => 'required',
            'nama_mapel' => 'required',
        ]);

        Mapel::where('id_mapel', $id_mapel)->update([
            'kode_mapel' => $request->kode_mapel,
            'nama_mapel' => $request->nama_mapel,
        ]);

        return redirect('/admin/mapel');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id_mapel)
    {
        Mapel::destroy($id_mapel);
        return redirect('/admin/mapel');
    }
}
