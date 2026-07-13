<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PiketPembiasaanPagi extends Model
{
    use HasFactory;

    protected $table = 'piket_pembiasaan_pagi';

    protected $guarded = [];

    protected $primaryKey = 'id_pembiasaan';

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }
}
