<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    use HasFactory;

    protected $table = 'berita';

    protected $guarded = [];

    protected $primaryKey = 'id_berita';

    public function guru()
    {
        return $this->belongsTo('App\Models\Guru', 'pembuat', 'id_guru');
    }
}
