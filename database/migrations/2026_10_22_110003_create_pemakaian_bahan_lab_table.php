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
        Schema::create('pemakaian_bahan_lab', function (Blueprint $table) {
            $table->id('id_pemakaian');
            $table->unsignedBigInteger('id_aset');
            $table->unsignedBigInteger('id_jadwal_praktik')->nullable();
            $table->unsignedBigInteger('id_modul_praktik')->nullable();
            $table->integer('jumlah_pakai');
            $table->integer('sisa_stok_saat_itu');
            $table->date('tanggal_pakai');
            $table->string('keperluan');
            $table->unsignedBigInteger('id_pengguna_guru')->nullable();
            $table->unsignedBigInteger('id_petugas')->nullable()->comment('Kaprog / Admin yang mencatat');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('id_aset')->references('id_aset')->on('aset_lab')->onDelete('cascade');
            $table->foreign('id_jadwal_praktik')->references('id_jadwal_praktik')->on('jadwal_praktik')->onDelete('set null');
            $table->foreign('id_modul_praktik')->references('id_modul_praktik')->on('modul_praktik')->onDelete('set null');
            $table->foreign('id_pengguna_guru')->references('id_guru')->on('guru')->onDelete('set null');
            $table->foreign('id_petugas')->references('id_guru')->on('guru')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemakaian_bahan_lab');
    }
};
