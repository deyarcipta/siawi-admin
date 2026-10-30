<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use App\Models\Kompetensi;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KompetensiController extends Controller
{
    /**
     * Tampilkan daftar kompetensi kejuruan.
     */
    public function index()
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();

        if (!$user->hasAnyRole(['admin', 'kaprog'])) {
            abort(403, 'Akses terbatas untuk Admin dan Kaprog.');
        }

        $kompetensis = Kompetensi::forUserJurusan($user)
            ->with('jurusan')
            ->orderBy('id_jurusan', 'asc')
            ->orderBy('tingkat', 'asc')
            ->orderBy('kode_kompetensi', 'asc')
            ->get();

        return view('kejuruan.kompetensi.index', compact('layout', 'setting', 'user', 'kompetensis'));
    }

    /**
     * Form tambah kompetensi baru.
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

        return view('kejuruan.kompetensi.create', compact('layout', 'setting', 'user', 'jurusans'));
    }

    /**
     * Simpan data kompetensi baru.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user->hasAnyRole(['admin', 'kaprog'])) {
            abort(403, 'Akses terbatas untuk Admin dan Kaprog.');
        }

        $accessibleJurusanIds = Jurusan::getAccessibleJurusanIds($user);

        $request->validate([
            'id_jurusan' => 'required|in:' . implode(',', $accessibleJurusanIds),
            'kode_kompetensi' => 'required|string|max:50',
            'nama_kompetensi' => 'required|string|max:255',
            'tingkat' => 'required|string|max:20',
            'semester' => 'required|string|max:20',
            'kkm' => 'nullable|numeric|min:0|max:100',
        ], [
            'id_jurusan.required' => 'Program keahlian/jurusan wajib dipilih.',
            'id_jurusan.in' => 'Anda tidak memiliki hak akses untuk jurusan ini.',
            'kode_kompetensi.required' => 'Kode kompetensi wajib diisi.',
            'nama_kompetensi.required' => 'Nama kompetensi wajib diisi.',
            'tingkat.required' => 'Tingkat kelas wajib dipilih.',
            'semester.required' => 'Semester wajib dipilih.',
            'kkm.numeric' => 'Nilai KKM harus berupa angka.',
        ]);

        Kompetensi::create([
            'id_jurusan' => $request->id_jurusan,
            'kode_kompetensi' => strtoupper(trim($request->kode_kompetensi)),
            'nama_kompetensi' => trim($request->nama_kompetensi),
            'tingkat' => trim($request->tingkat),
            'semester' => trim($request->semester),
            'kkm' => $request->filled('kkm') ? $request->kkm : 75.00,
        ]);

        return redirect('/admin/kompetensi')->with('success', 'Data kompetensi kejuruan berhasil ditambahkan.');
    }

    /**
     * Form edit kompetensi.
     */
    public function edit(string $id_kompetensi)
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();

        if (!$user->hasAnyRole(['admin', 'kaprog'])) {
            abort(403, 'Akses terbatas untuk Admin dan Kaprog.');
        }

        $kompetensi = Kompetensi::forUserJurusan($user)->findOrFail($id_kompetensi);

        $jurusans = Jurusan::forUserJurusan($user)
            ->orderBy('nama_jurusan', 'asc')
            ->get();

        return view('kejuruan.kompetensi.edit', compact('layout', 'setting', 'user', 'kompetensi', 'jurusans'));
    }

    /**
     * Perbarui data kompetensi.
     */
    public function update(Request $request, string $id_kompetensi)
    {
        $user = Auth::user();
        if (!$user->hasAnyRole(['admin', 'kaprog'])) {
            abort(403, 'Akses terbatas untuk Admin dan Kaprog.');
        }

        $kompetensi = Kompetensi::forUserJurusan($user)->findOrFail($id_kompetensi);
        $accessibleJurusanIds = Jurusan::getAccessibleJurusanIds($user);

        $request->validate([
            'id_jurusan' => 'required|in:' . implode(',', $accessibleJurusanIds),
            'kode_kompetensi' => 'required|string|max:50',
            'nama_kompetensi' => 'required|string|max:255',
            'tingkat' => 'required|string|max:20',
            'semester' => 'required|string|max:20',
            'kkm' => 'nullable|numeric|min:0|max:100',
        ], [
            'id_jurusan.required' => 'Program keahlian/jurusan wajib dipilih.',
            'id_jurusan.in' => 'Anda tidak memiliki hak akses untuk jurusan ini.',
            'kode_kompetensi.required' => 'Kode kompetensi wajib diisi.',
            'nama_kompetensi.required' => 'Nama kompetensi wajib diisi.',
            'tingkat.required' => 'Tingkat kelas wajib dipilih.',
            'semester.required' => 'Semester wajib dipilih.',
            'kkm.numeric' => 'Nilai KKM harus berupa angka.',
        ]);

        $kompetensi->update([
            'id_jurusan' => $request->id_jurusan,
            'kode_kompetensi' => strtoupper(trim($request->kode_kompetensi)),
            'nama_kompetensi' => trim($request->nama_kompetensi),
            'tingkat' => trim($request->tingkat),
            'semester' => trim($request->semester),
            'kkm' => $request->filled('kkm') ? $request->kkm : 75.00,
        ]);

        return redirect('/admin/kompetensi')->with('success', 'Data kompetensi kejuruan berhasil diperbarui.');
    }

    /**
     * Hapus data kompetensi.
     */
    public function destroy(string $id_kompetensi)
    {
        $user = Auth::user();
        if (!$user->hasAnyRole(['admin', 'kaprog'])) {
            abort(403, 'Akses terbatas untuk Admin dan Kaprog.');
        }

        $kompetensi = Kompetensi::forUserJurusan($user)->findOrFail($id_kompetensi);
        $kompetensi->delete();

        return redirect('/admin/kompetensi')->with('success', 'Data kompetensi kejuruan berhasil dihapus.');
    }
}
