<?php

namespace App\Models;

use App\Traits\FilterByJurusan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CapaianPembelajaran extends Model
{
    use HasFactory, FilterByJurusan;

    protected $table = 'capaian_pembelajaran';

    protected $primaryKey = 'id_cp';

    protected $guarded = [];

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'id_jurusan', 'id_jurusan');
    }

    /**
     * Relasi ke daftar Tujuan Pembelajaran (TP) / ATP anak.
     */
    public function tujuanPembelajarans()
    {
        return $this->hasMany(Kompetensi::class, 'id_cp', 'id_cp')
            ->orderBy('urutan_atp', 'asc')
            ->orderBy('kode_kompetensi', 'asc');
    }

    /**
     * Alias relasi tujuanPembelajarans.
     */
    public function tps()
    {
        return $this->tujuanPembelajarans();
    }

    public function kompetensis()
    {
        return $this->tujuanPembelajarans();
    }

    /**
     * Hitung total jam pelajaran (JP) seluruh TP di bawah elemen CP ini.
     */
    public function getTotalJpAttribute(): int
    {
        return (int) $this->tujuanPembelajarans->sum('alokasi_jp');
    }

    /**
     * Label Fase format cantik (Fase E - Kelas X atau Fase F - Kelas XI/XII).
     */
    public function getFaseLabelAttribute(): string
    {
        return match ($this->fase) {
            'E' => 'Fase E (Kelas X)',
            'F' => 'Fase F (Kelas XI & XII)',
            default => 'Fase ' . $this->fase,
        };
    }
}

