<?php

namespace App\Http\Controllers\Kejuruan;

use App\Exports\LaporanPraktikExport;
use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\JurnalPraktik;
use App\Models\Kelas;
use App\Models\Lab;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class LaporanPraktikController extends Controller
{
    /**
     * Halaman Rekapitulasi Laporan Praktik Kejuruan.
     */
    public function index(Request $request)
    {
        $layout = 'layout.app';
        $setting = Setting::find(1);
        $user = Auth::user();

        $periode = $request->input('periode', 'mingguan'); // 'mingguan', 'bulanan', 'tahunan'
        $query = JurnalPraktik::forUserJurusan($user)
            ->with(['jadwalPraktik.kelas', 'jadwalPraktik.lab', 'jadwalPraktik.guru', 'jadwalPraktik.mapel', 'approver']);

        // Filter berdasarkan periode waktu
        $now = Carbon::now();
        $periodeLabel = '';

        if ($periode === 'mingguan') {
            $tglAwal = $request->input('tanggal_awal', $now->copy()->startOfWeek()->toDateString());
            $tglAkhir = $request->input('tanggal_akhir', $now->copy()->endOfWeek()->toDateString());
            $query->whereBetween('tanggal', [$tglAwal, $tglAkhir]);
            $periodeLabel = Carbon::parse($tglAwal)->translatedFormat('d M Y') . ' s.d. ' . Carbon::parse($tglAkhir)->translatedFormat('d M Y');
        } elseif ($periode === 'bulanan') {
            $bulan = $request->input('bulan', $now->month);
            $tahun = $request->input('tahun', $now->year);
            $query->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
            $periodeLabel = Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y');
        } elseif ($periode === 'tahunan') {
            $tahun = $request->input('tahun', $now->year);
            $query->whereYear('tanggal', $tahun);
            $periodeLabel = 'Tahun ' . $tahun;
        }

        // Filter tambahan
        if ($request->filled('id_guru')) {
            $query->whereHas('jadwalPraktik', function ($q) use ($request) {
                $q->where('id_guru', $request->id_guru);
            });
        }

        if ($request->filled('id_lab')) {
            $query->whereHas('jadwalPraktik', function ($q) use ($request) {
                $q->where('id_lab', $request->id_lab);
            });
        }

        if ($request->filled('status_approval')) {
            $query->where('status_approval', $request->status_approval);
        }

        $jurnals = $query->orderBy('tanggal', 'asc')->get();

        // Statistik ringkasan
        $stats = [
            'total_pertemuan' => $jurnals->count(),
            'total_siswa_hadir' => $jurnals->sum('jumlah_hadir'),
            'disetujui' => $jurnals->where('status_approval', 'disetujui')->count(),
            'menunggu' => $jurnals->where('status_approval', 'menunggu')->count(),
            'ditolak' => $jurnals->where('status_approval', 'ditolak')->count(),
        ];

        // Dropdown filter
        $labs = Lab::forUserJurusan($user)->orderBy('nama_lab', 'asc')->get();
        $kelas = Kelas::forUserJurusan($user)->orderBy('nama_kelas', 'asc')->get();
        $gurus = Guru::orderBy('nama_guru', 'asc')->get();

        return view('kejuruan.laporan_praktik.index', compact(
            'layout', 'setting', 'user', 'jurnals', 'stats', 'periode',
            'periodeLabel', 'labs', 'kelas', 'gurus'
        ));
    }

    /**
     * Persetujuan (Approval) Laporan oleh Kaprog atau Admin.
     */
    public function approve(Request $request, string $id)
    {
        $user = Auth::user();

        if (!$user->hasAnyRole(['admin', 'kaprog'])) {
            abort(403, 'Hanya Kaprog dan Admin yang berhak menyetujui laporan praktik.');
        }

        $jurnal = JurnalPraktik::forUserJurusan($user)->findOrFail($id);

        $request->validate([
            'status' => 'required|in:disetujui,ditolak',
            'catatan_kaprog' => 'nullable|string',
        ]);

        $jurnal->update([
            'status_approval' => $request->status,
            'approved_by' => $user->id_guru,
            'approved_at' => now(),
            'catatan_kaprog' => $request->catatan_kaprog ? trim($request->catatan_kaprog) : null,
        ]);

        $statusText = $request->status === 'disetujui' ? 'disetujui' : 'ditolak dengan catatan';
        return back()->with('success', "Laporan jurnal praktik berhasil {$statusText}.");
    }

    /**
     * Ekspor Laporan ke format PDF resmi ber-kop sekolah.
     */
    public function exportPdf(Request $request)
    {
        $setting = Setting::find(1);
        $user = Auth::user();

        $data = $this->getFilteredReportData($request, $user);
        $jurnals = $data['jurnals'];
        $periodeLabel = $data['periodeLabel'];

        // Ambil Kaprog penanggung jawab untuk tanda tangan
        $kaprog = null;
        if ($user->hasRole('kaprog')) {
            $kaprog = $user;
        } else {
            // Ambil dari jurusan jurnal pertama jika ada
            $firstJurnal = $jurnals->first();
            if ($firstJurnal && $firstJurnal->jadwalPraktik && $firstJurnal->jadwalPraktik->lab && $firstJurnal->jadwalPraktik->lab->jurusan) {
                $kaprog = $firstJurnal->jadwalPraktik->lab->jurusan->kaprog->first();
            }
        }

        $pdf = Pdf::loadView('kejuruan.laporan_praktik.pdf', [
            'jurnals' => $jurnals,
            'setting' => $setting,
            'periodeLabel' => $periodeLabel,
            'user' => $user,
            'kaprog' => $kaprog,
        ])->setPaper('a4', 'landscape');

        $filename = 'Laporan_Praktik_Kejuruan_' . str_replace(' ', '_', $periodeLabel) . '.pdf';
        return $pdf->download($filename);
    }

    /**
     * Ekspor Laporan ke format Excel (.xlsx).
     */
    public function exportExcel(Request $request)
    {
        $setting = Setting::find(1);
        $user = Auth::user();

        $data = $this->getFilteredReportData($request, $user);
        $jurnals = $data['jurnals'];
        $periodeLabel = $data['periodeLabel'];

        $filename = 'Laporan_Praktik_Kejuruan_' . str_replace(' ', '_', $periodeLabel) . '.xlsx';
        return Excel::download(new LaporanPraktikExport($jurnals, $setting, $periodeLabel, $user), $filename);
    }

    /**
     * Helper pemfilteran query laporan.
     */
    protected function getFilteredReportData(Request $request, $user): array
    {
        $periode = $request->input('periode', 'mingguan');
        $query = JurnalPraktik::forUserJurusan($user)
            ->with(['jadwalPraktik.kelas', 'jadwalPraktik.lab', 'jadwalPraktik.guru', 'jadwalPraktik.mapel', 'approver']);

        $now = Carbon::now();
        $periodeLabel = '';

        if ($periode === 'mingguan') {
            $tglAwal = $request->input('tanggal_awal', $now->copy()->startOfWeek()->toDateString());
            $tglAkhir = $request->input('tanggal_akhir', $now->copy()->endOfWeek()->toDateString());
            $query->whereBetween('tanggal', [$tglAwal, $tglAkhir]);
            $periodeLabel = Carbon::parse($tglAwal)->translatedFormat('d M Y') . ' s.d. ' . Carbon::parse($tglAkhir)->translatedFormat('d M Y');
        } elseif ($periode === 'bulanan') {
            $bulan = $request->input('bulan', $now->month);
            $tahun = $request->input('tahun', $now->year);
            $query->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
            $periodeLabel = Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y');
        } elseif ($periode === 'tahunan') {
            $tahun = $request->input('tahun', $now->year);
            $query->whereYear('tanggal', $tahun);
            $periodeLabel = 'Tahun ' . $tahun;
        }

        if ($request->filled('id_guru')) {
            $query->whereHas('jadwalPraktik', function ($q) use ($request) {
                $q->where('id_guru', $request->id_guru);
            });
        }

        if ($request->filled('id_lab')) {
            $query->whereHas('jadwalPraktik', function ($q) use ($request) {
                $q->where('id_lab', $request->id_lab);
            });
        }

        if ($request->filled('status_approval')) {
            $query->where('status_approval', $request->status_approval);
        }

        $jurnals = $query->orderBy('tanggal', 'asc')->get();

        return [
            'jurnals' => $jurnals,
            'periodeLabel' => $periodeLabel,
        ];
    }
}
