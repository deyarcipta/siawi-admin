<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \App\Models\SiswaPkl
;
class Perusahaan extends Model
{
    use HasFactory;

    protected $table = 'perusahaan';

    protected $guarded = [];

    protected $primaryKey = 'id_perusahaan';

    public function siswaPkl()
    {
        return $this->hasMany(SiswaPkl::class, 'id_perusahaan', 'id_perusahaan');
    }
}
