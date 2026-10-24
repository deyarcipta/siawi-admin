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
        Schema::create('peminjaman_aset', function (Blueprint $table) {
            $table->id('id_peminjaman');
            $table->string('kode_peminjaman', 50)->unique();
            $table->unsignedBigInteger('id_aset');
            $table->enum('tipe_peminjam', ['guru', 'siswa'])->default('guru');
            $table->unsignedBigInteger('id_peminjam_guru')->nullable();
            $table->unsignedBigInteger('id_peminjam_siswa')->nullable();
            $table->integer('jumlah')->default(1);
            $table->date('tanggal_pinjam');
            $table->date('rencana_kembali');
            $table->date('tanggal_kembali')->nullable();
            $table->enum('kondisi_sebelum', ['baik', 'rusak_ringan'])->default('baik');
            $table->enum('kondisi_sesudah', ['baik', 'rusak_ringan', 'rusak_berat'])->nullable();
            $table->enum('status', ['dipinjam', 'kembali', 'terlambat', 'hilang'])->default('dipinjam');
            $table->unsignedBigInteger('id_petugas')->nullable()->comment('Kaprog / Admin yang memproses');
            $table->text('keperluan')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('id_aset')->references('id_aset')->on('aset_lab')->onDelete('cascade');
            $table->foreign('id_peminjam_guru')->references('id_guru')->on('guru')->onDelete('set null');
            $table->foreign('id_peminjam_siswa')->references('id_siswa')->on('siswa')->onDelete('set null');
            $table->foreign('id_petugas')->references('id_guru')->on('guru')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman_aset');
    }
};
