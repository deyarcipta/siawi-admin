<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Guru;
use App\Models\Setting;
use Illuminate\Support\Facades\Hash;
use DB;

class GuruController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $layout = 'layout.app';// Misalnya, layout default Anda adalah 'layouts.app'
        $setting = Setting::find('1');
        $user = Auth::user();
        $guru = Guru::orderBy('created_at', 'desc')->get();
        return view('dataGuru.dataGuru', compact('layout','guru','setting','user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        return view('dataGuru.tambah_guru', compact('layout','setting','user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required',
            // 'password' => 'required',
            'nama_guru' => 'required',
            'role' => 'required',
        ]);
        $id_face = '0';
        $changPass = 'admin123';
        $password = Hash::make($changPass);
        
        // $hashedPassword = Hash::make($request['password']);

        $guru = Guru::create([
            'id_face' => $id_face,
            'username' => $request->username,
            'password' => $password,
            'nama_guru' => $request->nama_guru,
            'role' => $request->role,
        ]);

        return redirect('/admin/guru')->with('success', 'Data Guru Berhasil Ditambah<br>password default adalah <b>admin123</b>');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id_guru)
    {
        $layout = 'layout.app'; // Misalnya, layout default Anda adalah 'layouts.app'
        $setting = Setting::find('1');
        $user = Auth::user();
        $edit = Guru::find($id_guru);
        return view('dataGuru.edit_guru', compact('layout','edit','setting','user'));
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id_guru)
    {
        $request->validate([
            'username' => 'required',
            // 'password' => 'required',
            'nama_guru' => 'required',
            'id_face' => 'required',
            'role' => 'required',
        ]);
        
        $hashedPassword = Hash::make($request['password']);

        Guru::where('id_guru', $id_guru)->update([
            'username' => $request->username,
            // 'password' => $request->password,
            'nama_guru' => $request->nama_guru,
            'id_face' => $request->id_face,
            'role' => $request->role,
        ]);

        return redirect('/admin/guru');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id_guru)
    {
        Guru::destroy($id_guru);
        return redirect('/admin/guru');
    }

    public function reset(string $id_guru)
    {
        $layout = 'layout.app'; // Misalnya, layout default Anda adalah 'layouts.app'
        $setting = Setting::find('1');
        $user = Auth::user();
        $guru = Guru::find($id_guru);
        $changPass = 'admin123';
        $guru->password = Hash::make($changPass);
        $guru->save();
        return redirect('/admin/guru')->with('success', 'Password berhasil direset<br>password default adalah <b>admin123</b>');
    }
}
