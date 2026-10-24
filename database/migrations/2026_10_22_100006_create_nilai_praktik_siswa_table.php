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
        Schema::create('nilai_praktik_siswa', function (Blueprint $table) {
            $table->id('id_nilai_praktik');
            $table->unsignedBigInteger('id_penilaian_praktik');
            $table->unsignedBigInteger('id_siswa');
            $table->json('nilai_per_indikator')->nullable()->comment('JSON key: id_rubrik, value: skor');
            $table->decimal('nilai_awal', 5, 2)->default(0.00);
            $table->decimal('nilai_remedial', 5, 2)->nullable();
            $table->decimal('nilai_akhir', 5, 2)->default(0.00);
            $table->enum('status_kompetensi', ['kompeten', 'belum_kompeten'])->default('belum_kompeten');
            $table->boolean('is_remedial')->default(false);
            $table->text('catatan_guru')->nullable();
            $table->timestamps();

            $table->foreign('id_penilaian_praktik')->references('id_penilaian_praktik')->on('penilaian_praktik')->onDelete('cascade');
            $table->foreign('id_siswa')->references('id_siswa')->on('siswa')->onDelete('cascade');
            $table->unique(['id_penilaian_praktik', 'id_siswa']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_praktik_siswa');
    }
};
