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
        Schema::create('absensi_guru', function (Blueprint $table) {
            $table->bigIncrements('id_absenguru');
            $table->unsignedBigInteger('id_guru');
            $table->string('hari');
            $table->string('tanggal');
            $table->string('jam_masuk')->nullable();
            $table->string('jam_pulang')->nullable();
            $table->string('kehadiran');
            $table->string('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('id_guru')->references('id_guru')->on('guru')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi_guru');
    }
};
