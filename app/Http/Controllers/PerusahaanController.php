<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Perusahaan;
use App\Models\SiswaPkl;
use App\Models\Setting;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Guru;

class PerusahaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();

        $today = now()->format('Y-m-d');

        $perusahaan = Perusahaan::withCount([
            'siswaPkl as siswa_aktif_count' => function ($query) use ($today) {
                $query->where('status', '!=', 'selesai')
                      ->where('tanggal_mulai', '<=', $today)
                      ->where('tanggal_selesai', '>=', $today);
            },
            'siswaPkl as siswa_ditempatkan_count' => function ($query) use ($today) {
                $query->where('status', '!=', 'selesai')
                      ->where('tanggal_mulai', '>', $today);
            },
            'siswaPkl as siswa_total_count'
        ])
        ->with([
            'guru',
            'siswaPkl' => function ($query) {
                $query->with(['siswa.kelas', 'kelas'])->orderBy('tanggal_mulai', 'desc');
            }
        ])
        ->orderBy('nama_perusahaan', 'asc')
        ->get();

        $siswaList = Siswa::with('kelas')->orderBy('nama_siswa', 'asc')->get();
        $kelasList = Kelas::orderBy('nama_kelas', 'asc')->get();
        $guruList = Guru::orderBy('nama_guru', 'asc')->get();
        $totalMitra = $perusahaan->count();
        $totalSiswaPklAktif = SiswaPkl::where('status', '!=', 'selesai')
                                     ->where('tanggal_mulai', '<=', $today)
                                     ->where('tanggal_selesai', '>=', $today)
                                     ->count();
        $totalSiswaDitempatkan = SiswaPkl::where('status', '!=', 'selesai')
                                         ->where('tanggal_mulai', '>', $today)
                                         ->count();

        return view('bkk.data_perusahaan', compact(
            'layout', 'perusahaan', 'setting', 'user', 
            'siswaList', 'kelasList', 'guruList', 'totalMitra', 'totalSiswaPklAktif', 'totalSiswaDitempatkan'
        ));
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
        $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'alamat_perusahaan' => 'required|string',
            'penanggung_jawab' => 'nullable|string|max:255',
            'id_guru' => 'nullable|exists:guru,id_guru',
            'pic' => 'nullable|string|max:255',
            'kontak_pic' => 'nullable|string|max:50',
        ]);

        Perusahaan::create([
            'nama_perusahaan' => $request->nama_perusahaan,
            'alamat_perusahaan' => $request->alamat_perusahaan,
            'penanggung_jawab' => $request->penanggung_jawab ?: '-',
            'id_guru' => $request->id_guru ?: null,
            'pic' => $request->pic,
            'kontak_pic' => $request->kontak_pic,
        ]);

        return redirect()->route('admin.perusahaan.index')->with('success', 'Perusahaan berhasil ditambahkan.');
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
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'alamat_perusahaan' => 'required|string',
            'penanggung_jawab' => 'nullable|string|max:255',
            'id_guru' => 'nullable|exists:guru,id_guru',
            'pic' => 'nullable|string|max:255',
            'kontak_pic' => 'nullable|string|max:50',
        ]);

        $perusahaan = Perusahaan::findOrFail($id);
        $perusahaan->nama_perusahaan = $request->nama_perusahaan;
        $perusahaan->alamat_perusahaan = $request->alamat_perusahaan;
        $perusahaan->penanggung_jawab = $request->penanggung_jawab ?: '-';
        $perusahaan->id_guru = $request->id_guru ?: null;
        $perusahaan->pic = $request->pic;
        $perusahaan->kontak_pic = $request->kontak_pic;
        $perusahaan->save();

        return redirect()->back()->with('success', 'Data perusahaan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Ambil data perusahaan
        $data = Perusahaan::findOrFail($id);

        // Hapus semua siswa PKL yang terkait dengan perusahaan ini
        SiswaPkl::where('id_perusahaan', $data->id_perusahaan)->delete();

        // Hapus perusahaan
        $data->delete();

        return redirect()->route('admin.perusahaan.index')
                        ->with('success', 'Data Perusahaan dan siswa PKL terkait berhasil dihapus');
    }
}
