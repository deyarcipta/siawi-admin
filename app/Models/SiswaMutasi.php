<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiswaMutasi extends Model
{
    use HasFactory;

    protected $table = 'siswa_mutasi';
    protected $primaryKey = 'id_mutasi';
    protected $guarded = [];

    protected $casts = [
        'tanggal_mutasi' => 'date',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'id_jurusan', 'id_jurusan');
    }

    public function level()
    {
        return $this->belongsTo(Level::class, 'id_level', 'id_level');
    }

    public function orangTua()
    {
        return $this->belongsTo(OrangTua::class, 'id_orang_tua', 'id_orang_tua');
    }

    public function getBadgeClassAttribute(): string
    {
        return match ($this->jenis_mutasi) {
            'Pindah Sekolah' => 'badge-info',
            'Mengundurkan Diri' => 'badge-warning text-dark',
            'Dikeluarkan (DO)' => 'badge-danger',
            'Wafat' => 'badge-dark',
            default => 'badge-secondary',
        };
    }
}
