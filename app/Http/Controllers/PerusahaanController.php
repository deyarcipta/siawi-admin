<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Perusahaan;
use App\Models\SiswaPkl;
use App\Models\Setting;

class PerusahaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        $perusahaan = Perusahaan::withCount([
            'siswaPkl as siswa_aktif_count' => function ($query) {
                $query->where('status', 'PKL');
            }
        ])->orderBy('created_at', 'desc')->get();
        return view('bkk.data_perusahaan', compact('layout','perusahaan','setting','user'));
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
            'nama_perusahaan' => 'required|string|max:255',
            'alamat_perusahaan' => 'required|string',
            'penanggung_jawab' => 'required|string|max:255',
        ]);

        Perusahaan::create($request->all());

        return redirect()->route('admin.perusahaan.index')->with('success', 'Perusahaan berhasil ditambahkan.');
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
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'alamat_perusahaan' => 'required|string',
            'penanggung_jawab' => 'required|string|max:255',
        ]);

        $perusahaan = Perusahaan::findOrFail($id);
        $perusahaan->nama_perusahaan = $request->nama_perusahaan;
        $perusahaan->alamat_perusahaan = $request->alamat_perusahaan;
        $perusahaan->penanggung_jawab = $request->penanggung_jawab;
        $perusahaan->save();

        return redirect()->back()->with('success', 'Data perusahaan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Ambil data perusahaan
        $data = Perusahaan::findOrFail($id);

        // Hapus semua siswa PKL yang terkait dengan perusahaan ini
        SiswaPkl::where('id_perusahaan', $data->id_perusahaan)->delete();

        // Hapus perusahaan
        $data->delete();

        return redirect()->route('admin.perusahaan.index')
                        ->with('success', 'Data Perusahaan dan siswa PKL terkait berhasil dihapus');
    }
}
