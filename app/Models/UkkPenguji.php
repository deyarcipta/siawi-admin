<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UkkPenguji extends Model
{
    use HasFactory;

    protected $table = 'ukk_penguji';
    protected $primaryKey = 'id_ukk_penguji';
    protected $guarded = [];

    public function kegiatan()
    {
        return $this->belongsTo(UkkKegiatan::class, 'id_ukk_kegiatan', 'id_ukk_kegiatan');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }

    public function getNamaTampilAttribute()
    {
        if ($this->jenis === 'internal') {
            return $this->guru->nama_guru ?? 'Penguji Internal';
        }
        return $this->nama_penguji . ($this->instansi ? ' (' . $this->instansi . ')' : '');
    }
}
