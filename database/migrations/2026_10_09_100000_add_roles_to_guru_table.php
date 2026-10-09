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
        if (!Schema::hasColumn('guru', 'roles')) {
            Schema::table('guru', function (Blueprint $table) {
                $table->json('roles')->nullable()->after('role');
            });
        }

        // Backfill existing rows: if roles is null, set roles = JSON array containing current role
        DB::table('guru')->whereNull('roles')->get()->each(function ($guru) {
            $currentRole = $guru->role ?: 'guru';
            DB::table('guru')->where('id_guru', $guru->id_guru)->update([
                'roles' => json_encode([$currentRole])
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('guru', 'roles')) {
            Schema::table('guru', function (Blueprint $table) {
                $table->dropColumn('roles');
            });
        }
    }
};
