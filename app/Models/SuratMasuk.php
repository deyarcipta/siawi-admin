<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SuratMasuk extends Model
{
    use HasFactory;

    protected $table = 'surat_masuk';

    protected $primaryKey = 'id_surat_masuk';

    protected $guarded = [];

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

    public function getTanggalDiterimaFormattedAttribute(): string
    {
        if (!$this->tanggal_diterima) return '-';
        try {
            return Carbon::parse($this->tanggal_diterima)->locale('id')->translatedFormat('d F Y');
        } catch (\Exception $e) {
            return $this->tanggal_diterima;
        }
    }

    /**
     * Generate nomor agenda surat masuk berikutnya berdasarkan tahun.
     */
    public static function generateNextAgenda(?string $tanggal = null): array
    {
        $date = $tanggal ? Carbon::parse($tanggal) : Carbon::now();
        $tahun = (int) $date->format('Y');

        $maxUrut = self::where('tahun', $tahun)->max('no_urut') ?? 0;
        $nextUrut = $maxUrut + 1;
        $formattedUrut = str_pad((string) $nextUrut, 3, '0', STR_PAD_LEFT);
        $nomorAgenda = "AGM/{$formattedUrut}/{$tahun}";

        return [
            'no_urut' => $nextUrut,
            'tahun' => $tahun,
            'nomor_agenda' => $nomorAgenda,
        ];
    }
}
