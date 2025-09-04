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
        Schema::table('jurnal_mengajar', function (Blueprint $table) {
            $table->unsignedBigInteger('id_jadwal')->nullable()->after('id_kelas');

            // foreign key ke tabel jadwal_mapel
            $table->foreign('id_jadwal')
                ->references('id_jadwal')
                ->on('jadwal_mapel')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jurnal_mengajar', function (Blueprint $table) {
            $table->dropForeign(['id_jadwal']);
            $table->dropColumn('id_jadwal');
        });
    }
};
