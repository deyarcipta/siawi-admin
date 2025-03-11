<?php

namespace App\Http\Controllers\SiswaController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UpdateSiswaController extends Controller
{
    public function updateSiswa(Request $request,$idSiswa)
    {
        // dd($request->all());
        $request->validate([
            'nis'=> 'required',
            'nisn'=> 'required',
            'nama_siswa'=> 'required',
            'tmpt_lahir'=> 'required',
            'tgl_lahir'=> 'required',
            'agama'=> 'required',
            'jenis_kelamin'=> 'required',
            'no_hp'=> 'required',
            'no_tlpn'=> 'required',
            'email'=> 'required',
            'alamat'=> 'required',
            'rt'=> 'required',
            'rw'=> 'required',
            'no_rumah'=> 'required',
            'kel'=> 'required',
            'kec'=> 'required',
            'kota'=> 'required',
            'prov'=> 'required',
            'nik_ayah'=> 'required',
            'nama_ayah'=> 'required',
            'tmpt_lahir_ayah'=> 'required',
            'tgl_lahir_ayah'=> 'required',
            'pendidikan_ayah'=> 'required',
            'pekerjaan_ayah'=> 'required',
            'penghasilan_ayah'=> 'required',
            'nik_ibu'=> 'required',
            'nama_ibu'=> 'required',
            'tmpt_lahir_ibu'=> 'required',
            'tgl_lahir_ibu'=> 'required',
            'pendidikan_ibu'=> 'required',
            'pekerjaan_ibu'=> 'required',
            'penghasilan_ibu'=> 'required',
            'nik_wali'=> 'required',
            'nama_wali'=> 'required',
            'tmpt_lahir_wali'=> 'required',
            'tgl_lahir_wali'=> 'required',
            'pendidikan_wali'=> 'required',
            'pekerjaan_wali'=> 'required',
            'penghasilan_wali'=> 'required',
        ]);

        
        if ($request->password1 != $request->password2) {
            // Pengguna tidak ditemukan atau kata sandi tidak cocok
            return response()->json([
                'success' => false,
                'message' => 'Password yang anda masukan tidak cocok'
            ], 401);
        }
            $user = Siswa::where('id_siswa', $request->idSiswa)->update([
                'nis'=> $request->nis,
                'nisn'=> $request->nisn,
                'nama_siswa'=> $request->nama_siswa,
                'tmpt_lahir'=> $request->tmpt_lahir,
                'tgl_lahir'=> $request->tgl_lahir,
                'agama'=> $request->agama,
                'jenis_kelamin'=> $request->jenis_kelamin,
                'no_hp'=> $request->no_hp,
                'no_tlpn'=> $request->no_tlpn,
                'email'=> $request->email,
                'alamat'=> $request->alamat,
                'rt'=> $request->rt,
                'rw'=> $request->rw,
                'no_rumah'=> $request->no_rumah,
                'kel'=> $request->kel,
                'kec'=> $request->kec,
                'kota'=> $request->kota,
                'prov'=> $request->prov,
                'nik_ayah'=> $request->nik_ayah,
                'nama_ayah'=> $request->nama_ayah,
                'tmpt_lahir_ayah'=> $request->tmpt_lahir_ayah,
                'tgl_lahir_ayah'=> $request->tgl_lahir_ayah,
                'pendidikan_ayah'=> $request->pendidikan_ayah,
                'pekerjaan_ayah'=> $request->pekerjaan_ayah,
                'penghasilan_ayah'=> $request->penghasilan_ayah,
                'nik_ibu'=> $request->nik_ibu,
                'nama_ibu'=> $request->nama_ibu,
                'tmpt_lahir_ibu'=> $request->tmpt_lahir_ibu,
                'tgl_lahir_ibu'=> $request->tgl_lahir_ibu,
                'pendidikan_ibu'=> $request->pendidikan_ibu,
                'pekerjaan_ibu'=> $request->pekerjaan_ibu,
                'penghasilan_ibu'=> $request->penghasilan_ibu,
                'nik_wali'=> $request->nik_wali,
                'nama_wali'=> $request->nama_wali,
                'tmpt_lahir_wali'=> $request->tmpt_lahir_wali,
                'tgl_lahir_wali'=> $request->tgl_lahir_wali,
                'pendidikan_wali'=> $request->pendidikan_wali,
                'pekerjaan_wali'=> $request->pekerjaan_wali,
                'penghasilan_wali'=> $request->penghasilan_wali,
            ]);
            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'Data Siswa Berhasil Diubah'
            ]);
    
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
