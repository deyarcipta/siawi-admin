<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Point;
use App\Models\Setting;
use DB;

class PointController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        $point = Point::orderBy('created_at', 'desc')->get();
        return view('point.data_point', compact('layout','point','setting','user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        return view('point.tambah_point', compact('layout','setting','user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_point' => 'required',
            'jenis_point' => 'required',
            'skor_point' => 'required',
        ]);

        $point = Point::create([
            'nama_point' => $request->nama_point,
            'jenis_point' => $request->jenis_point,
            'skor_point' => $request->skor_point,
        ]);

        return redirect('/admin/point');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id_point)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id_point)
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        $edit = Point::find($id_point);
        return view('point.edit_point', compact('layout','edit','setting','user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id_point)
    {
        $request->validate([
            'nama_point' => 'required',
            'jenis_point' => 'required',
            'skor_point' => 'required',
        ]);

        Point::where('id_point', $id_point)->update([
            'nama_point' => $request->nama_point,
            'jenis_point' => $request->jenis_point,
            'skor_point' => $request->skor_point,
        ]);

        return redirect('/admin/point');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id_point)
    {
        Point::destroy($id_point);
        return redirect('/admin/point');
    }
}
