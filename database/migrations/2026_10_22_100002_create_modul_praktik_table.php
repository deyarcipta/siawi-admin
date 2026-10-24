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
        Schema::create('modul_praktik', function (Blueprint $table) {
            $table->id('id_modul_praktik');
            $table->unsignedBigInteger('id_kompetensi');
            $table->unsignedBigInteger('id_guru')->comment('Pembuat modul / Guru pengampu');
            $table->string('kode_modul', 50)->unique();
            $table->string('judul');
            $table->text('tujuan');
            $table->text('alat_bahan');
            $table->longText('langkah_kerja');
            $table->string('file_lembar_kerja')->nullable()->comment('Lampiran lembar kerja PDF / gambar');
            $table->string('versi', 20)->default('1.0');
            $table->enum('status', ['draft', 'terbit', 'arsip'])->default('draft');
            $table->timestamps();

            $table->foreign('id_kompetensi')->references('id_kompetensi')->on('kompetensi')->onDelete('cascade');
            $table->foreign('id_guru')->references('id_guru')->on('guru')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modul_praktik');
    }
};
