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
        Schema::create('alumni', function (Blueprint $table) {
            $table->bigIncrements('id_alumni');
            $table->string('nama');
            $table->string('nis');
            $table->string('nisn');
            $table->string('id_jurusan');
            $table->string('tahun_lulus');
            $table->string('foto')->nullable();
            $table->string('status')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->string('tanggal_lahir')->nullable();
            $table->string('alamat')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('email')->nullable();
            $table->string('jenis_kelamin')->nullable();
            $table->string('agama')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alumni');
    }
};
