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
        if (!Schema::hasTable('penilaian_praktik_kompetensi')) {
            Schema::create('penilaian_praktik_kompetensi', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('id_penilaian_praktik');
                $table->unsignedBigInteger('id_kompetensi');
                $table->timestamps();

                $table->foreign('id_penilaian_praktik')
                    ->references('id_penilaian_praktik')
                    ->on('penilaian_praktik')
                    ->onDelete('cascade');

                $table->foreign('id_kompetensi')
                    ->references('id_kompetensi')
                    ->on('kompetensi')
                    ->onDelete('cascade');

                $table->unique(['id_penilaian_praktik', 'id_kompetensi'], 'penilaian_kompetensi_unique');
            });

            // Backfill data dari kolom penilaian_praktik.id_kompetensi ke pivot table
            if (Schema::hasTable('penilaian_praktik') && Schema::hasColumn('penilaian_praktik', 'id_kompetensi')) {
                $existing = DB::table('penilaian_praktik')
                    ->whereNotNull('id_kompetensi')
                    ->select('id_penilaian_praktik', 'id_kompetensi', 'created_at', 'updated_at')
                    ->get();

                foreach ($existing as $row) {
                    DB::table('penilaian_praktik_kompetensi')->insertOrIgnore([
                        'id_penilaian_praktik' => $row->id_penilaian_praktik,
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
        Schema::dropIfExists('penilaian_praktik_kompetensi');
    }
};

