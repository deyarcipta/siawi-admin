<?php

namespace App\Http\Controllers\SiswaController;

use App\Http\Controllers\Controller;
use App\Models\AsetLab;
use App\Models\CapaianSiswa;
use App\Models\JadwalPraktik;
use App\Models\JenisTkk;
use App\Models\JurnalPraktikPresensi;
use App\Models\ModulPraktik;
use App\Models\NilaiPraktikSiswa;
use App\Models\PeminjamanAset;
use App\Models\PortofolioSiswa;
use App\Models\Siswa;
use App\Models\SiswaTkk;
use App\Models\UkkPeserta;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KejuruanApiController extends Controller
{
    /**
     * Dashboard ringkasan Kejuruan siswa (Header, Praktik Berikutnya, & Statistik).
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

        $jurusanObj = $siswa->jurusan ?? $siswa->kelas->jurusan ?? null;
        $namaJurusan = $jurusanObj->nama_jurusan ?? 'Program Keahlian';
        $kodeJurusan = $jurusanObj->kode_jurusan ?? 'KEJURUAN';
        $namaKelas = $siswa->kelas->nama_kelas ?? 'Kelas';
        $subtitle = $namaJurusan . ' · ' . $namaKelas;

        // 1. Praktik Berikutnya
        $hariMap = [
            'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu', 'Sunday' => 'Minggu',
        ];
        $todayEnglish = Carbon::now()->format('l');
        $todayIndo = $hariMap[$todayEnglish] ?? 'Senin';

        $idKelas = $siswa->id_kelas;
        $allJadwal = JadwalPraktik::where('id_kelas', $idKelas)
            ->with(['lab', 'guru', 'mapel'])
            ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')")
            ->orderBy('jam_mulai')
            ->get();

        $jadwalHariIniList = $allJadwal->filter(function ($item) use ($todayIndo) {
            return $item->isActiveOnDate() || strcasecmp($item->hari, $todayIndo) === 0;
        })->values();

        $daftarPraktikPilihan = $jadwalHariIniList->isNotEmpty()
            ? $jadwalHariIniList
            : ($allJadwal->isNotEmpty() ? collect([$allJadwal->first()]) : collect());

        $isToday = $jadwalHariIniList->isNotEmpty();
        $totalSesiHariIni = $daftarPraktikPilihan->count();

        $praktikHariIniList = $daftarPraktikPilihan->values()->map(function ($praktikPilihan, $idx) use ($isToday, $totalSesiHariIni) {
            $jamMulai = substr($praktikPilihan->jam_mulai, 0, 5);
            $jamSelesai = substr($praktikPilihan->jam_selesai, 0, 5);
            $namaLab = $praktikPilihan->lab->nama_lab ?? 'Lab Praktik';
            $namaGuru = $praktikPilihan->guru->nama_guru ?? 'Guru Pengampu';
            $noHpGuru = $praktikPilihan->guru->no_hp
                ?? ($praktikPilihan->guru->no_telp ?? ($praktikPilihan->guru->telepon ?? null));
            $materi = $praktikPilihan->mapel->nama_mapel ?? 'Praktik Kejuruan';
            $badgeWaktu = $isToday
                ? ($totalSesiHariIni > 1 ? 'Hari Ini • Sesi ' . ($idx + 1) : 'Hari Ini')
                : $praktikPilihan->hari;

            return [
                'id_jadwal_praktik' => $praktikPilihan->id_jadwal_praktik,
                'judul' => $materi,
                'lab' => $namaLab,
                'lokasi' => $namaLab,
                'jam' => "$jamMulai - $jamSelesai",
                'jadwal' => "$jamMulai - $jamSelesai WIB",
                'guru' => $namaGuru,
                'no_hp_guru' => $noHpGuru,
                'peran_guru' => 'Instruktur Praktik (' . $praktikPilihan->label_frekuensi . ')',
                'subtitle' => "$namaLab · $jamMulai - $jamSelesai · $namaGuru",
                'alat_bahan' => $praktikPilihan->alat_bahan ?: '-',
                'catatan_modul' => 'Silakan pelajari modul sebelum praktik dimulai',
                'badge' => $badgeWaktu,
                'badge_waktu' => $badgeWaktu,
                'modul_nomor' => 'JP-' . str_pad($praktikPilihan->id_jadwal_praktik, 3, '0', STR_PAD_LEFT),
                'topik_modul' => $praktikPilihan->label_frekuensi,
            ];
        })->all();

        $praktikBerikutnya = $praktikHariIniList[0] ?? null;

        // 2. Statistik Kehadiran dari Absensi Jurnal Praktik Siswa
        $presensiList = JurnalPraktikPresensi::where('id_siswa', $idSiswa)->get();
        $totalSesi = $presensiList->count();
        $hadirSesi = $presensiList->where('status', 'hadir')->count();
        $kehadiranPersen = $totalSesi > 0 ? round(($hadirSesi / $totalSesi) * 100) : 0;
        $kehadiranBadge = $totalSesi == 0 ? '-' : ($kehadiranPersen >= 90 ? 'Sangat Disiplin' : ($kehadiranPersen >= 75 ? 'Cukup Baik' : 'Perlu Ditingkatkan'));

        // 3. Statistik Nilai & Remedial (Hanya penilaian yang sudah berstatus final)
        $nilaiSiswas = NilaiPraktikSiswa::where('id_siswa', $idSiswa)
            ->whereHas('penilaian', function ($q) {
                $q->where('status', 'final');
            })
            ->with(['penilaian.modul', 'penilaian.kompetensi'])
            ->orderByDesc('id_nilai_praktik')
            ->get();

        $totalNilai = $nilaiSiswas->count();
        $avgNilai = $totalNilai > 0 ? round($nilaiSiswas->avg('nilai_akhir'), 1) : 0;

        $remedialItems = $nilaiSiswas->filter(function ($n) {
            $kkm = floatval($n->penilaian->kkm_saat_penilaian ?? 75);
            return $n->status_kompetensi === 'belum_kompeten' || floatval($n->nilai_akhir) < $kkm;
        });
        $remedialCount = $remedialItems->count();
        $firstRemedial = $remedialItems->first();

        $remedialTopik = '-';
        $remedialKkm = 'Target KKM 75';
        if ($firstRemedial && $firstRemedial->penilaian) {
            $remedialTopik = $firstRemedial->penilaian->modul->judul
                ?? ($firstRemedial->penilaian->kompetensi->nama_kompetensi ?? 'Modul Praktik');
            $remedialKkm = 'Target KKM ' . round(floatval($firstRemedial->penilaian->kkm_saat_penilaian ?? 75));
        }

        // 4. Peminjaman Alat Aktif (status dipinjam atau terlambat)
        $peminjamanAktif = PeminjamanAset::where('id_peminjam_siswa', $idSiswa)
            ->whereIn('status', ['dipinjam', 'terlambat'])
            ->with(['aset.lab', 'petugas'])
            ->latest('id_peminjaman')
            ->first();

        $peminjamanAlatData = null;
        if ($peminjamanAktif) {
            $namaAset = $peminjamanAktif->aset->nama_aset ?? 'Peralatan Praktik';
            $satuan = $peminjamanAktif->aset->satuan ?? 'Unit';
            $namaLabAset = $peminjamanAktif->aset->lab->nama_lab ?? 'Lab Kejuruan';

            $peminjamanAlatData = [
                'id' => $peminjamanAktif->id_peminjaman,
                'nama_alat' => $peminjamanAktif->jumlah . ' ' . $satuan . ' ' . $namaAset,
                'nama' => $namaAset,
                'status' => ucfirst($peminjamanAktif->status),
                'lokasi' => $namaLabAset,
                'batas_kembali' => $peminjamanAktif->rencana_kembali ? Carbon::parse($peminjamanAktif->rencana_kembali)->translatedFormat('d M Y') : '-',
                'keterangan' => 'Kembalikan tepat waktu',
                'sn' => $peminjamanAktif->aset->kode_aset ?? '-',
                'kode' => $peminjamanAktif->kode_peminjaman,
                'jam_pinjam' => $peminjamanAktif->tanggal_pinjam ? Carbon::parse($peminjamanAktif->tanggal_pinjam)->translatedFormat('d M Y') : '-',
                'petugas' => $peminjamanAktif->petugas->nama_guru ?? 'Instruktur Lab',
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'nama_siswa' => $siswa->nama_siswa ?? 'Siswa',
                'jurusan' => $namaJurusan,
                'kode_jurusan' => $kodeJurusan,
                'kelas' => $namaKelas,
                'subtitle' => $subtitle,
                'modul_kejuruan' => $jurusanObj ? $jurusanObj->getModulKejuruan() : [],
                'praktik_berikutnya' => $praktikBerikutnya,
                'praktik_hari_ini' => $praktikHariIniList,
                'stats' => [
                    'kehadiran_praktik' => "$kehadiranPersen%",
                    'kehadiran_sesi' => "($hadirSesi/$totalSesi Sesi)",
                    'kehadiran_badge' => $kehadiranBadge,
                    'rata_rata_nilai' => (string) $avgNilai,
                    'predikat' => $totalNilai > 0 ? ($avgNilai >= 85 ? 'Predikat A' : ($avgNilai >= 75 ? 'Predikat B' : 'Predikat C')) : '-',
                    'kompetensi_badge' => $totalNilai > 0 ? ($avgNilai >= 75 ? 'Kompeten' : 'Belum Kompeten') : '-',
                    'perlu_remedial' => (string) $remedialCount,
                    'remedial_modul' => $remedialCount > 0 ? "$remedialCount Modul Perlu Remedial" : '',
                    'remedial_topik' => $remedialTopik,
                    'remedial_kkm' => $remedialKkm,
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
        $siswa = Siswa::with(['kelas.jurusan', 'jurusan'])->find($idSiswa);
        if (!$siswa) {
            return response()->json(['success' => false, 'message' => 'Siswa tidak ditemukan'], 404);
        }

        $jurusanObj = $siswa->jurusan ?? $siswa->kelas->jurusan ?? null;
        $namaJurusan = $jurusanObj->nama_jurusan ?? 'Kejuruan';
        $kodeJurusan = $jurusanObj->kode_jurusan ?? 'KEJURUAN';

        $jadwals = JadwalPraktik::where('id_kelas', $siswa->id_kelas)
            ->with(['lab', 'guru', 'mapel'])
            ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')")
            ->orderBy('jam_mulai')
            ->get()
            ->map(function ($item) use ($namaJurusan, $kodeJurusan) {
                $arr = $item->toArray();
                $arr['kode_jadwal'] = 'JP-' . str_pad($item->id_jadwal_praktik, 3, '0', STR_PAD_LEFT);
                $arr['kode'] = $arr['kode_jadwal'];
                $arr['status'] = $item->label_frekuensi;
                $arr['frekuensi_label'] = $item->label_frekuensi;
                $arr['is_active'] = $item->isActiveOnDate();
                $arr['ruangan'] = $item->lab->nama_lab ?? 'Lab Praktik';
                $arr['nama_jurusan'] = $namaJurusan;
                $arr['kode_jurusan'] = $kodeJurusan;
                return $arr;
            });

        return response()->json([
            'success' => true,
            'data' => $jadwals,
            'meta' => [
                'nama_jurusan' => $namaJurusan,
                'kode_jurusan' => $kodeJurusan,
            ],
        ]);
    }

    /**
     * Modul Praktik Siswa (Hanya yang berstatus terbit).
     */
    public function modul(string $idSiswa)
    {
        $siswa = Siswa::with(['kelas', 'jurusan'])->find($idSiswa);
        if (!$siswa) {
            return response()->json(['success' => false, 'message' => 'Siswa tidak ditemukan'], 404);
        }

        $idJurusan = $siswa->id_jurusan ?? ($siswa->kelas->id_jurusan ?? null);

        $moduls = ModulPraktik::terbit()
            ->when($idJurusan, function ($q) use ($idJurusan) {
                $q->where(function ($subQ) use ($idJurusan) {
                    $subQ->whereHas('kompetensi', function ($sub) use ($idJurusan) {
                        $sub->where('id_jurusan', $idJurusan);
                    })->orWhereHas('kompetensis', function ($sub) use ($idJurusan) {
                        $sub->where('kompetensi.id_jurusan', $idJurusan);
                    });
                });
            })
            ->with(['kompetensi', 'kompetensis', 'guru', 'versis', 'rubriks'])
            ->orderByDesc('id_modul_praktik')
            ->get()
            ->map(function ($m) {
                $arr = $m->toArray();
                $tingkat = strtoupper($m->kompetensi->tingkat_kelas ?? '');

                if (!empty($m->file_lembar_kerja)) {
                    $kategori = 'Job Sheet';
                } elseif ($tingkat === 'X') {
                    $kategori = 'Dasar Kejuruan';
                } else {
                    $kategori = 'Konsentrasi Keahlian';
                }

                $arr['kode'] = $m->kode_modul;
                $arr['judul_modul'] = $m->judul;
                $arr['kategori'] = $kategori;
                $arr['revisi'] = 'Versi ' . ($m->versi ?: '1.0');
                $arr['halaman'] = $m->rubriks->count() > 0
                    ? $m->rubriks->count() . ' Indikator Uji'
                    : 'Panduan Praktik';
                $arr['ukuran'] = $m->file_lembar_kerja ? 'Lampiran PDF' : 'Modul Digital';
                $arr['deskripsi'] = Str::limit(strip_tags($m->tujuan), 140);
                $arr['file_url'] = $m->file_lembar_kerja
                    ? url('storage/modul_praktik/' . $m->file_lembar_kerja)
                    : '';

                return $arr;
            });

        return response()->json([
            'success' => true,
            'data' => $moduls,
        ]);
    }

    /**
     * Nilai Praktik Siswa (Hanya sesi penilaian dengan status final).
     */
    public function nilai(string $idSiswa)
    {
        $nilais = NilaiPraktikSiswa::where('id_siswa', $idSiswa)
            ->whereHas('penilaian', function ($q) {
                $q->where('status', 'final');
            })
            ->with(['penilaian.modul.rubriks', 'penilaian.guru', 'penilaian.kompetensi'])
            ->orderBy('id_nilai_praktik', 'desc')
            ->get()
            ->map(function ($item) {
                $arr = $item->toArray();
                $penilaian = $item->penilaian;
                $modul = $penilaian->modul ?? null;
                $rubriks = $modul ? $modul->rubriks->keyBy('id_rubrik') : collect();

                $detailAspek = [];
                $rawIndikator = is_array($item->nilai_per_indikator) ? $item->nilai_per_indikator : [];
                foreach ($rawIndikator as $idRubrik => $skor) {
                    if ((string) $idRubrik === '0') {
                        $namaIndikator = 'Keterampilan & Hasil Praktik';
                    } else {
                        $namaIndikator = isset($rubriks[$idRubrik])
                            ? $rubriks[$idRubrik]->nama_indikator
                            : 'Indikator #' . $idRubrik;
                    }
                    $detailAspek[$namaIndikator] = floatval($skor);
                }

                $kkm = floatval($penilaian->kkm_saat_penilaian ?? 75);
                $nilaiAkhir = floatval($item->nilai_akhir);
                $perluRemedial = $item->status_kompetensi === 'belum_kompeten' || $nilaiAkhir < $kkm;

                $arr['judul'] = $modul->judul ?? ($penilaian->kompetensi->nama_kompetensi ?? 'Penilaian Praktik');
                $arr['kode_modul'] = $modul->kode_modul ?? 'MODUL';
                $arr['kkm'] = $kkm;
                $arr['tanggal'] = $penilaian && $penilaian->tanggal_penilaian
                    ? Carbon::parse($penilaian->tanggal_penilaian)->format('Y-m-d')
                    : ($item->created_at ? $item->created_at->format('Y-m-d') : '-');
                $arr['tanggal_formatted'] = $penilaian && $penilaian->tanggal_penilaian
                    ? Carbon::parse($penilaian->tanggal_penilaian)->translatedFormat('d M Y')
                    : '-';
                $arr['detail_aspek'] = $detailAspek;
                $arr['perlu_remedial'] = $perluRemedial;

                return $arr;
            });

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
                'tanggal' => $jurnal && $jurnal->tanggal ? Carbon::parse($jurnal->tanggal)->format('Y-m-d') : '',
                'tanggal_formatted' => $jurnal && $jurnal->tanggal ? Carbon::parse($jurnal->tanggal)->translatedFormat('d M Y') : '',
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
     * Peminjaman Alat Siswa (Daftar Pinjaman Aktif, Riwayat, & Katalog Alat Lab).
     */
    public function peminjaman(string $idSiswa)
    {
        $siswa = Siswa::with(['kelas.jurusan', 'jurusan'])->find($idSiswa);
        if (!$siswa) {
            return response()->json(['success' => false, 'message' => 'Siswa tidak ditemukan'], 404);
        }

        $idJurusan = $siswa->id_jurusan ?? ($siswa->kelas->id_jurusan ?? null);
        $jurusanObj = $siswa->jurusan ?? $siswa->kelas->jurusan ?? null;

        $list = PeminjamanAset::where('id_peminjam_siswa', $idSiswa)
            ->with(['aset.lab', 'petugas'])
            ->orderBy('id_peminjaman', 'desc')
            ->get();

        $sedangDipinjam = [];
        $riwayatPinjam = [];

        foreach ($list as $item) {
            $namaAset = $item->aset->nama_aset ?? 'Peralatan Praktik';
            $satuan = $item->aset->satuan ?? 'Unit';
            $namaLab = $item->aset->lab->nama_lab ?? 'Lab Kejuruan';
            $tglPinjam = $item->tanggal_pinjam ? Carbon::parse($item->tanggal_pinjam)->translatedFormat('d M Y') : '-';
            $batasKembali = $item->rencana_kembali ? Carbon::parse($item->rencana_kembali)->translatedFormat('d M Y') : '-';
            $tglKembali = $item->tanggal_kembali ? Carbon::parse($item->tanggal_kembali)->translatedFormat('d M Y') : $batasKembali;
            $kondisi = $item->status === 'kembali'
                ? ucfirst(str_replace('_', ' ', $item->kondisi_sesudah ?? $item->kondisi_sebelum ?? 'baik'))
                : ucfirst(str_replace('_', ' ', $item->kondisi_sebelum ?? 'baik'));

            $row = [
                'id' => $item->id_peminjaman,
                'nama' => $namaAset,
                'nama_alat' => $item->jumlah . ' ' . $satuan . ' ' . $namaAset,
                'kode' => $item->kode_peminjaman,
                'kode_aset' => $item->aset->kode_aset ?? '-',
                'sn' => $item->kode_peminjaman,
                'jumlah' => $item->jumlah . ' ' . $satuan,
                'lokasi' => $namaLab,
                'lab' => $namaLab,
                'tgl_pinjam' => $tglPinjam,
                'jam_pinjam' => $tglPinjam,
                'batas_kembali' => $batasKembali,
                'tgl_kembali' => $tglKembali,
                'jam_kembali' => $tglKembali,
                'status' => ucfirst($item->status),
                'kondisi' => $kondisi,
                'petugas' => $item->petugas->nama_guru ?? 'Instruktur Lab',
                'keperluan' => $item->keperluan ?: 'Praktikum Siswa',
            ];

            if (in_array($item->status, ['dipinjam', 'terlambat'])) {
                $sedangDipinjam[] = $row;
            } else {
                $riwayatPinjam[] = $row;
            }
        }

        // Ambil daftar aset alat yang tersedia untuk dipinjam pada lab jurusan siswa
        $asetTersedia = AsetLab::alat()
            ->where('stok_tersedia', '>', 0)
            ->where('kondisi', '!=', 'rusak_berat')
            ->when($idJurusan, function ($q) use ($idJurusan) {
                $q->whereHas('lab', function ($sub) use ($idJurusan) {
                    $sub->where('id_jurusan', $idJurusan);
                });
            })
            ->with('lab')
            ->orderBy('nama_aset', 'asc')
            ->get()
            ->map(function ($a) {
                return [
                    'id_aset' => $a->id_aset,
                    'kode_aset' => $a->kode_aset,
                    'nama_aset' => $a->nama_aset,
                    'stok_tersedia' => $a->stok_tersedia,
                    'satuan' => $a->satuan,
                    'lab' => $a->lab->nama_lab ?? 'Lab',
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'sedang_dipinjam' => $sedangDipinjam,
                'riwayat' => $riwayatPinjam,
                'aset_tersedia' => $asetTersedia,
                'nama_jurusan' => $jurusanObj->nama_jurusan ?? 'Kejuruan',
                'kode_jurusan' => $jurusanObj->kode_jurusan ?? 'KEJURUAN',
            ]
        ]);
    }

    /**
     * Pengajuan Peminjaman Alat Lab oleh Siswa dari Mobile App.
     */
    public function storePeminjaman(Request $request, string $idSiswa)
    {
        $siswa = Siswa::find($idSiswa);
        if (!$siswa) {
            return response()->json(['success' => false, 'message' => 'Siswa tidak ditemukan'], 404);
        }

        $request->validate([
            'id_aset' => 'required|exists:aset_lab,id_aset',
            'jumlah' => 'required|integer|min:1',
            'rencana_kembali' => 'nullable|date|after_or_equal:today',
            'keperluan' => 'required|string|max:255',
        ]);

        $aset = AsetLab::with('lab')->findOrFail($request->id_aset);
        $jumlah = intval($request->jumlah);

        if ($aset->stok_tersedia < $jumlah) {
            return response()->json([
                'success' => false,
                'message' => "Stok alat tidak mencukupi (Tersedia: {$aset->stok_tersedia} {$aset->satuan}).",
            ], 422);
        }

        DB::beginTransaction();
        try {
            $kodePeminjaman = 'PJM-' . date('Ym') . '-' . strtoupper(Str::random(5));
            $tglPinjam = Carbon::today()->toDateString();
            $rencanaKembali = $request->filled('rencana_kembali')
                ? Carbon::parse($request->rencana_kembali)->toDateString()
                : Carbon::today()->addDays(1)->toDateString();

            $peminjaman = PeminjamanAset::create([
                'kode_peminjaman' => $kodePeminjaman,
                'id_aset' => $aset->id_aset,
                'tipe_peminjam' => 'siswa',
                'id_peminjam_siswa' => $siswa->id_siswa,
                'jumlah' => $jumlah,
                'tanggal_pinjam' => $tglPinjam,
                'rencana_kembali' => $rencanaKembali,
                'kondisi_sebelum' => $aset->kondisi === 'rusak_ringan' ? 'rusak_ringan' : 'baik',
                'status' => 'dipinjam',
                'id_petugas' => $aset->lab->id_kepala_lab ?? null,
                'keperluan' => trim($request->keperluan),
            ]);

            $aset->decrement('stok_tersedia', $jumlah);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Peminjaman {$jumlah} {$aset->satuan} {$aset->nama_aset} berhasil dicatat dengan kode {$kodePeminjaman}.",
                'data' => $peminjaman,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses peminjaman: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Uji Kompetensi Keahlian (UKK) Siswa.
     */
    public function ukk(string $idSiswa)
    {
        $peserta = UkkPeserta::where('id_siswa', $idSiswa)
            ->with([
                'kegiatan.lab',
                'kegiatan.jurusan',
                'kegiatan.pengujis.guru',
                'kegiatan.units.kompetensi',
                'nilais.unit.kompetensi',
                'nilais.penguji.guru',
            ])
            ->latest('id_ukk_peserta')
            ->first();

        if (!$peserta || !$peserta->kegiatan) {
            return response()->json([
                'success' => true,
                'data' => null,
            ]);
        }

        $kegiatan = $peserta->kegiatan;
        $nilaiByUnit = $peserta->nilais->keyBy('id_ukk_unit');

        // Penguji Eksternal & Internal
        $pengujiEksternal = $kegiatan->pengujis->where('jenis', 'eksternal')->first();
        $pengujiInternal = $kegiatan->pengujis->where('jenis', 'internal')->first();
        $lembagaPenguji = $pengujiEksternal && $pengujiEksternal->instansi
            ? $pengujiEksternal->instansi
            : ($kegiatan->jurusan->nama_jurusan ?? 'LSP-P1 / SMK Wisata Indonesia');

        $daftarAsesor = $kegiatan->pengujis->map(function ($p) {
            return $p->nama_tampil;
        })->filter()->implode(', ');

        // Daftar Unit Kompetensi dari kegiatan UKK + nilai peserta (jika sudah dinilai)
        $unitKompetensiList = $kegiatan->units->map(function ($u) use ($nilaiByUnit) {
            $nilaiRow = $nilaiByUnit->get($u->id_ukk_unit);
            $sudahDinilai = $nilaiRow !== null;
            $isKompeten = $sudahDinilai && $nilaiRow->status === 'kompeten';

            $statusLabel = 'Menunggu Penilaian';
            if ($sudahDinilai) {
                $statusLabel = $isKompeten ? 'Kompeten' : 'Belum Kompeten';
            }

            return [
                'id_ukk_unit' => $u->id_ukk_unit,
                'kode_unit' => $u->kompetensi->kode_kompetensi ?? '-',
                'nama_unit' => $u->kompetensi->nama_kompetensi ?? 'Unit Kompetensi',
                'kkm' => floatval($u->kkm ?? 75),
                'bobot' => floatval($u->bobot ?? 100),
                'nilai' => $sudahDinilai ? floatval($nilaiRow->nilai) : null,
                'sudah_dinilai' => $sudahDinilai,
                'is_kompeten' => $isKompeten,
                'status' => $statusLabel,
                'penguji' => $nilaiRow && $nilaiRow->penguji ? $nilaiRow->penguji->nama_tampil : null,
                'catatan' => $nilaiRow->catatan_penguji ?? null,
                'jadwal_remedial' => $nilaiRow && $nilaiRow->jadwal_remedial
                    ? Carbon::parse($nilaiRow->jadwal_remedial)->translatedFormat('d M Y')
                    : null,
            ];
        })->values();

        $statusKelulusanMap = [
            'kompeten' => 'Lulus / Kompeten',
            'belum_kompeten' => 'Belum Kompeten',
            'belum_dinilai' => 'Terdaftar Peserta',
        ];

        $data = [
            'id_ukk_peserta' => $peserta->id_ukk_peserta,
            'nomor_peserta' => $peserta->nomor_peserta ?? '-',
            'status_kehadiran' => $peserta->status_kehadiran ?? 'hadir',
            'nilai_akhir' => floatval($peserta->nilai_akhir ?? 0),
            'status_kelulusan_raw' => $peserta->status_kelulusan,
            'status_kelulusan' => $statusKelulusanMap[$peserta->status_kelulusan] ?? 'Terdaftar Peserta',
            'catatan_umum' => $peserta->catatan_umum,
            'kegiatan' => [
                'id_ukk_kegiatan' => $kegiatan->id_ukk_kegiatan,
                'nama_kegiatan' => $kegiatan->nama_kegiatan,
                'penyelenggara' => $lembagaPenguji,
                'nomor_skema' => $kegiatan->nomor_sk ?: '-',
                'nomor_sk' => $kegiatan->nomor_sk ?: '-',
                'tempat_uji' => $kegiatan->lab->nama_lab ?? 'TUK Laboratorium Kejuruan',
                'asesor' => $daftarAsesor ?: ($pengujiInternal->guru->nama_guru ?? 'Tim Penguji UKK'),
                'tanggal_mulai' => $kegiatan->tanggal_mulai ? Carbon::parse($kegiatan->tanggal_mulai)->translatedFormat('d M Y') : '-',
                'tanggal_selesai' => $kegiatan->tanggal_selesai ? Carbon::parse($kegiatan->tanggal_selesai)->translatedFormat('d M Y') : '-',
                'tahun_ajaran' => $kegiatan->tahun_ajaran ?? '-',
                'status_kegiatan' => $kegiatan->status ?? 'berlangsung',
                'deskripsi' => $kegiatan->deskripsi,
            ],
            'unit_kompetensi' => $unitKompetensiList,
        ];

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Portofolio dan Sertifikat Siswa.
     */
    public function portofolio(string $idSiswa)
    {
        $siswa = Siswa::with(['kelas.jurusan', 'jurusan'])->find($idSiswa);
        $jurusanObj = $siswa ? ($siswa->jurusan ?? $siswa->kelas->jurusan ?? null) : null;

        $kategoriLabelMap = [
            'sertifikat' => 'Sertifikat',
            'proyek_karya' => 'Proyek & Karya',
            'prestasi_lomba' => 'Prestasi & Lomba',
        ];

        $statusLabelMap = [
            'disetujui' => 'Terverifikasi',
            'menunggu' => 'Menunggu Verifikasi',
            'ditolak' => 'Ditolak',
        ];

        $portofolio = PortofolioSiswa::where('id_siswa', $idSiswa)
            ->with(['kompetensi', 'verifikator'])
            ->orderBy('tanggal_kegiatan', 'desc')
            ->get()
            ->map(function ($p) use ($kategoriLabelMap, $statusLabelMap) {
                $arr = $p->toArray();
                $arr['kategori_kode'] = $p->kategori;
                $arr['kategori_label'] = $kategoriLabelMap[$p->kategori] ?? 'Sertifikat';
                $arr['status_verifikasi'] = $p->status_verifikasi;
                $arr['status_label'] = $statusLabelMap[$p->status_verifikasi] ?? 'Menunggu Verifikasi';
                $arr['tanggal_formatted'] = $p->tanggal_kegiatan
                    ? Carbon::parse($p->tanggal_kegiatan)->translatedFormat('d M Y')
                    : '-';
                $arr['nomor_sertifikat'] = $p->kompetensi
                    ? $p->kompetensi->kode_kompetensi . ' · ' . $p->kompetensi->nama_kompetensi
                    : 'Portofolio Vokasi #' . str_pad($p->id_portofolio, 3, '0', STR_PAD_LEFT);
                $arr['verifikator_nama'] = $p->verifikator->nama_guru ?? '-';
                $arr['file_url'] = $p->file_dokumen
                    ? url('storage/' . $p->file_dokumen)
                    : ($p->tautan_karya ?: null);
                return $arr;
            });

        return response()->json([
            'success' => true,
            'data' => $portofolio,
            'meta' => [
                'nama_jurusan' => $jurusanObj->nama_jurusan ?? 'Kompetensi Keahlian',
                'kode_jurusan' => $jurusanObj->kode_jurusan ?? 'KEJURUAN',
            ],
        ]);
    }

    /**
     * Tanda Kecakapan Kejuruan (TKK) & Rekap Capaian Kompetensi Siswa.
     */
    public function tkk(string $idSiswa)
    {
        $siswa = Siswa::with(['kelas.jurusan', 'jurusan'])->find($idSiswa);
        if (!$siswa) {
            return response()->json(['success' => false, 'message' => 'Siswa tidak ditemukan'], 404);
        }

        $idJurusan = $siswa->id_jurusan ?? ($siswa->kelas->id_jurusan ?? null);

        // 1. Ambil seluruh capaian kompetensi siswa dari buku induk capaian_siswa
        $capaians = CapaianSiswa::where('id_siswa', $idSiswa)
            ->with(['kompetensi', 'penilai'])
            ->orderByDesc('tanggal')
            ->orderByDesc('id_capaian')
            ->get();

        $lulusKompetensiIds = $capaians
            ->where('status', 'kompeten')
            ->pluck('id_kompetensi')
            ->filter()
            ->unique()
            ->toArray();

        // 2. Ambil rekor TKK siswa & master JenisTkk jurusan
        $rekorMap = SiswaTkk::where('id_siswa', $idSiswa)
            ->with(['jenisTkk.syarats', 'pemberi'])
            ->get()
            ->keyBy('id_jenis_tkk');

        $jenisTkks = JenisTkk::when($idJurusan, function ($q) use ($idJurusan) {
                $q->where('id_jurusan', $idJurusan);
            })
            ->with('syarats')
            ->orderBy('nomor_klaster', 'asc')
            ->orderBy('id_jenis_tkk', 'asc')
            ->get();

        $statusLabelMap = [
            'diberikan' => 'Sah & Berlaku',
            'memenuhi_syarat' => 'Memenuhi Syarat',
            'proses' => 'Dalam Proses',
            'belum_memenuhi' => 'Belum Memenuhi',
        ];

        $tkkList = $jenisTkks->map(function ($jenis) use ($rekorMap, $lulusKompetensiIds, $statusLabelMap) {
            $rekor = $rekorMap->get($jenis->id_jenis_tkk);
            $syaratIds = $jenis->syarats->pluck('id_kompetensi')->toArray();
            $totalSyarat = count($syaratIds);
            $lulusCount = count(array_intersect($syaratIds, $lulusKompetensiIds));

            $statusRaw = $rekor ? $rekor->status : 'belum_memenuhi';
            if ($statusRaw !== 'diberikan') {
                if ($totalSyarat > 0 && $lulusCount >= $totalSyarat) {
                    $statusRaw = 'memenuhi_syarat';
                } elseif ($lulusCount > 0) {
                    $statusRaw = 'proses';
                }
            }

            $tingkatLabel = 'Tingkat ' . ucfirst($jenis->tingkat ?? 'dasar');
            if ($jenis->nomor_klaster) {
                $tingkatLabel = "Klaster #{$jenis->nomor_klaster} • " . $tingkatLabel;
            }

            return [
                'id_jenis_tkk' => $jenis->id_jenis_tkk,
                'id_siswa_tkk' => $rekor->id_siswa_tkk ?? null,
                'nama_tkk' => $jenis->nama_tkk,
                'tingkat' => $tingkatLabel,
                'deskripsi' => $jenis->deskripsi,
                'status_raw' => $statusRaw,
                'status' => $statusLabelMap[$statusRaw] ?? 'Dalam Proses',
                'nomor_piagam' => $rekor->nomor_piagam ?? '-',
                'tanggal_perolehan' => $rekor && $rekor->tanggal_perolehan
                    ? Carbon::parse($rekor->tanggal_perolehan)->translatedFormat('d M Y')
                    : '-',
                'asesor' => $rekor && $rekor->pemberi ? $rekor->pemberi->nama_guru : 'Tim Penguji Kejuruan',
                'syarat_lulus' => $lulusCount,
                'syarat_total' => $totalSyarat,
                'file_url' => $rekor && $rekor->file_bukti ? url('storage/' . $rekor->file_bukti) : null,
                'catatan' => $rekor->catatan ?? null,
            ];
        })->values();

        // Jika siswa memiliki SiswaTkk dari luar daftar di atas, gabungkan
        foreach ($rekorMap as $idJenis => $rekor) {
            if (!$jenisTkks->contains('id_jenis_tkk', $idJenis) && $rekor->jenisTkk) {
                $tkkList->push([
                    'id_jenis_tkk' => $idJenis,
                    'id_siswa_tkk' => $rekor->id_siswa_tkk,
                    'nama_tkk' => $rekor->jenisTkk->nama_tkk,
                    'tingkat' => 'Tingkat ' . ucfirst($rekor->jenisTkk->tingkat ?? 'dasar'),
                    'deskripsi' => $rekor->jenisTkk->deskripsi,
                    'status_raw' => $rekor->status,
                    'status' => $statusLabelMap[$rekor->status] ?? 'Sah & Berlaku',
                    'nomor_piagam' => $rekor->nomor_piagam ?? '-',
                    'tanggal_perolehan' => $rekor->tanggal_perolehan
                        ? Carbon::parse($rekor->tanggal_perolehan)->translatedFormat('d M Y')
                        : '-',
                    'asesor' => $rekor->pemberi->nama_guru ?? 'Tim Penguji Kejuruan',
                    'syarat_lulus' => 0,
                    'syarat_total' => 0,
                    'file_url' => $rekor->file_bukti ? url('storage/' . $rekor->file_bukti) : null,
                    'catatan' => $rekor->catatan,
                ]);
            }
        }

        // 3. Format daftar Capaian Kompetensi Siswa (untuk Tab 2)
        $jenisCapaianMap = [
            'praktik' => 'Uji Praktik Modul',
            'ukk' => 'Uji Kompetensi (UKK)',
            'sertifikat' => 'Portofolio / Sertifikat',
            'tkk' => 'Pengesahan TKK',
        ];

        $capaianList = $capaians->map(function ($c) use ($jenisCapaianMap) {
            return [
                'id_capaian' => $c->id_capaian,
                'judul' => $c->kompetensi->nama_kompetensi ?? ($c->catatan ?: 'Capaian Kompetensi Kejuruan'),
                'kode_kompetensi' => $c->kompetensi->kode_kompetensi ?? strtoupper($c->jenis_capaian),
                'jenis' => $jenisCapaianMap[$c->jenis_capaian] ?? ucfirst($c->jenis_capaian),
                'skor' => round(floatval($c->nilai)),
                'nilai' => floatval($c->nilai),
                'kkm' => round(floatval($c->kkm ?? 75)),
                'status' => $c->status === 'kompeten' ? 'Kompeten' : 'Belum Kompeten',
                'is_kompeten' => $c->status === 'kompeten',
                'tanggal' => $c->tanggal ? Carbon::parse($c->tanggal)->translatedFormat('d M Y') : '-',
                'penilai' => $c->penilai->nama_guru ?? '-',
                'catatan' => $c->catatan,
            ];
        })->values();

        return response()->json([
            'success' => true,
            'data' => $tkkList,
            'capaian' => $capaianList,
        ]);
    }
}
