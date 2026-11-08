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
            if (Schema::hasColumn('perusahaan', 'jam_masuk')) {
                $table->dropColumn(['jam_masuk', 'jam_pulang']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perusahaan', function (Blueprint $table) {
            $table->time('jam_masuk')->nullable()->after('radius_meter');
            $table->time('jam_pulang')->nullable()->after('jam_masuk');
        });
    }
};

