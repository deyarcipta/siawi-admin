<?php

namespace App\Http\Controllers\SiswaController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Siswa;
use Carbon\Carbon;
use DB;

class UploadFotoController extends Controller
{

    public function uploadFoto(Request $request, $idSiswa)
    {
        // dd($request->all());
        $request->validate([
            'foto' => 'max:2048|mimes:png,PNG,jpg,JPG,jpeg,JPEG',
        ]);

        $siswa = Siswa::findOrFail($idSiswa);

        // Periksa apakah file diunggah
        if ($request->hasFile('foto')) {
            if ($siswa->foto && $siswa->foto != 'avatar.jpg') {
                // Hapus foto lama dari penyimpanan jika bukan "avatar.jpg"
                Storage::delete('foto-siswa/' . $siswa->foto);
            }
            $file = $request->file('foto');
            $nama_file = $file->getClientOriginalName();
            $tujuan_upload = 'foto-siswa';
            $imeagePath = $file->storeAs($tujuan_upload, $nama_file);

            // Simpan nama file baru ke dalam data
            $siswa->foto = $nama_file;
        } else {
            // Gunakan nama file yang ada dalam session
            $foto = session('old_foto');
            $siswa->foto = $foto;
        }
        //dd($siswa->foto);
        // Proses penyimpanan data lainnya

        // Hapus nama file dari session setelah digunakan
        session()->forget('old_foto');

        $user = Siswa::where('id_siswa', $idSiswa)->update([
                'foto' => $request->foto,
            ]);
            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'Foto Berhasil Diubah'
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
