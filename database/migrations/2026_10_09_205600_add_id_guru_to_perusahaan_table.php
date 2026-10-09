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
        Schema::table('perusahaan', function (Blueprint $table) {
            if (!Schema::hasColumn('perusahaan', 'id_guru')) {
                $table->unsignedBigInteger('id_guru')->nullable()->after('penanggung_jawab');
                $table->foreign('id_guru')->references('id_guru')->on('guru')->onDelete('set null');
            }
            $table->text('alamat_perusahaan')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perusahaan', function (Blueprint $table) {
            if (Schema::hasColumn('perusahaan', 'id_guru')) {
                $table->dropForeign(['id_guru']);
                $table->dropColumn('id_guru');
            }
            $table->string('alamat_perusahaan', 255)->change();
        });
    }
};
