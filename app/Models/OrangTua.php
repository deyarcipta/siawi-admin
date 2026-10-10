<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class OrangTua extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'orang_tua';

    protected $primaryKey = 'id_orang_tua';

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Relasi ke seluruh siswa (anak) yang dinaungi oleh akun orang tua ini.
     */
    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'id_orang_tua', 'id_orang_tua');
    }
}
