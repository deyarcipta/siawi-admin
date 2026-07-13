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
        Schema::create('piket_pembiasaan_pagi', function (Blueprint $table) {
            $table->bigIncrements('id_pembiasaan');
            $table->unsignedBigInteger('id_guru');
            $table->string('hari');
            $table->string('waktu_awal');
            $table->string('waktu_akhir');
            $table->timestamps();

            $table->foreign('id_guru')->references('id_guru')->on('guru')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('piket_pembiasaan_pagi');
    }
};
