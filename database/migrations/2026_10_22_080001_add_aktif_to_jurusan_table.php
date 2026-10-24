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
        if (Schema::hasTable('jurusan') && !Schema::hasColumn('jurusan', 'aktif')) {
            Schema::table('jurusan', function (Blueprint $table) {
                $table->boolean('aktif')->default(true)->after('nama_jurusan');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('jurusan') && Schema::hasColumn('jurusan', 'aktif')) {
            Schema::table('jurusan', function (Blueprint $table) {
                $table->dropColumn('aktif');
            });
        }
    }
};
