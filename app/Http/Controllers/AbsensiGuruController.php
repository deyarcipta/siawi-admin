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

        // Cek apakah event sudah dicatat sebelumnya berdasarkan `serialNo`
        $filePath = storage_path('logs/data.txt');
        if (file_exists($filePath) && strpos(file_get_contents($filePath), "SerialNo: $serialNo") !== false) {
            return response()->json(["status" => "ignored", "message" => "Duplicate event"], 200);
        }

        // Simpan log ke file
        $logMessage = now() . " - SerialNo: $serialNo - DECODED DATA: " . print_r($decodedData, true) . "\n";
        file_put_contents($filePath, $logMessage, FILE_APPEND);
        Log::info('Data Absensi Diterima', $decodedData);

        // Log::info('employeeId: ' . $employeeNoString);

        // Cari data guru berdasarkan `employeeNoString`
        $guru = Guru::where('id_face', $employeeNoString)->first();
        if (!$guru) {
            Log::info('Guru tidak ditemukan');
            return response()->json(['message' => 'Guru tidak ditemukan'], 404);
        }

        // if (is_null($dateTime)) {
        //     return response()->json(['message' => 'Tanggal dan waktu tidak ditemukan'], 400);
        // }

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
                Log::info("Absensi diperbarui untuk guru ID: " . $guru->id_guru);
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
            Log::info("Absensi baru dibuat untuk guru ID: " . $guru->id_guru);
        }

        DB::commit();
        Log::info("Transaksi database berhasil disimpan");

        return response()->json(['message' => 'Absensi berhasil disimpan'], 201);
    }

    // Menampilkan form untuk menambahkan absensi baru
    // public function storePresence(Request $request)
    // {
    //     try {
    //         // Cek apakah IP address sesuai
    //         $allowedIps = ['103.75.209.93', '103.75.209.94'];
    //         $clientIp = $request->ip(); // Ambil IP client

    //         if (!in_array($clientIp, $allowedIps)) {
    //             return response()->json(['message' => 'Access Denied'], 403);
    //         }

    //         $data = $request->all();
    //         Log::info($data);

    //         $eventLog = $data['event_log'] ?? null;

    //         if (is_string($eventLog)) {
    //             // Decode JSON jika event_log berupa string
    //             $eventLog = json_decode($eventLog, true);

    //             if (json_last_error() === JSON_ERROR_NONE && is_array($eventLog)) {
    //                 // Ambil employeeNoString jika decoding berhasil
    //                 $employeeNoString = $eventLog['AccessControllerEvent']['employeeNoString'] ?? null;
    //                 Log::info('employeeNoString: ' . ($employeeNoString ?? 'null'));
    //             } else {
    //                 Log::error('event_log JSON decoding error: ' . json_last_error_msg());
    //             }
    //         } else {
    //             Log::error('event_log is not a string or is missing');
    //         }

    //         // Ambil data dari 'AccessControllerEvent'
    //         $data = $eventLog['AccessControllerEvent'] ?? null;
    //         if (is_null($data)) {
    //             return response()->json(['message' => 'Data AccessControllerEvent tidak ditemukan'], 400);
    //         }

    //         if (empty($employeeNoString)) {
    //             return response()->json(['message' => 'employeeNoString tidak ditemukan dalam data'], 400);
    //         }

    //         // Cari data guru berdasarkan `employeeNoString`
    //         $guru = Guru::where('id_face', $employeeNoString)->first();
    //         if (!$guru) {
    //             return response()->json(['message' => 'Guru tidak ditemukan'], 404);
    //         }

    //         // Ambil dateTime dan konversi ke objek Carbon
    //         $dateTime = $eventLog['dateTime'] ?? null;
    //         if (is_null($dateTime)) {
    //             return response()->json(['message' => 'Tanggal dan waktu tidak ditemukan'], 400);
    //         }

    //         // Konversi ke objek Carbon
    //         $carbonDate = \Carbon\Carbon::parse($dateTime);

    //         // Ambil jam masuk atau jam pulang
    //         $jam = $carbonDate->format('H:i:s');

    //         // Dapatkan tanggal, hari, dan jam sekarang
    //         $now = Carbon::now('Asia/Jakarta');
    //         $tanggal = $now->toDateString();
    //         $hari = $now->locale('id')->dayName;
    //         $jamNow = $now->toTimeString();

    //         // Gunakan transaksi database
    //         DB::beginTransaction();

    //         // Cek absensi hari ini
    //         $absensi = AbsensiGuru::where('id_guru', $guru->id_guru)
    //             ->whereDate('tanggal', $tanggal)
    //             ->first();

    //         if ($absensi) {
    //             // Cek keterangan dan status log
    //             if ($absensi->jam_pulang < $jam) {
    //                 if ($data['attendanceStatus'] === 'checkOut') {
    //                 // if ($absensi->jam_pulang < $jam) {
    //                     $absensi->update([
    //                         'jam_pulang' => $jam,
    //                         'kehadiran' => 'Hadir',
    //                         'keterangan' => 'Check Out',
    //                     ]);
    //                 }
    //             }
    //         } else {
    //             // Tambahkan absensi baru jika belum ada
    //             AbsensiGuru::create([
    //                 'id_guru' => $guru->id_guru,
    //                 'hari' => $hari,
    //                 'tanggal' => $tanggal,
    //                 'jam_masuk' => $jam,
    //                 'kehadiran' => 'Hadir',
    //                 'keterangan' => $data['label'] ?? 'Check In',
    //             ]);
    //         }

    //         DB::commit();
    //         return response()->json(['message' => 'Absensi berhasil disimpan'], 201);

    //     } catch (\Exception $e) {
    //         DB::rollBack();

    //         // Log error
    //         Log::error('Error processing absensi: ' . $e->getMessage());
    //         return response()->json(['message' => 'Terjadi kesalahan saat memproses absensi'], 500);
    //     }
    // }

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
        return Excel::download(new AbsensiGuruRekapExport($request->tanggal_awal, $request->tanggal_akhir), 'rekap_absensi.xlsx');
    }

    // Menghapus data absensi
    public function destroy($id)
    {
        $absensi = AbsensiGuru::findOrFail($id);
        $absensi->delete();

        return redirect()->route('absensi_guru.index')->with('success', 'Data absensi berhasil dihapus.');
    }
}
