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
        // 1. Kegiatan UKK
        Schema::create('ukk_kegiatan', function (Blueprint $table) {
            $table->id('id_ukk_kegiatan');
            $table->string('nama_kegiatan');
            $table->unsignedBigInteger('id_jurusan');
            $table->unsignedBigInteger('id_lab')->nullable();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->string('tahun_ajaran', 20)->nullable();
            $table->enum('status', ['draft', 'berlangsung', 'selesai'])->default('draft');
            $table->string('nomor_sk', 100)->nullable()->comment('Nomor SK / Surat Tugas UKK');
            $table->text('deskripsi')->nullable();
            $table->unsignedBigInteger('created_by')->comment('ID Guru/Kaprog pembuat kegiatan');
            $table->timestamps();

            $table->foreign('id_jurusan')->references('id_jurusan')->on('jurusan')->onDelete('cascade');
            $table->foreign('id_lab')->references('id_lab')->on('lab')->onDelete('set null');
            $table->foreign('created_by')->references('id_guru')->on('guru')->onDelete('cascade');
        });

        // 2. Penguji UKK (Internal & Eksternal)
        Schema::create('ukk_penguji', function (Blueprint $table) {
            $table->id('id_ukk_penguji');
            $table->unsignedBigInteger('id_ukk_kegiatan');
            $table->enum('jenis', ['internal', 'eksternal'])->default('internal');
            $table->unsignedBigInteger('id_guru')->nullable()->comment('Relasi jika guru internal');
            $table->string('nama_penguji')->nullable()->comment('Nama penguji eksternal');
            $table->string('instansi')->nullable()->comment('DU-DI, LSP, atau instansi mitra');
            $table->string('jabatan_penguji', 50)->default('Penguji Utama');
            $table->string('no_telp', 30)->nullable();
            $table->timestamps();

            $table->foreign('id_ukk_kegiatan')->references('id_ukk_kegiatan')->on('ukk_kegiatan')->onDelete('cascade');
            $table->foreign('id_guru')->references('id_guru')->on('guru')->onDelete('cascade');
        });

        // 3. Unit Kompetensi yang diujikan dalam kegiatan
        Schema::create('ukk_unit_kompetensi', function (Blueprint $table) {
            $table->id('id_ukk_unit');
            $table->unsignedBigInteger('id_ukk_kegiatan');
            $table->unsignedBigInteger('id_kompetensi');
            $table->decimal('bobot', 5, 2)->default(100.00);
            $table->decimal('kkm', 5, 2)->default(75.00);
            $table->timestamps();

            $table->foreign('id_ukk_kegiatan')->references('id_ukk_kegiatan')->on('ukk_kegiatan')->onDelete('cascade');
            $table->foreign('id_kompetensi')->references('id_kompetensi')->on('kompetensi')->onDelete('cascade');
        });

        // 4. Peserta UKK (Siswa)
        Schema::create('ukk_peserta', function (Blueprint $table) {
            $table->id('id_ukk_peserta');
            $table->unsignedBigInteger('id_ukk_kegiatan');
            $table->unsignedBigInteger('id_siswa');
            $table->string('nomor_peserta', 50)->nullable();
            $table->enum('status_kehadiran', ['hadir', 'tidak_hadir'])->default('hadir');
            $table->decimal('nilai_akhir', 5, 2)->default(0.00);
            $table->enum('status_kelulusan', ['belum_dinilai', 'kompeten', 'belum_kompeten'])->default('belum_dinilai');
            $table->text('catatan_umum')->nullable();
            $table->timestamps();

            $table->foreign('id_ukk_kegiatan')->references('id_ukk_kegiatan')->on('ukk_kegiatan')->onDelete('cascade');
            $table->foreign('id_siswa')->references('id_siswa')->on('siswa')->onDelete('cascade');
            $table->unique(['id_ukk_kegiatan', 'id_siswa']);
        });

        // 5. Nilai Siswa per Unit Kompetensi
        Schema::create('ukk_nilai_siswa', function (Blueprint $table) {
            $table->id('id_ukk_nilai');
            $table->unsignedBigInteger('id_ukk_peserta');
            $table->unsignedBigInteger('id_ukk_unit');
            $table->unsignedBigInteger('id_ukk_penguji')->nullable();
            $table->decimal('nilai', 5, 2)->default(0.00);
            $table->enum('status', ['kompeten', 'belum_kompeten'])->default('belum_kompeten');
            $table->text('catatan_penguji')->nullable();
            $table->date('jadwal_remedial')->nullable();
            $table->decimal('nilai_remedial', 5, 2)->nullable();
            $table->enum('status_remedial', ['belum_remedial', 'lulus_remedial', 'tidak_lulus'])->nullable();
            $table->timestamps();

            $table->foreign('id_ukk_peserta')->references('id_ukk_peserta')->on('ukk_peserta')->onDelete('cascade');
            $table->foreign('id_ukk_unit')->references('id_ukk_unit')->on('ukk_unit_kompetensi')->onDelete('cascade');
            $table->foreign('id_ukk_penguji')->references('id_ukk_penguji')->on('ukk_penguji')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ukk_nilai_siswa');
        Schema::dropIfExists('ukk_peserta');
        Schema::dropIfExists('ukk_unit_kompetensi');
        Schema::dropIfExists('ukk_penguji');
        Schema::dropIfExists('ukk_kegiatan');
    }
};
