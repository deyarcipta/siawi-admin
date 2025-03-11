<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;
// use Laravel\Sanctum\HasApiTokens;

class Siswa extends Model implements Authenticatable
{
    use AuthenticableTrait;
    
    // use HasApiTokens;

    use HasFactory;

    protected $table = 'siswa';

    protected $guarded = [];

    protected $primaryKey = 'id_siswa';

    public function kelas()
    {
        return $this->belongsTo('App\Models\Kelas', 'id_kelas', 'id_kelas');
    }

    public function jurusan()
    {
        return $this->belongsTo('App\Models\Jurusan', 'id_jurusan', 'id_jurusan');
    }

    public function level()
    {
        return $this->belongsTo('App\Models\Level', 'id_level', 'id_level');
    }

    public function absensi()
    {
        return $this->belongsTo('App\Models\Absensi', 'id_siswa', 'id_siswa');
    }
    // public function jurusan()
    // {
    //     return $this->belongsTo('App\Models\Jurusan', 'kode_jurusan');
    // }
}

