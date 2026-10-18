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
        Schema::create('siswa_mutasi', function (Blueprint $table) {
            $table->bigIncrements('id_mutasi');
            $table->unsignedBigInteger('id_siswa_asal')->nullable()->index();

            // Informasi Mutasi
            $table->date('tanggal_mutasi');
            $table->string('jenis_mutasi')->default('Pindah Sekolah'); // Pindah Sekolah, Mengundurkan Diri, Dikeluarkan, Wafat, Lainnya
            $table->string('sekolah_tujuan')->nullable();
            $table->text('alasan_mutasi')->nullable();
            $table->string('dicatat_oleh')->nullable();

            // Data Akademik Terakhir Saat Mutasi
            $table->string('id_level')->nullable();
            $table->string('id_kelas')->nullable();
            $table->string('id_jurusan')->nullable();
            $table->string('kelas_terakhir')->nullable();
            $table->string('jurusan_terakhir')->nullable();
            $table->string('level_terakhir')->nullable();

            // Data Identitas Siswa
            $table->string('nis')->nullable();
            $table->string('nisn')->nullable();
            $table->string('password')->nullable();
            $table->string('nama_siswa');
            $table->string('foto')->nullable();
            $table->string('tmpt_lahir')->nullable();
            $table->string('tgl_lahir')->nullable();
            $table->string('agama')->nullable();
            $table->string('jenis_kelamin')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('no_tlpn')->nullable();
            $table->string('email')->nullable();
            $table->text('alamat')->nullable();
            $table->string('rt')->nullable();
            $table->string('rw')->nullable();
            $table->string('no_rumah')->nullable();
            $table->string('kel')->nullable();
            $table->string('kec')->nullable();
            $table->string('prov')->nullable();
            $table->string('kota')->nullable();

            // Data Orang Tua
            $table->unsignedBigInteger('id_orang_tua')->nullable();
            $table->string('nik_ayah')->nullable();
            $table->string('nama_ayah')->nullable();
            $table->string('tmpt_lahir_ayah')->nullable();
            $table->string('tgl_lahir_ayah')->nullable();
            $table->string('pendidikan_ayah')->nullable();
            $table->string('pekerjaan_ayah')->nullable();
            $table->string('penghasilan_ayah')->nullable();
            $table->string('no_hp_ayah')->nullable();

            $table->string('nik_ibu')->nullable();
            $table->string('nama_ibu')->nullable();
            $table->string('tmpt_lahir_ibu')->nullable();
            $table->string('tgl_lahir_ibu')->nullable();
            $table->string('pendidikan_ibu')->nullable();
            $table->string('pekerjaan_ibu')->nullable();
            $table->string('penghasilan_ibu')->nullable();
            $table->string('no_hp_ibu')->nullable();

            $table->string('nik_wali')->nullable();
            $table->string('nama_wali')->nullable();
            $table->string('tmpt_lahir_wali')->nullable();
            $table->string('tgl_lahir_wali')->nullable();
            $table->string('pendidikan_wali')->nullable();
            $table->string('pekerjaan_wali')->nullable();
            $table->string('penghasilan_wali')->nullable();
            $table->string('no_hp_wali')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa_mutasi');
    }
};
