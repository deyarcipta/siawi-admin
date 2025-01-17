<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $guarded = [];

    protected $primaryKey = 'id_kelas';

    public function absensis()
    {
        return $this->belongsTo('App\Models\Absensi', 'id_kelas', 'id_kelas');
    }
}
