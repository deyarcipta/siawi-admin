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
        Schema::create('capaian_siswa', function (Blueprint $table) {
            $table->id('id_capaian');
            $table->unsignedBigInteger('id_siswa');
            $table->unsignedBigInteger('id_kompetensi');
            $table->enum('jenis_capaian', ['praktik', 'ukk', 'tkk', 'sertifikat'])->default('praktik');
            $table->unsignedBigInteger('id_referensi')->nullable()->comment('ID sumber: id_penilaian_praktik, id_ukk, dsb.');
            $table->decimal('nilai', 5, 2)->default(0.00);
            $table->decimal('kkm', 5, 2)->default(75.00);
            $table->enum('status', ['kompeten', 'belum_kompeten'])->default('belum_kompeten');
            $table->date('tanggal');
            $table->unsignedBigInteger('id_penilai')->comment('Guru penilai atau penguji');
            $table->string('file_bukti')->nullable()->comment('File lembar kerja, dokumen nilai, atau sertifikat');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('id_siswa')->references('id_siswa')->on('siswa')->onDelete('cascade');
            $table->foreign('id_kompetensi')->references('id_kompetensi')->on('kompetensi')->onDelete('cascade');
            $table->foreign('id_penilai')->references('id_guru')->on('guru')->onDelete('cascade');

            $table->index(['id_siswa', 'id_kompetensi', 'jenis_capaian']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('capaian_siswa');
    }
};
