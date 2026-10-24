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
        if (!Schema::hasTable('jurnal_praktik')) {
            Schema::create('jurnal_praktik', function (Blueprint $table) {
                $table->bigIncrements('id_jurnal_praktik');
                $table->unsignedBigInteger('id_jadwal_praktik')->index();
                $table->date('tanggal');
                $table->unsignedBigInteger('modul_praktik_id')->nullable();
                $table->text('materi');
                $table->integer('jumlah_hadir')->default(0);
                $table->text('kendala')->nullable();
                $table->text('alat_dipakai')->nullable();
                $table->text('catatan')->nullable();
                $table->string('foto_praktik')->nullable();
                $table->enum('status_approval', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
                $table->unsignedBigInteger('approved_by')->nullable()->index();
                $table->timestamp('approved_at')->nullable();
                $table->text('catatan_kaprog')->nullable();
                $table->timestamps();

                // Foreign key constraints
                $table->foreign('id_jadwal_praktik')->references('id_jadwal_praktik')->on('jadwal_praktik')->onDelete('cascade');
                $table->foreign('approved_by')->references('id_guru')->on('guru')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jurnal_praktik');
    }
};
