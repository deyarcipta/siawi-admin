<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Kelas;
use App\Models\Setting;
use App\Services\LaporanAbsensiBulananService;
use Carbon\Carbon;

class LaporanAbsensiBulananController extends Controller
{
    protected $service;

    public function __construct(LaporanAbsensiBulananService $service)
    {
        $this->service = $service;
    }

    /**
     * Tampilkan halaman rekap absensi bulanan dan kontrol notifikasi WA.
     */
    public function index(Request $request)
    {
        $layout = 'layout.app';
        $setting = Setting::first();
        $user = Auth::user();

        // Default rentang tanggal bulan ini (Awal Bulan - Akhir Bulan)
        $awalBulanIni = Carbon::now()->startOfMonth()->format('Y-m-d');
        $akhirBulanIni = Carbon::now()->endOfMonth()->format('Y-m-d');

        $tanggalMulai = $request->input('tanggal_mulai', $awalBulanIni);
        $tanggalSelesai = $request->input('tanggal_selesai', $akhirBulanIni);
        $idKelas = $request->input('id_kelas');

        $daftarKelas = Kelas::with('waliKelas')->orderBy('nama_kelas', 'asc')->get();
        if ($user && $user->role == 'wali_kelas') {
            $kelasWali = Kelas::where('id_guru', $user->id_guru)->with('waliKelas')->orderBy('nama_kelas', 'asc')->get();
            if ($kelasWali->isNotEmpty()) {
                $daftarKelas = $kelasWali;
                if (!$idKelas || !$daftarKelas->contains('id_kelas', $idKelas)) {
                    $idKelas = $daftarKelas->first()->id_kelas;
                }
            }
        }

        // Jika belum ada kelas dipilih, pilih kelas pertama secara default jika ada
        if (!$idKelas && $daftarKelas->isNotEmpty()) {
            $idKelas = $daftarKelas->first()->id_kelas;
        }

        $selectedKelas = $daftarKelas->firstWhere('id_kelas', $idKelas);

        $rekapData = $this->service->hitungRekapBulanan($idKelas, $tanggalMulai, $tanggalSelesai);

        return view('absensi.laporan_bulanan_wa', compact(
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
     * Trigger pengiriman notifikasi WhatsApp bulanan ke Orang Tua siswa per kelas.
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

        $pesan = "Berhasil menjadwalkan {$result['queued']} notifikasi WhatsApp bulanan ke antrean pengiriman.";
        if ($result['skipped'] > 0) {
            $pesan .= " ({$result['skipped']} siswa dilewati karena nomor HP Orang Tua tidak valid/kosong).";
        }

        return redirect()->back()->with('success', $pesan);
    }

    /**
     * Trigger pengiriman notifikasi WhatsApp bulanan ke Wali Kelas.
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
            return redirect()->back()->with('success', 'Ringkasan rekap bulanan berhasil dikirim ke antrean WhatsApp Wali Kelas.');
        } else {
            return redirect()->back()->with('error', 'Gagal mengirim: Wali Kelas belum diatur atau nomor HP Wali Kelas tidak valid.');
        }
    }

    /**
     * API Preview template pesan WA bulanan.
     */
    public function preview(Request $request)
    {
        $idKelas = $request->input('id_kelas');
        $awalBulanIni = Carbon::now()->startOfMonth()->format('Y-m-d');
        $akhirBulanIni = Carbon::now()->endOfMonth()->format('Y-m-d');

        $tanggalMulai = $request->input('tanggal_mulai', $awalBulanIni);
        $tanggalSelesai = $request->input('tanggal_selesai', $akhirBulanIni);
        $setting = Setting::first();

        $rekapData = $this->service->hitungRekapBulanan($idKelas, $tanggalMulai, $tanggalSelesai);

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
