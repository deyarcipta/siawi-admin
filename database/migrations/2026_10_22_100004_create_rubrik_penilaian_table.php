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
        Schema::create('rubrik_penilaian', function (Blueprint $table) {
            $table->id('id_rubrik');
            $table->unsignedBigInteger('id_modul_praktik')->nullable();
            $table->unsignedBigInteger('id_kompetensi');
            $table->string('nama_indikator');
            $table->text('deskripsi')->nullable();
            $table->decimal('bobot', 5, 2)->default(100.00)->comment('Bobot persentase atau proporsi nilai');
            $table->integer('urutan')->default(1);
            $table->timestamps();

            $table->foreign('id_modul_praktik')->references('id_modul_praktik')->on('modul_praktik')->onDelete('cascade');
            $table->foreign('id_kompetensi')->references('id_kompetensi')->on('kompetensi')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rubrik_penilaian');
    }
};
