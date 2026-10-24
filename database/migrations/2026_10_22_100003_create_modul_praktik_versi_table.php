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
        Schema::create('modul_praktik_versi', function (Blueprint $table) {
            $table->id('id_versi');
            $table->unsignedBigInteger('id_modul_praktik');
            $table->string('versi', 20);
            $table->string('judul');
            $table->text('tujuan');
            $table->text('alat_bahan');
            $table->longText('langkah_kerja');
            $table->string('file_lembar_kerja')->nullable();
            $table->text('catatan_revisi')->nullable()->comment('Catatan perubahan pada versi ini');
            $table->unsignedBigInteger('diubah_oleh')->comment('ID guru yang merevisi');
            $table->timestamps();

            $table->foreign('id_modul_praktik')->references('id_modul_praktik')->on('modul_praktik')->onDelete('cascade');
            $table->foreign('diubah_oleh')->references('id_guru')->on('guru')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modul_praktik_versi');
    }
};
