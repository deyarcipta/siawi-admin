<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Level;
use App\Models\Setting;
use DB;

class LevelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        $level = Level::orderBy('created_at', 'desc')->get();
        return view('dataMaster.level.data_level', compact('layout','level','setting','user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        return view('dataMaster.level.tambah_level', compact('layout','setting','user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_level' => 'required',
            'nama_level' => 'required',
        ]);

        $level = Level::create([
            'kode_level' => $request->kode_level,
            'nama_level' => $request->nama_level,
        ]);

        return redirect('/admin/level');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $layout = 'layout.app';
        $edit = Level::find($id);
        $user = Auth::user();
        return view('dataMaster.level.edit_level', compact('layout','edit','user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $layout = 'layout.app';
        $edit = Level::find($id);
        $setting = Setting::find('1');
        $user = Auth::user();
        return view('dataMaster.level.edit_level', compact('layout','edit','setting','user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'kode_level' => 'required',
            'nama_level' => 'required',
        ]);

        Level::where('id', $id)->update([
            'kode_level' => $request->kode_level,
            'nama_level' => $request->nama_level,
        ]);

        return redirect('/admin/level');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Level::destroy($id);
        return redirect('/admin/level');
    }
}
