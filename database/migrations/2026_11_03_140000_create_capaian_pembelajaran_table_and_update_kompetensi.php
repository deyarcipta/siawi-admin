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
        // 1. Buat Tabel Induk: Capaian Pembelajaran (Elemen CP)
        if (!Schema::hasTable('capaian_pembelajaran')) {
            Schema::create('capaian_pembelajaran', function (Blueprint $table) {
                $table->bigIncrements('id_cp');
                $table->unsignedBigInteger('id_jurusan')->index();
                $table->string('fase', 10)->default('F'); // Fase E (Kelas X) atau Fase F (Kelas XI/XII)
                $table->string('kode_elemen', 50); // Contoh: EL-01, CP-01
                $table->string('nama_elemen', 255); // Contoh: Perencanaan dan Pengalamatan Jaringan
                $table->text('deskripsi_cp'); // Narasi resmi Capaian Pembelajaran
                $table->timestamps();

                $table->foreign('id_jurusan')
                      ->references('id_jurusan')
                      ->on('jurusan')
                      ->onDelete('cascade');
            });
        }

        // 2. Modifikasi Tabel Anak: Kompetensi (Tujuan Pembelajaran / ATP)
        if (Schema::hasTable('kompetensi')) {
            Schema::table('kompetensi', function (Blueprint $table) {
                if (!Schema::hasColumn('kompetensi', 'id_cp')) {
                    $table->unsignedBigInteger('id_cp')->nullable()->after('id_jurusan')->index();
                    $table->foreign('id_cp')
                          ->references('id_cp')
                          ->on('capaian_pembelajaran')
                          ->onDelete('set null');
                }

                if (!Schema::hasColumn('kompetensi', 'urutan_atp')) {
                    $table->integer('urutan_atp')->default(1)->after('semester');
                }

                if (!Schema::hasColumn('kompetensi', 'alokasi_jp')) {
                    $table->integer('alokasi_jp')->nullable()->after('urutan_atp');
                }

                if (!Schema::hasColumn('kompetensi', 'materi_pokok')) {
                    $table->text('materi_pokok')->nullable()->after('alokasi_jp');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('kompetensi')) {
            Schema::table('kompetensi', function (Blueprint $table) {
                if (Schema::hasColumn('kompetensi', 'id_cp')) {
                    $table->dropForeign(['id_cp']);
                    $table->dropColumn('id_cp');
                }
                if (Schema::hasColumn('kompetensi', 'urutan_atp')) {
                    $table->dropColumn('urutan_atp');
                }
                if (Schema::hasColumn('kompetensi', 'alokasi_jp')) {
                    $table->dropColumn('alokasi_jp');
                }
                if (Schema::hasColumn('kompetensi', 'materi_pokok')) {
                    $table->dropColumn('materi_pokok');
                }
            });
        }

        Schema::dropIfExists('capaian_pembelajaran');
    }
};

