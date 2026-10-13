<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrangTuaFcmToken extends Model
{
    use HasFactory;

    protected $table = 'orang_tua_fcm_tokens';

    protected $fillable = [
        'id_orang_tua',
        'fcm_token',
        'token_hash',
        'device_name'
    ];

    /**
     * Relasi ke OrangTua
     */
    public function orangTua()
    {
        return $this->belongsTo(OrangTua::class, 'id_orang_tua', 'id_orang_tua');
    }
}
