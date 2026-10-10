<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanSuratPkl extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_surat_pkl';

    protected $primaryKey = 'id_pengajuan';

    protected $guarded = [];

    protected $casts = [
        'tanggal_surat' => 'date',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'disetujui_pada' => 'datetime',
    ];

    public function siswaList()
    {
        return $this->hasMany(PengajuanSuratPklSiswa::class, 'id_pengajuan', 'id_pengajuan');
    }

    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'id_perusahaan', 'id_perusahaan');
    }

    public function pemohon()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa_pemohon', 'id_siswa');
    }

    public function penyetuju()
    {
        return $this->belongsTo(Guru::class, 'disetujui_oleh', 'id_guru');
    }

    /**
     * Dapatkan label badge HTML untuk status pengajuan
     */
    public function getStatusBadgeAttribute(): string
    {
        switch ($this->status) {
            case 'disetujui':
                return '<span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle mr-1"></i> Disetujui (ACC)</span>';
            case 'ditolak':
                return '<span class="badge badge-danger px-2 py-1"><i class="fas fa-times-circle mr-1"></i> Ditolak</span>';
            default:
                return '<span class="badge badge-warning px-2 py-1 text-dark"><i class="fas fa-clock mr-1"></i> Menunggu ACC</span>';
        }
    }

    /**
     * Generate format nomor surat default jika belum ada
     * Contoh: 4/OJT/SMK-WI/IX/2026
     */
    public static function generateNomorSuratBaru(?string $tanggal = null): string
    {
        $date = $tanggal ? strtotime($tanggal) : time();
        $bulanAngka = (int)date('n', $date);
        $tahun = date('Y', $date);

        $romawi = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI',
            7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
        ];
        $bulanRomawi = $romawi[$bulanAngka] ?? 'X';

        $totalSuratTahunIni = self::whereYear('tanggal_surat', $tahun)
                                  ->whereNotNull('nomor_surat')
                                  ->count() + 1;

        return "{$totalSuratTahunIni}/OJT/SMK-WI/{$bulanRomawi}/{$tahun}";
    }

    /**
     * Format tanggal Indonesia untuk surat
     * Contoh: Jakarta, 30 September 2026
     */
    public function getTanggalSuratFormattedAttribute(): string
    {
        $bulanIndo = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $date = $this->tanggal_surat ?: now();
        $tgl = $date->format('j');
        $bln = $bulanIndo[(int)$date->format('n')] ?? $date->format('F');
        $thn = $date->format('Y');

        return "Jakarta, {$tgl} {$bln} {$thn}";
    }

    /**
     * Dapatkan nama kota atau "Tempat" yang ringkas dan profesional untuk tujuan surat resmi
     * Menghindari alamat jalan yang terlalu panjang pada lembar surat dinas
     */
    public function getKotaAtauTempatAttribute(): string
    {
        if (empty($this->alamat_perusahaan)) {
            return 'Tempat';
        }

        $alamat = trim($this->alamat_perusahaan);

        // Jika alamat sudah berupa nama kota / singkat (<= 35 karakter) tanpa jalan/rt/rw
        if (strlen($alamat) <= 35 && !preg_match('/(jl\.|jalan|rt|rw|no\.)/i', $alamat)) {
            return ltrim(preg_replace('/^di\s+/i', '', $alamat));
        }

        // Ekstrak pola "Kota [Nama]" atau "Kabupaten [Nama]"
        if (preg_match('/(?:Kota|Kabupaten|Kab\.?)\s+([A-Za-z\s]+?)(?:,|$|\.|\d)/i', $alamat, $m)) {
            $kota = trim($m[1]);
            if (!empty($kota)) {
                return $kota;
            }
        }

        // Ekstrak nama kota populer Indonesia
        if (preg_match('/(Jakarta\s*(?:Selatan|Pusat|Barat|Timur|Utara)?|Bogor|Depok|Tangerang\s*(?:Selatan)?|Bekasi|Bandung|Semarang|Surabaya|Yogyakarta|Solo)/i', $alamat, $m)) {
            return ucwords(strtolower(trim($m[1])));
        }

        return 'Tempat';
    }
}

