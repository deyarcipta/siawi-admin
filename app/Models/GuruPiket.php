<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuruPiket extends Model
{
    use HasFactory;

    protected $table = 'guru_piket';

    protected $guarded = [];

    protected $primaryKey = 'id_piket';

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }
}
