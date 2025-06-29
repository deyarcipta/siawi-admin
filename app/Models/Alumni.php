<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumni extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'alumni';              // Nama tabel
    protected $primaryKey = 'id_alumni';      // Ganti ini sesuai nama ID-mu

    protected $fillable = [
        'nama', 'nis', 'nisn', 'id_jurusan', 'tahun_lulus', 'foto',
        'status', 'tempat_lahir', 'tanggal_lahir', 'alamat', 'no_hp',
        'email', 'jenis_kelamin', 'agama'
    ];

    public function jurusan()
    {
        return $this->belongsTo('App\Models\Jurusan', 'id_jurusan', 'id_jurusan');
    }
}
