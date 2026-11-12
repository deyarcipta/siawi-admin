<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('setting', function (Blueprint $table) {
            if (!Schema::hasColumn('setting', 'panel_video_loop')) {
                $table->integer('panel_video_loop')->default(2)->after('video_panel');
            }
            if (!Schema::hasColumn('setting', 'panel_data_duration')) {
                $table->integer('panel_data_duration')->default(120)->after('panel_video_loop');
            }
        });
    }

    public function down(): void
    {
        Schema::table('setting', function (Blueprint $table) {
            $cols = [];
            if (Schema::hasColumn('setting', 'panel_video_loop')) {
                $cols[] = 'panel_video_loop';
            }
            if (Schema::hasColumn('setting', 'panel_data_duration')) {
                $cols[] = 'panel_data_duration';
            }
            if (!empty($cols)) {
                $table->dropColumn($cols);
            }
        });
    }
};

