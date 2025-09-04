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
        Schema::create('jadwal_mapel', function (Blueprint $table) {
            $table->bigIncrements('id_jadwal');

            // Kolom relasi
            $table->unsignedBigInteger('id_mapel');
            $table->unsignedBigInteger('id_guru');
            $table->unsignedBigInteger('id_kelas');

            $table->string('hari');
            $table->string('jam_awal');
            $table->string('jam_akhir');
            $table->string('waktu_awal');
            $table->string('waktu_akhir');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('id_mapel')->references('id_mapel')->on('mapel')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('id_guru')->references('id_guru')->on('guru')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('id_kelas')->references('id_kelas')->on('kelas')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_mapel');
    }
};
