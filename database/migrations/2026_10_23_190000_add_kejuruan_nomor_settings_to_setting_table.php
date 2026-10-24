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
        if (Schema::hasTable('setting') && !Schema::hasColumn('setting', 'kejuruan_nomor_settings')) {
            Schema::table('setting', function (Blueprint $table) {
                $table->text('kejuruan_nomor_settings')->nullable()->after('level_pkl');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('setting') && Schema::hasColumn('setting', 'kejuruan_nomor_settings')) {
            Schema::table('setting', function (Blueprint $table) {
                $table->dropColumn('kejuruan_nomor_settings');
            });
        }
    }
};
