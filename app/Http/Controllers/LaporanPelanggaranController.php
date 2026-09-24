<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PointSiswa;
use App\Models\Point;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Setting;
use App\Exports\LaporanPelanggaranExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class LaporanPelanggaranController extends Controller
{
    /**
     * Menampilkan halaman utama Laporan Rekapitulasi Pelanggaran Siswa.
     */
    public function index(Request $request)
    {
        $layout = 'layout.app';
        $user = Auth::user();
        $setting = Setting::first();
        $daftarKelas = Kelas::orderBy('nama_kelas', 'asc')->get();

        // Default rentang tanggal (Bulan berjalan)
        $tanggalMulai = $request->input('tanggal_mulai', Carbon::now('Asia/Jakarta')->startOfMonth()->format('Y-m-d'));
        $tanggalSelesai = $request->input('tanggal_selesai', Carbon::now('Asia/Jakarta')->endOfMonth()->format('Y-m-d'));
        $selectedKelas = $request->input('id_kelas', 'all');
        $selectedStatusSp = $request->input('status_sp', 'all');
        $perPage = (int) $request->input('per_page', 10);
        if (!in_array($perPage, [10, 20, 50, 100])) {
            $perPage = 10;
        }

        $rekapData = $this->getRekapData($tanggalMulai, $tanggalSelesai, $selectedKelas, $selectedStatusSp, $setting);

        // Kategori / Top pelanggaran paling sering terjadi
        $topPelanggaran = PointSiswa::whereBetween('created_at', [$tanggalMulai . ' 00:00:00', $tanggalSelesai . ' 23:59:59'])
            ->when($selectedKelas !== 'all', function ($q) use ($selectedKelas) {
                $q->where('id_kelas', $selectedKelas);
            })
            ->selectRaw('id_point, count(*) as total_kasus, sum(skor_point) as total_skor')
            ->groupBy('id_point')
            ->with('point')
            ->orderByDesc('total_kasus')
            ->take(5)
            ->get();

        return view('pelanggaran.laporan_pelanggaran', [
            'layout' => $layout,
            'user' => $user,
            'setting' => $setting,
            'daftarKelas' => $daftarKelas,
            'tanggalMulai' => $tanggalMulai,
            'tanggalSelesai' => $tanggalSelesai,
            'selectedKelas' => $selectedKelas,
            'selectedStatusSp' => $selectedStatusSp,
            'dataRekap' => $rekapData['rekap_siswa'],
            'summary' => $rekapData['summary'],
            'topPelanggaran' => $topPelanggaran,
        ]);
    }

    /**
     * Ekspor Laporan Rekapitulasi Pelanggaran ke format Excel (.xlsx).
     */
    public function exportExcel(Request $request)
    {
        $setting = Setting::first();
        $tanggalMulai = $request->input('tanggal_mulai', Carbon::now('Asia/Jakarta')->startOfMonth()->format('Y-m-d'));
        $tanggalSelesai = $request->input('tanggal_selesai', Carbon::now('Asia/Jakarta')->endOfMonth()->format('Y-m-d'));
        $selectedKelas = $request->input('id_kelas', 'all');
        $selectedStatusSp = $request->input('status_sp', 'all');

        $rekapData = $this->getRekapData($tanggalMulai, $tanggalSelesai, $selectedKelas, $selectedStatusSp, $setting);

        $namaKelas = 'Semua Kelas';
        if ($selectedKelas !== 'all') {
            $k = Kelas::find($selectedKelas);
            if ($k) $namaKelas = $k->nama_kelas;
        }

        $fileName = 'Laporan_Pelanggaran_Siswa_' . date('Ymd_His') . '.xlsx';

        return Excel::download(
            new LaporanPelanggaranExport($rekapData['rekap_siswa'], $tanggalMulai, $tanggalSelesai, $namaKelas),
            $fileName
        );
    }

    /**
     * Ekspor Laporan Rekapitulasi Pelanggaran ke format PDF resmi.
     */
    public function exportPdf(Request $request)
    {
        $setting = Setting::first();
        $tanggalMulai = $request->input('tanggal_mulai', Carbon::now('Asia/Jakarta')->startOfMonth()->format('Y-m-d'));
        $tanggalSelesai = $request->input('tanggal_selesai', Carbon::now('Asia/Jakarta')->endOfMonth()->format('Y-m-d'));
        $selectedKelas = $request->input('id_kelas', 'all');
        $selectedStatusSp = $request->input('status_sp', 'all');

        $rekapData = $this->getRekapData($tanggalMulai, $tanggalSelesai, $selectedKelas, $selectedStatusSp, $setting);

        $namaKelas = 'Semua Kelas';
        if ($selectedKelas !== 'all') {
            $k = Kelas::find($selectedKelas);
            if ($k) $namaKelas = $k->nama_kelas;
        }

        $pdf = Pdf::loadView('pelanggaran.laporan_pelanggaran_pdf', [
            'setting' => $setting,
            'tanggalMulai' => $tanggalMulai,
            'tanggalSelesai' => $tanggalSelesai,
            'namaKelas' => $namaKelas,
            'dataRekap' => $rekapData['rekap_siswa'],
            'summary' => $rekapData['summary'],
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('Laporan_Pelanggaran_Siswa_' . date('Ymd_His') . '.pdf');
    }

    /**
     * AJAX endpoint untuk mendapatkan riwayat kronologi pelanggaran siswa tertentu.
     */
    public function detailRiwayat(string $id_siswa, Request $request)
    {
        $siswa = Siswa::with(['kelas.waliKelas', 'jurusan'])->findOrFail($id_siswa);
        $setting = Setting::first();

        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalSelesai = $request->input('tanggal_selesai');

        $query = PointSiswa::where('id_siswa', $id_siswa)
            ->with(['point', 'guru'])
            ->orderBy('created_at', 'desc');

        if ($tanggalMulai && $tanggalSelesai) {
            $query->whereBetween('created_at', [$tanggalMulai . ' 00:00:00', $tanggalSelesai . ' 23:59:59']);
        }

        $pelanggaran = $query->get();
        $totalPoin = $pelanggaran->sum('skor_point');
        $statusSp = $setting->getSpStatus($totalPoin);

        return response()->json([
            'success' => true,
            'siswa' => [
                'nama' => $siswa->nama_siswa,
                'nis' => $siswa->nis,
                'kelas' => $siswa->kelas->nama_kelas ?? '-',
                'wali_kelas' => $siswa->kelas->waliKelas->nama_guru ?? '-',
                'foto' => $siswa->foto ? asset('storage/siswa/' . $siswa->foto) : null,
            ],
            'total_poin' => $totalPoin,
            'status_sp' => $statusSp,
            'total_kasus' => $pelanggaran->count(),
            'riwayat' => $pelanggaran->map(function ($item) {
                $tglStr = $item->created_at 
                    ? Carbon::parse($item->created_at)->locale('id')->translatedFormat('d F Y H:i') 
                    : ($item->tanggal ?? '-');

                return [
                    'id' => $item->id_point_siswa,
                    'tanggal' => $tglStr,
                    'nama_point' => $item->point->nama_point ?? 'Pelanggaran',
                    'skor_point' => $item->skor_point,
                    'kategori' => $item->point->jenis_point ?? 'Kedisiplinan',
                    'guru' => $item->guru->nama_guru ?? 'Petugas / Guru Piket',
                ];
            })
        ]);
    }

    /**
     * Helper untuk menghitung rekap data pelanggaran berdasarkan filter.
     */
    private function getRekapData(string $tanggalMulai, string $tanggalSelesai, string $selectedKelas, string $selectedStatusSp, Setting $setting): array
    {
        $query = PointSiswa::with(['siswa.kelas.waliKelas', 'point', 'guru'])
            ->whereBetween('created_at', [$tanggalMulai . ' 00:00:00', $tanggalSelesai . ' 23:59:59']);

        if ($selectedKelas !== 'all') {
            $query->where('id_kelas', $selectedKelas);
        }

        $semuaKasus = $query->get();

        // Group per siswa
        $grouped = $semuaKasus->groupBy('id_siswa');

        $rekapSiswa = [];
        $totalPoinSemua = 0;
        $totalKasusSemua = 0;
        $totalSpSiswa = 0;

        foreach ($grouped as $idSiswa => $pelanggaranList) {
            $siswa = $pelanggaranList->first()->siswa;
            if (!$siswa) continue;

            $totalPoinSiswa = $pelanggaranList->sum('skor_point');
            $statusSp = $setting->getSpStatus($totalPoinSiswa);

            // Filter status SP jika diminta
            if ($selectedStatusSp === 'sp_only' && $statusSp === 'Aman') {
                continue;
            } elseif ($selectedStatusSp === 'aman' && $statusSp !== 'Aman') {
                continue;
            }

            if ($statusSp !== 'Aman') {
                $totalSpSiswa++;
            }

            $totalPoinSemua += $totalPoinSiswa;
            $totalKasusSemua += $pelanggaranList->count();

            $rekapSiswa[] = [
                'siswa' => $siswa,
                'total_poin' => $totalPoinSiswa,
                'total_kasus' => $pelanggaranList->count(),
                'status_sp' => $statusSp,
                'pelanggaran_list' => $pelanggaranList,
            ];
        }

        // Sort by total_poin DESC
        usort($rekapSiswa, function ($a, $b) {
            return $b['total_poin'] <=> $a['total_poin'];
        });

        return [
            'rekap_siswa' => $rekapSiswa,
            'summary' => [
                'total_kasus' => $totalKasusSemua,
                'total_poin' => $totalPoinSemua,
                'total_siswa_pelanggar' => count($rekapSiswa),
                'total_siswa_sp' => $totalSpSiswa,
            ]
        ];
    }
}
