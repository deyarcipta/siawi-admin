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
        Schema::create('siswa_pkl', function (Blueprint $table) {
            $table->bigIncrements('id_siswa_pkl');
            $table->string('id_siswa');
            $table->string('id_kelas');
            $table->string('id_perusahaan');
            $table->string('tanggal_mulai');
            $table->string('tanggal_selesai');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa_pkls');
    }
};
