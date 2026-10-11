<?php

namespace App\Http\Controllers\SiswaController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UploadFotoController extends Controller
{
    /**
     * Fitur ubah/upload foto profil siswa secara mandiri via API SIAWI Mobile telah dinonaktifkan.
     * Pembaruan foto siswa hanya dapat dikelola secara terpusat oleh Administrator Sekolah.
     */
    public function uploadFoto(Request $request, $idSiswa)
    {
        return response()->json([
            'success' => false,
            'message' => 'Fitur ubah foto profil siswa secara mandiri telah dinonaktifkan oleh pihak sekolah. Pembaruan foto profil hanya dapat dilakukan melalui Administrator Sekolah.'
        ], 403);
    }
}
