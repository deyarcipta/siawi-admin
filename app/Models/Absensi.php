<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensi';

    protected $guarded = [];

    protected $primaryKey = 'id_absensi';

    public function siswa()
    {
        return $this->belongsTo('App\Models\Siswa', 'id_siswa', 'id_siswa');
    }

    public function kelas()
    {
        return $this->belongsTo('App\Models\Kelas', 'id_kelas', 'id_kelas');
    }

    // public function level()
    // {
    //     return $this->belongsTo('App\Models\Level', 'id_level', 'id_level');
    // }

    // public function jurusan()
    // {
    //     return $this->belongsTo('App\Models\Jurusan', 'id_jurusan', 'id_jurusan');
    // }
}
