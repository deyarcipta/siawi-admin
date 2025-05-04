<?php

namespace App\Http\Controllers\SiswaController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Siswa;
use App\Models\Absensi;
use App\Models\Kelas;
use Carbon\Carbon;
use DB;

class AbsensiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(String $id_siswa)
    {
        $absen = Absensi::where('id_siswa', $id_siswa)
        ->orderBy('tanggal', 'desc')
        ->get();
        $jumlahSakit = Absensi::where('id_siswa', $id_siswa)
        ->where('kehadiran', 'sakit')
        ->count();
        $jumlahAlfa = Absensi::where('id_siswa', $id_siswa)
        ->where('kehadiran', 'alfa')
        ->count();
        $jumlahIzin = Absensi::where('id_siswa', $id_siswa)
        ->where('kehadiran', 'izin')
        ->count();
        $jumlahHadir = Absensi::where('id_siswa', $id_siswa)
        ->where('kehadiran', 'hadir')
        ->count();
        $totalKehadiran = Absensi::where('id_siswa', $id_siswa)->count();

        $statusKehadiran = ['alfa', 'izin', 'sakit'];
        $jumlahTidakHadir = Absensi::where('id_siswa', $id_siswa)
                            ->whereIn('kehadiran', $statusKehadiran)
                            ->count();
                            // Menghitung jumlah siswa yang hadir hari ini
        $jumlahHadir = $totalKehadiran - $jumlahTidakHadir;

        // Menghitung presentase kehadiran
        if ($totalKehadiran > 0) {
            $presentaseKehadiran = number_format(($jumlahHadir / $totalKehadiran) * 100,0);
        } else {
            $presentaseKehadiran = 0;
        }
        $siswa = Siswa::where('id_siswa', $id_siswa)
        ->with('kelas')
        ->first();
        $warna = '';
        $absenArray = []; 
        foreach ($absen as $item) {
            if ($item->kehadiran == 'sakit' || $item->kehadiran == 'izin' || $item->kehadiran == 'alfa') {
                // Jika status kehadiran adalah sakit, izin, atau alfa, tetapkan warna sebagai Colors.red
                $warna = 'red';
            }else{
                $warna = 'blue';
            }
            $tanggalBaru = Carbon::createFromFormat('Y-m-d', $item->tanggal)->format('d F Y');
            $absenArray[] = [
                'tanggal' => $tanggalBaru, 
                'kehadiran' => $item->kehadiran,
                'hari' => $item->hari,
                'ket' => $item->keterangan,
                'warna' => $warna
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $absenArray,
            'dataSiswa' => $siswa,
            'jumlahSakit' => $jumlahSakit,
            'jumlahIzin' => $jumlahIzin,
            'jumlahAlfa' => $jumlahAlfa,
            'jumlahHadir' => $jumlahHadir,
            'jumlahTidakHadir' => $jumlahTidakHadir,
            'presentaseKehadiran' => $presentaseKehadiran,
            'message' => 'Berhasil Ambil Data'
        ]);
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

        // Cari data siswa berdasarkan `employeeNoString`
        $employeeNoString = strval($employeeNoString);
        $siswa = Siswa::where('id_siswa', $employeeNoString)->first();
        if (!$siswa) {
            Log::info('Siswa tidak ditemukan');
            return response()->json(['message' => 'Siswa tidak ditemukan'], 404);
        }

        $carbonDate = \Carbon\Carbon::parse($dateTime);
        $jam = $carbonDate->format('H:i:s');
        // Ambil tanggal dan hari sekarang
        $now = Carbon::now('Asia/Jakarta');
        $tanggal = $now->toDateString();
        $hari = $now->locale('id')->dayName;

        DB::beginTransaction();    

        // Cek absensi hari ini berdasarkan `id_siswa`
        $absensi = Absensi::where('id_siswa', $siswa->id_siswa)
            ->whereDate('tanggal', $tanggal)
            ->first();

        if ($absensi) {
            if ($attendanceStatus === 'checkOut' && (empty($absensi->jam_pulang) || $absensi->jam_pulang < $jam)) {
                $absensi->update([
                    'jam_pulang' => $jam,
                    'kehadiran' => 'hadir',
                    'keterangan' => 'Check Out',
                ]);
                // Log::info("Absensi diperbarui untuk siswa ID: " . $siswa->id_siswa);
            }
        } else {
            Absensi::create([
                'id_siswa' => $siswa->id_siswa,
                'id_kelas' => $siswa->id_kelas,
                'id_jurusan' => $siswa->id_jurusan,
                'hari' => $hari,
                'tanggal' => $tanggal,
                'jam_masuk' => $jam,
                'kehadiran' => 'hadir',
                'keterangan' => $label,
            ]);
            // Log::info("Absensi baru dibuat untuk siswa ID: " . $siswa->id_siswa);
        }

        DB::commit();
        // Log::info("Transaksi database berhasil disimpan");

        return response()->json(['message' => 'Absensi berhasil disimpan'], 201);
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
