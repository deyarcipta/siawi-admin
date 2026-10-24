<?php

namespace App\Models;

use App\Traits\FilterByJurusan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UkkKegiatan extends Model
{
    use HasFactory, FilterByJurusan;

    protected $table = 'ukk_kegiatan';
    protected $primaryKey = 'id_ukk_kegiatan';
    protected $guarded = [];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'id_jurusan', 'id_jurusan');
    }

    public function lab()
    {
        return $this->belongsTo(Lab::class, 'id_lab', 'id_lab');
    }

    public function pembuat()
    {
        return $this->belongsTo(Guru::class, 'created_by', 'id_guru');
    }

    public function pengujis()
    {
        return $this->hasMany(UkkPenguji::class, 'id_ukk_kegiatan', 'id_ukk_kegiatan');
    }

    public function units()
    {
        return $this->hasMany(UkkUnitKompetensi::class, 'id_ukk_kegiatan', 'id_ukk_kegiatan');
    }

    public function pesertas()
    {
        return $this->hasMany(UkkPeserta::class, 'id_ukk_kegiatan', 'id_ukk_kegiatan');
    }
}
