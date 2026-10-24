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
        if (!Schema::hasTable('kompetensi')) {
            Schema::create('kompetensi', function (Blueprint $table) {
                $table->bigIncrements('id_kompetensi');
                $table->unsignedBigInteger('id_jurusan')->index();
                $table->string('kode_kompetensi');
                $table->string('nama_kompetensi');
                $table->string('tingkat', 20)->nullable(); // contoh: X, XI, XII
                $table->string('semester', 20)->nullable(); // contoh: 1, 2, Ganjil, Genap
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kompetensi');
    }
};
