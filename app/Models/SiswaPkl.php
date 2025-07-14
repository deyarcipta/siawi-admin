<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiswaPkl extends Model
{
    use HasFactory;

    protected $table = 'siswa_pkl';

    protected $guarded = [];

    protected $primaryKey = 'id_siswa_pkl';


    public function siswa()
    {
        return $this->belongsTo('App\Models\Siswa', 'id_siswa', 'id_siswa');
    }

    public function kelas()
    {
        return $this->belongsTo('App\Models\Kelas', 'id_kelas', 'id_kelas');
    }

    public function perusahaan()
    {
        return $this->belongsTo('App\Models\Perusahaan', 'id_perusahaan', 'id_perusahaan');
    }
}
