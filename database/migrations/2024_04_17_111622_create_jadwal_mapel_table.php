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
            $table->string('id_mapel');
            $table->string('id_guru');
            $table->string('hari');
            $table->string('kelas');
            $table->string('jam_awal');
            $table->string('jam_akhir');
            $table->string('waktu_awal');
            $table->string('waktu_akhir');
            $table->timestamps();
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
