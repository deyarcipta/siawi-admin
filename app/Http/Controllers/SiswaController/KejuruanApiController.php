<?php

namespace App\Http\Controllers\SiswaController;

use App\Http\Controllers\Controller;
use App\Models\CapaianSiswa;
use App\Models\JadwalPraktik;
use App\Models\Kompetensi;
use App\Models\ModulPraktik;
use App\Models\NilaiPraktikSiswa;
use App\Models\PortofolioSiswa;
use App\Models\Siswa;
use App\Models\SiswaTkk;
use App\Models\UkkKegiatan;
use App\Models\UkkPeserta;
use Carbon\Carbon;
use Illuminate\Http\Request;

class KejuruanApiController extends Controller
{
    /**
     * Dashboard ringkasan Kejuruan siswa (Header, Praktik Berikutnya, & 3 Statistik).
     */
    public function dashboard(string $idSiswa)
    {
        $siswa = Siswa::with(['kelas.jurusan', 'jurusan'])->find($idSiswa);
        if (!$siswa) {
            return response()->json([
                'success' => false,
                'message' => 'Data siswa tidak ditemukan',
            ], 404);
        }

        $namaJurusan = $siswa->jurusan->nama_jurusan 
            ?? $siswa->kelas->jurusan->nama_jurusan 
            ?? 'Teknik Komputer dan Jaringan';
        $namaKelas = $siswa->kelas->nama_kelas ?? 'Kelas XI';
        $subtitle = $namaJurusan . ' · ' . $namaKelas;

        // 1. Praktik Berikutnya
        $hariMap = [
            'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu', 'Sunday' => 'Minggu',
        ];
        $todayEnglish = Carbon::now()->format('l');
        $todayIndo = $hariMap[$todayEnglish] ?? 'Senin';

        // Cari jadwal praktik kelas siswa hari ini atau berikutnya
        $idKelas = $siswa->id_kelas;
        $allJadwal = JadwalPraktik::where('id_kelas', $idKelas)
            ->with(['lab', 'guru', 'mapel'])
            ->get();

        $jadwalHariIni = $allJadwal->first(function ($item) use ($todayIndo) {
            return strcasecmp($item->hari, $todayIndo) === 0;
        });

        $praktikPilihan = $jadwalHariIni ?: $allJadwal->first();

        $praktikBerikutnya = null;
        if ($praktikPilihan) {
            $isToday = $jadwalHariIni !== null;
            $jamMulai = substr($praktikPilihan->jam_mulai, 0, 5);
            $jamSelesai = substr($praktikPilihan->jam_selesai, 0, 5);
            $namaLab = $praktikPilihan->lab->nama_lab ?? 'Lab 2';
            $namaGuru = $praktikPilihan->guru->nama_guru ?? 'Pak Andi';
            $materi = $praktikPilihan->mapel->nama_mapel ?? 'Instalasi jaringan LAN';

            $praktikBerikutnya = [
                'judul' => $materi,
                'lab' => $namaLab,
                'jam' => "$jamMulai sampai $jamSelesai",
                'guru' => $namaGuru,
                'subtitle' => "$namaLab · $jamMulai sampai $jamSelesai · $namaGuru",
                'alat_bahan' => $praktikPilihan->alat_bahan ?: 'kabel UTP, crimping tool, LAN tester',
                'catatan_modul' => 'Baca modul sebelum praktik',
                'badge' => $isToday ? 'Hari ini' : $praktikPilihan->hari,
            ];
        } else {
            // Default mockup data jika belum ada jadwal di database
            $praktikBerikutnya = [
                'judul' => 'Instalasi jaringan LAN',
                'lab' => 'Lab 2',
                'jam' => '08.00 sampai 10.15',
                'guru' => 'Pak Andi',
                'subtitle' => 'Lab 2 · 08.00 sampai 10.15 · Pak Andi',
                'alat_bahan' => 'kabel UTP, crimping tool, LAN tester',
                'catatan_modul' => 'Baca modul sebelum praktik',
                'badge' => 'Hari ini',
            ];
        }

        // 2. Tiga Statistik (Kehadiran Praktik, Rata-rata Nilai, Perlu Remedial)
        $nilaiSiswas = NilaiPraktikSiswa::where('id_siswa', $idSiswa)->get();
        $totalNilai = $nilaiSiswas->count();
        $avgNilai = $totalNilai > 0 ? round($nilaiSiswas->avg('nilai_akhir')) : 82;
        $remedialCount = $nilaiSiswas->where('is_remedial', true)->count();
        if ($totalNilai == 0) {
            $remedialCount = 1; // Default representatif
        }

        $kehadiranPraktik = '96%';

        return response()->json([
            'success' => true,
            'data' => [
                'nama_siswa' => $siswa->nama_siswa ?? 'Siswa',
                'jurusan' => $namaJurusan,
                'kelas' => $namaKelas,
                'subtitle' => $subtitle,
                'praktik_berikutnya' => $praktikBerikutnya,
                'stats' => [
                    'kehadiran_praktik' => '96%',
                    'kehadiran_sesi' => '(24/25 Sesi)',
                    'kehadiran_badge' => 'Sangat Disiplin',
                    'rata_rata_nilai' => '88.5',
                    'predikat' => 'Predikat A-',
                    'kompetensi_badge' => 'Kompeten',
                    'perlu_remedial' => (string) $remedialCount,
                    'remedial_modul' => '1 Modul Aktif',
                    'remedial_topik' => 'Subnetting & CIDR',
                    'remedial_kkm' => 'Target Nilai KKM 75',
                ],
                'peminjaman_alat' => [
                    'nama_alat' => '1 MikroTik RB951',
                    'status' => 'Dipinjam',
                    'lokasi' => 'Lab 2',
                    'batas_kembali' => '11.00 WIB',
                    'keterangan' => 'Lab 2 • Kembalikan sebelum 11.00 WIB',
                    'sn' => 'RB951-2HnD-7489',
                    'jam_pinjam' => '08.00 WIB',
                    'petugas' => 'Pak Hendra, S.Kom (Instruktur Lab)',
                ],
            ],
        ]);
    }

    /**
     * Jadwal Praktik Siswa.
     */
    public function jadwal(string $idSiswa)
    {
        $siswa = Siswa::find($idSiswa);
        if (!$siswa) {
            return response()->json(['success' => false, 'message' => 'Siswa tidak ditemukan'], 404);
        }

        $jadwals = JadwalPraktik::where('id_kelas', $siswa->id_kelas)
            ->with(['lab', 'guru', 'mapel'])
            ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')")
            ->get();

        return response()->json([
            'success' => true,
            'data' => $jadwals,
        ]);
    }

    /**
     * Modul Praktik Siswa.
     */
    public function modul(string $idSiswa)
    {
        $siswa = Siswa::with(['kelas', 'jurusan'])->find($idSiswa);
        if (!$siswa) {
            return response()->json(['success' => false, 'message' => 'Siswa tidak ditemukan'], 404);
        }

        $idJurusan = $siswa->id_jurusan ?? ($siswa->kelas->id_jurusan ?? null);

        $moduls = ModulPraktik::when($idJurusan, function ($q) use ($idJurusan) {
                $q->whereHas('kompetensi', function ($sub) use ($idJurusan) {
                    $sub->where('id_jurusan', $idJurusan);
                });
            })
            ->with(['kompetensi', 'guru', 'versis'])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $moduls,
        ]);
    }

    /**
     * Nilai Praktik Siswa.
     */
    public function nilai(string $idSiswa)
    {
        $nilais = NilaiPraktikSiswa::where('id_siswa', $idSiswa)
            ->with(['penilaian.modul', 'penilaian.guru', 'penilaian.kompetensi'])
            ->orderBy('id_nilai_praktik', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $nilais,
        ]);
    }

    /**
     * Uji Kompetensi Keahlian (UKK) Siswa.
     */
    public function ukk(string $idSiswa)
    {
        $peserta = UkkPeserta::where('id_siswa', $idSiswa)
            ->with(['kegiatan.pengujis.guru', 'nilais.unitKompetensi'])
            ->first();

        return response()->json([
            'success' => true,
            'data' => $peserta,
        ]);
    }

    /**
     * Portofolio dan Sertifikat Siswa.
     */
    public function portofolio(string $idSiswa)
    {
        $portofolio = PortofolioSiswa::where('id_siswa', $idSiswa)
            ->with(['kompetensi', 'verifikator'])
            ->orderBy('tanggal_kegiatan', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $portofolio,
        ]);
    }

    /**
     * Tanda Kecakapan Kejuruan (TKK) Siswa.
     */
    public function tkk(string $idSiswa)
    {
        $tkks = SiswaTkk::where('id_siswa', $idSiswa)
            ->with(['jenisTkk', 'pemberi'])
            ->orderBy('tanggal_perolehan', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $tkks,
        ]);
    }
}
