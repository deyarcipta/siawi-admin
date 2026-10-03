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
        Schema::table('absensi', function (Blueprint $table) {
            if (!Schema::hasColumn('absensi', 'tipe_masuk')) {
                $table->string('tipe_masuk', 20)->nullable()->default('manual')->after('keterangan');
            }
            if (!Schema::hasColumn('absensi', 'tipe_pulang')) {
                $table->string('tipe_pulang', 20)->nullable()->default(null)->after('tipe_masuk');
            }
        });

        // Backfill data lama agar kolom baru terisi dengan tepat
        try {
            // 1. Yang ada jam_pulang valid diisi tipe_pulang = 'mesin'
            DB::table('absensi')
                ->whereNotNull('jam_pulang')
                ->where('jam_pulang', '!=', '-')
                ->where('jam_pulang', '!=', '')
                ->update(['tipe_pulang' => 'mesin']);

            // 2. Yang keterangan mengandung kata kunci mesin
            DB::table('absensi')
                ->where(function($query) {
                    $query->where('keterangan', 'like', '%check in%')
                          ->orWhere('keterangan', 'like', '%face%')
                          ->orWhere('keterangan', 'like', '%presence%')
                          ->orWhere('keterangan', '=', 'Masuk')
                          ->orWhere('keterangan', '=', 'Check In');
                })
                ->update(['tipe_masuk' => 'mesin']);

            // 3. Yang dicatat oleh guru piket (terlambat)
            DB::table('absensi')
                ->where('keterangan', 'like', '%terlambat%')
                ->update(['tipe_masuk' => 'piket']);
        } catch (\Throwable $e) {
            // Log or ignore during migration if database is fresh
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absensi', function (Blueprint $table) {
            $table->dropColumn(['tipe_masuk', 'tipe_pulang']);
        });
    }
};
