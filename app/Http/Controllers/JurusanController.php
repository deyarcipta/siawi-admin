<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Jurusan;
use App\Models\Setting;
use DB;

class JurusanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();

        if (!$user->hasAnyRole(['admin', 'kaprog'])) {
            abort(403, 'Akses terbatas untuk Admin dan Kaprog.');
        }

        $jurusan = Jurusan::forUserJurusan($user)
            ->with(['kaprog'])
            ->orderBy('nama_jurusan', 'asc')
            ->get();

        return view('dataMaster.jurusan.data_jurusan', compact('layout', 'jurusan', 'setting', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();

        if (!$user->hasRole('admin')) {
            return redirect('/admin/jurusan')->with('error', 'Hanya Admin yang dapat menambah program keahlian baru.');
        }

        return view('dataMaster.jurusan.tambah_jurusan', compact('layout', 'setting', 'user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user->hasRole('admin')) {
            abort(403, 'Hanya Admin yang dapat menambah jurusan baru.');
        }

        $request->validate([
            'kode_jurusan' => 'required|string|max:50|unique:jurusan,kode_jurusan',
            'nama_jurusan' => 'required|string|max:255',
            'aktif' => 'nullable|boolean',
        ], [
            'kode_jurusan.required' => 'Kode jurusan wajib diisi.',
            'kode_jurusan.unique' => 'Kode jurusan sudah terdaftar.',
            'nama_jurusan.required' => 'Nama jurusan wajib diisi.',
        ]);

        $modulKejuruan = [
            'tkk' => $request->boolean('modul_tkk', true),
            'ukk' => $request->boolean('modul_ukk', true),
            'praktik' => $request->boolean('modul_praktik', true),
            'portofolio' => $request->boolean('modul_portofolio', true),
            'lab' => $request->boolean('modul_lab', true),
        ];

        Jurusan::create([
            'kode_jurusan' => strtoupper(trim($request->kode_jurusan)),
            'nama_jurusan' => trim($request->nama_jurusan),
            'aktif' => $request->has('aktif') ? (bool)$request->aktif : true,
            'modul_kejuruan' => json_encode($modulKejuruan),
        ]);

        return redirect('/admin/jurusan')->with('success', 'Program keahlian berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id_jurusan)
    {
        return $this->edit($id_jurusan);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id_jurusan)
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();

        if (!$user->hasAnyRole(['admin', 'kaprog'])) {
            abort(403, 'Akses terbatas untuk Admin dan Kaprog.');
        }

        $edit = Jurusan::forUserJurusan($user)->findOrFail($id_jurusan);

        return view('dataMaster.jurusan.edit_jurusan', compact('layout', 'edit', 'setting', 'user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id_jurusan)
    {
        $user = Auth::user();
        if (!$user->hasAnyRole(['admin', 'kaprog'])) {
            abort(403, 'Akses terbatas untuk Admin dan Kaprog.');
        }

        $jurusan = Jurusan::forUserJurusan($user)->findOrFail($id_jurusan);

        $request->validate([
            'kode_jurusan' => 'required|string|max:50|unique:jurusan,kode_jurusan,' . $id_jurusan . ',id_jurusan',
            'nama_jurusan' => 'required|string|max:255',
            'aktif' => 'nullable|boolean',
        ], [
            'kode_jurusan.required' => 'Kode jurusan wajib diisi.',
            'kode_jurusan.unique' => 'Kode jurusan sudah digunakan oleh jurusan lain.',
            'nama_jurusan.required' => 'Nama jurusan wajib diisi.',
        ]);

        $updateData = [
            'kode_jurusan' => strtoupper(trim($request->kode_jurusan)),
            'nama_jurusan' => trim($request->nama_jurusan),
        ];

        if ($user->hasRole('admin')) {
            $updateData['aktif'] = $request->has('aktif') ? (bool)$request->aktif : false;
            $updateData['modul_kejuruan'] = json_encode([
                'tkk' => $request->boolean('modul_tkk'),
                'ukk' => $request->boolean('modul_ukk'),
                'praktik' => $request->boolean('modul_praktik'),
                'portofolio' => $request->boolean('modul_portofolio'),
                'lab' => $request->boolean('modul_lab'),
            ]);
        }

        $jurusan->update($updateData);

        return redirect('/admin/jurusan')->with('success', 'Data program keahlian berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id_jurusan)
    {
        $user = Auth::user();
        if (!$user->hasRole('admin')) {
            abort(403, 'Hanya Admin yang memiliki hak untuk menghapus data jurusan.');
        }

        $jurusan = Jurusan::findOrFail($id_jurusan);

        // Validasi relasi data sebelum dihapus
        if ($jurusan->kelas()->exists()) {
            return redirect('/admin/jurusan')->with('error', 'Jurusan tidak dapat dihapus karena masih digunakan pada data Kelas.');
        }

        $jurusan->delete();
        return redirect('/admin/jurusan')->with('success', 'Jurusan berhasil dihapus.');
    }
}
