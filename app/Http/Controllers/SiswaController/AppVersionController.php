<?php

namespace App\Http\Controllers\SiswaController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AppVersion;

class AppVersionController extends Controller
{
    public function getLatestVersion(Request $request)
    {
        // Ambil versi aplikasi saat ini dari request
        $currentVersion = $request->input('current_version');

        // Ambil versi aplikasi terbaru dari database
        $latestVersion = AppVersion::latest()->first();
        // dd($latestVersion->versi); 

        // Jika tidak ada data versi terbaru di database
        if (!$latestVersion) {
            return response()->json([
                'success' => false,
                'message' => 'Versi aplikasi tidak ditemukan.',
            ], 404);
        }

        // Bandingkan versi aplikasi saat ini dengan versi terbaru
        if (version_compare($currentVersion, $latestVersion->versi, '<')) {
            // Jika ada versi terbaru
            return response()->json([
                'success' => true,
                'update_available' => true,
                'latest_version' => $latestVersion->versi,
                'download_url' => $latestVersion->download_url,
                'message' => 'Update tersedia untuk aplikasi Anda.',
            ]);
        }

        // Jika aplikasi sudah versi terbaru
        return response()->json([
            'success' => true,
            'update_available' => false,
            'message' => 'Aplikasi Anda sudah versi terbaru.',
        ]);
    }
}
