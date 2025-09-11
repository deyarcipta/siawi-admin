<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Guru;
use App\Models\JadwalMapel;
use App\Models\JurnalMengajar;
use App\Models\AbsensiGuru;
use App\Models\Setting;
use Carbon\Carbon;
use App\Exports\RekapKehadiranGuruExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class RekapKehadiranGuruController extends Controller
{
    public function index(Request $request)
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();

        $guruList = Guru::all();

        $tanggalAwal = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;
        $guruId = $request->guru_id;

        $data = [];

        if ($tanggalAwal && $tanggalAkhir) {
            $tanggalRange = [];
            $start = Carbon::parse($tanggalAwal);
            $end = Carbon::parse($tanggalAkhir);

            while ($start <= $end) {
                $tanggalRange[] = $start->copy();
                $start->addDay();
            }

            $no = 1;

            foreach ($tanggalRange as $tanggal) {
                $hari = $tanggal->translatedFormat('l');

                // ambil jadwal mapel sesuai hari, urutkan berdasarkan jam_awal lalu jam_akhir
                $jadwalHari = JadwalMapel::with(['guru', 'mapel', 'kelas'])
                    ->where('hari', $hari)
                    ->when($guruId, function ($q) use ($guruId) {
                        $q->where('id_guru', $guruId);
                    })
                    ->orderByRaw('CAST(jam_awal AS UNSIGNED) ASC')
                    ->orderByRaw('CAST(jam_akhir AS UNSIGNED) ASC')
                    ->get();

                foreach ($jadwalHari as $item) {
                    // cek absensi guru
                    $absen = AbsensiGuru::where('id_guru', $item->id_guru)
                        ->whereDate('tanggal', $tanggal->format('Y-m-d'))
                        ->first();

                    // cek jurnal berdasarkan jadwal_mapel
                    $jurnal = JurnalMengajar::where('id_jadwal', $item->id_jadwal)
                        ->where('id_guru', $item->id_guru)
                        ->whereDate('tanggal', $tanggal->format('Y-m-d'))
                        ->first();

                    // status absensi
                    $statusAbsensi = 'Alfa';
                    if ($absen) {
                        if ($absen->kehadiran == 'Sakit') {
                            $statusAbsensi = 'Sakit';
                        } elseif ($absen->kehadiran == 'Izin') {
                            $statusAbsensi = 'Izin';
                        } else {
                            $statusAbsensi = 'Hadir';
                        }
                    }

                    $data[] = [
                        'no' => $no++,
                        'tanggal' => $tanggal->format('Y-m-d'),
                        'hari' => $hari,
                        'nama_guru' => $item->guru->nama_guru ?? '-',
                        'mapel' => $item->mapel->nama_mapel ?? '-',
                        'kelas' => $item->kelas->nama_kelas ?? '-',
                        'jam_jadwal' => $item->jam_mulai . " s/d " . $item->jam_selesai,
                        'jam_absen' => $absen ? $absen->jam_masuk . " - " . $absen->jam_pulang : '-',
                        'jurnal' => $jurnal ? $jurnal->materi : "Tidak",
                        'jam_jurnal' => $jurnal ? $jurnal->jam_awal . " s/d " . $jurnal->jam_akhir : "-",
                        'status_absensi' => $statusAbsensi,
                        'status_jurnal'  => $jurnal ? 'Isi Jurnal' : 'Tidak isi jurnal',
                        'created_at'     => $jurnal ? $jurnal->created_at : null,
                    ];
                }
            }
        }

        return view('rekap_guru.index', compact(
            'layout',
            'setting',
            'user',
            'data',
            'tanggalAwal',
            'tanggalAkhir',
            'guruList',
            'guruId'
        ));
    }

    public function export(Request $request)
    {
        $tanggalAwal = $request->tanggal_awal ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $tanggalAkhir = $request->tanggal_akhir ?? Carbon::now()->endOfMonth()->format('Y-m-d');
        $guruId = $request->guru_id;

        return Excel::download(new RekapKehadiranGuruExport($tanggalAwal, $tanggalAkhir, $guruId), 'rekap_kehadiran_guru.xlsx');
    }

    public function downloadPdf(Request $request)
    {
        $tanggalAwal = $request->tanggal_awal ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $tanggalAkhir = $request->tanggal_akhir ?? Carbon::now()->endOfMonth()->format('Y-m-d');
        $guruId = $request->guru_id;

        $data = [];
        $tanggalRange = [];
        $start = Carbon::parse($tanggalAwal);
        $end = Carbon::parse($tanggalAkhir);

        while ($start <= $end) {
            $tanggalRange[] = $start->copy();
            $start->addDay();
        }

        $no = 1;

        foreach ($tanggalRange as $tanggal) {
            $hari = $tanggal->translatedFormat('l');

            $jadwalHari = JadwalMapel::with(['guru', 'mapel', 'kelas'])
                ->where('hari', $hari)
                ->when($guruId, function ($q) use ($guruId) {
                    $q->where('id_guru', $guruId);
                })
                ->orderByRaw('CAST(jam_awal AS UNSIGNED) ASC')
                ->orderByRaw('CAST(jam_akhir AS UNSIGNED) ASC')
                ->get();

            foreach ($jadwalHari as $item) {
                $absen = AbsensiGuru::where('id_guru', $item->id_guru)
                    ->whereDate('tanggal', $tanggal->format('Y-m-d'))
                    ->first();

                $jurnal = JurnalMengajar::where('id_jadwal', $item->id_jadwal)
                    ->where('id_guru', $item->id_guru)
                    ->whereDate('tanggal', $tanggal->format('Y-m-d'))
                    ->first();

                $statusAbsensi = 'Alfa';
                if ($absen) {
                    if ($absen->kehadiran == 'Sakit') {
                        $statusAbsensi = 'Sakit';
                    } elseif ($absen->kehadiran == 'Izin') {
                        $statusAbsensi = 'Izin';
                    } else {
                        $statusAbsensi = 'Hadir';
                    }
                }

                $data[] = [
                    'no' => $no++,
                    'tanggal' => $tanggal->format('Y-m-d'),
                    'hari' => $hari,
                    'nama_guru' => $item->guru->nama_guru ?? '-',
                    'mapel' => $item->mapel->nama_mapel ?? '-',
                    'kelas' => $item->kelas->nama_kelas ?? '-',
                    'jam_jadwal' => $item->jam_mulai . " s/d " . $item->jam_selesai,
                    'jam_absen' => $absen ? $absen->jam_masuk . " - " . $absen->jam_pulang : '-',
                    'jurnal' => $jurnal ? $jurnal->materi : "Tidak",
                    'jam_jurnal' => $jurnal ? $jurnal->jam_awal . " s/d " . $jurnal->jam_akhir : "-",
                    'status_absensi' => $statusAbsensi,
                    'status_jurnal'  => $jurnal ? 'Isi Jurnal' : 'Tidak isi jurnal',
                    'created_at'     => $jurnal ? $jurnal->created_at : null,
                ];
            }
        }

        $pdf = Pdf::loadView('rekap_guru.pdf', compact(
            'data',
            'tanggalAwal',
            'tanggalAkhir',
            'guruId'
        ))->setPaper('a4', 'landscape');

        return $pdf->download('rekap-kehadiran-guru.pdf');
    }
}
