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
        // Temukan dan drop foreign key pada tabel absensi yang mereferensikan siswa
        $fks = DB::select("
            SELECT CONSTRAINT_NAME 
            FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
            WHERE TABLE_NAME = 'absensi' 
              AND COLUMN_NAME = 'id_siswa'
              AND REFERENCED_TABLE_NAME = 'siswa'
              AND TABLE_SCHEMA = DATABASE()
        ");

        foreach ($fks as $fk) {
            DB::statement("ALTER TABLE `absensi` DROP FOREIGN KEY `{$fk->CONSTRAINT_NAME}`");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absensi', function (Blueprint $table) {
            $table->foreign('id_siswa')->references('id_siswa')->on('siswa');
        });
    }
};
