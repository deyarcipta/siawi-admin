<?php

namespace App\Http\Controllers\SiswaController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\InformasiSekolah;
use Carbon\Carbon;
use DB;

class InformasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $informasi = InformasiSekolah::orderBy('created_at', 'asc')->get();

        foreach ($informasi as $item) {
            // Mengonversi tanggal_awal ke nama hari dalam format Indonesia
            $hari = Carbon::parse($item->tanggal_awal)->translatedFormat('l');
            $informasiArray[] = [
                'informasi' => $item->informasi,
                'ket_informasi' => $item->ket_informasi,
                'hari' => $hari,
                'tanggal_awal' => $item->tanggal_awal,
                'tanggal_akhir' => $item->tanggal_akhir,
                'file' => $item->file,
            ];
        }
        
        return response()->json([
            'success' => true,
            'data' => $informasiArray,
            'message' => 'Data Informasi Berhasil Diambil'
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
