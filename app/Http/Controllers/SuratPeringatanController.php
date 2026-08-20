<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratPeringatan;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class SuratPeringatanController extends Controller
{
    /**
     * Display a listing of the SP.
     */
    public function index()
    {
        $layout = 'layout.app';
        $setting = Setting::find(1);
        $user = Auth::user();

        // Get all issued SPs
        $suratPeringatan = SuratPeringatan::with('siswa.kelas', 'kelas')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('suratPeringatan.index', compact('layout', 'setting', 'user', 'suratPeringatan'));
    }

    /**
     * Upload signed SP.
     */
    public function uploadTtd(Request $request, $id_sp)
    {
        $request->validate([
            'file_ttd' => 'required|mimes:pdf,jpg,jpeg,png|max:4096',
        ]);

        $sp = SuratPeringatan::findOrFail($id_sp);

        if ($request->hasFile('file_ttd')) {
            // Delete old file if exists
            if ($sp->file_ttd && File::exists(public_path('storage/sp_ttd/' . $sp->file_ttd))) {
                File::delete(public_path('storage/sp_ttd/' . $sp->file_ttd));
            }

            $file = $request->file('file_ttd');
            $fileName = time() . '_ttd_' . str_replace(' ', '_', $file->getClientOriginalName());
            
            // Ensure folder exists
            if (!File::isDirectory(public_path('storage/sp_ttd'))) {
                File::makeDirectory(public_path('storage/sp_ttd'), 0777, true, true);
            }

            $file->move(public_path('storage/sp_ttd'), $fileName);

            $sp->update([
                'file_ttd' => $fileName,
            ]);

            return redirect()->back()->with('success', 'File SP bertanda tangan berhasil diunggah.');
        }

        return redirect()->back()->with('failed', 'Gagal mengunggah file.');
    }
}
