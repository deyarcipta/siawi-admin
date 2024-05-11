<?php

namespace App\Http\Controllers\SiswaController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KalenderSekolah;
use DB;


class KalenderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kalender = KalenderSekolah::all();
        
        foreach ($kalender as $item) {
            $kalenderArray[] = [
                'namaKegiatan' => $item->kegiatan,
                'tglMulai' => $item->tgl_mulai  ,
                'tglAkhir' => $item->tgl_akhir,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $kalenderArray,
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
