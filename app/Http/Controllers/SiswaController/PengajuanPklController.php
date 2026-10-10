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
            ->orWhere('nisn', $siswa->nisn)
            ->pluck('pengajuan_id')
            ->toArray();

        $pengajuanList = PengajuanSuratPkl::where('id_siswa_pemohon', $id_siswa)
            ->orWhereIn('id', $pengajuanIds)
            ->with(['siswaList', 'perusahaan'])
            ->latest()
            ->get();

        $data = $pengajuanList->map(function ($item) {
            return [
                'id' => $item->id,
                'kode_pengajuan' => $item->kode_pengajuan,
                'nomor_surat' => $item->nomor_surat,
                'tanggal_surat' => $item->tanggal_surat ? Carbon::parse($item->tanggal_surat)->translatedFormat('d F Y') : null,
                'nama_perusahaan' => $item->nama_perusahaan,
                'alamat_perusahaan' => $item->alamat_perusahaan,
                'tujuan_nama' => $item->tujuan_nama,
                'tujuan_jabatan' => $item->tujuan_jabatan,
                'periode_mulai' => $item->periode_mulai ? Carbon::parse($item->periode_mulai)->format('Y-m-d') : null,
                'periode_selesai' => $item->periode_selesai ? Carbon::parse($item->periode_selesai)->format('Y-m-d') : null,
                'periode_teks' => $item->periode_teks,
                'durasi_bulan' => $item->durasi_bulan,
                'catatan_pemohon' => $item->catatan_pemohon,
                'status' => $item->status,
                'catatan_admin' => $item->catatan_admin,
                'disetujui_pada' => $item->disetujui_pada ? Carbon::parse($item->disetujui_pada)->translatedFormat('d F Y H:i') : null,
                'tanggal_pengajuan' => $item->created_at ? $item->created_at->translatedFormat('d F Y') : null,
                'jumlah_siswa' => $item->siswaList->count(),
                'siswa' => $item->siswaList->map(function ($s, $idx) {
                    return [
                        'no' => $idx + 1,
                        'id_siswa' => $s->id_siswa,
                        'nisn' => $s->nisn,
                        'nama_siswa' => $s->nama_siswa,
                        'program_keahlian' => $s->program_keahlian,
                    ];
                }),
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
            'tujuan_nama' => 'nullable|string|max:150',
            'tujuan_jabatan' => 'nullable|string|max:150',
            'alamat_perusahaan' => 'nullable|string',
            'id_perusahaan' => 'nullable|integer',
            'periode_mulai' => 'required|date',
            'periode_selesai' => 'required|date|after_or_equal:periode_mulai',
            'durasi_bulan' => 'nullable|integer|min:1|max:24',
            'catatan_pemohon' => 'nullable|string|max:500',
            'anggota_siswa' => 'nullable|array', // Opsional array siswa tambahan jika berkelompok
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

        // Hitung durasi bulan jika tidak disertakan
        $mulai = Carbon::parse($request->periode_mulai);
        $selesai = Carbon::parse($request->periode_selesai);
        $durasiBulan = $request->durasi_bulan ?: max(1, (int) round($mulai->diffInDays($selesai) / 30));

        // Generate kode unik pengajuan
        $prefix = 'PKL-' . date('Ym') . '-';
        $lastOrder = PengajuanSuratPkl::where('kode_pengajuan', 'LIKE', $prefix . '%')->count();
        $kodePengajuan = $prefix . str_pad($lastOrder + 1, 4, '0', STR_PAD_LEFT);

        DB::beginTransaction();
        try {
            $pengajuan = PengajuanSuratPkl::create([
                'kode_pengajuan' => $kodePengajuan,
                'id_siswa_pemohon' => $pemohon->id_siswa,
                'nama_pemohon' => $pemohon->nama ?? 'Siswa SIAWI',
                'nisn_pemohon' => $pemohon->nisn ?? null,
                'id_perusahaan' => $request->id_perusahaan,
                'nama_perusahaan' => trim($request->nama_perusahaan),
                'tujuan_nama' => $request->tujuan_nama ? trim($request->tujuan_nama) : 'Pimpinan / HRD',
                'tujuan_jabatan' => $request->tujuan_jabatan ? trim($request->tujuan_jabatan) : 'H.R & Learning Manager',
                'alamat_perusahaan' => $request->alamat_perusahaan,
                'periode_mulai' => $request->periode_mulai,
                'periode_selesai' => $request->periode_selesai,
                'durasi_bulan' => $durasiBulan,
                'catatan_pemohon' => $request->catatan_pemohon,
                'status' => 'menunggu',
            ]);

            // Selalu tambahkan pemohon sebagai siswa nomor 1
            $jurusanPemohon = $pemohon->jurusan->nama_jurusan ?? ($pemohon->kelas->jurusan->nama_jurusan ?? 'Pariwisata');
            PengajuanSuratPklSiswa::create([
                'pengajuan_id' => $pengajuan->id,
                'id_siswa' => $pemohon->id_siswa,
                'nisn' => $pemohon->nisn,
                'nama_siswa' => $pemohon->nama,
                'program_keahlian' => $jurusanPemohon,
                'urutan' => 1,
            ]);

            // Jika ada teman sekelompok yang didaftarkan
            if ($request->has('anggota_siswa') && is_array($request->anggota_siswa)) {
                $urutan = 2;
                foreach ($request->anggota_siswa as $anggota) {
                    $idAnggota = $anggota['id_siswa'] ?? null;
                    if ($idAnggota && $idAnggota == $pemohon->id_siswa) {
                        continue; // Lewati jika duplikat pemohon
                    }

                    $namaSiswa = trim($anggota['nama_siswa'] ?? '');
                    if (empty($namaSiswa) && $idAnggota) {
                        $sObj = Siswa::with('jurusan')->find($idAnggota);
                        if ($sObj) {
                            $namaSiswa = $sObj->nama;
                            $nisn = $sObj->nisn;
                            $keahlian = $sObj->jurusan->nama_jurusan ?? 'Pariwisata';
                        }
                    } else {
                        $nisn = $anggota['nisn'] ?? null;
                        $keahlian = $anggota['program_keahlian'] ?? 'Pariwisata';
                    }

                    if (!empty($namaSiswa)) {
                        PengajuanSuratPklSiswa::create([
                            'pengajuan_id' => $pengajuan->id,
                            'id_siswa' => $idAnggota,
                            'nisn' => $nisn,
                            'nama_siswa' => $namaSiswa,
                            'program_keahlian' => $keahlian,
                            'urutan' => $urutan++,
                        ]);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $pengajuan->id,
                    'kode_pengajuan' => $pengajuan->kode_pengajuan,
                    'nama_perusahaan' => $pengajuan->nama_perusahaan,
                    'status' => $pengajuan->status,
                    'tanggal_pengajuan' => Carbon::now()->translatedFormat('d F Y'),
                ],
                'message' => 'Pengajuan surat permohonan PKL berhasil dikirim. Menunggu verifikasi BKK/Hubin.'
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
        $pengajuan = PengajuanSuratPkl::with(['siswaList', 'perusahaan', 'penyetuju'])->find($id);
        if (!$pengajuan) {
            return response()->json([
                'success' => false,
                'message' => 'Data pengajuan tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $pengajuan->id,
                'kode_pengajuan' => $pengajuan->kode_pengajuan,
                'nomor_surat' => $pengajuan->nomor_surat,
                'tanggal_surat' => $pengajuan->tanggal_surat ? Carbon::parse($pengajuan->tanggal_surat)->translatedFormat('d F Y') : null,
                'nama_perusahaan' => $pengajuan->nama_perusahaan,
                'tujuan_nama' => $pengajuan->tujuan_nama,
                'tujuan_jabatan' => $pengajuan->tujuan_jabatan,
                'alamat_perusahaan' => $pengajuan->alamat_perusahaan,
                'periode_mulai' => $pengajuan->periode_mulai,
                'periode_selesai' => $pengajuan->periode_selesai,
                'durasi_bulan' => $pengajuan->durasi_bulan,
                'periode_teks' => $pengajuan->periode_teks,
                'status' => $pengajuan->status,
                'catatan_pemohon' => $pengajuan->catatan_pemohon,
                'catatan_admin' => $pengajuan->catatan_admin,
                'disetujui_pada' => $pengajuan->disetujui_pada ? Carbon::parse($pengajuan->disetujui_pada)->translatedFormat('d F Y H:i') : null,
                'nama_penyetuju' => $pengajuan->penyetuju->nama ?? 'BKK / Hubin',
                'siswa' => $pengajuan->siswaList->map(function ($s, $idx) {
                    return [
                        'no' => $idx + 1,
                        'id_siswa' => $s->id_siswa,
                        'nisn' => $s->nisn,
                        'nama_siswa' => $s->nama_siswa,
                        'program_keahlian' => $s->program_keahlian,
                    ];
                }),
            ],
            'message' => 'Berhasil mengambil detail pengajuan'
        ]);
    }
}
