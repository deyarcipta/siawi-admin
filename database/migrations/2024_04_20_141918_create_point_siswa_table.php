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
        Schema::create('point_siswa', function (Blueprint $table) {
            $table->bigIncrements('id_point_siswa');
            $table->string('id_siswa');
            $table->string('id_point');
            $table->string('id_kelas');
            $table->string('id_jurusan');
            $table->string('id_guru');
            $table->string('role');
            $table->string('skor_point');
            $table->string('tanggal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('point_siswa');
    }
};
