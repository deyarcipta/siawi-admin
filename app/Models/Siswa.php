<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;
// use Laravel\Sanctum\HasApiTokens;

class Siswa extends Model implements Authenticatable
{
    use AuthenticableTrait;
    
    // use HasApiTokens;

    use HasFactory;

    protected $table = 'siswa';

    protected $guarded = [];

    protected $primaryKey = 'id_siswa';

    public function kelas()
    {
        return $this->belongsTo('App\Models\Kelas', 'id_kelas', 'id_kelas');
    }

    public function jurusan()
    {
        return $this->belongsTo('App\Models\Jurusan', 'id_jurusan', 'id_jurusan');
    }

    public function level()
    {
        return $this->belongsTo('App\Models\Level', 'id_level', 'id_level');
    }

    public function absensi()
    {
        return $this->hasMany('App\Models\Absensi', 'id_siswa', 'id_siswa');
    }

    public function dokumen() {
        return $this->hasMany('App\Models\Dokumen', 'id_siswa', 'id_siswa');
    }

    public function rapot() {
        return $this->hasMany('App\Models\Rapot', 'id_siswa', 'id_siswa');
    }

    public function fcmTokens()
    {
        return $this->hasMany(SiswaFcmToken::class, 'id_siswa', 'id_siswa');
    }

    public function siswaPkl()
    {
        return $this->hasMany(\App\Models\SiswaPkl::class, 'id_siswa', 'id_siswa');
    }

    public function orangTua()
    {
        return $this->belongsTo(OrangTua::class, 'id_orang_tua', 'id_orang_tua');
    }

    /**
     * Dapatkan nomor HP tujuan notifikasi kehadiran & pesan penting berdasarkan urutan prioritas:
     * 1. No. HP Ibu
     * 2. No. HP Ayah
     * 3. No. HP Wali
     * 4. No. HP Siswa
     */
    public function getNoHpNotifikasiAttribute(): ?string
    {
        return $this->no_hp_ibu 
            ?: ($this->no_hp_ayah 
            ?: ($this->no_hp_wali 
            ?: ($this->no_hp && $this->no_hp !== '-' ? $this->no_hp : ($this->no_tlpn && $this->no_tlpn !== '-' ? $this->no_tlpn : null))));
    }
}

