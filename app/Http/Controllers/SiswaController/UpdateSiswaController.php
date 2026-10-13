<?php

namespace App\Http\Controllers\SiswaController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;

class UpdateSiswaController extends Controller
{
    public function updateSiswa(Request $request, $idSiswa)
    {
        $request->validate([
            'nama_siswa' => 'required',
        ]);

        if ($request->filled('password1')) {
            if ($request->password1 != $request->password2) {
                return response()->json([
                    'success' => false,
                    'message' => 'Password yang anda masukkan tidak cocok'
                ], 400);
            }
        }

        $updateData = [
            'nama_siswa'        => $request->nama_siswa,
            'tmpt_lahir'        => $request->tmpt_lahir,
            'tgl_lahir'         => $request->tgl_lahir,
            'agama'             => $request->agama,
            'jenis_kelamin'     => $request->jenis_kelamin,
            'no_hp'             => $request->no_hp,
            'no_tlpn'           => $request->no_tlpn,
            'email'             => $request->email,
            'alamat'            => $request->alamat,
            'rt'                => $request->rt,
            'rw'                => $request->rw,
            'no_rumah'          => $request->no_rumah,
            'kel'               => $request->kel,
            'kec'               => $request->kec,
            'kota'              => $request->kota,
            'prov'              => $request->prov,

            // Data Ayah
            'nik_ayah'          => $request->nik_ayah,
            'nama_ayah'         => $request->nama_ayah,
            'tmpt_lahir_ayah'   => $request->tmpt_lahir_ayah,
            'tgl_lahir_ayah'    => $request->tgl_lahir_ayah,
            'pendidikan_ayah'   => $request->pendidikan_ayah,
            'pekerjaan_ayah'    => $request->pekerjaan_ayah,
            'penghasilan_ayah'  => $request->penghasilan_ayah,
            'no_hp_ayah'        => $request->no_hp_ayah,

            // Data Ibu
            'nik_ibu'           => $request->nik_ibu,
            'nama_ibu'          => $request->nama_ibu,
            'tmpt_lahir_ibu'    => $request->tmpt_lahir_ibu,
            'tgl_lahir_ibu'     => $request->tgl_lahir_ibu,
            'pendidikan_ibu'    => $request->pendidikan_ibu,
            'pekerjaan_ibu'     => $request->pekerjaan_ibu,
            'penghasilan_ibu'   => $request->penghasilan_ibu,
            'no_hp_ibu'         => $request->no_hp_ibu,

            // Data Wali
            'nik_wali'          => $request->nik_wali,
            'nama_wali'         => $request->nama_wali,
            'tmpt_lahir_wali'   => $request->tmpt_lahir_wali,
            'tgl_lahir_wali'    => $request->tgl_lahir_wali,
            'pendidikan_wali'   => $request->pendidikan_wali,
            'pekerjaan_wali'    => $request->pekerjaan_wali,
            'penghasilan_wali'  => $request->penghasilan_wali,
            'no_hp_wali'        => $request->no_hp_wali,
        ];

        if ($request->filled('nis')) {
            $updateData['nis'] = $request->nis;
        }
        if ($request->filled('nisn')) {
            $updateData['nisn'] = $request->nisn;
        }
        if ($request->filled('password1')) {
            $updateData['password'] = bcrypt($request->password1);
        }

        $siswa = Siswa::where('id_siswa', $idSiswa)->first();
        if ($siswa) {
            // Jika jenis kelamin diperbarui dan foto saat ini masih avatar bawaan, sesuaikan avatar defaultnya
            if ($request->filled('jenis_kelamin') && in_array($siswa->foto, [null, '', 'avatar.jpg', 'avatar.png', 'avatar_male.jpg', 'avatar_male.png', 'avatar_female.jpg', 'avatar_female.png'])) {
                $isPerempuan = in_array(strtoupper(trim($request->jenis_kelamin)), ['P', 'PEREMPUAN']);
                $updateData['foto'] = $isPerempuan ? 'avatar_female.jpg' : 'avatar_male.jpg';
            }

            $siswa->update($updateData);

            if ($siswa->id_orang_tua) {
                $ortuPhone = $request->no_hp_ayah ?? $request->no_hp_ibu;
                if (!empty($ortuPhone)) {
                    \App\Models\OrangTua::where('id_orang_tua', $siswa->id_orang_tua)
                        ->update(['no_hp' => $ortuPhone]);
                }
            }
        }

        return response()->json([
            'success' => true,
            'data'    => $siswa,
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
