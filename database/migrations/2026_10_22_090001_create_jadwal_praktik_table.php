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
        if (!Schema::hasTable('jadwal_praktik')) {
            Schema::create('jadwal_praktik', function (Blueprint $table) {
                $table->bigIncrements('id_jadwal_praktik');
                $table->unsignedBigInteger('id_kelas')->index();
                $table->unsignedBigInteger('id_lab')->index();
                $table->unsignedBigInteger('id_guru')->index();
                $table->unsignedBigInteger('id_mapel')->index();
                $table->string('hari', 20); // Senin, Selasa, Rabu, Kamis, Jumat, Sabtu
                $table->time('jam_mulai');
                $table->time('jam_selesai');
                $table->string('tahun_ajaran', 50)->nullable();
                $table->string('semester', 20)->nullable();
                $table->text('alat_bahan')->nullable();
                $table->timestamps();

                // Foreign key constraints
                $table->foreign('id_kelas')->references('id_kelas')->on('kelas')->onDelete('cascade');
                $table->foreign('id_lab')->references('id_lab')->on('lab')->onDelete('cascade');
                $table->foreign('id_guru')->references('id_guru')->on('guru')->onDelete('cascade');
                $table->foreign('id_mapel')->references('id_mapel')->on('mapel')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_praktik');
    }
};
