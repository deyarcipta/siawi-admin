<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modul extends Model
{
    use HasFactory;

    protected $table = 'modul';

    protected $guarded = [];

    protected $primaryKey = 'id_modul';

    public function level()
    {
        return $this->belongsTo('App\Models\Level', 'id_level', 'id_level');
    }

    public function jurusan()
    {
        return $this->belongsTo('App\Models\Jurusan', 'id_jurusan', 'id_jurusan');
    }

    public function mapel()
    {
        return $this->belongsTo('App\Models\Mapel', 'id_mapel', 'id_mapel');
    }

    public function guru()
    {
        return $this->belongsTo('App\Models\Guru', 'id_guru', 'id_guru');
    }
    
}
