<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiswaTkk extends Model
{
    use HasFactory;

    protected $table = 'siswa_tkk';
    protected $primaryKey = 'id_siswa_tkk';
    protected $guarded = [];

    protected $casts = [
        'tanggal_perolehan' => 'date',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa');
    }

    public function jenisTkk()
    {
        return $this->belongsTo(JenisTkk::class, 'id_jenis_tkk', 'id_jenis_tkk');
    }

    public function pemberi()
    {
        return $this->belongsTo(Guru::class, 'id_pemberi', 'id_guru');
    }
}
