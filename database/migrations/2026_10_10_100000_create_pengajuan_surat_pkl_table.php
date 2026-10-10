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
        if (!Schema::hasTable('pengajuan_surat_pkl')) {
            Schema::create('pengajuan_surat_pkl', function (Blueprint $table) {
                $table->bigIncrements('id_pengajuan');
                $table->string('kode_pengajuan')->unique();
                $table->string('nomor_surat')->nullable();
                $table->date('tanggal_surat')->nullable();
                $table->string('hal')->default('Permohonan PKL');
                
                // Perusahaan Tujuan
                $table->unsignedBigInteger('id_perusahaan')->nullable();
                $table->string('nama_perusahaan');
                $table->string('ditujukan_kepada')->nullable()->comment('Contoh: Ibu. Cathleen Abigail');
                $table->string('jabatan_tujuan')->nullable()->comment('Contoh: H.R & Learning Manager');
                $table->text('alamat_perusahaan')->nullable();
                
                // Periode PKL
                $table->string('periode_teks')->nullable()->comment('Contoh: Januari 2027 – Juni 2027 ( 6 bulan )');
                $table->date('tanggal_mulai')->nullable();
                $table->date('tanggal_selesai')->nullable();
                
                // Pemohon / Kontak Perwakilan Siswa
                $table->unsignedBigInteger('id_siswa_pemohon')->nullable();
                $table->string('nama_pemohon')->nullable();
                $table->string('kontak_pemohon')->nullable();
                $table->text('catatan_siswa')->nullable();
                
                // Tanda Tangan & Pengesahan BKK
                $table->string('nama_penandatangan')->default('Nanan Supriatna');
                $table->string('jabatan_penandatangan')->default('Koordinator Traning & Wakahubin');
                $table->string('kontak_penandatangan')->nullable()->default('082312261278');
                $table->string('file_stempel_ttd')->nullable();
                
                // Status & Moderasi
                $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
                $table->text('catatan_bkk')->nullable();
                $table->unsignedBigInteger('disetujui_oleh')->nullable();
                $table->timestamp('disetujui_pada')->nullable();
                
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('pengajuan_surat_pkl_siswa')) {
            Schema::create('pengajuan_surat_pkl_siswa', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('id_pengajuan');
                $table->unsignedBigInteger('id_siswa')->nullable();
                $table->string('nama_siswa');
                $table->string('nis')->nullable();
                $table->string('program_keahlian');
                $table->string('kelas')->nullable();
                $table->timestamps();

                $table->foreign('id_pengajuan')
                      ->references('id_pengajuan')
                      ->on('pengajuan_surat_pkl')
                      ->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_surat_pkl_siswa');
        Schema::dropIfExists('pengajuan_surat_pkl');
    }
};
