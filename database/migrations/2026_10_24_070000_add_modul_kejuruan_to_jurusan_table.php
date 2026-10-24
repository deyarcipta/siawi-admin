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
        if (Schema::hasTable('jurusan') && !Schema::hasColumn('jurusan', 'modul_kejuruan')) {
            Schema::table('jurusan', function (Blueprint $table) {
                $table->text('modul_kejuruan')->nullable()->after('aktif');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('jurusan') && Schema::hasColumn('jurusan', 'modul_kejuruan')) {
            Schema::table('jurusan', function (Blueprint $table) {
                $table->dropColumn('modul_kejuruan');
            });
        }
    }
};
