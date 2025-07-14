<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SiswaPkl;
use App\Models\Perusahaan;
use App\Models\Setting;
use App\Models\Siswa;
use App\Models\Kelas;

class SiswaPklController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        $kelasList = Kelas::orderBy('created_at', 'desc')->get();
        $siswaList = Siswa::orderBy('created_at', 'desc')->get();
        $perusahaan = Perusahaan::orderBy('created_at', 'desc')->get();
        $data_siswa_pkl = SiswaPkl::orderBy('created_at', 'desc')->get();
        return view('bkk.data_siswa_pkl', compact('layout','data_siswa_pkl','setting','user','kelasList','siswaList', 'perusahaan'));
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
        $request->validate([
            'id_siswa' => 'required|exists:siswa,id_siswa',
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'id_perusahaan' => 'required|exists:perusahaan,id_perusahaan',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'status' => 'required|in:PKL,selesai',
        ]);
        SiswaPkl::create($request->all());
        // dd($data); // Lihat apa benar tersimpan

        return redirect()->route('admin.siswaPkl.index')->with('success', 'Data Siswa PKL berhasil ditambahkan');
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = SiswaPkl::findOrFail($id); // cari data berdasarkan ID, atau gagal 404
        $data->delete(); // hapus data

        return redirect()->route('admin.siswaPkl.index')
                        ->with('success', 'Data Siswa PKL berhasil dihapus');
    }
}
