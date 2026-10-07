<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\KlasifikasiSurat;
use App\Models\Setting;

class KlasifikasiSuratController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $layout = 'layout.app';
        $setting = Setting::find(1);
        $user = Auth::user();

        $klasifikasi = KlasifikasiSurat::orderBy('urutan', 'asc')->orderBy('id', 'asc')->get();

        return view('surat.klasifikasi.index', compact('layout', 'setting', 'user', 'klasifikasi'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:50|unique:klasifikasi_surat,kode',
            'nama' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'urutan' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ], [
            'kode.required' => 'Kode klasifikasi wajib diisi.',
            'kode.unique' => 'Kode klasifikasi sudah terdaftar.',
            'nama.required' => 'Nama klasifikasi wajib diisi.',
        ]);

        KlasifikasiSurat::create([
            'kode' => strtoupper(trim($request->kode)),
            'nama' => trim($request->nama),
            'keterangan' => $request->keterangan,
            'urutan' => $request->input('urutan', 0) ?? 0,
            'is_active' => $request->has('is_active') ? (bool) $request->is_active : true,
        ]);

        return redirect()->route('admin.klasifikasi-surat.index')->with('success', 'Klasifikasi surat baru berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $klasifikasi = KlasifikasiSurat::findOrFail($id);

        $request->validate([
            'kode' => 'required|string|max:50|unique:klasifikasi_surat,kode,' . $klasifikasi->id,
            'nama' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'urutan' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ], [
            'kode.required' => 'Kode klasifikasi wajib diisi.',
            'kode.unique' => 'Kode klasifikasi sudah digunakan oleh data lain.',
            'nama.required' => 'Nama klasifikasi wajib diisi.',
        ]);

        $klasifikasi->update([
            'kode' => strtoupper(trim($request->kode)),
            'nama' => trim($request->nama),
            'keterangan' => $request->keterangan,
            'urutan' => $request->input('urutan', 0) ?? 0,
            'is_active' => $request->has('is_active') ? (bool) $request->is_active : true,
        ]);

        return redirect()->route('admin.klasifikasi-surat.index')->with('success', 'Klasifikasi surat berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $klasifikasi = KlasifikasiSurat::findOrFail($id);

        // Periksa apakah kode klasifikasi sedang digunakan di SuratKeluar
        $isUsed = \App\Models\SuratKeluar::where('kode_klasifikasi', $klasifikasi->kode)->exists();
        if ($isUsed) {
            return redirect()->route('admin.klasifikasi-surat.index')->with('error', "Klasifikasi '{$klasifikasi->kode}' tidak dapat dihapus karena sudah digunakan dalam data Surat Keluar. Anda dapat menonaktifkannya jika tidak ingin digunakan lagi.");
        }

        $klasifikasi->delete();

        return redirect()->route('admin.klasifikasi-surat.index')->with('success', 'Klasifikasi surat berhasil dihapus.');
    }
}
