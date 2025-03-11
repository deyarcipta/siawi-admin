<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsensiGuru extends Model
{
     use HasFactory;

    protected $table = 'absensi_guru';

    protected $guarded = [];

    protected $primaryKey = 'id_absenguru';

    // protected $fillable = ['id_guru', 'hari', 'tanggal', 'jam_masuk', 'kehadiran', 'keterangan', 'jam_pulang'];

    public function guru()
    {
        return $this->belongsTo('App\Models\Guru', 'id_guru', 'id_guru');
    }
}
