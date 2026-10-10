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

    /**
     * Helper pembuatan atau penautan akun orang tua untuk siswa secara otomatis.
     * Menggunakan hierarki prioritas kontak: 1. Ibu -> 2. Ayah -> 3. Wali -> 4. Siswa
     */
    public static function createOrLinkForSiswa(Siswa $siswa, ?string $customPhone = null, ?string $customName = null): self
    {
        $rawPhone = trim($customPhone ?? $siswa->no_hp_notifikasi ?? $siswa->no_hp ?? '');
        $cleanPhone = preg_replace('/[^0-9]/', '', $rawPhone);
        $hasValidPhone = strlen($cleanPhone) >= 9 && !in_array($cleanPhone, ['000000000', '123456789']);
        $username = 'ortu_' . trim($siswa->nis);

        $namaOrtu = null;
        if (!empty($customName)) {
            $namaOrtu = trim($customName);
        } elseif (!empty($siswa->nama_ibu) && trim($siswa->nama_ibu) !== '-') {
            $namaOrtu = trim($siswa->nama_ibu);
        } elseif (!empty($siswa->nama_ayah) && trim($siswa->nama_ayah) !== '-') {
            $namaOrtu = trim($siswa->nama_ayah);
        } elseif (!empty($siswa->nama_wali) && trim($siswa->nama_wali) !== '-') {
            $namaOrtu = trim($siswa->nama_wali);
        } else {
            $namaOrtu = 'Orang Tua dari ' . $siswa->nama_siswa;
        }

        $ortu = null;
        if ($hasValidPhone) {
            $ortu = self::where('no_hp', $cleanPhone)->first();
        }

        if (!$ortu) {
            $ortu = self::where('username', $username)->first();
        }

        if (!$ortu) {
            $ortu = self::create([
                'username' => $username,
                'password' => \Illuminate\Support\Facades\Hash::make('123456'),
                'nama_lengkap' => $namaOrtu,
                'no_hp' => $hasValidPhone ? $cleanPhone : null,
                'alamat' => $siswa->alamat ?? null,
                'status_aktif' => true,
            ]);
        } else {
            $updates = [];
            if (!empty($namaOrtu)) {
                $updates['nama_lengkap'] = trim($namaOrtu);
            }
            if ($hasValidPhone) {
                $updates['no_hp'] = $cleanPhone;
            }
            if (!empty($updates)) {
                $ortu->update($updates);
            }
        }

        $siswa->id_orang_tua = $ortu->id_orang_tua;
        $siswa->save();

        return $ortu;
    }
}
