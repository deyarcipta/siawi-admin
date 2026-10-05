<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SuratKeluar extends Model
{
    use HasFactory;

    protected $table = 'surat_keluar';

    protected $primaryKey = 'id_surat_keluar';

    protected $guarded = [];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }

    public function creator()
    {
        return $this->belongsTo(Guru::class, 'created_by', 'id_guru');
    }

    public function getTanggalSuratFormattedAttribute(): string
    {
        if (!$this->tanggal_surat) return '-';
        try {
            return Carbon::parse($this->tanggal_surat)->locale('id')->translatedFormat('d F Y');
        } catch (\Exception $e) {
            return $this->tanggal_surat;
        }
    }

    /**
     * Konversi angka bulan (1-12) ke angka Romawi (I-XII).
     */
    public static function getRomawiMonth(int $month): string
    {
        $romawiMap = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI',
            7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
        ];
        return $romawiMap[$month] ?? 'I';
    }

    /**
     * Generate estimasi nomor surat berikutnya berdasarkan tahun & format standar sekolah.
     */
    public static function generateNextNumber(string $kodeKlasifikasi = 'TU', ?string $tanggal = null, string $kodeSekolah = 'SMK-WI'): array
    {
        $date = $tanggal ? Carbon::parse($tanggal) : Carbon::now();
        $tahun = (int) $date->format('Y');
        $bulanRomawi = self::getRomawiMonth((int) $date->format('n'));

        // Hitung nomor urut tertinggi pada tahun yang sama
        $maxUrut = self::where('tahun', $tahun)->max('no_urut') ?? 0;
        $nextUrut = $maxUrut + 1;
        $formattedUrut = str_pad((string) $nextUrut, 3, '0', STR_PAD_LEFT);

        // Format: 001/SK-SISWA/SMK-WI/X/2026
        $nomorSurat = "{$formattedUrut}/{$kodeKlasifikasi}/{$kodeSekolah}/{$bulanRomawi}/{$tahun}";

        return [
            'no_urut' => $nextUrut,
            'tahun' => $tahun,
            'bulan_romawi' => $bulanRomawi,
            'nomor_surat' => $nomorSurat,
        ];
    }
}
