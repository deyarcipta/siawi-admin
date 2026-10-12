<?php

namespace App\Http\Controllers\SiswaController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PengajuanSuratPkl;
use App\Models\PengajuanSuratPklSiswa;
use App\Models\Siswa;
use App\Models\Perusahaan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PengajuanPklController extends Controller
{
    /**
     * Mengambil daftar perusahaan mitra untuk dropdown
     */
    
    /**
     * Mengambil daftar teman siswa satu level / tingkat untuk pilihan anggota kelompok PKL
     */
    public function getTemanSelevel(string $id_siswa)
    {
        $siswa = Siswa::find($id_siswa);
        if (!$siswa) {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Data siswa tidak ditemukan'
            ], 404);
        }

        $query = Siswa::where('id_siswa', '!=', $id_siswa)
            ->with(['kelas', 'jurusan']);

        if ($siswa->id_level) {
            $query->where('id_level', $siswa->id_level);
        }

        $teman = $query->orderBy('nama_siswa', 'asc')
            ->get(['id_siswa', 'nama_siswa', 'nis', 'id_kelas', 'id_jurusan'])
            ->map(function ($s) {
                return [
                    'id_siswa' => $s->id_siswa,
                    'nama_siswa' => $s->nama_siswa,
                    'nis' => $s->nis,
                    'kelas' => $s->kelas->nama_kelas ?? '-',
                    'program_keahlian' => $s->jurusan->nama_jurusan ?? '-',
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $teman,
            'message' => 'Berhasil mengambil daftar teman selevel'
        ]);
    }
    public function getPerusahaan()
    {
        $perusahaan = Perusahaan::orderBy('nama_perusahaan', 'asc')
            ->get(['id_perusahaan', 'nama_perusahaan', 'alamat_perusahaan', 'penanggung_jawab']);

        return response()->json([
            'success' => true,
            'data' => $perusahaan,
            'message' => 'Berhasil mengambil daftar mitra perusahaan'
        ]);
    }

    /**
     * Mengambil riwayat pengajuan surat PKL untuk siswa tertentu (via SIAWI App)
     */
    public function index(string $id_siswa)
    {
        $siswa = Siswa::find($id_siswa);
        if (!$siswa) {
            return response()->json([
                'success' => false,
                'message' => 'Data siswa tidak ditemukan'
            ], 404);
        }

        // Cari pengajuan di mana siswa adalah pemohon atau terdaftar sebagai anggota kelompok
        $pengajuanIds = PengajuanSuratPklSiswa::where('id_siswa', $id_siswa)
            ->orWhere('nis', $siswa->nis)
            ->pluck('id_pengajuan')
            ->toArray();

        $pengajuanList = PengajuanSuratPkl::where('id_siswa_pemohon', $id_siswa)
            ->orWhereIn('id_pengajuan', $pengajuanIds)
            ->with(['siswaList', 'perusahaan'])
            ->latest('id_pengajuan')
            ->get();

        $data = $pengajuanList->map(function ($item) {
            return [
                'id_pengajuan' => $item->id_pengajuan,
                'id' => $item->id_pengajuan,
                'kode_pengajuan' => $item->kode_pengajuan,
                'nomor_surat' => $item->nomor_surat,
                'tanggal_surat' => $item->tanggal_surat ? Carbon::parse($item->tanggal_surat)->translatedFormat('d F Y') : null,
                'nama_perusahaan' => $item->nama_perusahaan,
                'alamat_perusahaan' => $item->alamat_perusahaan,
                'ditujukan_kepada' => $item->ditujukan_kepada ?? $item->tujuan_nama,
                'jabatan_tujuan' => $item->jabatan_tujuan ?? $item->tujuan_jabatan,
                'tanggal_mulai' => $item->tanggal_mulai ? Carbon::parse($item->tanggal_mulai)->format('Y-m-d') : null,
                'tanggal_selesai' => $item->tanggal_selesai ? Carbon::parse($item->tanggal_selesai)->format('Y-m-d') : null,
                'periode_teks' => $item->periode_teks,
                'catatan_siswa' => $item->catatan_siswa ?? $item->catatan_pemohon,
                'status' => $item->status ?? 'menunggu',
                'catatan_bkk' => $item->catatan_bkk,
                'disetujui_pada' => $item->disetujui_pada ? Carbon::parse($item->disetujui_pada)->translatedFormat('d F Y H:i') : null,
                'tanggal_pengajuan' => $item->created_at ? $item->created_at->translatedFormat('d F Y') : null,
                'jumlah_siswa' => $item->siswaList ? $item->siswaList->count() : 1,
                'siswa' => $item->siswaList ? $item->siswaList->map(function ($s, $idx) {
                    return [
                        'no' => $idx + 1,
                        'id_siswa' => $s->id_siswa,
                        'nis' => $s->nis,
                        'nama_siswa' => $s->nama_siswa,
                        'program_keahlian' => $s->program_keahlian,
                        'kelas' => $s->kelas,
                    ];
                }) : [],
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => 'Berhasil mengambil riwayat pengajuan surat PKL'
        ]);
    }

    /**
     * Submit pengajuan surat permohonan PKL baru dari SIAWI App
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_siswa' => 'required',
            'nama_perusahaan' => 'required|string|max:255',
            'ditujukan_kepada' => 'nullable|string|max:150',
            'jabatan_tujuan' => 'nullable|string|max:150',
            'alamat_perusahaan' => 'nullable|string',
            'id_perusahaan' => 'nullable|integer',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'periode_teks' => 'nullable|string|max:255',
            'kontak_pemohon' => 'nullable|string|max:50',
            'catatan_siswa' => 'nullable|string|max:500',
            'anggota_siswa' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => $validator->errors()->first()
            ], 422);
        }

        $pemohon = Siswa::with(['jurusan', 'kelas'])->find($request->id_siswa);
        if (!$pemohon) {
            return response()->json([
                'success' => false,
                'message' => 'Data siswa pemohon tidak ditemukan'
            ], 404);
        }

        $mulai = Carbon::parse($request->tanggal_mulai);
        $selesai = Carbon::parse($request->tanggal_selesai);
        $periodeTeks = $request->periode_teks;
        if (empty($periodeTeks)) {
            $periodeTeks = $mulai->translatedFormat('d F Y') . ' s/d ' . $selesai->translatedFormat('d F Y');
        }

        // Generate kode unik pengajuan
        $prefix = 'PKL-' . date('Ym') . '-';
        $lastOrder = PengajuanSuratPkl::where('kode_pengajuan', 'LIKE', $prefix . '%')->count();
        $kodePengajuan = $prefix . str_pad($lastOrder + 1, 4, '0', STR_PAD_LEFT);

        DB::beginTransaction();
        try {
            $pengajuan = PengajuanSuratPkl::create([
                'kode_pengajuan' => $kodePengajuan,
                'nomor_surat' => null,
                'tanggal_surat' => now()->toDateString(),
                'hal' => 'Permohonan PKL',
                'id_siswa_pemohon' => $pemohon->id_siswa,
                'nama_pemohon' => $pemohon->nama_siswa ?? $pemohon->nama ?? 'Siswa SIAWI',
                'kontak_pemohon' => $request->kontak_pemohon ?? ($pemohon->no_hp ?? '-'),
                'id_perusahaan' => $request->id_perusahaan ?: null,
                'nama_perusahaan' => trim($request->nama_perusahaan),
                'ditujukan_kepada' => $request->ditujukan_kepada ? trim($request->ditujukan_kepada) : ($request->tujuan_nama ?? 'Pimpinan / HRD'),
                'jabatan_tujuan' => $request->jabatan_tujuan ? trim($request->jabatan_tujuan) : ($request->tujuan_jabatan ?? 'H.R & Learning Manager'),
                'alamat_perusahaan' => $request->alamat_perusahaan,
                'periode_teks' => $periodeTeks,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'catatan_siswa' => $request->catatan_siswa ?? $request->catatan_pemohon,
                'status' => 'menunggu',
            ]);

            // Selalu tambahkan pemohon sebagai siswa nomor 1
            $jurusanPemohon = $pemohon->jurusan->nama_jurusan ?? ($pemohon->kelas->jurusan->nama_jurusan ?? 'Pariwisata');
            $kelasPemohon = $pemohon->kelas->nama_kelas ?? '-';

            PengajuanSuratPklSiswa::create([
                'id_pengajuan' => $pengajuan->id_pengajuan,
                'id_siswa' => $pemohon->id_siswa,
                'nama_siswa' => $pemohon->nama_siswa ?? $pemohon->nama,
                'nis' => $pemohon->nis ?? $pemohon->nisn,
                'program_keahlian' => $jurusanPemohon,
                'kelas' => $kelasPemohon,
            ]);

            // Jika ada teman sekelompok yang didaftarkan
            if ($request->has('anggota_siswa') && is_array($request->anggota_siswa)) {
                foreach ($request->anggota_siswa as $anggota) {
                    $idAnggota = $anggota['id_siswa'] ?? null;
                    if ($idAnggota && $idAnggota == $pemohon->id_siswa) {
                        continue;
                    }

                    $namaSiswa = trim($anggota['nama_siswa'] ?? ($anggota['nama'] ?? ''));
                    $nis = $anggota['nis'] ?? null;
                    $keahlian = $anggota['program_keahlian'] ?? $jurusanPemohon;
                    $kelas = $anggota['kelas'] ?? $kelasPemohon;

                    if (empty($namaSiswa) && $idAnggota) {
                        $sObj = Siswa::with(['jurusan', 'kelas'])->find($idAnggota);
                        if ($sObj) {
                            $namaSiswa = $sObj->nama_siswa ?? $sObj->nama;
                            $nis = $sObj->nis;
                            $keahlian = $sObj->jurusan->nama_jurusan ?? $jurusanPemohon;
                            $kelas = $sObj->kelas->nama_kelas ?? $kelasPemohon;
                        }
                    }

                    if (!empty($namaSiswa)) {
                        PengajuanSuratPklSiswa::create([
                            'id_pengajuan' => $pengajuan->id_pengajuan,
                            'id_siswa' => $idAnggota,
                            'nama_siswa' => $namaSiswa,
                            'nis' => $nis,
                            'program_keahlian' => $keahlian,
                            'kelas' => $kelas,
                        ]);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => [
                    'id_pengajuan' => $pengajuan->id_pengajuan,
                    'kode_pengajuan' => $pengajuan->kode_pengajuan,
                    'nama_perusahaan' => $pengajuan->nama_perusahaan,
                    'status' => $pengajuan->status,
                    'tanggal_pengajuan' => Carbon::now()->translatedFormat('d F Y'),
                ],
                'message' => 'Pengajuan surat permohonan PKL berhasil dikirim! Menunggu persetujuan BKK & Hubin.'
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan pengajuan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Detail status pengajuan spesifik
     */
    public function show(string $id)
    {
        $pengajuan = PengajuanSuratPkl::with(['siswaList', 'perusahaan', 'penyetuju'])
            ->where('id_pengajuan', $id)
            ->first();

        if (!$pengajuan) {
            return response()->json([
                'success' => false,
                'message' => 'Data pengajuan tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id_pengajuan' => $pengajuan->id_pengajuan,
                'kode_pengajuan' => $pengajuan->kode_pengajuan,
                'nomor_surat' => $pengajuan->nomor_surat,
                'tanggal_surat' => $pengajuan->tanggal_surat ? Carbon::parse($pengajuan->tanggal_surat)->translatedFormat('d F Y') : null,
                'nama_perusahaan' => $pengajuan->nama_perusahaan,
                'ditujukan_kepada' => $pengajuan->ditujukan_kepada,
                'jabatan_tujuan' => $pengajuan->jabatan_tujuan,
                'alamat_perusahaan' => $pengajuan->alamat_perusahaan,
                'tanggal_mulai' => $pengajuan->tanggal_mulai,
                'tanggal_selesai' => $pengajuan->tanggal_selesai,
                'periode_teks' => $pengajuan->periode_teks,
                'status' => $pengajuan->status,
                'catatan_siswa' => $pengajuan->catatan_siswa,
                'catatan_bkk' => $pengajuan->catatan_bkk,
                'disetujui_pada' => $pengajuan->disetujui_pada ? Carbon::parse($pengajuan->disetujui_pada)->translatedFormat('d F Y H:i') : null,
                'nama_penyetuju' => $pengajuan->penyetuju->nama_guru ?? 'BKK / Hubin',
                'siswa' => $pengajuan->siswaList ? $pengajuan->siswaList->map(function ($s, $idx) {
                    return [
                        'no' => $idx + 1,
                        'id_siswa' => $s->id_siswa,
                        'nis' => $s->nis,
                        'nama_siswa' => $s->nama_siswa,
                        'program_keahlian' => $s->program_keahlian,
                        'kelas' => $s->kelas,
                    ];
                }) : [],
            ],
            'message' => 'Berhasil mengambil detail pengajuan'
        ]);
    }
}