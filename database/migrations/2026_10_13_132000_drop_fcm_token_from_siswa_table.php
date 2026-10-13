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
        if (Schema::hasColumn('siswa', 'fcm_token')) {
            Schema::table('siswa', function (Blueprint $table) {
                $table->dropColumn('fcm_token');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasColumn('siswa', 'fcm_token')) {
            Schema::table('siswa', function (Blueprint $table) {
                $table->string('fcm_token')->nullable()->after('id_siswa');
            });
        }
    }
};
