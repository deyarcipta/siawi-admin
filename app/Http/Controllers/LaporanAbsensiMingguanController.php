<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Kelas;
use App\Models\Setting;
use App\Services\LaporanAbsensiMingguanService;
use Carbon\Carbon;

class LaporanAbsensiMingguanController extends Controller
{
    protected $service;

    public function __construct(LaporanAbsensiMingguanService $service)
    {
        $this->service = $service;
    }

    /**
     * Tampilkan halaman rekap absensi mingguan dan kontrol notifikasi WA.
     */
    public function index(Request $request)
    {
        $layout = 'layout.app';
        $setting = Setting::first();
        $user = Auth::user();

        // Default rentang tanggal pekan ini (Senin - Jumat)
        $seninPekanIni = Carbon::now()->startOfWeek(Carbon::MONDAY)->format('Y-m-d');
        $jumatPekanIni = Carbon::now()->startOfWeek(Carbon::MONDAY)->addDays(4)->format('Y-m-d');

        $tanggalMulai = $request->input('tanggal_mulai', $seninPekanIni);
        $tanggalSelesai = $request->input('tanggal_selesai', $jumatPekanIni);
        $idKelas = $request->input('id_kelas');

        $daftarKelas = Kelas::with('waliKelas')->orderBy('nama_kelas', 'asc')->get();

        // Jika belum ada kelas dipilih, pilih kelas pertama secara default jika ada
        if (!$idKelas && $daftarKelas->isNotEmpty()) {
            $idKelas = $daftarKelas->first()->id_kelas;
        }

        $selectedKelas = $daftarKelas->firstWhere('id_kelas', $idKelas);

        $rekapData = $this->service->hitungRekapMingguan($idKelas, $tanggalMulai, $tanggalSelesai);

        return view('absensi.laporan_mingguan_wa', compact(
            'layout',
            'setting',
            'user',
            'daftarKelas',
            'selectedKelas',
            'idKelas',
            'tanggalMulai',
            'tanggalSelesai',
            'rekapData'
        ));
    }

    /**
     * Trigger pengiriman notifikasi WhatsApp ke Orang Tua siswa per kelas.
     */
    public function kirimOrangTua(Request $request)
    {
        $request->validate([
            'id_kelas' => 'required',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        $idKelas = $request->input('id_kelas');
        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalSelesai = $request->input('tanggal_selesai');

        $result = $this->service->dispatchNotifikasiOrangTua($idKelas, $tanggalMulai, $tanggalSelesai);

        $pesan = "Berhasil menjadwalkan {$result['queued']} notifikasi WhatsApp ke antrean pengiriman.";
        if ($result['skipped'] > 0) {
            $pesan .= " ({$result['skipped']} siswa dilewati karena nomor HP Orang Tua tidak valid/kosong).";
        }

        return redirect()->back()->with('success', $pesan);
    }

    /**
     * Trigger pengiriman notifikasi WhatsApp ke Wali Kelas.
     */
    public function kirimWaliKelas(Request $request)
    {
        $request->validate([
            'id_kelas' => 'required',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        $idKelas = $request->input('id_kelas');
        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalSelesai = $request->input('tanggal_selesai');

        $success = $this->service->dispatchNotifikasiWaliKelas($idKelas, $tanggalMulai, $tanggalSelesai);

        if ($success) {
            return redirect()->back()->with('success', 'Ringkasan rekap mingguan berhasil dikirim ke antrean WhatsApp Wali Kelas.');
        } else {
            return redirect()->back()->with('error', 'Gagal mengirim: Wali Kelas belum diatur atau nomor HP Wali Kelas tidak valid.');
        }
    }

    /**
     * API Preview template pesan WA.
     */
    public function preview(Request $request)
    {
        $idKelas = $request->input('id_kelas');
        $tanggalMulai = $request->input('tanggal_mulai', date('Y-m-d'));
        $tanggalSelesai = $request->input('tanggal_selesai', date('Y-m-d'));
        $setting = Setting::first();

        $rekapData = $this->service->hitungRekapMingguan($idKelas, $tanggalMulai, $tanggalSelesai);

        $previewOrangTua = "Belum ada data siswa.";
        if (!empty($rekapData['rekap_siswa'])) {
            $firstSiswa = $rekapData['rekap_siswa'][0];
            $previewOrangTua = $this->service->formatPesanOrangTua($firstSiswa, $rekapData['periode']['label'], $setting);
        }

        $kelas = Kelas::with('waliKelas')->find($idKelas);
        $previewWaliKelas = "Wali Kelas belum diatur.";
        if ($kelas) {
            $previewWaliKelas = $this->service->formatPesanWaliKelas($kelas, $rekapData, $setting);
        }

        return response()->json([
            'status' => 'success',
            'preview_orang_tua' => $previewOrangTua,
            'preview_wali_kelas' => $previewWaliKelas,
        ]);
    }
}
