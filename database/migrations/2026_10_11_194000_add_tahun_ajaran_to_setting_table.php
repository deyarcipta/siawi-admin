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
        if (!Schema::hasColumn('setting', 'tahun_ajaran')) {
            Schema::table('setting', function (Blueprint $table) {
                $table->string('tahun_ajaran', 50)->default('2026/2027')->nullable()->after('nama_sekolah');
            });
        }

        if (!Schema::hasColumn('setting', 'semester_aktif')) {
            Schema::table('setting', function (Blueprint $table) {
                $table->string('semester_aktif', 20)->default('Ganjil')->nullable()->after('tahun_ajaran');
            });
        }

        // Pastikan row setting ID 1 memiliki nilai default jika null
        DB::table('setting')->whereNull('tahun_ajaran')->update(['tahun_ajaran' => '2026/2027']);
        DB::table('setting')->whereNull('semester_aktif')->update(['semester_aktif' => 'Ganjil']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('setting', function (Blueprint $table) {
            if (Schema::hasColumn('setting', 'semester_aktif')) {
                $table->dropColumn('semester_aktif');
            }
            if (Schema::hasColumn('setting', 'tahun_ajaran')) {
                $table->dropColumn('tahun_ajaran');
            }
        });
    }
};
