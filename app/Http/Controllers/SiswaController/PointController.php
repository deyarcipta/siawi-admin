<?php

namespace App\Http\Controllers\SiswaController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\PointSiswa;
use App\Models\Kelas;
use Carbon\Carbon;
use DB;

class PointController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(String $id_siswa)
    {
        $point = PointSiswa::where('id_siswa', $id_siswa)
        ->get();
        $siswa = Siswa::where('id_siswa', $id_siswa)
        ->with('kelas')
        ->with('jurusan')
        ->first();
        $totalSkor = 0;
        $pointArray = []; 
        foreach ($point as $item) {
            $totalSkor += $item->skor_point;
            $dateTimeParts = explode(' ', $item->tanggal);
            $tanggal = implode(' ', array_slice($dateTimeParts, 0, 3)); // Bagian pertama adalah tanggal
            $waktu = implode(' ', array_slice($dateTimeParts, 3, 5)); 
            $hari = Carbon::parse($item->tanggal)->translatedFormat('l');
            $pointArray[] = [
                'nama_point' => $item->point->nama_point,
                'skor_point' => $item->skor_point,
                'tanggal' => $tanggal, 
                'waktu' => $waktu,
                'hari' => $hari
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $pointArray,
            'dataSiswa' => $siswa,
            'totalSkor' => $totalSkor,
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
