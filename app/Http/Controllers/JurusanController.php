<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Jurusan;
use App\Models\Setting;
use DB;

class JurusanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        $jurusan = Jurusan::orderBy('created_at', 'desc')->get();
        return view('dataMaster.jurusan.data_jurusan', compact('layout','jurusan','setting','user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        return view('dataMaster.jurusan.tambah_jurusan', compact('layout','setting','user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_jurusan' => 'required',
            'nama_jurusan' => 'required',
        ]);

        $jurusan = Jurusan::create([
            'kode_jurusan' => $request->kode_jurusan,
            'nama_jurusan' => $request->nama_jurusan,
        ]);

        return redirect('/admin/jurusan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id_jurusan)
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        $edit = Jurusan::find($id_jurusan);
        return view('dataMaster.jurusan.edit_jurusan', compact('layout','edit','setting','user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id_jurusan)
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        $edit = Jurusan::find($id_jurusan);
        return view('dataMaster.jurusan.edit_jurusan', compact('layout','edit','setting','user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id_jurusan)
    {
        $request->valid_jurusanate([
            'kode_jurusan' => 'required',
            'nama_jurusan' => 'required',
        ]);

        Jurusan::where('id_jurusan', $id_jurusan)->update([
            'kode_jurusan' => $request->kode_jurusan,
            'nama_jurusan' => $request->nama_jurusan,
        ]);

        return redirect('/admin/jurusan');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id_jurusan)
    {
        Jurusan::destroy($id_jurusan);
        return redirect('/admin/jurusan');
    }
}
