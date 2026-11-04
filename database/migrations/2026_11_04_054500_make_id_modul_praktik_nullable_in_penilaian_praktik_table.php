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
        Schema::table('penilaian_praktik', function (Blueprint $table) {
            // Drop foreign key first to allow modifying column
            $table->dropForeign(['id_modul_praktik']);
        });

        Schema::table('penilaian_praktik', function (Blueprint $table) {
            // Make id_modul_praktik nullable and re-attach foreign key with onDelete('set null')
            $table->unsignedBigInteger('id_modul_praktik')->nullable()->change();
            $table->foreign('id_modul_praktik')
                ->references('id_modul_praktik')
                ->on('modul_praktik')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penilaian_praktik', function (Blueprint $table) {
            $table->dropForeign(['id_modul_praktik']);
        });

        Schema::table('penilaian_praktik', function (Blueprint $table) {
            $table->unsignedBigInteger('id_modul_praktik')->nullable(false)->change();
            $table->foreign('id_modul_praktik')
                ->references('id_modul_praktik')
                ->on('modul_praktik')
                ->onDelete('cascade');
        });
    }
};

