<?php

namespace App\Http\Controllers\SiswaController;

use App\Http\Controllers\Controller;
use App\Models\CapaianSiswa;
use App\Models\JadwalPraktik;
use App\Models\JurnalPraktikPresensi;
use App\Models\Kompetensi;
use App\Models\ModulPraktik;
use App\Models\NilaiPraktikSiswa;
use App\Models\PeminjamanAset;
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
        $namaKelas = $siswa->kelas->nama_kelas ?? 'Kelas';
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
            $namaLab = $praktikPilihan->lab->nama_lab ?? 'Lab';
            $namaGuru = $praktikPilihan->guru->nama_guru ?? 'Guru Pengampu';
            $materi = $praktikPilihan->mapel->nama_mapel ?? 'Praktik Kejuruan';

            $praktikBerikutnya = [
                'judul' => $materi,
                'lab' => $namaLab,
                'jam' => "$jamMulai - $jamSelesai",
                'guru' => $namaGuru,
                'subtitle' => "$namaLab · $jamMulai - $jamSelesai · $namaGuru",
                'alat_bahan' => $praktikPilihan->alat_bahan ?: '-',
                'catatan_modul' => 'Silakan pelajari modul sebelum praktik',
                'badge' => $isToday ? 'Hari ini' : $praktikPilihan->hari,
            ];
        }

        // 2. Statistik Kehadiran dari Absensi Jurnal Praktik Siswa
        $presensiList = JurnalPraktikPresensi::where('id_siswa', $idSiswa)->get();
        $totalSesi = $presensiList->count();
        $hadirSesi = $presensiList->where('status', 'hadir')->count();
        $kehadiranPersen = $totalSesi > 0 ? round(($hadirSesi / $totalSesi) * 100) : 0;
        $kehadiranBadge = $totalSesi == 0 ? '-' : ($kehadiranPersen >= 90 ? 'Sangat Disiplin' : ($kehadiranPersen >= 75 ? 'Cukup Baik' : 'Kurang'));

        // 3. Statistik Nilai & Remedial
        $nilaiSiswas = NilaiPraktikSiswa::where('id_siswa', $idSiswa)->get();
        $totalNilai = $nilaiSiswas->count();
        $avgNilai = $totalNilai > 0 ? round($nilaiSiswas->avg('nilai_akhir'), 1) : 0;
        $remedialCount = $nilaiSiswas->where('is_remedial', true)->count();

        // 4. Peminjaman Alat Aktif
        $peminjamanAktif = PeminjamanAset::where('id_peminjam_siswa', $idSiswa)
            ->where('status', 'dipinjam')
            ->with(['aset', 'petugas'])
            ->first();

        $peminjamanAlatData = null;
        if ($peminjamanAktif) {
            $namaAset = $peminjamanAktif->aset->nama_aset ?? 'Peralatan Praktik';
            $peminjamanAlatData = [
                'nama_alat' => $peminjamanAktif->jumlah . ' ' . $namaAset,
                'status' => 'Dipinjam',
                'lokasi' => $peminjamanAktif->aset->lokasi ?? 'Lab',
                'batas_kembali' => $peminjamanAktif->rencana_kembali ? Carbon::parse($peminjamanAktif->rencana_kembali)->format('d M Y') : '-',
                'keterangan' => 'Kembalikan tepat waktu',
                'sn' => $peminjamanAktif->aset->kode_aset ?? '-',
                'jam_pinjam' => Carbon::parse($peminjamanAktif->tanggal_pinjam)->format('d M Y'),
                'petugas' => $peminjamanAktif->petugas->nama_guru ?? 'Instruktur Lab',
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'nama_siswa' => $siswa->nama_siswa ?? 'Siswa',
                'jurusan' => $namaJurusan,
                'kelas' => $namaKelas,
                'subtitle' => $subtitle,
                'praktik_berikutnya' => $praktikBerikutnya,
                'stats' => [
                    'kehadiran_praktik' => "$kehadiranPersen%",
                    'kehadiran_sesi' => "($hadirSesi/$totalSesi Sesi)",
                    'kehadiran_badge' => $kehadiranBadge,
                    'rata_rata_nilai' => (string) $avgNilai,
                    'predikat' => $totalNilai > 0 ? ($avgNilai >= 85 ? 'Predikat A' : ($avgNilai >= 75 ? 'Predikat B' : 'Predikat C')) : '-',
                    'kompetensi_badge' => $totalNilai > 0 ? ($avgNilai >= 75 ? 'Kompeten' : 'Belum Kompeten') : '-',
                    'perlu_remedial' => (string) $remedialCount,
                    'remedial_modul' => $remedialCount > 0 ? "$remedialCount Modul Perlu Remedial" : "Tidak Ada Remedial",
                    'remedial_topik' => '-',
                    'remedial_kkm' => 'Target KKM 75',
                ],
                'peminjaman_alat' => $peminjamanAlatData,
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
     * Presensi Bengkel / Lab Siswa (Diambil dari Rekapitulasi Jurnal Praktik Guru).
     */
    public function presensi(string $idSiswa)
    {
        $siswa = Siswa::find($idSiswa);
        if (!$siswa) {
            return response()->json(['success' => false, 'message' => 'Siswa tidak ditemukan'], 404);
        }

        $presensiList = JurnalPraktikPresensi::where('id_siswa', $idSiswa)
            ->with([
                'jurnalPraktik.jadwalPraktik.lab',
                'jurnalPraktik.jadwalPraktik.mapel',
                'jurnalPraktik.jadwalPraktik.guru',
                'jurnalPraktik.modulPraktik'
            ])
            ->orderByDesc('id_presensi_praktik')
            ->get();

        $total = $presensiList->count();
        $hadir = $presensiList->where('status', 'hadir')->count();
        $izin = $presensiList->where('status', 'izin')->count();
        $sakit = $presensiList->where('status', 'sakit')->count();
        $alpa = $presensiList->where('status', 'alpa')->count();

        $persen = $total > 0 ? round(($hadir / $total) * 100, 1) : 0;

        $formattedList = $presensiList->map(function ($item) {
            $jurnal = $item->jurnalPraktik;
            $jadwal = $jurnal->jadwalPraktik ?? null;
            $jamMulai = $jadwal ? substr($jadwal->jam_mulai, 0, 5) : '';
            $jamSelesai = $jadwal ? substr($jadwal->jam_selesai, 0, 5) : '';
            $jamString = ($jamMulai && $jamSelesai) ? "$jamMulai - $jamSelesai" : '-';

            return [
                'id_presensi' => $item->id_presensi_praktik,
                'id_jurnal' => $item->id_jurnal_praktik,
                'tanggal' => $jurnal ? Carbon::parse($jurnal->tanggal)->format('Y-m-d') : '',
                'tanggal_formatted' => $jurnal ? Carbon::parse($jurnal->tanggal)->translatedFormat('d M Y') : '',
                'materi' => $jurnal ? strip_tags($jurnal->materi) : '-',
                'mapel' => $jadwal->mapel->nama_mapel ?? ($jurnal->modulPraktik->judul ?? 'Praktik Kejuruan'),
                'lab' => $jadwal->lab->nama_lab ?? 'Lab Kejuruan',
                'guru' => $jadwal->guru->nama_guru ?? 'Instruktur',
                'jam' => $jamString,
                'status' => ucfirst($item->status),
                'catatan' => $item->catatan ?? '-',
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'summary' => [
                    'total_pertemuan' => $total,
                    'hadir' => $hadir,
                    'izin' => $izin,
                    'sakit' => $sakit,
                    'alpa' => $alpa,
                    'persentase' => "$persen%",
                    'badge' => $total == 0 ? '-' : ($persen >= 90 ? 'Sangat Baik' : ($persen >= 75 ? 'Baik' : 'Perlu Ditingkatkan')),
                ],
                'riwayat' => $formattedList,
            ]
        ]);
    }

    /**
     * Peminjaman Alat Siswa.
     */
    public function peminjaman(string $idSiswa)
    {
        $siswa = Siswa::find($idSiswa);
        if (!$siswa) {
            return response()->json(['success' => false, 'message' => 'Siswa tidak ditemukan'], 404);
        }

        $list = PeminjamanAset::where('id_peminjam_siswa', $idSiswa)
            ->with(['aset.lab', 'petugas'])
            ->orderBy('id_peminjaman', 'desc')
            ->get();

        $sedangDipinjam = [];
        $riwayatPinjam = [];

        foreach ($list as $item) {
            $row = [
                'id' => $item->id_peminjaman,
                'nama' => $item->aset->nama_aset ?? 'Peralatan Praktik',
                'kode' => $item->kode_peminjaman,
                'sn' => $item->aset->kode_aset ?? '-',
                'jumlah' => $item->jumlah . ' Unit',
                'lokasi' => $item->aset->lab->nama_lab ?? ($item->aset->lokasi ?? 'Lab'),
                'tgl_pinjam' => Carbon::parse($item->tanggal_pinjam)->translatedFormat('d M Y'),
                'tgl_kembali' => $item->tanggal_kembali ? Carbon::parse($item->tanggal_kembali)->translatedFormat('d M Y') : ($item->rencana_kembali ? Carbon::parse($item->rencana_kembali)->translatedFormat('d M Y') : '-'),
                'status' => ucfirst($item->status),
                'kondisi' => ucfirst(str_replace('_', ' ', $item->kondisi_sebelum ?? 'baik')),
                'petugas' => $item->petugas->nama_guru ?? 'Instruktur',
                'keperluan' => $item->keperluan ?: 'Praktikum Siswa',
            ];

            if ($item->status === 'dipinjam') {
                $sedangDipinjam[] = $row;
            } else {
                $riwayatPinjam[] = $row;
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'sedang_dipinjam' => $sedangDipinjam,
                'riwayat' => $riwayatPinjam,
            ]
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