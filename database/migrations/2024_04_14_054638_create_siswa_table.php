<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('siswa', function (Blueprint $table) {
            $table->bigIncrements('id_siswa');
            $table->string('nis');
            $table->string('nisn');
            $table->string('password');
            $table->string('nama_siswa');
            $table->string('id_level');
            $table->string('id_kelas');
            $table->string('id_jurusan');
            $table->string('foto');
            $table->string('tmpt_lahir');
            $table->string('tgl_lahir');
            $table->string('agama');
            $table->string('jenis_kelamin');
            $table->string('no_hp');
            $table->string('no_tlpn');
            $table->string('email');
            $table->string('alamat');
            $table->string('rt');
            $table->string('rw');
            $table->string('no_rumah');
            $table->string('kel');
            $table->string('kec');
            $table->string('prov');
            $table->string('kota');
            $table->string('nik_ayah');
            $table->string('nama_ayah');
            $table->string('tmpt_lahir_ayah');
            $table->string('tgl_lahir_ayah');
            $table->string('pendidikan_ayah');
            $table->string('pekerjaan_ayah');
            $table->string('penghasilan_ayah');
            $table->string('nik_ibu');
            $table->string('nama_ibu');
            $table->string('tmpt_lahir_ibu');
            $table->string('tgl_lahir_ibu');
            $table->string('pendidikan_ibu');
            $table->string('pekerjaan_ibu');
            $table->string('penghasilan_ibu');
            $table->string('nik_wali');
            $table->string('nama_wali');
            $table->string('tmpt_lahir_wali');
            $table->string('tgl_lahir_wali');
            $table->string('pendidikan_wali');
            $table->string('pekerjaan_wali');
            $table->string('penghasilan_wali');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa');
    }
};
