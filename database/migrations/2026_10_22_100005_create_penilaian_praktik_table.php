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
        Schema::create('penilaian_praktik', function (Blueprint $table) {
            $table->id('id_penilaian_praktik');
            $table->unsignedBigInteger('id_kelas');
            $table->unsignedBigInteger('id_modul_praktik');
            $table->unsignedBigInteger('id_kompetensi');
            $table->unsignedBigInteger('id_jadwal_praktik')->nullable();
            $table->unsignedBigInteger('id_guru')->comment('Guru penilai / penguji');
            $table->date('tanggal_penilaian');
            $table->decimal('kkm_saat_penilaian', 5, 2)->default(75.00);
            $table->enum('status', ['draft', 'final'])->default('draft')->comment('Hanya tampil ke siswa jika status final');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('id_kelas')->references('id_kelas')->on('kelas')->onDelete('cascade');
            $table->foreign('id_modul_praktik')->references('id_modul_praktik')->on('modul_praktik')->onDelete('cascade');
            $table->foreign('id_kompetensi')->references('id_kompetensi')->on('kompetensi')->onDelete('cascade');
            $table->foreign('id_jadwal_praktik')->references('id_jadwal_praktik')->on('jadwal_praktik')->onDelete('set null');
            $table->foreign('id_guru')->references('id_guru')->on('guru')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaian_praktik');
    }
};
