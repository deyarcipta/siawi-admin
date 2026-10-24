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
        if (!Schema::hasTable('lab')) {
            Schema::create('lab', function (Blueprint $table) {
                $table->bigIncrements('id_lab');
                $table->string('kode_lab')->nullable();
                $table->string('nama_lab');
                $table->unsignedBigInteger('id_jurusan')->nullable()->index();
                $table->integer('kapasitas')->nullable()->default(0);
                $table->string('lokasi')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab');
    }
};
