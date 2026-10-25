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
        Schema::table('jadwal_praktik', function (Blueprint $table) {
            $table->string('tipe_jadwal', 20)->default('rutin')->after('id_mapel'); // 'rutin', 'insidental'
            $table->string('frekuensi', 20)->default('setiap_minggu')->after('tipe_jadwal'); // 'setiap_minggu', 'minggu_ganjil', 'minggu_genap'
            $table->date('tanggal_khusus')->nullable()->after('frekuensi'); // Untuk jadwal insidental / dadakan
            $table->unsignedBigInteger('id_jadwal_mapel')->nullable()->after('tanggal_khusus');

            $table->foreign('id_jadwal_mapel')->references('id_jadwal')->on('jadwal_mapel')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal_praktik', function (Blueprint $table) {
            $table->dropForeign(['id_jadwal_mapel']);
            $table->dropColumn(['tipe_jadwal', 'frekuensi', 'tanggal_khusus', 'id_jadwal_mapel']);
        });
    }
};
