<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratPeringatan;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class SuratPeringatanController extends Controller
{
    /**
     * Display a listing of the SP.
     */
    public function index()
    {
        $layout = 'layout.app';
        $setting = Setting::find(1);
        $user = Auth::user();

        $isWaliKelasOnly = $user && $user->hasRole('wali_kelas') && !$user->hasAnyRole(['admin', 'kesiswaan', 'kurikulum']);
        $kelasWaliIds = $isWaliKelasOnly ? \App\Models\Kelas::where('id_guru', $user->id_guru)->pluck('id_kelas') : collect();

        // 1. Get issued SPs
        $query = SuratPeringatan::with('siswa.kelas', 'kelas');
        if ($isWaliKelasOnly) {
            $query->whereIn('id_kelas', $kelasWaliIds);
        }
        $suratPeringatan = $query->orderBy('created_at', 'desc')->get();

        // 2. SP Configuration & Thresholds
        $spSettings = $setting->sp_settings ?? [];
        $spRules = $spSettings['sp_rules'] ?? ['1' => 25, '2' => 50, '3' => 75];
        $minThreshold = !empty($spRules) ? min($spRules) : 25;

        // 3. Query students reaching point thresholds
        $siswaQuery = \App\Models\Siswa::query();
        if ($isWaliKelasOnly) {
            $siswaQuery->whereIn('id_kelas', $kelasWaliIds);
        }
        $eligibleSiswaIds = $siswaQuery->pluck('id_siswa');

        $pointTotals = \App\Models\PointSiswa::select('id_siswa', \Illuminate\Support\Facades\DB::raw('SUM(skor_point) as total_point'))
            ->whereIn('id_siswa', $eligibleSiswaIds)
            ->groupBy('id_siswa')
            ->having('total_point', '>=', $minThreshold)
            ->get();

        $pointSiswaIds = $pointTotals->pluck('id_siswa');

        // Existing SPs for these students
        $existingSps = SuratPeringatan::whereIn('id_siswa', $pointSiswaIds)
            ->get(['id_siswa', 'sp_level'])
            ->groupBy('id_siswa')
            ->map(function ($items) {
                return $items->pluck('sp_level')->toArray();
            });

        $studentMaxSingle = \App\Models\PointSiswa::select('id_siswa', \Illuminate\Support\Facades\DB::raw('MAX(skor_point) as max_score'))
            ->whereIn('id_siswa', $pointSiswaIds)
            ->groupBy('id_siswa')
            ->pluck('max_score', 'id_siswa');

        $siswaList = \App\Models\Siswa::with('kelas', 'jurusan')->whereIn('id_siswa', $pointSiswaIds)->get()->keyBy('id_siswa');

        // Urutkan aturan SP dari level tertinggi ke terendah (misal: 3 => 75, 2 => 50, 1 => 25)
        $sortedRules = collect($spRules)->sortKeysDesc();

        $antreanSp = collect();
        foreach ($pointTotals as $pt) {
            $siswa = $siswaList->get($pt->id_siswa);
            if (!$siswa) continue;

            $studentExistingSps = $existingSps->get($pt->id_siswa, []);

            // Cari target level SP tertinggi yang berhak didapatkan berdasarkan akumulasi poin saat ini
            $targetSpLevel = null;
            $targetThreshold = null;
            foreach ($sortedRules as $spLevel => $threshold) {
                if ($pt->total_point >= $threshold) {
                    $targetSpLevel = (int) $spLevel;
                    $targetThreshold = (int) $threshold;
                    break;
                }
            }

            // Cari apakah ada level SP sebelumnya yang belum pernah diterbitkan (pending lower levels)
            // Hanya berlaku jika siswa TIDAK melompat karena pelanggaran tunggal besar (skor_point >= targetThreshold)
            $pendingLowerLevels = [];
            $maxSingle = (int) ($studentMaxSingle->get($pt->id_siswa, 0));
            $isDirectMajorViolation = ($targetThreshold !== null && $maxSingle >= $targetThreshold);

            if ($targetSpLevel !== null && !$isDirectMajorViolation) {
                foreach ($spRules as $spLevel => $threshold) {
                    if ((int)$spLevel < $targetSpLevel && $pt->total_point >= $threshold && !in_array($spLevel, $studentExistingSps)) {
                        $pendingLowerLevels[] = [
                            'sp_level' => (int)$spLevel,
                            'threshold' => (int)$threshold,
                        ];
                    }
                }
            }

            // Jika siswa memenuhi syarat SP dan BELUM pernah menerima SP di level tersebut atau level di atasnya
            if ($targetSpLevel !== null) {
                $alreadyIssued = !empty(array_filter($studentExistingSps, fn($lvl) => (int)$lvl >= $targetSpLevel));

                if (!$alreadyIssued) {
                    $antreanSp->push((object)[
                        'siswa' => $siswa,
                        'id_siswa' => $siswa->id_siswa,
                        'total_point' => (int) $pt->total_point,
                        'sp_level' => $targetSpLevel,
                        'threshold' => $targetThreshold,
                        'pending_lower_levels' => $pendingLowerLevels,
                    ]);
                }
            }
        }

        // Sort queue: sp_level ascending, then total_point descending
        $antreanSp = $antreanSp->sortBy([
            ['sp_level', 'asc'],
            ['total_point', 'desc'],
        ]);

        // Stats calculation
        $totalSpDiterbitkan = $suratPeringatan->count();
        $totalAntrean = $antreanSp->count();
        $totalSudahTtd = $suratPeringatan->filter(fn($sp) => !empty($sp->file_ttd))->count();
        $totalBelumTtd = $totalSpDiterbitkan - $totalSudahTtd;

        return view('suratPeringatan.index', compact(
            'layout',
            'setting',
            'user',
            'suratPeringatan',
            'antreanSp',
            'spRules',
            'totalSpDiterbitkan',
            'totalAntrean',
            'totalSudahTtd',
            'totalBelumTtd'
        ));
    }

    /**
     * Upload signed SP.
     */
    public function uploadTtd(Request $request, $id_sp)
    {
        $request->validate([
            'file_ttd' => 'required|mimes:pdf,jpg,jpeg,png|max:4096',
        ]);

        $sp = SuratPeringatan::findOrFail($id_sp);

        if ($request->hasFile('file_ttd')) {
            // Delete old file if exists
            if ($sp->file_ttd && File::exists(public_path('storage/sp_ttd/' . $sp->file_ttd))) {
                File::delete(public_path('storage/sp_ttd/' . $sp->file_ttd));
            }

            $file = $request->file('file_ttd');
            $fileName = time() . '_ttd_' . str_replace(' ', '_', $file->getClientOriginalName());
            
            // Ensure folder exists
            if (!File::isDirectory(public_path('storage/sp_ttd'))) {
                File::makeDirectory(public_path('storage/sp_ttd'), 0777, true, true);
            }

            $file->move(public_path('storage/sp_ttd'), $fileName);

            $sp->update([
                'file_ttd' => $fileName,
            ]);

            return redirect()->back()->with('success', 'File SP bertanda tangan berhasil diunggah.');
        }

        return redirect()->back()->with('failed', 'Gagal mengunggah file.');
    }
}
