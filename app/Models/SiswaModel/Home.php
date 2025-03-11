<?php

namespace App\Models\SiswaModel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Home extends Model
{
    use HasFactory;

    protected $table = 'siswa';

    protected $guarded = [];

    protected $primaryKey = 'id_siswa';
}
