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
        Schema::create('surat_keluar', function (Blueprint $table) {
            $table->bigIncrements('id_surat_keluar');
            $table->string('nomor_surat')->unique();
            $table->integer('no_urut');
            $table->integer('tahun');
            $table->string('kode_klasifikasi', 50)->default('TU');
            $table->string('nama_klasifikasi', 150)->nullable();
            $table->string('perihal');
            $table->string('tujuan_surat');
            $table->date('tanggal_surat');
            $table->unsignedBigInteger('id_siswa')->nullable();
            $table->unsignedBigInteger('id_guru')->nullable();
            $table->string('penandatangan', 150)->nullable();
            $table->string('file_lampiran')->nullable();
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->index(['tahun', 'no_urut']);
            $table->index('kode_klasifikasi');
        });

        Schema::create('surat_masuk', function (Blueprint $table) {
            $table->bigIncrements('id_surat_masuk');
            $table->string('nomor_agenda')->nullable();
            $table->integer('no_urut');
            $table->integer('tahun');
            $table->string('nomor_surat_asal');
            $table->string('asal_surat');
            $table->string('perihal');
            $table->date('tanggal_surat');
            $table->date('tanggal_diterima');
            $table->string('disposisi_kepada', 200)->nullable();
            $table->text('isi_disposisi')->nullable();
            $table->string('file_lampiran')->nullable();
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->index(['tahun', 'no_urut']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_masuk');
        Schema::dropIfExists('surat_keluar');
    }
};
