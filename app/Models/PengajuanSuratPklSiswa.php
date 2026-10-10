<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanSuratPklSiswa extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_surat_pkl_siswa';

    protected $guarded = [];

    public function pengajuan()
    {
        return $this->belongsTo(PengajuanSuratPkl::class, 'id_pengajuan', 'id_pengajuan');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa');
    }
}
