<?php

namespace App\Http\Controllers\SiswaController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Siswa;
use App\Models\OrangTua;
use App\Models\Absensi;
use App\Models\Rapot;
use Carbon\Carbon;
use DB;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(String $id_siswa)
    {
        $siswa = Siswa::where('id_siswa', $id_siswa)
        ->with('kelas')
        ->with('jurusan')
        ->first();
        $today = Carbon::now()->toDateString();
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

        $kehadiranToday = Absensi::where('id_siswa', $id_siswa)
                            ->where('tanggal', $today)
                            ->first();

        $rapotTerakhir = Rapot::where('id_siswa', $id_siswa)
                            ->latest()
                            ->first();
        $dataRapot = null;
        
        if ($rapotTerakhir) { // Periksa apakah $rapotTerakhir tidak null
            $semester = $rapotTerakhir->semester - 1;
            $dataRapot = Rapot::where('id_siswa', $id_siswa)
                            ->where('semester', $semester)
                            ->first();
        
            if ($dataRapot && $rapotTerakhir->rata_rata > $dataRapot->rata_rata) {
                $pesan = 'benar';
            } else {
                $pesan = 'salah';
            }
        } else {
            $pesan = 'Rapot terakhir tidak ditemukan.';
        }

        return response()->json([
            'success' => true,
            'data' => $siswa,
            'rapotTerakhir' => $rapotTerakhir,
            'dataRapot' => $dataRapot,
            'pesan' => $pesan,
            'presentaseKehadiran' => $presentaseKehadiran,
            'kehadiranToday' => $kehadiranToday,
            'message' => 'Berhasil login'
        ]);
    }

    public function login(Request $request)
    {
        $identifier = trim($request->input('nis') ?? $request->input('username') ?? '');
        $password = $request->input('password');

        if (empty($identifier) || empty($password)) {
            return response()->json([
                'success' => false,
                'message' => 'Kredensial dan kata sandi wajib diisi'
            ], 200);
        }

        // 1. Cek kredensial Siswa terlebih dahulu berdasarkan NIS
        $siswa = Siswa::where('nis', $identifier)
            ->with(['kelas', 'jurusan'])
            ->first();

        if ($siswa) {
            $passwordValid = $this->verifyPassword($password, $siswa->password);
            if (!$passwordValid) {
                return response()->json([
                    'success' => false,
                    'message' => 'Password Salah'
                ], 200);
            }

            Auth::login($siswa);

            $responseData = $siswa->toArray();
            $responseData['role'] = 'siswa';
            $responseData['children'] = [];

            return response()->json([
                'success' => true,
                'role' => 'siswa',
                'data' => $responseData,
                'children' => [],
                'message' => 'Berhasil login sebagai Siswa'
            ]);
        }

        // 2. Jika bukan Siswa, cek kredensial Orang Tua (Username / Nomor Telepon)
        $cleanPhone = preg_replace('/[^0-9]/', '', $identifier);
        $candidates = array_unique(array_filter([
            $identifier,
            $cleanPhone,
            !empty($cleanPhone) && str_starts_with($cleanPhone, '0') ? '62' . substr($cleanPhone, 1) : null,
            !empty($cleanPhone) && str_starts_with($cleanPhone, '62') ? '0' . substr($cleanPhone, 2) : null,
        ]));

        $ortu = OrangTua::where(function ($query) use ($identifier, $candidates) {
            $query->where('username', $identifier)
                ->orWhereIn('username', $candidates)
                ->orWhereIn('no_hp', $candidates);
        })->first();

        // Smart Alias untuk Akun Multi-Anak: Jika orang tua login dengan format ortu_{nis} anak kedua / ketiga
        if (!$ortu && str_starts_with(strtolower($identifier), 'ortu_')) {
            $targetNis = substr($identifier, 5);
            $siblingSiswa = Siswa::where('nis', $targetNis)->first();
            if ($siblingSiswa && $siblingSiswa->id_orang_tua) {
                $ortu = OrangTua::find($siblingSiswa->id_orang_tua);
            }
        }

        if ($ortu) {
            $passwordValid = $this->verifyPassword($password, $ortu->password);
            if (!$passwordValid) {
                return response()->json([
                    'success' => false,
                    'message' => 'Password Salah'
                ], 200);
            }

            if (!$ortu->status_aktif) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akun orang tua tidak aktif. Silakan hubungi admin sekolah.'
                ], 200);
            }

            $children = Siswa::where('id_orang_tua', $ortu->id_orang_tua)
                ->with(['kelas', 'jurusan'])
                ->get();
            $defaultChild = $children->first();

            // Format backward-compatible untuk aplikasi mobile lama & baru
            $responseData = [
                'id_siswa' => $defaultChild ? $defaultChild->id_siswa : '',
                'nis' => $defaultChild ? $defaultChild->nis : $ortu->username,
                'nama_siswa' => $defaultChild ? $defaultChild->nama_siswa : $ortu->nama_lengkap,
                'foto' => $defaultChild ? $defaultChild->foto : null,
                'kelas' => $defaultChild ? $defaultChild->kelas : null,
                'jurusan' => $defaultChild ? $defaultChild->jurusan : null,
                'id_orang_tua' => $ortu->id_orang_tua,
                'nama_lengkap' => $ortu->nama_lengkap,
                'username' => $ortu->username,
                'no_hp' => $ortu->no_hp,
                'role' => 'orang_tua',
                'children' => $children,
            ];

            return response()->json([
                'success' => true,
                'role' => 'orang_tua',
                'data' => $responseData,
                'children' => $children,
                'message' => 'Berhasil login sebagai Orang Tua'
            ]);
        }

        // 3. Jika tidak ditemukan sama sekali
        return response()->json([
            'success' => false,
            'message' => 'NIS atau Nomor WhatsApp/Username tidak ditemukan'
        ], 200);
    }

    public function ubahPassword(Request $request)
    {
        $request->validate([
            'idSiswa' => 'required',
            'password1' => 'required',
            'password2' => 'required'
        ]);

        if ($request->password1 != $request->password2) {
            return response()->json([
                'success' => false,
                'message' => 'Password yang anda masukan tidak cocok'
            ], 401);
        }

        $user = Siswa::where('id_siswa', $request->idSiswa)->update([
            'password' => $request->password1,
        ]);

        return response()->json([
            'success' => true,
            'data' => $user,
            'message' => 'Password Berhasil Diubah'
        ]);
    }

    public function ubahPasswordOrangTua(Request $request)
    {
        $request->validate([
            'id_orang_tua' => 'required',
            'password1' => 'required',
            'password2' => 'required'
        ]);

        if ($request->password1 !== $request->password2) {
            return response()->json([
                'success' => false,
                'message' => 'Konfirmasi kata sandi tidak cocok'
            ], 400);
        }

        $ortu = OrangTua::find($request->id_orang_tua);
        if (!$ortu) {
            return response()->json([
                'success' => false,
                'message' => 'Akun orang tua tidak ditemukan'
            ], 404);
        }

        $ortu->update([
            'password' => Hash::make($request->password1)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kata sandi orang tua berhasil diperbarui'
        ]);
    }

    public function getChildrenOrangTua($idOrangTua)
    {
        $children = Siswa::where('id_orang_tua', $idOrangTua)
            ->with(['kelas', 'jurusan'])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $children,
            'message' => 'Berhasil mengambil daftar anak'
        ]);
    }

    public function updateFcmToken(Request $request)
    {
        $request->validate([
            'fcm_token' => 'required'
        ]);

        $idSiswa = $request->input('idSiswa');
        $idOrangTua = $request->input('id_orang_tua');

        if ($idOrangTua) {
            $children = Siswa::where('id_orang_tua', $idOrangTua)->get();
            foreach ($children as $c) {
                $tokenHash = md5($c->id_siswa . '_' . $request->fcm_token);
                \App\Models\SiswaFcmToken::updateOrCreate(
                    ['token_hash' => $tokenHash],
                    [
                        'id_siswa' => $c->id_siswa,
                        'fcm_token' => $request->fcm_token,
                        'device_name' => $request->input('device_name', 'Parent Device'),
                        'updated_at' => now(),
                    ]
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'FCM Token updated successfully for parent devices'
            ]);
        }

        if ($idSiswa) {
            $siswa = Siswa::where('id_siswa', $idSiswa)->first();
            if ($siswa) {
                $siswa->update([
                    'fcm_token' => $request->fcm_token
                ]);

                $tokenHash = md5($request->fcm_token);
                \App\Models\SiswaFcmToken::updateOrCreate(
                    ['token_hash' => $tokenHash],
                    [
                        'id_siswa' => $siswa->id_siswa,
                        'fcm_token' => $request->fcm_token,
                        'device_name' => $request->input('device_name', 'Mobile Device'),
                        'updated_at' => now(),
                    ]
                );

                return response()->json([
                    'success' => true,
                    'message' => 'FCM Token updated successfully'
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Siswa or Orang Tua not found'
        ], 404);
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
        //
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
    /**
     * Verifikasi password aman untuk plain text maupun bcrypt hash.
     */
    private function verifyPassword(string $plainPassword, ?string $storedPassword): bool
    {
        if (empty($storedPassword)) {
            return false;
        }

        if ($plainPassword === $storedPassword) {
            return true;
        }

        $info = password_get_info($storedPassword);
        if (!empty($info['algo'])) {
            return Hash::check($plainPassword, $storedPassword);
        }

        return false;
    }
}
