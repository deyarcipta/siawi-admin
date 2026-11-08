<?php

namespace App\Http\Controllers\SiswaController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\SiswaPkl;
use App\Models\AbsensiPkl;
use App\Models\Perusahaan;
use App\Models\Setting;
use App\Models\Siswa;
use Carbon\Carbon;

class PresensiPklController extends Controller
{
    /**
     * Dapatkan informasi target penempatan PKL, aturan presensi aktif, dan status presensi hari ini.
     * Endpoint: GET /api/presensi-pkl/target/{idSiswa}
     */
    public function getStatusPresensi(Request $request, $id_siswa)
    {
        $today = now()->toDateString();

        $siswa = Siswa::with(['kelas', 'jurusan'])->find($id_siswa);
        if (!$siswa) {
            return response()->json([
                'success' => false,
                'status' => 'error',
                'message' => 'Data siswa tidak ditemukan.',
            ], 404);
        }

        $siswaPkl = SiswaPkl::with(['perusahaan.guru', 'siswa.kelas'])
            ->where('id_siswa', $id_siswa)
            ->where('status', '!=', 'selesai')
            ->latest()
            ->first();

        if (!$siswaPkl) {
            return response()->json([
                'success' => false,
                'status' => 'error',
                'message' => 'Siswa tidak memiliki data penempatan PKL aktif.',
            ], 404);
        }

        $perusahaan = $siswaPkl->perusahaan;
        if (!$perusahaan) {
            return response()->json([
                'success' => false,
                'status' => 'error',
                'message' => 'Data mitra perusahaan tempat PKL belum ditentukan.',
            ], 404);
        }

        $isGeofenceConfigured = !empty($perusahaan->latitude) && !empty($perusahaan->longitude);

        $presensiHariIni = AbsensiPkl::where('id_siswa', $id_siswa)
            ->where('tanggal', $today)
            ->first();

        $setting = Setting::first();
        $attendanceMode = $setting->pkl_attendance_mode ?? 'radius_foto_lock';
        $fotoMode = $setting->pkl_foto_mode ?? 'masuk_saja';

        $wajibLockDevice = $this->isLockDeviceRequiredForSiswa($siswa, $siswaPkl, $setting);
        $wajibFotoMasuk = in_array($attendanceMode, ['radius_foto', 'radius_foto_lock']);
        $wajibFotoPulang = $wajibFotoMasuk && ($fotoMode === 'masuk_pulang');

        $sudahMasuk = $presensiHariIni && !empty($presensiHariIni->jam_masuk);
        $sudahPulang = $presensiHariIni && !empty($presensiHariIni->jam_pulang);
        $sudahIzinSakit = $presensiHariIni && in_array($presensiHariIni->status, ['izin', 'sakit']);

        $canMasuk = !$sudahMasuk && !$sudahIzinSakit && $isGeofenceConfigured;
        $canPulang = $sudahMasuk && !$sudahPulang && !$sudahIzinSakit && $isGeofenceConfigured;

        return response()->json([
            'success' => true,
            'status' => 'success',
            'data' => [
                'siswa' => [
                    'id_siswa' => $siswa->id_siswa,
                    'nama_siswa' => $siswa->nama_siswa,
                    'nis' => $siswa->nis,
                    'kelas' => $siswa->kelas->nama_kelas ?? '-',
                ],
                'aturan_presensi' => [
                    'mode' => $attendanceMode,
                    'mode_label' => $this->getModeLabel($attendanceMode),
                    'wajib_foto_masuk' => $wajibFotoMasuk,
                    'wajib_foto_pulang' => $wajibFotoPulang,
                    'wajib_lock_device' => $wajibLockDevice,
                    'is_device_locked' => !empty($siswa->device_id),
                    'device_terdaftar' => $siswa->device_name ?? null,
                ],
                'siswa_pkl' => [
                    'id_siswa_pkl' => $siswaPkl->id_siswa_pkl,
                    'status_pkl' => $siswaPkl->status_pkl ?? $siswaPkl->status,
                    'status_label' => $siswaPkl->status_label ?? ucfirst($siswaPkl->status),
                    'tanggal_mulai' => $siswaPkl->tanggal_mulai,
                    'tanggal_selesai' => $siswaPkl->tanggal_selesai,
                ],
                'perusahaan' => [
                    'id_perusahaan' => $perusahaan->id_perusahaan,
                    'nama_perusahaan' => $perusahaan->nama_perusahaan,
                    'alamat_perusahaan' => $perusahaan->alamat_perusahaan,
                    'latitude' => $perusahaan->latitude ? (float) $perusahaan->latitude : null,
                    'longitude' => $perusahaan->longitude ? (float) $perusahaan->longitude : null,
                    'radius_meter' => (int) ($perusahaan->radius_meter ?: 50),
                    'is_geofence_configured' => $isGeofenceConfigured,
                    'pembimbing_sekolah' => $perusahaan->guru->nama_guru ?? '-',
                    'pic_lapangan' => $perusahaan->pic ?? '-',
                    'kontak_pic' => $perusahaan->kontak_pic ?? '-',
                ],
                'presensi_hari_ini' => $presensiHariIni ? [
                    'id_absensi_pkl' => $presensiHariIni->id_absensi_pkl,
                    'tanggal' => $presensiHariIni->tanggal,
                    'jam_masuk' => $presensiHariIni->jam_masuk,
                    'jam_pulang' => $presensiHariIni->jam_pulang,
                    'jarak_masuk_meter' => $presensiHariIni->jarak_masuk_meter,
                    'jarak_pulang_meter' => $presensiHariIni->jarak_pulang_meter,
                    'foto_masuk_url' => $presensiHariIni->foto_masuk_url,
                    'foto_pulang_url' => $presensiHariIni->foto_pulang_url,
                    'status' => $presensiHariIni->status,
                    'keterangan' => $presensiHariIni->keterangan,
                    'sudah_masuk' => $sudahMasuk,
                    'sudah_pulang' => $sudahPulang,
                ] : null,
                'aksi' => [
                    'can_presensi_masuk' => $canMasuk,
                    'can_presensi_pulang' => $canPulang,
                    'can_pengajuan_izin' => !$sudahMasuk && !$sudahIzinSakit,
                ]
            ]
        ]);
    }

    /**
     * Submit presensi siswa PKL via koordinat GPS & Foto.
     * Endpoint: POST /api/presensi-pkl
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_siswa' => 'required',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'tipe' => 'nullable|in:masuk,pulang',
            'foto' => 'nullable', // File upload atau Base64 string
            'image' => 'nullable',
            'device_id' => 'nullable|string',
            'device_name' => 'nullable|string',
            'keterangan' => 'nullable|string|max:500',
        ]);

        $today = now()->toDateString();
        $currentTime = now()->format('H:i:s');

        $siswa = Siswa::with('kelas')->find($request->id_siswa);
        if (!$siswa) {
            return response()->json([
                'success' => false,
                'status' => 'error',
                'message' => 'Data siswa tidak ditemukan.'
            ], 404);
        }

        $siswaPkl = SiswaPkl::with('perusahaan')
            ->where('id_siswa', $request->id_siswa)
            ->where('status', '!=', 'selesai')
            ->latest()
            ->first();

        if (!$siswaPkl || !$siswaPkl->perusahaan) {
            return response()->json([
                'success' => false,
                'status' => 'error',
                'message' => 'Data penempatan PKL tidak ditemukan atau mitra perusahaan belum ditentukan.'
            ], 422);
        }

        $perusahaan = $siswaPkl->perusahaan;

        if (empty($perusahaan->latitude) || empty($perusahaan->longitude)) {
            return response()->json([
                'success' => false,
                'status' => 'error',
                'message' => 'Titik koordinat kantor mitra PKL belum disetel oleh sekolah. Silakan hubungi Guru Pembimbing PKL / Admin.'
            ], 422);
        }

        $setting = Setting::first();
        $attendanceMode = $setting->pkl_attendance_mode ?? 'radius_foto_lock';
        $fotoMode = $setting->pkl_foto_mode ?? 'masuk_saja';

        // 1. Validasi Lock Device jika wajib
        $wajibLock = $this->isLockDeviceRequiredForSiswa($siswa, $siswaPkl, $setting);
        if ($wajibLock) {
            $deviceId = trim($request->input('device_id') ?? $request->header('X-Device-Id') ?? '');
            $deviceName = trim($request->input('device_name') ?? $request->header('X-Device-Name') ?? 'Perangkat Mobile');

            if (!empty($siswa->device_id)) {
                if (empty($deviceId) || $siswa->device_id !== $deviceId) {
                    $namaHp = $siswa->device_name ?: 'Perangkat Terdaftar';
                    return response()->json([
                        'success' => false,
                        'status' => 'error',
                        'message' => "Presensi ditolak. Akun Anda sedang terkunci pada ({$namaHp}). Untuk berpindah HP, silakan hubungi Guru Pembimbing / Admin untuk melakukan Reset HP.",
                        'is_device_locked' => true,
                        'locked_device_name' => $namaHp,
                    ], 403);
                }
            } else {
                // Siswa belum memiliki kuncian perangkat, daftarkan HP pertama yang melakukan presensi
                if (!empty($deviceId)) {
                    $siswa->update([
                        'device_id' => $deviceId,
                        'device_name' => $deviceName,
                        'device_locked_at' => now(),
                    ]);
                }
            }
        }

        // 2. Kalkulasi Jarak Haversine (meter)
        $distance = $this->calculateHaversineDistance(
            (float) $request->latitude,
            (float) $request->longitude,
            (float) $perusahaan->latitude,
            (float) $perusahaan->longitude
        );

        $maxRadius = (int) ($perusahaan->radius_meter ?: 50);

        if ($distance > $maxRadius) {
            $kelebihan = $distance - $maxRadius;
            return response()->json([
                'success' => false,
                'status' => 'error',
                'message' => "Presensi ditolak. Anda berada di luar radius kantor PKL ({$distance} meter dari kantor, batas maksimal {$maxRadius} meter). Silakan mendekat sejauh {$kelebihan} meter lagi.",
                'jarak_meter' => $distance,
                'radius_meter' => $maxRadius,
            ], 422);
        }

        // 3. Tentukan tipe presensi (masuk / pulang)
        $presensi = AbsensiPkl::firstOrNew([
            'id_siswa' => $request->id_siswa,
            'tanggal' => $today,
        ]);

        if (in_array($presensi->status, ['izin', 'sakit'])) {
            return response()->json([
                'success' => false,
                'status' => 'error',
                'message' => "Anda sudah tercatat {$presensi->status} pada hari ini.",
            ], 422);
        }

        $tipe = $request->input('tipe');
        if (!$tipe) {
            $tipe = empty($presensi->jam_masuk) ? 'masuk' : 'pulang';
        }

        // 4. Validasi Foto jika mode mewajibkan foto
        $wajibFotoMasuk = in_array($attendanceMode, ['radius_foto', 'radius_foto_lock']);
        $wajibFotoPulang = $wajibFotoMasuk && ($fotoMode === 'masuk_pulang');

        $isWajibFoto = ($tipe === 'masuk') ? $wajibFotoMasuk : $wajibFotoPulang;
        $fotoName = $this->simpanFoto($request, $request->id_siswa, $tipe);

        if ($isWajibFoto && empty($fotoName)) {
            return response()->json([
                'success' => false,
                'status' => 'error',
                'message' => "Foto selfie {$tipe} wajib disertakan sebagai bukti kehadiran.",
            ], 422);
        }

        $presensi->id_siswa_pkl = $siswaPkl->id_siswa_pkl;
        $presensi->id_perusahaan = $perusahaan->id_perusahaan;

        // 5. Eksekusi Presensi Masuk / Pulang
        if ($tipe === 'masuk') {
            if (!empty($presensi->jam_masuk)) {
                return response()->json([
                    'success' => false,
                    'status' => 'error',
                    'message' => 'Anda sudah melakukan presensi masuk hari ini pada pukul ' . substr($presensi->jam_masuk, 0, 5) . ' WIB.'
                ], 422);
            }

            $presensi->jam_masuk = $currentTime;
            $presensi->latitude_masuk = $request->latitude;
            $presensi->longitude_masuk = $request->longitude;
            $presensi->jarak_masuk_meter = $distance;
            if ($fotoName) {
                $presensi->foto_masuk = $fotoName;
            }

            // Presensi PKL tidak mencatat status terlambat karena jam kerja shift industri fleksibel
            $presensi->status = 'hadir';

            if ($request->filled('keterangan')) {
                $presensi->keterangan = $request->keterangan;
            }

            $presensi->save();

            return response()->json([
                'success' => true,
                'status' => 'success',
                'message' => 'Presensi masuk PKL berhasil dicatat.',
                'data' => $presensi->fresh()
            ]);
        } else {
            // Presensi Pulang
            if (empty($presensi->jam_masuk)) {
                return response()->json([
                    'success' => false,
                    'status' => 'error',
                    'message' => 'Anda belum melakukan presensi masuk hari ini.'
                ], 422);
            }

            if (!empty($presensi->jam_pulang)) {
                return response()->json([
                    'success' => false,
                    'status' => 'error',
                    'message' => 'Anda sudah melakukan presensi pulang hari ini pada pukul ' . substr($presensi->jam_pulang, 0, 5) . ' WIB.'
                ], 422);
            }

            $presensi->jam_pulang = $currentTime;
            $presensi->latitude_pulang = $request->latitude;
            $presensi->longitude_pulang = $request->longitude;
            $presensi->jarak_pulang_meter = $distance;
            if ($fotoName) {
                $presensi->foto_pulang = $fotoName;
            }

            $presensi->save();

            return response()->json([
                'success' => true,
                'status' => 'success',
                'message' => 'Presensi pulang PKL berhasil dicatat.',
                'data' => $presensi->fresh()
            ]);
        }
    }

    /**
     * Pengajuan Izin / Sakit siswa saat masa PKL.
     * Endpoint: POST /api/presensi-pkl/izin
     */
    public function izin(Request $request)
    {
        $request->validate([
            'id_siswa' => 'required',
            'status' => 'required|in:izin,sakit',
            'keterangan' => 'required|string|max:500',
            'foto' => 'nullable', // Surat dokter atau bukti keterangan
            'bukti' => 'nullable',
        ]);

        $today = now()->toDateString();

        $siswaPkl = SiswaPkl::with('perusahaan')
            ->where('id_siswa', $request->id_siswa)
            ->where('status', '!=', 'selesai')
            ->latest()
            ->first();

        if (!$siswaPkl) {
            return response()->json([
                'success' => false,
                'status' => 'error',
                'message' => 'Penempatan PKL aktif tidak ditemukan.'
            ], 422);
        }

        $presensi = AbsensiPkl::firstOrNew([
            'id_siswa' => $request->id_siswa,
            'tanggal' => $today,
        ]);

        if (!empty($presensi->jam_masuk)) {
            return response()->json([
                'success' => false,
                'status' => 'error',
                'message' => 'Anda sudah melakukan presensi masuk hari ini, tidak dapat mengajukan izin/sakit.'
            ], 422);
        }

        $fotoName = $this->simpanFoto($request, $request->id_siswa, $request->status);

        $presensi->id_siswa_pkl = $siswaPkl->id_siswa_pkl;
        $presensi->id_perusahaan = $siswaPkl->id_perusahaan;
        $presensi->status = $request->status;
        $presensi->keterangan = $request->keterangan;
        if ($fotoName) {
            $presensi->foto_masuk = $fotoName;
        }

        $presensi->save();

        return response()->json([
            'success' => true,
            'status' => 'success',
            'message' => 'Pengajuan ' . ucfirst($request->status) . ' PKL berhasil disimpan.',
            'data' => $presensi->fresh()
        ]);
    }

    /**
     * Riwayat presensi PKL siswa per bulan.
     * Endpoint: GET /api/presensi-pkl/riwayat/{idSiswa}?bulan=11&tahun=2026
     */
    public function getRiwayatPresensi(Request $request, $id_siswa)
    {
        $bulan = $request->query('bulan', now()->month);
        $tahun = $request->query('tahun', now()->year);

        $riwayat = AbsensiPkl::with('perusahaan')
            ->where('id_siswa', $id_siswa)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderBy('tanggal', 'desc')
            ->get();

        $totalHadir = $riwayat->where('status', 'hadir')->count();
        $totalIzin = $riwayat->where('status', 'izin')->count();
        $totalSakit = $riwayat->where('status', 'sakit')->count();

        return response()->json([
            'success' => true,
            'status' => 'success',
            'data' => [
                'ringkasan' => [
                    'hadir' => $totalHadir,
                    'izin' => $totalIzin,
                    'sakit' => $totalSakit,
                    'total_hari' => $riwayat->count(),
                ],
                'riwayat' => $riwayat
            ]
        ]);
    }

    /**
     * Hitung jarak antara dua koordinat GPS menggunakan formula Haversine.
     * Mengembalikan jarak dalam satuan meter.
     */
    private function calculateHaversineDistance(float $lat1, float $lon1, float $lat2, float $lon2): int
    {
        $earthRadius = 6371000; // Radius bumi dalam meter

        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($lonDelta / 2) * sin($lonDelta / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return (int) round($earthRadius * $c);
    }

    /**
     * Simpan file foto (mendukung multipart File upload dan Base64 string).
     */
    private function simpanFoto(Request $request, $id_siswa, string $prefix = 'masuk'): ?string
    {
        // 1. Cek file multipart
        $file = $request->file('foto') ?? $request->file('image') ?? $request->file('bukti');
        if ($file && $file->isValid()) {
            $ext = $file->getClientOriginalExtension() ?: 'jpg';
            $filename = 'absen_pkl_' . $prefix . '_' . $id_siswa . '_' . time() . '_' . Str::random(5) . '.' . $ext;
            $file->storeAs('absensi_pkl', $filename, 'public');
            return $filename;
        }

        // 2. Cek base64 string
        $base64Data = $request->input('foto') ?? $request->input('image') ?? $request->input('bukti');
        if ($base64Data && is_string($base64Data)) {
            try {
                if (preg_match('/^data:image\/(\w+);base64,/', $base64Data, $type)) {
                    $base64Data = substr($base64Data, strpos($base64Data, ',') + 1);
                    $ext = strtolower($type[1]);
                } else {
                    $ext = 'jpg';
                }
                if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                    $ext = 'jpg';
                }
                $decoded = base64_decode($base64Data);
                if ($decoded !== false && strlen($decoded) > 0) {
                    $filename = 'absen_pkl_' . $prefix . '_' . $id_siswa . '_' . time() . '_' . Str::random(5) . '.' . $ext;
                    Storage::disk('public')->put('absensi_pkl/' . $filename, $decoded);
                    return $filename;
                }
            } catch (\Throwable $e) {
                // Ignore gracefully
            }
        }

        return null;
    }

    /**
     * Periksa apakah siswa saat ini terkena aturan Lock Device berdasarkan pengaturan aplikasi.
     */
    private function isLockDeviceRequiredForSiswa(Siswa $siswa, SiswaPkl $siswaPkl, ?Setting $setting): bool
    {
        if (!$setting) return false;

        $attendanceMode = $setting->pkl_attendance_mode ?? 'radius_foto_lock';
        $lockScope = $setting->pkl_lock_scope ?? 'periode_pkl';

        if (!in_array($attendanceMode, ['radius_lock', 'radius_foto_lock']) || $lockScope === 'nonaktif') {
            return false;
        }

        $today = now()->toDateString();

        if ($lockScope === 'periode_pkl') {
            if ($siswaPkl && strtolower($siswaPkl->status) !== 'selesai') {
                $tglMulai = $siswaPkl->tanggal_mulai ? date('Y-m-d', strtotime($siswaPkl->tanggal_mulai)) : null;
                $tglSelesai = $siswaPkl->tanggal_selesai ? date('Y-m-d', strtotime($siswaPkl->tanggal_selesai)) : null;
                if ($tglMulai && $today >= $tglMulai && (!$tglSelesai || $today <= $tglSelesai)) {
                    return true;
                }
            }
            return false;
        }

        if ($lockScope === 'level_pkl') {
            $activeLevels = json_decode($setting->level_pkl ?? '[]', true) ?: ['XI', 'XII'];
            $kelasNama = $siswa->kelas->nama_kelas ?? '';
            foreach ($activeLevels as $lvl) {
                if (stripos($kelasNama, $lvl) !== false) {
                    return true;
                }
            }
            return false;
        }

        if ($lockScope === 'semua_siswa') {
            return $siswaPkl !== null && strtolower($siswaPkl->status) !== 'selesai';
        }

        return false;
    }

    private function getModeLabel(string $mode): string
    {
        return match($mode) {
            'radius_lock' => 'Radius + Lock Device',
            'radius_foto' => 'Radius + Foto Selfie',
            default => 'Radius + Foto + Lock Device'
        };
    }
}
