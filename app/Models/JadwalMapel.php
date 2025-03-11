<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalMapel extends Model
{
    use HasFactory;

    protected $table = 'jadwal_mapel';

    protected $guarded = [];

    protected $primaryKey = 'id_jadwal';

    public function guru()
    {
        return $this->belongsTo('App\Models\Guru', 'id_guru', 'id_guru');
    }

    public function mapel()
    {
        return $this->belongsTo('App\Models\Mapel', 'id_mapel', 'id_mapel');
    }
}
