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
        Schema::create('perawatan_aset_lab', function (Blueprint $table) {
            $table->id('id_perawatan');
            $table->unsignedBigInteger('id_aset');
            $table->enum('jenis', ['perawatan_rutin', 'perbaikan', 'kalibrasi'])->default('perawatan_rutin');
            $table->date('tanggal_jadwal');
            $table->date('tanggal_selesai')->nullable();
            $table->text('deskripsi_kendala');
            $table->text('tindakan_perbaikan')->nullable();
            $table->decimal('biaya', 12, 2)->default(0.00);
            $table->enum('status', ['terjadwal', 'dalam_pengerjaan', 'selesai', 'tidak_dapat_diperbaiki'])->default('terjadwal');
            $table->enum('kondisi_akhir', ['baik', 'rusak_ringan', 'rusak_berat'])->nullable();
            $table->string('teknisi', 150)->nullable();
            $table->unsignedBigInteger('id_pelapor')->nullable()->comment('Guru / Kaprog pelapor');
            $table->string('foto_sebelum')->nullable();
            $table->string('foto_sesudah')->nullable();
            $table->timestamps();

            $table->foreign('id_aset')->references('id_aset')->on('aset_lab')->onDelete('cascade');
            $table->foreign('id_pelapor')->references('id_guru')->on('guru')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perawatan_aset_lab');
    }
};
