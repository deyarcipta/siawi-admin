<?php

namespace App\Http\Controllers\SiswaController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Modul;
use App\Models\Kelas;
use DB;

class ModulController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(String $id_siswa)
    {
        $siswa = Siswa::where('id_siswa', $id_siswa)->first();
        $modul = Modul::where('id_level', $siswa->id_level)
        ->where('id_jurusan', $siswa->id_jurusan)
        ->get();
        
        $modulArray = [];
        foreach ($modul as $item) {
            // Jika belum ada array untuk nama mapel tersebut, inisialisasikan
            if (!isset($modulArray[$item->mapel->nama_mapel])) {
                $modulArray[$item->mapel->nama_mapel] = [
                    'namaMapel' => $item->mapel->nama_mapel,
                    'modul' => [],
                ];
            }
            // Tambahkan data modul ke dalam array nama mapel yang sesuai
            $modulArray[$item->mapel->nama_mapel]['modul'][] = [
                'namaModul' => $item->nama_modul,
                'file_modul' => $item->file_modul,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $modulArray,
            'message' => 'Berhasil Ambil Data'
        ]);
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
        //
    }
}
