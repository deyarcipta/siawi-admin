<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokumen extends Model
{
    use HasFactory;

    protected $table = 'dokumen';

    protected $guarded = [];

    protected $fillable = [
        'id_siswa', 'jenis_dokumen', 'file_dokumen'
    ];

    protected $primaryKey = 'id_dokumen';

    public function siswa()
    {
        return $this->belongsTo('App\Models\Siswa', 'id_siswa', 'id_siswa');
    }

}
