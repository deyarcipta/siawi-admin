<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;
use App\Models\SiswaPkl;
use App\Models\AbsensiPkl;
use App\Models\Perusahaan;
use App\Models\Kelas;
use App\Models\Siswa;
use Carbon\Carbon;

class AbsensiPklAdminController extends Controller
{
    /**
     * Tampilkan monitoring kehadiran siswa PKL harian.
     */
    public function index(Request $request)
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();

        $tanggal = $request->query('tanggal', now()->toDateString());
        $idPerusahaan = $request->query('id_perusahaan');
        $idKelas = $request->query('id_kelas');
        $filterStatus = $request->query('status');

        // Ambil seluruh siswa yang sedang aktif masa PKL-nya pada tanggal tersebut
        $siswaPklQuery = SiswaPkl::with(['siswa.kelas', 'kelas', 'perusahaan'])
            ->where('status', '!=', 'selesai')
            ->where('tanggal_mulai', '<=', $tanggal)
            ->where('tanggal_selesai', '>=', $tanggal);

        if ($idPerusahaan) {
            $siswaPklQuery->where('id_perusahaan', $idPerusahaan);
        }

        if ($idKelas) {
            $siswaPklQuery->where('id_kelas', $idKelas);
        }

        $siswaPklList = $siswaPklQuery->get();

        // Ambil data absensi untuk siswa-siswa tersebut pada tanggal yang dipilih
        $siswaIds = $siswaPklList->pluck('id_siswa')->toArray();
        $absensiMap = AbsensiPkl::whereIn('id_siswa', $siswaIds)
            ->where('tanggal', $tanggal)
            ->get()
            ->keyBy('id_siswa');

        // Gabungkan data siswa PKL dengan record absensinya
        $dataList = $siswaPklList->map(function ($item) use ($absensiMap) {
            $absen = $absensiMap->get($item->id_siswa);
            $item->absensi = $absen;
            $item->status_kehadiran = $absen ? $absen->status : 'belum_absen';
            return $item;
        });

        // Hitung statistik ringkasan
        $totalSiswaPkl = $dataList->count();
        $totalHadir = $dataList->where('status_kehadiran', 'hadir')->count();
        $totalBelumAbsen = $dataList->where('status_kehadiran', 'belum_absen')->count();

        // Filter status kehadiran jika diminta
        if ($filterStatus && $filterStatus !== 'semua') {
            $dataList = $dataList->where('status_kehadiran', $filterStatus);
        }

        $perusahaanList = Perusahaan::orderBy('nama_perusahaan', 'asc')->get();
        $kelasList = Kelas::orderBy('nama_kelas', 'asc')->get();

        return view('bkk.presensi_pkl.index', compact(
            'layout', 'setting', 'user', 'dataList',
            'tanggal', 'idPerusahaan', 'idKelas', 'filterStatus',
            'totalSiswaPkl', 'totalHadir', 'totalBelumAbsen',
            'perusahaanList', 'kelasList'
        ));
    }

    /**
     * Hapus record presensi siswa PKL.
     */
    public function destroy($id)
    {
        $absen = AbsensiPkl::findOrFail($id);
        $absen->delete();

        return redirect()->back()->with('success', 'Data presensi berhasil dihapus.');
    }

    /**
     * Reset kuncian perangkat (Lock Device) untuk siswa PKL.
     */
    public function resetDevice($id_siswa)
    {
        $siswa = Siswa::findOrFail($id_siswa);
        $siswa->update([
            'device_id' => null,
            'device_name' => null,
            'device_locked_at' => null,
        ]);

        return redirect()->back()->with('success', "Kuncian perangkat untuk {$siswa->nama_siswa} berhasil di-reset. Siswa kini dapat login di perangkat baru.");
    }
}

