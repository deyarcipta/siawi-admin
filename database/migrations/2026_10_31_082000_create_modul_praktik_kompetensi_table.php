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
        if (!Schema::hasTable('modul_praktik_kompetensi')) {
            Schema::create('modul_praktik_kompetensi', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('id_modul_praktik');
                $table->unsignedBigInteger('id_kompetensi');
                $table->timestamps();

                $table->foreign('id_modul_praktik')
                    ->references('id_modul_praktik')
                    ->on('modul_praktik')
                    ->onDelete('cascade');

                $table->foreign('id_kompetensi')
                    ->references('id_kompetensi')
                    ->on('kompetensi')
                    ->onDelete('cascade');

                $table->unique(['id_modul_praktik', 'id_kompetensi']);
            });

            // Migrasikan data relasi lama dari modul_praktik.id_kompetensi ke pivot table
            if (Schema::hasTable('modul_praktik') && Schema::hasColumn('modul_praktik', 'id_kompetensi')) {
                $existing = DB::table('modul_praktik')
                    ->whereNotNull('id_kompetensi')
                    ->select('id_modul_praktik', 'id_kompetensi', 'created_at', 'updated_at')
                    ->get();

                foreach ($existing as $row) {
                    DB::table('modul_praktik_kompetensi')->insertOrIgnore([
                        'id_modul_praktik' => $row->id_modul_praktik,
                        'id_kompetensi' => $row->id_kompetensi,
                        'created_at' => $row->created_at ?? now(),
                        'updated_at' => $row->updated_at ?? now(),
                    ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modul_praktik_kompetensi');
    }
};
