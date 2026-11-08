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
        Schema::create('absensi_pkl', function (Blueprint $table) {
            $table->bigIncrements('id_absensi_pkl');
            $table->unsignedBigInteger('id_siswa_pkl')->index();
            $table->unsignedBigInteger('id_siswa')->index();
            $table->unsignedBigInteger('id_perusahaan')->index();
            $table->date('tanggal')->index();
            $table->time('jam_masuk')->nullable();
            $table->decimal('latitude_masuk', 10, 8)->nullable();
            $table->decimal('longitude_masuk', 11, 8)->nullable();
            $table->unsignedInteger('jarak_masuk_meter')->nullable();
            $table->string('foto_masuk')->nullable();
            $table->time('jam_pulang')->nullable();
            $table->decimal('latitude_pulang', 10, 8)->nullable();
            $table->decimal('longitude_pulang', 11, 8)->nullable();
            $table->unsignedInteger('jarak_pulang_meter')->nullable();
            $table->string('foto_pulang')->nullable();
            $table->enum('status', ['hadir', 'terlambat', 'izin', 'sakit'])->default('hadir')->index();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi_pkl');
    }
};
