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
        Schema::table('setting', function (Blueprint $table) {
            $table->string('pkl_attendance_mode')->default('radius_foto_lock')->after('level_pkl');
            $table->string('pkl_lock_scope')->default('periode_pkl')->after('pkl_attendance_mode');
            $table->string('pkl_foto_mode')->default('masuk_saja')->after('pkl_lock_scope');
            $table->boolean('pkl_auto_release')->default(true)->after('pkl_foto_mode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('setting', function (Blueprint $table) {
            $table->dropColumn(['pkl_attendance_mode', 'pkl_lock_scope', 'pkl_foto_mode', 'pkl_auto_release']);
        });
    }
};
