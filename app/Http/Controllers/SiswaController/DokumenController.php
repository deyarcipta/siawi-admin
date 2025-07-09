<?php

namespace App\Http\Controllers\SiswaController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Dokumen;
use App\Models\Kelas;
use DB;

class DokumenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(String $id_siswa)
    {
        $dokumen = Dokumen::where('id_siswa', $id_siswa)
        ->get();
        
        $dokumenArray = [];
        foreach ($dokumen as $item) {
            $dokumenArray[] = [
                'jenis_dokumen' => $item->jenis_dokumen,
                'file_dokumen' => $item->file_dokumen,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $dokumenArray,
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
