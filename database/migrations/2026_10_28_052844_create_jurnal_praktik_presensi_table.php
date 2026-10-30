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
        if (!Schema::hasTable('jurnal_praktik_presensi')) {
            Schema::create('jurnal_praktik_presensi', function (Blueprint $table) {
                $table->bigIncrements('id_presensi_praktik');
                $table->unsignedBigInteger('id_jurnal_praktik')->index();
                $table->unsignedBigInteger('id_siswa')->index();
                $table->enum('status', ['hadir', 'sakit', 'izin', 'alpa'])->default('hadir');
                $table->string('catatan')->nullable();
                $table->timestamps();

                $table->unique(['id_jurnal_praktik', 'id_siswa'], 'unik_jurnal_siswa');
                $table->foreign('id_jurnal_praktik')->references('id_jurnal_praktik')->on('jurnal_praktik')->onDelete('cascade');
                $table->foreign('id_siswa')->references('id_siswa')->on('siswa')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jurnal_praktik_presensi');
    }
};
