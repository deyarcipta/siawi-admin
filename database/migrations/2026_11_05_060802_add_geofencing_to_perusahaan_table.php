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
            $table->decimal('latitude', 10, 8)->nullable()->after('alamat_perusahaan');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            $table->unsignedInteger('radius_meter')->default(50)->after('longitude');
            $table->time('jam_masuk')->nullable()->after('radius_meter');
            $table->time('jam_pulang')->nullable()->after('jam_masuk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perusahaan', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'radius_meter', 'jam_masuk', 'jam_pulang']);
        });
    }
};
