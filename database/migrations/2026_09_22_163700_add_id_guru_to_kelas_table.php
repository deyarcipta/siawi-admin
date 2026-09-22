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
        Schema::table('kelas', function (Blueprint $table) {
            if (!Schema::hasColumn('kelas', 'id_guru')) {
                $table->unsignedBigInteger('id_guru')->nullable()->after('kode_jurusan');
                $table->foreign('id_guru')->references('id_guru')->on('guru')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            if (Schema::hasColumn('kelas', 'id_guru')) {
                $table->dropForeign(['id_guru']);
                $table->dropColumn('id_guru');
            }
        });
    }
};
