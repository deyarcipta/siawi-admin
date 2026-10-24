<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use App\Models\Lab;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LabController extends Controller
{
    /**
     * Tampilkan daftar laboratorium/bengkel.
     */
    public function index()
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();

        if (!$user->hasAnyRole(['admin', 'kaprog'])) {
            abort(403, 'Akses terbatas untuk Admin dan Kaprog.');
        }

        $labs = Lab::forUserJurusan($user)
            ->with('jurusan')
            ->orderBy('nama_lab', 'asc')
            ->get();

        return view('kejuruan.lab.index', compact('layout', 'setting', 'user', 'labs'));
    }

    /**
     * Form tambah laboratorium baru.
     */
    public function create()
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();

        if (!$user->hasAnyRole(['admin', 'kaprog'])) {
            abort(403, 'Akses terbatas untuk Admin dan Kaprog.');
        }

        $jurusans = Jurusan::forUserJurusan($user)
            ->where('aktif', true)
            ->orderBy('nama_jurusan', 'asc')
            ->get();

        return view('kejuruan.lab.create', compact('layout', 'setting', 'user', 'jurusans'));
    }

    /**
     * Simpan data laboratorium baru.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user->hasAnyRole(['admin', 'kaprog'])) {
            abort(403, 'Akses terbatas untuk Admin dan Kaprog.');
        }

        $accessibleJurusanIds = Jurusan::getAccessibleJurusanIds($user);

        $request->validate([
            'nama_lab' => 'required|string|max:255',
            'kode_lab' => 'nullable|string|max:50',
            'id_jurusan' => 'required|in:' . implode(',', $accessibleJurusanIds),
            'kapasitas' => 'nullable|integer|min:0',
            'lokasi' => 'nullable|string|max:255',
        ], [
            'nama_lab.required' => 'Nama laboratorium wajib diisi.',
            'id_jurusan.required' => 'Program keahlian/jurusan wajib dipilih.',
            'id_jurusan.in' => 'Anda tidak memiliki hak akses untuk jurusan yang dipilih.',
            'kapasitas.integer' => 'Kapasitas harus berupa angka bulat positif.',
        ]);

        Lab::create([
            'nama_lab' => trim($request->nama_lab),
            'kode_lab' => $request->kode_lab ? strtoupper(trim($request->kode_lab)) : null,
            'id_jurusan' => $request->id_jurusan,
            'kapasitas' => $request->kapasitas ?: 0,
            'lokasi' => $request->lokasi ? trim($request->lokasi) : null,
        ]);

        return redirect('/admin/lab')->with('success', 'Data laboratorium berhasil ditambahkan.');
    }

    /**
     * Form edit laboratorium.
     */
    public function edit(string $id_lab)
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();

        if (!$user->hasAnyRole(['admin', 'kaprog'])) {
            abort(403, 'Akses terbatas untuk Admin dan Kaprog.');
        }

        $lab = Lab::forUserJurusan($user)->findOrFail($id_lab);

        $jurusans = Jurusan::forUserJurusan($user)
            ->orderBy('nama_jurusan', 'asc')
            ->get();

        return view('kejuruan.lab.edit', compact('layout', 'setting', 'user', 'lab', 'jurusans'));
    }

    /**
     * Perbarui data laboratorium.
     */
    public function update(Request $request, string $id_lab)
    {
        $user = Auth::user();
        if (!$user->hasAnyRole(['admin', 'kaprog'])) {
            abort(403, 'Akses terbatas untuk Admin dan Kaprog.');
        }

        $lab = Lab::forUserJurusan($user)->findOrFail($id_lab);
        $accessibleJurusanIds = Jurusan::getAccessibleJurusanIds($user);

        $request->validate([
            'nama_lab' => 'required|string|max:255',
            'kode_lab' => 'nullable|string|max:50',
            'id_jurusan' => 'required|in:' . implode(',', $accessibleJurusanIds),
            'kapasitas' => 'nullable|integer|min:0',
            'lokasi' => 'nullable|string|max:255',
        ], [
            'nama_lab.required' => 'Nama laboratorium wajib diisi.',
            'id_jurusan.required' => 'Program keahlian/jurusan wajib dipilih.',
            'id_jurusan.in' => 'Anda tidak memiliki hak akses untuk jurusan yang dipilih.',
            'kapasitas.integer' => 'Kapasitas harus berupa angka bulat positif.',
        ]);

        $lab->update([
            'nama_lab' => trim($request->nama_lab),
            'kode_lab' => $request->kode_lab ? strtoupper(trim($request->kode_lab)) : null,
            'id_jurusan' => $request->id_jurusan,
            'kapasitas' => $request->kapasitas ?: 0,
            'lokasi' => $request->lokasi ? trim($request->lokasi) : null,
        ]);

        return redirect('/admin/lab')->with('success', 'Data laboratorium berhasil diperbarui.');
    }

    /**
     * Hapus data laboratorium.
     */
    public function destroy(string $id_lab)
    {
        $user = Auth::user();
        if (!$user->hasAnyRole(['admin', 'kaprog'])) {
            abort(403, 'Akses terbatas untuk Admin dan Kaprog.');
        }

        $lab = Lab::forUserJurusan($user)->findOrFail($id_lab);
        $lab->delete();

        return redirect('/admin/lab')->with('success', 'Data laboratorium berhasil dihapus.');
    }
}
