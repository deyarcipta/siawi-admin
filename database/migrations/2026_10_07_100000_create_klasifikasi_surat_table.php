<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('klasifikasi_surat')) {
            Schema::create('klasifikasi_surat', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('kode', 50)->unique();
                $table->string('nama', 255);
                $table->text('keterangan')->nullable();
                $table->boolean('is_active')->default(true);
                $table->integer('urutan')->default(0);
                $table->timestamps();
            });

            // Initial seed data dari getKlasifikasiList
            $initialData = [
                ['kode' => 'TU', 'nama' => 'Tata Usaha / Administrasi Umum', 'urutan' => 1],
                ['kode' => 'SK-SISWA', 'nama' => 'Surat Keterangan Siswa Aktif', 'urutan' => 2],
                ['kode' => 'PKL', 'nama' => 'Surat Pengantar PKL / Magang', 'urutan' => 3],
                ['kode' => 'UND', 'nama' => 'Surat Undangan Rapat / Dinas', 'urutan' => 4],
                ['kode' => 'ST', 'nama' => 'Surat Tugas Guru & Pegawai', 'urutan' => 5],
                ['kode' => 'MUTASI', 'nama' => 'Surat Keterangan Pindah / Mutasi', 'urutan' => 6],
                ['kode' => 'EDR', 'nama' => 'Surat Edaran & Pemberitahuan Orang Tua', 'urutan' => 7],
                ['kode' => 'REK', 'nama' => 'Surat Rekomendasi', 'urutan' => 8],
                ['kode' => 'LAIN', 'nama' => 'Lainnya', 'urutan' => 9],
            ];

            $now = now();
            foreach ($initialData as &$item) {
                $item['is_active'] = true;
                $item['created_at'] = $now;
                $item['updated_at'] = $now;
            }

            DB::table('klasifikasi_surat')->insert($initialData);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('klasifikasi_surat');
    }
};
