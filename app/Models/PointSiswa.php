<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointSiswa extends Model
{
    use HasFactory;

    protected $table = 'point_siswa';

    protected $guarded = [];

    protected $primaryKey = 'id_point_siswa';

    public function point()
    {
        return $this->belongsTo('App\Models\Point', 'id_point', 'id_point');
    }

    public function guru()
    {
        return $this->belongsTo('App\Models\Guru', 'id_guru', 'id_guru');
    }

    public function kelas()
    {
        return $this->belongsTo('App\Models\Kelas', 'id_kelas', 'id_kelas');
    }

    public function siswa()
    {
        return $this->belongsTo('App\Models\Siswa', 'id_siswa', 'id_siswa');
    }

    /**
     * Accessor untuk mendapatkan tanggal kejadian yang sudah terformat rapi dan konsisten.
     */
    public function getTanggalFormattedAttribute(): string
    {
        if ($this->created_at) {
            return \Carbon\Carbon::parse($this->created_at)->locale('id')->translatedFormat('d F Y H:i');
        }

        if (!empty($this->attributes['tanggal'])) {
            return $this->attributes['tanggal'];
        }

        return '-';
    }
}
