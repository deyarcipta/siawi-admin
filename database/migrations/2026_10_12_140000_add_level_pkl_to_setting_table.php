<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('setting', 'level_pkl')) {
            Schema::table('setting', function (Blueprint $table) {
                $table->text('level_pkl')->nullable()->after('sp_settings');
            });
        }

        // Inisialisasi default level PKL: XI dan XII (tingkat umum PKL di SMK)
        DB::table('setting')->whereNull('level_pkl')->update([
            'level_pkl' => json_encode(['XI', 'XII'])
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('setting', function (Blueprint $table) {
            if (Schema::hasColumn('setting', 'level_pkl')) {
                $table->dropColumn('level_pkl');
            }
        });
    }
};
