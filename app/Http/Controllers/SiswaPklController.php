<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SiswaPkl;
use App\Models\Perusahaan;
use App\Models\Setting;
use App\Models\Siswa;
use App\Models\Kelas;

class SiswaPklController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        $kelasList = Kelas::orderBy('nama_kelas', 'asc')->get();
        $siswaList = Siswa::with('kelas')->orderBy('nama_siswa', 'asc')->get();
        $perusahaan = Perusahaan::orderBy('nama_perusahaan', 'asc')->get();

        $today = now()->format('Y-m-d');
        $query = SiswaPkl::with(['siswa.kelas', 'kelas', 'perusahaan']);

        if ($request->filled('id_perusahaan')) {
            $query->where('id_perusahaan', $request->id_perusahaan);
        }
        if ($request->filled('id_kelas')) {
            $query->where('id_kelas', $request->id_kelas);
        }
        if ($request->filled('status')) {
            if ($request->status === 'belum_mulai') {
                $query->where('status', '!=', 'selesai')->where('tanggal_mulai', '>', $today);
            } elseif ($request->status === 'aktif' || $request->status === 'PKL') {
                $query->where('status', '!=', 'selesai')
                      ->where('tanggal_mulai', '<=', $today)
                      ->where('tanggal_selesai', '>=', $today);
            } elseif ($request->status === 'selesai') {
                $query->where(function ($q) use ($today) {
                    $q->where('status', 'selesai')->orWhere('tanggal_selesai', '<', $today);
                });
            }
        }

        $data_siswa_pkl = $query->orderBy('tanggal_mulai', 'desc')->get();

        $totalPklDitempatkan = SiswaPkl::where('status', '!=', 'selesai')->where('tanggal_mulai', '>', $today)->count();
        $totalPklAktif = SiswaPkl::where('status', '!=', 'selesai')
                                 ->where('tanggal_mulai', '<=', $today)
                                 ->where('tanggal_selesai', '>=', $today)
                                 ->count();
        $totalPklSelesai = SiswaPkl::where('status', 'selesai')->orWhere('tanggal_selesai', '<', $today)->count();
        $totalMitra = Perusahaan::count();

        return view('bkk.data_siswa_pkl', compact(
            'layout', 'data_siswa_pkl', 'setting', 'user', 
            'kelasList', 'siswaList', 'perusahaan',
            'totalPklDitempatkan', 'totalPklAktif', 'totalPklSelesai', 'totalMitra'
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
            'id_siswa' => 'required',
            'id_perusahaan' => 'required|exists:perusahaan,id_perusahaan',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'status' => 'required|in:PKL,selesai',
        ]);

        $siswaIds = is_array($request->id_siswa) ? $request->id_siswa : [$request->id_siswa];
        $count = 0;

        foreach ($siswaIds as $siswaId) {
            if (!$siswaId) continue;
            $siswa = Siswa::find($siswaId);
            if (!$siswa) continue;

            $kelasId = $request->id_kelas ?: $siswa->id_kelas;

            SiswaPkl::create([
                'id_siswa' => $siswaId,
                'id_kelas' => $kelasId,
                'id_perusahaan' => $request->id_perusahaan,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'status' => $request->status,
            ]);
            $count++;
        }

        return redirect()->back()->with('success', "Berhasil menempatkan {$count} siswa PKL ke perusahaan.");
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
        $data = SiswaPkl::findOrFail($id);

        if ($request->has('quick_status')) {
            $request->validate([
                'status' => 'required|in:PKL,selesai',
            ]);
            $data->status = $request->status;
            $data->save();
            return redirect()->back()->with('success', 'Status PKL siswa berhasil diperbarui.');
        }

        $request->validate([
            'id_siswa' => 'required',
            'id_perusahaan' => 'required',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date',
        ]);

        $siswa = Siswa::find($request->id_siswa);
        $data->id_kelas = $request->id_kelas ?: ($siswa?->id_kelas ?? $data->id_kelas);
        $data->id_siswa = $request->id_siswa;
        $data->id_perusahaan = $request->id_perusahaan;
        $data->tanggal_mulai = $request->tanggal_mulai;
        $data->tanggal_selesai = $request->tanggal_selesai;
        if ($request->filled('status')) {
            $data->status = $request->status;
        }
        $data->save();

        return redirect()->back()->with('success', 'Data siswa PKL berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = SiswaPkl::findOrFail($id);
        $data->delete();

        return redirect()->back()->with('success', 'Data Siswa PKL berhasil dihapus');
    }
}
