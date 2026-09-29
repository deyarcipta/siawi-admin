<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Alumni;
use App\Models\Jurusan;
use App\Models\Setting;
use App\Exports\AlumniExport;
use Maatwebsite\Excel\Facades\Excel;

class DataAlumniController extends Controller
{
    public function index(Request $request)
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        $jurusan = Jurusan::all();

        $query = Alumni::with('jurusan');

        if ($request->filled('id_jurusan')) {
            $query->where('id_jurusan', $request->id_jurusan);
        }

        if ($request->filled('tahun_lulus')) {
            $query->where('tahun_lulus', $request->tahun_lulus);
        }

        $alumni = $query->orderBy('tahun_lulus', 'desc')->orderBy('nama', 'asc')->get();
        $tahunLulusList = Alumni::select('tahun_lulus')->distinct()->whereNotNull('tahun_lulus')->orderBy('tahun_lulus', 'desc')->pluck('tahun_lulus');

        return view('dataAlumni.index', compact('layout', 'setting', 'user', 'alumni', 'jurusan', 'tahunLulusList'));
    }

    public function create()
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        $jurusan = Jurusan::all();

        return view('dataAlumni.tambah_alumni', compact('layout', 'setting', 'user', 'jurusan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nis' => 'nullable|string|max:50',
            'nisn' => 'nullable|string|max:50',
            'id_jurusan' => 'required',
            'tahun_lulus' => 'required|string|max:10',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $fotoName = 'avatar.jpg';
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $fotoName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
            $file->storeAs('public/foto-siswa', $fotoName);
        }

        Alumni::create([
            'nama' => $request->nama,
            'nis' => $request->nis ?? '-',
            'nisn' => $request->nisn ?? '-',
            'id_jurusan' => $request->id_jurusan,
            'tahun_lulus' => $request->tahun_lulus,
            'foto' => $fotoName,
            'status' => $request->status ?? '-',
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'email' => $request->email,
            'jenis_kelamin' => $request->jenis_kelamin,
            'agama' => $request->agama,
        ]);

        return redirect('/admin/dataAlumni')->with('success', 'Data alumni berhasil ditambahkan.');
    }

    public function show($id)
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        $detail = Alumni::with('jurusan')->findOrFail($id);

        return view('dataAlumni.detail_alumni', compact('layout', 'setting', 'user', 'detail'));
    }

    public function edit($id)
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        $edit = Alumni::findOrFail($id);
        $jurusan = Jurusan::all();

        return view('dataAlumni.edit_alumni', compact('layout', 'setting', 'user', 'edit', 'jurusan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nis' => 'nullable|string|max:50',
            'nisn' => 'nullable|string|max:50',
            'id_jurusan' => 'required',
            'tahun_lulus' => 'required|string|max:10',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $alumni = Alumni::findOrFail($id);
        $fotoName = $alumni->foto;

        if ($request->hasFile('foto')) {
            if ($alumni->foto && $alumni->foto != 'avatar.jpg') {
                Storage::disk('public')->delete('foto-siswa/' . $alumni->foto);
            }
            $file = $request->file('foto');
            $fotoName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
            $file->storeAs('public/foto-siswa', $fotoName);
        }

        $alumni->update([
            'nama' => $request->nama,
            'nis' => $request->nis ?? '-',
            'nisn' => $request->nisn ?? '-',
            'id_jurusan' => $request->id_jurusan,
            'tahun_lulus' => $request->tahun_lulus,
            'foto' => $fotoName,
            'status' => $request->status ?? '-',
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'email' => $request->email,
            'jenis_kelamin' => $request->jenis_kelamin,
            'agama' => $request->agama,
        ]);

        return redirect('/admin/dataAlumni')->with('success', 'Data alumni berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $alumni = Alumni::findOrFail($id);
        if ($alumni->foto && $alumni->foto != 'avatar.jpg') {
            Storage::disk('public')->delete('foto-siswa/' . $alumni->foto);
        }
        $alumni->delete();

        return redirect('/admin/dataAlumni')->with('success', 'Data alumni berhasil dihapus.');
    }

    public function download()
    {
        return Excel::download(new AlumniExport, 'data_alumni_' . date('Y-m-d') . '.xlsx');
    }
}
