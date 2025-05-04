<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AbsensiGuruExport;
use App\Exports\AbsensiGuruRekapExport;
use App\Models\Guru;
use App\Models\AbsensiGuru;
use App\Models\Setting;
use Carbon\Carbon;

class AbsensiGuruController extends Controller
{
    // Menampilkan daftar absensi guru
    public function index()
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
        $now = Carbon::now('Asia/Jakarta');
        $tanggal = $now->toDateString();
        $hari = $now->locale('id')->dayName;
        $guruList = Guru::all();
        // Ambil data absensi hanya untuk hari ini
        $absensiGuru = AbsensiGuru::whereDate('tanggal', $tanggal)
                              ->orderBy('created_at', 'desc')
                              ->get();
        return view('absensi_guru.index', compact('absensiGuru', 'layout', 'setting', 'user','hari','guruList'));
    }

    public function store(Request $request)
    {
        $eventLogRaw = $request->input('event_log');
        $decodedData = $eventLogRaw ? json_decode($eventLogRaw, true) : null;

        if (!$decodedData || !isset($decodedData['AccessControllerEvent'])) {
            return response()->json(["status" => "error", "message" => "Invalid data"], 400);
        }

        $event = $decodedData['AccessControllerEvent'];
        $attendanceStatus = $event['attendanceStatus'] ?? null;
        $serialNo = $event['serialNo'] ?? null;
        $employeeNoString = $event['employeeNoString'] ?? null;
        $label = $event['label'] ?? 'Check In';
        $dateTime = $event['dateTime'] ?? null;


        if (!in_array($attendanceStatus, ['checkIn', 'checkOut'])) {
            return response()->json(["status" => "ignored", "message" => "Event not related to attendance"], 200);
        }

        if (empty($employeeNoString)) {
            return response()->json(['message' => 'employeeNoString tidak ditemukan dalam data'], 400);
        }

        $clientIp = $request->ip(); // Ambil IP pengirim
        $uniqueSerial = "$serialNo-$clientIp"; // Gabungkan serialNo dengan IP

        // Cek apakah event sudah dicatat sebelumnya berdasarkan kombinasi serialNo dan IP
        $filePath = storage_path('logs/data.txt');
        if (file_exists($filePath) && strpos(file_get_contents($filePath), "SerialNo: $uniqueSerial") !== false) {
            return response()->json(["status" => "ignored", "message" => "Duplicate event"], 200);
        }

        // Simpan log dengan IP Address agar unik
        $logMessage = now() . " - SerialNo: $uniqueSerial - DECODED DATA: " . print_r($decodedData, true) . "\n";
        file_put_contents($filePath, $logMessage, FILE_APPEND);
        // Log::info('Data Absensi Diterima', $decodedData);

        // Cari data guru berdasarkan `employeeNoString`
        $guru = Guru::where('id_face', $employeeNoString)->first();
        if (!$guru) {
            Log::info('Guru tidak ditemukan');
            return response()->json(['message' => 'Guru tidak ditemukan'], 404);
        }

        $carbonDate = \Carbon\Carbon::parse($dateTime);
        $jam = $carbonDate->format('H:i:s');
        // Ambil tanggal dan hari sekarang
        $now = Carbon::now('Asia/Jakarta');
        $tanggal = $now->toDateString();
        $hari = $now->locale('id')->dayName;

        DB::beginTransaction();    

        // Cek absensi hari ini berdasarkan `id_guru`
        $absensi = AbsensiGuru::where('id_guru', $guru->id_guru)
            ->whereDate('tanggal', $tanggal)
            ->first();

        if ($absensi) {
            if ($attendanceStatus === 'checkOut' && (empty($absensi->jam_pulang) || $absensi->jam_pulang < $jam)) {
                $absensi->update([
                    'jam_pulang' => $jam,
                    'kehadiran' => 'Hadir',
                    'keterangan' => 'Check Out',
                ]);
                // Log::info("Absensi diperbarui untuk guru ID: " . $guru->id_guru);
            }
        } else {
            AbsensiGuru::create([
                'id_guru' => $guru->id_guru,
                'hari' => $hari,
                'tanggal' => $tanggal,
                'jam_masuk' => $jam,
                'kehadiran' => 'Hadir',
                'keterangan' => $label,
            ]);
            // Log::info("Absensi baru dibuat untuk guru ID: " . $guru->id_guru);
        }

        DB::commit();
        // Log::info("Transaksi database berhasil disimpan");

        return response()->json(['message' => 'Absensi berhasil disimpan'], 201);
    }

    public function storeKehadiran(Request $request)
    {
        $request->validate([
            'id_guru' => 'required|exists:guru,id_guru',
            // 'jam_masuk' => 'required',
            'kehadiran' => 'required',
        ]);

        $tanggal = Carbon::now()->toDateString();
        $hari = Carbon::now()->locale('id')->translatedFormat('l');

        AbsensiGuru::create([
            'id_guru' => $request->id_guru,
            'hari' => $hari,
            'tanggal' => $tanggal,
            'kehadiran' => $request->kehadiran,
            'keterangan' => 'Manual Entry',
        ]);

        return redirect()->back()->with('success', 'Kehadiran berhasil ditambahkan!');
    }

    public function AbsensiGuruExport()
    {
        $tanggal = Carbon::now()->format('Y-m-d'); // Format: 2025-02-25
        $hari = Carbon::now()->locale('id')->translatedFormat('l'); // Format: Selasa

        $fileName = "Kehadiran_Guru_{$hari}_{$tanggal}.xlsx"; // Contoh: Absensi_Selasa_2025-02-25.xlsx

        return Excel::download(new AbsensiGuruExport($tanggal), $fileName);
    }

    public function rekapAbsenGuru(Request $request)
    {
        $layout = 'layout.app';
        $setting = Setting::find('1');
        $user = Auth::user();
         $tanggalAwal = $request->input('tanggal_awal', Carbon::now()->startOfMonth()->toDateString());
        $tanggalAkhir = $request->input('tanggal_akhir', Carbon::now()->endOfMonth()->toDateString());

        $rekapKehadiran = AbsensiGuru::whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
            ->with('guru')
            ->get()
            ->groupBy('id_guru');

        return view('absensi_guru.rekap_absen', compact('layout', 'setting', 'user', 'rekapKehadiran', 'tanggalAwal', 'tanggalAkhir'));
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(
            new AbsensiGuruRekapExport($request->tanggal_awal, $request->tanggal_akhir), 
            'rekap_absensi_' . $request->tanggal_awal . '-' . $request->tanggal_akhir . '.xlsx'
        );
    }

    // Menghapus data absensi
    public function destroy($id)
    {
        $absensi = AbsensiGuru::findOrFail($id);
        $absensi->delete();

        return redirect()->route('absensi_guru.index')->with('success', 'Data absensi berhasil dihapus.');
    }
}
