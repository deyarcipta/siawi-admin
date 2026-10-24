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
        // 1. Master Jenis TKK (Tanda Kecakapan Khusus)
        Schema::create('jenis_tkk', function (Blueprint $table) {
            $table->id('id_jenis_tkk');
            $table->string('nama_tkk');
            $table->unsignedBigInteger('id_jurusan');
            $table->enum('tingkat', ['dasar', 'menengah', 'utama'])->default('dasar');
            $table->text('deskripsi')->nullable();
            $table->string('ikon', 100)->nullable();
            $table->timestamps();

            $table->foreign('id_jurusan')->references('id_jurusan')->on('jurusan')->onDelete('cascade');
        });

        // 2. Syarat Kompetensi per Jenis TKK
        Schema::create('tkk_syarat_kompetensi', function (Blueprint $table) {
            $table->id('id_tkk_syarat');
            $table->unsignedBigInteger('id_jenis_tkk');
            $table->unsignedBigInteger('id_kompetensi');
            $table->timestamps();

            $table->foreign('id_jenis_tkk')->references('id_jenis_tkk')->on('jenis_tkk')->onDelete('cascade');
            $table->foreign('id_kompetensi')->references('id_kompetensi')->on('kompetensi')->onDelete('cascade');
            $table->unique(['id_jenis_tkk', 'id_kompetensi']);
        });

        // 3. Rekor Pemberian TKK Siswa
        Schema::create('siswa_tkk', function (Blueprint $table) {
            $table->id('id_siswa_tkk');
            $table->unsignedBigInteger('id_siswa');
            $table->unsignedBigInteger('id_jenis_tkk');
            $table->enum('status', ['belum_memenuhi', 'proses', 'memenuhi_syarat', 'diberikan'])->default('proses');
            $table->date('tanggal_perolehan')->nullable();
            $table->unsignedBigInteger('id_pemberi')->nullable()->comment('Guru atau Penguji yang menerbitkan TKK');
            $table->string('nomor_piagam', 100)->nullable();
            $table->string('file_bukti')->nullable()->comment('Foto atau scan sertifikat');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('id_siswa')->references('id_siswa')->on('siswa')->onDelete('cascade');
            $table->foreign('id_jenis_tkk')->references('id_jenis_tkk')->on('jenis_tkk')->onDelete('cascade');
            $table->foreign('id_pemberi')->references('id_guru')->on('guru')->onDelete('set null');
            $table->unique(['id_siswa', 'id_jenis_tkk']);
        });

        // 4. Portofolio & Sertifikat Siswa
        Schema::create('portofolio_siswa', function (Blueprint $table) {
            $table->id('id_portofolio');
            $table->unsignedBigInteger('id_siswa');
            $table->unsignedBigInteger('id_kompetensi')->nullable()->comment('Terkait kompetensi tertentu');
            $table->enum('kategori', ['sertifikat', 'proyek_karya', 'prestasi_lomba'])->default('sertifikat');
            $table->string('judul');
            $table->string('penyelenggara')->nullable();
            $table->date('tanggal_kegiatan');
            $table->string('file_dokumen')->nullable()->comment('File PDF, JPG, PNG');
            $table->string('tautan_karya')->nullable()->comment('Link URL demo / GitHub / Drive');
            $table->text('deskripsi')->nullable();
            $table->enum('status_verifikasi', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
            $table->unsignedBigInteger('id_verifikator')->nullable()->comment('Guru yang memverifikasi');
            $table->text('catatan_verifikasi')->nullable();
            $table->timestamps();

            $table->foreign('id_siswa')->references('id_siswa')->on('siswa')->onDelete('cascade');
            $table->foreign('id_kompetensi')->references('id_kompetensi')->on('kompetensi')->onDelete('set null');
            $table->foreign('id_verifikator')->references('id_guru')->on('guru')->onDelete('set null');
        });

        // 5. Penyesuaian capaian_siswa: jadikan id_kompetensi nullable untuk sertifikat umum
        Schema::table('capaian_siswa', function (Blueprint $table) {
            $table->unsignedBigInteger('id_kompetensi')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portofolio_siswa');
        Schema::dropIfExists('siswa_tkk');
        Schema::dropIfExists('tkk_syarat_kompetensi');
        Schema::dropIfExists('jenis_tkk');
    }
};
