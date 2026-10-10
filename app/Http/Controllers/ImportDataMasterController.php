<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\MasterImport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Setting;
use App\Models\Siswa;
use ZipArchive;
use DB;

class ImportDataMasterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        return view('dataMaster.importDataMaster', compact('layout','setting','user'));
    }

    /**
     * Import Data Master Siswa via Spreadsheet Excel
     */
    public function importData(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx'
        ], [
            'file.required' => 'Silakan pilih berkas Excel terlebih dahulu.',
            'file.mimes' => 'Berkas harus berupa spreadsheet dengan ekstensi .xls atau .xlsx.'
        ]);

        try {
            Excel::import(new MasterImport, $request->file('file'));
            return redirect()->back()->with('success', 'Data Master siswa dan akun orang tua berhasil diimpor & disinkronkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengimpor data: ' . $e->getMessage());
        }
    }

    /**
     * Import Foto Siswa Massal via Berkas ZIP
     * Mencocokkan nama berkas di dalam zip dengan NIS Siswa (contoh: 233001.jpg -> Siswa NIS 233001)
     * Lalu menyimpan foto dengan nama acak unik standar sistem.
     */
    public function importFotoZip(Request $request)
    {
        $request->validate([
            'file_zip' => 'required|file|mimes:zip|max:102400', // Maks 100MB
        ], [
            'file_zip.required' => 'Silakan pilih berkas arsip ZIP foto.',
            'file_zip.file' => 'Berkas yang diunggah tidak valid.',
            'file_zip.mimes' => 'Berkas harus berformat arsip .zip.',
            'file_zip.max' => 'Ukuran berkas ZIP maksimal adalah 100 MB.',
        ]);

        try {
            $zipFile = $request->file('file_zip');
            $zip = new ZipArchive();

            if ($zip->open($zipFile->getRealPath()) !== true) {
                return redirect()->back()->with('error', 'Gagal membuka berkas ZIP. Pastikan berkas tidak rusak atau terproteksi kata sandi.');
            }

            if (!Storage::disk('public')->exists('foto-siswa')) {
                Storage::disk('public')->makeDirectory('foto-siswa');
            }

            $successCount = 0;
            $skippedCount = 0;
            $notFoundNis = [];
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

            for ($i = 0; $i < $zip->numFiles; $i++) {
                $entryName = $zip->getNameIndex($i);

                // Abaikan direktori
                if (substr($entryName, -1) === '/') {
                    continue;
                }

                $baseFilename = basename($entryName);

                // Abaikan file metadata tersembunyi (misal dari macOS __MACOSX atau .DS_Store)
                if (str_starts_with($baseFilename, '.') || str_contains($entryName, '__MACOSX')) {
                    continue;
                }

                $ext = strtolower(pathinfo($baseFilename, PATHINFO_EXTENSION));
                if (!in_array($ext, $allowedExtensions)) {
                    continue;
                }

                // Ambil NIS dari nama berkas (tanpa ekstensi)
                $nis = trim(pathinfo($baseFilename, PATHINFO_FILENAME));
                if (empty($nis)) {
                    continue;
                }

                // Cari siswa berdasarkan NIS
                $siswa = Siswa::where('nis', $nis)->first();
                if (!$siswa) {
                    $skippedCount++;
                    if (count($notFoundNis) < 10) {
                        $notFoundNis[] = $nis;
                    }
                    continue;
                }

                // Ambil isi berkas dari ZIP
                $stream = $zip->getStream($entryName);
                if (!$stream) {
                    continue;
                }
                $fileContent = stream_get_contents($stream);
                fclose($stream);

                if (empty($fileContent)) {
                    continue;
                }

                // Generate nama unik acak standar sistem
                $newFilename = time() . '_' . uniqid() . '.' . $ext;

                // Hapus foto lama jika ada dan bukan avatar bawaan
                if ($siswa->foto && $siswa->foto !== 'avatar.jpg') {
                    Storage::disk('public')->delete('foto-siswa/' . $siswa->foto);
                    if (file_exists(public_path('storage/foto-siswa/' . $siswa->foto))) {
                        @unlink(public_path('storage/foto-siswa/' . $siswa->foto));
                    }
                }

                // Simpan berkas ke storage/app/public/foto-siswa/
                Storage::disk('public')->put('foto-siswa/' . $newFilename, $fileContent);

                // Perbarui foto siswa
                $siswa->foto = $newFilename;
                $siswa->save();

                $successCount++;
            }

            $zip->close();

            if ($successCount === 0 && $skippedCount === 0) {
                return redirect()->back()->with('error', 'Tidak ditemukan berkas gambar (.jpg, .jpeg, .png, .webp) di dalam berkas ZIP.');
            }

            $message = "Berhasil mengimpor dan memperbarui foto untuk {$successCount} siswa.";
            if ($skippedCount > 0) {
                $nisList = implode(', ', $notFoundNis);
                $more = $skippedCount > count($notFoundNis) ? ' dan ' . ($skippedCount - count($notFoundNis)) . ' lainnya' : '';
                $message .= " Terdapat {$skippedCount} foto dilewati karena NIS tidak ditemukan di database ({$nisList}{$more}).";
            }

            return redirect()->back()->with('success', $message);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses arsip foto ZIP: ' . $e->getMessage());
        }
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
