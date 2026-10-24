<?php

namespace App\Models;

use App\Traits\FilterByJurusan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lab extends Model
{
    use HasFactory, FilterByJurusan;

    protected $table = 'lab';

    protected $primaryKey = 'id_lab';

    protected $guarded = [];

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'id_jurusan', 'id_jurusan');
    }

    public function asets()
    {
        return $this->hasMany(AsetLab::class, 'id_lab', 'id_lab');
    }
}
